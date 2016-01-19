<?php

$view_path = get_stylesheet_directory()."/view/";

// (1) Удаления даты и количества комментариев из ленты записей, удаление даты и админа из тела
if (!function_exists('twentyfifteen_entry_meta')) {
    function twentyfifteen_entry_meta() {
		if ( is_sticky() && is_home() && ! is_paged() ) {
			printf( '<span class="sticky-post">%s</span>', __( 'Featured', 'twentyfifteen' ) );
		}
		$format = get_post_format();
		if ( current_theme_supports( 'post-formats', $format ) ) {
			printf( '<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
				sprintf( '<span class="screen-reader-text">%s </span>', _x( 'Format', 'Used before post format.', 'twentyfifteen' ) ),
				esc_url( get_post_format_link( $format ) ),
				get_post_format_string( $format )
			);
		}
		if ( 'post' == get_post_type() ) {
			$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'twentyfifteen' ) );
			if ( $categories_list && twentyfifteen_categorized_blog() ) {
				printf( '<span  class="cat-links 2"><span class="screen-reader-text">%1$s </span>%2$s</span>',
					_x( 'Categories', 'Used before category names.', 'twentyfifteen' ),
					$categories_list
				);
			}

			$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'twentyfifteen' ) );
			if ( $tags_list ) {
				printf( '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
					_x( 'Tags', 'Used before tag names.', 'twentyfifteen' ),
					$tags_list
				);
			}
		}
		if ( is_attachment() && wp_attachment_is_image() ) {
			// Retrieve attachment metadata.
			$metadata = wp_get_attachment_metadata();

			printf( '<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>',
				_x( 'Full size', 'Used before full size attachment link.', 'twentyfifteen' ),
				esc_url( wp_get_attachment_url() ),
				$metadata['width'],
				$metadata['height']
			);
		}
    }
}

// (6) Функция вывода меток на странице Источники
function func_list_sources( $atts ){
	global $view_path;
	$tags = get_tags();
	if(file_exists($view_path."istochniki_page.php")) {
		require_once($view_path."istochniki_page.php");
	} 
}

// (7) Шорткод вывода ленты меток
add_shortcode( 'istochniki', 'func_list_sources' );

// (32) Шорткод страницы  "Моя зачетка" 
add_shortcode( 'zachetka', 'moya_zachetka' );
function moya_zachetka() {
	global $view_path, $wpdb;
	
	$user_id = get_current_user_id();
	$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
	$sql  = "SELECT * FROM `$table_name` WHERE `user_id` = '{$user_id}' ";
	$progress = $wpdb->get_results($sql);
	$learned_lessons = array();
	foreach ($progress as $lesson_key => $lesson_value) {
		$lessons_count = $lesson_value -> lessons_count;
		if($lesson_value->checked_lessons != 0) {
			$checked_lessons = explode(',', $lesson_value->checked_lessons);
			$checked_lessons_count = count($checked_lessons);
		} else {
			$checked_lessons_count = 0;
		}
		if($checked_lessons_count == $lessons_count) {
			$post_info = get_post($lesson_value->post_id);
			$new_data['post_title'] = $post_info->post_title;
			$new_data['post_url'] = get_permalink($post_info->ID);
			$learned_lessons[$lesson_key] = $new_data;
		}
	}
	unset($new_data);
	if(file_exists($view_path."moya_zachetka_page.php")) {
		require_once($view_path."moya_zachetka_page.php");
	} 
}
// (32) Шорткод страницы  "Моя зачетка"  end

// (2) Модификация виджета Мета
function remove_meta_widget() {
    unregister_widget('WP_Widget_Meta');
    register_widget('WP_Widget_Meta_Mod');
    unregister_widget('WP_Widget_Recent_Comments');
    register_widget('WP_Widget_Recent_Comments_Mod');
}

add_action( 'widgets_init', 'remove_meta_widget' );

class WP_Widget_Meta_Mod extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_meta', 'description' => __( "Login, RSS, &amp; WordPress.org links.") );
		parent::__construct('meta', __('Meta'), $widget_ops);
	}

	/**
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty($instance['title']) ? __( 'Meta' ) : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
?>
			<ul>
			<?php 
			if ( is_user_logged_in() ) {
				$user_ID = get_current_user_id();
				$favorites_array = get_user_meta($user_ID, 'wpfp_favorites');
				$comment_args = array(
					'author__in' => $user_ID
					);
				$comments_count = count(get_comments($comment_args));
				if($favorites_array) {	
					$fav_count = 0;
					$moya_zachetka_items_count  = 0 ;
					global $wpdb;
			        $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
					foreach ($favorites_array[0] as $fav_key => $fav_value) {
						$my_array_post_id = $fav_value;
				        $sql  = "SELECT * FROM `$table_name` WHERE `user_id` = '{$user_ID}' ";
				        $sql .= "AND `post_id` = '{$my_array_post_id}'";
				        $progress = $wpdb->get_row($sql);
	        			$lessons_count = $progress -> lessons_count;
	        			if($progress->checked_lessons != 0) {
	        				$checked_lessons = explode(',', $progress->checked_lessons);
	        				$checked_lessons_count = count($checked_lessons);
	        			} else {
	        				$checked_lessons_count = 0;
	        			}
	        			if($lessons_count  != $checked_lessons_count) {
	        				$fav_count++;
	        			} else {
	        				$moya_zachetka_items_count ++ ;
	        			}
					}
				} else {
					$fav_count = 0;
					$moya_zachetka_items_count = 0;
				}
				
				echo "<li><a href='/moi-kursy'>Мои массивы <span class='label label-success right-count'>".$fav_count."</span></a></li>";
				echo "<li><a href='/moya-zachetka'>Моя зачетка <span class='label label-success right-count'>".$moya_zachetka_items_count."</span></a></li>";
				echo "<li><a href='/comments'>Мои комментарии <span class='label label-success right-count'>".$comments_count."</span></a></li>";
				echo "<li><a href='/wp-admin/profile.php'>Мой профиль</a></li>";
			}
			
			?>
			<?php
				if(!is_user_logged_in()){
					wp_register(); 				
				} 
			?>
			<li><?php wp_loginout(); ?></li>
<?php
			
?>
			</ul>
<?php
		echo $args['after_widget'];
	}

	/**
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
	}

	/**
	 * @param array $instance
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = strip_tags($instance['title']);
?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
	}
}


// (21) Редактирование виджета "Свежие комментарии"

add_shortcode( 'my_comments', 'get_my_comments' );
/**
 * Recent_Comments widget class
 *
 * @since 2.8.0
 */
class WP_Widget_Recent_Comments_Mod extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_recent_comments', 'description' => __( 'Your site&#8217;s most recent comments.' ) );
		parent::__construct('recent-comments', __('Recent Comments'), $widget_ops);
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
		global $comments, $comment;

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
		$comments = get_comments( apply_filters( 'widget_comments_args', array(
			'number'      => $number,
			'status'      => 'approve',
			'post_status' => 'publish'
		) ) );

		$output .= $args['before_widget'];
		if ( $title ) {
			$output .= $args['before_title'] . $title . $args['after_title'];
		}

		$output .= '<ul id="recentcomments">';
		if ( is_array( $comments ) && $comments ) {
			// Prime cache for associated posts. (Prime post term cache if we need it for permalinks.)
			$post_ids = array_unique( wp_list_pluck( $comments, 'comment_post_ID' ) );
			_prime_post_caches( $post_ids, strpos( get_option( 'permalink_structure' ), '%category%' ), false );

			foreach ( (array) $comments as $comment) {
				$output .= '<li class="recentcomments">';
				/* translators: comments widget: 1: comment author, 2: post link */
				$output .= sprintf( _x( '%1$s', 'widgets' ),
					' <a class="link-style-1" href="' . esc_url( get_permalink( $comment->comment_post_ID ) ) . '"> ' . get_the_title( $comment->comment_post_ID ) . '</a>'
				);
				$output .= "<div class='comment-body'>".get_comment_excerpt($comment->id) ."";
				$output .="<span><a class='link-style-1' href='". esc_url( get_comment_link( $comment->comment_ID ) ) ."'>&nbsp;#</a></span></div>";
				$output .= '</li>';
			}
		}
		$output .= '</ul>';
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

function get_my_comments() {
	global $view_path;

	$user = wp_get_current_user();
	$args = array(
		'author__in' => $user->id
	);
	$user_comments =get_comments($args);

	if(file_exists($view_path."my_comments_page.php")) {
		require_once($view_path."my_comments_page.php");
	} 
}
// (21) Редактирование виджета "Свежие комментарии" end

// (12 глобальный) Подключения JS файлов и файлов стилизации
add_action( 'wp_enqueue_scripts', 'my_scripts_method' );
function my_scripts_method() {
 wp_register_script('diductio-script', get_stylesheet_directory_uri()."/diductio.js");
 
// (13 глобальный) Опции JS по умолчанию
 $didaction_object = array(
  'child_theme_url' => get_stylesheet_directory_uri(),
 );

 wp_localize_script( 'diductio-script', 'diductioObject', $didaction_object );
 
 
 wp_enqueue_script( 'diductio-script' );

// (14) Подключение bootstrap
	wp_register_script('diductio-bootstrap-js', "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js");
	wp_enqueue_script( 'diductio-bootstrap-js' );
	wp_enqueue_style( 'diductio-bootstrap-style', "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" );
	wp_enqueue_style( 'diductio-bootstrap-theme', "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" );
// (14) Подключение bootstrap end

}

// (15) Вывод прогресса в "Мои курсы"
function diductio_add_progress($post_id){
	global $wpdb;
	
	$user_id = get_current_user_id();
	$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
	$sql  = "SELECT * FROM `$table_name` WHERE `user_id` = '{$user_id}' ";
	$sql .= "AND `post_id` = '{$post_id}'";
	$progress = $wpdb->get_row($sql);

	if($progress) {	
		if($progress->checked_lessons != "0") {
			$checked_count = count(explode(',', $progress->checked_lessons));
			$percent =  (100 * $checked_count) / $progress->lessons_count;
		} else {
			$percent =  0;
		}
		
		$progressbar_class = "";
		if($percent == 100) {
			$progressbar_class = "progress-bar-success";
		}

		$progress_html = "<div class='progress'>
				  				<div class='progress-bar {$progressbar_class}' role='progressbar' aria-valuenow='{$percent}' aria-valuemin='0' aria-valuemax='100' style='width:{$percent}%;'>
					    		{$percent} %
					 			</div>
							</div>";
	} else {
		$progress_html = "<div class='progress'>
				  				<div class='progress-bar' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width:0%;'>
					    		0%
					 			</div>
							</div>";
	}
	
	echo $progress_html;
}

// (16) Добавление хвоста в URL для правильного открытия нужного место в уроке
function get_first_unchecked_lesson($post_id) {
	global $wpdb;

	$user_id = get_current_user_id();

	$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
	$sql  = "SELECT * FROM `$table_name` WHERE `user_id` = '{$user_id}' ";
	$sql .= "AND `post_id` = '{$post_id}'";
	$progress = $wpdb->get_row($sql);

	$all_lessons = range(1, $progress->lessons_count);
	$lessons_checked = explode(',', $progress->checked_lessons);

	if($all_lessons && count($all_lessons) > 1) {
		$unchecked_array = array_diff($all_lessons, $lessons_checked);
		if(!empty($unchecked_array)) {
			$first_unchecked = min($unchecked_array);
			if($first_unchecked) {
				return "#lesson-".$first_unchecked;
			}
		}

	}
}
// (20) Стилизация авторизации, регистрации, восстановления пароля, выхода
function custom_logo(){
   echo '
   <style type="text/css">
        #login h1 a { background: url('. get_stylesheet_directory_uri() .'/images/logo.png) no-repeat 0 0 !important; width:320px; height: 123px;    box-shadow: 0 1px 3px rgba(0,0,0,.13); }
    </style>';
}
add_action('login_head', 'custom_logo');

/* Ставим ссылку с логотипа на сайт, а не на wordpress.org */
add_filter( 'login_headerurl', create_function('', 'return get_home_url();') );
 
/* убираем title в логотипе "сайт работает на wordpress" */
add_filter( 'login_headertitle', create_function('', 'return false;') );
// (20) Стилизация авторизации, регистрации, восстановления пароля, выхода end


// (25) Стилизация "Читать далее"
add_action( 'the_content_more_link', 'read_more_customize', 10, 2 );
function read_more_customize( $link, $text )
{
	
    return str_replace(
        'more-link',
        'more-link link-style-1',
        $link
    );
}

// (25) Стилизация "Читать далее" end

// (26) Удаление "Рубрика", "Метка" из лент рубрик и источников
add_filter('get_the_archive_title', function ($title) {

	$title = str_replace(array('Рубрика:','Метка:'), '', $title);
	return $title;
});
// (26) Удаление "Рубрика", "Метка" из лент рубрик и источников end

// (27) Редирект на главную после после авторизации
function login_redirect( $redirect_to, $request, $user ){
    return home_url();
}
add_filter( 'login_redirect', 'login_redirect', 10, 3 );
// (27) Редирект на главную после после авторизации end

// (28) Модификация личного кабинета
function remove_menus (){
	global $current_user; 
	get_currentuserinfo();
	
	if(user_can( $current_user, "subscriber" )) {
		remove_menu_page( 'index.php' );
	}  
	remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

}
add_action( 'admin_menu', 'remove_menus' );
function init_function() {
	global $current_user; 
	get_currentuserinfo();
	if(user_can( $current_user, "subscriber" )) {
		update_user_meta($current_user->ID,'show_admin_bar_front', 'false');
	}
}
add_action( 'init', 'init_function' );
// (28) Модификация личного кабинета end

// (33c) Модификация внешнего вида комментариев
function diductio_comments($comment, $args, $depth) {
 $GLOBALS['comment'] = $comment; ?>
 <li <?php comment_class(); ?> class ="comment" id="li-comment-<?php comment_ID() ?>">
	 <div class="comment-body" id="comment-<?php comment_ID(); ?>">
		 	 <?php comment_text() ?>
		 <div class="col-md-12 col-sm-12 col-xs-12 comment-meta-container">
		 	 <div class="comment-meta" id="reply-link">
		 	 <?php if(is_user_logged_in()): ?>
		 	 	<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		 	 <?php endif;?>
		 	 </div>
		 	 <div class="comment-meta comment-author">
		 	 	<?php 
		 	 		$user_name_str = substr(get_comment_author(),0, 20); 
					printf(__('<b>%s</b>'), $user_name_str) 
				?>
		 	 </div>
		 	 <div class="comment-meta comment-date">
		 	 	<?php printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time()) ?>
		 	 </div>
		 	 <div class="comment-meta comment-edit"	>
		 	 	<?php edit_comment_link(__('Edit'),'&nbsp; ',''); ?>
		 	 </div>
		 </div> 
	 </div>	
<?php
 }

function myclass_edit_comment_link( $output ) {
  $myclass = 'link-style-2';
  return preg_replace( '/comment-edit-link/', 'comment-edit-link ' . $myclass, $output, 1 );
}

add_filter( 'edit_comment_link', 'myclass_edit_comment_link' );

add_filter( 'comment_form_defaults', 'sp_comment_form_defaults' );
function sp_comment_form_defaults( $defaults ) {

    $defaults['title_reply'] = "";
      $defaults['comment_field'] = '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="10" aria-required="true"></textarea></p>';
    return $defaults;

}
// (33c) Модификация внешнего вида комментариев end

// (38) Работа с массивами текущих и пройденных
function array_complite($post_id) {
	global $view_path, $wpdb;
	
	$user_id = get_current_user_id();
	$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
	$sql   = "SELECT * FROM `$table_name` WHERE `user_id` = '{$user_id}' ";
	$sql  .= "AND `post_id` = '{$post_id}'";
	$progress = $wpdb->get_results($sql);
	if(!empty($progress)) {
		if($progress[0]->checked_lessons != 0){
			$checked_lessons = count(explode(',', $progress[0]->checked_lessons));
		} else {
			$checked_lessons = 0;
		}
		$lessons_count = $progress[0]->lessons_count;

		if($checked_lessons==$lessons_count) {
			return true;
		}  else {
			return false;
		}
	} else {
		return false;
	}
}

function get_courses( $is_complite = true) {
	global $view_path, $wpdb;
	
	$user_id = get_current_user_id();
	$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
	$courses_array = array();

	
	$sql  = "SELECT DISTINCT(`post_id`) FROM `$table_name`";
	$progress = $wpdb->get_results($sql);

	foreach ($progress as $course_key => $course_value) {
		$complite_count = 0;
		$in_progress_count = 0;
		$post_id = $course_value->post_id;
		$post_info = get_post($post_id);
		$sql   = "SELECT  *  FROM `$table_name` WHERE ";
		$sql  .= "`post_id` = '{$post_id}'";
		$post_progress_info = $wpdb->get_results($sql);
		foreach ($post_progress_info as $post_progress_key => $post_progress_value) {
			if($post_progress_value->checked_lessons != 0) {
				$post_checked_lessons = count(explode(',', $post_progress_value->checked_lessons));
			} else {
				$post_checked_lessons = 0;
			}
			$post_lessons_count = $post_progress_value->lessons_count;
			if($post_checked_lessons ==  $post_lessons_count) {
				$complite_count++;
			} else {
				$in_progress_count ++; 
			}
		}
		$post_info -> complite_count = $complite_count;
		$post_info -> in_progress_count = $in_progress_count;
		$courses_array[$post_id]  = $post_info;
	}
	if($is_complite) {
		usort($courses_array, function($a, $b) {
		    return  $b->complite_count - $a->complite_count;
		});
	} else {
		usort($courses_array, function($a, $b) {
		    return  $b->in_progress_count - $a->in_progress_count;
		});
	}
	
	return $courses_array;
}
// (38) Работа с массивами текущих и пройденных end

// (36) Изменение шаблонов
add_filter('template_include', 'my_template');
function my_template( $template ) {

	// если это страница со слагом projjdennye-massivy, используем файл шаблона page-arrays.php
	// используем условный тег is_page()
	
	if( is_page('projjdennye-massivy') || is_page('aktivnye-massivy') ){
		if ( $new_template = locate_template( array( 'page-arrays.php' ) ) )
			return $new_template ;
	} elseif(is_page('istochniki') ){
		// если это страница со слагом istochniki(страница источников), используем файл шаблона page-istochiki
		// используем условный тег is_page()
		if ( $new_template = locate_template( array( 'page-istochniki.php' ) ) )
		return $new_template ;
	}
	else {
			return $template;		
	}
}
// (36) Изменение шаблонова end

// (45) Стилизация количества записей в виджете категорий
function categories_postcount_filter ($variable) {
   $variable = str_replace('(', '<span class="label label-success right-count">', $variable);
   $variable = str_replace(')', '</span>', $variable);
   return $variable;
}
add_filter('wp_list_categories','categories_postcount_filter');
// (45) Стилизация количества записей в виджете категорий end

// (47) Связывание добавление и удаление в избранное с логикой зачётки
add_action( 'post_updated', 'post_update_method', 10, 3 );
function post_update_method($post_ID, $post_after, $post_before){
    global $wpdb;

    $words_array = str_word_count($post_after->post_content, 1);
    $accordion_count = 0; 
    foreach ($words_array as $key => $value) {
    	if($value=='accordion-item') $accordion_count++;
    }
    if($accordion_count % 2 == 0){
    	$accordion_count = $accordion_count / 2;
    }
	
	$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
	$sql   = "UPDATE {$table_name} SET `lessons_count` = {$accordion_count} ";
	$sql  .= " WHERE `post_id` = {$post_ID}";
	$wpdb->query($sql);
}
function remove_br_accordion($content) {
	$array = array(
		'<p>['    => '[',
		']</p>'   => ']',
		']<br />' => ']',
		']<br>'   => ']'
	);
	$content = strtr($content, $array);

	return $content;
}
add_filter('the_content', 'remove_br_accordion');	
add_action('wpfp_after_add', 'add_post_to_statistic');

function add_post_to_statistic($post_id)
{
	global $current_user, $wpdb;
	$user_id = $current_user->ID;
	$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
	$wpdb->insert( 
		$table_name, 
		array( 
			'user_id' => $user_id, 
			'post_id' => $post_id, 
			'lessons_count' => 1, 
			'checked_lessons' => 0, 
		), 
		array( 
			'%d', 
			'%d', 
			'%d', 
			'%s', 
		) 
	);
}

add_action('wpfp_after_remove', 'remove_post_from_statistic');
function  remove_post_from_statistic ($post_id) {
	global $current_user, $wpdb;
	$user_id = $current_user->ID;
	$table_name = $wpdb->get_blog_prefix() . 'user_add_info';

	$wpdb->delete( $table_name, array( 'user_id' => $user_id, 'post_id' => $post_id ) );
}
// (47) Связывание добавление и удаление в избранное с логикой зачётки end

//пересчет счетчика перед переносом поста в корзину
add_action('wp_trash_post','before_trash');
function before_trash($postid) {
	print_r($postid);
	exit;
}


//удаление поста из статистической таблицы пользователей 
add_action( 'before_delete_post', 'course_removed' );
function course_removed($postid) {
	global $current_user, $wpdb;

	$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
	$sql = "DELETE FROM `wp_user_add_info` WHERE `post_id` = {$postid}";
	$wpdb->query($sql);
}


?>
