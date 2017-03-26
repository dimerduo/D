<?php
/**
 * The template used for displaying content in the peoples page (each user view)
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

global $user, $st, $dUser;
$user_statistic = $st->get_user_info( $user->ID );
$is_free        = $dUser->is_free( $user->ID );
//$level = $st->get_rating('local', $user->ID);
$progress = $st->get_div_studying_progress( $user->ID );

// Get info about user posts formats
$post_format_ru = array(
	'aside' => 'знаний',
	'quote' => 'проектов',
	'gallery' => 'галерей',
	'chat' => 'чатов',
);

$post_counter = array(
	'aside'   => $st::get_count_posts_by_format( $user->ID, 'aside' ),
	'quote'   => $st::get_count_posts_by_format( $user->ID, 'quote' ),
	'gallery' => $st::get_count_posts_by_format( $user->ID, 'gallery' ),
	'chat'    => $st::get_count_posts_by_format( $user->ID, 'chat' ),
);

$formats_counters_arr = array();
foreach ( $post_counter as $name => $counter ) {
	if ( $counter > 0 ) {
		$formats_counters_arr[] = $post_format_ru[ $name ] . ' ' . $counter;
	}
}
?>

<div class="col-md-6">
	<div class="inline"><?=get_avatar( $user->user_email, 32 );?></div>
	<div class="inline">
		<a class="link-style-1" href="<?= get_site_url(); ?>/people/<?= $user->user_nicename ?>"><?= $user->display_name ?></a><?php
		if ( ! $is_free ) {
			?><span class="busy-people">, занят еще
			<?= $st::ru_months_days( $user_statistic['countdown_days'] ); ?>
			</span>
		<?php } ?>
		<div class="formats-counters"><?= implode(', ', $formats_counters_arr); ?></div>
	</div>
	<?php
	// Warning this output was hidden, see: themes/diductio/style.css:6568
	// .all-users .stat-col { display: none; }
	// Now disabled with PHP comment
	/*
	if( $user_statistic['in_progress'] || $user_statistic['done'] || $level || $progress) {?>
	<div class="inline">
		<div class="stat-col">
			<span class="label label-success label-soft" data-toggle="tooltip" data-placement="top" title="Активных"><?=$user_statistic['in_progress'];?></span>
		</div>
		<div class="stat-col">
			<span class="label label-success label-soft" data-toggle="tooltip" data-placement="top" title="Пройденных"><?=$user_statistic['done'];?></span>
			</div>
		<!-- Level is disabled
		<div class="stat-col">
			<span class="label label-important-soft" data-toggle="tooltip" data-placement="top" title="Уровень"><?=$level;?> %</span>
		</div>
		-->
		<div class="stat-col">
			<span class="label label-important-soft" data-toggle="tooltip" data-placement="top" title="Прогресс"><?=$progress;?> %</span>
		</div>
	</div>
	<?php }
	*/ ?>
</div>
