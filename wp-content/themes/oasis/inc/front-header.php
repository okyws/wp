<?php return 
array (
  'navigation' => 
  array (
    'props' => 
    array (
      'showTopBar' => true,
      'sticky' => true,
      'overlap' => true,
      'width' => 'boxed',
      'layoutType' => 'logo-spacing-menu',
    ),
    'style' => 
    array (
      'ancestor' => 
      array (
        'sticky' => 
        array (
          'background' => 
          array (
            'color' => '#ffffff',
          ),
        ),
      ),
      'background' => 
      array (
        'color' => '${theme.colors.4}',
      ),
      'padding' => 
      array (
        'top' => 
        array (
          'value' => 20,
        ),
      ),
    ),
  ),
  'logo' => 
  array (
    'props' => 
    array (
      'layoutType' => 'logo-spacing-menu',
    ),
  ),
  'header-menu' => 
  array (
    'props' => 
    array (
      'sticky' => true,
      'hoverEffect' => 
      array (
        'type' => 'bordered-active-item bordered-active-item--bottom',
        'group' => 
        array (
          'border' => 
          array (
            'transition' => 'effect-borders-grow grow-from-center',
          ),
        ),
        'activeGroup' => 'border',
        'enabled' => true,
      ),
      'showOffscreenMenuOn' => 'has-offcanvas-tablet',
    ),
  ),
  'title' => 
  array (
    'style' => 
    array (
      'textAlign' => 'center',
    ),
  ),
  'hero' => 
  array (
    'style' => 
    array (
      'background' => 
      array (
        'type' => 'image',
        'color' => '${theme.colors.2}',
        'overlay' => 
        array (
          'shape' => 
          array (
            'value' => '',
            'isTile' => false,
          ),
          'light' => false,
          'color' => 
          array (
            'value' => '${theme.colors.4}',
            'opacity' => '0.6',
          ),
          'enabled' => true,
          'type' => 'gradient',
          'gradient' => 
          array (
            'name' => 'october_silence',
            'angle' => 0,
            'steps' => 
            array (
              0 => 
              array (
                'position' => '0',
                'color' => '#b721ff',
              ),
              1 => 
              array (
                'position' => '100',
                'color' => '#21d4fd',
              ),
            ),
          ),
        ),
        'image' => 
        array (
          0 => 
          array (
            'source' => 
            array (
              'url' => 'our-waves-2.png',
              'gradient' => 
              array (
                'name' => 'october_silence',
                'angle' => 0,
                'steps' => 
                array (
                  0 => 
                  array (
                    'position' => '0',
                    'color' => '#b721ff',
                  ),
                  1 => 
                  array (
                    'position' => '100',
                    'color' => '#21d4fd',
                  ),
                ),
              ),
            ),
            'attachment' => 'scroll',
            'position' => 'center center',
            'repeat' => 'no-repeat',
            'size' => 'custom',
            'useParallax' => false,
          ),
        ),
        'slideshow' => 
        array (
          'duration' => 
          array (
            'value' => 1500,
          ),
          'speed' => 
          array (
            'value' => 1500,
          ),
          'slides' => 
          array (
            0 => 
            array (
              'url' => 'http://wpsites.extendstudio.net/wp/defaults/oasis-new-default/wp-content/themes/colibri-wp/resources/images/beach-landscape-sea-water-nature-sand-1061655-pxhere.com.jpg',
            ),
            1 => 
            array (
              'url' => 'http://wpsites.extendstudio.net/wp/defaults/oasis-new-default/wp-content/themes/colibri-wp/resources/images/landscape-tree-water-nature-grass-outdoor-1327743-pxhere.com.jpg',
            ),
            2 => 
            array (
              'url' => 'http://wpsites.extendstudio.net/wp/defaults/oasis-new-default/wp-content/themes/colibri-wp/resources/images/leaf-nature-water-green-freshness-dew-1440543-pxhere.com.jpg',
            ),
          ),
        ),
        'video' => 
        array (
          'videoType' => 'external',
          'externalUrl' => 'https://www.youtube.com/watch?v=coYirc_qoSA',
          'internalUrl' => false,
          'poster' => 
          array (
            'url' => 'http://wpsites.extendstudio.net/wp/defaults/oasis-new-default/wp-content/uploads/2020/07/colibri-demo-video-cover.jpg',
          ),
        ),
      ),
      'padding' => 
      array (
        'top' => 
        array (
          'value' => '100',
          'unit' => 'px',
        ),
        'bottom' => 
        array (
          'value' => '0',
          'unit' => 'px',
        ),
      ),
      'separatorBottom' => 
      array (
        'enabled' => false,
        'type' => 'mountains',
        'color' => '#FFF',
        'height' => 
        array (
          'value' => 100,
        ),
        'negative' => false,
      ),
    ),
    'props' => 
    array (
      'layoutType' => 'textWithMediaOnRight',
      'heroSection' => 
      array (
        'layout' => 'textOnly',
      ),
    ),
  ),
);
