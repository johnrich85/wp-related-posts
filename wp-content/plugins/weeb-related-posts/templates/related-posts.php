<h3 id="weeb-related-title"> You may also like these posts: </h3>

<ul id="weeb-related-posts">
    <?php
        foreach ( $data as $the_post) {
            echo "<li>";
            echo "<a href=\"".$the_post['url'] ."\">";

            if ( $the_post['image'] ) {
                echo $the_post['image'];
            }
            else {
                echo "<img src=\"".WEEB_RELATED_POSTS_WEB_PATH."img/no_image_medium.gif\" alt=\"no-image\" />";
            }


            echo "<h4> ".$the_post['title']."</h4>";
            echo "</a>";
            echo "</li>";
        }
    ?>
</ul>