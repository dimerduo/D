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
    
        $sql = "SELECT `ID`, `post_author` FROM `wp_posts` WHERE `post_type` = 'post' GROUP BY `post_author`";
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
    
    /**
     * @param $post_id
     */
    public static function getOverDueUsers($post_id)
    {
        $self = new self();
        $work_days = get_post_meta($post_id, 'work_time')[0];
        $users = $self->staticClass->oldStatisticClass->get_users_by_post($post_id);
        $overdue_users = [];
        foreach ($users as $user) {
            $started_at = $self->userStartedAt($user['user_id'], $post_id);
            $startedDate = new DateTime($started_at);
            $now = new DateTime();
            $worked = $now->diff($startedDate)->format('%a');
            if($worked > $work_days) {
                $overdue_users[] = $user['user_id'];
            }
        }
        
        return $overdue_users;
    }
    
    public function userStartedAt($user_id, $post_id)
    {
        global $wpdb;
    
        $table = $this->staticClass->stat_table;
        $sql = "SELECT `created_at` FROM `{$table}` WHERE `post_id` = {$post_id} AND `user_id` = $user_id";
        $created_at = $wpdb->get_row($sql);
        if ($created_at) {
            return $created_at->created_at;
        }
    
        return false;
    }
    
}