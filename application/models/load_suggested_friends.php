<?php

class Load_suggested_friends extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function suggested_friends($user_id, $grad_year, $school_id)
    {

        $user = $this->ion_auth->get_user();

        // new query that selects all the followers of your followers
        $connection_query = "
            SELECT DISTINCT friend_relationships.follow_id FROM
                (SELECT friend_relationships.follow_id AS friend_id FROM friend_relationships 
                WHERE friend_relationships.user_id=$user->id)new_user
            JOIN friend_relationships ON friend_relationships.user_id=new_user.friend_id 
                AND friend_relationships.follow_id <> $user->user_id  
";
        $result = $this->db->query($connection_query);
        $result_array = $result->row_array();

        // query to pull all your classmates
        $schoolmate_query = "
            SELECT DISTINCT user_meta.user_id FROM user_meta
            WHERE user_meta.school_id=$user->school_id 
                AND user_meta.grad_year=$user->grad_year
                AND user_meta.user_id <> $user->user_id
            ";
        $result = $this->db->query($schoolmate_query);
        $result_array_2 = $result->row_array();

        // query to pull all your groupmates
        $groupmate_query = "
            SELECT DISTINCT group_relationships.user_joined_id FROM
                (SELECT group_relationships.group_id AS id FROM group_relationships
                WHERE group_relationships.user_joined_id=$user->id)group_joined_id
            JOIN group_relationships ON group_relationships.group_id=group_joined_id.id
            WHERE group_relationships.user_joined_id <> $user->id
            ";
        $result = $this->db->query($groupmate_query);
        $result_array_3 = $result->row_array();

        
        
        // combine the 3 arrays here into one array called "connection array"
        $connection_array = array_merge($result_array, $result_array_2, $result_array_3);
        var_dump($connection_array, $connection_query, $schoolmate_query, $groupmate_query);
        
        
        
        $this->load->model('follow_ops');
        $following_ids = $this->follow_ops->get_following_ids();
        $suggested_friends = array();

        foreach ($connection_array as $id)
        {
            if (!in_array($id, $following_ids))
            {
                $suggested_friends[] = $id;
            }
        }

        $suggested_friends = array_count_values($suggested_friends);
        asort($suggested_friends);
        $suggested_friends = array_reverse($suggested_friends, TRUE);

        $display_limit = 10;

        $result = $this->generate_suggested_friends($suggested_friends);
        $this->display_suggested_friends($result, $suggested_friends, 'suggested', $display_limit);
    }

    function display_suggested_friends($query_result, $suggested_friends=null, $options, $display_limit) //this function displays the suggested friends
    {
        $this->load->model('follow_ops');
        $count = 0;
        foreach ($query_result->result() as $row)
        {
            if ($count < $display_limit)
            {
                $this->follow_ops->echo_user_entry($row, $options, $suggested_friends);
            }
            $count++;
        }
    }

    function generate_suggested_friends($suggested_friends)
    {


        // this query pulls all the information needed to display suggested friends
        $query = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school " .
                "FROM user_meta LEFT JOIN school_data ON user_meta.school_id = school_data.id " .
                "WHERE  ";
        $mutual_friend_count = array(); // keep track of mutual friends to display
        foreach ($suggested_friends as $id => $count)
        {
            if (!in_array($id, $following_ids))
            {
                $query .= "user_meta.user_id=$id OR ";
            }
        }
        $query = substr($query, 0, strlen($query) - 3); // This cuts off the last "OR" and adds ")"
        $query .= "ORDER BY CASE user_meta.user_id ";
        $counter = 1;
        foreach ($suggested_friends as $id => $count)
        {
            $query .= "WHEN $id THEN $counter ";
            $counter++;
        }
        $query .= "END";
        return $this->db->query($query);
    }

    /*
      function show_suggested_school_friends($display_limit, $already_following)
      {

      $user = $this->ion_auth->get_user();
      $user_id = $user->id;
      $grad_year = $user->grad_year;
      $school_id = $user->school_id;

      $query = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school
      FROM user_meta
      LEFT JOIN school_data ON user_meta.school_id=school_data.id
      WHERE school_id=$school_id AND user_id!=$user_id";

      foreach ($already_following as $friend_id) // this makes sure the user hasn't already been shown
      {
      $query .= " AND user_id!=$friend_id";
      }

      $date1 = date("Y");
      $date2 = $date1 + 4;
      $query .= " AND (user_meta.grad_year BETWEEN $date1 AND $date2) ";
      $query .= "ORDER BY (user_meta.grad_year=$grad_year) DESC LIMIT 0, 30";

      $result = $this->db->query($query);

      $options = "suggested_school";
      if ($result->num_rows() > 0)
      {
      echo "<div style=\"padding-top:5px; text-align:center;padding-top:10px;padding-bottom:10px;font-style:italic;border-top:1px solid #AAA;\">Expanded search results to include people from your school</div>";
      }
      $this->display_suggested_friends($result, null, $options, 15);
      }
     * 
     */
}

?>