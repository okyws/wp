<?php
namespace ExtendBuilder;

add_shortcode('colibri_video_player', function ($atts) {
    ob_start();
   if($atts['type']==='external')
       colibri_html_embed_iframe( $atts['url'],$atts['autoplay']);
   else
       colibri_html_embed_video($atts['url'],$atts['attributes']);
   $content = ob_get_clean();
   return $content;
});

function colibri_html_embed_iframe($url,$autoplay){
    $iframe_html = "<iframe src=".$url." class='h-video-main'".(($autoplay === 'true') ? 'allow="autoplay"' : '')."  allowfullscreen></iframe>";
    echo $iframe_html;
}

function colibri_html_embed_video($url,$attributes){
    $video_html = "<video class='h-video-main' ".$attributes." ><source src=".$url." type='video/mp4' /></video>";
    echo $video_html;
}


