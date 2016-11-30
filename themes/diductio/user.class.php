<?php

class User extends Diductio {
	/**
	 * @var array $settings ;
	 */
	public $settings;

	/**
	 * @int logged user ID
	 */
	public $current_user;

	/**
	 * User constructor.
	 */
	function __construct() {
		$this->current_user = get_current_user_id();
		$this->settings     = Diductio::gi()->settings;
		$this->addActions();
	}

	function addActions() {
		add_action( 'deleted_user', Array( $this, 'afterUserDelete' ) );
	}

	function afterUserDelete( $user_id ) {
		Diductio::gi()->deleteStatByUser( $user_id );
	}

	/**
	 * Getting all users by some knowledge lesson(accordion item)
	 * @param int $post_id - post ID
	 * @param int $lesson_part - lesson part of the knowledge
	 */
	public function getUserByAccordionItem( $post_id, $accordion_item ) {
		global $wpdb;

		$sql = "SELECT `user_id` FROM " . Diductio::gi()->settings['stat_table'] . " ";
		$sql .= "WHERE `post_id` = {$post_id} AND FIND_IN_SET({$accordion_item}, `checked_lessons`) > 0 ";
		$result = $wpdb->get_results($sql);

		$result_array = array();
		if(!empty($result)) {
			foreach ( $result as $item ) {
				$result_array[] = $item->user_id;
			}
			return $result_array;
		} else {
			return array();
		}
	}

	/*
	 * Getting user data such as avatar, link
	 * @param int $user_id  user ID
	 */
	public function getUserData($user_id)
	{
		$user_info = get_user_by('id', $user_id);
		$result['username'] = $user_info->user_nicename;
		$result['avatar'] = get_avatar($user_id, 24);
		$result['user_link'] = get_site_url() . "/people/" /*hardcode*/ . $user_info->user_nicename;
		$result['user_id']  = $user_id;

		return $result;
	}
}