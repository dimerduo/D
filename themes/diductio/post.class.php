<?php

/**
 * Class Post. Adding all features/functional according to the post.
 */
class Post extends Diductio
{
	/**
	 * @var
	 */
	public $settings;

	/**
	 * Post constructor.
	 */
	public function __construct()
	{
		$this->settings = Diductio::gi()->settings;
		$this->addActions();
		$this->addFilters();
	}

	/**
	 * Actions function
	 */
	public function addActions()
	{
		add_action('before_delete_post', Array($this, 'onPostDelete'));
		add_action('post_updated', Array($this, 'onPostUpdate'), 10, 3);
		add_action('init', Array($this,'rewrite_mode'));
	}

	/**
	 * Filters function
	 */
	public function addFilters()
	{
		/* Remove core WordPress filter. */
		remove_filter('term_link', '_post_format_link', 10);

		add_filter('term_link', Array($this, 'customPostTypes'), 10, 4);

		/* Remove the core WordPress filter. */
		remove_filter('request', '_post_format_request');
		/* Add custom filter. */
		add_filter('request', Array($this, 'my_post_format_request'));

		add_filter('gettext_with_context', Array($this, 'rename_post_formats_2'), 10, 4);

	}

	/**
	 * Function run after post update in Admin Panel
	 * @param $post_ID - ID of post
	 * @param $post_after - After changed post content
	 * @param $post_before - Before changing post content
	 */
	public function onPostUpdate($post_ID, $post_after, $post_before)
	{
		global $wpdb;

		$words_array = str_word_count($post_after->post_content, 1);
		$accordion_count = 0;
		foreach ($words_array as $key => $value) {
			if ($value == 'accordion-item') {
				$accordion_count++;
			}
		}
		if ($accordion_count % 2 == 0) {
			$accordion_count = $accordion_count / 2;
		}

		if ($accordion_count == 0) {
			$accordion_count = get_post_meta($post_ID, 'publication_count', true);
		}

		if ($accordion_count) {
			$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
			$sql = "UPDATE {$table_name} SET `lessons_count` = {$accordion_count} ";
			$sql .= " WHERE `post_id` = {$post_ID}";
			$wpdb->query($sql);
		}

	}

	/**
	 * Function run when some post is delete from trashbox
	 * @param $post_id - ID of the post
	 */
	public function onPostDelete($post_id)
	{
		Diductio::gi()->deleteStatByPost($post_id);
	}

	/**
	 * Customize WordPress Post format slugs
	 * @param $link Link
	 * @param $term Term Type
	 * @param $taxonomy Taxonomy Type
	 * @return mixed|string link
	 */
	public function customPostTypes($link, $term, $taxonomy)
	{
		global $wp_rewrite;

		if ('post_format' != $taxonomy) {
			return $link;
		}

		$slugs = $this->my_get_post_format_slugs();

		$slug = str_replace('post-format-', '', $term->slug);
		$slug = isset($slugs[$slug]) ? $slugs[$slug] : $slug;

		if ($wp_rewrite->get_extra_permastruct($taxonomy)) {
			$link = str_replace("/{$term->slug}", '/' . $slug, $link);
		} else {
			$link = add_query_arg('post_format', $slug, remove_query_arg('post_format', $link));
		}

		return $link;
	}

	/**
	 * New post format links
	 * @return array with customized format slugs
	 */
	function my_get_post_format_slugs()
	{

		$slugs = array(
			'aside' => 'knowledge',
			'chat' => 'poll',
			'gallery' => 'task',
			'image' => 'test'
		);

		return $slugs;
	}

	/**
	 * Post format request
	 * Get from: http://justintadlock.com/archives/2012/09/11/custom-post-format-urls
	 * @param $qvs
	 * @return mixed
	 */
	function my_post_format_request($qvs)
	{
		if (!isset($qvs['post_format'])) {
			return $qvs;
		}

		$slugs = array_flip($this->my_get_post_format_slugs());

		if (isset($slugs[$qvs['post_format']])) {
			$qvs['post_format'] = 'post-format-' . $slugs[$qvs['post_format']];
		}

		$tax = get_taxonomy('post_format');

		if (!is_admin()) {
			$qvs['post_type'] = $tax->object_type;
		}

		return $qvs;
	}

	/**
	 * Change translation of hte Post Formats
	 * @param $translation
	 * @param $text
	 * @param $context
	 *
	 * @return mixed
	 */
	function rename_post_formats_2($translation, $text, $context)
	{

		//change translations in the Admin Panel
		if ($context == 'Post format') {
			$names = array(
				'Aside' => 'Знание',
				'Chat' => 'Голосование',
				'Image' => 'Тест',
				'Gallery' => 'Задача',
				'Изображения' => ''
			);
			$translation = str_replace(array_keys($names), array_values($names), $text);
		}

		//change translations of the Post format archive title
		if($context == 'post format archive title') {
			$post_format_titles = array(
				'Asides' => "Знания",
				'Chats' => "Голосования",
				'Images' => "Тесты",
				'Galleries' => "Задачи"
			);

			$translation = str_replace(array_keys($post_format_titles), array_values($post_format_titles), $text);
		}

		return $translation;
	}

	/**
	 * Rewrite rules of the theme
	 */
	public function rewrite_mode()
	{
		add_rewrite_tag('%username%', '([^&]+)');
		add_rewrite_rule('^(subscription)/([^/]*)/?', 'index.php?pagename=$matches[1]&username=$matches[2]', 'top');
		add_rewrite_rule('^(comments)/([^/]*)/?', 'index.php?pagename=$matches[1]&username=$matches[2]', 'top');
	}
}

?>