<?php

/**
 * Twenty Fifteen functions and definitions
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link       https://codex.wordpress.org/Theme_Development
 * @link       https://codex.wordpress.org/Child_Themes
 *             Functions that are not pluggable (not wrapped in function_exists()) are
 *             instead attached to a filter or action hook.
 *             For more information on hooks, actions, and filters,
 *             {@link https://codex.wordpress.org/Plugin_API}
 * @package    WordPress
 * @subpackage Twenty_Fifteen
 * @since      Twenty Fifteen 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Twenty Fifteen 1.0
 */
if (!isset($content_width)) {
    $content_width = 660;
}

/**
 * Twenty Fifteen only works in WordPress 4.1 or later.
 */
if (version_compare($GLOBALS['wp_version'], '4.1-alpha', '<')) {
    require get_template_directory() . '/inc/back-compat.php';
}

if (!function_exists('twentyfifteen_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     *
     * @since Twenty Fifteen 1.0
     */
    function twentyfifteen_setup()
    {
        
        /*
         * Make theme available for translation.
         * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/twentyfifteen
         * If you're building a theme based on twentyfifteen, use a find and replace
         * to change 'diductio' to the name of your theme in all the template files
         */
        load_theme_textdomain('diductio');
        
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');
        
        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');
        
        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
         */
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(825, 510, true);
        
        // This theme uses wp_nav_menu() in two locations.
        register_nav_menus(array(
            'primary' => __('Primary Menu', 'diductio'),
            'social' => __('Social Links Menu', 'diductio'),
        ));
        
        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));
        
        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support('post-formats', array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'status',
            'audio',
            'chat',
        ));
        
        /*
         * Enable support for custom logo.
         *
         * @since Twenty Fifteen 1.5
         */
        add_theme_support('custom-logo', array(
            'height' => 248,
            'width' => 248,
            'flex-height' => true,
        ));
        
        $color_scheme = twentyfifteen_get_color_scheme();
        $default_color = trim($color_scheme[0], '#');
        
        // Setup the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('twentyfifteen_custom_background_args', array(
            'default-color' => $default_color,
            'default-attachment' => 'fixed',
        )));
        
        /*
         * This theme styles the visual editor to resemble the theme style,
         * specifically font, colors, icons, and column width.
         */
        add_editor_style(array('css/editor-style.css', 'genericons/genericons.css', twentyfifteen_fonts_url()));
        
        // Indicate widget sidebars can use selective refresh in the Customizer.
        add_theme_support('customize-selective-refresh-widgets');
    }
endif; // twentyfifteen_setup
add_action('after_setup_theme', 'twentyfifteen_setup');

/**
 * Register widget area.
 *
 * @since Twenty Fifteen 1.0
 * @link  https://codex.wordpress.org/Function_Reference/register_sidebar
 */
function twentyfifteen_widgets_init()
{
    register_sidebar(array(
        'name' => __('Widget Area', 'diductio'),
        'id' => 'sidebar-1',
        'description' => __('Add widgets here to appear in your sidebar.', 'diductio'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}

add_action('widgets_init', 'twentyfifteen_widgets_init');

if (!function_exists('twentyfifteen_fonts_url')) :
    /**
     * Register Google fonts for Twenty Fifteen.
     *
     * @since Twenty Fifteen 1.0
     * @return string Google fonts URL for the theme.
     */
    function twentyfifteen_fonts_url()
    {
        $fonts_url = '';
        $fonts = array();
        $subsets = 'latin,latin-ext';
        
        /*
         * Translators: If there are characters in your language that are not supported
         * by Noto Sans, translate this to 'off'. Do not translate into your own language.
         */
        if ('off' !== _x('on', 'Noto Sans font: on or off', 'diductio')) {
            $fonts[] = 'Noto Sans:400italic,700italic,400,700';
        }
        
        /*
         * Translators: If there are characters in your language that are not supported
         * by Noto Serif, translate this to 'off'. Do not translate into your own language.
         */
        if ('off' !== _x('on', 'Noto Serif font: on or off', 'diductio')) {
            $fonts[] = 'Noto Serif:400italic,700italic,400,700';
        }
        
        /*
         * Translators: If there are characters in your language that are not supported
         * by Inconsolata, translate this to 'off'. Do not translate into your own language.
         */
        if ('off' !== _x('on', 'Inconsolata font: on or off', 'diductio')) {
            $fonts[] = 'Inconsolata:400,700';
        }
        
        /*
         * Translators: To add an additional character subset specific to your language,
         * translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language.
         */
        $subset = _x('no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'diductio');
        
        if ('cyrillic' == $subset) {
            $subsets .= ',cyrillic,cyrillic-ext';
        } elseif ('greek' == $subset) {
            $subsets .= ',greek,greek-ext';
        } elseif ('devanagari' == $subset) {
            $subsets .= ',devanagari';
        } elseif ('vietnamese' == $subset) {
            $subsets .= ',vietnamese';
        }
        
        if ($fonts) {
            $fonts_url = add_query_arg(array(
                'family' => urlencode(implode('|', $fonts)),
                'subset' => urlencode($subsets),
            ), 'https://fonts.googleapis.com/css');
        }
        
        return $fonts_url;
    }
endif;

/**
 * JavaScript Detection.
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Fifteen 1.1
 */
function twentyfifteen_javascript_detection()
{
    echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}

add_action('wp_head', 'twentyfifteen_javascript_detection', 0);

/**
 * Enqueue scripts and styles.
 *
 * @since Twenty Fifteen 1.0
 */
function twentyfifteen_scripts()
{
    Diductio::includeStyles();
    // Add custom fonts, used in the main stylesheet.
    wp_enqueue_style('twentyfifteen-fonts', twentyfifteen_fonts_url(), array(), null);
    
    // Add Genericons, used in the main stylesheet.
    wp_enqueue_style('genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.2');
    
    // Load our main stylesheet.
    wp_enqueue_style('twentyfifteen-style', get_stylesheet_uri());
    
    // Load the Internet Explorer specific stylesheet.
    wp_enqueue_style('twentyfifteen-ie', get_template_directory_uri() . '/css/ie.css', array('twentyfifteen-style'),
        '20141010');
    wp_style_add_data('twentyfifteen-ie', 'conditional', 'lt IE 9');
    
    // Load the Internet Explorer 7 specific stylesheet.
    wp_enqueue_style('twentyfifteen-ie7', get_template_directory_uri() . '/css/ie7.css',
        array('twentyfifteen-style'), '20141010');
    wp_style_add_data('twentyfifteen-ie7', 'conditional', 'lt IE 8');
    
    wp_enqueue_script('twentyfifteen-skip-link-focus-fix',
        get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20141010', true);
    
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    if (is_singular() && wp_attachment_is_image()) {
        wp_enqueue_script('twentyfifteen-keyboard-image-navigation',
            get_template_directory_uri() . '/js/keyboard-image-navigation.js', array('jquery'), '20141010');
    }
    
    wp_enqueue_script('twentyfifteen-script', get_template_directory_uri() . '/js/functions.js', array('jquery'),
        '20150330', true);
    wp_localize_script('twentyfifteen-script', 'screenReaderText', array(
        'expand' => '<span class="screen-reader-text">' . __('expand child menu', 'diductio') . '</span>',
        'collapse' => '<span class="screen-reader-text">' . __('collapse child menu', 'diductio') . '</span>',
    ));
}

add_action('wp_enqueue_scripts', 'twentyfifteen_scripts');

/**
 * Add featured image as background image to post navigation elements.
 *
 * @since Twenty Fifteen 1.0
 * @see   wp_add_inline_style()
 */
function twentyfifteen_post_nav_background()
{
    if (!is_single()) {
        return;
    }
    
    $previous = (is_attachment()) ? get_post(get_post()->post_parent) : get_adjacent_post(false, '', true);
    $next = get_adjacent_post(false, '', false);
    $css = '';
    
    if (is_attachment() && 'attachment' == $previous->post_type) {
        return;
    }
    
    if ($previous && has_post_thumbnail($previous->ID)) {
        $prevthumb = wp_get_attachment_image_src(get_post_thumbnail_id($previous->ID), 'post-thumbnail');
        $css
            .= '
			.post-navigation .nav-previous { background-image: url(' . esc_url($prevthumb[0]) . '); }
			.post-navigation .nav-previous .post-title, .post-navigation .nav-previous a:hover .post-title, .post-navigation .nav-previous .meta-nav { color: #fff; }
			.post-navigation .nav-previous a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
    }
    
    if ($next && has_post_thumbnail($next->ID)) {
        $nextthumb = wp_get_attachment_image_src(get_post_thumbnail_id($next->ID), 'post-thumbnail');
        $css
            .= '
			.post-navigation .nav-next { background-image: url(' . esc_url($nextthumb[0]) . '); border-top: 0; }
			.post-navigation .nav-next .post-title, .post-navigation .nav-next a:hover .post-title, .post-navigation .nav-next .meta-nav { color: #fff; }
			.post-navigation .nav-next a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
    }
    
    wp_add_inline_style('twentyfifteen-style', $css);
}

add_action('wp_enqueue_scripts', 'twentyfifteen_post_nav_background');

/**
 * Display descriptions in main navigation.
 *
 * @since Twenty Fifteen 1.0
 * @param string  $item_output The menu item output.
 * @param WP_Post $item Menu item object.
 * @param int     $depth Depth of the menu.
 * @param array   $args wp_nav_menu() arguments.
 * @return string Menu item with possible description.
 */
function twentyfifteen_nav_description($item_output, $item, $depth, $args)
{
    if ('primary' == $args->theme_location && $item->description) {
        $item_output = str_replace($args->link_after . '</a>',
            '<div class="menu-item-description">' . $item->description . '</div>' . $args->link_after . '</a>',
            $item_output);
    }
    
    return $item_output;
}

add_filter('walker_nav_menu_start_el', 'twentyfifteen_nav_description', 10, 4);

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Twenty Fifteen 1.0
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function twentyfifteen_search_form_modify($html)
{
    return str_replace('class="search-submit"', 'class="search-submit screen-reader-text"', $html);
}

add_filter('get_search_form', 'twentyfifteen_search_form_modify');

/**
 * Implement the Custom Header feature.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/customizer.php';


//Global variables
$view_path = get_stylesheet_directory() . "/view/";
$data = new stdClass();

//OOP start here

//class autoloader function

//Deprecated autoloading method
spl_autoload_register(function ($class_name) {
    if ($class_name !== 'Diductio' && $class_name[0] == 'd') {
        $class_name = substr($class_name, 1);
    }
    $file_name = strtolower($class_name) . '.class.php';
    $file = get_template_directory() . DIRECTORY_SEPARATOR . $file_name;
    if (file_exists($file)) {
        require_once($file);
    }
});
remove_theme_support('post-formats');

//theme configuration
$settings = array();
$settings['stat_table'] = $wpdb->get_blog_prefix() . 'user_add_info';
$stat_count = $wpdb->get_row("SELECT COUNT(`id`) AS `count` FROM `{$settings['stat_table']}`");
$settings['stat_table_count'] = $stat_count->count;
$settings['view_path'] = get_stylesheet_directory() . "/view/";
$settings['post_formats_slug'] = array(
    'post-format-aside',
    'post-format-chat',
    'post-format-gallery',
    'post-format-image',
);
unset($stat_count);
$diductio = Diductio::gi();
$diductio->settings = $settings;
$dPost = new Post();
$dUser = new User();
$st = new Statistic;
Diductio::gi()->post = $dPost;
Diductio::gi()->user = $dUser;
Diductio::gi()->statistic = $st;

if (is_admin()) {
    $file_name = 'admin.class.php';
    $admin_file = get_template_directory() . DIRECTORY_SEPARATOR . $file_name;
    if (file_exists($admin_file)) {
        require_once($admin_file);
    }
}
add_action('embed_head', array($diductio, 'includeStyles'));

//OOP end here
function sort_desc($a, $b)
{
    if ($a['update_at'] < $b['update_at']) {
        return 1;
    }
}

// (1) Удаления даты и количества комментариев из ленты записей, удаление даты и админа из тела
if (!function_exists('twentyfifteen_entry_meta')) {
    function twentyfifteen_entry_meta()
    {
        if (is_sticky() && is_home() && !is_paged()) {
            printf('<span class="sticky-post">%s</span>', __('Featured', 'diductio'));
        }
        $format = get_post_format();
        if (current_theme_supports('post-formats', $format)) {
            printf('<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
                sprintf('<span class="screen-reader-text">%s </span>',
                    _x('Format', 'Used before post format.', 'diductio')),
                esc_url(get_post_format_link($format)),
                get_post_format_string($format)
            );
        }
        if ('post' == get_post_type()) {
            $categories_list = get_the_category_list(_x(', ',
                'Used between list items, there is a space after the comma.', 'diductio'));
            if ($categories_list && twentyfifteen_categorized_blog()) {
                printf('<span  class="cat-links 2"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                    _x('Categories', 'Used before category names.', 'diductio'),
                    $categories_list
                );
            }
            
            $tags_list = get_the_tag_list('',
                _x(', ', 'Used between list items, there is a space after the comma.', 'diductio'));
            if ($tags_list) {
                printf('<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                    _x('Tags', 'Used before tag names.', 'diductio'),
                    $tags_list
                );
            }
        }
        if (is_attachment() && wp_attachment_is_image()) {
            // Retrieve attachment metadata.
            $metadata = wp_get_attachment_metadata();
            
            printf('<span class="full-size-link"><span class="screen-reader-text">%1$s </span><a href="%2$s">%3$s &times; %4$s</a></span>',
                _x('Full size', 'Used before full size attachment link.', 'diductio'),
                esc_url(wp_get_attachment_url()),
                $metadata['width'],
                $metadata['height']
            );
        }
    }
}

// (6) Функция вывода меток на странице Источники
function func_list_sources($atts)
{
    global $view_path;
    $tags = get_tags();
    if (file_exists($view_path . "istochniki_page.php")) {
        require_once($view_path . "istochniki_page.php");
    }
}

// (7) Шорткод вывода ленты меток
add_shortcode('istochniki', 'func_list_sources');

// (32) Шорткод страницы  "Моя зачетка"
add_shortcode('zachetka', 'moya_zachetka');
function moya_zachetka()
{
    global $view_path, $wpdb, $author_info;
    
    $author = get_user_by('slug', get_query_var('author_name'));
    if ($author_info) {
        $user_id = $author_info->ID;
    } else {
        $user_id = get_current_user_id();
    }
    
    $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
    $sql = "SELECT * FROM `$table_name` WHERE `user_id` = '{$user_id}' ";
    $progress = $wpdb->get_results($sql);
    $learned_lessons = array();
    foreach ($progress as $lesson_key => $lesson_value) {
        $lessons_count = $lesson_value->lessons_count;
        if ($lesson_value->checked_lessons != 0) {
            $checked_lessons = explode(',', $lesson_value->checked_lessons);
            $checked_lessons_count = count($checked_lessons);
        } else {
            $checked_lessons_count = 0;
        }
        if ($checked_lessons_count == $lessons_count) {
            $post_info = get_post($lesson_value->post_id);
            $new_data['post_title'] = $post_info->post_title;
            $new_data['post_url'] = get_permalink($post_info->ID);
            $learned_lessons[$lesson_key] = $new_data;
        }
    }
    unset($new_data);
    if (file_exists($view_path . "moya_zachetka_page.php")) {
        require_once($view_path . "moya_zachetka_page.php");
    }
}

// (32) Шорткод страницы  "Моя зачетка"  end

// (2) Модификация виджета Мета
function remove_meta_widget()
{
    unregister_widget('WP_Widget_Meta');
    register_widget('WP_Widget_Meta_Mod');
    unregister_widget('WP_Widget_Recent_Comments');
    register_widget('WP_Widget_Recent_Comments_Mod');
}

add_action('widgets_init', 'remove_meta_widget');

class WP_Widget_Meta_Mod extends
    WP_Widget
{
    
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'widget_meta',
            'description' => __("Login, RSS, &amp; WordPress.org links."),
        );
        parent::__construct('meta', __('Meta'), $widget_ops);
    }
    
    /**
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters('widget_title', empty($instance['title']) ? __('Meta') : $instance['title'],
            $instance, $this->id_base);
        
        echo $args['before_widget'];
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        if (is_user_logged_in()) {
            global $st, $dUser, $dPost;
            $user_ID = get_current_user_id();
            $user_statistic = $st->get_user_info($user_ID);
            $comments_count = $dUser->get_comments_count($user_ID);
            $subscription_count = $dUser->getSubscriptionsCount($user_ID);
            $progress_percent = $st->get_knowledges($user_ID, 'active');
            $percent = 0;
            if ($progress_percent) {
                $tmp_precent = 0;
                foreach ($progress_percent as $item) {
                    $tmp_precent += $st->get_user_progress_by_post($item, $user_ID);
                }
                $percent = round($tmp_precent / count($progress_percent), 2);
            }
            
            //Get knowledges
            $post_ids = $st->get_knowledges($user_ID, 'active');
            $knowledges = [];
            if ($post_ids) {
                $qry = array(
                    'posts_per_page' => 5,
                    'limit' => 5,
                    'orderby' => 'ID',
                    'post__in' => $post_ids,
                );
                $knowledges = get_posts($qry, ARRAY_A);
            }
        }
        view('widgets/my-progress', compact('args', 'title', 'user_ID', 'user_statistic', 'percent', 'knowledges'));
        echo $args['after_widget'];
    }
    
    /**
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        
        return $instance;
    }
    
    /**
     * @param array $instance
     */
    public function form($instance)
    {
        $instance = wp_parse_args((array)$instance, array('title' => ''));
        $title = strip_tags($instance['title']);
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input
                class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                name="<?php echo $this->get_field_name('title'); ?>" type="text"
                value="<?php echo esc_attr($title); ?>"/></p>
        <?php
    }
}

// (21) Редактирование виджета "Свежие комментарии"

//add_shortcode( 'my_comments', 'get_my_comments' );
/**
 * Recent_Comments widget class
 *
 * @since 2.8.0
 */
class WP_Widget_Recent_Comments_Mod extends
    WP_Widget
{
    
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'widget_recent_comments',
            'description' => __('Your site&#8217;s most recent comments.'),
        );
        parent::__construct('recent-comments', __('Recent Comments'), $widget_ops);
        $this->alt_option_name = 'widget_recent_comments';
        
        if (is_active_widget(false, false, $this->id_base)) {
            add_action('wp_head', array($this, 'recent_comments_style'));
        }
        
        add_action('comment_post', array($this, 'flush_widget_cache'));
        add_action('edit_comment', array($this, 'flush_widget_cache'));
        add_action('transition_comment_status', array($this, 'flush_widget_cache'));
    }
    
    /**
     * @access public
     */
    public function recent_comments_style()
    {
        /**
         * Filter the Recent Comments default widget styles.
         *
         * @since 3.1.0
         * @param bool   $active Whether the widget is active. Default true.
         * @param string $id_base The widget ID.
         */
        if (!current_theme_supports('widgets') // Temp hack #14876
            || !apply_filters('show_recent_comments_widget_style', true, $this->id_base)
        ) {
            return;
        }
        ?>
        <style type="text/css">.recentcomments a {
                display: inline !important;
                padding: 0 !important;
                margin: 0 !important;
            }</style>
        <?php
    }
    
    /**
     * @access public
     */
    public function flush_widget_cache()
    {
        wp_cache_delete('widget_recent_comments', 'widget');
    }
    
    /**
     * @global array  $comments
     * @global object $comment
     * @param array   $args
     * @param array   $instance
     */
    public function widget($args, $instance)
    {
        global $comments, $comment, $wpdb;
        
        $cache = array();
        if (!$this->is_preview()) {
            $cache = wp_cache_get('widget_recent_comments', 'widget');
        }
        if (!is_array($cache)) {
            $cache = array();
        }
        
        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }
        
        if (isset($cache[$args['widget_id']])) {
            echo $cache[$args['widget_id']];
            
            return;
        }
        
        $output = '';
        
        $title = (!empty($instance['title'])) ? $instance['title'] : __('Recent Comments');
        
        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        
        $number = (!empty($instance['number'])) ? absint($instance['number']) : 5;
        if (!$number) {
            $number = 5;
        }
        
        /**
         * Filter the arguments for the Recent Comments widget.
         *
         * @since 3.4.0
         * @see   WP_Comment_Query::query() for information on accepted arguments.
         * @param array $comment_args An array of arguments used to retrieve the recent comments.
         */
        
        /* Так как нельзя группировать то по Post_id пишем свой запрос
        $comments = get_comments( apply_filters( 'widget_comments_args', array(
            'number'      => 100,
            'status'      => 'approve',
            'post_status' => 'publish'
        ) ) );*/
        
        //SQL запрос, получаем интересующие записи
        $table_name = $wpdb->get_blog_prefix() . 'comments';
        $sql
            = "
		  SELECT *
		  FROM (
			SELECT *
			FROM `$table_name`
			ORDER BY `comment_date` DESC
		  ) AS wp_comments
		  WHERE `comment_approved` = 1
		  GROUP BY wp_comments.comment_post_id
		  ORDER BY wp_comments.comment_date DESC
		  LIMIT $number";
        
        //это SQL запрос прогресса
        $table_name2 = $wpdb->get_blog_prefix() . 'user_add_info';
        $sql2
            = "
		  SELECT *
		  FROM (
			SELECT *
			FROM `$table_name2`
			ORDER BY `update_at` DESC
		  ) AS wp_progres
          WHERE wp_progres.update_at != '0000-00-00 00:00:00' 
		  AND  wp_progres.checked_lessons != '0'
          GROUP BY  wp_progres.post_id
		  ORDER BY  wp_progres.update_at DESC
		  LIMIT $number";
        
        //выполняем запроссы
        $progress = $wpdb->get_results($sql2);
        $comments = $wpdb->get_results($sql);
        $stream = [];
        
        //формируем ленту
        if (is_array($comments) && $comments) {
            foreach ((array)$comments as $comment) {
                
                //print_r($comment);
                $stream[] = Array(
                    'id' => $comment->comment_ID,
                    'post_id' => $comment->comment_post_ID,
                    'user_id' => $comment->user_id,
                    'update_at' => $comment->comment_date_gmt,
                    'content' => $comment->comment_content,
                );
            }
        }
        
        if (is_array($progress) && $progress) {
            foreach ((array)$progress as $progres) {
                $stream[] = Array(
                    'post_id' => $progres->post_id,
                    'user_id' => $progres->user_id,
                    'update_at' => $progres->update_at,
                    'content' => null,
                );
            }
        }
        //сортируем по дате
        usort($stream, 'sort_desc');
        //обраезаем массив по колличеству из админки
        $stream_n = array_slice($stream, 0, $number);
        unset($stream);
        
        $output .= $args['before_widget'];
        if ($title) {
            $output .= $args['before_title'] . $title . $args['after_title'];
        }
        
        #stat stream
        $output .= '<ul id="recentcomments">';
        if (is_array($stream_n) && $stream_n) {
            
            foreach ((array)$stream_n as $s) {
                $user_info = get_user_by('id', $s['user_id']);
                $user_link = get_site_url() . "/people/" . $user_info->data->user_nicename;
                
                $output .= '<li class="recentcomments">';
                $output .= "<div class='inline comment-avatar'><a href='{$user_link}'>";
                $output .= get_avatar($user_info->data->user_email, 20);
                $output .= "<span>";
                $output .= $user_info->data->display_name;
                
                if ($s['content'] === null) {
                    $small_text = "+ прогресс";
                } else {
                    $comments_count = wp_count_comments($s['post_id']);
                    $approved = $comments_count->approved;
                    
                    $small_text = "+ комментарий";
                }
                
                $output .= "</span></a><small>" . $small_text . "</small></div>";
                $output .= "<div class='inline comment-content'>";
                $output .= "<div class='comment-body'>";
                if ($s['content'] != null) {
                    $output .= excerp_comment(get_comment($s['id'])->comment_content, 67);
                    $output .= "<a class='link-style-1' href='"
                        . esc_url(get_comment_link($s['id'])) . "'>&nbsp;#</a><br>";
                    $output .= sprintf(_x('%1$s', 'widgets'), ' <a class="link-style-1" href="'
                        . esc_url(get_permalink($s['post_id'])) . '"> '
                        . get_the_title($s['post_id']) . '</a>');
                } else {
                    $output .= sprintf(_x('%1$s', 'widgets'), ' <a class="link-style-1" href="'
                        . esc_url(get_permalink($s['post_id'])) . '"> '
                        . get_the_title($s['post_id']) . '</a>');
                }
                $output .= "<span></span></div>";
                $output .= "</div>";
                $output .= "</li>";
            }
        }
        $output .= '</ul>';
        ##end stream
        $output .= $args['after_widget'];
        echo $output;
        
        if (!$this->is_preview()) {
            $cache[$args['widget_id']] = $output;
            wp_cache_set('widget_recent_comments', $cache, 'widget');
        }
    }
    
    /**
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = absint($new_instance['number']);
        $this->flush_widget_cache();
        
        $alloptions = wp_cache_get('alloptions', 'options');
        if (isset($alloptions['widget_recent_comments'])) {
            delete_option('widget_recent_comments');
        }
        
        return $instance;
    }
    
    /**
     * @param array $instance
     */
    public function form($instance)
    {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
        </p>
        
        <p><label
                for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of comments to show:'); ?></label>
            <input id="<?php echo $this->get_field_id('number'); ?>"
                   name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>"
                   size="3"/></p>
        <?php
    }
}

function get_my_comments()
{
    global $view_path;
    
    $user = wp_get_current_user();
    $args = array(
        'author__in' => $user->id,
    );
    $user_comments = get_comments($args);
    
    if (file_exists($view_path . "my_comments_page.php")) {
        require_once($view_path . "my_comments_page.php");
    }
}

// (21) Редактирование виджета "Свежие комментарии" end

// (12 глобальный) Подключения JS файлов и файлов стилизации
add_action('wp_enqueue_scripts', 'my_scripts_method');
function my_scripts_method()
{
    wp_register_script('diductio-script', get_stylesheet_directory_uri() . "/js/javascripts.js");
    // (13 глобальный) Опции JS по умолчанию
    $didaction_object = array(
        'child_theme_url' => get_stylesheet_directory_uri(),
        'ajax_path' => admin_url('admin-ajax.php'),
    );
    
    wp_localize_script('diductio-script', 'diductioObject', $didaction_object);
    
    
    wp_enqueue_script('diductio-script');
    
    // (14) Подключение bootstrap
    wp_register_script('diductio-bootstrap-js',
        "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js");
    wp_enqueue_script('diductio-bootstrap-js');
    
    // (14) Подключение bootstrap end
    
}

// (15) Вывод прогресса в "Мои курсы"
function diductio_add_progress($post_id, $uid = false, $render = true)
{
    global $wpdb;
    
    if (!$uid) {
        $user_id = get_current_user_id();
    } else {
        $user_id = (int)$uid;
    }
    $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
    $sql = "SELECT * FROM `$table_name` WHERE `user_id` = '{$user_id}' ";
    $sql .= "AND `post_id` = '{$post_id}'";
    $progress = $wpdb->get_row($sql);
    if ($progress) {
        if ($progress->checked_lessons != "0") {
            $checked_count = count(explode(',', $progress->checked_lessons));
            $percent = round((100 * $checked_count) / $progress->lessons_count, 2);
        } else {
            $percent = 0;
        }
        
        $progressbar_class = "";
        if ($percent == 100) {
            $progressbar_class = "progress-bar-success";
        }
        
        $progress_html
            = "<div class='progress'>
				  				<div class='progress-bar {$progressbar_class}' role='progressbar' aria-valuenow='{$percent}' aria-valuemin='0' aria-valuemax='100' style='width:{$percent}%;'>
					    		{$percent} %
					 			</div>
							</div>";
    } else {
        $progress_html
            = "<div class='progress'>
				  				<div class='progress-bar' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width:0%;'>
					    		0%
					 			</div>
							</div>";
    }
    if ($render) {
        echo $progress_html;
    } else {
        return $progress_html;
    }
}

// (16) Добавление хвоста в URL для правильного открытия нужного место в уроке
function get_first_unchecked_lesson($post_id)
{
    global $wpdb;
    
    $user_id = get_current_user_id();
    if (!$user_id) {
        return false;
    }
    $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
    $sql = "SELECT * FROM `$table_name` WHERE `user_id` = '{$user_id}' ";
    $sql .= "AND `post_id` = '{$post_id}'";
    $progress = $wpdb->get_row($sql);
    if ($progress) {
        
        $all_lessons = range(1, $progress->lessons_count);
        $lessons_checked = explode(',', $progress->checked_lessons);
        
        if ($all_lessons && count($all_lessons) > 1) {
            $unchecked_array = array_diff($all_lessons, $lessons_checked);
            if (!empty($unchecked_array)) {
                $first_unchecked = min($unchecked_array);
                if ($first_unchecked) {
                    return "#lesson-" . $first_unchecked;
                }
            }
            
        }
    }
}

// (20) Стилизация авторизации, регистрации, восстановления пароля, выхода
function custom_logo()
{
    echo '
   <style type="text/css">
        #login h1 a { background: url(' . get_stylesheet_directory_uri() . '/images/logo.png) no-repeat 0 0 !important; width:320px; height: 123px;    box-shadow: 0 1px 3px rgba(0,0,0,.13); }
    </style>';
}

add_action('login_head', 'custom_logo');

/* Ставим ссылку с логотипа на сайт, а не на wordpress.org */
add_filter('login_headerurl', create_function('', 'return get_home_url();'));

/* убираем title в логотипе "сайт работает на wordpress" */
add_filter('login_headertitle', create_function('', 'return false;'));
add_filter('is_protected_meta', 'my_is_protected_meta_filter', 10, 2);
// (20) Стилизация авторизации, регистрации, восстановления пароля, выхода end


// (25) Стилизация "Читать далее"
add_action('the_content_more_link', 'read_more_customize', 10, 2);
function read_more_customize($link, $text)
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
    
    $title = str_replace(array('Рубрика:', 'Метка:'), '', $title);
    
    return $title;
});
// (26) Удаление "Рубрика", "Метка" из лент рубрик и источников end

// (27) Редирект на главную после после авторизации
function login_redirect($redirect_to, $request, $user)
{
    return home_url();
}

add_filter('login_redirect', 'login_redirect', 10, 3);
// (27) Редирект на главную после после авторизации end

// (28) Модификация личного кабинета
function remove_menus()
{
    global $current_user;
    wp_get_current_user();
    
    if (user_can($current_user, "subscriber")) {
        remove_menu_page('index.php');
    }
    remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');
    
}

add_action('admin_menu', 'remove_menus');
function init_function()
{
    global $current_user;
    wp_get_current_user();
    if (user_can($current_user, "subscriber")) {
        update_user_meta($current_user->ID, 'show_admin_bar_front', 'false');
    }
    add_post_type_support('post', 'custom-fields');
    
    // add suggest user support
    $suggestUser = new Did_SuggestUser();
}

add_action('init', 'init_function');
// (28) Модификация личного кабинета end

// (33c) Модификация внешнего вида комментариев
function diductio_comments($comment, $args, $depth) {
$GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?> class="comment" id="li-comment-<?php comment_ID() ?>">
    <div class="comment-body" id="comment-<?php comment_ID(); ?>">
        <?php comment_text() ?>
        <div class="col-md-12 col-sm-12 col-xs-12 comment-meta-container">
            <div class="comment-meta" id="reply-link">
                <?php if (is_user_logged_in()): ?>
                    <?php comment_reply_link(array_merge($args,
                        array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                <?php endif; ?>
            </div>
            <div class="comment-meta comment-author">
                <?php
                // $user_name_str = substr(get_comment_author(),0, 20);
                $user_info = get_user_by('email', $comment->comment_author_email);
                $user_link = get_site_url() . "/people/" . $user_info->data->user_nicename;
                echo "<a href='{$user_link}'>";
                printf(__('<div class="inline"><b>%s</b></div>'), $user_info->data->display_name);
                printf(__('<div class="inline">%s</div>'), get_avatar($user_info->data->user_email));
                echo "</a>";
                ?>
            </div>
            <div class="comment-meta comment-date">
                <?php printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time()) ?>
            </div>
            <div class="comment-meta comment-edit">
                <?php edit_comment_link(__('Edit'), '&nbsp; ', ''); ?>
            </div>
        </div>
    </div>
    <?php
    }
    
    function myclass_edit_comment_link($output)
    {
        $myclass = 'link-style-2';
        
        return preg_replace('/comment-edit-link/', 'comment-edit-link ' . $myclass, $output, 1);
    }
    
    add_filter('edit_comment_link', 'myclass_edit_comment_link');
    
    add_filter('comment_form_defaults', 'sp_comment_form_defaults');
    function sp_comment_form_defaults($defaults)
    {
        
        $defaults['title_reply'] = "";
        $defaults['comment_field']
            = '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="10" aria-required="true"></textarea></p>';
        
        return $defaults;
        
    }
    
    // (33c) Модификация внешнего вида комментариев end
    
    // (38) Работа с массивами текущих и пройденных
    function array_complite($post_id)
    {
        global $view_path, $wpdb;
        
        $user_id = get_current_user_id();
        $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
        $sql = "SELECT * FROM `$table_name` WHERE `user_id` = '{$user_id}' ";
        $sql .= "AND `post_id` = '{$post_id}'";
        $progress = $wpdb->get_results($sql);
        if (!empty($progress)) {
            if ($progress[0]->checked_lessons != 0) {
                $checked_lessons = count(explode(',', $progress[0]->checked_lessons));
            } else {
                $checked_lessons = 0;
            }
            $lessons_count = $progress[0]->lessons_count;
            
            if ($checked_lessons == $lessons_count) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    function get_courses($is_complite = true)
    {
        global $view_path, $wpdb;
        
        $user_id = get_current_user_id();
        $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
        $courses_array = array();
        
        
        $sql = "SELECT DISTINCT(`post_id`) FROM `$table_name`";
        $progress = $wpdb->get_results($sql);
        
        foreach ($progress as $course_key => $course_value) {
            $complite_count = 0;
            $in_progress_count = 0;
            $post_id = $course_value->post_id;
            $post_info = get_post($post_id);
            
            $sql = "SELECT  *  FROM `$table_name` WHERE ";
            $sql .= "`post_id` = '{$post_id}'";
            $post_progress_info = $wpdb->get_results($sql);
            
            foreach ($post_progress_info as $post_progress_key => $post_progress_value) {
                if ($post_progress_value->checked_lessons != 0) {
                    $post_checked_lessons = count(explode(',', $post_progress_value->checked_lessons));
                } else {
                    $post_checked_lessons = 0;
                }
                $post_lessons_count = $post_progress_value->lessons_count;
                if ($post_checked_lessons == $post_lessons_count) {
                    $complite_count++;
                } else {
                    $in_progress_count++;
                }
            }
            //print_r($post_info -> complite_count);
            if ($post_info != null) {
                $post_info->complite_count = $complite_count;
                $post_info->in_progress_count = $in_progress_count;
                $courses_array[$post_id] = $post_info;
            }
        }
        if ($is_complite) {
            usort($courses_array, function ($a, $b) {
                return $b->complite_count - $a->complite_count;
            });
        } else {
            usort($courses_array, function ($a, $b) {
                return $b->in_progress_count - $a->in_progress_count;
            });
        }
        foreach ($courses_array as $key => $value) {
            if ($is_complite && $value->complite_count == 0) {
                unset($courses_array[$key]);
            }
            if (!$is_complite && $value->in_progress_count == 0) {
                unset($courses_array[$key]);
            }
        }
        
        return $courses_array;
    }
    
    // (38) Работа с массивами текущих и пройденных end
    
    // (36) Изменение шаблонов
    add_filter('template_include', 'my_template');
    function my_template($template)
    {
        
        // если это страница со слагом projjdennye-massivy, используем файл шаблона page-arrays.php
        // используем условный тег is_page()
        
        if (is_page('array-recently') || is_page('array-active')) {
            if ($new_template = locate_template(array('page-arrays.php'))) {
                return $new_template;
            }
        } elseif (is_page('source')) {
            // если это страница со слагом istochniki(страница источников), используем файл шаблона page-istochiki
            // используем условный тег is_page()
            if ($new_template = locate_template(array('page-istochniki.php'))) {
                return $new_template;
            }
        } else {
            return $template;
        }
    }
    
    // (36) Изменение шаблонова end
    
    // (45) Стилизация количества записей в виджете категорий
    function categories_postcount_filter($variable)
    {
        $variable = str_replace('(', '<span class="label label-success right-count">', $variable);
        $variable = str_replace(')', '</span>', $variable);
        
        return $variable;
    }
    
    add_filter('wp_list_categories', 'categories_postcount_filter');
    // (45) Стилизация количества записей в виджете категорий end
    
    // (47) Связывание добавление и удаление в избранное с логикой зачётки
    // add_action( 'post_updated', 'post_update_method', 10, 3 );
    function post_update_method($post_ID, $post_after, $post_before)
    {
        //method depricated - I've moved it to the post->onPostUpdate;
        global $wpdb;
        
        $words_array = str_word_count($post_after->post_content, 1);
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
        $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
        $sql = "UPDATE {$table_name} SET `lessons_count` = {$accordion_count} ";
        $sql .= " WHERE `post_id` = {$post_ID}";
        $wpdb->query($sql);
    }
    
    function remove_br_accordion($content)
    {
        $array = array(
            '<p>[' => '[',
            ']</p>' => ']',
            ']<br />' => ']',
            ']<br>' => ']',
        );
        $content = strtr($content, $array);
        
        return $content;
    }
    
    add_filter('the_content', 'remove_br_accordion');
    //        add_action('save_post', 'add_post_to_statistic', 10, 3);
    
    function add_post_to_statistic($post_id, $user_id = false)
    {
        global $current_user, $wpdb;
        
        $user_id = $user_id ? $user_id : $current_user->ID;
        
        $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
        $wpdb->insert(
            $table_name,
            array(
                'user_id' => $user_id,
                'post_id' => $post_id,
                'update_at' => "NOW()",
                'lessons_count' => 1,
                'checked_lessons' => 0,
            ),
            array(
                '%d',
                '%d',
                '%s',
                '%d',
                '%s',
            )
        );
    }
    
    add_action('wpfp_after_remove', 'remove_post_from_statistic');
    function remove_post_from_statistic($post_id)
    {
        global $current_user, $wpdb;
        $user_id = $current_user->ID;
        $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
        
        $wpdb->delete($table_name, array('user_id' => $user_id, 'post_id' => $post_id));
    }
    
    // (47) Связывание добавление и удаление в избранное с логикой зачётки end
    
    
    //удаление поста из статистической таблицы пользователей
    add_action('before_delete_post', 'course_removed');
    function course_removed($postid)
    {
        global $current_user, $wpdb;
        
        $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
        $sql = "DELETE FROM `wp_user_add_info` WHERE `post_id` = {$postid}";
        $wpdb->query($sql);
    }
    
    add_filter('embed_defaults', 'bigger_embed_size');
    
    function bigger_embed_size()
    {
        if (wp_is_mobile()) {
            return array('width' => 780, 'height' => 200);
        } else {
            return array('width' => 780, 'height' => 430);
        }
    }
    
    function draw_user_progress($id)
    {
        global $current_user, $wpdb;
        
        $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
        $sql = "SELECT * FROM `$table_name` WHERE `user_id` = {$id}";
        $progress = $wpdb->get_results($sql);
        $user_id = $id;
        foreach ($progress as $key => $value) {
            $post_id = $value->post_id;
            $html = diductio_add_progress($post_id, $user_id);
        }
    }
    
    function excerp_comment($text, $size = 23)
    {
        
        $comment_excerp_size = $size; //configuration;
        
        $excerpt = strip_shortcodes($text);
        $excerpt = strip_tags($excerpt);
        
        $str_lenght = strlen($excerpt);
        if ($str_lenght < $comment_excerp_size) {
            $the_str = $excerpt;
        } else {
            mb_internal_encoding("UTF-8");
            $the_str = mb_substr($excerpt, 0, $comment_excerp_size) . "…";
        }
        
        return $the_str;
    }
    
    function my_is_protected_meta_filter($protected, $meta_key)
    {
        if ($meta_key == 'old_id' || $meta_key == 'wpfp_favorites') {
            return true;
        } else {
            return $protected;
        }
    }
    
    function my_tweaked_admin_bar()
    {
        global $wp_admin_bar;
        
        // print_r($wp_admin_bar);
    }
    
    add_action('wp_before_admin_bar_render', 'my_tweaked_admin_bar');
    
    add_filter('clean_url', 'js_front_end_defer', 11, 1);
    function js_front_end_defer($url)
    {
        if (false === strpos($url, '.js')) {
            return $url;
            
        }
        
        return "$url' defer='defer";
    }
    
    function get_user_work_times($uid = 0)
    {
        global $current_user, $wpdb;
        if ($uid === 0) {
            $uid = $current_user->ID;
        }
        $table_name = $wpdb->get_blog_prefix() . 'user_add_info';
        $sql = "SELECT * FROM `$table_name` WHERE `user_id`=$uid";
        $progress = $wpdb->get_results($sql);
        $wts = array(
            'all' => 0,
            'complete' => 0,
            'nocomplete' => 0,
        );
        
        foreach ($progress as $k => $v) {
            $wt = (int)get_post_meta($v->post_id, 'work_time', true);
            $wts['all'] += $wt;
            if ($wt != 0 and $v->checked_lessons != '0') {
                $cof = count(explode(',', $v->checked_lessons)) / $v->lessons_count;
                $wts['complete'] += floor($wt * $cof);
            }
        }
        $wts['nocomplete'] = $wts['all'] - $wts['complete'];
        
        return $wts;
    }
    
    // Helper functions starts here, other will removed into classes
    /**
     * Include view of the diductio
     *
     * @param string $name - Name of the View
     * @param mixed  $data - Delegated data to the View
     * @return mixed - included page content
     */
    function view($name, $data)
    {
        $name = str_replace('.', DIRECTORY_SEPARATOR, $name);
        extract($data);
        return require "view/{$name}.php";
    }
    
    spl_autoload_register(
        function ($class) {
            
            // Don't interfere with other autoloaders
            if (0 !== strpos($class, 'Did_')) {
                return;
            }
            
            $path = __DIR__ . '/' . 'classes' . '/' . str_replace('Did_', '', $class) . '.class' . '.php';
            if (!file_exists($path)) {
                return;
            }
            
            require $path;
        }
    );
    
    ?>
