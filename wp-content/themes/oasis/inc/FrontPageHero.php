<?php


namespace ExtendThemes\Oasis;


use ColibriWP\Theme\Components\CSSOutput;
use ColibriWP\Theme\Components\FrontHeader\Hero;

class FrontPageHero extends Hero {

    protected static function getOptions( $include_content_settings = true ) {
        $options = Hero::getOptions( $include_content_settings );

        $texts_selectors = array(
            Hero::selectiveRefreshSelector() . " h1",
            Hero::selectiveRefreshSelector() . " .h-text p"
        );

        $options['settings'] = array_merge( (array) $options['settings'],
            array(
                "header_front_page.hero.separator-before-white-texts" => array(
                    'default' => '',
                    'control' => array(
                        'label'       => '',
                        'type'        => 'separator',
                        'section'     => 'hero',
                        'colibri_tab' => 'style',
                    ),
                ),

                'header_front_page.hero.style.white-texts' => array(
                    'default'    => false,
                    'control'    => array(
                        'label'       => __( 'Make texts white', 'oasis' ),
                        'type'        => 'switch',
                        'section'     => 'hero',
                        'colibri_tab' => 'style',
                    ),
                    'css_output' => array(
                        array(
                            'selector'    => implode( ",", $texts_selectors ),
                            'media'       => CSSOutput::NO_MEDIA,
                            'property'    => 'color',
                            'true_value'  => '#ffffff !important',
                            'false_value' => 'inherit',
                        ),
                    ),
                )
            )
        );

        return $options;
    }
}
