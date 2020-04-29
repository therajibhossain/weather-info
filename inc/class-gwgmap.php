<?php
/*Core class*/
if (!defined('ABSPATH')) {
    exit();
}

use GWGMapconfig as conf;

class GWGMap
{
    /*version string*/
    protected $version = null;
    /*filepath string*/
    protected $filepath = null;
    private $_backup_dir = GWGM_DIR . 'backup/';

    /**
     * GWBackup constructor.
     * @param $version
     * @param $filepath
     */
    public function __construct($version, $filepath)
    {
        $this->version = $version;
        $this->filepath = $filepath;
        $this->init_hooks();
    }

    private function init_hooks()
    {
        register_activation_hook($this->filepath, array($this, 'plugin_activate')); //activate hook
        register_deactivation_hook($this->filepath, array($this, 'plugin_deactivate')); //deactivate hook
        register_uninstall_hook($this->filepath, 'GWGMap::plugin_uninstall'); //deactivate hook

        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        new GWGMapSettings();
        add_action('admin_init', array($this, 'execution'));
    }

    public function admin_scripts()
    {
        $file = GWGM_NAME . '-admin';
        wp_enqueue_style($file, GWGM_STYLES . "admin.css");
        wp_enqueue_script($file, GWGM_SCRIPTS . "admin.js", array('jquery'));
    }


    public function plugin_activate()
    {
        $this->do_actions('active');
    }

    public function plugin_deactivate()
    {
        $this->do_actions('de-active');
    }

    public static function plugin_uninstall()
    {
        $options = array('gwgm_config');
        foreach ($options as $item) {
            if (get_option($item) != false) {
                delete_option($item);
            }
        }
    }

    /*active/ de-active callback*/
    private function do_actions($status)
    {
        $options = conf::option_name();
    }

    public function execution()
    {
        if (is_admin() && current_user_can('manage_options')) {
            if (isset($_POST['action'])) {
                $res = array('', '', '');
                $input = conf::sanitize_data($_POST);
                if ($address = $input['location']) {
                    if (isset($input['_token']) && wp_verify_nonce($input['_token'], '_wpnonce')) {
                        $res = array('updated', 'copy and paste the below iFrame link where you need', 1);
                        $param = "&type=" . $res[0] . "&message=" . $res[1] . "&address=" . $address . "&map_width=" . $input['map_width'] . "&map_height=" . $input['map_height'];
                        wp_redirect(conf::setting_url() . $param);
                    }
                }
            }
        }
    }



}