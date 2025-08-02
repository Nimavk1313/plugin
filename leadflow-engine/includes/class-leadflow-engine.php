<?php

class Leadflow_Engine {

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
    }

    private function load_dependencies() {
        // Load modules.
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/user-gatekeeper/user-gatekeeper.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/landing-page-creator/landing-page-creator.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'modules/analytics-dashboard/analytics-dashboard.php';
    }

    public function run() {
        // The plugin is loaded and running.
    }
}
