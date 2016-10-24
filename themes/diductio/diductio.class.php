<?php

class Diductio
{
	public $stat_table;

	public $post;

	/**
	 * @var Singleton The reference to *Singleton* instance of this class
	 */
	protected static $instance;

	/**
	 * @var  array main setting of the diductio theme
	 */
	public $settings;

	public function __construct()
	{
		
	}

	private function __clone()
	{
	}

	public static function getInstance()
	{
		if (null === static::$instance) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	public static function gi()
	{
		return self::getInstance();
	}

	/**
	 * Удаляет всю статистику связанную с постом из таблицы надстройки
	 * @param $post_id - ID поста
	 */
	public function deleteStatByPost($post_id)
	{
		global $wpdb;
		$wpdb->delete(
			self::$instance->settings['stat_table'],
			array('post_id' => $post_id)
		);
	}

	/**
	 * Удаляет всю статистику связанную с пользователем из таблицы надстройки
	 * @param $post_id - ID пользователя
	 */
	public function deleteStatByUser($user_id)
	{
		global $wpdb;

		$wpdb->delete(
			self::$instance->settings['stat_table'],
			array('user_id' => $user_id)
		);
	}

	public function loadView($view_name, $data = false)
	{
		$view_path = get_template_directory() . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "{$view_name}.php";
		if (file_exists($view_path)) {
			include_once($view_path);
		}
	}
}
?>