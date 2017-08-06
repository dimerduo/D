<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>
<?php global $st; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
		// Post thumbnail.
		twentyfifteen_post_thumbnail();
	?>

	<header class="entry-header">
		<?php
			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
			endif;
		?>
	</header><!-- .entry-header -->
	<div class="entry-content <?=$removing_space_class;?>">
	

		<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading %s', 'diductio' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			) );

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'diductio' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'diductio' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>

<!-- (17) Добавление чекбокса на страницу с отсутствующим акордеоном -->
    <?php if ( is_single()): ?>
		<?php if(!$GLOBALS['accordion_exsit']):
			$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
			$user_id = get_current_user_id();
			$sql  = "SELECT `checked_lessons` FROM `$table_name` WHERE `user_id` = '{$user_id}' ";
			$sql .= "AND `post_id` = '{$post->ID}'";
			$progress = $wpdb->get_row($sql);
		    $isMine = Did_Posts::isPostInMyCabinet($user_id, $post->ID);
		    
			if($progress->checked_lessons) {
				$checkbox_attr = "checked='checked' disabled='disabled'";
			}
		?>
		 <?php if(is_user_logged_in() && $isMine): ?>
			 <div class="col-md-1 col-xs-2" style="height: 0;">
					<div style="height: 22px;" class="checkbox inline">
						<input id="checkbox-<?=$post->ID;?>" type="checkbox" class="accordion-checkbox" data-accordion-count="1" data-post-id="<?=$post->ID;?>" <?=$checkbox_attr?> >
					    <label for="checkbox-<?=$post->ID;?>"></label>
					</div>
			 </div>
			 <div class="col-md-3 col-xs-5 checkbox-label">
			 		<label for="checkbox-<?=$post->ID;?>">Готово!</label>
			 </div>
		<?php endif;?>
		<?php endif;?>
	<?php endif;?>
<!-- (17) Добавление чекбокса на страницу с отсутствующим акордеоном end-->
	</div><!-- .entry-content -->

	<?php
		// Author bio.
		if ( get_the_author_meta( 'description' ) ) :
			get_template_part( 'author-bio' );
		endif;
	?>
	
	<footer class="entry-footer">
		<?php if(is_single()): ?>
			<div class="post-rating">
				<?php if(function_exists('the_ratings')) { the_ratings(); } ?>
			</div>
		<?php endif; ?>
		<?php if(!is_single()): ?>
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
							<span data-toggle="tooltip" data-placement="top" title="Просрочили" class="label label-danger"><?=$post_statistic['overdue_users'];?></span>
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

				<?php
					//Получаем время урока из произвольного поля
					$work_time = (int)get_post_meta($post->ID, 'work_time', true);
					if( $work_time!=0 ): ?>
					<div class="stat-col">
						<span class="label label-success label-soft">Время</span>
						<span class="label label-success"><?= $st::ru_months_days($work_time); ?></span>
					</div>
				<?php endif; ?>

				<?php $approved = wp_count_comments( $post->ID )->approved;
				if($approved > 0 ): ?>
					<div class="stat-col">
						<span class="label label-important-soft">Обсуждение</span>
						<span class="label label-important"> <?=$approved; ?> </span>
					</div>
				<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php twentyfifteen_entry_meta(); ?>
		<?php edit_post_link( __( 'Edit', 'diductio' ), '<span class="edit-link">', '</span>' ); ?>
		
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
