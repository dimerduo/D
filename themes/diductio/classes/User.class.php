<?php

class Did_User
{
    public static function getAllMyPosts($user_id)
    {
        $args = array(
            'author'        =>  $user_id,
            'orderby'       =>  'post_date',
            'order'         =>  'ASC',
            'posts_per_page' => -1 // no limit
        );
        
        $current_user_posts = get_posts( $args );
        
        return count($current_user_posts);
    }
    
}