<?php
/**
 * Вьюшка личного и публичного кабинета пользователя
 */
?>
<!-- Cabinet -->
<div class="personal-area row">
    <div class="avatar ">
        <div class="col-sm-12 col-md-2"><?= get_avatar($author_info->user_email, 96); ?></div>
        <div class="user-info col-sm-12 col-md-10">
            <span class="label <?php if($author_info->inner_passing_rating > 99):?>label-success<?php else:?>label-danger<?php endif;?> single" data-toggle="tooltip" data-placement="top" title="Общая оценка системы"><?=$author_info->inner_passing_rating;?>%</span>
            <h1 class="entry-title inline">
                <?= $author_info->data->display_name; ?>
            </h1>
            <?php if($will_busy_days): ?>
                <?php if ($will_busy_days): ?><span class="inline">, занят еще <?= $will_busy_days; ?></span><?php endif; ?>
            <?php endif; ?>
            <div class="about"><?= get_user_meta($author_info->ID, 'description')[0]; ?></div>
            <div class="user-categories">
                <?php view('user-category-static',
                    compact('user_statistic', 'category_statistic', 'author_info', 'tag_statistic')); ?>
            </div>
        </div>
    </div>
</div>
<?php if ($favorite_post_ids): ?>
    <div class="wpfp-span public-page-statistic-box">
        <ul>
            <?php while (have_posts()) : the_post();
                $author_id = get_the_author_meta('ID'); ?>
                <?php
                $passing_date = $dPost->get_passing_info_by_post($user_id, get_the_ID());
                $percent = $GLOBALS['st']->get_user_progress_by_post(get_the_ID(), $user_id);
                
                if ($percent == 100) {
                    $passed_rating = Did_Posts::getPassedPostRating(get_the_ID(), $user_id);
                }
                $passing_string = "<span class='passing_date'>" . $passing_date['date_string'] . "</span>";
                $on_knowledge = $passing_date['undone_title']
                    ? '<span class="on-knowldedge"> На этапе: ' . $passing_date['undone_title'] . '</span>'
                    : '';
                ?>
                <li>
                    <?php if($passed_rating): ?>
                        <span data-toggle="tooltip" data-placement="top" title="Oценка системы" class="label <?=$passed_rating['class'];?> single"><?=$passed_rating['value'];?>%</span>
                    <?php endif; ?>
                    <a href="<?= get_permalink(); ?>"
                       title="<?= get_the_title(); ?>">
                        <?= get_the_title(); ?>
                        <?php if ($author_id === $user_id): ?>
                            <small class="is_author"> автор</small>
                        <?php endif; ?>
                    </a>
                    <?= $passing_string; ?>
                    <?= diductio_add_progress(get_the_ID(), $user_id, false); ?>
                    <?= $on_knowledge; ?>
                </li>
                <?php unset($passed_rating); ?>
            <?php endwhile; ?>
        </ul>
    </div>
<?php endif; ?>
<!-- Cabinet end -->
