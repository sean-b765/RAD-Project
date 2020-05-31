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
?>
