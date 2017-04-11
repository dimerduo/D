<?php

/**
 * Class Diductio - main class of the Diductio Template
 */
class Diductio
{
	/**
	 * @var static table name
	 */
	public $stat_table;

	/**
	 * @var post object
	 */
	public $post;

    /**
     * @var object user class object
     */
    public $user;

    /**
     * @var object post class object
     */
    public $statistic;

	/**
	 * @var Singleton The reference to *Singleton* instance of this class
	 */
	protected static $instance;


	/**
	 * @var  array main setting of the diductio theme
	 */
	public $settings;

	/**
	 * Diductio constructor {empty - singleton}
	 */
	public function __construct()
	{

	}

    /**
     * Return User class object
     */
	public function getUserClass()
    {
        return $this->user;
    }
    
    public static function includeStyles()
    {
        // Add bootstrap
        wp_enqueue_style('diductio-bootstrap-style',
            "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css");
        wp_enqueue_style('diductio-bootstrap-theme',
            "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css");
    }
    /**
     *  Return Post class object
     */
    public function getPostClass()
    {
        return $this->post;
    }

    /**
     *  Return Statistic class object
     */
    public function getStatisticClass()
    {
        return $this->statistic;
    }

	/**
	 *  Diductio clone (can't clone - singleton)
	 */
	private function __clone()
	{
	}

	/**
	 * @return Singleton
	 */
	public static function getInstance()
	{
		if (null === static::$instance) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * @return Singleton
	 */
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

	/**
	 * Loading HTML view to the template
	 * @param $view_name - file name
	 * @param bool $data - add some data
	 */
	public function loadView($view_name, $data = false)
	{
		$view_path = get_template_directory() . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "{$view_name}.php";
		if (file_exists($view_path)) {
			include_once($view_path);
		}
	}

}
?>