<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
	get_header(); 
    global $wpdb, $st;
	$cat_id =get_query_var('cat') ; 
	$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
	$author_info = get_userdata($author->ID);
	$favorite_post_ids = get_user_meta($author->ID, WPFP_META_KEY, true);

	if($favorite_post_ids) {
		$favorite_post_ids = array_reverse($favorite_post_ids);
	}
	
	$post_per_page = wpfp_get_option("post_per_page");
	$page = intval(get_query_var('paged'));
	$qry = array('post__in' => $favorite_post_ids, 'posts_per_page'=> $post_per_page, 'orderby' => 'post__in', 'paged' => $page);
    query_posts($qry);
	$user_id = $author->ID;
	$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
	$curses_count = 0;
	$user_statistic = $st->get_user_info($user_id);
?>

	<section id="primary" class="content-area">
	  <div id="statistic" class="hentry">
		<div class="public_statistic row precent-row">
			<div class="stat-col" style="margin-right: 11px;">
					<span class="label label-success label-soft">Активных</span>
					<span class="label label-success"><?=$user_statistic['in_progress'];?></span>
			</div>
			<div class="stat-col" style="margin-right: 11px;">
					<span class="label label-success label-soft">Пройденных</span>
					<span class="label label-success"><?=$user_statistic['done'];?></span>
			</div>

			<?php $wts = get_user_work_times($user_id);?>
				<div class="stat-col" style="margin-right: 11px;">
					<span class="label label-important label-important-soft">Требуется</span>
					<span class="label label-important">
						<?php print_r(floor($wts['nocomplete']/60))?>ч : 
						<?php print_r($wts['nocomplete']%60)?>м
					</span>
				</div>

				<div class="stat-col" style="margin-right: 11px;">
					<span class="label label-important label-important-soft">Пройденно</span>
					<span class="label label-important">
						<?php print_r(floor($wts['complete']/60))?>ч : 
						<?php print_r($wts['complete'] %60)?>м
					</span>
				</div>

			<?php /*
			<div class="stat-col" style="margin-right: 11px;">
					<span class="label label-important label-important-soft">Уровень</span>
					<span class="label label-important">< ?=$st->get_rating('local',$user_id);? > %</span>
					</div>*/ ?>
			<div class="stat-col" style="margin-right: 11px;">
					<span class="label label-important label-important-soft">Прогресс</span>
					<span class="label label-important">
					<?=0 //$st->get_div_studying_progress($user_id);?> 
					%</span>
			</div>
			<?php
				if (function_exists('getSubsriberView')) {
					echo getSubsriberView('author'); 
				}
			?>
		</div> 
	</div>
		<main id="main" class="site-main" role="main">
			<header class="page-header" id="author-page">
				<div  class="avatar inline "><?=get_avatar( $author_info->user_email, 96 );?></div>		
				<div class="inline" style="margin-left:20px;"> 
					<h1 class="entry-title"><?php print_r($author_info->data->display_name); ?></h1>
					<div class="about"><?=get_user_meta($author_info->ID,'description')[0];?></div>
				</div>
				<div class="wpfp-span">
					<?php 

				        echo "<ul>";
				        while ( have_posts() ) : the_post();
				        $my_array_post_id = get_the_ID();
				        $sql  = "SELECT * FROM `$table_name` WHERE `user_id` = '{$user_id}' ";
				        $sql .= "AND `post_id` = '{$my_array_post_id}'";
				        $progress = $wpdb->get_row($sql);
				        $lessons_count = $progress -> lessons_count;
				        if($progress->checked_lessons != 0) 
				        {
				            $checked_lessons = explode(',', $progress->checked_lessons);
				            $checked_lessons_count = count($checked_lessons);
				        } else {
				            $checked_lessons_count = 0;
				        }
				        //проверка если курс пройден не полностью, то показывать его на странице 
				        // "Мои массивы", в противном случае, курс будет показан на странице "Моя зачётка"
						if($lessons_count != $checked_lessons_count ) {
				            echo "<li><a href='".get_permalink(). get_first_unchecked_lesson(get_the_ID()) ."' title='". get_the_title() ."'>" . get_the_title() ."</a> ";
				                diductio_add_progress(get_the_ID(),$user_id);            
				            echo "</li>";
				            $curses_count ++;
				        }
				        endwhile;
				        if($curses_count == 0) {
				            echo "<li>Нету активных массивов</li>";
				        }
				        echo "</ul>";
					?>
					<?php  moya_zachetka();?>
				</div>
			</header><!-- .page-header -->
		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>
