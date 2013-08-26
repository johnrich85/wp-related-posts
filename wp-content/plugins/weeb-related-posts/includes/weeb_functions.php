<?php

function weeb_related_posts_get_template( $template, $data )
{
    ob_start();
    $test = include($template);
    $echoed_content = ob_get_clean();
    return $echoed_content ;

}