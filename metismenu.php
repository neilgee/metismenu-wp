<?php     namespace ng_metismenu;

/*
Plugin Name: metisMenu WP
Plugin URI: http://wpbeaches.com/
Description: Using metisMenu in WordPress
Author: Neil Gee
Version: 1.0.0
Author URI: http://wpbeaches.com
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: metismenu
Domain Path: /languages/
*/


  // If called direct, refuse
  if ( ! defined( 'ABSPATH' ) ) {
          die;
  }

/* Assign global variables */

$plugin_url = WP_PLUGIN_URL . '/metismenu';
$options = array();

/**
 * Register our text domain.
 *
 * @since 1.0.0
 */


function load_textdomain() {
  load_plugin_textdomain( 'metismenu', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_textdomain' );

/**
 * Register and Enqueue Scripts and Styles
 *
 * @since 1.0.0
 */

//Script-tac-ulous -> All the Scripts and Styles Registered and Enqueued
function scripts_styles() {

$options = get_option( 'metismenu_settings' );

  wp_register_script ( 'metismenujs' , plugins_url( '/js/metisMenu.min.js',  __FILE__ ), array( 'jquery' ), '2.2.0', false );
  wp_register_style ( 'metismenucss' , plugins_url( '/css/metisMenu.css',  __FILE__ ), '' , '2.2.0', 'all' );
  wp_register_script ( 'metismenu-init' , plugins_url( '/js/metismenu-init.js',  __FILE__ ), array( 'metismenujs' ), '1.0.0', false );
  wp_register_style ( 'fontawesome' , '//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', '' , '4.4.0', 'all' );

  wp_enqueue_script( 'metismenujs' );

  if( (bool) $options['ng_metismenu_disable_style'] == false ) { 
  wp_enqueue_style( 'metismenucss' );
  wp_enqueue_style( 'fontawesome' );
  } 


     $data = array (

      'ng_metis' => array(
          
			'ng_metismenu_selection'  => esc_html($options['ng_metismenu_selection']),
			'ng_metismenu_toggle'  => (bool)$options['ng_metismenu_toggle'],

          

      ),
  );

    // Pass PHP variables to jQuery script
    wp_localize_script( 'metismenu-init', 'metismenuVars', $data );

    wp_enqueue_script( 'metismenu-init' );
  
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\scripts_styles' );

/**
 * Register our option fields
 *
 * @since 1.0.0
 */

function plugin_settings(){
  register_Setting(
        'ng_metismenu_settings_group', //option name
        'metismenu_settings',// option group setting name and option name
        __NAMESPACE__ . '\\metismenu_validate_input' //sanitize the inputs
  );

  add_settings_section(
        'ng_metismenu_section', //declare the section id
        'metismenu Settings', //page title
         __NAMESPACE__ . '\\ng_metismenu_section_callback', //callback function below
        'metismenu' //page that it appears on

    );

  add_settings_field(
        'ng_metismenu_selection', //unique id of field
        'Add Menu ID or Class', //title
         __NAMESPACE__ . '\\ng_metismenu_id_callback', //callback function below
        'metismenu', //page that it appears on
        'ng_metismenu_section' //settings section declared in add_settings_section
    );

    add_settings_field(
        'ng_metismenu_toggle', //unique id of field
        'Toggle Menu', //title
         __NAMESPACE__ . '\\ng_metismenu_toggle_callback', //callback function below
        'metismenu', //page that it appears on
        'ng_metismenu_section' //settings section declared in add_settings_section
    );
     add_settings_field(
        'ng_metismenu_disable_style', //unique id of field
        'Disable metisMenu Default CSS', //title
         __NAMESPACE__ . '\\ng_metismenu_disable_style_callback', //callback function below
        'metismenu', //page that it appears on
        'ng_metismenu_section' //settings section declared in add_settings_section
    );
}
add_action('admin_init', __NAMESPACE__ . '\\plugin_settings');

/**
 * Sanitize our inputs
 *
 * @since 1.0.0
 */

function metismenu_validate_input( $input ) {
   // Create our array for storing the validated options
    $output = array();
     
    // Loop through each of the incoming options
    foreach( $input as $key => $value ) {
         
        // Check to see if the current option has a value. If so, process it.
        if( isset( $input[$key] ) ) {
         
            // Strip all HTML and PHP tags and properly handle quoted strings
            $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
             
        } // end if

         
    } // end foreach
     
    // Return the array processing any additional functions filtered by this action
    return apply_filters( 'metismenu_validate_input' , $output, $input );
}

/**
 * Register our section call back
 * (not much happening here)
 * @since 1.0.0
 */

function ng_metismenu_section_callback() {

}

/**
 * Register Menu ID to use as metismenu Menu
 *
 * @since 1.0.0
 */

function ng_metismenu_id_callback() {
$options = get_option( 'metismenu_settings' ); 

if( !isset( $options['ng_metismenu_selection'] ) ) $options['ng_metismenu_selection'] = '';


echo '<input type="text" id="ng_metismenu_selection" name="metismenu_settings[ng_metismenu_selection]" value="' . sanitize_text_field($options['ng_metismenu_selection']) . '" placeholder="Add Menu ID/Class to use as metisMenu" class="regular-text" >';
echo '<label for="ng_metismenu_selection">' . esc_attr_e( 'Add Menu ID or Class to use as metisMenu , comma sep. multiple menus','metismenu') . '</label>';
}

/**
 *  Menu Toggle
 *
 * @since 1.0.0
 */

function ng_metismenu_toggle_callback() {
$options = get_option( 'metismenu_settings' ); 

//if( !isset( $options['ng_metismenu_toggle'] ) ) $options['ng_metismenu_toggle'] = 1;


  echo'<input type="checkbox" id="ng_metismenu_toggle" name="metismenu_settings[ng_metismenu_toggle]" value="1"' . checked( 1, $options['ng_metismenu_toggle'], false ) . '/>';
  echo'<label for="ng_metismenu_toggle">' . esc_attr_e( 'Check to enable Toggle effect on menu, (closes menu items when you open a new one)','metismenu') . '</label>';

}


/**
 *  Disable metismenu Default Style
 *
 * @since 1.0.0
 */

function ng_metismenu_disable_style_callback() {
$options = get_option( 'metismenu_settings' ); 

//if( !isset( $options['ng_menu_accordion'] ) ) $options['ng_menu_accordion'] = 1;


  echo'<input type="checkbox" id="ng_metismenu_disable_style" name="metismenu_settings[ng_metismenu_disable_style]" value="1"' . checked( 1, $options['ng_metismenu_disable_style'], false ) . '/>';
  echo'<label for="ng_metismenu_disable_style">' . esc_attr_e( 'Check to Disable Default metismenu CSS Stylin and DIY','metismenu') . '</label>';

}

/**
 * Create the plugin option page.
 *
 * @since 1.0.0
 */

function plugin_page() {

    /*
     * Use the add options_page function
     * add_options_page( $page_title, $menu_title, $capability, $menu-slug, $function )
     */

     add_options_page(
        __( 'metisMenu Options Plugin','metismenu' ), //$page_title
        __( 'metisMenu', 'metismenu' ), //$menu_title
        'manage_options', //$capability
        'metismenu', //$menu-slug
        __NAMESPACE__ . '\\plugin_options_page' //$function
      );
}
add_action( 'admin_menu', __NAMESPACE__ . '\\plugin_page' );

/**
 * Include the plugin option page.
 *
 * @since 1.0.0
 */

function plugin_options_page() {

    if( !current_user_can( 'manage_options' ) ) {

      wp_die( "Hall and Oates 'Say No Go'" );
    }

   require( 'inc/options-page-wrapper.php' );
}