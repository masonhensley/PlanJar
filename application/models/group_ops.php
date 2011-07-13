<?php

class Group_ops extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    // Echos a group entry.
    public function echo_group_entry($row, $option = '')
    {
        ?>
        <div class="group_entry" group_id="<?php echo($row->id); ?>">
            <div class="group_entry_left">
                <center>
                    <div class="group_picture"></div>

                    <div class="city">
                        City is pending
                    </div>
                </center>
            </div>

            <div class="group_entry_middle">
                <div class="group_name">
                    <?php echo($row->name); ?>
                </div>
            </div>
            <?php
            if ($option == 'remove following')
            {
                ?>
                <div class="remove_following">- Unfollow</div>
                <?php
            } else if ($option == 'add following')
            {
                ?>
                <div class="add_following">+ Follow</div>
                <?php
            } else if ($option == 'following')
            {
                ?>
                <div class="following">Following</div>
                <?php
            } else if ($option == 'remove joined')
            {
                ?>
                <div class="remove_joined">- Unjoin</div>
                <?php
            }
            ?>
        </div>
        <?php
    }

}
?>
