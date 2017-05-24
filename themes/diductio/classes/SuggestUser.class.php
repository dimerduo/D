<?php

class Did_SuggestUser
{
    /**
     * Did_SuggestUser constructor.
     */
    public function __construct()
    {
        $this->doActions();
    }
    
    /**
     *
     */
    public function addJs()
    {
        // js
        wp_register_script('suggest-user', get_stylesheet_directory_uri() . "/js/suggest_user.js");
        wp_enqueue_script('suggest-user');
        
        // css
        wp_enqueue_style('suggest-user-css', get_template_directory_uri() . '/css/suggest_user.css');
    }
    
    /**
     *
     */
    public function addCss()
    {
        
    }
    
    /**
     *
     */
    public function doActions()
    {
        add_action('wp_ajax_nopriv_suggestUsers', array($this, 'suggest_me_user'));
        add_action('wp_ajax_suggestUsers', array($this, 'suggest_me_user'));
        
        add_action('wp_enqueue_scripts', array($this, 'addJs'));
    }
    
    /**
     *
     */
    public function suggest_me_user()
    {
        global $dPost, $wpdb;
    
        $url     = wp_get_referer();
        $post_id = url_to_postid( $url );
        
        $users = $_POST['users'];
        $include = $exclude = [];
        
        foreach ($users as $user) {
            if($user['alreadyHas'] == 'true') {
                $include[] = $user;
                continue;
            }
            
            $exclude[] = $user;
        }
        
        // exclude first
        $exclude_ids = implode(array_map(function($item){
            return $item['id'];
        }, $exclude), ',');
        $sql = "DELETE FROM `wp_user_add_info` WHERE `user_id` IN ({$exclude_ids}) ";
        $wpdb->query($sql);
        
        // include
        $already_subscribed = $this->getUsersByPost($post_id);
        foreach ($include as $user) {
            if (!in_array($user['id'], $already_subscribed)) {
                add_post_to_statistic($post_id, $user['id']);
                $dPost->addToFavorite($post_id, $user['id']);
            }
        }
        
        wp_die();
    }
    
    /*
     *
     */
    public function getSuggestingUsers($user_id, $post_id)
    {
        $all_users = [];
        $subscribed_to = get_user_meta($user_id, 'subscribe_to')[0];
        $already_subscribed = $this->getUsersByPost($post_id);
        
        if ($subscribed_to) {
            $args = [
                'fields' => array('ID', 'display_name'),
                'include' => $subscribed_to,
            ];
            $all_users = get_users($args);
        }
        
        foreach ($all_users as $key => $user) {
            $is_selected = false;
            if(in_array($user->ID, $already_subscribed)) {
                $is_selected = true;
            }
            
            $all_users[$key]->is_selected = $is_selected;
        }
        
        return (array)$all_users;
    }
    
    public function getUsersByPost($post_id)
    {
        global $wpdb;
        
        $sql = "SELECT `user_id` FROM `wp_user_add_info` WHERE `post_id` = {$post_id}";
        $result = $wpdb->get_results($sql, 'ARRAY_A');
        
        $users = array_map(function ($item) {
            return $item['user_id'];
        }, $result);
        
        return $users;
    }
}