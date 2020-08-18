<div data-colibri-id="6-h24" id="hero" class="h-section h-hero d-flex align-items-lg-center align-items-md-center align-items-center style-24 style-local-6-h24 position-relative">
  <?php $component = \ColibriWP\Theme\View::getData( 'component' ); ?>
      <?php $component->printBackground(); ?>
  <?php $component->printSeparator(); ?>
  <div class="h-section-grid-container h-navigation-padding h-section-boxed-container">
    <div data-colibri-id="6-h25" class="h-row-container gutters-row-lg-0 gutters-row-md-0 gutters-row-2 gutters-row-v-lg-0 gutters-row-v-md-0 gutters-row-v-2 style-25 style-local-6-h25 position-relative">
      <div class="h-row justify-content-lg-start justify-content-md-center justify-content-start align-items-lg-stretch align-items-md-stretch align-items-stretch gutters-col-lg-0 gutters-col-md-0 gutters-col-2 gutters-col-v-lg-0 gutters-col-v-md-0 gutters-col-v-2">
        <div class="h-column h-column-container d-flex h-col-lg-auto h-col-md-auto h-col-auto style-26-outer style-local-6-h26-outer">
          <div data-colibri-id="6-h26" class="d-flex h-flex-basis h-column__inner h-px-lg-0 h-px-md-0 h-px-0 v-inner-lg-3 v-inner-md-3 v-inner-3 style-26 style-local-6-h26 position-relative">
            <div class="w-100 h-y-container h-column__content h-column__v-align flex-basis-100 align-self-lg-center align-self-md-center align-self-center">
              <?php colibriwp_theme()->get('front-title')->render(); ?>
                              <?php colibriwp_theme()->get('front-subtitle')->render(); ?>
                              <?php colibriwp_theme()->get('front-buttons')->render(); ?>
            </div>
          </div>
        </div>
        <div class="h-column h-column-container d-flex h-col-lg h-col-md-auto h-col-auto style-32-outer style-local-6-h32-outer">
          <div data-colibri-id="6-h32" class="d-flex h-flex-basis h-column__inner h-px-lg-0 h-px-md-0 h-px-0 v-inner-lg-0 v-inner-md-0 v-inner-0 style-32 style-local-6-h32 position-relative">
            <div class="w-100 h-y-container h-column__content h-column__v-align flex-basis-100 align-self-lg-center align-self-md-center align-self-center">
              <?php colibriwp_theme()->get('front-image')->render(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
