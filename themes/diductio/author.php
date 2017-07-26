<?php
/**
 * Шаблон отвечает за вывод публичной статистики пользователя, типа: http://diductio.ru/people/{username}
 *
 * @link       https://codex.wordpress.org/Template_Hierarchy
 * @package    WordPress
 * @subpackage Twenty_Fifteen
 * @since      Twenty Fifteen 1.0
 */
get_header();
global $wpdb, $st, $dPost;
$cat_id = get_query_var('cat');
$author = get_user_by('slug', get_query_var('author_name'));
$user_id = $author->ID;
$user_statistic = $st->get_user_info($user_id);
$will_busy_days = $user_statistic['countdown_days'] ? $st::ru_months_days($user_statistic['countdown_days']) : 0;
$user_statistic['author'] = Did_User::getAllMyPosts($user_id);
$user_info = $author_info = get_userdata($user_id);
$author_info->inner_passing_rating = Did_Statistic::getSummOfTheInnerRatingByUser($user_id);
$favorite_post_ids = $st->get_knowledges($user_id);
$post_per_page = wpfp_get_option("post_per_page");
$page = intval(get_query_var('paged'));

if($favorite_post_ids) {
    $qry = array(
        'post__in' => $favorite_post_ids,
        'posts_per_page' => $post_per_page,
        'orderby' => 'post__in',
        'paged' => $page,
    );
    query_posts($qry);
}

//Get categories information by user
$category_statistic = $tag_statistic = array();
$Did_Categories = new Did_Categories();
$category_statistic = $Did_Categories->fetchCategoriesByUser($user_id)->orderBy('value', 'desc')->get(3);
$tag_statistic = $Did_Categories->fetchTagsByUser($user_id)->orderBy('value', 'desc')->max();
?>

<section id="primary" class="content-area">
    <?php do_action('author-page-header'); ?>
    <main id="main" class="site-main" role="main">
        <header class="page-header" id="author-page">
            <?php view('cabinet', compact('user_statistic','category_statistic', 'author_info', 'tag_statistic', 'user_id', 'dPost', 'favorite_post_ids','will_busy_days')); ?>
        </header><!-- .page-header -->
    </main><!-- .site-main -->
</section><!-- .content-area -->

<?php get_footer(); ?>
