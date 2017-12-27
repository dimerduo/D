<?php
$post_statistic = $st->get_course_info($post->ID);
$post_statistic['total_progress'] = Did_Posts::getAllUsersProgress($post->ID);
$post_statistic['overdue_users'] = count(Did_Posts::getOverDueUsers($post->ID));
$active_users = $post_statistic['active_users'];
$done_users = $post_statistic['done_users'];
if ($active_users) {
    $act_args = array(
        'role__in' => $roles,
    );
    $act_args['include'] = $active_users;
    $active_users_array = new WP_User_Query($act_args);
}
if ($done_users) {
    $done_args = array(
        'role__in' => $roles,
    );
    $done_args['include'] = $done_users;
    $done_users_array = new WP_User_Query($done_args);
}

// suggest users
$suggestUser = new Did_SuggestUser();
if (is_user_logged_in()) {
    $suggesting_users = $suggestUser->getSuggestingUsers(get_current_user_id(), $post->ID);
}
?>

<div class="article_header">
	<div class="article_header-img col-md-3 col-xs-12" style="background-image:url('<?php echo get_the_post_thumbnail_url(); ?>')"></div>
	
	<div class="article_header-infoWrp col-md-9 col-xs-12 format-<?php echo get_post_format();?>">
		<div class="article_header-titleWrp">
			<header class="entry-header">
		<?php
			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			endif;
		?>
	</header>
		</div>
		
		<div class="article_header-statWrp">
			<?php if ($post_statistic['in_progress'] > 0): ?>
				<div class="stat-col">
					<span class="label label-success label-soft">Проходят</span>
					<span class="label label-success"><?= $post_statistic['in_progress']; ?></span>
					<?php if ($post_statistic['overdue_users']): ?>
						<span data-toggle="tooltip" data-placement="bottom" title="Просрочили"
							  class="label label-danger"><?= $post_statistic['overdue_users']; ?></span>
					<?php endif; ?>
					<?php if ($post_statistic['total_progress'] > 0 && $post_statistic['total_progress'] != 100): ?>
						<span data-toggle="tooltip" data-placement="bottom" title="Общий прогресс"
							  class="label label-success"><?= $post_statistic['total_progress']; ?> %</span>
					<?php endif; ?>
					<div class="inline profile_avatars">
						<?php
						// User Loop
						if (!empty($active_users_array->results)) {
							$end = (count($active_users_array->results) < 4)
								? count($active_users_array->results)
								: 4 /* hardcode */
							;
							for ($i = 0; $i < $end; $i++) {
								$additional_class = ($i + 1 == $end) ? 'last-inline' : '';
								$user = $active_users_array->results[$i];
								$user_link = get_site_url() . "/people/" . $user->data->user_nicename;
								printf("<div class='inline {$additional_class}'><a href='%s'>%s</a></div>", $user_link,
									get_avatar($user->data->user_email, 24));
								
							}
							unset($end);
						}
						?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($post_statistic['done'] > 0): ?>
				<div class="stat-col">
					<span class="label label-success label-soft">Прошли</span>
					<span class="label label-success"><?= $post_statistic['done']; ?></span>
					<?php if ($post_statistic['total_progress'] > 0 && $post_statistic['total_progress'] == 100): ?>
						<span data-toggle="tooltip" data-placement="bottom" title="Общий прогресс"
							  class="label label-success"><?= $post_statistic['total_progress']; ?> %</span>
					<?php endif; ?>
					<div class="inline profile_avatars">
						<?php
						// User Loop
						if (!empty($done_users_array->results)) {
							$end = (count($done_users_array->results) < 4)
								? count($done_users_array->results)
								: 4 /* hardcode */
							;
							for ($i = 0; $i < $end; $i++) {
								$additional_class = ($i + 1 == $end) ? 'last-inline' : '';
								$user = $done_users_array->results[$i];
								$user_link = get_site_url() . "/people/" . $user->data->user_nicename;
								printf("<div class='inline {$additional_class}'><a href='%s'>%s</a></div>", $user_link,
									get_avatar($user->data->user_email, 24));
							}
							unset($end);
						}
						?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ($post_statistic['les_count']): ?>
				<div class="stat-col">
					<span class="label label-success label-soft">Частей</span>
					<span class="label label-success"><?= $post_statistic['les_count']; ?></span>
				</div>
			<?php endif; ?>
			
			<?php
			//Получаем время урока из произвольного поля
			$work_time = (int)get_post_meta($post->ID, 'work_time', true);
			if ($work_time != 0): ?>
				<div class="stat-col">
					<span class="label label-success label-soft">Время</span>
					<span class="label label-success"><?= $st::ru_months_days($work_time); ?></span>
				</div>
			<?php endif; ?>
			
			<?php $approved = wp_count_comments($post->ID)->approved;
			if ($approved > 0): ?>
				<div class="stat-col">
					<span class="label label-important-soft">Обсуждение</span>
					<span class="label label-important"> <?= $approved; ?> </span>
				</div>
			<?php endif; ?>
			
		</div>
		
		<div class="article_header-metaWrp entry-footer">
			<?php twentyfifteen_entry_meta(); ?>
			<?php edit_post_link( __( 'Edit', 'diductio' ), '<span class="edit-link">', '</span>' ); ?>
		</div>
		
		<div class="article_header-authorWrp">
			<?php
				// Author bio.
					
					$authorID = $post->post_author;
					?>
					
					<div class="author-info">
						<h2 style="display: none;" class="author-heading"><?php _e( 'Published by', 'diductio' ); ?></h2>
						<div class="author-avatar"><a href="<?php echo esc_url( get_author_posts_url( $authorID ) ); ?>">
							<?php
							/**
							 * Filter the author bio avatar size.
							 *
							 * @since Twenty Fifteen 1.0
							 *
							 * @param int $size The avatar height and width size in pixels.
							 */
							$author_bio_avatar_size = apply_filters( 'twentyfifteen_author_bio_avatar_size', 56 );

							echo get_avatar( get_the_author_meta( 'user_email', $authorID ), $author_bio_avatar_size );
							?>
							</a>
						</div><!-- .author-avatar -->
						<div class="author-description">
							<h3 class="author-title"><a href="<?php echo esc_url( get_author_posts_url( $authorID ) ); ?>"><?php echo get_the_author_meta( 'display_name', $authorID ); ?></a></h3>

							<span class="author-bio">
								<?php the_author_meta( 'description', $authorID ); ?>
							</span>
							<p>
								<a class="author-link" href="<?php echo esc_url( get_author_posts_url( $authorID ) ); ?>" rel="author">
									<?php printf( __( 'View all posts by %s', 'diductio' ), get_the_author_meta( 'display_name', $authorID ) ); ?>
								</a>
							</p><!-- .author-bio -->

						</div><!-- .author-description -->
					</div><!-- .author-info -->
					
					<?php
				
			?>
		</div>
	</div>
	
	<div class="clr"></div>
	
	
		<?php
		$current_user_id = get_current_user_id();
		$current_user_progress = false;
		$posts_users = $st->get_users_by_post($post->ID);
		// Find total progress
		$total_progress = 0;
		$num_users = 0;
		foreach ($posts_users as $user) {
			// Get current user progress
			if ($current_user_id
				&& isset($user['user_id'])
				&& $user['user_id'] === $current_user_id
			) {
				$current_user_progress = $user['progress'];
			}
			if (isset($user['progress'])
				&& $user['progress'] > 0 // if more than zero
			) {
				$total_progress += $user['progress'];
				++$num_users;
			}
		}
		if ($total_progress > 0
			&& $num_users > 1
		) {
			$total_progress = round($total_progress / $num_users, 2);
			?>
			
			<div>
				<div>
					<span>Общий прогресс</span>
				</div>
				<div class="progress">
					<div class="progress-bar " role="progressbar" aria-valuenow="<?= $total_progress; ?>"
						 aria-valuemin="0" aria-valuemax="100" style="width: <?= $total_progress; ?>%;">
						<?= $total_progress; ?> %
					</div>
				</div>
			</div>
			
			<?php
		}
		
		?>
			
	<div class="clr"></div>
</div>
