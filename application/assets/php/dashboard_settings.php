<div class="settings_wrapper">
    <table>
        <tr>
            <td>Upload a picture</td>
            <td>
                <form id="image_upload">
                    <input name="file_path" id="file_path" type="file"/>
                </form>
            </td>
        </tr>

        <form id="change_password">
            <tr>
                <td>Change your password</td>

                <td>
                    <div class="in-field_block">
                        <label for="old_password">Old password</label>
                        <input type="text" id="old_password" name="old_passwor"/>
                    </div>
                </td>
            </tr>

            <tr style="display: none;">
                <td></td>
                <td>
                    <div class="in-field_block">
                        <label for="new_password">New password</label>
                        <input type="text" id="new_password" name="new_password"/>
                    </div>
                </td>
            </tr>

            <tr style="display: none;">
                <td></td>
                <td>
                    <div class="in-field_block">
                        <label for="new_password_1">Re-enter new password</label>
                        <input type="text" id="new_password_1" name="new_password_1"/>
                    </div>
                    <input type="submit" value="Change"/>
                </td>
            </tr>
        </form>
    </table>
</div>