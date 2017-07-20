<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
	global $active_flag, $st;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		// Post thumbnail.
		twentyfifteen_post_thumbnail();

	?>

	<header class="entry-header">
	 	<?php $id = get_the_ID();
	 	$post_permalink = get_permalink($id);
	 	if($id != '68'): ?>
			<a href="<?=$post_permalink; ?>"><?php the_title( '<h1 class="entry-title">', '</h1>' ); ?></a>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content('Читать далее'); ?>
		<?php
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'diductio' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'diductio' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<div class="footer-statistic">
			<?php
			$post_statistic = $st->get_course_info($post->ID);
			$post_statistic['total_progress'] = Did_Posts::getAllUsersProgress($post->ID);
			$post_statistic['overdue_users'] = count(Did_Posts::getOverDueUsers($post->ID));
			?>
			<?php if($post_statistic['in_progress'] > 0 ): ?>
				<div class="stat-col">
					<span class="label label-success label-soft">Проходят</span>
					<span class="label label-success"><?=$post_statistic['in_progress'];?></span>
					<?php if($post_statistic['overdue_users']): ?>
						<span class="label label-danger"><?=$post_statistic['overdue_users'];?></span>
					<?php endif; ?>
					<?php if($post_statistic['total_progress'] > 0 && $post_statistic['total_progress'] != 100): ?>
						<span class="label label-success"><?=$post_statistic['total_progress'];?> %</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if($post_statistic['done'] > 0 ): ?>
				<div class="stat-col">
					<span class="label label-success label-soft">Прошли</span>
					<span class="label label-success"><?=$post_statistic['done'];?></span>
					<?php if($post_statistic['total_progress'] > 0 && $post_statistic['total_progress'] == 100): ?>
						<span class="label label-success"><?=$post_statistic['total_progress'];?> %</span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if($post_statistic['les_count']): ?>
				<div class="stat-col">
					<span class="label label-success label-soft">Частей</span>
					<span class="label label-success"><?=$post_statistic['les_count'];?></span>
				</div>
			<?php endif; ?>
			<?php $approved = wp_count_comments( $post->ID )->approved;
			if($approved > 0 ): ?>
				<div class="stat-col">
					<span class="label label-success label-soft">Обсуждение</span>
					<span class="label label-success"> <?=$approved; ?> </span>
				</div>
			<?php endif; ?>
		</div>
		<?php twentyfifteen_entry_meta(); ?>
		<?php edit_post_link( __( 'Edit', 'diductio' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
