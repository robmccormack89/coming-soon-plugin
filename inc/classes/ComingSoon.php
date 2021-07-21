<?php
namespace Rmcc;
use Timber\Timber;

class ComingSoon extends Timber {

  public function __construct() {
    parent::__construct();
    add_filter('timber/twig', array($this, 'add_to_twig'));
    add_filter('timber/context', array($this, 'add_to_context'));
    
    add_action('plugins_loaded', array($this, 'plugin_timber_locations'));
    add_action('plugins_loaded', array($this, 'plugin_text_domain_init'));    
    add_action('wp_enqueue_scripts', array($this, 'plugin_enqueue_assets'), 20);

    add_action('template_include', array($this, 'coming_soon_template'));
    add_action('template_redirect', array($this, 'coming_soon_redirect'));
    
    add_action('plugins_loaded', array($this, 'coming_soon_assets_plugins_loaded'), 99);
  }
  
  public function coming_soon_assets_plugins_loaded() {
    // remove the active theme's styles & scripts
    add_action('wp_print_scripts', array($this, 'remove_theme_all_scripts'), 100);
    add_action('wp_print_styles', array($this, 'remove_theme_all_styles'), 100);
  }
  public function remove_theme_all_scripts() {
    if (is_user_logged_in()) return;
    
    global $wp_scripts;
    
    $stylesheet_uri = get_stylesheet_directory_uri();
    $new_scripts_list = array(); 
    
    foreach( $wp_scripts->queue as $handle ) {
      $obj = $wp_scripts->registered[$handle];
      $obj_handle = $obj->handle;
      $obj_uri = $obj->src;
  
      if (strpos( $obj_uri, $stylesheet_uri ) === 0)  {
        //Do Nothing
      } else {
        $new_scripts_list[] = $obj_handle;
      }
    }
    
    $wp_scripts->queue = $new_scripts_list;
  }
  public function remove_theme_all_styles() {
    if (is_user_logged_in()) return;
    
    global $wp_styles;
    
    $stylesheet_uri = get_stylesheet_directory_uri();
    $new_styles_list = array(); 
    
    foreach( $wp_styles->queue as $handle ) {
      $obj = $wp_styles->registered[$handle];
      $obj_handle = $obj->handle;
      $obj_uri = $obj->src;
      if ( strpos( $obj_uri, $stylesheet_uri ) === 0 )  {
        //Do Nothing
      } else {
        $new_styles_list[] = $obj_handle;
      }
    }
    
    $wp_styles->queue = $new_styles_list;
  }

  public function coming_soon_template($page_template) {
    if(is_user_logged_in()) {
      $page_template = $page_template;
    } else {
      $page_template = COMING_SOON_PATH . 'templates/coming-soon.php';
    };
    return $page_template;
  }
  public function coming_soon_redirect() {
    
    if(is_user_logged_in()) return;
    
    if (is_front_page()) return;
  
    wp_redirect(esc_url_raw(home_url()));
    exit;
    
  }

  public function plugin_timber_locations() {
    // if timber::locations is empty (another plugin hasn't already added to it), make it an array
    if(!Timber::$locations) Timber::$locations = array();
    // add a new views path to the locations array
    array_push(
      Timber::$locations, 
      COMING_SOON_PATH . 'views'
    );
  }
  public function plugin_text_domain_init() {
    load_plugin_textdomain('coming-soon', false, COMING_SOON_BASE. '/languages');
  }
  public function plugin_enqueue_assets() {
    
    if(is_user_logged_in()) return;
    
    // base
    wp_enqueue_style(
      'coming-soon-base',
      COMING_SOON_URL . 'public/css/base.css'
    );

    // base
    wp_enqueue_script(
      'coming-soon-base',
      COMING_SOON_URL . 'public/js/base.js',
      '',
      '',
      false
    );
    
    // plugin*
    wp_enqueue_style(
      'coming-soon',
      COMING_SOON_URL . 'public/css/plugin.css'
    );
    
    // jquery*
    wp_enqueue_script('jquery');
    
    // plugin*
    wp_enqueue_script(
      'coming-soon',
        COMING_SOON_URL . 'public/js/plugin.js',
      'jquery',
      '1.0.0',
      true
    );
    
  }
  
  public function add_to_twig($twig) { 
    if(!class_exists('Twig_Extension_StringLoader')){
      $twig->addExtension(new Twig_Extension_StringLoader());
    }
    return $twig;
  }
  public function add_to_context($context) {
    $context['site'] = new \Timber\Site;
    return $context;    
  }
}