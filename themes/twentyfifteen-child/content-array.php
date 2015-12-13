<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
	global $active_flag;
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
		<div class="add-to-favor-wrapper">
		    <?php if($active_flag): ?>
				<span class="label label-success label-soft">Массив недавно прошли</span>
				<span class="label label-success"><?=$post->complite_count;?></span>
		    <?php else: ?>
				<span class="label label-success label-soft">Массив проходят</span>
				<span class="label label-success"><?=$post->in_progress_count;?></span>
		    <?php endif; ?>
		</div>
		<?php the_content('Читать далее'); ?>
		<?php
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfifteen' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php twentyfifteen_entry_meta(); ?>
		<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
