<?php 
/*
 * Template Name: Моё 
 * Данный шаблон выводит все посты из категорий и меток на которые подписан  пользователь
*/
get_header();

$args = array();
$args['tax_query'] = array( 'relation' => 'OR' );
$id = get_current_user_id();
$tag_list = get_user_meta($id, 'signed_tags')[0];
$category_list = get_user_meta($id, 'signed_categories')[0];
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
if($category_list) {
	$args['category__in'] = $category_list; 
	$args['posts_per_page'] = get_option( 'posts_per_page' ); 
	$args['paged'] = $paged; 

}

if($tag_list) {
	$args['tag__in'] = $tag_list; 
}

query_posts($args);

?>


	<div id="primary" class="content-area">
		<div id="statistic" class="hentry">
			<div class="public_statistic row">
				<div class="stat-col">
					<a href="<?=get_site_url();?>">
						<span class="label label-success">Массивы</span>
						<span class="label label-success"><?=$st->get_all_arrays();?></span>
					</a>
				</div>
				<div class="stat-col">
					<a href="/array-active">
						<span class="label label-success label-soft">Проходят</span>
						<span class="label label-success"><?=$st->active;?></span>
					</a>
				</div>
				<div class="stat-col">
					<a href="/array-recently">
						<span class="label label-success label-soft">Прошли</span>
						<span class="label label-success"><?=$st->done;?></span>
					</a>
				</div>
				<div class="stat-col">
				   <a href="/source">
					<span class="label label-success label-soft">Источники</span>
					<span class="label label-success"><?=$st->get_istochiki_count();?></span>
				   </a>
				</div>
				<div class="stat-col">
				   <a href="/people">
					<span class="label label-important-soft">Люди</span>
					<span class="label label-important"><?=$st->get_all_users();?></span>
				   </a>
				</div>
				<!--<div class="stat-col">
				   <a href="/category/poll">
					<span class="label label-orange">+</span>
				   </a>
				</div>-->
			</div>
		</div>
		<main id="main" class="site-main homepage-main" role="main">
		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
			<?php endif; ?>

			<?php
			// Start the loop.
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'content', get_post_format() );

			// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( '->', 'diductio' ),
				'next_text'          => __( '<-', 'diductio' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'diductio' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
