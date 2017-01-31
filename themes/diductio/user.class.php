<?php

    class User extends Diductio
    {
        /**
         * @var array $settings ;
         */
        public $settings;

        /**
         * @int logged user ID
         */
        public $current_user;

        /**
         * Конструктор класса
         */
        function __construct()
        {
            $this->current_user = get_current_user_id();
            $this->settings     = Diductio::gi()->settings;
            $this->addActions();
            $this->includeShortCodes();
        }

        /**
         * Подключает все хуки, связанные с пользователем.
         */
        public function addActions()
        {
            add_action('deleted_user', Array($this, 'afterUserDelete'));
        }

        /**
         *  Подключить все шоркоды что используются для пользователя.
         */
        public function includeShortCodes()
        {
            add_shortcode('my_comments', Array($this, 'comment_shortcode'));
        }

        /**
         * Метод срабатывает после того как был удалён пользователь.
         *
         * @param int $user_id - ID пользователя.
         */
        public function afterUserDelete($user_id)
        {
            Diductio::gi()->deleteStatByUser($user_id);
        }

        /**
         * Getting all users by some knowledge lesson(accordion item)
         * Возвращает всех пользователей по части урока (аккордион эелементу)
         *
         * @param int $post_id     - post ID
         * @param int $lesson_part - lesson part of the knowledge
         */
        public function getUserByAccordionItem($post_id, $accordion_item)
        {
            global $wpdb;

            $sql = "SELECT `user_id` FROM " . Diductio::gi()->settings['stat_table'] . " ";
            $sql .= "WHERE `post_id` = {$post_id} AND FIND_IN_SET({$accordion_item}, `checked_lessons`) > 0 ";
            $result = $wpdb->get_results($sql);

            $result_array = array();
            if ( ! empty($result)) {
                foreach ($result as $item) {
                    $result_array[] = $item->user_id;
                }

                return $result_array;
            } else {
                return array();
            }
        }

        /**
         * Getting user data such as avatar, link and etc.
         * Возвращает дополнительную информацию по пользователю, такую как: аватарка, ссылка на профиль,
         *
         * @param1 int $user_id  - ID пользователя.
         * @return array $result -
         */
        public function getUserData($user_id)
        {
            $user_info           = get_user_by('id', $user_id);
            $result['nickname']  = $user_info->user_nicename;
            $result['username']  = $user_info->display_name;
            $result['avatar']    = get_avatar($user_id, 24);
            $result['user_link'] = get_site_url() . "/people/" /*hardcode*/ . $user_info->user_nicename;
            $result['user_id']   = $user_id;

            return $result;
        }

        /**
         * Get users subscriptions count
         *
         * @param false|int $id - If ID provided function will return subscription count of specific user. If not,
         *                      count of current logged user
         * @return int - Subscriptions count
         */
        public function getSubscriptionsCount($id = false)
        {
            $user_ID         = $id ? $id : get_current_user_id();
            $subscriber_list = get_user_meta($user_ID, 'subscribe_to')[0];
            $tag_list        = get_user_meta($user_ID, 'signed_tags')[0];
            $category_list   = get_user_meta($user_ID, 'signed_categories')[0];
            $count           = count($subscriber_list) + count($tag_list) + count($category_list);

            return $count;
        }

        /**
         * Return comments count of the user
         *
         * @param bool|int $id Return current user comments count if ID is not specified, and needed user comment count
         *                     if ID specified
         * @return int
         */
        public function get_comments_count($id = false)
        {
            $user_ID      = $id ? $id : get_current_user_id();
            $comment_args = array(
                'author__in' => $user_ID,
            );

            return count(get_comments($comment_args));
        }

        /**
         * Показывает комментарии пользователя на странице "мои комментарии"
         *
         * @see my_comments_page.php
         * @param $user_id
         */
        public function get_comments($user_id = false)
        {
            $view_path = Diductio::gi()->settings['view_path'];
            $id        = $user_id ?: get_current_user_id();
            $page = (get_query_var('page')) ? get_query_var('page') : 1;
            $limit = 2; /* Hardcode */
            $offset = ($page * $limit) - $limit;


            $args = array(
                'offset'=>$offset,
                'author__in' => $id,
                'number'=>$limit,
            );
            $total_comments = get_comments(array('orderby' => 'post_date' , 'order' => 'DESC', 'author__in'=>$id, 'status' => 'approve'));
            $pages = ceil(count($total_comments)/2);

            $user_comments = get_comments($args);
            if (file_exists($view_path . "my_comments_page.php")) {
                require_once($view_path . "my_comments_page.php");
            }
        }

        /**
         * Функция-обработчик шордкода [my_comments]
         */
        public function comment_shortcode()
        {
            if (get_query_var('username')) {
                $user_obj = get_user_by('slug', get_query_var('username'));
                $user_id  = $user_obj->ID;
            } else {
                $user_id = get_current_user_id();
            }

            if ($user_id) {
                $this->get_comments($user_id);
            }
        }

        /**
         * Return accordion element (knowledge part) passed datetime.
         * Возвращает время и дату указанной части аккордион элемента (части знания).
         *
         * @param int $user_id - User ID
         * @param int $post_id - Post ID
         * @param int $accordion_element - Accordion (knowledge) element number
         * @return string|bool - Date or false if something goes wrong
         */
        public function get_accordion_passed_date($user_id, $post_id, $accordion_element)
        {
            global $wpdb;

            $table = Diductio::gi()->settings['stat_table'];
            $sql  = "SELECT * FROM `{$table}` ";
            $sql .= "WHERE `post_id` = {$post_id} AND `user_id` = {$user_id}";

            $progress = $wpdb->get_row($sql);

            if($progress->checked_at) {
                //TODO. Добавить формат в основной класс
                $d_format = 'd.m.Y';
                $t_format = 'H:i';
                $date = date($d_format,$progress->checked_at);
                $time = date($t_format,$progress->checked_at);
                $result = $date . ' в ' . $time;
            } else {
                $result = false;
            }

            return $result;
        }

        /**
         * Check if user is free
         * @param $user_id
         */
        public function is_free($user_id)
        {
            global $wpdb;

            $table = Diductio::getInstance()->settings['stat_table'];
            $sql  = "SELECT * FROM `{$table}` ";
            $sql .= "WHERE `user_id` = $user_id";
            $is_free = true;
            $result = $wpdb->get_results($sql);

            if($result) {
                foreach($result as $item)
                {
                    $checked_lessons = count(explode(',',$item->checked_lessons));
                    if($checked_lessons != $item->lessons_count) {
                        $is_free = false;
                    }
                }
            }

            return $is_free;
        }
    }