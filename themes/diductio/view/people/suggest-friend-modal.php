
<div class="stat-col">
    <div class="add-to-favor-wrapper">
                <span class="wpfp-span">
                    <a id="suggest-to-user" data-toggle="modal" data-target="#suggestUser">Добавить пользователя</a>
                </span>
    </div>
</div>

<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="suggestUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Добавить пользователя</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php foreach ($suggesting_users as $user): ?>
                        <div class="col-md-3">
                            <label for="user-<?=$user->ID;?>">
                                <input <?php if($user->is_selected): ?> checked="checked" <?php endif;?> id="user-<?=$user->ID;?>" data-user="<?=$user->ID;?>" class="suggested-user" type="checkbox" value="test">
                                <?=$user->display_name;?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button onclick="suggestToUser.save()" type="button" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</div>