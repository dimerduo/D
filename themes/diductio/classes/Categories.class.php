<?php

/**
 * Класс категорий. Храниться все логика, касающаяся категорий и метаданных.
 */
class Did_Categories
{
    private $available_order = ['asc','desc'];
    private $available_type = ['key','value'];
    
    private $categoies;
    private $tags;
    private $sql;
    
    private $flag;
    
    function fetchCategoriesByUser($user_id)
    {
        global $wpdb;
        $this->flag = 'categories';
        $table_name = Diductio::gi()->settings['stat_table'];
        $sql = "SELECT `post_id` FROM `{$table_name}` WHERE `user_id` = {$user_id}";
        $posts = $wpdb->get_results($sql, ARRAY_A);
        $categories = [];
        foreach ($posts as $post) {
            $p_categories = wp_get_post_categories($post['post_id'], array('fields' => 'names'));
            $categories[] = $p_categories;
        }
        
        $cat_result = [];
        foreach ($categories as $key => $item) {
            foreach ($item as $sub_item) {
                if (array_key_exists($sub_item, $cat_result)) {
                    $cat_result[$sub_item] += 1;
                } else {
                    $cat_result[$sub_item] = 1;
                }
            }
        }
        $this->categoies = $cat_result;
        
        return $this;
    }
    
    function fetchTagsByUser($user_id)
    {
        global $wpdb;
        $this->flag = 'tags';
        $table_name = Diductio::gi()->settings['stat_table'];
        $sql = "SELECT `post_id` FROM `{$table_name}` WHERE `user_id` = {$user_id}";
        $posts = $wpdb->get_results($sql, ARRAY_A);
        
        $tags_array = [];
        foreach ($posts as $post) {
            $tagInfo = wp_get_post_tags($post['post_id'], array('fields' => 'names'));
            $tags_array[] = $tagInfo;
        }
        $tags_array = array_filter($tags_array);
        $tag_result = [];
        foreach ($tags_array as $key => $item) {
            foreach ($item as $sub_item) {
                if (array_key_exists($sub_item, $tag_result)) {
                    $tag_result[$sub_item] += 1;
                } else {
                    $tag_result[$sub_item] = 1;
                }
            }
        }
        $this->tags = $tag_result;
        
        return $this;
    }
    
    function fetchTags()
    {
        return $this;
    }
    
    function orderBy($type, $order )
    {
        $order = strtolower($order);
        
        if(!in_array($type, $this->available_type) || !in_array($order, $this->available_order)) {
            return false;
        }
        
        $sort = 'sort';
        if ($type == 'value') {
            $sort_order = $order == 'asc' ? 'a' : 'ar' ;
        } else {
            $sort_order = $order == 'asc' ? 'k' : 'kr' ;
        }
        
        $orderFunction = $sort_order . $sort;
        
        if ($this->flag == 'categories') {
            $orderFunction($this->categoies);
        } else {
            $orderFunction($this->tags);
        }
        
        return $this;
    }
    
    function filter()
    {
        
    }
    
    private function _max($array)
    {
        reset($array);
        $key = key($array);
        return [ $key => $array[$key] ] ;
    }
    
    function max()
    {
        if($this->flag == 'categories') {
            return $this->_max($this->categoies);
        }
        
        return $this->_max($this->tags);
    }
    
    function min()
    {
        
    }
    
    /**
     * Fetching data
     *
     * @param  int   $number - Limit category number
     * @return array         - Found category
     */
    function get($number = 0)
    {
        if ($number) {
            return array_slice($this->categoies, 0, $number);
        }
        
        return $this->categoies;
    }
}
?>
