<?php
class WPRP_Repeater_Custom_Field_Helper
{
    /**** Custom Plugin Properties ****/
    protected $repeater_js_filepath;
    protected $admin_js_filepath;
    protected $image_upload_js_filepath;

    /**** Plugin Constructor and Initializer(Run) ****/
    //The constructor off the class. Initialize all the basics components of the plugin.
    public function __construct()
    {
        $this->repeater_js_filepath = 'public/js/repeater.js';
        $this->admin_js_filepath = 'public/js/admin.js';
        $this->image_upload_js_filepath = 'public/js/custom-image-upload.js';
    }

    //The run method starts the execution of the plugin. 
    public function run()
    {
        $this->add_actions();
    }

    //The add_actions methods contains the actions of the plugins
    private function add_actions()
    {
        add_action('init', $this->wprp_repeater_custom_field_cpt());
        add_action('admin_init', array($this,'wprp_admin_repeater_script'));
        add_action('admin_init', array($this,'wprp_admin_validation_script'));
        add_action('admin_init', array($this,'wprp_custom_image_upload_script'));
    }

    function wprp_admin_repeater_script()
    {
        wp_enqueue_script('wprp-repeater-script', WPRP_PLUGIN_PATH.$this->repeater_js_filepath, array('jquery'), false, true);
    }

    function wprp_admin_validation_script() 
    {
        wp_enqueue_script('wprp-admin-script', WPRP_PLUGIN_PATH.$this->admin_js_filepath, array('jquery'), false, true);
    }

    function wprp_custom_image_upload_script()
    {
        wp_enqueue_script('wprp-image-upload-script', WPRP_PLUGIN_PATH.$this->image_upload_js_filepath, array('jquery'), false, true);
    }

    //Method for creating the custom post type in admin backend
    private function wprp_repeater_custom_field_cpt()
    {
        $args = array(
            'public' => true,
            'label' => 'Repeater Fields',
            'menu_icon' => 'dashicons-welcome-widgets-menus',
            'supports' => array(
                'title',
                'thumbnail',
            ),
        );
        register_post_type('wprp-repeater-fields', $args);
    }
}
?>