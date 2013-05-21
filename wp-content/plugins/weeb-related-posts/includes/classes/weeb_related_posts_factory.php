<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Aziz
 * Date: 20/05/13
 * Time: 16:29
 * To change this template use File | Settings | File Templates.
 */

class weeb_related_posts_factory {

    public static function create($post, $required_posts) {

        //Instantiating object to retrieve post details.
        $postProperties = new weeb_relatable_properties($post);

        //Setting the required properties.
        $postProperties->setCategories();
        $postProperties->setKeywords();
        $postProperties->setTags();

        $getRelatedPosts = new weeb_related_posts($required_posts);

        //Setting the parameters for WP_query.
        $getRelatedPosts->set_query_args(
            array(
                //Returning posts in these categories only
                'category__in' => $postProperties->getCategories(),
                //Returning posts with these tags.
                'tag__in' => $postProperties->getTags(),
                //Exclude these posts - ontains a single post only to start with(the post currently being viewed).
                'post__not_in' => array($postProperties->getID()),
                //Max number of posts to return.
                'posts_per_page'=> $required_posts,
                //Custom parameter, used to add additional SQL to query.
                'search_prod_title' => $postProperties->getKeywords()
            )
        );

        return $getRelatedPosts;

    }

}