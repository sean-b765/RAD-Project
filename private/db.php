<?php

    require_once 'db_credentials.php';

    error_reporting(E_ALL ^ E_DEPRECATED);

class Database
{
    public $conn;

    function __construct()
    {
        $this->connect(); // connect when instantiated
    }

    function connect()
    {
        $this->conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
        if (!$this->conn) {
            // conn == null -> error
            die('DB Connection Failed ' . mysqli_error());
        } else {
            // conn successfull
            $db_select = mysqli_select_db($this->conn, DB_NAME);
            if (!$db_select) {
                die('DB Selection Failed ' . mysqli_error());
            }
        }
    } // end connect

    // query function
    public function query($sql)
    {
        $result = mysqli_query($this->conn, $sql);
        return $result;
    } // end query
}

    // initialise database object, this will always be instantiated when the page requires db.php
    $database = new Database();

?>
