<?php
/*
Plugin Name: Promotions: Share Entry
Plugin URI: http://bozuko.com
Description: This plugin improves the UI for Wordpress SEO by Yoast
Version: 1.0.0
Author: Bozuko
Author URI: http://bozuko.com
License: Proprietary
*/

add_action('promotions/plugins/load', function()
{
  define('PROMOTIONS_SHAREENTRY_DIR', dirname(__FILE__));
  define('PROMOTIONS_SHAREENTRY_URI', plugins_url('/', __FILE__));
  
  Snap_Loader::register( 'PromotionsShareEntry', PROMOTIONS_SHAREENTRY_DIR . '/lib' );
  Snap::inst('PromotionsShareEntry_Plugin');
}, 100);