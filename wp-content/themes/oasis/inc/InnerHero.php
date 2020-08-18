<?php

namespace ExtendThemes\Oasis;

use ColibriWP\Theme\Components\InnerHeader\Hero;
use ColibriWP\Theme\Defaults;

class InnerHero extends Hero {


    public function renderContent() {
        if ( $this->mod( 'header_post.title.show', Defaults::get( 'header_post.title.show', true ) ) ) {
            parent::renderContent();
        } else {
            $this->printPlaceholder();
        }

    }

    public function printPlaceholder() {
        if ( is_customize_preview() ) {
            ?>
            <div class="hide">
                <?php parent::renderContent(); ?>
            </div>
            <?php
        }

        $selector = Hero::selectiveRefreshSelector();
        $selector = trim( $selector, '[]' );
        ?>
        <div class="oasis-inner-hero-placeholder" <?php echo $selector; ?>></div>
        <?php
    }
}
