<?php 
/*
Plugin Name: Diductio Subscriber
Description: This is description of this awesome plugin
Version: 2.0
Author: Aleksey Novikov
*/
register_activation_hook( __FILE__ , 'sbscr_install' );
add_action('wp_ajax_subscribe', 'subscribe');
add_action('subscriber_added', 'onSubscriberAdded', 2, 2);
add_action('wp_ajax_tag_subscribe', 'tag_subscribe');
add_action('wp_ajax_сategory_subscribe', 'category_subscribe');
add_action('wp_enqueue_scripts', 'load_scripts', 99);
add_filter('template_include', 'portfolio_page_template', 99);
add_action('widgets_init', 'WidgetInit');
add_action('single-after-stat-row', 'suggestUsers');
add_action('init', 'subscriber_init');



function load_scripts(){
    wp_enqueue_style('diductio_subscriber_style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('diductio_script', plugin_dir_url(__FILE__) . '/js/subscriber-script.js', array('jquery'), 1.1, true);
    wp_localize_script('twentyfifteen-script', 'didAjax', array('url' => admin_url('admin-ajax.php')));
    wp_register_script('suggest-user', plugin_dir_url(__FILE__) . "js/suggest_user.js");
    wp_enqueue_script('suggest-user');
    wp_enqueue_style('suggest-user-css', plugin_dir_url(__FILE__) . 'css/suggest_user.css');
}

function WidgetInit()
{
	register_widget('Diductio_subsriber');
}

/**
 * Run before installation process
 */
function sbscr_install()
{
	global $wpdb;
    $table_name = $wpdb->prefix . "didsubscriber";

    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    	
    }
}

function getSubsriberView($view_type = false){

	if(is_user_logged_in()) {

		global $author, $current_user;
		$data = new stdClass();
		$id = get_current_user_id();
		switch ($view_type) {
			//вывод view для подписки-отписки на автора
			case 'author':
				$data->html_class = "add-subscriber";
				$data->html_id = "author-" . $author->ID;
				$data->author = $author;
				$subscriber_list = get_user_meta($id, 'subscribe_to')[0];
				$data->main_phrase = "Подписаться"; 
				if(!empty($subscriber_list) && in_array($author->ID,$subscriber_list)) {
					//мы подписаны на обновление данного пользователя
					$data->main_phrase = "Отписаться"; 
				}
				break;
			//вывод view для подписки-отписки на рубрику, источник 
			case 'tag':
			    global $tag, $tag_id;
				$data->html_class = "tag-subscribe";
				$data->html_id = "tag-" . $tag->term_id;
				$tag_list = get_user_meta($id, 'signed_tags')[0];
				$data->main_phrase = "Подписаться"; 
				if(!empty($tag_list) && in_array($tag_id, $tag_list)) {
					$data->main_phrase = "Отписаться"; 
				}
				break;
			case 'category':
			    global $cat_id;
				$data->html_class = "category-subscribe";
				$data->html_id = "category-" . $cat_id;
				$data->main_phrase = "Подписаться";
				$category_list = get_user_meta($id, 'signed_categories')[0];
				if(!empty($category_list) && in_array($cat_id, $category_list)) {
					$data->main_phrase = "Отписаться";
				}
			break;
		}
		loadView('subscriber', $data);
	}
}

function loadView($view_name, $data) {
	$view_path = plugin_dir_path( __FILE__)."view/{$view_name}.php";

	if(file_exists($view_path)) {
		include_once($view_path);
	}	 
}

function pluginView($name, $data)
{
    $name = str_replace('.', DIRECTORY_SEPARATOR, $name);
    extract($data);
    return require plugin_dir_path( __FILE__)."view/{$name}.php";
}

function subscribe($user_id)
{
	$user_id = $_POST['user_id']; 
	$id = get_current_user_id();
	$subscribed_to = get_user_meta($id, 'subscribe_to')[0];
	$out['message'] = 'Вы успешно подписались на обновление';
	$unsubscribe = false;
	if($subscribed_to) {
		foreach ($subscribed_to as $key => $value) {
			if($user_id == $value) {
				$out['message'] = 'Вы успешно отписались от пользователя';
				$unsubscribe = true;
			}
		}

		if(!$unsubscribe) {
			$subscribed_to[$user_id] = $user_id;
		} else {
			unset($subscribed_to[$user_id]);
		}

		if(!empty($subscribed_to)) {
			update_user_meta($id, 'subscribe_to', $subscribed_to);
		} else {
			delete_user_meta($id, 'subscribe_to');
		}

 	} else {
 		$subscribed_to[$user_id] = $user_id; 
		add_user_meta($id, 'subscribe_to', $subscribed_to);
	}

	$out['status'] = 'ok';
	echo json_encode($out);
	wp_die();
}

function tag_subscribe()
{
	$tag_id = sanitize_text_field($_POST['tag_id']);
	$id = get_current_user_id();
	$subscribed_to = get_user_meta($id, 'signed_tags')[0];
	$out['message'] = 'Вы успешно подписались на обновление';
	$unsubscribe = false;

	if($subscribed_to) {
		foreach ($subscribed_to as $key => $value) {
			if($tag_id == $value) {
				$out['message'] = 'Вы успешно отписались от обновления';
				$unsubscribe = true;
			}
		}

		if(!$unsubscribe) {
			$subscribed_to[$tag_id] = $tag_id;
		} else {
			unset($subscribed_to[$tag_id]);
		}

		if(!empty($subscribed_to)) {
			update_user_meta($id, 'signed_tags', $subscribed_to);
		} else {
			delete_user_meta($id, 'signed_tags');
		}

 	} else {
 		$subscribed_to[$tag_id] = $tag_id;
		add_user_meta($id, 'signed_tags', $subscribed_to);
	}

	$out['status'] = 'ok';
	echo json_encode($out);
	wp_die();

}

function category_subscribe()
{
	$cat_id = sanitize_text_field($_POST['cat_id']);
	$id = get_current_user_id();
	$subscribed_to = get_user_meta($id, 'signed_categories')[0];
	$out['message'] = 'Вы успешно подписались на обновление';
	$unsubscribe = false;
	if($subscribed_to) {
		foreach ($subscribed_to as $key => $value) {
			if($cat_id == $value) {
				$out['message'] = 'Вы успешно отписались от обновления';
				$unsubscribe = true;
			}
		}

		if(!$unsubscribe) {
			$subscribed_to[$cat_id] = $cat_id;
		} else {
			unset($subscribed_to[$cat_id]);
		}

		if(!empty($subscribed_to)) {
			update_user_meta($id, 'signed_categories', $subscribed_to);
		} else {
			delete_user_meta($id, 'signed_categories');
		}

 	} else {
 		$subscribed_to[$cat_id] = $cat_id;
		add_user_meta($id, 'signed_categories', $subscribed_to);
	}

	$out['status'] = 'ok';
	echo json_encode($out);
	wp_die();
}

function portfolio_page_template( $template ) {
	
	if( is_page('subscription')  ){
		if ( $new_template =  plugin_dir_path( __FILE__)."page-subscribers.php")
			$template = $new_template ;
	}
	if(is_page('my-subscriptions')) {
		if ( $new_template =  plugin_dir_path( __FILE__)."page-my.php")
			$template = $new_template ;
	}

	return $template;
}

add_action('admin_menu', 'add_admin_menu');
function add_admin_menu() {
	add_options_page(
		'Дидактио подписка',
		'Дидактио подписка',
		'manage_options',                               
		'diductio-subsriber-options.php',                       
		'diductio_subsriber_options'                             
	);

	$option_name = 'my_option';

	// регистрируем опцию
	register_setting( 'diductio-subsriber-options', $option_name );

	// добавляем поле
	add_settings_field( 
		'myprefix_setting-id', 
		'Название опции', 
		'myprefix_setting_callback_function', 
		'diductio-subsriber-options.php', 
		'default', 
		array( 
			'id' => 'myprefix_setting-id', 
			'option_name' => 'my_option' 
		)
	);
}

function diductio_subsriber_options()
{
	$data = new stdClass();
	$data->test = 'test';
	loadView('subscriber-options', $data);
}

function myprefix_setting_callback_function( $val )
{
	$id = $val['id'];
	$option_name = $val['option_name'];
	?>
	<input 
		type="text" 
		name="<? echo $option_name ?>" 
		id="<? echo $id ?>" 
		value="<? echo esc_attr( get_option($option_name) ) ?>" 
	/> 
	<?php
}

class Diductio_subsriber extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'diductio_subscriber', 'description' => __( 'Вывод ленды "Моя подписка"' ) );
		parent::__construct('diductio-subcriber', __('Дидактио подписка'), $widget_ops);
		$this->alt_option_name = 'widget_recent_comments';

		if ( is_active_widget(false, false, $this->id_base) )
			add_action( 'wp_head', array($this, 'recent_comments_style') );

		add_action( 'comment_post', array($this, 'flush_widget_cache') );
		add_action( 'edit_comment', array($this, 'flush_widget_cache') );
		add_action( 'transition_comment_status', array($this, 'flush_widget_cache') );
	}

	/**
	 * @access public
	 */
	public function recent_comments_style() {
		/**
		 * Filter the Recent Comments default widget styles.
		 *
		 * @since 3.1.0
		 *
		 * @param bool   $active  Whether the widget is active. Default true.
		 * @param string $id_base The widget ID.
		 */
		if ( ! current_theme_supports( 'widgets' ) // Temp hack #14876
			|| ! apply_filters( 'show_recent_comments_widget_style', true, $this->id_base ) )
			return;
		?>
	<style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
<?php
	}

	/**
	 * @access public
	 */
	public function flush_widget_cache() {
		wp_cache_delete('widget_recent_comments', 'widget');
	}

	/**
	 * @global array  $comments
	 * @global object $comment
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		global $comments, $comment, $wpdb;

		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get('widget_recent_comments', 'widget');
		}
		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		$output = '';

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Comments' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;

		/**
		 * Filter the arguments for the Recent Comments widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Comment_Query::query() for information on accepted arguments.
		 *
		 * @param array $comment_args An array of arguments used to retrieve the recent comments.
		 */

		/* Так как нельзя группировать то по Post_id пишем свой запрос 
		$comments = get_comments( apply_filters( 'widget_comments_args', array(
			'number'      => 100,
			'status'      => 'approve',
			'post_status' => 'publish'
		) ) );*/

		//SQL запрос, получаем интересующие записи
		$progress_where="";
		$comments_where = "";
		if(is_user_logged_in()) {
			$id = get_current_user_id();
			$subscriber_list = get_user_meta($id, 'subscribe_to')[0];
			if($subscriber_list) {
				$subscriber_list_string = implode(",", $subscriber_list);
				$progress_where = "AND `user_id` IN ({$subscriber_list_string})";
				$comments_where = "AND wp_progres.user_id IN ({$subscriber_list_string})";
			}
		}

		$table_name = $wpdb->get_blog_prefix() . 'comments';
		$sql  = "
		  SELECT *
		  FROM (
			SELECT *
			FROM `$table_name`
			ORDER BY `comment_date` DESC
		  ) AS wp_comments
		  WHERE `comment_approved` = 1 
		  {$progress_where}
		  GROUP BY wp_comments.comment_post_id
		  ORDER BY wp_comments.comment_date DESC
		  LIMIT $number";
		//это SQL запрос прогресса 
		$table_name2 = $wpdb->get_blog_prefix() . 'user_add_info';
		$sql2  = "
		  SELECT *
		  FROM (
			SELECT *
			FROM `$table_name2`
			ORDER BY `update_at` DESC
		  ) AS wp_progres
          WHERE wp_progres.update_at != '0000-00-00 00:00:00' 
		  AND  wp_progres.checked_lessons != '0'
		  {$comments_where}
          GROUP BY  wp_progres.post_id
		  ORDER BY  wp_progres.update_at DESC
		  LIMIT $number";
		//выполняем запроссы
		$comments = array();
		$progress = array();
		if($subscriber_list)
		{
			$progress = $wpdb->get_results($sql2);
			$comments = $wpdb->get_results($sql);
		}
		$stream   = [];
		
		//формируем ленту
		if(is_array( $comments) && $comments){
		  foreach((array) $comments as $comment){

			//print_r($comment);
			$stream[] = Array(
			  'id'        => $comment->comment_ID,
			  'post_id'   => $comment->comment_post_ID,
			  'user_id'   => $comment->user_id,
			  'update_at' => $comment->comment_date_gmt,
			  'content'   => $comment->comment_content
			);
		  }
		}

		if(is_array( $progress) && $progress){
		  foreach((array) $progress as $progres){
			$stream[] = Array(
			  'post_id'   => $progres->post_id,
			  'user_id'   => $progres->user_id,
			  'update_at' => $progres->update_at,
			  'content'   => null
			);
		  }
		}
		//сортируем по дате
		usort($stream, 'sort_desc');
		//обраезаем массив по колличеству из админки
		$stream_n = array_slice($stream, 0, $number);
		unset($stream);

		$output .= $args['before_widget'];
		if ( $title ) {
			$output .= $args['before_title'] . $title . $args['after_title'];
		}
		
		#stat stream
		$output .= '<ul id="recentcomments">';
		if(!$progress && !$comments ) {
			$output .= '<li class="recentcomments">Ваша лента пуста</li>';
		} elseif(!is_user_logged_in())
			$output .= '<li class="recentcomments">Ваша лента пуста</li>';
		else {
			if ( is_array($stream_n) && $stream_n ) {
			  foreach ( (array) $stream_n as $s) {
					$user_info = get_user_by ('id', $s['user_id']);
					$user_link = get_site_url() . "/people/" . $user_info->data->user_nicename;
					
					$output .= '<li class="recentcomments">';
					$output .= "<div class='inline comment-avatar'><a href='{$user_link}'>";
					$output .= get_avatar( $user_info->data->user_email, 20 );
					$output .= "<span>";
					$output .= $user_info->data->display_name;
					
					if($s['content'] === null){
					  $small_text = "+ прогресс";
					}else{
					  $comments_count  = wp_count_comments($s['post_id']);
					  $approved = $comments_count->approved;

						$small_text = "+ комментарий";
					}

					$output .= "</span></a><small>". $small_text ."</small></div>";
					$output .= "<div class='inline comment-content'>";
					$output .= "<div class='comment-body'>";
					if($s['content'] != null ){
					  $output .= excerp_comment(get_comment($s['id'])->comment_content, 67);
					  $output .= "<a class='link-style-1' href='"
						. esc_url( get_comment_link( $s['id'] ) ) ."'>&nbsp;#</a><br>";
					  $output .= sprintf( _x( '%1$s', 'widgets' ),' <a class="link-style-1" href="' 
						. esc_url( get_permalink( $s['post_id'] ) ) . '"> ' 
						. get_the_title( $s['post_id'] ) . '</a>');
					}else{
					  $output .= sprintf( _x( '%1$s', 'widgets' ),' <a class="link-style-1" href="' 
						. esc_url( get_permalink( $s['post_id'] ) ) . '"> ' 
						. get_the_title( $s['post_id'] ) . '</a>');
					}
					$output .= "<span></span></div>";
					$output .= "</div>";
					$output .= "</li>";
				}
			}

		}
		$output .= '</ul>';

		##end stream
		$output .= $args['after_widget'];
		echo $output;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = $output;
			wp_cache_set( 'widget_recent_comments', $cache, 'widget' );
		}
	}

	/**
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = absint( $new_instance['number'] );
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_comments']) )
			delete_option('widget_recent_comments');

		return $instance;
	}

	/**
	 * @param array $instance
	 */
	public function form( $instance ) {
		$title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of comments to show:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}

function getMyPostCount()
{
	$args = array();
	$args['tax_query'] = array( 'relation' => 'OR' );
	$id = get_current_user_id();
	$tag_list = get_user_meta($id, 'signed_tags')[0];
	$category_list = get_user_meta($id, 'signed_categories')[0];
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$is_empty = true;
	if($category_list) {
		$args['category__in'] = $category_list; 
		$args['posts_per_page'] = get_option( 'posts_per_page' ); 
		$args['paged'] = $paged; 
		$is_empty = false; 
	}

	if($tag_list) {
		$args['tag__in'] = $tag_list;
		$is_empty = false; 
	}
	$the_query = new WP_Query( $args );
	
	if($is_empty) {
		return 0;
	} else {
		return $the_query->found_posts;

	}
}

function suggestUsers()
{
	global $st, $post, $current_user;
	if (is_user_logged_in()) {
		$suggesting_users = getSuggestingUsers(get_current_user_id(), $post->ID);
        pluginView('people.suggest-friend-modal', compact('suggesting_users', 'st', 'post', 'current_user'));
	}
}

function suggest_me_user()
{
    global $dPost, $wpdb;
    
    $url     = wp_get_referer();
	$post_id = url_to_postid($url);
	
	$users = $_POST['users'];
	$include = $exclude = [];
	
	foreach ($users as $user) {
		if($user['alreadyHas'] == 'true' && $user['wasChecked'] == 'false') {
			$include[] = $user;
			continue;
		}
		
		if($user['alreadyHas'] == 'false' && $user['wasChecked'] == 'true') {
			$exclude[] = $user;
		}
	}
	
	
	// exclude first
	$exclude_ids = implode(array_map(function ($item) {
		return $item['id'];
	}, $exclude), ',');
	$sql = "DELETE FROM `wp_user_add_info` WHERE `user_id` IN ({$exclude_ids}) AND `post_id` = {$post_id}  ";
	$wpdb->query($sql);
	
	// include
	$already_subscribed = getUsersByPost($post_id);
	foreach ($include as $user) {
		if (!in_array($user['id'], $already_subscribed)) {
			do_action('subscriber_added', $user, $post_id);
			add_post_to_statistic($post_id, $user['id']);
			$dPost->addToFavorite($post_id, $user['id']);
		}
	}
	
	wp_die();
}

function getSuggestingUsers($user_id, $post_id)
{
	$all_users = [];
	$subscribed_to = get_user_meta($user_id, 'subscribe_to')[0];
	// add myself
	$subscribed_to[$user_id] = $user_id;
	
	$already_subscribed = getUsersByPost($post_id);
	
	if ($subscribed_to) {
		$args = [
			'fields' => array('ID', 'display_name'),
			'include' => $subscribed_to,
		];
		$all_users = get_users($args);
	}
	foreach ($all_users as $key => $user) {
		if (!isSubsribedToMe($user)) {
			unset($all_users[$key]);
			continue;
		}
		
		$is_selected = false;
		if (in_array($user->ID, $already_subscribed)) {
			$is_selected = true;
		}
		
		$all_users[$key]->is_selected = $is_selected;
	}
	
	return (array)$all_users;
}

/**
 * Is provided user has subsribed to me
 *
 * @param  WP_User $user - User object
 * @return bool          - Is user subsribed
 */
function isSubsribedToMe($user)
{
	$me  = get_current_user_id();
	$subscribers = get_user_meta($user->ID, 'subscribe_to')[0];
	$subscribers[$user->ID] = $user->ID;
	
	if ($subscribers && is_array($subscribers)) {
		return in_array($me, $subscribers);
	}
	
	return false;
}

/**
 * Getting all users from statistic table by Post ID
 *
 * @param  int   $post_id - ID of the Post
 * @return array $result  - Users of the
 */
function getUsersByPost($post_id)
{
	global $wpdb;
	
	$sql = "SELECT `user_id` FROM `wp_user_add_info` WHERE `post_id` = {$post_id}";
	$result = $wpdb->get_results($sql, 'ARRAY_A');
	
	$users = array_map(function ($item) {
		return $item['user_id'];
	}, $result);
	
	return $users;
}

/**
 * Fire when someone is adding subsribers to the post
 *
 * @param WP_User     $user - User which has been subcribed
 * @param int $post_id - Post ID
 * @return bool
 */
function onSubscriberAdded($user, $post_id)
{
	if ($user['id'] == get_current_user_id()) {
		return false;
	}
	
	$subject = Did_EmailTemplates::POST_ADDED_TO_USERS_CABINET['subject'];
	$message = Did_EmailTemplates::POST_ADDED_TO_USERS_CABINET['body'];
	
	$added_to = get_user_by('id', $user['id']);
	$current_user = get_current_user_id();
	$user_info = get_user_by('id', $current_user);
	
	$post_url = get_permalink($post_id);
	$post_name = get_the_title($post_id);
	$user_link = get_site_url() . "/people/" . $user_info->data->user_nicename;
	
	$post_format = get_post_format($post_id);
	$translate       = array(
		'aside'       => 'Знание',
		'chat'        => 'Голосование',
		'image'       => 'Тест',
		'gallery'     => 'Задача',
		'quote'       => 'Проект',
	);
	
	$find = array('{post_link}', '{user_link}', '{post_format}');
	$replace = array(
		sprintf("<a href='%s'>%s</a>", $post_url, $post_name),
		sprintf("<a href='%s'>%s</a>", $user_link, $user_info->display_name),
		$translate[$post_format]
	);
	$message = str_replace($find, $replace, $message);
	
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$res = wp_mail($added_to->user_email, $subject, $message, $headers);
}

/**
 * Hooks subsriber init
 */
function subscriber_init()
{
	add_action('wp_ajax_nopriv_suggestUsers', 'suggest_me_user');
	add_action('wp_ajax_suggestUsers', 'suggest_me_user');
}
?>