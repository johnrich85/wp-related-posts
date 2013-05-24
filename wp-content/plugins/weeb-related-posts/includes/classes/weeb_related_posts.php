<?php
/**
 * weeb_related_posts
 *
 *
 * @author     John Richardson <johnrich85@hotmail.com>
 */

class weeb_related_posts {

    //Class properties
    private $num_posts;
    private $num_required;
    private $posts;
    private $query_args;
    private $related_query;

    public function __construct($num_required = 2) {

        $this->num_required = $num_required;

    }

    /**
     *
     * Recursive function - repeats x amount of times until the required
     * number of posts are found, or the search criteria can no longer
     * be diminished.
     *
     * @return mixed
     */

    public function getRelatedPosts() {

        //Run query.
        $this->related_query = new WP_Query($this->query_args);

        while ( $this->related_query->have_posts()  ) {
            $this->related_query->the_post();

            //Store posts to class property.
            $post = array();
            $post['title'] = get_the_title();
            $post['url'] = get_permalink();
            $post['image'] = get_the_post_thumbnail(get_the_ID());

            //Keeping track of the number of posts matched.
            $this->num_posts ++;

            //Maximum posts to be returned by next query.
            $this->query_args['posts_per_page'] -= $this->num_posts;

            //Keeping track of the exact posts matched, so that they are not returned more than once.
            $this->store_ID(get_the_ID());

            //Storing post details in array.
            $this->posts[] = $post;

        }

        //Check if enough posts returned
        if ($this->num_posts < $this->num_required && $this->diminish_search_criteria()) {
            return $this->getRelatedPosts();
        }
        else {

            return $this->posts;
        }

    }

    /**
     *
     * Makes the search criteria less restrictive on each call. Returns false
     * if this is no longer possible - used to halt the recursion in
     * 'getRelatedPosts()'
     *
     * @return bool
     */

    public function diminish_search_criteria() {

        //No longer restricting results by title
        if ( isset($this->query_args['search_prod_title'])) {
            unset($this->query_args['search_prod_title']);
            return true;
        }

        //No longer restricting results by category
        if ( isset($this->query_args['category__in'])) {
            unset($this->query_args['category__in']);
            return true;
        }

        return false;

    }

    /**
     *
     * Used to assign search parameters to class property.
     *
     * @param $argsArray Array containing parameters for WP_Query
     */

    public function set_query_args($argsArray) {
        //Defining arguments for wp_query.
        $this->query_args = $argsArray;
    }

    /**
     *
     * Returns the number of posts currently stored in $this->posts
     *
     * @return int
     */

    public function getNumPosts() {
        return $this->num_posts;
    }

    /**
     *
     * Adds an ID to the ignore list so that it will not be returned
     * in future queries.
     *
     * @param $id post id
     */

    public function store_ID($id) {
        if ( isset($this->query_args['post__not_in']) ) {
            $this->query_args['post__not_in'][] = $id;
        }
        else {
            $this->query_args['post__not_in'] = array();
            $this->query_args['post__not_in'][] = $id;
        }
    }



}