<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
$post_statistic = $st->get_course_info($post->ID);
$active_users = $post_statistic['active_users'];
$done_users = $post_statistic['done_users'];
if ($active_users) {
	$act_args = array(
		'role__in' => ['subscriber','administrator']
	);
	$act_args['include'] = $active_users;
	$active_users_array = new WP_User_Query( $act_args );
} 
if ($done_users) {
	$done_args = array(
		'role__in' => ['subscriber','administrator']
	);
	$done_args['include'] = $done_users;
	$done_users_array = new WP_User_Query( $done_args );	
}

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="statistic" class="hentry">
			<?php if($post_statistic['in_progress'] > 0 ): ?>
					<div class="stat-col">
						<span class="label label-success label-soft">Массив проходят</span>
						<span class="label label-success"><?=$post_statistic['in_progress'];?></span>
						<div class="inline profile_avatars">
							<?php 
								// User Loop
								if ( ! empty( $active_users_array->results ) ) {
									foreach ( $active_users_array->results as $user ) {
										$user_link = get_site_url() . "/people/" . $user->data->user_nicename;

										printf("<div class='inline'><a href='%s'>%s</a></div>",$user_link, get_avatar($user->data->user_email, 24 ));
									}
								} else {
									echo 'No users found.';
								}
							?>
						</div>
					</div>
			<?php endif; ?>
			<?php if($post_statistic['done'] > 0 ): ?>
					<div class="stat-col">
						<span class="label label-success label-soft">Недавно прошли</span>
						<span class="label label-success"><?=$post_statistic['done'];?></span>
						<div class="inline profile_avatars">
							<?php 
								// User Loop
								if ( ! empty( $done_users_array->results ) ) {
									foreach ( $done_users_array->results as $user ) {
										$user_link = get_site_url() . "/people/" . $user->data->user_nicename;

										printf("<div class='inline'><a href='%s'>%s</a></div>",$user_link, get_avatar($user->data->user_email, 24 ));
									}
								} else {
									echo 'No users found.';
								}
							?>
						</div>
					</div>
			<?php endif; ?>
			<?php if($post_statistic['les_count']): ?>
					<div class="stat-col">
						<span class="label label-grey-soft">Частей</span>
						<span class="label label-grey"><?=$post_statistic['les_count'];?></span>
					</div>
			<?php endif; ?>
			<?php $approved = wp_count_comments( $post->ID )->approved;
				if($approved > 0 ): ?>
					<div class="stat-col">
						<span class="label label-success label-soft">Обсуждение</span>
						<span class="label label-success"> <?=$approved; ?> </span>
					</div>
			<?php endif; ?>
					<div class="stat-col">
						<!-- (4) Вставка добавления избранного в начало записи -->
							<?php 
								if ( is_user_logged_in() && is_single() ) {
									echo '<div class="add-to-favor-wrapper">';
									 if(array_complite($post->ID)) {
										echo "<span class='label label-success'>Массив успешно пройден</span>";
									 } else {
										if (function_exists('wpfp_link')) { wpfp_link(); } 
									 }
									echo "</div>";
								}  
							?>
						<!-- (4) Вставка добавления избранного в начало записи end -->

					</div>
		</div>
		<main id="main" class="site-main" role="main">

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			/*
			 * Include the post format-specific template for the content. If you want to
			 * use this in a child theme, then include a file called called content-___.php
			 * (where ___ is the post format) and that will be used instead.
			 */
			get_template_part( 'content', get_post_format() );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template('/comments-short.php');
			endif;

			// Previous/next post navigation.
			the_post_navigation( array(
				'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'twentyfifteen' ) . '</span> ' .
					'<span class="screen-reader-text">' . __( 'Next post:', 'twentyfifteen' ) . '</span> ' .
					'<span class="post-title">%title</span>',
				'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'twentyfifteen' ) . '</span> ' .
					'<span class="screen-reader-text">' . __( 'Previous post:', 'twentyfifteen' ) . '</span> ' .
					'<span class="post-title">%title</span>',
			) );

		// End the loop.
		endwhile;
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
