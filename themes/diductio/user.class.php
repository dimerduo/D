<?php 
class User extends Diductio
{
	public $settings;

	function __construct()
	{
		$this->settings = Diductio::gi()->settings;
		$this->addActions();
	}

	function addActions()
	{
		add_action( 'deleted_user', Array($this, 'afterUserDelete'));
	}

	function afterUserDelete($user_id)
	{
		Diductio::gi()->deleteStatByUser($user_id);
	}

}