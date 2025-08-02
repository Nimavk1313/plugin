<?php
/**
 * Module: Landing Page Creator
 *
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Register the Landing Page Custom Post Type.
 */
function lfe_register_landing_page_cpt() {
    $labels = [
        'name'                  => _x( 'Landing Pages', 'Post Type General Name', 'leadflow-engine' ),
        'singular_name'         => _x( 'Landing Page', 'Post Type Singular Name', 'leadflow-engine' ),
        'menu_name'             => __( 'Landing Pages', 'leadflow-engine' ),
        'name_admin_bar'        => __( 'Landing Page', 'leadflow-engine' ),
        'archives'              => __( 'Landing Page Archives', 'leadflow-engine' ),
        'attributes'            => __( 'Landing Page Attributes', 'leadflow-engine' ),
        'parent_item_colon'     => __( 'Parent Landing Page:', 'leadflow-engine' ),
        'all_items'             => __( 'All Landing Pages', 'leadflow-engine' ),
        'add_new_item'          => __( 'Add New Landing Page', 'leadflow-engine' ),
        'add_new'               => __( 'Add New', 'leadflow-engine' ),
        'new_item'              => __( 'New Landing Page', 'leadflow-engine' ),
        'edit_item'             => __( 'Edit Landing Page', 'leadflow-engine' ),
        'update_item'           => __( 'Update Landing Page', 'leadflow-engine' ),
        'view_item'             => __( 'View Landing Page', 'leadflow-engine' ),
        'view_items'            => __( 'View Landing Pages', 'leadflow-engine' ),
        'search_items'          => __( 'Search Landing Page', 'leadflow-engine' ),
        'not_found'             => __( 'Not found', 'leadflow-engine' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'leadflow-engine' ),
        'featured_image'        => __( 'Featured Image', 'leadflow-engine' ),
        'set_featured_image'    => __( 'Set featured image', 'leadflow-engine' ),
        'remove_featured_image' => __( 'Remove featured image', 'leadflow-engine' ),
        'use_featured_image'    => __( 'Use as featured image', 'leadflow-engine' ),
        'insert_into_item'      => __( 'Insert into landing page', 'leadflow-engine' ),
        'uploaded_to_this_item' => __( 'Uploaded to this landing page', 'leadflow-engine' ),
        'items_list'            => __( 'Landing pages list', 'leadflow-engine' ),
        'items_list_navigation' => __( 'Landing pages list navigation', 'leadflow-engine' ),
        'filter_items_list'     => __( 'Filter landing pages list', 'leadflow-engine' ),
    ];
    $args = [
        'label'                 => __( 'Landing Page', 'leadflow-engine' ),
        'description'           => __( 'Post Type for Landing Pages', 'leadflow-engine' ),
        'labels'                => $labels,
        'supports'              => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-analytics',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
    ];
    register_post_type( 'landing_page', $args );
}
add_action( 'init', 'lfe_register_landing_page_cpt', 0 );

/**
 * Register block patterns and categories.
 */
function lfe_register_block_patterns() {
    register_block_pattern_category(
        'leadflow-engine',
        [ 'label' => __( 'LeadFlow Engine', 'leadflow-engine' ) ]
    );

    require_once plugin_dir_path( __FILE__ ) . 'patterns/lead-magnet-download.php';
}
add_action( 'init', 'lfe_register_block_patterns' );

/**
 * Add the templates meta box.
 */
function lfe_add_templates_meta_box() {
    add_meta_box(
        'lfe_templates_meta_box',
        __( 'Landing Page Templates', 'leadflow-engine' ),
        'lfe_render_templates_meta_box',
        'landing_page',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'lfe_add_templates_meta_box' );

/**
 * Render the templates meta box.
 */
function lfe_render_templates_meta_box() {
    $patterns = WP_Block_Pattern_Registry::get_instance()->get_all_registered();
    ?>
    <div class="lfe-templates-wrapper">
        <ul>
            <?php foreach ( $patterns as $pattern ) : ?>
                <?php if ( in_array( 'leadflow-engine', $pattern['categories'] ) ) : ?>
                    <li class="lfe-template" data-pattern="<?php echo esc_attr( $pattern['name'] ); ?>" style="cursor: pointer; padding: 5px; border-bottom: 1px solid #eee;">
                        <?php echo esc_html( $pattern['title'] ); ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
}

/**
 * Enqueue admin scripts.
 *
 * @param string $hook The current admin page.
 */
function lfe_enqueue_admin_scripts( $hook ) {
    global $post;

    if ( ( $hook == 'post-new.php' || $hook == 'post.php' ) && 'landing_page' === $post->post_type ) {
        wp_enqueue_script(
            'lfe-template-inserter',
            plugin_dir_url( __FILE__ ) . 'js/template-inserter.js',
            [ 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ],
            '1.0.0',
            true
        );
    }
}
add_action( 'admin_enqueue_scripts', 'lfe_enqueue_admin_scripts' );
