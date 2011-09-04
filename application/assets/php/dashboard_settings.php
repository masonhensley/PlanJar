<div class="settings_wrapper">
    <table>
        <tr>
            <td><font style="color:gray">Upload a picture</font></td>
            <td>
                <form id="image_upload" enctype="multipart/form-data">
                    <input name="image" id="image" type="file"/>
                    <br/>
                    <input type="submit" value="Upload"/>
                </form>
                <div id="image_upload_alt" style="display: none;">Crop your image to the right...</div>
                <div style="position:absolute; top: 100px; height:1px; width:200px;"></div>
            </td>
        </tr>

        <tr style="height: 30px;"><td></td></tr>

        <form id="change_password">
            <tr>
                <td><font style="color:gray">Change your password</font></td>
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
                    <div style="position:absolute; top:200px;height:1px; width:200px;"></div>
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

        <tr style="height: 30px;"><td></td></tr>

        <form id="email_notifications">
            <tr>
                <td><font style="color:gray">Email me when...</font></td>
                <td>
                    <label><input type="checkbox" name="event_invite" id="event_invite"/>I'm invited to an event</label>
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <label><input type="checkbox" name="follow_notif" id="follow_notif"/>Somebody follows me</label>
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <label><input type="checkbox" name="group_invite" id="group_invite"/>I'm invited to a group</label>
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <label><input type="checkbox" name="join_group_request" id="join_group_request"/>People want to join groups I'm in</label>
                </td>
            </tr>
        </form>

        <tr>
            <td colspan="2"></td>
        </tr>

        <tr>
            <td colspan="2" style="text-align: center;">
                <br/><br/>
                <font style="color:gray">Want more settings? Leave us some feedback on the left.</font>
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