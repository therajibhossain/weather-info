<?php
/**
 * During plugin deactivation
 */
if (!defined('ABSPATH')) die('Direct access not allowed');

class GWWeatherDeactivator
{
    public static function deactivate()
    {
        // deleting transients
        global $wpdb;
        $wpdb->query("DELETE FROM `$wpdb->options` WHERE `option_name` LIKE '%gwweather_%'");
    }



}
