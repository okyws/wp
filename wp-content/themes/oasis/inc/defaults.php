<?php


$oasis_slideshow_defaults = array(
    "duration" => array( "value" => 1500 ),
    "speed"    => array( "value" => 500 ),
    "slides"   => array(
        array(
            'url' => get_template_directory_uri() . "/resources/images/beach-landscape-sea-water-nature-sand-1061655-pxhere.com.jpg",
        ),
        array(
            'url' => get_template_directory_uri() . "/resources/images/landscape-tree-water-nature-grass-outdoor-1327743-pxhere.com.jpg",
        ),
        array(
            'url' => get_template_directory_uri() . "/resources/images/leaf-nature-water-green-freshness-dew-1440543-pxhere.com.jpg",
        ),
    )
);

return array(
    'lorem_ipsum'       => 'Lorem ipsum dolor sit amet',
    'header_front_page' => array(
        'hero'         => array(
            'hero_column_width' => 100,
            'style'             => array(
                "background" => array(
                    'image'     =>
                        array(
                            0 =>
                                array(
                                    'source' =>
                                        array(
                                            'url' => get_stylesheet_directory_uri() . "/resources/images/our-waves-2.png",
                                        )
                                )
                        ),
                    "slideshow" => $oasis_slideshow_defaults,
                    'overlay'   => array(
                        'type'  => 'color',
                        'color' => array(
                            'opacity' => 50
                        ),
                        'shape' =>
                            array(
                                'value'  => 'none',
                                'isTile' => false,
                            ),
                    )

                )
            )
        ),
        'title'        => array(
            'style' => array(
                'textAlign' => 'center',
            )
        ),
        'subtitle'     => array(
            'style' => array(
                'textAlign' => 'center',
            ),
            "value" => '',

        ),
        'button_group' => array(
            'style' => array(
                'textAlign' => 'center',
            )
        ),
    ),

    'header_post' => array(
        'hero'  => array(
            'style' => array(
                "background" => array(
                    'image'     =>
                        array(
                            0 =>
                                array(
                                    'source' =>
                                        array(
                                            'url' => get_stylesheet_directory_uri() . "/resources/images/inner-hero.jpg",
                                        )
                                )
                        ),
                    "slideshow" => $oasis_slideshow_defaults,
                    'overlay'   => array(
                        'type'    => 'color',
                        'enabled' => true,
                        'color'   => array(
                            'opacity' => 85
                        ),
                        'shape'   =>
                            array(
                                'value'  => 'none',
                                'isTile' => false,
                            ),
                    )

                )
            )
        ),
        'title' => array(
            'show' => true
        )
    ),

    'footer_post' =>
        array(
            'footer' =>
                array(

                    'props' =>
                        array(
                            'useFooterParallax' => false,
                        ),
                ),
        ),

    'blog_posts_per_row'                => 1,
    'blog_show_post_thumb_placeholder'  => true,
    'blog_post_thumb_placeholder_color' => 'rgb(247, 144, 7)'
);
