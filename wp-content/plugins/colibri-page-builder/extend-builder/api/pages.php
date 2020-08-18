<?php

namespace ExtendBuilder;

function wp_colibri_v1_duplicate_page($req)
{
    if(!colibri_user_can_customize()) {
        return new \WP_REST_Response(null, 401);
    }
    $post_id = $req['postId'];
    $title = $req['title'];
    $new_post_id = colibri_duplicate_post_as_draft($post_id, $title);
    $post = get_post($new_post_id);
    $data = (new PageData($post->post_title, get_permalink($post), null, $post->ID))->toArray();
    return json_encode($data);
}


add_action('rest_api_init', function () {
    register_rest_route('colibri/v1', '/pages/duplicate', array(
        'methods' => 'POST',
        'callback' => '\ExtendBuilder\wp_colibri_v1_duplicate_page',
        'permission_callback' => function () {
            return current_user_can( 'edit_theme_options' );
        }
    ));
});
