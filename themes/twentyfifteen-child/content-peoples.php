<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

	global $user, $st;
	$user_statistic = $st->get_user_info($user->ID);
	$level = $st->get_rating('local', $user->ID);
	$progress = $st->get_div_studying_progress($user->ID);
?>

<div class="col-md-6">
	<div class="inline"><?=get_avatar( $user->user_email, 32 );?></div>
	<div class="inline"><a class="link-style-1" href="<?=get_site_url();?>/user/<?=$user->user_nicename?>"><?=$user->display_name?></a></div>
	<?php if( $user_statistic['in_progress'] || $user_statistic['done'] || $level || $progress): ?>
	<div class="inline">
		<div class="stat-col">
			<span class="label label-success label-soft" data-toggle="tooltip" data-placement="top" title="Активных"><?=$user_statistic['in_progress'];?></span>
		</div>
		<div class="stat-col">
			<span class="label label-success label-soft" data-toggle="tooltip" data-placement="top" title="Пройденных"><?=$user_statistic['done'];?></span>
		</div>
		<div class="stat-col">
			<span class="label label-important-soft" data-toggle="tooltip" data-placement="top" title="Уровень"><?=$level;?> %</span>
		</div>
		<div class="stat-col">
			<span class="label label-important-soft" data-toggle="tooltip" data-placement="top" title="Прогресс"><?=$progress;?> %</span>
		</div>
	</div>
	<?php endif; ?>
</div>
