<?php
namespace FormsPlugin\FrontEnd;

use FormsPlugin\FrontEnd\Controller\Ajax;

class PublicBuilder
{
    const NONCE_AJAX = 'form';

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $name    The ID of this plugin.
     */
    private $name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @var      string    $name       The name of the plugin.
     * @var      string    $version    The version of this plugin.
     */
    public function __construct( $name, $version ) {
        $this->name = $name;
        $this->version = $version;
        add_action( 'wp_ajax_HandlerSendForms',  array( &$this, 'ajaxHandlerSendForms'  ));
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueueStyles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Vrbm_Public_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Vrbm_Public_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueueScripts()
    {
        wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/forms.js', [], $this->version, FALSE );

        wp_localize_script( $this->name, 'formsPluginUrl',
            array(
                'url' => admin_url('admin-ajax.php')
            )
        );

        $this->addJsData($this->name);
    }

    /**
     * Добавляет nonce на фронте.
     *
     * @param $handel
     */
    private function addJsData($handel)
    {

        $data = array(
            'nonce' => wp_create_nonce( self::NONCE_AJAX ),
        );

        wp_localize_script( $handel, 'FormsData', $data );
    }

    public function ajaxHandlerSendForms()
    {
       check_ajax_referer( self::NONCE_AJAX,  'security');
       if (!isset($_POST['formData'])) {
           throw new Error( 'No post data' );
       }

       $ajController = new Ajax();

        if($ajController->actionSaveFormsRequest($_POST['formData']) == false)
            throw new Error( 'Error add request.' );
       echo  'true';
    }
}


