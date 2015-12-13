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
				the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) . get_first_unchecked_lesson($post->ID) ), '</a></h2>' );
			endif;
		?>
	</header><!-- .entry-header -->
	<div class="entry-content <?=$removing_space_class;?>">
	
<!-- (4) Вставка добавления избранного в начало записи -->
	<?php 
		if ( is_user_logged_in() && is_single() ) {
			echo '<div class="add-to-favor-wrapper">';
			 if(array_complite($post->ID)) {
				echo "<span class='label label-success'>Массив успешно пройден</span>";
			 } else {
				if (function_exists('wpfp_link')) { wpfp_link(); } 
			 }
			echo "</div>";
		}  
	?>
<!-- (4) Вставка добавления избранного в начало записи end -->

		<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading %s', 'twentyfifteen' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			) );

			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfifteen' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>%',
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

			if($progress->checked_lessons) {
				$checkbox_attr = "checked='checked' disabled='disabled'";
			}
		?>
		 <?php if(is_user_logged_in()): ?>
			 <div class="col-md-1 col-xs-2">
					<div class="checkbox inline">
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
		if ( is_single() && get_the_author_meta( 'description' ) ) :
			get_template_part( 'author-bio' );
		endif;
	?>

	<footer class="entry-footer">
		<?php twentyfifteen_entry_meta(); ?>
		<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
