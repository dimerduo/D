<?php
$st = $GLOBALS['st'];
?>
<div id="statistic" class="hentry">
    <?php switch ($data->type):
        case 'knowledge': ?>
            <div class="public_statistic row">
                <div class="stat-col">
                    <a href="<?= get_site_url(); ?>">
                        <span class="label label-success
                        <?php if(!is_front_page()): ?>
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
                            <?php if($term->slug != "post-format-aside"): ?>
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
                            <?php if($term->slug != "post-format-image"): ?>
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
                            <?php if($term->slug != "post-format-chat"): ?>
                            label-soft
                            <?php endif; ?>">Голосования</span>
                            <span class="label label-success"><?= $st->getPostsCountByFormat('post-format-chat',
                                    'post_format'); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if ($st->getPostsCountByFormat('post-format-gallery', 'post_format')): ?>
                    <div class="stat-col">
                        <a href="/type/task">
                            <span class="label label-success
                            <?php if($term->slug != "post-format-gallery"): ?>
                            label-soft
                            <?php endif; ?>">Задачи</span>
                            <span class="label label-success"><?= $st->getPostsCountByFormat('post-format-gallery',
                                    'post_format'); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if (function_exists('loadView')) {
                    global $post;
                    $post_slug=$post->post_name;
                    if($post_slug != 'my-subscriptions') {
                        $data->class = "label-soft";
                    } else {
                        $data->class = "";
                    }
                    $my_post_number = getMyPostCount();
                    if($my_post_number) {
                        $data->number_of_posts = $my_post_number;
                        loadView('my', $data);
                    }
                }
                ?>
                <div class="stat-col">
                    <a href="/array-active">
                        <span class="label label-success
                        <?php if(!is_page('array-active')): ?>
                        label-soft
                        <?php endif; ?>
                        ">Проходят</span>
                        <span class="label label-success"><?= $st->active; ?></span>
                    </a>
                </div>
                <div class="stat-col">
                    <a href="/array-recently">
                        <span class="label label-success
                        <?php if(!is_page('array-recently')): ?>
                        label-soft
                        <?php endif; ?>">Прошли</span>
                        <span class="label label-success"><?= $st->done; ?></span>
                    </a>
                </div>
                <div class="stat-col">
                    <a href="/source">
                        <span class="label label-success
                        <?php if(!is_page('source')): ?>
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
                        <span class="label label-important<?php if(!is_page('people')): ?>-soft<?php endif; ?>">Люди</span>
                        <span class="label label-important"><?= $st->get_all_users(); ?></span>
                    </a>
                </div>
                <div class="stat-col">
                    <a href="<?php get_home_url(); ?>/people-active">
                        <span class="label label-important<?php if(!is_page('people-active')): ?>-soft<?php endif; ?>">Проходят</span>
                        <span class="label label-important"><?= $st->active_studies_users; ?></span>
                    </a>
                </div>
                <div class="stat-col">
                    <a href="<?php get_home_url(); ?>/people-recently">
                        <span class="label label-important<?php if(!is_page('people-recently')): ?>-soft<?php endif; ?>">Прошли</span>
                        <span class="label label-important"><?= $st->finished_study_users; ?></span>
                    </a>
                </div>
                <div class="stat-col">
                    <span class="label label-important-soft">Прогресс</span>
                    <span class="label label-important"><?= $st->get_progress(); ?> %</span>
                </div>
                <div class="stat-col">
                    <a href="<?= get_home_url(); ?>">
                        <span class="label label-success label-soft">Массивы</span>
                        <span class="label label-success"><?= $st->get_all_arrays(); ?></span>
                    </a>
                </div>
            </div>
            <?php break; ?>
        <?php endswitch; ?>
</div>