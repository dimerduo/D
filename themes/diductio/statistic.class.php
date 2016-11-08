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
	 * Statistic constructor.
	 */
	function __construct()
	{
		$this->active = 0;
		$this->done = 0;
		$this->count_arrays();
		$this->do_actions();
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
		add_action('index-head', function (){
			$this->renderHeaderStatistic('knowledge');
		});
        add_action('subscribtion-index', function (){
			$this->renderHeaderStatistic('knowledge');
		});
        add_action('knowledge-header', function (){
			$this->renderHeaderStatistic('knowledge');
		});
        add_action('istochniki-header', function (){
			$this->renderHeaderStatistic('knowledge');
		});

        //peoples statistic
        add_action('all-peoples-header', function (){
            $this->renderHeaderStatistic('peoples');
        });
        add_action('people-studying-header', function (){
            $this->renderHeaderStatistic('peoples');
        });

	}

	/**
	 * @param bool $status - get posts count by status | publish by default
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
		$sql = "SELECT * FROM `$table_name`";

		$progress = $wpdb->get_results($sql);
		$active_courses = 0;
		$done_courses = 0;
		$users_array = array();
		$cours_array = array();
		$statistic_array = array();
		// Arrays variables
		$finshed_array = array();
		$inprogress_array = array();
		// Users variables
		$finished_users = array();
		$inprogress_users = array();

		foreach ($progress as $key => $value) {
			$lessons_count = $value->lessons_count;

			if ($value->checked_lessons != 0) {
				$checked_lessons = count(explode(',', $value->checked_lessons));
			} else {
				$checked_lessons = 0;
			}

			if ($lessons_count != $checked_lessons) {
				$statistic_array[$key]['status'] = 'unfinised';
				$statistic_array[$key]['pos_id'] = $value->post_id;
				$statistic_array[$key]['user_id'] = $value->user_id;

			} else {
				$statistic_array[$key]['status'] = 'finished';
				$statistic_array[$key]['pos_id'] = $value->post_id;
				$statistic_array[$key]['user_id'] = $value->user_id;
			}
		}

		foreach ($statistic_array as $key => $value) {
			if ($value['status'] == 'finished') {
				if (!in_array($value['pos_id'], $finshed_array)) {
					array_push($finshed_array, $value['pos_id']);
				}
				if (!in_array($value['user_id'], $finished_users)) {
					array_push($finished_users, $value['user_id']);
				}
			}
			if ($value['status'] == 'unfinised') {
				if (!in_array($value['pos_id'], $inprogress_array)) {
					array_push($inprogress_array, $value['pos_id']);
				}
				if (!in_array($value['user_id'], $inprogress_users)) {
					array_push($inprogress_users, $value['user_id']);
				}
			}
		}
		//статистика по массивам
		$this->active = count($inprogress_array);
		$this->done = count($finshed_array);

		//статистика по пользователям


		$this->finished_study_users = count($finished_users);
		$this->active_studies_users = count($inprogress_users);
	}

	/**
	 *  Получить количество постов в источнике
	 */
	public function get_istochiki_count()
	{
		return wp_count_terms('post_tag');
	}

	/*
		public function get_rating($rating_type = 'global', $uid = false) {
			global $current_user, $wpdb;

			if ($uid) {
				$current_user =  get_userdata( $uid );
			}

			$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
			$sql   = "SELECT * FROM `$table_name` WHERE `checked_lessons` != '0' ";
			$progress = $wpdb->get_results($sql);

			//моя функция
			$count_c = 0;
			$count_l = 0;
			foreach($progress as $k => $v){
				$count_c = $count_c + count(explode(',', $v->checked_lessons));
				$count_l = $count_l + $v -> lessons_count;

			}
			//и Всё!
			//остальное 'магия'

			$users_statistick = array();
			$passed_courses = 0;
			if($progress) {
				foreach ($progress as $key => $value) {
					$lessons_count = $value->lessons_count;
					if($value->checked_lessons != 0) {
						$checked_lessons = count(explode(',', $value->checked_lessons));
					} else {
						$checked_lessons = 0;
					}
					if($lessons_count != $checked_lessons) {
						unset($progress[$key]);
					} else {
						if(isset($users_statistick[$value->user_id])) {
							$users_statistick[$value->user_id] ++;
						} else {
							$users_statistick[$value->user_id]  = 1;
						}
					}
				}

				$user_count  = count($users_statistick);
				$all_div_counts = 0;

				foreach ($users_statistick as $key => $value) {
					$massiv_counts = $value;
					$div_value = ($massiv_counts * 100)/ $this->get_all_arrays();
					$all_div_counts += $div_value;
					$users_statistick[$key]  =  array ( 'passed' => $value, 'passed_div' =>$div_value );
				}
				if(!empty($all_div_counts)) {
					if($rating_type =='global') {
					  //return round($all_div_counts/$user_count, 2);
					  return round( ($count_c/$count_l)*100, 2);
					} else {
						return round($users_statistick[$current_user->ID]['passed_div'],2);
					}
				} else {
					return 0;
				}
			} else {
				return 0;
			}
		}
	*/


	public function get_progress()
	{
		global $wpdb;


		$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
		$sql = "SELECT * FROM `$table_name` WHERE `checked_lessons` != '0' ORDER BY `user_id` ";
		$progress = $wpdb->get_results($sql);

		$all_bar = 0;
		$count_bar = 0;

		foreach ($progress as $k => $v) {
			$count_c = count(explode(',', $v->checked_lessons));
			$count_l = $v->lessons_count;

			$all_bar = $all_bar + (($count_c / $count_l) * 100);
			$count_bar = $count_bar + 1;
		}

		return round($all_bar / $count_bar, 2);
	}


	/**
	 * Возвращает пользователей
	 */
	public function get_all_users($flag = false)
	{
		if (!$flag) {
			$users = get_users();
			return count($users);
		} else {
			global $wpdb;

			$finished_users = array();
			$inprogress_users = array();


			$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
			$sql = "SELECT * FROM `$table_name` ";
			$progress = $wpdb->get_results($sql);

			foreach ($progress as $key => $value) {
				$lessons_count = $value->lessons_count;

				if ($value->checked_lessons != 0) {
					$checked_lessons = count(explode(',', $value->checked_lessons));
				} else {
					$checked_lessons = 0;
				}

				if ($lessons_count != $checked_lessons) {
					$statistic_array[$key]['status'] = 'unfinised';
					$statistic_array[$key]['pos_id'] = $value->post_id;
					$statistic_array[$key]['user_id'] = $value->user_id;

				} else {
					$statistic_array[$key]['status'] = 'finished';
					$statistic_array[$key]['pos_id'] = $value->post_id;
					$statistic_array[$key]['user_id'] = $value->user_id;
				}


			}

			foreach ($statistic_array as $key => $value) {
				if ($value['status'] == 'finished') {
					if (!in_array($value['user_id'], $finished_users)) {
						array_push($finished_users, $value['user_id']);
					}
				}
				if ($value['status'] == 'unfinised') {
					if (!in_array($value['user_id'], $inprogress_users)) {
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

	public function get_div_studying_progress($uid = false)
	{
		global $current_user, $wpdb;

		if ($uid) {
			$user_info = get_userdata($uid);
		} else {
			$user_info = $current_user;
		}

		$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
		$sql = "SELECT * FROM `$table_name` ";
		$sql .= "WHERE `user_id` = " . $user_info->ID . " ";
		//$sql   .= "AND `checked_lessons` != 0 ";
		$progress = $wpdb->get_results($sql);
		$user_array_count = 0;
		$precent = 0;
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
	 * @param $course_id - Id поста
	 * @return array [done - количество пользовтелей, которые прошли курс]
	 *    in_progress - количество пользовтелей, которые проходят курс,
	 *  les_count - количество уроков в массиве]
	 */
	public function get_course_info($course_id)
	{
		global $current_user, $wpdb;

		$done = $in_progress = 0;
		$user_done_ids = $user_active_ids = array();

		$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
		$sql = "SELECT * FROM `$table_name` WHERE `post_id` = {$course_id}";
		$progress = $wpdb->get_results($sql);
		if ($progress) {
			foreach ($progress as $key => $value) {
				$lessons_count = $value->lessons_count;
				if ($value->checked_lessons != 0) {
					$checked_lessons = count(explode(',', $value->checked_lessons));
				} else {
					$checked_lessons = 0;
				}

				if ($lessons_count != $checked_lessons) {
					if (!in_array($value->user_id, $user_active_ids)) {
						array_push($user_active_ids, $value->user_id);
					}
					$in_progress++;
				} else {
					if (!in_array($value->user_id, $user_done_ids)) {
						array_push($user_done_ids, $value->user_id);
					}
					$done++;
				}
				$les_count = $lessons_count;
			}
			$out['done'] = $done;
			$out['in_progress'] = $in_progress;
			$out['les_count'] = get_post_meta($course_id, 'publication_count')[0];
			$out['active_users'] = $user_active_ids;
			$out['done_users'] = $user_done_ids;
		} else {
			$out['done'] = 0;
			$out['in_progress'] = 0;
			$out['les_count'] = get_post_meta($course_id, 'publication_count')[0];
			$out['active_users'] = 0;
			$out['done_users'] = 0;
		}

		return $out;
	}

	/**
	 *  Возвращает информацию по статистике пользователя пройденные и активные
	 */
	public function get_user_info($id = false)
	{
		global $current_user, $wpdb;

		if (!$id) {
			$user_id = $current_user->ID;
		} else {
			$user_id = (int)$id;
		}
		$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
		$sql = "SELECT * FROM `$table_name` WHERE `user_id` = {$user_id}";
		$progress = $wpdb->get_results($sql);

		$in_progress = $done = 0;
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
				} else {
					$done++;
				}
				$les_count = $lessons_count;
			}
			$out['done'] = $done;
			$out['in_progress'] = $in_progress;
		} else {
			$out['done'] = 0;
			$out['in_progress'] = 0;
		}

		return $out;
	}

	/**
	 * Пересчитывает всю статистику
	 */
	public function refresh()
	{
		global $wpdb;

		$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
		$sql = "SELECT * FROM `$table_name` ";
		$progress = $wpdb->get_results($sql);

		foreach ($progress as $key => $value) {

			$user_exist = get_userdata($value->user_id);
			$post_exist = get_post_status($value->post_id);

			if (!$user_exist || !$post_exist) {
				$del_sql = "DELETE FROM `wp_user_add_info` WHERE `id` = {$value->id}";
				$wpdb->query($del_sql);
			}
		}

	}

	/**
	 * Return post count by any format
	 * @param $format - format name {aside, gallery, image, chat}
	 * @return int - posts count by format
	 */
	function getPostsCountByFormat($term, $taxonomy, $type = 'post')
	{
        $args = array (
            'fields'         =>'ids',
            'posts_per_page' => -1, //-1 to get all post
            'post_type'      => $type,
            'tax_query'      => array (
                array (
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => $term
                )
            )
        );

        if ( $posts = get_posts( $args ) )
            return count( $posts );

        return 0;
	}

	/**
	 * Display statistic block
	 */
	function renderHeaderStatistic($type = false)
	{
		$data = new stdClass();
        $data->type = $type;
		Diductio::gi()->loadView('statistic_block', $data);
	}
}