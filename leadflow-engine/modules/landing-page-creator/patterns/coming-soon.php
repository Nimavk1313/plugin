<?php
/**
 * Block Pattern: Coming Soon
 */

register_block_pattern(
    'leadflow-engine/coming-soon',
    [
        'title'       => __( 'Coming Soon', 'leadflow-engine' ),
        'description' => _x( 'A simple coming soon page.', 'Block pattern description', 'leadflow-engine' ),
        'categories'  => [ 'leadflow-engine' ],
        'content'     => '<!-- wp:heading {"textAlign":"center","level":1} -->
                        <h1 class="has-text-align-center">Coming Soon</h1>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph {"align":"center"} -->
                        <p class="has-text-align-center">We are working hard to bring you something amazing. Stay tuned!</p>
                        <!-- /wp:paragraph -->',
    ]
);
