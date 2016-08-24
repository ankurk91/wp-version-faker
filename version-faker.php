<?php
namespace Ankur\Plugins\WP_Version_Faker;

    /**
     * Plugin Name: WP Version Faker
     * Plugin URI: https://github.com/ankurk91/wp-version-faker
     * Description: WordPress Version Faker
     * Version: 1.0.0
     * Author: Ankur Kumar
     * Author URI: http://ankurk91.github.io/
     * License: MIT
     * License URI: https://opensource.org/licenses/MIT
     */

// No direct access
if (!defined('ABSPATH')) die;

define('AVF_PLUGIN_VER', '1.0.0');
define('AVF_BASE_FILE', __FILE__);

class WP_Version_Faker
{

    /*
     * Singleton
     */
    private static $instances = array();

    /**
     * WordPress Official API URL
     */
    const API_URL = 'https://api.wordpress.org/core/version-check/1.7/';

    /**
     * All transients have same prefix
     */
    const PREFIX = 'avf_';

    private function __construct()
    {
        add_action('wp_loaded', array($this, 'override_version'));
        add_action('init', array($this, 'store_latest_version'));

        // Hook when plugin gets deactivated
        register_deactivation_hook(plugin_basename(AVF_BASE_FILE), array($this, 'do_upon_plugin_deactivation'));


    }

    /**
     * Function to instantiate our class and make it a singleton
     */
    public static function instance()
    {
        $cls = get_called_class();
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static;
        }
        return self::$instances[$cls];
    }

    protected function __clone()
    {
        //don't not allow clones
    }

    public function __wakeup()
    {
        return new \Exception("Cannot unserialize singleton");
    }

    public function store_latest_version()
    {
        if ($cache = $this->get_transient()) {
            return $cache;
        }
        return $this->set_transient($this->call_wp_api());
    }

    /**
     * Override WordPress version number
     */
    public function override_version()
    {
        global $wp_version;
        $wp_version = $this->get_transient();
    }

    /**
     * Call the WordPress API
     * Note: We cant not reply on WordPress inbuilt wp_version_check function
     * @return string
     */
    private function call_wp_api()
    {
        global $wp_version;
        $response = wp_remote_get(self::API_URL, array());

        if (is_wp_error($response) || 200 != wp_remote_retrieve_response_code($response)) {
            return $wp_version;
        }

        $body = trim(wp_remote_retrieve_body($response));
        $body = json_decode($body);
        //pick first offer
        return $body->offers[0]->version;

    }

    public function do_upon_plugin_deactivation()
    {
        return $this->delete_transient();
    }

    private function get_transient()
    {
        return get_transient(self::PREFIX . 'version');
    }

    /**
     * Save transient , expire after 12 hours
     * @param $val
     * @return bool
     */
    private function set_transient($val)
    {
        return set_transient(self::PREFIX . 'version', $val, 43200);
    }

    private function delete_transient()
    {
        return delete_transient(self::PREFIX . 'version');
    }

}

WP_Version_Faker::instance();

