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
            <h1 class="entry-title inline"><?= $author_info->data->display_name; ?></h1>
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
                $passing_string = "<span class='passing_date'>" . $passing_date['date_string'] . "</span>";
                $on_knowledge = $passing_date['undone_title']
                    ? '<span class="on-knowldedge"> На этапе: ' . $passing_date['undone_title'] . '</span>'
                    : '';
                ?>
                <li>
                    <a href="<?= get_permalink() . get_first_unchecked_lesson(get_the_ID()) ?>"
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
            <?php endwhile; ?>
        </ul>
    </div>
<?php endif; ?>
<!-- Cabinet end -->
