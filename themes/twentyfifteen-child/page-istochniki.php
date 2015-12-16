<?php
/**
 * Template Name: Шаблон страницы "Источники"
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php
		// $args = array(
		// 		'orderby'=> 'modified',
		// 		'order' => 'DESC'
		// );
		// $tags = get_tags($args);
		global $wpdb;
		
		$sql = "SELECT term_id, name, slug, tag_history.tagdate FROM (SELECT wp_term_relationships.term_taxonomy_id AS tagid, substr(wp_posts.post_date_gmt,1,10) AS tagdate FROM wp_term_relationships INNER JOIN wp_term_taxonomy ON wp_term_taxonomy.term_taxonomy_id=wp_term_relationships.term_taxonomy_id INNER JOIN wp_posts ON wp_posts.ID=wp_term_relationships.object_id WHERE taxonomy='post_tag' ORDER BY post_date_gmt DESC, wp_posts.post_title) AS tag_history INNER JOIN wp_terms ON wp_terms.term_id=tag_history.tagid GROUP BY tag_history.tagid ORDER BY tag_history.tagdate DESC";
		$progress = $wpdb->get_results($sql);
		
		foreach($progress as $tag_info)
		{
			$tag = get_tag($tag_info->term_id);
			get_template_part( 'content', 'istochniki' );
		}
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
