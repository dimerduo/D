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
$is_empty = true;
$data->class = "";
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
query_posts($args);
$data->number_of_posts=getMyPostCount();
?>


	<div id="primary" class="content-area">
		<?php do_action('subscribtion-index'); ?>
		<main id="main" class="site-main homepage-main" role="main">
		<?php if ( have_posts() && !$is_empty ) : ?>

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
		else: ?>
			<section class="no-results not-found">
				<header class="page-header">
					<p>Ваша лента пуста</p>
				</header><!-- .page-header -->
			</section><!-- .no-results -->
		<?php endif; ?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
