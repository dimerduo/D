<?php
/**
 * Template Name: Шаблон страницы Массивы
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
		// Start the loop.
		if(is_page('projjdennye-massivy')) {
			$active_flag = true;
			$courses = get_courses();
		} elseif ('aktivnye-massivy') { 
			$courses = get_courses(false);
			$active_flag = false;
		} 
		foreach($courses as $post)
		{
			if(is_page('projjdennye-massivy') && $post->complite_count == 0) {
				continue;
			}

			if(is_page('aktivnye-massivy') && $post->in_progress_count == 0) {
				continue;
			}

			global $post;
		    setup_postdata($post);
			get_template_part( 'content', 'array' );
		}
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
