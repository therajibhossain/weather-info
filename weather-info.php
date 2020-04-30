<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Weather Info
 * Description:       Simple Weather Information
 * Version:           1.0.0
 * Author:            M. A. Monim
 * Author URI:        https://www.upwork.com/freelancers/~01a7e2b3d17cd8070a
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       weather-info
 * Domain Path:       /languages
 * WP requires at least: 5.0.0
 */

if (!defined('ABSPATH')) die('Direct access not allowed');
/*plugin environment variables*/
define('GWW_DIR_PATH', plugin_dir_path(__FILE__));
require_once GWW_DIR_PATH . '/inc/autoload.php';

define('GWW_VERSION', '1.0.0');
define('GWW_NAME', 'weather-info');
define('GWW_FILE', plugin_basename(__FILE__));
define('GWW_URL', plugins_url(GWW_NAME . '/'));
define('GWW_STYLES', GWW_URL . 'css/');
define('GWW_SCRIPTS', GWW_URL . 'js/');
define('GWW_LOGS', GWW_DIR_PATH . 'logs/');

use GWWeatherConfig as conf;

//is_requirements_met();
function is_requirements_met()
{
    $min_wp = '4.6'; // minimum WP version
    $min_php = '5.6'; // minimum PHP version
    // Check for WordPress version
    if (version_compare(get_bloginfo('version'), $min_wp, '<')) {
        return false;
    }
    // Check the PHP version
    if (version_compare(PHP_VERSION, $min_php, '<')) {
        add_action('admin_notices', function () {
            conf::notice_div('error', 'Weather Info requires at least PHP 5.6. Please upgrade PHP. The Plugin has been deactivated.');
        });
        return false;
    }
    return true;
}

/**
 * During plugin activation.
 */
function activate_gw_weather()
{
    GWWeatherActDeAct::activate();
}

//
/**
 * During plugin deactivation.
 */
function deactivate_gw_weather()
{
    GWWeatherActDeAct::deactivate();
}

register_activation_hook(__FILE__, 'activate_gw_weather');
register_deactivation_hook(__FILE__, 'deactivate_gw_weather');

/**
 * Execution of the plugin.
 */
function gww_exe()
{
    static $Plugin = null;
    if (null === $Plugin) {
        $Plugin = new GWWeather(GWW_VERSION, GWW_FILE);
    }
    return $Plugin;
}

if (is_admin()) {
    gww_exe();
}


function visitor_ip() {
    $ip = '103.133.206.230';
    if (array_key_exists('HTTP_CLIENT_IP', $_SERVER) && $_SERVER['HTTP_CLIENT_IP'])
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    else if(array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_FOR'])
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(array_key_exists('HTTP_X_FORWARDED', $_SERVER) && $_SERVER['HTTP_X_FORWARDED'])
        $ip = $_SERVER['HTTP_X_FORWARDED'];
    else if(array_key_exists('HTTP_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_FORWARDED_FOR'])
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(array_key_exists('HTTP_FORWARDED', $_SERVER) && $_SERVER['HTTP_FORWARDED'])
        $ip = $_SERVER['HTTP_FORWARDED'];
    else if(array_key_exists('REMOTE_ADDR', $_SERVER) && $_SERVER['REMOTE_ADDR'])
        $ip = $_SERVER['REMOTE_ADDR'];
    else
        $ip = 'none';

    return $ip;
}
echo visitor_ip();die;