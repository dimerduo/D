<?php 

class Post extends Diductio
{
	public $settings;

	public function __construct()
	{
		$this->settings = Diductio::gi()->settings;
		$this->addActions();
	} 
	
	public function addActions()
	{
		add_action( 'before_delete_post', Array($this, 'onPostDelete'));
		add_action( 'post_updated', Array($this, 'onPostUpdate'), 10, 3 );
	}

	public function onPostUpdate($post_ID, $post_after, $post_before)
	{
		global $wpdb;

	    $words_array = str_word_count($post_after->post_content, 1);
	    $accordion_count = 0; 
	    foreach ($words_array as $key => $value) {
	    	if($value=='accordion-item') $accordion_count++;
	    }
	    if($accordion_count % 2 == 0){
	    	$accordion_count = $accordion_count / 2;
	    }
		
		if( $accordion_count == 0) {
		   $accordion_count = get_post_meta($post_ID, 'publication_count', true);	
		}

		if($accordion_count) {
			$table_name = $wpdb->get_blog_prefix() . 'user_add_info';
			$sql   = "UPDATE {$table_name} SET `lessons_count` = {$accordion_count} ";
			$sql  .= " WHERE `post_id` = {$post_ID}";
			$wpdb->query($sql);
		}

	}

	public function onPostDelete($post_id)
	{
		Diductio::gi()->deleteStatByPost($post_id);
	}
}

?>