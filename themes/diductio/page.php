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
	?>
	<div id="statistic" class="hentry">
		<div class="public_statistic row precent-row">
			<div class="stat-col" style="margin-right: 11px;">
					<span class="label label-success label-soft">Активных</span>
					<span class="label label-success"><?=$user_statistic['in_progress'];?></span>
			</div>
			<div class="stat-col" style="margin-right: 11px;">
					<span class="label label-success label-soft">Пройденныхu</span>
					<span class="label label-success"><?=$user_statistic['done'];?></span>
			</div>
			<?php if ( is_user_logged_in() ): ?>
				<?php $wts = get_user_work_times();?>
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
			<?php endif;?>


			<div class="stat-col" style="margin-right: 11px;">
				<?php if ( is_user_logged_in() ): ?>
					<span class="label label-important label-important-soft">Мой прогресс</span>
					<span class="label label-important"><?=$st->get_div_studying_progress();?> %</span>
				<?php else: ?>
					<span><span class="label label-important label-important-soft">Ваш прогресс </span> <a href="<?php get_home_url(); ?>/wp-login.php" class="more-link link-style-1"><span class="label label-important" style="text-decoration:underline;">?</span></a></span>
				<?php endif;?>
			</div>
		</div> 
	</div>
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
