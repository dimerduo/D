<?php
global $user_comments, $wpdb;

$progress_where = "";
$comments_where = "";
if (is_user_logged_in()) {
    $id = get_current_user_id();
    $subscriber_list = get_user_meta($id, 'subscribe_to')[0];
    if ($subscriber_list) {
        $subscriber_list_string = implode(",", $subscriber_list);
        $progress_where = "`user_id` IN ({$subscriber_list_string})";
        $comments_where = "wp_progres.user_id IN ({$subscriber_list_string})";
    }
}
// prepare sql
$sql
    = <<<MYSQL
          SELECT `T2`.`post_title`, `T1`.`update_at`  FROM `wp_user_add_info` as `T1`
	      LEFT JOIN `wp_posts` AS `T2` ON `T1`.`post_id` = `T2`.`ID`
          WHERE `user_id` = $id
          UNION ALL
          SELECT `T3`.`Comment_ID`, `T3`.`comment_date` FROM `wp_comments` as `T3` WHERE `user_id` = $id
          ORDER BY `update_at` DESC
          LIMIT 10
MYSQL;
$activities = $wpdb->get_results($sql, ARRAY_A);
$activity_date = '';

foreach ($activities as $key => $activity) {
    $activities[$key]['date'] = date('d.m.Y', strtotime($activity['update_at']));
}
?>
<article class="post type-post status-publish format-quote hentry">
    
    <?php foreach ($activities as $activity): ?>
        <div class="activity entry-content ">
            <?php if ($activity['date'] !== $activity_date): ?>
                <span class="date"><?= $activity['date'] ?></span>
                <?php $activity_date = $activity['date']; ?>
            <?php endif; ?>
            <div class="">
                <?=$activity['post_title'];?>
            </div>
            <?php
            if ($activity['date'] !== $activity_date) {
            
            }
            ?>
        </div>
    <?php endforeach; ?>

</article>