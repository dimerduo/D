<?php
/**
 * Template Name: Шаблон страницы "Источники"
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
		$args = array(
				'orderby'=> 'modified',
				'order' => 'DESC'
		);
		$tags = get_tags($args);

		foreach($tags as $tag)
		{
			get_template_part( 'content', 'istochniki' );
		}
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
