( function( wp ) {
    wp.domReady( function() {
        const templates = document.querySelectorAll( '.lfe-template' );

        templates.forEach( ( template ) => {
            template.addEventListener( 'click', function() {
                const patternName = this.dataset.pattern;

                // Get all registered patterns.
                const patterns = wp.data.select( 'core/block-patterns' ).getBlockPatterns();
                // Find the selected pattern.
                const selectedPattern = patterns.find( p => p.name === patternName );

                if ( selectedPattern ) {
                    // The content is now available as a string of HTML.
                    const content = selectedPattern.content;
                    // The wp.blocks.parse() function can be used to convert a string of HTML into a block or a set of blocks.
                    const blocks = wp.blocks.parse( content );
                    // The insertBlocks function can be used to insert one or more blocks.
                    wp.data.dispatch( 'core/block-editor' ).insertBlocks( blocks );
                }
            } );
        } );
    } );
} )( window.wp );
