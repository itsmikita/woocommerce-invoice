<?php

/**
 * Plugin Name: WooCommerce Invoice
 * Plugin URI: https://github.com/itsmikita
 * Description: The most basic Payment Gateway for WooCommerce.
 * Text Domain: woocommerce-invoice
 * Domain Path: /languages/
 * Author: Mikita Stankiewicz
 * Author URI: https://github.com/itsmikita
 * Version: 2.0.0
 */

class WooCommerce_Invoice
{
  /**
   * Constructor
   */
  public function __construct()
  {
    add_action( "plugins_loaded", [ $this, "load_plugin" ] );
    add_filter( "woocommerce_payment_gateways", [ $this, "add_payment_gateway" ] );
  }

  /**
   * Load Plugin Class
   *
   * @return void
   */
  public function load_plugin()
  {
    require_once "includes/class-wc-payment-gateway-invoice.php";

    load_plugin_textdomain( 'woocommerce-invoice', false, dirname( plugin_basename( __FILE__ ) ) . "/languages/" );
  }

  /**
   * Add Payment Gateway
   *
   * @param array $methods
   * @return array
   */
  public function add_payment_gateway( $methods )
  {
    $methods[] = "WC_Payment_Gateway_Invoice";
    
    return $methods;
  }
}

new WooCommerce_Invoice();
