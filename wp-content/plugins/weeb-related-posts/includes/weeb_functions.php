<?php

/**
 *
 * Filter used to add additional sql to wp_query.
 *
 * @param $where
 * @param $wp_query
 * @return string
 */
function weeb_related_posts_title_filter( $where, &$wp_query )
{
    global $wpdb;
    if ( $search_term = $wp_query->get( 'search_prod_title' ) ) {

        $count = 0;
        foreach ( $wp_query->get( 'search_prod_title' ) as $keyword ) {

            if ( $count == 0 ) {
                $where .= ' AND (' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( like_escape( $keyword ) ) . '%\'';
            }
            else {
                $where .= ' OR ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( like_escape( $keyword ) ) . '%\'';
            }


            $count++;
        }

        $where .= ")";

    }

    return $where;
}

function weeb_related_posts_get_template( $template, $data )
{
    ob_start();
    $test = include($template);
    $echoed_content = ob_get_clean();
    return $echoed_content ;

}