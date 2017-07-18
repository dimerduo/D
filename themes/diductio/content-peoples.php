<?php
/**
 * The template used for displaying content in the peoples page (each user view)
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

global $user, $st, $dUser, $dPost;
$Did_Categories = new Did_Categories();
$user_id = $user->ID;
$user_statistic = $st->get_user_info($user_id);
$will_busy_days = $user_statistic['countdown_days'] ? $st::ru_months_days($user_statistic['countdown_days']) : 0;
$user_statistic['author'] = Did_User::getAllMyPosts($user_id);
$is_free = $dUser->is_free($user_id);

$progress = $st->get_div_studying_progress($user_id);
$category_statistic = $Did_Categories->fetchCategoriesByUser($user_id)->orderBy('value', 'desc')->get(3);
$tag_statistic = $Did_Categories->fetchTagsByUser($user_id)->orderBy('value', 'desc')->max();
$author_info = get_userdata($user_id);
$favorite_post_ids = $st->get_knowledges($user_id);
$enable_link = true;

$tasks_counters = array(
    'in_progress' => $user_statistic['in_progress'],
    'overdue' => $user_statistic['overdue_tasks'],
);
?>
<div class="col-md-12 peoples-row">
    <?php view(
        'people.single-row',
        compact(
            'user_statistic',
            'category_statistic',
            'author_info',
            'tag_statistic',
            'user_id',
            'dPost',
            'favorite_post_ids',
            'will_busy_days',
            'enable_link'
        )
    );
    ?>
</div>
