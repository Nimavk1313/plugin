<?php
/**
 * Module: Analytics Dashboard
 *
 * @since 1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Track landing page impressions.
 */
function lfe_track_landing_page_impressions() {
    if ( is_singular( 'landing_page' ) ) {
        $post_id = get_the_ID();
        $cookie_name = 'lfe_viewed_landing_page_' . $post_id;

        if ( ! isset( $_COOKIE[ $cookie_name ] ) ) {
            $count = get_post_meta( $post_id, '_lfe_impression_count', true );
            $count = $count ? (int) $count : 0;
            $count++;
            update_post_meta( $post_id, '_lfe_impression_count', $count );
            setcookie( $cookie_name, '1', time() + 3600, COOKIEPATH, COOKIE_DOMAIN );
        }
    }
}
add_action( 'wp_head', 'lfe_track_landing_page_impressions' );

/**
 * Add the analytics page to the Landing Pages menu.
 */
function lfe_add_analytics_page() {
    add_submenu_page(
        'edit.php?post_type=landing_page',
        __( 'Analytics', 'leadflow-engine' ),
        __( 'Analytics', 'leadflow-engine' ),
        'manage_options',
        'lfe-analytics',
        'lfe_render_analytics_page'
    );
}
add_action( 'admin_menu', 'lfe_add_analytics_page' );

/**
 * Render the analytics page.
 */
function lfe_render_analytics_page() {
    $args = [
        'post_type' => 'landing_page',
        'posts_per_page' => -1,
    ];
    $query = new WP_Query( $args );
    ?>
    <div class="wrap">
        <h1><?php _e( 'Landing Page Analytics', 'leadflow-engine' ); ?></h1>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e( 'Landing Page', 'leadflow-engine' ); ?></th>
                    <th><?php _e( 'Impressions', 'leadflow-engine' ); ?></th>
                    <th><?php _e( 'Date Published', 'leadflow-engine' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ( $query->have_posts() ) : ?>
                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                        <tr>
                            <td>
                                <a href="<?php echo get_edit_post_link(); ?>"><?php the_title(); ?></a>
                            </td>
                            <td>
                                <?php echo get_post_meta( get_the_ID(), '_lfe_impression_count', true ) ?: 0; ?>
                            </td>
                            <td>
                                <?php echo get_the_date(); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3"><?php _e( 'No landing pages found.', 'leadflow-engine' ); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
