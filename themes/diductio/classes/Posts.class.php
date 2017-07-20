<?php

class Did_Posts
{
    /**
     * @var Did_Statistic
     */
    private $staticClass;
    
    /**
     * Did_Posts constructor.
     */
    public function __construct()
    {
        $this->staticClass = new Did_Statistic();
    }
    
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
    
    public static function getAllUsersProgress($post_id)
    {
        $self = new self();
        
        $passing_users = $self->staticClass->oldStatisticClass->get_users_by_post($post_id);
        if ($passing_users) {
            $progress = 0;
            foreach ($passing_users as $user) {
                $progress += $user['progress'];
            }
            
            return round($progress / count($passing_users),2);
        }
        
        return 0;
    }
    
}