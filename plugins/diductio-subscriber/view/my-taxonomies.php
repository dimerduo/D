<?php if(!empty($data->taxonomies)):?>
	<ul class="my-taxonomies">	
		<?php foreach ($data->taxonomies as $key => $value): ?>
			<li>
				<a class="link-style-1" href="<?=$value['url'];?>"><?=$value['name'];?></a>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>