<?php
/**
 * During plugin activation/ deactivation
 */
if (!defined('ABSPATH')) die('Direct access not allowed');

class GWWeatherActDeAct
{
    /**
     * During plugin ctivation
     */
    public static function activate()
    {
    }
        /**
         * deactivation
         */
        public
        static function deactivate()
        {
            // deleting transients
            global $wpdb;
            $wpdb->query("DELETE FROM `$wpdb->options` WHERE `option_name` LIKE '%gwweather_%'");
        }
    }
