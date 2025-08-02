<?php
/**
 * Block Pattern: Webinar Signup
 */

register_block_pattern(
    'leadflow-engine/webinar-signup',
    [
        'title'       => __( 'Webinar Signup', 'leadflow-engine' ),
        'description' => _x( 'A layout for a webinar signup page.', 'Block pattern description', 'leadflow-engine' ),
        'categories'  => [ 'leadflow-engine' ],
        'content'     => '<!-- wp:heading -->
                        <h2>Join Our Free Webinar</h2>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph -->
                        <p>Learn how to grow your business with our expert-led webinar. Reserve your spot now!</p>
                        <!-- /wp:paragraph -->

                        <!-- wp:columns -->
                        <div class="wp-block-columns"><!-- wp:column -->
                        <div class="wp-block-column"><!-- wp:html -->
                        <form>
                            <p><input type="text" placeholder="Your Name"></p>
                            <p><input type="email" placeholder="Your Email"></p>
                            <p><input type="submit" value="Sign Up Now"></p>
                        </form>
                        <!-- /wp:html --></div>
                        <!-- /wp:column --></div>
                        <!-- /wp:columns -->',
    ]
);
