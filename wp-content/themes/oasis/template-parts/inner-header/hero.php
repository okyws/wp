<?php $component = \ColibriWP\Theme\View::getData( 'component' ); ?>
<div data-colibri-id="12-h24" id="hero" class="h-section h-hero d-flex align-items-lg-center align-items-md-center align-items-center style-57 style-local-12-h24 position-relative" style=" <?php $component->printPostFeaturedImage(); ?>">
  <?php $component = \ColibriWP\Theme\View::getData( 'component' ); ?>
      <?php $component->printBackground(); ?>
  <?php $component->printSeparator(); ?>
  <div class="h-section-grid-container h-navigation-padding h-section-boxed-container">
    <div data-colibri-id="12-h25" class="h-row-container gutters-row-lg-3 gutters-row-md-3 gutters-row-0 gutters-row-v-lg-0 gutters-row-v-md-0 gutters-row-v-0 style-58 style-local-12-h25 position-relative">
      <div class="h-row justify-content-lg-start justify-content-md-start justify-content-center align-items-lg-stretch align-items-md-stretch align-items-stretch gutters-col-lg-3 gutters-col-md-3 gutters-col-0 gutters-col-v-lg-0 gutters-col-v-md-0 gutters-col-v-0">
        <div class="h-column h-column-container d-flex h-col-lg-auto h-col-md-auto h-col-auto style-59-outer style-local-12-h26-outer">
          <div data-colibri-id="12-h26" class="d-flex h-flex-basis h-column__inner h-px-lg-3 h-px-md-3 h-px-3 v-inner-lg-3 v-inner-md-3 v-inner-3 style-59 style-local-12-h26 inner-hero-title-column position-relative">
            <div class="background-wrapper">
              <div class="background-layer background-layer-media-container-lg">
                <div class="overlay-layer">
                  <div class="overlay-image-layer" style="background-image: linear-gradient(-60deg, rgba(255, 88, 88, 0.8) 0%, rgba(240, 152, 25, 0.8) 100%);"></div>
                </div>
              </div>
              <div class="background-layer background-layer-media-container-md">
                <div class="overlay-layer">
                  <div class="overlay-image-layer" style="background-image: linear-gradient(-60deg, rgba(255, 88, 88, 0.8) 0%, rgba(240, 152, 25, 0.8) 100%);"></div>
                </div>
              </div>
              <div class="background-layer background-layer-media-container">
                <div class="overlay-layer">
                  <div class="overlay-image-layer" style="background-image: linear-gradient(-60deg, rgba(255, 88, 88, 0.8) 0%, rgba(240, 152, 25, 0.8) 100%);"></div>
                </div>
              </div>
            </div>
            <div class="w-100 h-y-container h-column__content h-column__v-align flex-basis-auto align-self-lg-end align-self-md-end align-self-end">
              <?php colibriwp_theme()->get('inner-title')->render(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
