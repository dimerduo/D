<?php
    /**
     * The template for displaying all single posts and attachments
     *
     * @package    WordPress
     * @subpackage Twenty_Fifteen
     * @since      Twenty Fifteen 1.0
     */
    global $wp_roles, $post, $dUser, $st, $dPost;
    
    $roles = array();
    foreach ($wp_roles->roles as $rKey => $rvalue) {
        $roles[] = $rKey;
    }
    
    $post_statistic = $st->get_course_info($post->ID);
    $post_statistic['total_progress'] = Did_Posts::getAllUsersProgress($post->ID);
    $post_statistic['overdue_users'] = count(Did_Posts::getOverDueUsers($post->ID));
    $active_users   = $post_statistic['active_users'];
    $done_users     = $post_statistic['done_users'];
    if ($active_users) {
        $act_args            = array(
            'role__in' => $roles,
        );
        $act_args['include'] = $active_users;
        $active_users_array  = new WP_User_Query($act_args);
    }
    if ($done_users) {
        $done_args            = array(
            'role__in' => $roles,
        );
        $done_args['include'] = $done_users;
        $done_users_array     = new WP_User_Query($done_args);
    }
    
    // suggest users
    $suggestUser = new Did_SuggestUser();
    if(is_user_logged_in()) {
        $suggesting_users = $suggestUser->getSuggestingUsers(get_current_user_id(), $post->ID);
    }
    
    get_header(); ?>

<div id="primary" class="content-area">
    <div id="statistic" class="hentry">
        <?php if ($post_statistic['in_progress'] > 0): ?>
            <div class="stat-col">
                <span class="label label-success label-soft">Проходят</span>
                <span class="label label-success"><?= $post_statistic['in_progress']; ?></span>
                <?php if($post_statistic['overdue_users']): ?>
                    <span data-toggle="tooltip" data-placement="bottom" title="Просрочили" class="label label-danger"><?=$post_statistic['overdue_users'];?></span>
                <?php endif; ?>
                <?php if($post_statistic['total_progress'] > 0 && $post_statistic['total_progress'] != 100): ?>
                    <span data-toggle="tooltip" data-placement="bottom" title="Общий прогресс" class="label label-success"><?=$post_statistic['total_progress'];?> %</span>
                <?php endif; ?>
                <div class="inline profile_avatars">
                    <?php
                        // User Loop
                        if ( ! empty($active_users_array->results)) {
                            $end = (count($active_users_array->results) < 4)
                                ? count($active_users_array->results)
                                : 4 /* hardcode */
                            ;
                            for ($i = 0; $i < $end; $i++) {
                                $additional_class = ($i + 1 == $end) ? 'last-inline' : '';
                                $user             = $active_users_array->results[$i];
                                $user_link        = get_site_url() . "/people/" . $user->data->user_nicename;
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
                <?php if($post_statistic['total_progress'] > 0 && $post_statistic['total_progress'] == 100): ?>
                    <span data-toggle="tooltip" data-placement="bottom" title="Общий прогресс" class="label label-success"><?=$post_statistic['total_progress'];?> %</span>
                <?php endif; ?>
                <div class="inline profile_avatars">
                    <?php
                        // User Loop
                        if ( ! empty($done_users_array->results)) {
                            $end = (count($done_users_array->results) < 4)
                                ? count($done_users_array->results)
                                : 4 /* hardcode */
                            ;
                            for ($i = 0; $i < $end; $i++) {
                                $additional_class = ($i + 1 == $end) ? 'last-inline' : '';
                                $user             = $done_users_array->results[$i];
                                $user_link        = get_site_url() . "/people/" . $user->data->user_nicename;
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
        <?php do_action('single-after-stat-row') ?>
        <div id="user-activity" class="row">
            <?php
            $current_user_id = get_current_user_id();
            $current_user_progress = false;
            $posts_users = $st->get_users_by_post($post->ID);
            // Find total progress
            $total_progress = 0;
            $num_users = 0;
            foreach ( $posts_users as $user ) {
            	// Get current user progress
            	if ( $current_user_id
	                 && isset( $user['user_id'] )
	                 && $user['user_id'] === $current_user_id
	            ) {
		            $current_user_progress = $user['progress'];
	            }
	            if ( isset( $user['progress'] )
	                 && $user['progress'] > 0 // if more than zero
	            ) {
		            $total_progress += $user['progress'];
		            ++$num_users;
	            }
            }
            if ($total_progress > 0
                && $num_users > 1
            ) {
	            $total_progress = round( $total_progress / $num_users, 2 );
	            ?>

	            <div class="col-sm-6 col-md-6">
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

	        // Estimated progress
			$estimated_progress = 0;
	        $estimated_progress_class = '';

	        if ( isset( $post_statistic['users_started'][ $current_user_id ] ) ) {
		        $started = $post_statistic['users_started'][ $current_user_id ];
		        $now     = date_create();
		        $start   = date_create( $started );
		        // date_add() modifies $end object
		        $end     = date_create( $started );
		        date_add( $end, date_interval_create_from_date_string( $work_time . ' days' ) );
		        $diff      = date_diff( $now, $start );
		        $countdown = date_diff( $end, $now );

		        $diff_h_in_days = $diff->h > 0
			        ? $diff->h / 24
			        : 0;
                $estimated_progress = 0;
                
                if($work_time) {
                    $estimated_progress = round(
                        (
                            ($diff->days + $diff_h_in_days) / $work_time
                        ) * 100,
                        2
                    );
                }
                

		        if ( $estimated_progress > 0
		             && $current_user_progress < 100 // Hide estimated progress if user completed all tasks
		        ) {
		        	$prefix_word = 'Ещё';

			        if ( $estimated_progress >= 100 ) {
				        $estimated_progress = 100; // Fix: if $diff->days > $work_time
				        $prefix_word = 'Уже';
				        $estimated_progress_class = 'progress-bar-danger progress-bar-striped';
			        }
			        ?>
			        <div class="col-sm-6 col-md-6">
				        <div>
					        <span>Мой расчетный прогресс</span>
					        <span class="passing_date"><?= $prefix_word . ' ' . $st::ru_months_days( $countdown->days ) ?></span>
				        </div>
				        <div class="progress">
					        <div class="progress-bar <?= $estimated_progress_class; ?>" role="progressbar"
					             aria-valuenow="<?= $estimated_progress; ?>"
					             aria-valuemin="0" aria-valuemax="100" style="width: <?= $estimated_progress; ?>%;">
						        <?= $estimated_progress; ?> %
					        </div>
				        </div>
			        </div>
			        <?php
		        } // if ( $estimated_progress > 0 )
	        } // if ( isset( $post_statistic['users_started'][ $current_user_id ] ) )

                $end = count($posts_users) >= 2 ?  2 : count($posts_users);
                for ($i = 0; $i < $end; $i++):
                    $passing_date = $dPost->get_passing_info_by_post($posts_users[$i]['user_id'], $post->ID);
                    ?>
                <div class="col-sm-6 col-md-6">
                    <div>
                        <a href="<?=$posts_users[$i]['user_link'];?>">
                            <?=$posts_users[$i]['avatar'];?>
                            <span><?=$posts_users[$i]['username'];?></span>
                        </a>
                        <span class="passing_date"><?=$passing_date['date_string'];?></span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar " role="progressbar" aria-valuenow="<?=$posts_users[$i]['progress'];?>" aria-valuemin="0" aria-valuemax="100" style="width:<?=$posts_users[$i]['progress'];?>%;">
                            <?=$posts_users[$i]['progress'];?> %
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
            <?php if(count($posts_users) > 2): ?>
                <div class="row">
                    <div class="col-md-4 col-md-offset-8 more-users">
                        <a id="display-more-users" class="link-style-2" href="javascript:void(0);">
                            Развернуть
                        </a>
                    </div>
                </div>
                <div class="rest-users" style="display: none;">
                <?php    for ($i = 2; $i < count($posts_users); $i++):
                    $passing_date = $dPost->get_passing_info_by_post($posts_users[$i]['user_id'], $post->ID);
                    ?>
                    <div class="col-sm-6 col-md-6">
                        <div>
                            <a href="<?=$posts_users[$i]['user_link'];?>">
                                <?=$posts_users[$i]['avatar'];?>
                                <span><?=$posts_users[$i]['username'];?></span>
                            </a>
                            <span class="passing_date"><?=$passing_date['date_string'];?></span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar " role="progressbar" aria-valuenow="<?=$posts_users[$i]['progress'];?>" aria-valuemin="0" aria-valuemax="100" style="width:<?=$posts_users[$i]['progress'];?>%;">
                                <?=$posts_users[$i]['progress'];?> %
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>

        </div>
        
    </div>
    <main id="main" class="site-main" role="main">

        <?php
            // Start the loop.
            while (have_posts()) : the_post();

                /*
                 * Include the post format-specific template for the content. If you want to
                 * use this in a child theme, then include a file called called content-___.php
                 * (where ___ is the post format) and that will be used instead.
                 */
                get_template_part('content', get_post_format());

                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template('/comments-short.php');
                endif;

                // Previous/next post navigation.
                the_post_navigation(array(
                    'next_text' => '<span class="meta-nav" aria-hidden="true">' . __('Next', 'diductio') . '</span> ' .
                                   '<span class="screen-reader-text">' . __('Next post:', 'diductio') . '</span> ' .
                                   '<span class="post-title">%title</span>',
                    'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __('Previous',
                            'diductio') . '</span> ' .
                                   '<span class="screen-reader-text">' . __('Previous post:', 'diductio') . '</span> ' .
                                   '<span class="post-title">%title</span>',
                ));

                // End the loop.
            endwhile;
        ?>

    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
