<?php

    require 'initialize.php';

    class Session
    {

        private $logged_in;

        public $username;


        function __construct()
        {
            session_start();
            // check login will detect if the user is already logged in, when switching pages
            $this->check_login();

        }//end __construct()


        public function login($name)
        {
            $this->username     = $_SESSION['username'] = $name;
            $this->logged_in = true;

        }//end login()


        public function logout()
        {
            // stop the PHP SESSION
            session_unset();
            session_destroy();
            $this->username     = '';
            $this->logged_in = false;

        }//end logout()


        private function check_login()
        {
            if (isset($_SESSION['username'])) {
                $this->username  = $_SESSION['username'];
                $this->logged_in = true;
            } else {
                unset($this->username);
                $this->logged_in = false;
            }

        }//end check_login()


    }//end class

    $session = new Session();

?>