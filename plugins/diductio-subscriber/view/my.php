<?php if(is_user_logged_in()): ?>
<div class="stat-col">
	<a href="/my-subscriptions">
		<span class="label label-success <?=$data->class;?>">Подписки</span>
		<span class="label label-success"><?=$data->number_of_posts;?></span>
	</a>
</div>
<?php endif; ?>