<?php
/**
 * Preloader Classic.
 *
 *
 * @copyright Copyright (C) 2014-2019, DavidCarbon - wp-plugins@davidcarbon.ml
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or higher
 *
 * @wordpress-plugin
 * Plugin Name: Preloader Classic
 * Version:     1.0.9
 * Plugin URI:  https://davidcarbon.ml/preloader-classic
 * Description: Add-on preloader to your website real easily, responsive and retina, full customization, compatible with all major browsers.
 * Author:      DavidCarbon
 * Author URI:  https://davidcarbon.ml
 * License:     GPL v3
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


// Add plugin meta links
function WPTime_preloader_plugin_row_meta( $links, $file ) {

  if ( strpos( $file, 'preloader.php' ) !== false ) {
    
    $new_links = array(
            '<a href="http://wp-plugins.in/the-preloader" target="_blank">Explanation of Use</a>',
            '<a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=24967&tid1=preloader_plugin_m&url=35248" target="_blank">Elegant Themes</a>',
            '<a href="https://www.bluehost.com/track/wptime/preloader-plugin-m" target="_blank">Bluehost</a>',
            '<a href="http://wp-plugins.in" target="_blank">More Plugins</a>'
          );
    
    $links = array_merge( $links, $new_links );
    
  }
  
  return $links;
  
}
add_filter( 'plugin_row_meta', 'WPTime_preloader_plugin_row_meta', 10, 2 );


// Add settings page link in before activate/deactivate links.
function WPTime_preloader_plugin_action_links( $actions, $plugin_file ){
  
  static $plugin;

  if ( !isset($plugin) ){
    $plugin = plugin_basename(__FILE__);
  }
    
  if ($plugin == $plugin_file) {
    
    if ( is_ssl() ) {
      $settings_link = '<a href="'.admin_url( 'plugins.php?page=WPTime_preloader_settings', 'https' ).'">Settings</a>';
    }else{
      $settings_link = '<a href="'.admin_url( 'plugins.php?page=WPTime_preloader_settings', 'http' ).'">Settings</a>';
    }
    
    $settings = array($settings_link);
    
    $actions = array_merge($settings, $actions);
      
  }
  
  return $actions;
  
}
add_filter( 'plugin_action_links', 'WPTime_preloader_plugin_action_links', 10, 5 );


// Set default setting of display preloader (default is full website)
function WPTime_plugin_preloader_init(){
  if( !get_option('wptpreloader_screen') ){
    update_option('wptpreloader_screen', 'full');
  }

  if( !function_exists('is_woocommerce') and get_option('wptpreloader_screen') == 'woocommerce' ){
        update_option('wptpreloader_screen', 'full');
    }
}
add_action('init', 'WPTime_plugin_preloader_init');


// Include Settings page
include( plugin_dir_path(__FILE__).'/settings.php' );


// Include JavaScript
function WPTime_plugin_preloader_script(){  

  if( function_exists('is_woocommerce') ){
    $woocommerce = is_woocommerce();
    $checkout = is_checkout();
    $cart = is_cart();
    $account = is_account_page();
    $view = is_view_order_page();
  }else{
    $woocommerce = false;
    $checkout = false;
    $cart = false;
    $account = false;
    $view = false;
  }

  if(
    get_option( 'wptpreloader_screen' ) == 'full'
    or get_option( 'wptpreloader_screen' ) == 'homepage' and is_home()
    or get_option( 'wptpreloader_screen' ) == 'frontpage' and is_front_page()
    or get_option( 'wptpreloader_screen' ) == 'posts' and is_single()
    or get_option( 'wptpreloader_screen' ) == 'pages' and is_page()
    or get_option( 'wptpreloader_screen' ) == 'cats' and is_category()
    or get_option( 'wptpreloader_screen' ) == 'tags' and is_tag()
    or get_option( 'wptpreloader_screen' ) == 'attachment' and is_attachment()
    or get_option( 'wptpreloader_screen' ) == '404error' and is_404()
    or get_option( 'wptpreloader_screen' ) == 'woocommerce' and ( $woocommerce === true or $checkout === true or $cart === true or $account === true or $view === true)
  ){
    wp_enqueue_script( 'wptime-plugin-preloader-script', plugins_url( '/js/preloader-script.js', __FILE__ ), array('jquery'), null, false);
  }

}
add_action('wp_enqueue_scripts', 'WPTime_plugin_preloader_script');


// Add CSS
function WPTime_plugin_preloader_css(){
  
  if( get_option('wptpreloader_bg_color') ){
    $background_color = get_option('wptpreloader_bg_color');
  }else{
    $background_color = '#FFFFFF';
  }
    
  if( get_option('wptpreloader_image') ){
    $preloader_image = get_option('wptpreloader_image');
  }else{
    $preloader_image = plugins_url( '/images/preloader.GIF', __FILE__ );
  }

  if( function_exists('is_woocommerce') ){
    $woocommerce = is_woocommerce();
    $checkout = is_checkout();
    $cart = is_cart();
    $account = is_account_page();
    $view = is_view_order_page();
  }else{
    $woocommerce = false;
    $checkout = false;
    $cart = false;
    $account = false;
    $view = false;
  }
  
  if(
    get_option( 'wptpreloader_screen' ) == 'full'
    or get_option( 'wptpreloader_screen' ) == 'homepage' and is_home()
    or get_option( 'wptpreloader_screen' ) == 'frontpage' and is_front_page()
    or get_option( 'wptpreloader_screen' ) == 'posts' and is_single()
    or get_option( 'wptpreloader_screen' ) == 'pages' and is_page()
    or get_option( 'wptpreloader_screen' ) == 'cats' and is_category()
    or get_option( 'wptpreloader_screen' ) == 'tags' and is_tag()
    or get_option( 'wptpreloader_screen' ) == 'attachment' and is_attachment()
    or get_option( 'wptpreloader_screen' ) == '404error' and is_404()
    or get_option( 'wptpreloader_screen' ) == 'woocommerce' and ( $woocommerce === true or $checkout === true or $cart === true or $account === true or $view === true)
  ){
    $image_width = get_option('wpt_thepreloader_image_size_get_width');
    $image_height = get_option('wpt_thepreloader_image_size_get_height');
  ?>
      <style type="text/css">
      #wptime-plugin-preloader{
        position: fixed;
        top: 0;
         left: 0;
         right: 0;
         bottom: 0;
        background:url(<?php echo $preloader_image; ?>) no-repeat <?php echo $background_color; ?> 50%;
        -moz-background-size:<?php echo $image_width; ?>px <?php echo $image_height; ?>px;
        -o-background-size:<?php echo $image_width; ?>px <?php echo $image_height; ?>px;
        -webkit-background-size:<?php echo $image_width; ?>px <?php echo $image_height; ?>px;
        background-size:<?php echo $image_width; ?>px <?php echo $image_height; ?>px;
        z-index: 99998;
        width:100%;
        height:100%;
      }
    </style>

    <noscript>
        <style type="text/css">
            #wptime-plugin-preloader{
              display:none !important;
            }
        </style>
    </noscript>
    <?php
  
  }
  
}
add_action('wp_head', 'WPTime_plugin_preloader_css');