<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="statistic" class="hentry">
			<div class="public_statistic row">
				<div class="stat-col">
					<span class="label label-success label-soft">Массивы</span>
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
					<span class="label label-success label-soft">Активных</span>
					<span class="label label-success"><?=$st->get_all_users();?></span>
				</div>
				<div class="stat-col">
					<span class="label label-success label-soft">Сейчас учатся</span>
					<span class="label label-success"><?=$st->active_studies_users;?></span>
				</div>
				<div class="stat-col">
					<span class="label label-success label-soft">Недавно закончили</span>
					<span class="label label-success"><?=$st->finished_study_users;?></span>
				</div>
				<div class="stat-col">
					<span class="label label-success label-soft">Всего</span>
					<span class="label label-success">523К</span>
				</div>
			</div>
			<div class="public_statistic row precent-row">
				<div class="stat-col">
					<span class="label label-important label-important-soft">Общий уровень </span>
					<span class="label label-important"><?=$st->get_rating();?> %</span>
				</div>
				<div class="stat-col">
					<?php if ( is_user_logged_in() ): ?>
						<span class="label label-important label-important-soft">Ваш уровень</span>
						<span class="label label-important"><?=$st->get_rating('local');?> %</span>
					<?php else: ?>
						<span><span class="label label-important label-important-soft">Ваш уровень </span> <a href="<?php get_home_url(); ?>/wp-login.php" class="more-link link-style-1"><span class="label label-important" style="text-decoration:underline;">?</span></a></span>
					<?php endif;?>
				</div>
				<div class="stat-col">
					<?php if ( is_user_logged_in() ): ?>
						<span class="label label-important label-important-soft">Ваш прогресс</span>
						<span class="label label-important"><?=$st->get_div_studying_progress();?> %</span>
					<?php else: ?>
						<span><span class="label label-important label-important-soft">Ваш прогресс </span> <a href="<?php get_home_url(); ?>/wp-login.php" class="more-link link-style-1"><span class="label label-important" style="text-decoration:underline;">?</span></a></span>
					<?php endif;?>
				</div>
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
				'prev_text'          => __( '->', 'twentyfifteen' ),
				'next_text'          => __( '<-', 'twentyfifteen' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
