<?php    
    // query the database
    function query($sql)
    {
        global $database;
        return $database->query($sql);
    }

    // return the num of rows in the movies table
    function total_rows()
    {
        global $database;

        return mysqli_num_rows($database->query("SELECT * FROM movies;"));
    }

    // easy query function to add a new user into the members table
    function add_user($user, $email, $mailing_option) {
        // check that email is not already in use,
        //  then add
        $sql = "SELECT * FROM members WHERE Email='" . $email . "'";
        $result = query($sql);

        global $database;

        // if the email does not exist in the db, add user
        if (mysqli_num_rows($result) === 0) {
            // there CANNOT be an email already in the database
            $sql = "INSERT INTO members (Name, Email, MailingOption) VALUES ('" . $user .  "', '" . $email . "', '" . $mailing_option . "');";

            // query data base to insert new member
            $return_result = query($sql);

            // get last id of insert query
            $last_id = mysqli_insert_id($database->conn);
            //  add the new member into USERS group on sign up
            $sql = "INSERT INTO group_members (GroupID, MemberID) VALUES (2, '" . $last_id . "')";
            query($sql);
            
            return $return_result;
        } else {
            // return false, as we cannot add a duplicate email
            return false;
        }
    }

    // insert OR update a member's rating of a movie
    function insert_member_rating($stars, $movie_id, $member_id) {
        $sql = 'SELECT * FROM member_ratings WHERE MemberID="' . $member_id . '" AND MovieID="' . $movie_id . '"';
        $result = query($sql);

        // update the movie ratings fields
        update_movie_ratings($movie_id, $stars);
        
        if (mysqli_num_rows($result) === 0) {
            // insert
            $sql = 'INSERT INTO member_ratings (MemberID, MovieID, Stars) VALUES ('. $member_id .', ' . $movie_id . ', ' . $stars . ')';
            return query($sql);
        } else {
            // don't insert, update
            $sql = 'UPDATE member_ratings SET Stars=' . $stars . ' WHERE MovieID=' . $movie_id . ' AND MemberID=' . $member_id;
            return query($sql);
        }
    }

    // check if email already exists
    function user_exists($email) {
        global $database;
        
        // check if $email already exists in members table
        $sql = "SELECT * FROM members WHERE Email='" . $email . "'";

        $result = $database->query($sql);
        if (mysqli_num_rows($result) > 0) {
            return true;
        } else {
            // if the SQL query returend 0 rows, the user does not exist
            return false;
        }
        
    }

    function sanitize_user() {

    }

    function sanitize_email() {
        
    }

    function edit_user($id, $new_mailing_option) {
        $monthly = "Monthly";
        $news    = "Flash News";
        $both    = "Both";
        $none    = "None";

        
    }

    // this function is called along side insert_member_rating
    function update_movie_ratings($movie_id, $stars_given) {
        $sql = 'SELECT * FROM movies 
                WHERE ID="' . $movie_id . '"';
        $result = query($sql);
        $row = mysqli_fetch_assoc($result);

        // update the total int rating
        $totalIntegerRating = $row['TotalIntegerRating'];
        $totalIntegerRating += $stars_given;

        // increment the number of ratings
        $numOfRatings = $row['NumberOfRatings'];
        $numOfRatings += 1;

        // calculate the avg rating
        $avgRating = $totalIntegerRating / $numOfRatings;

        $sql = 'UPDATE movies 
                SET TotalIntegerRating="' . $totalIntegerRating . '", NumberOfRatings="' . $numOfRatings . '", AvgRating="' . $avgRating . '"
                WHERE ID="' . $movie_id . '"';

        return query($sql);
    }

    // Get a member's star rating of a movie
    function get_user_movie_rating($member_id, $movie_id) {
        $sql = 'SELECT Stars FROM member_ratings WHERE MemberID=' . $member_id . ' AND MovieID=' . $movie_id;
        return query($sql);
    }

    // Quick get all users function
    function get_all_users() {
        $sql = "SELECT * FROM members";
        return query($sql);
    }

    // Get all users, and their group name
    function get_all_users_groups() {
        $sql = "SELECT members.*, groups.Name AS Group_Name FROM members
                INNER JOIN group_members ON members.ID = group_members.MemberID
                INNER JOIN groups ON groups.ID = group_members.GroupID";
        return query($sql);
    }

    // Get the group of the user
    function get_users_group($email) {
        $sql = "SELECT members.*, groups.Name AS Group_Name FROM members
                INNER JOIN group_members ON members.ID = group_members.MemberID
                INNER JOIN groups ON groups.ID = group_members.GroupID
                WHERE members.email = '" . $email . "'";
        return query($sql);
    }

    // Returns true if the user needs to set their password
    function user_needs_to_set_password($email) {
        $sql = "SELECT * FROM members
        WHERE Email='" . $email . "'";

        $result = query($sql);

        $row = mysqli_fetch_assoc($result);

        if (trim($row['Password']) === '' || !isset($row['Password'])) {
            return true;
        } else {
            return false;
        }
    }

    // Set the user password
    function set_password($user_id, $password) {
        $sql = 'UPDATE members 
                SET members.Password="' . $password . '" 
                WHERE ID="' . $user_id . '"';
        return query($sql);
    }

    // Takes user_id,
    // returns password of the user_id
    function get_password($user_id) {
        $sql = 'SELECT members.Password FROM members WHERE ID="' . $user_id . '"';
        $result = query($sql);
        return mysqli_fetch_assoc($result)['Password'];
    }

    // Will return true if user is ACME or Admin group
    function user_is_admin($email) {
        $sql = "SELECT members.*, groups.Name AS Group_Name FROM members
                INNER JOIN group_members ON members.ID = group_members.MemberID
                INNER JOIN groups ON groups.ID = group_members.GroupID
                WHERE members.email = '" . $email . "'";
        $result = query($sql);
        // fetch associative array
        $row = mysqli_fetch_assoc($result);

        // if user is Admin or ACME, return true
        //  otherwise, return false
        if ($row['Group_Name'] === 'Admin' || $row['Group_Name'] === 'ACME') {
            return true;
        } else {
            return false;
        }
    }

    // will return time difference between $lastUpdate and server time
    function get_time_difference($lastUpdate) {
        // get CURRENT TIME in the DATABASE
        $sql = "SELECT CURRENT_TIME As Time";
        $result = query($sql);
        $server_time = mysqli_fetch_assoc($result)['Time'];
        // HH:MM:SS

        $sql = 'SELECT TIMEDIFF("' . $server_time . '", "' . $lastUpdate . '") AS Difference';
        $result = mysqli_fetch_assoc(query($sql));
        return $result['Difference'];
    }

    // takes: SQL datetime
    //  returns SQL time
    function get_time($date_time) {
        // formatted as [DD-MM-YYYY hh:mm:ss]
        //  string can be split by the space
        return explode(" ", $date_time)[1];
    }

    // takes time formatted as "HH:MM:SS"
    //  and returns as "1h 20m 3s"
    function get_time_string($time) {
        // split by :
        $H_M_S = explode(":", $time);

        $time_string = "";
        if ($H_M_S[0] != "00") {
            $time_string .= intval($H_M_S[0]) . "h ";
        }

        if ($H_M_S[1] != "00") {
            $time_string .= intval($H_M_S[1]) . "m ";
        }
        
        if ($H_M_S[2] != "00") {
            $time_string .= intval($H_M_S[2]) . "s";
        }

        if ($time_string === "") {
            $time_string = "0s";
        }

        return $time_string;
    }

    function close_db() {
        global $database;
        $database->conn->close();
    }

    // Check for a registered change in the database
    function check_for_changes() {
        $sql = 'SELECT Value FROM status WHERE ID=1';
        $result = query($sql);
        if ($result = mysqli_fetch_assoc($result)) {
            return $result['Value'];
        }
    }
    
    // register a change in the database
    function register_change() {
        $sql = 'UPDATE status SET Value=Value+1 WHERE ID=1';
        query($sql);
    }
?>
