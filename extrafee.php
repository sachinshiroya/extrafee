<?php
   /*
   Plugin Name: Extra Fee
   Plugin URI: http://my-plugin.com
   description: Add Extra fee on Product
  a plugin to create extra fee on products
   Version: 1.0
   Author: sachi Patel
   Author URI: http://my-plugin.com
   
   */


include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
  // some code
   add_action('admin_menu', 'extra_fee_product_menu'); // Admin Menu
   add_action( 'woocommerce_cart_calculate_fees', 'woo_add_cart_fee' ); // Calculate cart Fee
   add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' ); // Load Css and Js
}

register_activation_hook( __FILE__, 'create_plugin_database_table' ); // Create a table if doest not exist

function create_plugin_database_table()
{
    global $table_prefix, $wpdb;

    $tblname = 'extra_fee';
    $wp_track_table = $table_prefix . "$tblname";

    #Check to see if the table exists already, if not, then create it

    if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
    {

        $sql = "CREATE TABLE `". $wp_track_table . "` ( ";
        $sql .= "  `id`  int(11)   NOT NULL auto_increment, ";
        $sql .= "  `products_id`  varchar(255)   NULL, ";
        $sql .= "  `lable_name`  varchar(255)   NULL, ";
        $sql .= "  `fee_type`  varchar(255)   NULL, ";
        $sql .= "  `fee`  varchar(255)   NULL, ";
        $sql .= "  PRIMARY KEY (`id`) "; 
        $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
        
        dbDelta($sql);
    }
}

function woo_add_cart_fee() {
  global $wpdb;
  $get_results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}extra_fee");

  global $woocommerce;
  $coast = 0;

  $add_fee = array();
  foreach ($woocommerce->cart->cart_contents as $key => $values ) {

      foreach ($get_results as $k => $extra_fee){

          if(in_array($values['product_id'], explode(",", $extra_fee->products_id))){

                if($extra_fee->fee_type == "percentage"){
                  $add_fee[$extra_fee->lable_name] = $add_fee[$extra_fee->lable_name] + ($values['line_total'] * ($extra_fee->fee / 100));
                  
                }else{
                   $add_fee[$extra_fee->lable_name]= $add_fee[$extra_fee->lable_name] + $extra_fee->fee;
                   
                }
          }
      }

    }
    
    if(isset($add_fee)){
      foreach ($add_fee as $key => $fee) {
        $woocommerce->cart->add_fee($key,$fee, $taxable = false, $tax_class = '');
      }
        
    }

}


function extra_fee_product_menu() {
    add_submenu_page( 'woocommerce', 'Extra Fee Call Back', 'Extra Fee Call Back', 'manage_options', 'woo-subpage-test', 'extra_fee_product_menu_callback' );
}

function extra_fee_product_menu_callback() {
  include( plugin_dir_path( __FILE__ ) . 'include/woocommerce_product_fee.php');
}

function load_custom_wp_admin_style($hook) {
   wp_enqueue_style( 'select2mincss', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css',false,'1.0.0');

   wp_enqueue_script( 'my_custom_script', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', null, null, true);

   wp_enqueue_script( 'customselect2js', plugins_url('/js/custom.js', __FILE__),null, null, true );
}
?>