<?php
/**
 * The template used for displaying page content
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
	 	<?php $id = get_the_ID();
	 	if($id != '68' && !is_page('progress') ): ?>
		    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php elseif(is_page('progress')):
			$current_user = wp_get_current_user();
		    //Get categories information by user
		    $Did_Categories = new Did_Categories();
		    $category_statistic = $tag_statistic = array();
		    $category_statistic = $Did_Categories->fetchCategoriesByUser($current_user->ID)->orderBy('value','desc')->max();
		    $tag_statistic = $Did_Categories->fetchTagsByUser($current_user->ID)->orderBy('value','desc')->max();
            ?>
            <div class="personal-area">
                <div  class="avatar inline">
                    <?=get_avatar( $current_user->user_email, 96 );?>
                </div>
                <div style="user-info" class="inline">
                    <h1 class="inline entry-title"><?php print_r($current_user->data->display_name); ?></h1>
                    <div class="about"><?=get_user_meta($current_user->ID,'description')[0];?></div>
                    <div class="user-categories">
                        <?php view('user-category-static', compact('category_statistic', 'current_user', 'tag_statistic')); ?>
                    </div>
                </div>

            </div>
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
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

	<?php edit_post_link( __( 'Edit', 'diductio' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->' ); ?>

</article><!-- #post-## -->
