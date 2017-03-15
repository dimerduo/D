<?php
    global $st;
    $user_id        = $data->user_id;
    $user_statistic = $st->get_user_info($user_id);
?>
<div id="statistic" class="hentry">
    <?php switch ($data->type):
        case 'knowledge': ?>
            <div class="public_statistic row">
                <div class="stat-col">
                    <a href="<?= get_site_url(); ?>">
                        <span class="label label-success
                        <?php if ( ! is_front_page()): ?>
                        label-soft
                        <?php endif; ?>">Все</span>
                        <span class="label label-success"><?= $st->get_all_arrays(); ?></span>
                    </a>
                </div>
                <?php if ($st->getPostsCountByFormat('post-format-aside', 'post_format')):
                    global $term; ?>
                    <div class="stat-col">
                        <a href="/type/knowledge">
                            <span class="label label-success
                            <?php if ($term->slug != "post-format-aside"): ?>
                            label-soft
                            <?php endif; ?>">Знания</span>
                            <span class="label label-success"><?= $st->getPostsCountByFormat('post-format-aside',
                                    'post_format') ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if ($st->getPostsCountByFormat('post-format-image', 'post_format')): ?>
                    <div class="stat-col">
                        <a href="/type/test">
                            <span class="label label-success
                            <?php if ($term->slug != "post-format-image"): ?>
                            label-soft
                            <?php endif; ?>">Тесты</span>
                            <span class="label label-success"><?= $st->getPostsCountByFormat('post-format-image',
                                    'post_format') ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if ($st->getPostsCountByFormat('post-format-chat', 'post_format')): ?>
                    <div class="stat-col">
                        <a href="/type/poll">
                            <span class="label label-success
                            <?php if ($term->slug != "post-format-chat"): ?>
                            label-soft
                            <?php endif; ?>">Голосования</span>
                            <span class="label label-success"><?= $st->getPostsCountByFormat('post-format-chat',
                                    'post_format'); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if ($st->getPostsCountByFormat('post-format-quote', 'post_format')): ?>
                    <div class="stat-col">
                        <a href="/type/project">
                            <span class="label label-success
                            <?php if ($term->slug != "post-format-quote"): ?>
                            label-soft
                            <?php endif; ?>">Проекты</span>
                            <span class="label label-success"><?= $st->getPostsCountByFormat('post-format-quote',
                                    'post_format'); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if ($st->getPostsCountByFormat('post-format-gallery', 'post_format')): ?>
                    <div class="stat-col">
                        <a href="/type/task">
                            <span class="label label-success
                            <?php if ($term->slug != "post-format-gallery"): ?>
                            label-soft
                            <?php endif; ?>">Задачи</span>
                            <span class="label label-success"><?= $st->getPostsCountByFormat('post-format-gallery',
                                    'post_format'); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if (function_exists('loadView')) {
                    global $post;
                    $post_slug = $post->post_name;
                    if ($post_slug != 'my-subscriptions') {
                        $data->class = "label-soft";
                    } else {
                        $data->class = "";
                    }
                    $my_post_number = getMyPostCount();
                    if ($my_post_number) {
                        $data->number_of_posts = $my_post_number;
                        loadView('my', $data);
                    }
                }
                ?>
                <div class="stat-col">
                    <a href="/array-active">
                        <span class="label label-success
                        <?php if ( ! is_page('array-active')): ?>
                        label-soft
                        <?php endif; ?>
                        ">Проходят</span>
                        <span class="label label-success"><?= $st->active; ?></span>
                    </a>
                </div>
                <div class="stat-col">
                    <a href="/source">
                        <span class="label label-success
                        <?php if ( ! is_page('source')): ?>
                        label-soft
                        <?php endif; ?>">Источники</span>
                        <span class="label label-success"><?= $st->get_istochiki_count(); ?></span>
                    </a>
                </div>
                <div class="stat-col">
                    <a href="/people">
                        <span class="label label-important-soft">Люди</span>
                        <span class="label label-important"><?= $st->get_all_users(); ?></span>
                    </a>
                </div>
            </div>
            <?php break; ?>
        <?php case 'peoples': ?>
            <div class="public_statistic row precent-row">
                <div class="stat-col">
                    <a href="<?php get_home_url(); ?>/people">
                        <span
                            class="label label-important<?php if ( ! is_page('people')): ?>-soft<?php endif; ?>">Люди</span>
                        <span class="label label-important"><?= $st->get_all_users(); ?></span>
                    </a>
                </div>
                <div class="stat-col">
                    <a href="<?php get_home_url(); ?>/people-active">
                        <span
                            class="label label-important<?php if ( ! is_page('people-active')): ?>-soft<?php endif; ?>">Заняты</span>
                        <span class="label label-important"><?= $st->active_studies_users; ?></span>
                    </a>
                </div>
                <div class="stat-col">
                    <a href="<?php get_home_url(); ?>/people-free">
                        <span class="label label-important<?php if ( ! is_page('peoples-free')): ?>-soft<?php endif; ?>">Свободны</span>
                        <span class="label label-important"><?= $st->free_peoples_count; ?></span>
                    </a>
                </div>
                <div class="stat-col">
                    <span class="label label-important-soft">Прогресс</span>
                    <span class="label label-important"><?= $st->get_progress(); ?> %</span>
                </div>
	            <?php
	            if ( $user_statistic['countdown_days'] > 0 ) {
		            ?>
		            <div class="stat-col">
			            <span class="label label-success label-soft">Ещё</span>
			            <span class="label label-success">
				            <?= $st::ru_months_days( $user_statistic['countdown_days'] ); ?>
			            </span>
		            </div>
		            <?php
	            }
	            ?>
                <div class="stat-col">
                    <a href="<?= get_home_url(); ?>">
                        <span class="label label-success label-soft">Массивы</span>
                        <span class="label label-success"><?= $st->get_all_arrays(); ?></span>
                    </a>
                </div>
            </div>
            <?php break; ?>
        <?php case('personal-area'):
            global $dUser;
            $subscription_count = $dUser->getSubscriptionsCount($user_id);
            $comment_count      = $dUser->get_comments_count($user_id);
            ?>

            <div class="public_statistic row precent-row">
                <div class="stat-col" style="margin-right: 11px;">
                    <a href="<?= $data->progress_url; ?>">
                        <span
                            class="label label-success <?php if ( is_page('activity') || is_page('subscription')): ?>label-soft<?php endif; ?>">Прогресс</span>
                        <span class="label label-success"><?= $user_statistic['in_progress']; ?></span>
                        <span class="label label-success"><?= $data->pecent; ?> %</span>
                    </a>
                </div>
	            <?php
	            if ( $user_statistic['countdown_days'] > 0 ) {
		            ?>
		            <div class="stat-col">
			            <span class="label label-success label-soft">Ещё</span>
			            <span class="label label-success">
				            <?= $st::ru_months_days( $user_statistic['countdown_days'] ); ?>
			            </span>
		            </div>
		            <?php
	            }
	            ?>
                <div class="stat-col" style="margin-right: 11px;">
                    <a href="/activity<?= $data->custom_url; ?>">
                        <span
                            class="label label-success <?php if ( ! is_page('activity')): ?>label-soft<?php endif; ?>">Активность</span>
                        <span class="label label-success"><?= $comment_count; ?></span>
                    </a>
                </div>
                <div class="stat-col" style="margin-right: 11px;">
                    <a href="/subscription<?= $data->custom_url; ?>">
                        <span
                            class="label label-success <?php if ( ! is_page('subscription')): ?>label-soft<?php endif; ?>"">Подписки</span>
                        <span class="label label-success"><?= $subscription_count; ?></span>
                    </a>
                </div>
                <?php
                    if (function_exists('getSubsriberView') && $user_id != get_current_user_id()) {
                        echo getSubsriberView('author');
                    }
                ?>
            </div>
            <?php break; ?>
        <?php endswitch; ?>
</div>