<div data-colibri-id="29-m4" class="h-row-container gutters-row-lg-0 gutters-row-md-0 gutters-row-0 gutters-row-v-lg-0 gutters-row-v-md-0 gutters-row-v-0 style-138 style-local-29-m4 position-relative">
  <div class="h-row justify-content-lg-center justify-content-md-center justify-content-center align-items-lg-stretch align-items-md-stretch align-items-stretch gutters-col-lg-0 gutters-col-md-0 gutters-col-0 gutters-col-v-lg-0 gutters-col-v-md-0 gutters-col-v-0">
    <div class="h-column h-column-container d-flex h-col-lg-auto h-col-md-auto h-col-auto style-139-outer style-local-29-m5-outer">
      <div data-colibri-id="29-m5" class="d-flex h-flex-basis h-column__inner h-px-lg-3 h-px-md-3 h-px-3 v-inner-lg-3 v-inner-md-3 v-inner-3 style-139 style-local-29-m5 position-relative">
        <div class="w-100 h-y-container h-column__content h-column__v-align flex-basis-100 align-self-lg-start align-self-md-start align-self-start">
          <div data-colibri-id="29-m6" class="h-blog-categories style-140 style-local-29-m6 position-relative h-element">
            <div class="h-global-transition-all">
              <?php colibriwp_post_categories(array (
                'prefix' => '',
              )); ?>
            </div>
          </div>
          <div data-colibri-id="29-m7" class="h-blog-title style-141 style-local-29-m7 position-relative h-element">
            <div class="h-global-transition-all">
              <?php colibriwp_post_title(array (
                'heading_type' => 'h4',
                'classes' => 'colibri-word-wrap',
              )); ?>
            </div>
          </div>
          <?php if ( \ColibriWP\Theme\Core\Hooks::prefixed_apply_filters( 'show_post_meta', true ) ): ?>
          <div data-colibri-id="29-m8" class="h-blog-meta style-325 style-local-29-m8 position-relative h-element">
            <div name="1" class="metadata-item">
              <span class="metadata-prefix">
                <?php esc_html_e('by','oasis'); ?>
              </span>
              <a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) )); ?>">
                <?php echo esc_html(get_the_author_meta( 'display_name', get_the_author_meta( 'ID' ) )); ?>
              </a>
              <span class="meta-separator">| </span>
            </div>
            <div name="2" class="metadata-item">
              <span class="metadata-prefix">
                <?php esc_html_e('on','oasis'); ?>
              </span>
              <a href="<?php colibriwp_post_meta_date_url(); ?>">
                <?php colibriwp_the_date('F j, Y'); ?>
              </a>
              <span class="meta-separator">| </span>
            </div>
            <div name="3" class="metadata-item">
              <span class="metadata-prefix">
                <?php esc_html_e('at','oasis'); ?>
              </span>
              <a href="<?php colibriwp_post_meta_time_url(); ?>">
                <?php echo esc_html(get_the_time()); ?>
              </a>
            </div>
          </div>
          <?php endif; ?>
          <div data-colibri-id="29-m9" class="style-143 style-local-29-m9 position-relative h-element">
            <div class="h-global-transition-all">
              <?php colibriwp_post_excerpt(array (
                'max_length' => '',
              )); ?>
            </div>
          </div>
          <div data-colibri-id="29-m10" class="h-x-container style-144 style-local-29-m10 position-relative h-element">
            <div class="h-x-container-inner style-dynamic-29-m10-group">
              <span class="h-button__outer style-145-outer style-local-29-m11-outer d-inline-flex h-element">
                <a h-use-smooth-scroll="true" href="<?php the_permalink(); ?>" data-colibri-id="29-m11" class="d-flex w-100 align-items-center h-button justify-content-lg-center justify-content-md-center justify-content-center style-145 style-local-29-m11 position-relative">
                  <span>
                    <?php esc_html_e('read more','oasis'); ?>
                  </span>
                </a>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
