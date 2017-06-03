<?php

class Did_Statistic
{
    public $stat_table;
    
    /**
     * Did_Statistic constructor.
     */
    public function __construct()
    {
        $diductio = Diductio::gi();
        $this->stat_table = $diductio->settings['stat_table'];
    }
    
    /**
     * Add post to the statistic table of the some user
     *
     * @param int $post_id - Post ID which are you wan't to add
     * @param int $user_id - User ID to which one should post be added
     */
    public static function addPostToStatistic($post_id, $user_id = 0)
    {
        global $current_user, $wpdb;
        $table = (new self())->stat_table;
        $user_id = $user_id ? $user_id : $current_user->ID;
        $table_name = $wpdb->get_blog_prefix() . $table->stat_table;
    
        $wpdb->insert(
            $table_name,
            array(
                'user_id' => $user_id,
                'post_id' => $post_id,
                'update_at' => "NOW()",
                'lessons_count' => 1,
                'checked_lessons' => 0,
            ),
            array('%d', '%d', '%s', '%d', '%s')
        );
    }
    
    /**
     * Remove post from static table of some user
     *
     * @param int $post_id - Post ID which will be removed
     * @param int $user_id - User ID of the user
     */
    public static function removePostFromStatic($post_id, $user_id = 0)
    {
        global $current_user, $wpdb;
        
        $table = (new self())->stat_table;
        $user_id = $user_id ? $user_id : $current_user->ID;
        $table_name = $wpdb->get_blog_prefix() . $table->stat_table;
        $sql = "DELETE FROM `{$table}` WHERE `user_id` = {$user_id} AND `post_id` = {$post_id} ";
        $wpdb->query($sql);
    }
    
    /**
     * Clear all user statistic data
     *
     * @param int $user_id - ID of the user.
     */
    public static function clearUserStat($user_id)
    {
        
    }
}