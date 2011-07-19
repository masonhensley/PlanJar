<?php

class Notification_ops extends CI_Model
{

// Constructor.
    function __construct()
    {
        parent::__construct();
    }

    // Sends a notification to everyone in the groups specified in the group list.
    // The passed groups must be joined by the user to work correctly.
    public function notify_joined_groups($group_list, $type, $subject_id)
    {
        $user = $this->ion_auth->get_user();
        $originator_id = $user->id;

        // Create the WHERE clauses to find all users in the group list.
        $or_clauses = '';
        foreach ($group_list as $group_id)
        {
            $or_clauses .= "group_id = $group_id OR ";
        }

        if ($or_clauses != '')
        {
            // Trim the trialing OR
            $or_clauses = substr($or_clauses, 0, -4);

            // Escape all vars
            $originator_id = $this->db->escape($originator_id);
            $date = $this->db->escape(date('Y-m-d'));
            $type = $this->db->escape($type);
            $subject_id = $this->db->escape($subject_id);

            // Get a list of all users joined to at least one of the specified groups
            $query_string = "SELECT user_joined_id FROM group_relationships WHERE ($or_clauses) AND user_joined_id <> 'NULL'";
            $query = $this->db->query($query_string);

            // Build the string containing the multiple entries to insert.
            $values_string = '';
            foreach ($query->result() as $row)
            {
                $user_id = $this->db->escape($row->user_joined_id);
                if ($user_id != $originator_id)
                {
                    $values_string .= "(DEFAULT, $user_id, $originator_id, $date, $type, $subject_id, DEFAULT), ";
                }
            }
            if ($values_string != '')
            {
                // Trim the trailing comma and space
                $values_string = substr($values_string, 0, -2);

                // Add all the notifications.
                $query_string = "INSERT IGNORE INTO notifications VALUES $values_string";
                $query = $this->db->query($query_string);
            }
        }
    }

    // Sends a notification to everyone specified in the user list
    public function notify_users($user_list, $type, $subject_id)
    {
        $user = $this->ion_auth->get_user();
        $originator_id = $user->id;

        // Escape all vars
        $originator_id = $this->db->escape($originator_id);
        $date = $this->db->escape(date('Y-m-d'));
        $type = $this->db->escape($type);
        $subject_id = $this->db->escape($subject_id);


        // Build the string containing the multiple entries to insert.
        $values_string = '';
        foreach ($user_list as $user_id)
        {
            $values_string .= "(DEFAULT, $user_id, $originator_id, $date, $type, $subject_id, DEFAULT), ";
        }
        if ($values_string != '')
        {
            // Trim the trailing comma and space
            $values_string = substr($values_string, 0, -2);

            // Add all the notifications.
            $query_string = "INSERT IGNORE INTO notifications VALUES $values_string";
            $query = $this->db->query($query_string);
        }
    }

    public function get_notifications()
    {
        $user_id = $this->ion_auth->get_user()->id;

        $query_string = "SELECT notifications.id, notifications.date, notifications.type, notifications.subject_id, notifications.viewed, user_meta.first_name, user_meta.last_name
            FROM notifications LEFT JOIN user_meta ON notifications.originator_id = user_meta.user_id
            WHERE notifications.user_id = ? ORDER BY notifications.viewed ASC";
        $query = $this->db->query($query_string, array($user_id));

        if ($query->num_rows() == 0)
        {
            echo('No recent notifications');
        } else
        {
            foreach ($query->result() as $row)
            {
                $this->echo_notification($row);
            }
        }
    }

    public function echo_notification($row)
    {
        $notification_text = $this->make_notification_text($row);

        $class = 'notification_entry';
        if ($row->viewed == true)
        {
            $class .= ' unviewed';
        }
        ?>
        <div class="<?php echo($class); ?>" notif_id="<?php echo($row->id); ?>">
            <?php echo($notification_text); ?>
        </div>
        <?php
    }

    private function make_notification_text($notification_row)
    {
        if ($notification_row->type == 'plan_invite')
        {
            $query_string = "SELECT places.name, plans.date FROM plans LEFT JOIN places ON plans.place_id = places.id
                WHERE plans.id = ?";
            $query = $this->db->query($query_string, array($notification_row->subject_id));
            $row = $query->row();

            $date = new DateTime($row->date);

            return $notification_row->first_name . ' ' . $notification_row->last_name . ' has invited you to ' . $row->name . ' on ' . $date->format('l') . ' the ' . $date->format('jS');
        }
    }

}
?>
