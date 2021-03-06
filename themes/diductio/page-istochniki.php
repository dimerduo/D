<?php
/**
 * Template Name: Шаблон страницы "Источники"
 */
get_header(); ?>
	<div id="primary" class="content-area">
		<?php do_action('istochniki-header'); ?>
		<main id="main" class="site-main" role="main">
		<?php
		global $wpdb;

		//pagination 
		$current_page = get_query_var('page');
		if($current_page) {
			$from = ($current_page - 1) * get_option( 'posts_per_page' );
			$to = $current_page * get_option( 'posts_per_page' );
		} else {
			$from = 0;
			$to = get_option( 'posts_per_page' ); 
		}
		//pagination 
		$sql = "SELECT term_id, name, slug, tag_history.tagdate FROM (SELECT wp_term_relationships.term_taxonomy_id AS tagid, substr(wp_posts.post_date_gmt,1,10) AS tagdate FROM wp_term_relationships INNER JOIN wp_term_taxonomy ON wp_term_taxonomy.term_taxonomy_id=wp_term_relationships.term_taxonomy_id INNER JOIN wp_posts ON wp_posts.ID=wp_term_relationships.object_id WHERE taxonomy='post_tag' ORDER BY post_date_gmt DESC, wp_posts.post_title) AS tag_history INNER JOIN wp_terms ON wp_terms.term_id=tag_history.tagid GROUP BY tag_history.tagid ORDER BY tag_history.tagdate DESC LIMIT {$from},{$to}";
		$progress = $wpdb->get_results($sql);
		$sql = "SELECT term_id FROM (SELECT wp_term_relationships.term_taxonomy_id AS tagid, substr(wp_posts.post_date_gmt,1,10) AS tagdate FROM wp_term_relationships INNER JOIN wp_term_taxonomy ON wp_term_taxonomy.term_taxonomy_id=wp_term_relationships.term_taxonomy_id INNER JOIN wp_posts ON wp_posts.ID=wp_term_relationships.object_id WHERE taxonomy='post_tag' ORDER BY post_date_gmt DESC, wp_posts.post_title) AS tag_history INNER JOIN wp_terms ON wp_terms.term_id=tag_history.tagid GROUP BY tag_history.tagid ORDER BY tag_history.tagdate DESC";
		$tag_count = ceil(count($wpdb->get_results($sql))/10);
		$paginate_url = home_url()."/source/";
		foreach($progress as $tag_info)
		{
			$tag = get_tag($tag_info->term_id);
			get_template_part( 'content', 'istochniki' );
		}
		?>
		<?php
		$args = array(
			'base'         => str_replace( $big = 999999999, '%#%', $paginate_url.$big ),
			// 'format'       => '?page=%#%',
			'total'        => $tag_count,
			'current'      => max(1,get_query_var('page')),
			'show_all'     => False,
			'end_size'     => 1,
			'mid_size'     => 2,
			'prev_next'    => True,
			'prev_text'    => __('« Previous'),
			'next_text'    => __('Next »'),
			'type'         => 'list',
			'add_args'     => False,
			'add_fragment' => '',
			'before_page_number' => '',
			'after_page_number' => ''
		); 

      	

		?>
		<nav class="navigation pagination custom-page-wrapper" role="navigation">
			<div class="nav-links custom-pagination">
				<?php echo paginate_links( $args ); ?>
			</div>
		</nav>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
