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
        global $database;
    }

    function user_exists($email) {
        // check if $email already exists in members table

        return false;
    }

    function edit_user($mailing_option) {
        $monthly = "MONTHLY";
        $news    = "NEWSFLASH";
        $both    = "BOTH";
        $none    = "NONE";

        
    }
?>
