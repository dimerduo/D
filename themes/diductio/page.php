<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
	<?php if(is_page('dlya-chego-ehto-nuzhno')): ?>
	<!-- <div id="statistic" class="hentry">
			<div class="public_statistic row">
				<div class="stat-col">
					<span class="label label-success label-soft">Массивы знаний</span>
					<span class="label label-success"><?=$st->get_all_arrays();?></span>
				</div>
				<div class="stat-col">
					<span class="label label-success label-soft">Сейчас проходят</span>
					<span class="label label-success"><?=$st->active;?></span>
				</div>
				<div class="stat-col">
					<span class="label label-success label-soft">Недавно прошли</span>
					<span class="label label-success"><?=$st->done;?></span>
				</div>
				<div class="stat-col">
					<span class="label label-success label-soft">Источники</span>
					<span class="label label-success"><?=$st->get_istochiki_count();?></span>
				</div>
			</div>
			<div class="public_statistic row precent-row">
				<div class="stat-col">
					<span class="label label-success label-soft">Люди</span>
					<span class="label label-success"><?=$st->get_all_users();?></span>
				</div>
				<div class="stat-col">
					<span class="label label-success label-soft">Сейчас проходят</span>
					<span class="label label-success"><?=$st->active_studies_users;?></span>
				</div>
				<div class="stat-col">
					<span class="label label-success label-soft">Недавно прошли</span>
					<span class="label label-success"><?=$st->finished_study_users;?></span>
				</div>
				<div class="stat-col">
					<span class="label label-important-soft">Общий уровень</span>
					<span class="label label-important"><?=$st->get_rating();?> %</span>
				</div>
			</div>
		</div> -->
	<?php elseif(is_page('progress')):
		$user_statistic = $st->get_user_info();
		do_action('progress-page-header');
	?>
	<?php endif; ?>
		<main id="main" class="site-main" role="main">

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the page content template.
			get_template_part( 'content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		// End the loop.
		endwhile;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
