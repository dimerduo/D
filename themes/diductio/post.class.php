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
	}

	public function onPostUpdate()
	{

	}

	public function onPostDelete($post_id)
	{
		Diductio::gi()->deleteStatByPost($post_id);
	}
}

?>