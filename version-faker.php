<?php
namespace Ankur\Plugins\WP_Version_Faker;
?><?php
/*
Plugin Name: WP Version Faker
Plugin URI: https://github.com/ankurk91/wp-version-faker
Description: WordPress Version Faker
Version: 1.0.0
Author: Ankur Kumar
Author URI: http://ankurk91.github.io/
License: MIT
License URI: https://opensource.org/licenses/MIT
*/
?><?php
/* No direct access */
if (!defined('ABSPATH')) die;

define('AVF_PLUGIN_VER', '1.0.0');
define('AVF_BASE_FILE', __FILE__);

class WP_Version_Faker
{
    public function __construct()
    {
        add_action('wp_loaded', array($this, 'set_version'));
    }

    /**
     * Override WordPress version number
     * TODO Get latest version from official API
     */
    public function set_version()
    {
        global $wp_version;
        $wp_version = '4.6';
    }

}

new WP_Version_Faker();

