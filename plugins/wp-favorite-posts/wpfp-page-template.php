<?php
    $wpfp_before = "";
    echo "<div class='wpfp-span'>";
    if (!empty($user)) {
        if (wpfp_is_user_favlist_public($user)) {
            $wpfp_before = "$user's Favorite Posts.";
        } else {
            $wpfp_before = "$user's list is not public.";
        }
    }

    if ($wpfp_before):
        echo '<div class="wpfp-page-before">'.$wpfp_before.'</div>';
    endif;

    if ($favorite_post_ids) {
		$favorite_post_ids = array_reverse($favorite_post_ids);
        $post_per_page = wpfp_get_option("post_per_page");
        $page = intval(get_query_var('paged'));

        $qry = array('post__in' => $favorite_post_ids, 'posts_per_page'=> $post_per_page, 'orderby' => 'post__in', 'paged' => $page);
        // custom post type support can easily be added with a line of code like below.
        // $qry['post_type'] = array('post','page');
        query_posts($qry);

        // Подготовка данных для проверки пройден ли курс полностью, для того чтобы убрать его из страницы "Мои массивы"
        // для того чтобы убрать его из страницы "Мои массивы"
        global $wpdb;
        $user_id = get_current_user_id();
        $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
        $curses_count = 0;
        echo "<ul>";
        while ( have_posts() ) : the_post();
        $my_array_post_id = get_the_ID();
        $sql  = "SELECT * FROM `$table_name` WHERE `user_id` = '{$user_id}' ";
        $sql .= "AND `post_id` = '{$my_array_post_id}'";
        $progress = $wpdb->get_row($sql);
        $lessons_count = $progress -> lessons_count;

        if($progress->checked_lessons != 0) 
        {
            $checked_lessons = explode(',', $progress->checked_lessons);
            $checked_lessons_count = count($checked_lessons);
        } else {
            $checked_lessons_count = 0;
        }
        //проверка если курс пройден не полностью, то показывать его на странице 
        // "Мои массивы", в противном случае, курс будет показан на странице "Моя зачётка"
		if($lessons_count != $checked_lessons_count ) {
            echo "<li><a href='".get_permalink(). get_first_unchecked_lesson(get_the_ID()) ."' title='". get_the_title() ."'>" . get_the_title() ."</a> ";
                wpfp_remove_favorite_link(get_the_ID());
                diductio_add_progress(get_the_ID());            
            echo "</li>";
            $curses_count ++;
        }
        endwhile;
        if($curses_count == 0) {
            echo "<li>Мои массивы пусты</li>";
        }
        echo "</ul>";

        echo '<div class="navigation">';
            if(function_exists('wp_pagenavi')) { wp_pagenavi(); } else { ?>
            <div class="alignleft"><?php next_posts_link( __( '&larr; Previous Entries', 'buddypress' ) ) ?></div>
            <div class="alignright"><?php previous_posts_link( __( 'Next Entries &rarr;', 'buddypress' ) ) ?></div>
            <?php }
        echo '</div>';

        wp_reset_query();
    } else {
        $wpfp_options = wpfp_get_options();
        echo "<ul><li>";
        echo $wpfp_options['favorites_empty'];
        echo "</li></ul>";
    }

    echo '<p>'.wpfp_clear_list_link().'</p>';
    echo "</div>";
    wpfp_cookie_warning();
