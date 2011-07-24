<?php

class Notification_ops extends CI_Model
{

// Constructor.
    function __construct()
    {
        parent::__construct();
    }

    // Notifies the given users of the given subject id
    // Optionally accepts an array of group ids to include in the notifications
    function notify($user_list, $type, $subject_id)
    {
        // Build the string containing the multiple entries to insert.
        $values_string = '';
        $type = $this->db->escape($type);
        $date = new DateTime();
        $date = $this->db->escape($date->format('Y-m-d'));
        foreach ($user_list as $user_id)
        {
            $values_string .= "(DEFAULT, $user_id, " . $this->ion_auth->get_user()->id . ", $date, $type, $subject_id, DEFAULT), ";
        }

        // Only continue if there were results
        if ($values_string != '')
        {
            // Trim the trailing comma and space
            $values_string = substr($values_string, 0, -2);

            // Add all the notifications.
            $query_string = "INSERT IGNORE INTO notifications VALUES $values_string";
            $query = $this->db->query($query_string);
        }
    }

    // Returns HTML for the user's recent notifications
    public function get_notifications()
    {
        $user_id = $this->ion_auth->get_user()->id;

        $query_string = "SELECT notifications.id, notifications.date, notifications.type, notifications.subject_id,
            notifications.viewed, user_meta.first_name, user_meta.last_name
            FROM notifications LEFT JOIN user_meta ON notifications.originator_id = user_meta.user_id
            WHERE notifications.user_id = ? ORDER BY notifications.viewed ASC, notifications.date DESC";
        $query = $this->db->query($query_string, array($user_id));

        if ($query->num_rows() == 0)
        {
            echo('No recent notifications');
        } else
        {
            foreach ($query->result() as $row)
            {


                //$accept = $this->deduce_accepted($row);
                $this->echo_notification($row, true);
            }
        }
    }

    // Echos the HTML for one notification entry
    public function echo_notification($row, $accepted)
    {
        $this->load->model('load_profile');

        $notification_text = $this->make_notification_text($row);

        $class = 'notification_entry';
        if ($row->viewed == false)
        {
            $class .= ' unviewed';
        }
        ?>
        <div class="<?php echo($class); ?>" notif_id="<?php echo($row->id); ?>">
            <div class="left">
                <div class="picture">
                    <?php echo($this->load_profile->insert_profile_picture()); ?>
                </div>
            </div>

            <div class="middle">
                <?php echo($notification_text); ?>
            </div>

            <?php
            if (!$accepted)
            {
                echo('<div class="accept">Accept</div>');
            }
            if ($row->viewed)
            {
                echo('<div class="mark_read">Mark as unread</div>');
            } else
            {
                echo('<div class="mark_read">Mark as read</div>');
            }
            ?>
        </div>
        <?php
    }

    // Returns appropriate notification text for the supplied notification row
    private function make_notification_text($notification_row)
    {
        if ($notification_row->type == 'plan_invite')
        {
            $query_string = "SELECT places.name, events.date FROM plans
                LEFT JOIN events ON events.id = plans.event_id
                LEFT JOIN places ON events.place_id = places.id
                WHERE events.id = ?";
            $query = $this->db->query($query_string, array($notification_row->subject_id));
            $row = $query->row();

            $date = new DateTime($row->date);

            return '<b>' . $notification_row->first_name . ' ' . $notification_row->last_name .
            '</b> has invited you to <b>' . $row->name . '</b> ' .
            'on ' . $date->format('l') . ' the ' . $date->format('jS');
        } else if ($notification_row->type == 'group_invite')
        {
            $query_string = "SELECT name FROM groups WHERE id = ?";
            $query = $this->db->query($query_string, array($notification_row->subject_id));
            $row = $query->row();

            return '<b>' . $notification_row->first_name . ' ' . $notification_row->last_name . '</b> ' .
            'has invited you to the group <b>' . $row->name . '</b>';
        }
    }

    // Updates the viewed status of the supplied notification id to the supplied value
    public function update_notification_viewed($id, $value)
    {
        $query_string = "UPDATE notifications SET viewed = ? WHERE id = ?";
        $query = $this->db->query($query_string, array($value, $id));
    }

//    // Accepts the notification given by id
//    public function accept_notification($id)
//    {
//        // Get the notification row
//        $query_string = "SELECT id, type, subject_id FROM notifications WHERE id = ?";
//        $query = $this->db->query($query_string, array($id));
//        $row = $query->row();
//
//        switch ($row->type)
//        {
//            case 'plan_invite':
//                $this->load->model('plan_actions');
//                $this->plan_actions->copy_plan($row->subject_id, $this->ion_auth->get_user()->id);
//                $this->update_notification_viewed($row->id, true);
//                break;
//            case 'group_invite':
//                break;
//        }
//    }
//
//    // Returns true if the user has accepted the notification (data in $notif_row).
//    public function deduce_accepted($notif_row)
//    {
//        switch ($notif_row->type)
//        {
//            case 'plan_invite':
//                break;
//            case 'group_invite':
//                break;
//        }
//    }
}
?>
