<?php

class Did_User
{
    /**
     * @var Did_Statistic
     */
    public $statistic;
    
    /**
     * Did_User constructor.
     */
    public function __construct()
    {
        $this->statistic = new Did_Statistic();
    }
    
    /**
     * Получить все посты пользователя
     * Get all posts by user
     *
     * @param string $user_id - ID пользователя | ID of the user
     * @return int $current_user_posts - Количество постов | Total posts count
     */
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
    
    public static function getAllMySubscribers($user_id)
    {
        $args = [
            'fields' => ['user_login', 'ID'],
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => 'subscribe_to',
                    'value' => $user_id,
                    'compare' => 'LIKE',
                ],
            ],
        ];
        $users = new WP_User_Query($args);
        $result = array();
        
        foreach ($users->get_results() as $key => $user) {
            $result[$key]['ID'] = $user->ID;
            $result[$key]['user_login'] = $user->user_login;
        }
        
        return $result;
    }
    
    public static function getPassedPosts($user_id)
    {
        global $wpdb;
        
        $self = new self();
        $table = Diductio::gi()->settings['stat_table'];
        $sql = "SELECT * FROM `{$table}` WHERE `user_id` = {$user_id} ";
        $sql .="AND ((LENGTH(`checked_lessons`) - LENGTH(REPLACE(`checked_lessons`, ',', ''))+1) = `lessons_count`) ";
        $result = $wpdb->get_results($sql, ARRAY_A);
        
        return $result;
    }
    
    public static function getUserSubscription($user_id)
    {
        $subscriber_list = get_user_meta($user_id, 'subscribe_to')[0];
        if($subscriber_list) {
            foreach ($subscriber_list as $key => $user) {
                $user_exist = get_userdata($user);
                if(!$user_exist) {
                    unset($subscriber_list[$key]);
                }
            }
            
            return $subscriber_list;
        }
        return [];
    }
    
    /**
     * Getting reciprocal subscriptions
     * Получаем взаимные подписки пользователя
     *
     * @param  int   $user_id - User ID
     * @return array          - List of the reciprocal subscriptions
     */
    public static function getReciprocalSubscriptions($user_id)
    {
        $following = Did_User::getAllMySubscribers($user_id);
        $followers = Did_User::getUserSubscription($user_id);
        $result = array();
        foreach ($following as $user) {
            if(in_array($user['ID'], $followers)) {
                $result[] = $user['ID'];
            }
        }
        
        return $result;
    }
}