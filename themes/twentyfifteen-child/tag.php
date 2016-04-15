<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<div id="statistic" class="hentry">
			<div class="stat-col">
				<span class="label label-success label-soft">Массивы знаний</span>
				<span class="label label-success"><?=$wp_the_query->post_count;?></span>
			</div>
		</div>
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>
			
			<article class='hentry'>
				<header class="entry-header">
					<?php
						the_archive_title( '<h1 class="entry-title">', '</h1>' );
						// echo "<span class='label label-success label-soft'>Массивов &nbsp;</span>";
						// echo "<span class='label label-success label-number'>". $wp_the_query->post_count."</span>";
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header>
				<footer class="entry-footer">
					<span class="screen-reader-text">Рубрики </span>
						<?php
						 if(is_tag()){
						 	$tag = get_queried_object();
		    				$tag_id = $tag->term_id;
		    				global $wp_query;
		    				$args = array( 
						        'tag__in' => $tag_id,
						        'posts_per_page' => -1);
		    				$tag_posts = get_posts($args);
		    				$tag_categories = array();
		    				foreach ($tag_posts as $tag_key => $tag_value) {
		    					$category_id = wp_get_post_categories($tag_value->ID);
		    					$category_info = get_category($category_id[0]);
			   					$category_link  = get_category_link($category_id[0]);
								$tmp_data['cat_id']   =  $category_info -> term_id;
								$tmp_data['cat_name'] =	 $category_info -> name;
								$tmp_data['cat_link'] =	 $category_link;
								$tag_categories[$category_info -> term_id] = $tmp_data;
		    				}

		    				foreach ($tag_categories as $key => $value) {
		    					$html  = '<span class="cat-links 2">';
		    					$html .='<a href="'. $value['cat_link'] .'">'. $value['cat_name'] . '</a>';
		    					$html .='</span>';
		    					echo $html;
		    				}	
						 }
						 ?>
				</footer>
			</article>

			<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'content', get_post_format() );

			// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
				'next_text'          => __( 'Next page', 'twentyfifteen' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>

