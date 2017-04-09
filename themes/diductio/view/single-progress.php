<?php
/**
 * Вьюшка отвечает за вывод одного прогресса
 */
 $class = '';
 if($percent == 100) {
     $class == 'progress-bar-success';
 }
?>
<div class="progress">
    <div class="progress-bar <?=$class;?>" role="progressbar" aria-valuenow="<?=$percent;?>" aria-valuemin="0" aria-valuemax="100" style="width:<?=$percent;?>%;">
        <?=$percent;?> %
    </div>
</div>

