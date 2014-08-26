<?php
global $base_url;
//include_once '/var/www/html/openatrium/includes/bootstrap.inc';
//drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);


//define('DRUPAL_ROOT', getcwd());
//require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
//drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
//    $block = block_load('views', 'milestone-block_1');
//   //    print_r($block);
//    $content=    drupal_render(_block_get_renderable_array(_block_render_blocks(array($block))));
//    print $content;

chdir('/var/www/html/openatrium/');
define('DRUPAL_ROOT', getcwd());
//Load Drupal
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
//$block = module_invoke('views', 'block_view', 'milestone-block_1');
//print render($block);
  echo views_embed_view('milestone', 'block_1'); 
?>
