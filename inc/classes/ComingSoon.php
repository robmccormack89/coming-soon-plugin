<?php
namespace Rmcc;
use Timber\Timber;

class ComingSoon extends Timber {

  public function __construct() {
    parent::__construct();
    
    // timber stuff. the usual stuff
    add_filter('timber/twig', array($this, 'add_to_twig'));
    add_filter('timber/context', array($this, 'add_to_context'));
    
    // enqueue plugin assets
    add_action('wp_enqueue_scripts', array($this, 'coming_soon_assets'), 20);
    
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
  
  public function coming_soon_assets() {
    if(is_user_logged_in()) return;
    wp_enqueue_style(
      'coming-soon',
      COMING_SOON_URL . 'public/css/coming-soon.css'
    );
  }
  
  public function add_to_twig($twig) { 
    $twig->addExtension(new \Twig_Extension_StringLoader());
    return $twig;
  }

  public function add_to_context($context) {
    return $context;    
  }

}