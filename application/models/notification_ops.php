<?php

class Notification_ops extends CI_Model
{

// Constructor.
    function __construct()
    {
        parent::__construct();
    }

    // Notifies the given users of the given subject id
    function notify($user_list, $type, $subject_id)
    {
        // Build the string containing the multiple entries to insert.
        $values_string = '';
        $type = $this->db->escape($type);
        $date = new DateTime();
        $date = $this->db->escape($date->format('Y-m-d'));
        foreach ($user_list as $user_id)
        {
            // Only add the notification if the originating user is not the current user
            if ($user_id != $this->ion_auth->get_user()->id)
            {
                $values_string .= "(DEFAULT, $user_id, " . $this->ion_auth->get_user()->id . ", $date, $type, $subject_id, DEFAULT), ";
            }
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
    function get_notifications()
    {
        $user_id = $this->ion_auth->get_user()->id;

        $query_string = "SELECT notifications.id, notifications.date, notifications.type, notifications.subject_id,
            notifications.viewed, user_meta.first_name, user_meta.last_name, user_meta.user_id
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


                $accepted = $this->deduce_accepted($row);
                $this->echo_notification($row, $accepted);
            }
        }
    }

    // Echos the HTML for one notification entry
    function echo_notification($row, $accepted)
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
                // Echo "follow back" instead of "accept" if necessary
                if ($row->type == 'follow_notif')
                {
                    echo('<div class="accept">Follow back</div>');
                } else
                {
                    echo('<div class="accept">Accept</div>');
                }
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
    function make_notification_text($notification_row)
    {
        // Take action depending on the type
        switch ($notification_row->type)
        {
            // Event invite
            case 'event_invite':
                $query_string = "SELECT places.name, events.date, events.title, events.id FROM plans
                LEFT JOIN events ON events.id = plans.event_id
                LEFT JOIN places ON events.place_id = places.id
                WHERE events.id = ?";
                $query = $this->db->query($query_string, array($notification_row->subject_id));
                $row = $query->row();

                $date = new DateTime($row->date);

                $event_text = '';
                if ($row->title != '')
                {
                    $event_text = '<b>' . $row->title . '</b> at ';
                }

                return '<b><a href="" class="user_notif_link" user_id="' . $notification_row->user_id . '">' .
                $notification_row->first_name . ' ' . $notification_row->last_name . '</a>' .
                '</b> has invited you to ' . $event_text . '<b>' . $row->name . '</b> ' .
                'on ' . $date->format('l') . ' the ' . $date->format('jS');

            // Group invite
            case 'group_invite':
                $query_string = "SELECT name FROM groups WHERE id = ?";
                $query = $this->db->query($query_string, array($notification_row->subject_id));
                $row = $query->row();

                return '<b><a href="" class="user_notif_link" user_id="' . $notification_row->user_id . '">' .
                $notification_row->first_name . ' ' . $notification_row->last_name . '</a>' .
                '</b> has invited you to join the group <b>' . $row->name . '</b>';

            // Follow notification
            case 'follow_notif':
                return '<b><a href="" class="user_notif_link" user_id="' . $notification_row->subject_id . '">' .
                $notification_row->first_name . ' ' . $notification_row->last_name . '</a>' .
                '</b> has followed you';
        }
    }

    // Updates the viewed status of the supplied notification id to the supplied value
    function update_notification_viewed($id, $value)
    {
        $query_string = "UPDATE notifications SET viewed = ? WHERE id = ?";
        $query = $this->db->query($query_string, array($value, $id));
    }

    // Accepts the notification given by id
    function accept_notification($id)
    {
        // Get the notification row
        $query_string = "SELECT type, subject_id FROM notifications WHERE id = ?";
        $query = $this->db->query($query_string, array($id));
        $row = $query->row();

        switch ($row->type)
        {
            // Event invite
            case 'event_invite':
                $this->update_notification_viewed($id, true);

                // Add a plan for the user to the specified event
                $this->load->model('plan_actions');
                $this->plan_actions->add_plan(array($this->ion_auth->get_user()->id, $row->subject_id));

                // Check if the user already has plans to that place at that time
                $plan_check = $this->plan_actions->unique_plan($row->subject_id);

                if ($plan_check === true)
                {
                    echo(json_encode(array('status' => 'success')));
                } else
                {
                    // Pre-existing plan. Return HTML for two options
                    $this->load->model('event_ops');
                    $choice_data = $this->event_ops->get_events_for_choice($row->subject_id, $plan_check);
                    echo(json_encode(array_merge(array('status' => 'conflict'), $choice_data)));
                }
                break;

            // Group invite
            case 'group_invite':
                $this->load->model('group_ops');
                $this->group_ops->follow_group($row->subject_id);
                $this->group_ops->join_group($row->subject_id);
                $this->update_notification_viewed($id, true);

                echo(json_encode(array('status' => 'success')));
                break;

            // Follow notification
            case 'follow_notif':
                $this->load->model('follow_ops');
                $this->follow_ops->add_user_following($row->subject_id);
                $this->update_notification_viewed($id, true);

                echo(json_encode(array('status' => 'success')));
                break;
        }
    }

    // Returns true if the user has accepted the notification (using data in $notif_row).
    public function deduce_accepted($notif_row)
    {
        $user_id = $this->ion_auth->get_user()->id;

        switch ($notif_row->type)
        {
            // Event invite
            case 'event_invite':
                $query_string = "SELECT * FROM plans WHERE user_id = ? AND event_id = ?";
                $query = $this->db->query($query_string, array(
                            $user_id,
                            $notif_row->subject_id));
                return $query->num_rows() > 0;

            // Group invite
            case 'group_invite':
                $this->load->model('group_ops');
                return $this->group_ops->user_is_joined($notif_row->subject_id);

            // Follow notification
            case 'follow_notif':
                $this->load->model('follow_ops');
                return $this->follow_ops->is_following($user_id, $notif_row->subject_id);
        }
    }

}
?>
