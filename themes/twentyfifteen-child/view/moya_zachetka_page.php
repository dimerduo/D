<?php if(!empty($learned_lessons)): ?>
	<div class="wpfp-span">
		<ul>
			<?php foreach($learned_lessons as $lesson_key => $lesson_value): ?>
				<li>
					<a href="<?=$lesson_value['post_url']?>"><?=$lesson_value['post_title']?></a>
					<div class="progress">
		  				<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%;">100 %</div>
					</div>
				</li>		
			<?php endforeach; ?>
		</ul>
	</div>
<?php else: ?>
	Ваша зачетка пуста.
<?php endif; ?>