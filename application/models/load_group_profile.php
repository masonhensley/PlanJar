<?php

class Load_group_profile extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function _load_group_profile($group_id)
    {
        $group_info = $this->_get_group_details($group_id);
        $this->_display_profile($group_info);
    }

    function _get_group_details($group_id)
    {
        $query = "SELECT school_id, name, description, privacy FROM groups WHERE id=$group_id";
        $result = $this->db->query($query);
        return $result;
    }

    function _display_profile($group_info)
    {
        ?>
        <div class="profile_top_bar">
            <div class="profile_picture">
        <?php $this->_insert_profile_picture(); ?>
            </div>
            <div class="profile_user_information"><?php
        echo "<br/>" . $group_info->name ;
        echo $row->school . " ('" . $year_display . ")<br/>";
        ?>
            </div>
        </div>

        <?php
    }

    function _insert_profile_picture()
    {
        $logo_text = "logo_" . rand(1, 25) . ".png";
        ?>
        <img src="/application/assets/images/logos/<?php echo $logo_text; ?>" />
        <?php
    }
}
?>
