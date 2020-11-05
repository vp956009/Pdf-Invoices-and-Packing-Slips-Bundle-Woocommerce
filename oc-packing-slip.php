<?php
/**
 * Plugin Name: Pdf Invoices and Packing Slips Bundle Woocommerce
 * Description: This plugin allows create to order PDF and packing slip.
 * Version: 1.0
 * Copyright: 2019 
 */
if (!defined('ABSPATH')) {
  die('-1');
}
if (!defined('OCPSW_PLUGIN_NAME')) {
  define('OCPSW_PLUGIN_NAME', 'Pdf Invoices and Packing Slips Bundle Woocommerce');
}
if (!defined('OCPSW_PLUGIN_VERSION')) {
  define('OCPSW_PLUGIN_VERSION', '1.0.0');
}
if (!defined('OCPSW_PLUGIN_FILE')) {
  define('OCPSW_PLUGIN_FILE', __FILE__);
}
if (!defined('OCPSW_PLUGIN_DIR')) {
  define('OCPSW_PLUGIN_DIR',plugins_url('', __FILE__));
}
if (!defined('OCPSW_DOMAIN')) {
  define('OCPSW_DOMAIN', 'ocwcp');
}
if (!defined('OCPSW_BASE_NAME')) {
define('OCPSW_BASE_NAME', plugin_basename(OCPSW_PLUGIN_FILE));
}

define('OCPSW_BASE_PLUGIN_DIR', plugin_dir_path(__FILE__));

//Main class  
if (!class_exists('OCPSW')) {

  class OCPSW {

    protected static $OCPSW_instance;
           /**
       * Constructor.
       *
       * @version 3.2.3
       */
    //Load required js,css and other files
    function __construct() {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        //check plugin activted or not
        add_action('admin_init', array($this, 'OCPSW_check_plugin_state'));
    }

    //Add JS and CSS on Backend
    function OCPSW_load_admin_script_style() {
      wp_enqueue_style( 'OCPSW_admin_css', OCPSW_PLUGIN_DIR . '/css/admin_style.css', false, '1.0.0' );
      wp_enqueue_media();
      wp_enqueue_script( 'OCWQV_admin_js', OCPSW_PLUGIN_DIR . '/js/wp_media_uploader.js', false, '1.0.0' );
    }

    
      function OCWQV_load_script_style() {
      wp_enqueue_style( 'OCWQV_front_css', OCPSW_PLUGIN_DIR . '/css/style.css', false, '1.0.0' );
       
    }



    function OCPSW_show_notice() {

        if ( get_transient( get_current_user_id() . 'ocwqverror' ) ) {

          deactivate_plugins( plugin_basename( __FILE__ ) );

          delete_transient( get_current_user_id() . 'ocwqverror' );

          echo '<div class="error"><p> This plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=woocommerce">WooCommerce</a> plugin installed and activated.</p></div>';

        }
    }


    function OCPSW_check_plugin_state(){
      if ( ! ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
        set_transient( get_current_user_id() . 'ocwqverror', 'message' );
      }
    }


    function init() {
      
      //echo WP_CONTENT_DIR."</br>";
      //echo OCPSW_BASE_PLUGIN_DIR;
      add_action('admin_notices', array($this, 'OCPSW_show_notice'));
      add_action('admin_enqueue_scripts', array($this, 'OCPSW_load_admin_script_style'));
      add_action('wp_enqueue_scripts',  array($this, 'OCWQV_load_script_style'));
      add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
    }

    function plugin_row_meta( $links, $file ) {
        if (OCPSW_BASE_NAME === $file ) {
            $row_meta = array(
                'rating'    =>  '<a href="#" target="_blank"><img src="'.OCPSW_PLUGIN_DIR.'/images/star.png" class="OCPSW_rating_div"></a>',
            );

            return array_merge( $links, $row_meta );
        }

        return (array) $links;
    }


    //Load all includes files
    function includes() {
      //Admn site Layout
      include_once('includes/oc-ocpsw-backend.php');
      include_once('includes/oc-ocpsw-front.php');
    
    }

    //Plugin Rating
    public static function OCPSW_do_activation() {
      set_transient('ocpsw-first-rating', true, MONTH_IN_SECONDS);
    }


    public static function OCPSW_instance() {
      if (!isset(self::$OCPSW_instance)) { 
        self::$OCPSW_instance = new self();
        self::$OCPSW_instance->init();
        self::$OCPSW_instance->includes();
      }
      return self::$OCPSW_instance;
    }
  }
  add_action('plugins_loaded', array('OCPSW', 'OCPSW_instance'));

  register_activation_hook(OCPSW_PLUGIN_FILE, array('OCPSW', 'OCPSW_do_activation'));
}

	