<div class="settings_wrapper">
    <table>
        <tr>
            <td>Upload a picture</td>
            <td>
                <form id="image_upload" enctype="multipart/form-data">
                    <input name="image" id="image" type="file"/>
                    <br/>
                    <input id="upload_submit" type="submit" value="Upload" style="display: none;"/>
                </form>
                <div id="image_upload_alt" style="display: none;">Crop your image to the right...</div>
            </td>
        </tr>

        <form id="change_password">
            <tr>
                <td>Change your password</td>

                <td style="padding: 5px;">
                    <div class="in-field_block">
                        <label for="old_password">Old password</label>
                        <input type="password" id="old_password" name="old_password"/>
                    </div>
                </td>
            </tr>

            <tr>
                <td></td>
                <td style="padding: 5px;">
                    <div class="in-field_block">
                        <label for="new_password">New password</label>
                        <input type="password" id="new_password" name="new_password"/>
                    </div>
                </td>
            </tr>

            <tr style="display: none;">
                <td></td>
                <td style="padding: 5px;">
                    <div class="in-field_block">
                        <label for="new_password_1">Re-enter new password</label>
                        <input type="password" id="new_password_1" name="new_password_1"/>
                    </div>
                </td>
            </tr>

            <tr style="display: none;">
                <td></td>
                <td>
                    <input id="submit_new_password" type="submit" value="Change"/>
                    <?php echo(anchor('auth/forgot_password', 'Forgot password?')); ?>
                </td>
            </tr>
        </form>

        <tr>
            <td>Email notifications</td>
            <td>
                <form id="email_notifications">
                    <label>
                        <input type="checkbox" name="email_notif" id="email_notif"/>All
                    </label>
                </form>
            </td>
        </tr>

        <tr>
            <td colspan="2"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center;">
                <br/><br/>
                Want more settings? Leave us some feedback on the left.
            </td>
        </tr>
    </table>
</div>

<div class="right">
    <h2>Click and drag to crop your image. Click the 'upload' button when you're done.</h2>
    <img id="preview_image" src=""/>
    <form id="crop_image">
        <input type="hidden" id ="x1" name="x1"/>
        <input type="hidden" id ="y1" name="y1"/>
        <input type="hidden" id ="x2" name="x2"/>
        <input type="hidden" id ="y2" name="y2"/>
        <input type="submit" value="Upload" id="upload_crop" style="display: none;"/>
</div>