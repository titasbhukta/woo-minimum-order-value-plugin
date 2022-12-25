<?php

/**
 * @link              https://titasbhukta.in
 * @since             1.0.0
 * @package           Woo_Minimum_Order_Value
 *
 * @wordpress-plugin
 * Plugin Name:       Woo Minimum Order Value
 * Plugin URI:        https://https://titasbhukta.in/
 * Description:       This is a plugin to add a minimum order amount for woocommerce. There is a settings page for the plugin where you can enter you desired minimum order amount. If the cart amount is lesser than that, it will show a customized notice which can be set in the plugin settings on the cart page and disable the user to checkout.
 * Version:           1.0.1
 * Author:            Titas Bhukta
 * Author URI:        https://titasbhukta.in
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-minimum-order-value
 * Domain Path:       /languages
 */


function woo_minimum_order_value_admin_menu() {    
	$page_title = 'Woo Minimum Order Value';   
	$menu_title = 'Woo Minimum Order Value';   
	$capability = 'manage_options';   
	$menu_slug  = 'woo-minimum-order-value';   
	$function   = 'woo_minimum_order_value_settings_page';   
	$icon_url   = 'dashicons-media-code';   
	$position   = 4;    
	add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );       
} 
add_action( 'admin_menu', 'woo_minimum_order_value_admin_menu' );

function woo_minimum_order_value_settings_init(  ) {
    register_setting( 'woo_minimum_order_value_settings', 'woo_minimum_order_value_validation_settings' );
    add_settings_section(
        'woo_minimum_order_value_validation_settings_section',
        __( 'Amount Settings', 'wordpress' ),
        'woo_minimum_order_value_validation_settings_section_callback',
        'woo_minimum_order_value_settings'
    );

    add_settings_field(
        'woo_minimum_order_value_amount_text_field',
        __( 'Minumum Order Amount For WooCommerce', 'wordpress' ),
        'woo_minimum_order_value_amount_text_field_render',
        'woo_minimum_order_value_settings',
        'woo_minimum_order_value_validation_settings_section'
    );
    add_settings_field(
        'woo_minimum_order_value_notice_text_field',
        __( 'Notice Text if Minumum Order Amount Not Reached', 'wordpress' ),
        'woo_minimum_order_value_notice_text_field_render',
        'woo_minimum_order_value_settings',
        'woo_minimum_order_value_validation_settings_section'
    );

}
add_action( 'admin_init', 'woo_minimum_order_value_settings_init' );

function woo_minimum_order_value_amount_text_field_render() {
    $options = get_option( 'woo_minimum_order_value_validation_settings' );
    ?>
    <input type='text' name='woo_minimum_order_value_validation_settings[woo_minimum_order_value_amount_text_field]' value='<?php echo $options['woo_minimum_order_value_amount_text_field']; ?>'>
    <?php
}

function woo_minimum_order_value_notice_text_field_render() {
    $options = get_option( 'woo_minimum_order_value_validation_settings' );
    ?>
    <input type='text' name='woo_minimum_order_value_validation_settings[woo_minimum_order_value_notice_text_field]' value='<?php echo $options['woo_minimum_order_value_notice_text_field']; ?>'>
    <?php
}

function woo_minimum_order_value_validation_settings_section_callback() {
    echo __( 'Enter the details to setup minimum order amount for woocommerce', 'wordpress' );
}

function woo_minimum_order_value_settings_page() {
    ?>
    <form action='options.php' method='post'>

        <h1>Woo Minimum Order Value Settings</h1>
		<br>
        <?php
        settings_fields( 'woo_minimum_order_value_settings' );
        do_settings_sections( 'woo_minimum_order_value_settings' );
        submit_button();
        ?>

    </form>
    <?php
}


add_action( 'woocommerce_check_cart_items', 'checkpoint_woo_minimum_order_amount' );
function checkpoint_woo_minimum_order_amount() {
    $options = get_option( 'woo_minimum_order_value_validation_settings' );
    $woo_minimum_order_value_amount = $options['woo_minimum_order_value_amount_text_field'];
    $woo_minimum_order_value_notice = $options['woo_minimum_order_value_notice_text_field'];
    $cart_subtotal = WC()->cart->subtotal;
    if( $cart_subtotal < $woo_minimum_order_value_amount  ) {
        wc_add_notice( '<strong>' . sprintf( $woo_minimum_order_value_notice ) . '</strong>', 'error' );
    }
}

?>