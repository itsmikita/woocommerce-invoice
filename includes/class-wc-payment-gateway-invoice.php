<?php

class WC_Payment_Gateway_Invoice extends WC_Payment_Gateway
{
  /**
   * Constructor
   */
  public function __construct()
  {
    $this->id = "invoice";
    $this->icon = plugins_url( "assets/images/icon-invoice.png", dirname( __FILE__ ) );
    $this->method_title = __( "Invoice", 'woocommerce-invoice' );
    $this->method_description = __( "Pay by Invoice", 'woocommerce-invoice' );
    $this->init_form_fields();
    $this->init_settings();
    $this->enabled = $this->get_option( 'enabled' );
    $this->title  = $this->get_option( 'title' );
    $this->description = $this->get_option( 'description' );
    add_action(
      "woocommerce_update_options_payment_gateways_{$this->id}",
      [ $this, "process_admin_options" ]
    );
  }

  /**
   * Gateway Settings Fields
   */
  public function init_form_fields()
  {
    $this->form_fields = [
      'enabled' => [
        'title' => __( "Enable/Disable", 'woocommerce-invoice' ),
        'type' => "checkbox",
        'label' => __( "Enable Invoice", 'woocommerce-invoice' ),
        'default' => "yes"
      ],
      'title' => [
        'title' => __( "Title", 'woocommerce-invoice' ),
        'type' => "text",
        'description' => __( "This controls the description the customer sees in checkout.", 'woocommerce-invoice' ),
        'default' => __( "Invoice", 'woocommerce-invoice' ),
        'desc_tip' => true
      ],
      'description' => [
        'title' => __( "Description", 'woocommerce-invoice' ),
        'type' => "textarea",
        'default' => ""
      ]
    ];
  }

  /**
   * Output the gateway settings screen.
   */
  public function admin_options()
  {
    // echo '<h2>' . esc_html( $this->get_method_title() );
    // wc_back_link( __( 'Return to payments', 'woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=checkout' ) );
    // echo '</h2>';
    // echo wp_kses_post( wpautop( $this->get_method_description() ) );
    parent::admin_options();
  }

  /**
   * Populate Gateway Settings
   */
  public function init_settings()
  {
    parent::init_settings();
    $this->enabled = ! empty( $this->settings['enabled'] ) && 'yes' === $this->settings['enabled'] ? 'yes' : 'no';
    // $this->title = $this->settings['title'];
    // $this->description = $this->settings['description'];
  }

  /**
   * Process Payment
   * 
   * @param $order_id
   */
  public function process_payment( $order_id )
  {
    global $woocommerce;
    $order = new WC_Order( $order_id );
    $order->update_status( "on-hold", __( "Waiting for the invoice to be paid.", 'woocommerce-invoice' ) );
    $woocommerce->cart->empty_cart();
    return [
      'result' => "success",
      'redirect' => $this->get_return_url( $order )
    ];
  }
}