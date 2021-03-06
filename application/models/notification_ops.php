<?php

class Notification_ops extends CI_Model
{

// Constructor.
    function __construct()
    {
        parent::__construct();
    }

    // Notifies the given users and groups of the given subject id
    function notify($user_list, $group_list, $type, $subject_id)
    {
        // Build the string containing the multiple entries to insert.
        $values_string = '';
        $date = new DateTime();
        $date = $this->db->escape($date->format('Y-m-d'));

        // Add individuals
        foreach ($user_list as $user_id)
        {
            // Only add the notification if the originating user is not the current user
            if ($user_id != $this->ion_auth->get_user()->id)
            {
                $notified = (integer) $this->deduce_notified($type, $subject_id, $user_id, $this->ion_auth->get_user()->id);
                $accepted = (integer) $this->deduce_accepted($type, $subject_id, $user_id, $this->ion_auth->get_user()->id);
                $values_string .= "(DEFAULT, $user_id, DEFAULT, " . $this->ion_auth->get_user()->id . ", $date, '$type', $subject_id, $notified, $accepted), ";
                if (!($accepted || $notified))
                {
                    // Send an email notification if the notification would show up as new (i.e. not previously accepted)
                    $this->send_email_reminder($type, $user_id, $subject_id);
                }
            }
        }

        // Add groups
        $this->load->model('group_ops');
        foreach ($group_list as $group_id)
        {
            $joined_users = $this->group_ops->get_group_members($group_id);

            // Create notifications for each joined user
            foreach ($joined_users as $joined_user)
            {
                // Only add the notification if the originating user is not the current user
                if ($joined_user != $this->ion_auth->get_user()->id)
                {
                    $notified = (integer) $this->deduce_notified($type, $subject_id, $joined_user, $this->ion_auth->get_user()->id);
                    $accepted = (integer) $this->deduce_accepted($type, $subject_id, $joined_user, $this->ion_auth->get_user()->id);
                    $values_string .= "(DEFAULT, $joined_user, $group_id, " . $this->ion_auth->get_user()->id . ", $date, '$type', $subject_id, $notified, $accepted), ";
                    if (!($accepted || $notified))
                    {
                        // Send an email notification if the notification would show up as new (i.e. not previously accepted)
                        $this->send_email_reminder($type, $joined_user, $subject_id, $group_id);
                    }
                }
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
    function display_all_notifications()
    {
        $user_id = $this->ion_auth->get_user()->id;

        $query_string = "SELECT notifications.id, notifications.date, notifications.type, notifications.subject_id,
            notifications.viewed, notifications.accepted, user_meta.first_name, user_meta.last_name, user_meta.user_id, groups.name AS group_name
            FROM notifications LEFT JOIN user_meta ON notifications.originator_id = user_meta.user_id
            LEFT JOIN groups ON notifications.group_id = groups.id
            WHERE notifications.user_id = ? ORDER BY notifications.viewed ASC, notifications.date DESC";
        $query = $this->db->query($query_string, array($user_id));

        if ($query->num_rows() == 0)
        {
            ?>
            <br/><font style="font-size:17px; font-style:italic; color:gray;">No unread notifications</font>
            <?php
        } else
        {
            foreach ($query->result() as $row)
            {
                $this->echo_notification($row);
            }
        }
    }

    function display_unread_notifications()
    {
        $user_id = $this->ion_auth->get_user()->id;

        $query_string = "SELECT notifications.id, notifications.date, notifications.type, notifications.subject_id,
            notifications.viewed, notifications.accepted, user_meta.first_name, user_meta.last_name, user_meta.user_id, groups.name AS group_name
            FROM notifications LEFT JOIN user_meta ON notifications.originator_id = user_meta.user_id
            LEFT JOIN groups ON notifications.group_id = groups.id
            LEFT JOIN events ON notifications.subject_id = events.id AND notifications.type = 'event_invite'
            WHERE notifications.user_id = $user_id AND notifications.viewed = 0
            AND (notifications.type <> 'event_invite' OR events.date >= CURDATE())
            ORDER BY notifications.viewed ASC, notifications.date DESC";

        $query = $this->db->query($query_string);

        if ($query->num_rows() == 0)
        {
            ?>
            <br/>
            <font style="font-size:17px; font-style:italic; color:gray;">No unread notifications</font>
            <?php
        } else
        {
            foreach ($query->result() as $row)
            {
                $this->echo_notification($row);
            }
        }
    }

    // Echos the HTML for one notification entry
    function echo_notification($row)
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
                    <?php echo($this->load_profile->insert_profile_picture($row->user_id, 50)); ?>
                </div>
            </div>

            <div class="middle">
                <?php echo($notification_text); ?>
            </div>

            <?php
            if (!$row->accepted)
            {
                // Echo "follow back" instead of "accept" if necessary
                if ($row->type == 'follow_notif')
                {
                    echo('<div class="accept">follow</div>');
                } else
                {
                    echo('<div class="accept">Accept</div>');
                }
            } else
            {
                echo('<div class="responded"><b>Responded</b></div>');
            }
            if ($row->viewed)
            {
                echo('<div class="mark_read">Mark unread</div>');
            } else
            {
                echo('<div class="mark_read">Dismiss</div>');
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
                $query_string = "SELECT places.name, events.date, events.title, events.id
                FROM plans
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

                // Figure out whether to use you or the group name
                if ($notification_row->group_name == NULL)
                {
                    $you = 'you';
                } else
                {
                    $you = $notification_row->group_name;
                }

                return '<b><a href="" class="user_notif_link" user_id="' . $notification_row->user_id . '">' .
                        $notification_row->first_name . ' ' . $notification_row->last_name . '</a>' .
                        "</b> has invited $you to " . $event_text . '<b>' . $row->name . '</b> ' .
                        'for ' . $date->format('l') . ' the ' . $date->format('jS');

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

            case 'join_group_request':
                $query_string = "SELECT name FROM groups WHERE id = ?";
                $query = $this->db->query($query_string, array($notification_row->subject_id));
                $row = $query->row();

                return '<b><a href="" class="user_notif_link" user_id="' . $notification_row->user_id . '">' .
                        $notification_row->first_name . ' ' . $notification_row->last_name . '</a>' .
                        '</b> has requested to join <b>' . $row->name . '</b> ';
        }
    }

    // Updates the viewed status of the supplied notification id to the supplied value
    // If find_all is set, all related notifications are also changed
    function update_notification_viewed($id, $value, $find_all = false)
    {
        if ($find_all)
        {
            // Get the notification type
            $query_string = "SELECT originator_id, type, subject_id FROM notifications WHERE id = ?";
            $query = $this->db->query($query_string, array($id));
            $row = $query->row();

            // Update all similar notifications
            if ($row->type == 'join_group_request')
            {
                $query_string = "UPDATE notifications SET viewed = ? WHERE type = ? AND originator_id = ? AND subject_id = ?";
                $query = $this->db->query($query_string, array(
                    $value,
                    $row->type,
                    $row->originator_id,
                    $row->subject_id));
            } else
            {
                $query_string = "UPDATE notifications SET viewed = ? WHERE type = ? AND user_id = ? AND subject_id = ?";
                $query = $this->db->query($query_string, array(
                    $value,
                    $row->type,
                    $this->ion_auth->get_user()->id,
                    $row->subject_id));
            }
        } else
        {
            $query_string = "UPDATE notifications SET viewed = ? WHERE id = ?";
            $query = $this->db->query($query_string, array($value, $id));
        }
    }

    // Updates the accepted status of the supplied notification id to the supplied value
    // If find_all is set, all related notifications are also changed
    function update_notification_accepted($id, $value, $find_all = false)
    {
        if ($find_all)
        {
            // Get the notification type
            $query_string = "SELECT originator_id, type, subject_id FROM notifications WHERE id = ?";
            $query = $this->db->query($query_string, array($id));
            $row = $query->row();

            // Update all similar notifications
            if ($row->type == 'join_group_request')
            {
                $query_string = "UPDATE notifications SET accepted = ? WHERE type = ? AND originator_id = ? AND subject_id = ?";
                $query = $this->db->query($query_string, array(
                    $value,
                    $row->type,
                    $row->originator_id,
                    $row->subject_id));
            } else
            {
                $query_string = "UPDATE notifications SET accepted = ? WHERE type = ? AND user_id = ? AND subject_id = ?";
                $query = $this->db->query($query_string, array(
                    $value,
                    $row->type,
                    $this->ion_auth->get_user()->id,
                    $row->subject_id));
            }
        } else
        {
            $query_string = "UPDATE notifications SET accepted = ? WHERE id = ?";
            $query = $this->db->query($query_string, array($value, $id));
        }
    }

    // Accepts the notification given by id
    function accept_notification($id)
    {
        // Get the notification row
        $query_string = "SELECT originator_id, type, subject_id FROM notifications WHERE id = ?";
        $query = $this->db->query($query_string, array($id));
        $row = $query->row();

        switch ($row->type)
        {
            // Event invite
            case 'event_invite':
                $this->update_notification_viewed($id, true, true);
                $this->update_notification_accepted($id, true, true);

                // Add a plan for the user to the specified event
                $this->load->model('plan_actions');
                echo($this->plan_actions->add_plan(array($this->ion_auth->get_user()->id, $row->subject_id), false, true));

                break;

            // Group invite
            case 'group_invite':
                $this->load->model('group_ops');
                $this->group_ops->join_group($row->subject_id);
                $this->update_notification_viewed($id, true, true);
                $this->update_notification_accepted($id, true, true);

                echo(json_encode(array('status' => 'success')));
                break;

            // Follow notification
            case 'follow_notif':
                $this->load->model('follow_ops');
                $this->follow_ops->add_user_following($row->subject_id, true);
                $this->update_notification_viewed($id, true);
                $this->update_notification_accepted($id, true);

                echo(json_encode(array('status' => 'success')));
                break;

            // Join group request
            case 'join_group_request':
                $this->load->model('group_ops');
                $this->group_ops->join_group($row->subject_id, $row->originator_id);
                $this->update_notification_viewed($id, true, true);
                $this->update_notification_accepted($id, true, true);

                echo(json_encode(array('status' => 'success')));
                break;
        }
    }

    // Returns true if the user has already been invited to the subject id
    function deduce_notified($type, $subject_id, $user_id, $originator_id)
    {
        switch ($type)
        {
            // Cascade these into one
            case 'event_invite':
            case 'group_invite':
            case 'follow_notif':
                $query_string = "SELECT id FROM notifications WHERE user_id = ? AND type = ? AND subject_id = ?";
                $query = $this->db->query($query_string, array(
                    $user_id,
                    $type,
                    $subject_id));

                return $query->num_rows() > 0;

            // Join group request
            case 'join_group_request':
                $query_string = "SELECT id FROM notifications WHERE type = ? AND originator_id = ? AND subject_id = ?";
                $query = $this->db->query($query_string, array($type, $originator_id, $subject_id));
                return $query->num_rows() > 0;
        }
    }

    // Returns true if the user has already accepted the subject id
    function deduce_accepted($type, $subject_id, $user_id, $originator_id)
    {
        switch ($type)
        {
            // Event invitation
            case 'event_invite':
                $query_string = "SELECT id FROM plans WHERE user_id = ? AND event_id = ?";
                $query = $this->db->query($query_string, array($user_id, $subject_id));

                return $query->num_rows() > 0;

            // Group invite
            case 'group_invite':
                $query_string = "SELECT id FROM group_relationships WHERE user_joined_id = ? AND group_id = ?";
                $query = $this->db->query($query_string, array($user_id, $subject_id));

                return $query->num_rows() > 0;

            // Follow notification
            case 'follow_notif':
                $query_string = "SELECT id FROM friend_relationships WHERE user_id = ? AND  follow_id = ?";
                $query = $this->db->query($query_string, array($user_id, $subject_id));

                return $query->num_rows() > 0;

            // Join group request
            case 'join_group_request':
                // A join_group_request can never be accepted already
                return false;
        }
    }

    // Sends a notificatin email based on the given type and data
    function send_email_reminder($type, $user_id, $subject_id, $group_id = false)
    {
        $user = $this->ion_auth->get_user($user_id);
        $this_user = $this->ion_auth->get_user();

        if (eval('return $user->' . $type . ';') == '1')
        {
            // Email setup
            $this->load->library('email');
            $this->email->clear();
            $this->email->from('noreply@planjar.com', 'PlanJar');
            $this->email->to($user->email);

            // See if this user has an unsubscribe alias. If not, create one.
            $query_string = "SELECT alias FROM unsubscribe WHERE user_id = ?";
            $query = $this->db->query($query_string, array($user->id));
            if ($query->num_rows() == 0)
            {
                // An md5 works fine as the alias. Add it to the db
                $unsubscribe_id = md5($user->id);
                $this->db->query("INSERT INTO unsubscribe VALUES (?, ?)", array($user->id, $unsubscribe_id));
            } else
            {
                $unsubscribe_id = $query->row()->alias;
            }

            // Get event info
            $query_string = "SELECT user_meta.first_name, user_meta.last_name, events.title, places.name, events.date
                            FROM events LEFT JOIN user_meta ON events.originator_id = user_meta.user_id
                            JOIN places ON events.place_id = places.id
                            WHERE events.id = ?";
            $query = $this->db->query($query_string, array($subject_id));
            $event_row = $query->row();

            // Compute a first/last name
            $first_last = $this_user->first_name . ' ' . $this_user->last_name;

            // Create the image string
            $this->load->model('load_profile');
            ob_start();
            $this->load_profile->insert_profile_picture($this_user->id, 33);
            $image = ob_get_clean();

            $body_string = $user->first_name . ",<br/><br/>";
            switch ($type)
            {
                case 'event_invite':
                    if ($group_id === false)
                    {
                        $you = 'you';
                    } else
                    {
                        // Get the group name
                        $row = $this->db->query("SELECT name FROM groups WHERE id = ?", array($group_id))->row();
                        $you = $row->name;
                    }

                    // Set the subject
                    $this->email->subject("$first_last has invited $you to " . $event_row->name);

                    // Get the date string
                    $date = new DateTime($event_row->date);
                    $date = $date->format('l') . ' the ' . $date->format('jS');

                    // Capture the body
                    if ($group_id !== false)
                    {
                        $you = anchor('dashboard/groups', $you);
                    }
                    $body_string .= "<b>$first_last</b>" .
                            " has invited <b>$you</b> to <b>" . $event_row->title . '</b>';
                    if ($event_row->title != '')
                    {
                        $body_string .= ' at ';
                    }
                    $body_string .= '<b>' . $event_row->name . '</b>' . " for <b>$date</b>.";
                    break;

                case 'follow_notif':
                    // Set the subject
                    $this->email->subject("$first_last has followed you");

                    // Capture the body
                    $body_string .= "<b>$first_last</b>" .
                            ' has followed you.';
                    break;

                case 'group_invite':
                    // Get the group name
                    $row = $this->db->query("SELECT name FROM groups WHERE id = ?", array($subject_id))->row();

                    // Set the subject
                    $this->email->subject("$first_last has invited you to join " . $row->name);

                    // Capture the body
                    $body_string .= "<b>$first_last</b>" .
                            ' has invited you to join <b>' . $row->name . '</b>.';
                    break;

                case 'join_group_request':
                    // Get the group name
                    $row = $this->db->query("SELECT name FROM groups WHERE id = ?", array($subject_id))->row();

                    // Set the subject
                    $this->email->subject("$first_last has requested to join " . $row->name);

                    // Capture the body
                    $body_string .= "<b>$first_last</b>" .
                            ' has requested to join <b>' . $row->name . '</b>. This email has been sent to multiple users. Only one of you needs to accept.';
                    break;
            }

            $this->email->message($this->create_email_notification($body_string, $unsubscribe_id, $image));
            $this->email->send();
        }
    }

    // Returns the html for an email notification as a string
    function create_email_notification($notif_text, $unsubscribe_id, $image)
    {
        $data = array('notif_text' => $notif_text, 'unsubscribe_id' => $unsubscribe_id, 'image' => $image);

        return $this->load->view('email_notification_view', $data, true);
    }

}
?>
