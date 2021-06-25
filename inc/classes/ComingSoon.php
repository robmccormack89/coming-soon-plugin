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
    wp_enqueue_style(
      'coming-soon',
      COMING_SOON_URL . 'public/css/coming-soon.css'
    );
  }
  
  public function add_to_twig($twig) { 
    if(!class_exists('Twig_Extension_StringLoader')){
      $twig->addExtension(new Twig_Extension_StringLoader());
    }
    return $twig;
  }
  public function add_to_context($context) {
    return $context;    
  }
}