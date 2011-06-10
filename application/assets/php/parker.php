$this->load->database();

        $needle = $this->input->get('needle');
        $search_terms = explode(' ', $needle);

        $latitude = $this->input->get('latitude');
        $longitude = $this->input->get('longitude');

        $like_clauses = '';
        foreach ($search_terms as $term)
        {
            $term = $this->db->escape_like_str($term);
            $like_clauses .= "places.name LIKE '%%$term%%' OR ";
        }
        $like_clauses = substr($like_clauses, 0, -4);

        // Check the PlanJar database. (Query string courtesy of Wells.)
        $query_string = "SELECT places.id, ((ACOS(SIN(? * PI() / 180) * SIN(places.latitude * PI() / 180) 
  + COS(? * PI() / 180) * COS(places.latitude * PI() / 180) * COS((? - places.longitude) 
  * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance, places.name, place_categories.category 
  FROM places LEFT JOIN place_categories ON places.category_id=place_categories.id
        WHERE ($like_clauses) ORDER BY distance ASC LIMIT ?";
        $query = $this->db->query($query_string, array($latitude, $latitude, $longitude, 10));

        // Return a JSON array.
        foreach ($query->result_array() as $row)
        {
            // Append to the return array.
            $return_array[] = $row;
        }

        // Check for no results.
        if (!isset($return_array))
        {
            // Search the Google API.
            $data = array(
                'location' => $latitude . ',' . $longitude,
                'name' => 'needle',
                'radius' => 2000,
                'sensor' => false,
                'key' => 'AIzaSyCYUQ0202077EncqTobwmahQzAY8DwGqa4'
            );
            $response = http_get('https://maps.googleapis.com/maps/api/place/search/json', array('timeout' => 2), $data);
            echo($response);
        } else
        {
            //echo(json_encode($return_array));
        }