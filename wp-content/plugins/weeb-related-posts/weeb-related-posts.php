<?php
/*
Plugin Name: Weeb related posts
Plugin URI: http://weebtutorials.com
Description: A simple related posts plugin made for demonstration purposes.
Version: 1.0
Author: John Richardson
Author URI: http://weebtutorials.com
License: GPL2

    Copyright 2013  John Richardson  (email : johnrich85@hotmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//Defining Constants
define( 'WEEB_RELATED_POSTS_PLUGIN_DIR', plugin_dir_path( __FILE__ ).'/' );
define( 'WEEB_RELATED_POSTS_WEB_PATH', plugins_url("weeb-related-posts") ."/");

//Hooks function to 'the_content' filter.
add_filter('the_content','related_posts');

//Callback function for the above.
function related_posts($content)
{
    //Gives us access to the global post object.
    global $post;

	//Checking if on post page.
	if ( is_single() ) {

        //Including classes
        include(WEEB_RELATED_POSTS_PLUGIN_DIR."includes/classes/weeb_relatable_properties.php");
        include(WEEB_RELATED_POSTS_PLUGIN_DIR."includes/classes/weeb_related_posts.php");
        include(WEEB_RELATED_POSTS_PLUGIN_DIR."includes/classes/weeb_related_posts_factory.php");

        //Other includes
        include(WEEB_RELATED_POSTS_PLUGIN_DIR."includes/weeb_functions.php");

        //Using a factory to abstract away the process of instantiating related posts object.
        $getRelatedPosts = weeb_related_posts_factory::create($post, 2);

        //Adding additional SQL to WP_Query temporarily - used to search title for any of the keywords.
        add_filter( 'posts_where', 'weeb_related_posts_title_filter', 10, 2 );

        //Fetching the related posts.
        $related = $getRelatedPosts->getRelatedPosts();

        //Remove the filter, no longer needed.
        remove_filter('posts_where', 'weeb_related_posts_title_filter');

        //Checking for related posts.
        if ( count($related) > 0 ) {
            //Add the stylesheet.
            wp_enqueue_style( 'myPluginStylesheet', plugins_url('css/weeb_related_posts.css', __FILE__)  );

            //Storing HTML to variable.
            $rel_html = weeb_related_posts_get_template(WEEB_RELATED_POSTS_PLUGIN_DIR ."templates/related-posts.php", $related);

            //Adding custom content to end of post.
            return $content . $rel_html;
        }
        //no posts, return content as usual.
        else {
            return $content;
        }
	}
	else {
		//else on blog page / home page etc, just return content as usual.
		return $content;
	}
}
