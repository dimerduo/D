<?php

    /**
     * Class Statistic. Всё что касается верхнего статистического блока
     */
    class Statistic extends Diductio
    {

        /**
         * @var int - active posts count
         */
        public $active = 0;

        /**
         * @var int  - finished learning posts count
         */
        public $done = 0;

        /**
         * @var int active study users count
         */
        public $active_studies_users = 0;

        /**
         * @var finished studying users count
         */
        public $finished_study_users = 0;

        /**
         * @var int active studying users id's
         */
        public $active_studies_users_ids = 0;

        /**
         * @var int finished studying users id's
         */
        public $finished_study_ids = 0;

        /**
         * @int peoples count who doesn't have any active knowledges
         */
        public $free_peoples_count = 0;

        /**
         * @var array peoples who is activate learning something
         */
        public $busy_peoples = 0;

        /**
         * Statistic constructor.
         */
        function __construct()
        {
            $this->active = 0;
            $this->done   = 0;
            $this->count_arrays();
            $this->do_actions();
            $this->busy_peoples       = $this->get_busy_peoples();
            $this->free_peoples_count = $this->get_free_peoples();
        }

        /**
         * Run template hooks
         */
        function do_actions()
        {
            $this->showHeaderStatistic();
        }

        /**
         *
         */
        function showHeaderStatistic()
        {
            //knowledge statistic
            add_action('index-head', function () {
                $this->renderHeaderStatistic('knowledge');
            });
            add_action('subscribtion-index', function () {
                $this->renderHeaderStatistic('knowledge');
            });
            add_action('knowledge-header', function () {
                $this->renderHeaderStatistic('knowledge');
            });
            add_action('istochniki-header', function () {
                $this->renderHeaderStatistic('knowledge');
            });
            add_action('archive-header', function () {
                $this->renderHeaderStatistic('knowledge');
            });

            //peoples statistic
            add_action('all-peoples-header', function () {
                $this->renderHeaderStatistic('peoples');
            });
            add_action('people-studying-header', function () {
                $this->renderHeaderStatistic('peoples');
            });

            //personal area statistic
            add_action('progress-page-header', function () {
                $this->renderHeaderStatistic('personal-area');
            });
            add_action('author-page-header', function () {
                $this->renderHeaderStatistic('personal-area');
            });
            add_action('progress-comments-header', function () {
                $this->renderHeaderStatistic('personal-area');
            });
            add_action('progress-subscribers-header', function () {
                $this->renderHeaderStatistic('personal-area');
            });
            add_action('page-user-comments-header', function () {
                $this->renderHeaderStatistic('personal-area');
            });

            //ajax methods
            add_action('wp_ajax_show_more_statistic', array($this, 'get_more_statistic'));
            add_action('wp_ajax_nopriv_show_more_statistic', array($this, 'get_more_statistic'));

        }

        /**
         * Return array of the posts by status.
         *
         * @param string $status - get posts count by status | publish by default
         * @return mixed posts array
         */
        public function get_all_arrays($status = false)
        {
            $all_array_obj = wp_count_posts();

            if ($status) {
                return $all_array_obj->$status;
            } else {
                return $all_array_obj->publish;
            }
        }

        private function count_arrays()
        {
            global $current_user, $wpdb;

            $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
            $sql        = "SELECT * FROM `$table_name`";

            $progress        = $wpdb->get_results($sql);
            $active_courses  = 0;
            $done_courses    = 0;
            $users_array     = array();
            $cours_array     = array();
            $statistic_array = array();
            // Arrays variables
            $finshed_array    = array();
            $inprogress_array = array();
            // Users variables
            $finished_users   = array();
            $inprogress_users = array();

            foreach ($progress as $key => $value) {
                $lessons_count = $value->lessons_count;

                if ($value->checked_lessons != 0) {
                    $checked_lessons = count(explode(',', $value->checked_lessons));
                } else {
                    $checked_lessons = 0;
                }

                if ($lessons_count != $checked_lessons) {
                    $statistic_array[$key]['status']  = 'unfinised';
                    $statistic_array[$key]['pos_id']  = $value->post_id;
                    $statistic_array[$key]['user_id'] = $value->user_id;

                } else {
                    $statistic_array[$key]['status']  = 'finished';
                    $statistic_array[$key]['pos_id']  = $value->post_id;
                    $statistic_array[$key]['user_id'] = $value->user_id;
                }
            }

            foreach ($statistic_array as $key => $value) {
                if ($value['status'] == 'finished') {
                    if ( ! in_array($value['pos_id'], $finshed_array)) {
                        array_push($finshed_array, $value['pos_id']);
                    }
                    if ( ! in_array($value['user_id'], $finished_users)) {
                        array_push($finished_users, $value['user_id']);
                    }
                }
                if ($value['status'] == 'unfinised') {
                    if ( ! in_array($value['pos_id'], $inprogress_array)) {
                        array_push($inprogress_array, $value['pos_id']);
                    }
                    if ( ! in_array($value['user_id'], $inprogress_users)) {
                        array_push($inprogress_users, $value['user_id']);
                    }
                }
            }
            //статистика по массивам
            $this->active = count($inprogress_array);
            $this->done   = count($finshed_array);

            //статистика по пользователям


            $this->finished_study_users = count($finished_users);
            $this->active_studies_users = count($inprogress_users);
        }

        /**
         *  Return posts count in some source.
         *  Получить количество постов в источнике.
         *
         * @return int  - posts count
         */
        public function get_istochiki_count()
        {
            return wp_count_terms('post_tag');
        }

        public function get_progress()
        {
            global $wpdb;


            $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
            $sql        = "SELECT * FROM `$table_name` WHERE `checked_lessons` != '0' ORDER BY `user_id` ";
            $progress   = $wpdb->get_results($sql);

            $all_bar   = 0;
            $count_bar = 0;

            foreach ($progress as $k => $v) {
                $count_c = count(explode(',', $v->checked_lessons));
                $count_l = $v->lessons_count;

                $all_bar   = $all_bar + (($count_c / $count_l) * 100);
                $count_bar = $count_bar + 1;
            }

            return round($all_bar / $count_bar, 2);
        }


        /**
         * Возвращает всю статистику по пользователям
         *
         * @param  string $flag - флаг поиска : активные, закончили и все пользователи если не задан флаг
         */
        public function get_all_users($flag = false)
        {
            if ( ! $flag) {
                $users = get_users();

                return count($users);
            } else {
                global $wpdb;

                $finished_users   = array();
                $inprogress_users = array();

                $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
                $sql        = "SELECT * FROM `$table_name` ";
                $progress   = $wpdb->get_results($sql);

                foreach ($progress as $key => $value) {
                    $lessons_count = $value->lessons_count;

                    if ($value->checked_lessons != 0) {
                        $checked_lessons = count(explode(',', $value->checked_lessons));
                    } else {
                        $checked_lessons = 0;
                    }

                    if ($lessons_count != $checked_lessons) {
                        $statistic_array[$key]['status']  = 'unfinised';
                        $statistic_array[$key]['pos_id']  = $value->post_id;
                        $statistic_array[$key]['user_id'] = $value->user_id;
                    } else {
                        $statistic_array[$key]['status']  = 'finished';
                        $statistic_array[$key]['pos_id']  = $value->post_id;
                        $statistic_array[$key]['user_id'] = $value->user_id;
                    }
                }

                foreach ($statistic_array as $key => $value) {
                    if ($value['status'] == 'finished') {
                        if ( ! in_array($value['user_id'], $finished_users)) {
                            array_push($finished_users, $value['user_id']);
                        }
                    }
                    if ($value['status'] == 'unfinised') {
                        if ( ! in_array($value['user_id'], $inprogress_users)) {
                            array_push($inprogress_users, $value['user_id']);
                        }
                    }
                }

                if ($flag == 'active_users') {
                    return $inprogress_users;
                } else {
                    return $finished_users;
                }
            }
        }

        /**
         * Return an array of the busy peoples
         *
         * @return
         */
        public function get_busy_peoples()
        {
            global $wpdb;

            $stat_table = Diductio::gi()->settings['stat_table'];
            $sql        = "SELECT *, ";
            $sql .= "IF(`checked_lessons` = 0, 0,(LENGTH(`checked_lessons`) - LENGTH(REPLACE(`checked_lessons`, ',', '')) + 1) ) as `checked_count` ";
            $sql .= "FROM {$stat_table} ";
            $sql .= "HAVING `lessons_count` != `checked_count`";
            $busy_people = $wpdb->get_results($sql, ARRAY_A);
            $result      = array();

            foreach ($busy_people as $people) {
                $result[$people['user_id']] = $people['user_id'];
            }

            return $result;
        }

        /**
         * Return count of the free users.
         *
         * @return int - count of the free users
         */
        public function get_free_peoples()
        {
            return $this->get_all_users() - count($this->busy_peoples);
        }


        public function get_div_studying_progress($uid = false)
        {
            global $current_user, $wpdb;

            if ($uid) {
                $user_info = get_userdata($uid);
            } else {
                $user_info = $current_user;
            }

            $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
            $sql        = "SELECT * FROM `$table_name` ";
            $sql .= "WHERE `user_id` = " . $user_info->ID . " ";
            //$sql   .= "AND `checked_lessons` != 0 ";
            $progress         = $wpdb->get_results($sql);
            $user_array_count = 0;
            $precent          = 0;
            if ($progress) {
                foreach ($progress as $key => $value) {
                    $lessons_count = $value->lessons_count;
                    if ($lessons_count) {
                        if ($value->checked_lessons != 0) {
                            $checked_lessons = count(explode(',', $value->checked_lessons));
                        } else {
                            $checked_lessons = 0;
                        }
                        $precent += round((100 * $checked_lessons) / $lessons_count, 2);
                        $user_array_count++;
                    }
                }

                if ($precent && $user_array_count) {
                    return round($precent / $user_array_count, 2);
                } else {
                    return 0;
                }
            } else {
                return 0;
            }

        }

        /**
         * Фунция дающая статистическую информацию по конкретному курсу
         *
         * @param $course_id - Id поста
         * @return array [done - количество пользовтелей, которые прошли курс]
         *                   in_progress - количество пользовтелей, которые проходят курс,
         *                   les_count - количество уроков в массиве]
         */
        public function get_course_info($course_id)
        {
            global $current_user, $wpdb;

            $done          = $in_progress = 0;
            $user_done_ids = $user_active_ids = array();

            $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
            $sql        = "SELECT * FROM `$table_name` WHERE `post_id` = {$course_id}";
            $progress   = $wpdb->get_results($sql);
            if ($progress) {
            	$users_started = array();
                foreach ($progress as $key => $value) {
                    $lessons_count = $value->lessons_count;
                    if ($value->checked_lessons != 0) {
                        $checked_lessons = count(explode(',', $value->checked_lessons));
                    } else {
                        $checked_lessons = 0;
                    }

                    if ($lessons_count != $checked_lessons) {
                        if ( ! in_array($value->user_id, $user_active_ids)) {
                            array_push($user_active_ids, $value->user_id);
                        }
                        $in_progress++;
                    } else {
                        if ( ! in_array($value->user_id, $user_done_ids)) {
                            array_push($user_done_ids, $value->user_id);
                        }
                        $done++;
                    }
                    $les_count = $lessons_count;

                    // Get object with `user_id`->`created_at` date
	                if (isset( $value->user_id)
	                    && isset($value->created_at)) {
	                	$users_started[ $value->user_id ] = $value->created_at;
	                }
                }
                $out['done']         = $done;
                $out['in_progress']  = $in_progress;
                $out['les_count']    = get_post_meta($course_id, 'publication_count')[0];
                $out['active_users'] = $user_active_ids;
                $out['done_users']   = $user_done_ids;
                $out['users_started']= $users_started;
            } else {
                $out['done']         = 0;
                $out['in_progress']  = 0;
                $out['les_count']    = get_post_meta($course_id, 'publication_count')[0];
                $out['active_users'] = 0;
                $out['done_users']   = 0;
            }

            return $out;
        }

        /**
         *  Возвращает информацию по статистике пользователя пройденные и активные.
         *
         * @param int $id - ID пользователя. Если параметр не был отправлен, то берётся ID залогиненого пользователя
         * @return array $out - статистический массив, в котором:
         *                int   done - количество пройденных массивов(постов)
         *                int in_progress - количество массивов, которые сейчас проходит пользователь
         *                int all - все
         */
        public function get_user_info($id = false)
        {
            global $current_user, $wpdb;

            if ( ! $id) {
                $user_id = $current_user->ID;
            } else {
                $user_id = (int)$id;
            }
            $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
            $sql        = "SELECT * FROM `$table_name` WHERE `user_id` = {$user_id}";
            $progress   = $wpdb->get_results($sql);

            $in_progress = $done = 0;
	        $in_progress_posts_created_at = array();
            if ($progress) {
                foreach ($progress as $key => $value) {
                    $lessons_count = $value->lessons_count;
                    if ($value->checked_lessons != 0) {
                        $checked_lessons = count(explode(',', $value->checked_lessons));
                    } else {
                        $checked_lessons = 0;
                    }

                    if ($lessons_count != $checked_lessons) {
                        $in_progress++;

                        if ( isset( $value->post_id )
                             && isset( $value->created_at )
                        ) {
	                        $in_progress_posts_created_at[ $value->post_id ] = $value->created_at;
                        }
                    } else {
                        $done++;
                    }
                    $les_count = $lessons_count;
                }
                $out['done']        = $done;
                $out['in_progress'] = $in_progress;
                $out['all']         = $done + $in_progress;
            } else {
                $out['done']        = 0;
                $out['in_progress'] = 0;
            }

	        // Count days to finish all in progress posts
	        $work_time = 0;
	        $now = date_create();
	        $countdown_days = 0; // total countdown in days
	        foreach ( $in_progress_posts_created_at as $post_id => $created_at ) {
		        $work_time += (int) get_post_meta( $post_id, 'work_time', true );

		        // date_add() modifies $end object
		        $end = date_create( $created_at );
		        date_add( $end, date_interval_create_from_date_string( $work_time . ' days' ) );
		        $countdown = date_diff( $end, $now );

		        if ($countdown->days > $countdown_days) {
		        	$countdown_days = $countdown->days;
		        }
	        }
	        $out['countdown_days'] = $countdown_days;

            return $out;
        }

        /**
         * Пересчитывает всю статистику
         */
        public function refresh()
        {
            global $wpdb;

            $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
            $sql        = "SELECT * FROM `$table_name` ";
            $progress   = $wpdb->get_results($sql);

            foreach ($progress as $key => $value) {

                $user_exist = get_userdata($value->user_id);
                $post_exist = get_post_status($value->post_id);

                if ( ! $user_exist || ! $post_exist) {
                    $del_sql = "DELETE FROM `wp_user_add_info` WHERE `id` = {$value->id}";
                    $wpdb->query($del_sql);
                }
            }

        }

        /**
         * Return posts count by some post format
         *
         * @param        $term     - Wordpress term
         * @param        $taxonomy - Taxonomy term
         * @param string $type     - Post format type
         * @return int
         */
        function getPostsCountByFormat($term, $taxonomy, $type = 'post')
        {
            $args = array(
                'fields'         => 'ids',
                'posts_per_page' => -1, //-1 to get all post
                'post_type'      => $type,
                'tax_query'      => array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field'    => 'slug',
                        'terms'    => $term,
                    ),
                ),
            );
            if ($posts = get_posts($args)) {
                return count($posts);
            }

            return 0;
        }

        /**
         * Render header statistic block
         *
         * @param bool $type statistic type. {user statistic | post(knowledge) statistic}
         */
        function renderHeaderStatistic($type = false)
        {
            $data       = new stdClass();
            $data->type = $type;

            switch ($type) {
                case 'personal-area':
                    global $author, $st;
                    if (get_query_var('username') && ! $author) {
                        $author  = get_user_by('slug', get_query_var('username'));
                        $user_id = $author->ID;
                    } else {
                        $user_id = $author ? $author->ID : get_current_user_id();
                    }

                    $progress_percent = $st->get_knowledges($user_id, 'active');
                    if ($progress_percent) {
                        $tmp_precent = 0;
                        foreach ($progress_percent as $item) {
                            $tmp_precent += $st->get_user_progress_by_post($item, $user_id);
                        }
                        $percent = round($tmp_precent / count($progress_percent), 2);
                    } else {
                        $percent = 0;
                    }

                    $data->pecent       = $percent;
                    $data->custom_url   = '';
                    $data->progress_url = '/progress';
                    if ($user_id != get_current_user_id()) {
                        $data->custom_url   = '/' . $author->user_nicename;
                        $data->progress_url = '/people/' . $author->user_nicename;
                    }
                    $data->user_id = $user_id;
                    break;
            }
            Diductio::gi()->loadView('statistic_block', $data);
        }

        /**
         * Get users statistic by post for "More statistic" block
         * Core: ajax
         */
        public function get_more_statistic()
        {
            $post_id      = $_POST['post_id'];
            $user_group   = $_POST['user_group'];
            $post_stat    = $this->get_course_info($post_id);
            $target_users = array_merge($post_stat['active_users'], $post_stat['done_users']);
            $result       = array();
            foreach ($target_users as $user) {
                $user_info        = get_user_by('id', $user);
                $tmp['username']  = $user_info->display_name;
                $tmp['avatar']    = get_avatar($user, 24);
                $tmp['user_link'] = get_site_url() . "/people/" /*hardcode*/ . $user_info->user_nicename;
                $tmp['user_id']   = $user;
                $tmp['progress']  = $this->get_user_progress_by_post($post_id, $user);
                $result[]         = $tmp;
            }
            unset($tmp);

            // sort array by progress
            usort($result, function ($a, $b) {
                return $a['progress'] - $b['progress'];
            });

        }

        /**
         * Getting user progress by single post
         *
         * @param bool $post_id post ID
         * @param bool $user_id user ID
         * @return json
         */
        public function get_user_progress_by_post($post_id = false, $user_id = false)
        {
            global $wpdb;

            if ( ! $post_id || ! $user_id) {
                return false;
            }

            $table = Diductio::gi()->settings['stat_table'];
            $sql   = "SELECT * FROM `{$table}` WHERE ";
            $sql .= "`post_id` = {$post_id} AND `user_id` = $user_id";
            $result = $wpdb->get_row($sql, 'ARRAY_A');
            if ($result && $result['checked_lessons']) {
                $progress = $this->count_progress($result['lessons_count'], $result['checked_lessons']);
            } else {
                $progress = 0;
            }

            return $progress;
        }

        /**
         * Count progress by checked lesson in the post
         *
         * @param $lessons_count   -  summary lessons count in the post
         * @param $lessons_checked -  lessons checked by some user
         */
        public function count_progress($lessons_count, $lessons_checked)
        {
            if ( ! $lessons_checked || ! $lessons_count) {
                return 0;
            }
            $lessons_checked = count(explode(',', $lessons_checked));

            return round(($lessons_checked * 100) / $lessons_count, 2);
        }

        /**
         * Возвращает массивы(знанния) пользователей по опередлённом критерию.
         *
         * @param int    $user_id - ID пользователя, по которому возвращать данные
         * @param string $type    - тип возвращаемых данных {all-все; done - пройденные; active - активнын}
         */
        public function get_knowledges($user_id = false, $type = 'all')
        {
            global $wpdb;

            $table_name = Diductio::gi()->settings['stat_table'];
            $select     = $user_id ? '*' : '`post_id`';

            $sql = "SELECT {$select} FROM `{$table_name}` ";
            $sql .= $user_id ? "WHERE `user_id` = {$user_id} " : '';
            $results = $wpdb->get_results($sql, ARRAY_A);

            //sorting
            foreach ($results as $key => $result) {
                $results[$key]['progress'] = $this->count_progress($result['lessons_count'],
                    $result['checked_lessons']);
            }
            usort($results, function ($a, $b) {
                return $a['progress'] - $b['progress'];
            });

            if ($user_id && $type != 'all') {
                $done   = array();
                $active = array();
                foreach ($results as $result) {
                    $lessons_count   = $result['lessons_count'];
                    $checked_lessons = $result['checked_lessons'] ? count(explode(',', $result['checked_lessons'])) : 0;

                    if ($lessons_count == $checked_lessons) {
                        $done[] = $result['post_id'];
                    } else {
                        $active[] = $result['post_id'];
                    }
                }
            } else {
                $all = array_map(function ($el) {
                    return $el['post_id'];
                }, $results);
            }

            return $$type;
        }


        function get_users_by_post($post_id)
        {
            global $dUser;

            $post_stat = $this->get_course_info($post_id);
            $result       = array();
            if ($post_stat['active_users'] || $post_stat['done_users']) {
                $target_users = array_merge($post_stat['active_users'], $post_stat['done_users']);
                foreach ($target_users as $user) {
                    $user_info       = get_user_by('id', $user);
                    $tmp             = $dUser->getUserData($user_info->ID);
                    $tmp['progress'] = $this->get_user_progress_by_post($post_id, $user);
                    $result[]        = $tmp;
                }
                unset($tmp);

                // sort array by progress
                usort($result, function ($a, $b) {
                    return $a['progress'] - $b['progress'];
                });
            }

            return $result;
        }

        /**
         * Возвращает информацию по категориям постов(знаний) которые клиент проходит или проходил
         * Return all categories information of the posts(knowledges) that user has been passed or passing now
         *
         * @param $user_id
         */
        function get_categories_stat_by_post($user_id)
        {
            global $wpdb;

            $table_name = Diductio::gi()->settings['stat_table'];

            $sql = "SELECT `post_id` FROM `{$table_name}` WHERE `user_id` = {$user_id}";
            $posts = $wpdb->get_results($sql, ARRAY_A);
            foreach ($posts as $post) {
                $p_categories = wp_get_post_categories($post['post_id'], array('fields' => 'names'));
//                print_r($p_categories);exit;
            }
        }

	    /**
	     * Format time in Russian months days
	     *
	     * @param {integer} $work_time
	     *
	     * @return string
	     */
	    public static function ru_months_days( $work_time ) {
		    $work_time = (int) $work_time;
		    if ($work_time === 0) {
			    return '';
		    }

		    $years_abbr    = 'г';
		    $month_abbr    = 'м';
		    $day_abbr      = 'д';
		    $days_in_month = 30;
		    $months_in_year = 12;

		    $months = floor( $work_time / $days_in_month );
		    $years = floor( $months / $months_in_year );
		    $months = floor( $months % $months_in_year );
		    $days   = floor( $work_time % $days_in_month );

		    $output = array();
		    if ( $years > 0 ) {
		    	array_push( $output, $years . $years_abbr);
		    }
		    if ( $months > 0 ) {
			    array_push( $output, $months . $month_abbr);
		    }
		    if ( $days > 0 ) {
			    array_push( $output, $days . $day_abbr);
		    }

		    return implode(', ', $output);
	    }
    }
