<div class="<?php colibriwp_print_archive_entry_class("h-column h-column-container d-flex  masonry-item style-111-outer style-local-23-m4-outer");?>" data-masonry-width="<?php colibriwp_print_masonry_col_class(true); ?>">
  <div data-colibri-id="23-m4" class="d-flex h-flex-basis h-column__inner h-px-lg-0 h-px-md-0 h-px-0 v-inner-lg-0 v-inner-md-0 v-inner-0 style-111 style-local-23-m4 position-relative">
    <div class="w-100 h-y-container h-column__content h-column__v-align flex-basis-100 align-self-lg-start align-self-md-start align-self-start">
      <div data-colibri-id="23-m5" class="h-row-container gutters-row-lg-3 gutters-row-md-3 gutters-row-3 gutters-row-v-lg-3 gutters-row-v-md-3 gutters-row-v-3 style-112 style-local-23-m5 position-relative">
        <div class="h-row justify-content-lg-center justify-content-md-center justify-content-center align-items-lg-stretch align-items-md-stretch align-items-stretch gutters-col-lg-3 gutters-col-md-3 gutters-col-3 gutters-col-v-lg-3 gutters-col-v-md-3 gutters-col-v-3">
          <div class="h-column h-column-container d-flex h-col-lg-auto h-col-md-auto h-col-auto style-113-outer style-local-23-m6-outer">
            <div data-colibri-id="23-m6" class="d-flex h-flex-basis h-column__inner h-px-lg-0 h-px-md-0 h-px-0 v-inner-lg-0 v-inner-md-0 v-inner-0 style-113 style-local-23-m6 position-relative">
              <div class="w-100 h-y-container h-column__content h-column__v-align flex-basis-100 align-self-lg-start align-self-md-start align-self-start">
                <div data-href="<?php the_permalink(); ?>" data-colibri-component="link" data-colibri-id="23-m7" class="colibri-post-thumbnail <?php colibriwp_post_thumbnail_classes(); ?> <?php colibriwp_post_thumb_placeholder_classes(); ?> style-114 style-local-23-m7 h-overflow-hidden position-relative h-element">
                  <div class="h-global-transition-all colibri-post-thumbnail-shortcode style-dynamic-23-m7-height">
                    <?php colibriwp_post_thumbnail(array (
                      'link' => true,
                    )); ?>
                  </div>
                  <div class="colibri-post-thumbnail-content align-items-lg-center align-items-md-center align-items-center flex-basis-100">
                    <div class="w-100 h-y-container"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="h-column h-column-container d-flex h-col-lg-auto h-col-md-auto h-col-auto style-115-outer style-local-23-m8-outer">
            <div data-colibri-id="23-m8" class="d-flex h-flex-basis h-column__inner h-px-lg-0 h-px-md-0 h-px-0 v-inner-lg-0 v-inner-md-0 v-inner-0 style-115 style-local-23-m8 position-relative">
              <div class="w-100 h-y-container h-column__content h-column__v-align flex-basis-100 align-self-lg-start align-self-md-start align-self-start">
                <?php if ( \ColibriWP\Theme\Core\Hooks::prefixed_apply_filters( 'show_post_meta', true ) ): ?>
                <div data-colibri-id="23-m9" class="h-blog-meta style-325 style-local-23-m9 position-relative h-element">
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
                      <?php colibriwp_the_date('M j'); ?>
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
                <div data-colibri-id="23-m10" class="h-blog-title style-117 style-local-23-m10 position-relative h-element">
                  <div class="h-global-transition-all">
                    <?php colibriwp_post_title(array (
                      'heading_type' => 'h4',
                      'classes' => 'colibri-word-wrap',
                    )); ?>
                  </div>
                </div>
                <div data-colibri-id="23-m11" class="style-118 style-local-23-m11 position-relative h-element">
                  <div class="h-global-transition-all">
                    <?php colibriwp_post_excerpt(array (
                      'max_length' => 18,
                    )); ?>
                  </div>
                </div>
                <div data-colibri-id="23-m12" class="h-x-container style-119 style-local-23-m12 position-relative h-element">
                  <div class="h-x-container-inner style-dynamic-23-m12-group">
                    <span class="h-button__outer style-120-outer style-local-23-m13-outer d-inline-flex h-element">
                      <a h-use-smooth-scroll="true" href="<?php the_permalink(); ?>" data-colibri-id="23-m13" class="d-flex w-100 align-items-center h-button justify-content-lg-center justify-content-md-center justify-content-center style-120 style-local-23-m13 position-relative">
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
    </div>
  </div>
</div>
