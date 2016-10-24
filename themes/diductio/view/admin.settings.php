<table class="form-table">
    <tbody>
        <tr>
            <th scope="row">
                <label>Количество пользователей на странице людей</label>
            </th>
            <td>
                <input name="d_main_settings[user_per_page]" type="number" value="<?= $data['user_per_page'] ?>"
                       class="small-text">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label>Текст футера</label>
            </th>
            <td>
                <textarea name="d_main_settings[footer_text]" cols="50"><?=$data['footer_text']?></textarea>
            </td>
        </tr>
    </tbody>
</table>