<?php

class Did_Posts
{
    /**
     * Get all authors
     */
    public static function getAllAuthors()
    {
        global $wpdb;
        
        $sql = "SELECT `ID`, `post_author` FROM `wp_posts` GROUP BY `post_author`";
        $result = $wpdb->get_results($sql, ARRAY_A);
        if ($result) {
            return $result;
        }
    
        return false;
    }
    
}