<?php

    /**
     * Class Post. Adding all features/functional according to the post.
     */
    class Post extends Diductio
    {
        /**
         * @var
         */
        public $settings;

        /**
         * Post constructor.
         */
        public function __construct()
        {
            $this->settings = Diductio::gi()->settings;
            $this->addActions();
            $this->addFilters();
        }

        /**
         * Actions function
         */
        public function addActions()
        {
            add_action('before_delete_post', Array($this, 'onPostDelete'));
            add_action('post_updated', Array($this, 'onPostUpdate'), 10, 3);
            add_action('init', Array($this, 'rewrite_mode'));
        }

        /**
         * Filters function
         */
        public function addFilters()
        {
            /* Remove core WordPress filter. */
            remove_filter('term_link', '_post_format_link', 10);

            add_filter('term_link', Array($this, 'customPostTypes'), 10, 4);

            /* Remove the core WordPress filter. */
            remove_filter('request', '_post_format_request');
            /* Add custom filter. */
            add_filter('request', Array($this, 'my_post_format_request'));

            add_filter('gettext_with_context', Array($this, 'rename_post_formats_2'), 10, 4);

        }

        /**
         * Function run after post update in Admin Panel
         *
         * @param $post_ID     - ID of post
         * @param $post_after  - After changed post content
         * @param $post_before - Before changing post content
         */
        public function onPostUpdate($post_ID, $post_after, $post_before)
        {
            global $wpdb;

            $words_array     = str_word_count($post_after->post_content, 1);
            $accordion_count = 0;
            foreach ($words_array as $key => $value) {
                if ($value == 'accordion-item') {
                    $accordion_count++;
                }
            }
            if ($accordion_count % 2 == 0) {
                $accordion_count = $accordion_count / 2;
            }

            if ($accordion_count == 0) {
                $accordion_count = get_post_meta($post_ID, 'publication_count', true);
            }

            if ($accordion_count) {
                $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
                $sql        = "UPDATE {$table_name} SET `lessons_count` = {$accordion_count} ";
                $sql .= " WHERE `post_id` = {$post_ID}";
                $wpdb->query($sql);
            }

        }

        /**
         * Function run when some post is delete from trashbox
         *
         * @param $post_id - ID of the post
         */
        public function onPostDelete($post_id)
        {
            Diductio::gi()->deleteStatByPost($post_id);
        }

        /**
         * Customize WordPress Post format slugs
         *
         * @param $link     Link
         * @param $term     Term Type
         * @param $taxonomy Taxonomy Type
         * @return mixed|string link
         */
        public function customPostTypes($link, $term, $taxonomy)
        {
            global $wp_rewrite;

            if ('post_format' != $taxonomy) {
                return $link;
            }

            $slugs = $this->my_get_post_format_slugs();

            $slug = str_replace('post-format-', '', $term->slug);
            $slug = isset($slugs[$slug]) ? $slugs[$slug] : $slug;

            if ($wp_rewrite->get_extra_permastruct($taxonomy)) {
                $link = str_replace("/{$term->slug}", '/' . $slug, $link);
            } else {
                $link = add_query_arg('post_format', $slug, remove_query_arg('post_format', $link));
            }

            return $link;
        }

        /**
         * New post format links
         *
         * @return array with customized format slugs
         */
        function my_get_post_format_slugs()
        {

            $slugs = array(
                'aside'   => 'knowledge',
                'chat'    => 'poll',
                'gallery' => 'task',
                'image'   => 'test',
                'quote'   => 'project',
            );

            return $slugs;
        }

        /**
         * Post format request
         * Get from: http://justintadlock.com/archives/2012/09/11/custom-post-format-urls
         *
         * @param $qvs
         * @return mixed
         */
        function my_post_format_request($qvs)
        {
            if ( ! isset($qvs['post_format'])) {
                return $qvs;
            }

            $slugs = array_flip($this->my_get_post_format_slugs());

            if (isset($slugs[$qvs['post_format']])) {
                $qvs['post_format'] = 'post-format-' . $slugs[$qvs['post_format']];
            }

            $tax = get_taxonomy('post_format');

            if ( ! is_admin()) {
                $qvs['post_type'] = $tax->object_type;
            }

            return $qvs;
        }

        /**
         * Change translation of hte Post Formats
         *
         * @param $translation
         * @param $text
         * @param $context
         * @return mixed
         */
        function rename_post_formats_2($translation, $text, $context)
        {

            //change translations in the Admin Panel
            if ($context == 'Post format') {
                $names       = array(
                    'Aside'       => 'Знание',
                    'Chat'        => 'Голосование',
                    'Image'       => 'Тест',
                    'Gallery'     => 'Задача',
                    'Изображения' => '',
                    'Quote'       => 'Проект',
                );
                $translation = str_replace(array_keys($names), array_values($names), $text);
            }

            //change translations of the Post format archive title
            if ($context == 'post format archive title') {
                $post_format_titles = array(
                    'Asides'    => "Знания",
                    'Chats'     => "Голосования",
                    'Images'    => "Тесты",
                    'Galleries' => "Задачи",
                    'Quotes'    => "Проекты",
                );

                $translation = str_replace(array_keys($post_format_titles), array_values($post_format_titles), $text);
            }

            return $translation;
        }

        /**
         * Rewrite rules of the theme
         */
        public function rewrite_mode()
        {
            add_rewrite_tag('%username%', '([^&]+)');
            add_rewrite_rule('^(subscription)/([^/]*)/?', 'index.php?pagename=$matches[1]&username=$matches[2]', 'top');
            add_rewrite_rule(
                '^(activity)/([^/]*)/?$',
                'index.php?pagename=$matches[1]&username=$matches[2]',
                'top');
            add_rewrite_rule(
                '^(activity)/([^/]*)/page/?([0-9]{1,})/?$',
                'index.php?pagename=$matches[1]&username=$matches[2]&paged=$matches[3]', 'top'
            );
        }

        /**
         * Возвращает информацию о прохождении поста (знания)
         * Return passing information about post (knowledge)
         *
         * @param int $user_id - ID of the user
         * @param int $post_id - ID of the post (knowledge)
         * @return
         */
        public function get_passing_info_by_post($user_id, $post_id)
        {
            global $wpdb;
            global $st;

            $d_format = 'd.m.Y';
            $t_format = 'H:i';


            $stat_table            = Diductio::gi()->settings['stat_table'];
            $sql                   = "SELECT * FROM `{$stat_table}` WHERE `post_id` = {$post_id} AND `user_id` = {$user_id}";
            $row                   = $wpdb->get_row($sql, ARRAY_A);
            $passed_lessons        = explode(',', $row['checked_lessons']);
            $lessons_count         = $row['lessons_count'];
            $all_lessons           = range(1, $row['lessons_count']);
            $result['date_string'] = '';
            if ($row['checked_at']) {
                $passed_date          = explode(',', $row['checked_at']);
                $result['started_at'] = array_shift($passed_date);

	            $now = date_create();
	            // Active for
                $start = date_create();
	            date_timestamp_set($start, $result['started_at'] );
	            $active_diff = date_diff($now, $start);
	            $result['date_string'] = 'Активна ' . $st::ru_months_days($active_diff->days);

                if (count($passed_lessons) == $lessons_count) {
                    $result['is_passed']   = 1;
                    $result['finished_at'] = end($passed_date);
                	// Completed for
	                if ($result['finished_at']) {
	                    $end = date_create();
		                date_timestamp_set($end, $result['finished_at'] );
	                } else {
	                	// Fix: if $passed_date array is empty
	                	$end = date_create( $result['updated_at']);
	                }
                	$completed_diff = date_diff($start, $end);
                    $result['date_string'] = 'Пройдена за ' . $st::ru_months_days( $completed_diff->days);
                } else {
                    $result['is_passed']    = 0;
                    $unchecked_array        = array_diff($all_lessons, $passed_lessons);
                    $result['first_undone'] = array_shift($unchecked_array);
                    $result['undone_title'] = $this->get_accordion_element_title($post_id, $result['first_undone']);
                }
            } else {
                if ($row['checked_lessons'] == 0) {
                    // Если пользователь просто добавил массив в избранное
                    $result['first_undone'] = 1;
                    $result['undone_title'] = $this->get_accordion_element_title($post_id, $result['first_undone']);
                }
            }

            return $result;
        }

        /**
         * Возвращает форматированную строку по пройденным датам поста
         *
         * @param array $array - информация о прохождении поста (результат работы функции get_passing_info_by_post() )
         * @return string $date - отформотированная информация
         */
        public function format_passed_date_string($array)
        {

        }

        public function get_accordion_element_title($post_id, $element_number)
        {
            $element_number -= 1;
            $content_post = get_post($post_id);
            $content      = $content_post->post_content;
            $title        = '';
            preg_match_all("/\[accordion-item title='\s*([^']*)\s*'\]/", $content, $output_array);
            if ($output_array[1]) {
                $title = $output_array[1][$element_number];
            }

            return $title;
        }

        public function get_posts_by_type($user_id, $limit)
        {
            global $wpdb;
            $table = Diductio::gi()->settings['stat_table'];
            $sql   = "SELECT * FROM `{$table}` WHERE ";
            $sql .= "`user_id` = $user_id ";
            $sql .= "LIMIT 5";
            $result = $wpdb->get_results($sql, 'ARRAY_A');
            foreach ($result as $item) {
                $tmpPost      = get_post($item['post_id']);
                $passing_info = $this->get_passing_info_by_post($user_id, $item['post_id']);
                if ($passing_info['first_undone']) {
                    $tmp_title          = $this->get_accordion_element_title($item['post_id'],
                        $passing_info['first_undone']);
                    $tmpPost->stoped_on = "На этапе: " . $tmp_title;
                }
                $post_array[] = $tmpPost;
            }

            return $post_array;
        }
    }

?>