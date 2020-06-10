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
        // check that email is not already in use, call user_exists()
        //  then add
        $sql = "INSERT INTO members (name, email, MailingOption) VALUES ('" . $user .  "', '" . $email . "', '" . $mailing_option . "');";
        return query($sql);
    }

    function user_exists($user, $email) {
        global $database;
        
        // check if $email already exists in members table
        $sql = "SELECT * FROM members WHERE name='" . $user . "' AND email='" . $email . "'";

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
?>
