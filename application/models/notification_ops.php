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
                $accepted = (integer) $this->deduce_accepted($type, $subject_id, $user_id);
                $values_string .= "(DEFAULT, $user_id, DEFAULT, " . $this->ion_auth->get_user()->id . ", $date, '$type', $subject_id, $accepted, $accepted), ";
                $this->send_email_reminder($type, $user_id, $subject_id);
            }
        }

        // Add groups
        $this->load->model('group_ops');
        foreach ($group_list as $group_id)
        {
            $joined_users = $this->group_ops->get_users($group_id);

            // Create notifications for each joined user
            foreach ($joined_users as $joined_user)
            {
                if ($joined_user != $this->ion_auth->get_user()->id)
                {
                    $accepted = (integer) $this->deduce_accepted($type, $subject_id, $joined_user);
                    $values_string .= "(DEFAULT, $joined_user, $group_id, " . $this->ion_auth->get_user()->id . ", $date, '$type', $subject_id, $accepted, $accepted), ";
                    $this->send_email_reminder($type, $joined_user, $subject_id, $group_id);
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
            WHERE notifications.user_id = $user_id AND notifications.viewed=0 ORDER BY notifications.viewed ASC, notifications.date DESC";

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
                    <?php echo($this->load_profile->insert_profile_picture()); ?>
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
                    echo('<div class="accept">Follow back</div>');
                } else
                {
                    echo('<div class="accept">Accept</div>');
                }
            } else
            {
                echo('<div style="color:black; float:right; margin-right:15px;"><b>Responded</b></div><br/>');
            }
            if ($row->viewed)
            {
                echo('<div class="mark_read">Mark unread</div>');
            } else
            {
                echo('<div class="mark_read">Mark read</div>');
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
        }
    }

    // Updates the viewed status of the supplied notification id to the supplied value
    // If find_all is set, all related notifications are also changed
    function update_notification_viewed($id, $value, $find_all = false)
    {
        if ($find_all)
        {
            // Get the notification type
            $query_string = "SELECT type, subject_id FROM notifications WHERE id = ?";
            $query = $this->db->query($query_string, array($id));

            // Update all similar notifications
            $query_string = "UPDATE notifications SET viewed = ? WHERE type = ? AND user_id = ? AND subject_id = ?";
            $query = $this->db->query($query_string, array(
                $value,
                $query->row()->type,
                $this->ion_auth->get_user()->id,
                $query->row()->subject_id));
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
            $query_string = "SELECT type, subject_id FROM notifications WHERE id = ?";
            $query = $this->db->query($query_string, array($id));

            // Update all similar notifications
            $query_string = "UPDATE notifications SET accepted = ? WHERE type = ? AND user_id = ? AND subject_id = ?";
            $query = $this->db->query($query_string, array(
                $value,
                $query->row()->type,
                $this->ion_auth->get_user()->id,
                $query->row()->subject_id));
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
        $query_string = "SELECT type, subject_id FROM notifications WHERE id = ?";
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
                $this->update_notification_viewed($id, true, true);
                $this->update_notification_accepted($id, true, true);

                echo(json_encode(array('status' => 'success')));
                break;

            // Follow notification
            case 'follow_notif':
                $this->load->model('follow_ops');
                $this->follow_ops->add_user_following($row->subject_id);
                $this->update_notification_viewed($id, true);
                $this->update_notification_accepted($id, true);

                echo(json_encode(array('status' => 'success')));
                break;
        }
    }

    // Returns true if the user has accepted the notification (using data in $notif_row).
    function deduce_accepted($type, $subject_id, $user_id)
    {
        switch ($type)
        {
            // Event invite
            case 'event_invite':
                $query_string = "SELECT * FROM plans WHERE user_id = ? AND event_id = ?";
                $query = $this->db->query($query_string, array(
                    $user_id,
                    $subject_id));

                return $query->num_rows() > 0;

            // Group invite
            case 'group_invite':
                $this->load->model('group_ops');
                return $this->group_ops->user_is_joined($subject_id, $user_id);

            // Follow notification
            case 'follow_notif':
                $this->load->model('follow_ops');
                return $this->follow_ops->is_following($user_id, $subject_id);
        }
    }

    // Sends a notificatin email based on the given type and data
    function send_email_reminder($type, $user_id, $subject_id, $group_id = false)
    {
        $user = $this->ion_auth->get_user($user_id);

        if ($user->email_notif == '1')
        {
            // Email setup
            $this->load->library('email');
            $this->email->clear();
            $this->email->from('noreply@planjar.com', 'PlanJar');
            $this->email->to($user->email);

            // Get event info
            $query_string = "SELECT user_meta.first_name, user_meta.last_name, events.title, places.name, events.date
                            FROM events LEFT JOIN user_meta ON events.originator_id = user_meta.user_id
                            JOIN places ON events.place_id = places.id
                            WHERE events.id = ?";
            $query = $this->db->query($query_string, array($subject_id));
            var_dump($this->db->last_query());
            $event_row = $query->row();

            switch ($type)
            {
                case 'event_invite':
                    if ($group_id === false)
                    {
                        $you = 'you';
                    } else
                    {
                        // Get the group name
                        $row = $query("SELECT name FROM groups WHERE id = ?", array($group_id));
                        $you = $row->name;
                    }

                    // Set the subject
                    if ($event_row->first_name != NULL)
                    {
                        $originator = $event_row->first_name . ' ' . $event_row->last_name . ' has invited';
                        $this->email->subject($event_row->first_name . ' ' . $event_row->last_name . " has invited $you to an event");
                    } else
                    {
                        if ($you == 'you')
                        {
                            $this->email->subject("You have been invited to an event");
                        } else
                        {
                            $this->email->subject("$you has been invited to an event");
                        }
                    }

                    // Get the date string
                    $date = new DateTime($event_row->date);
                    $date = $date->format('l') . ' the ' . $date->format('jS');

                    // Capture the body
                    $body_string = 'Hi ' . $user->first_name . ',<br/><br/>';
                    $body_string .= "$originator has invited $you to " . $event_row->title;
                    if ($event_row->title != '')
                    {
                        $body_string .= ' at ';
                    }
                    $body_string .= $event_row->name . " for $date.";

                    break;

                case 'follow_notif':
                    $body_string = '';
                    break;

                case 'group_invite':
                    $body_string = '';
                    break;
            }

            $this->email->message($body_string);
            $this->email->send();
        }
    }

    // Returns the html for an email notification as a string
    function create_email_notification($notif_text)
    {
        ob_start();
        ?>
        <html>
            <style type="text/css">
                .wrapper {
                    width: 450px;
                    height: auto;
                    background-color: #ECECEC;
                    font-family: helvetica;
                    font-size: 14pt;
                }

                .wrapper img {
                    padding: 15px;
                }

                .content {
                    padding: 15px;
                    margin-bottom: 50px;
                }

                .bottom_links {
                    font-size: 10pt;
                    padding: 15px;
                }
            </style>

            <body>
                <div class="wrapper">
                    <a href="<?php echo(base_url()); ?>">
                        <img src="<?php echo(base_url() . APPPATH . 'assets/images/pj_logo_white_text.png'); ?>"/>
                    </a>
                    <hr/>

                    <div class="content">
                        <?php echo($notif_text); ?>
                        <br/><br/>
                        Click <?php echo(anchor('dashboard/notifications', 'here')); ?> to respond.
                        <br/><br/><br/><br/>
                        This notification was up-to-date as of <?php echo(date('g:i a') . ' on ' . date('l, F jS Y')); ?>.
                    </div>

                    <hr/>
                    <div class="bottom_links">
                        <?php echo(anchor('', 'PlanJar | Home')); ?> - Don't want to receive these emails?
                        Click <?php echo(anchor('dashboard/settings', 'here')); ?> to change your email settings.
                    </div>
                </div>
            </body>
        </html>
        <?php
        return ob_get_clean();
    }

}
?>
