<?php
defined( 'ABSPATH' ) OR exit;
/**
 * Plugin Name: Random Writing Prompt v2
 * Plugin URI: http://kurtschweitzer.com/RWP
 * Description: Creates a shortcode that can insert a random writing prompt into a post or page. List of prompts is editable. This implementation uses classes and has multiple files contained within a folder.
 * Version: 2.0
 * Author: Kurt Schweitzer
 * Author URI: http://kurtschweitzer.com
 * License: GPL2
 */

register_activation_hook(   __FILE__, array( 'Kurts_RWP_Class', 'on_activation' ) );
register_deactivation_hook( __FILE__, array( 'Kurts_RWP_Class', 'on_deactivation' ) );
register_uninstall_hook(    __FILE__, array( 'Kurts_RWP_Class', 'on_uninstall' ) );

add_action( 'plugins_loaded', array( 'Kurts_RWP_Class', 'init' ) );
add_shortcode( 'RWP2', array( 'Kurts_RWP_Class', 'do_shortcode' ) );

//MAIN CLASS

class Kurts_RWP_Class
{
    protected static $instance;

    public static function init()
    {
        is_null( self::$instance ) AND self::$instance = new self;
        return self::$instance;
    }

    public static function on_activation()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "activate-plugin_{$plugin}" );
    }

    public static function on_deactivation()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "deactivate-plugin_{$plugin}" );
    }

    public static function on_uninstall()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        check_admin_referer( 'bulk-plugins' );

        // Important: Check if the file is the one
        // that was registered during the uninstall hook.
        if ( __FILE__ != WP_UNINSTALL_PLUGIN )
            return;
    }

    public static function do_shortcode( $attributes, $content = null ) {
    extract( shortcode_atts( array(
        'class' => ''
    ), $attributes ) );
        $lines = get_option( 'kurts_RWP_lines' );

        $first = ucfirst( $lines[ rand( 0, count($lines) ) ] );
        $second = $lines[ rand( 0, count($lines) ) ];
        $third = $lines[ rand( 0, count($lines) ) ];

        $str_out = $first . ', ' . $second . ', and ' . $third . '.';

        return '<blockquote class="kurts-rwp ' . $class . '">' . $str_out . '</blockquote>';
    }

    public function __construct()
    {
        # INIT the plugin: Hook your callbacks
    }
}

//SCRIPTS
function kurts2nd_scripts(){
    wp_register_script('kurts2nd_script',plugin_dir_url( __FILE__ ).'js/kurts1st.js');
    wp_enqueue_script('kurts2nd_script');
}
add_action('wp_enqueue_scripts','kurts2nd_scripts');

//OPTIONS PAGE (Admin page)

class Kurts_RWP_Options_Class
{
	function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}
	function admin_menu () {
		add_options_page( "Random Writing Prompt v2","Random Writing Prompt v2","manage_options","kurts2nd_options_page_slug", array( $this, 'settings_page' ) );
	}
	function  settings_page () {
		// echo 'This is the page content';
        include( 'lib/rwpv2_admin.php' );
	}
}

new Kurts_RWP_Options_Class;

?>