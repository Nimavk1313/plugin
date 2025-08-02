<?php
/**
 * Block Pattern: Lead Magnet Download
 */

register_block_pattern(
    'leadflow-engine/lead-magnet-download',
    [
        'title'       => __( 'Lead Magnet Download', 'leadflow-engine' ),
        'description' => _x( 'A simple layout for a lead magnet download.', 'Block pattern description', 'leadflow-engine' ),
        'categories'  => [ 'leadflow-engine' ],
        'content'     => '<!-- wp:heading -->
                        <h2>Download Our Free E-book</h2>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph -->
                        <p>Enter your email address below to get instant access to our exclusive e-book.</p>
                        <!-- /wp:paragraph -->

                        <!-- wp:buttons -->
                        <div class="wp-block-buttons"><!-- wp:button -->
                        <div class="wp-block-button"><a class="wp-block-button__link">Download Now</a></div>
                        <!-- /wp:button --></div>
                        <!-- /wp:buttons -->',
    ]
);
