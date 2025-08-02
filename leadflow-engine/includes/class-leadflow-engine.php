<?php

class Leadflow_Engine {

    protected $loader;
    protected $plugin_name;
    protected $version;

    public function __construct() {
        if ( defined( 'LEADFLOW_ENGINE_VERSION' ) ) {
            $this->version = LEADFLOW_ENGINE_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'leadflow-engine';

        $this->load_dependencies();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-leadflow-engine-loader.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-leadflow-engine-public.php';

        // Load modules.
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/user-gatekeeper/user-gatekeeper.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/landing-page-creator/landing-page-creator.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/analytics-dashboard/analytics-dashboard.php';

        $this->loader = new Leadflow_Engine_Loader();
    }

    private function define_public_hooks() {
        $plugin_public = new Leadflow_Engine_Public( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
    }

    public function run() {
        $this->loader->run();
    }

    public function get_plugin_name() {
        return $this->plugin_name;
    }

    public function get_version() {
        return $this->version;
    }
}
