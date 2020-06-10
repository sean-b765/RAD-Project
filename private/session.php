<?php

    require 'initialise.php';

    class Session
    {

        // boolean which reflects session status
        private $logged_in;

        public $user_name;
        public $user_email;


        function __construct()
        {
            session_start();
            // check login will detect if the user is already logged in, when switching pages
            $this->check_login();

        }//end __construct()


        public function login($user, $email)
        {
            if (user_exists($user, $email)) {
                $this->user_email = $_SESSION['email'] = $email;
                $this->user_name  = $_SESSION['username'] = $user;
                $this->logged_in  = true;
            } else {
                // do nothing
            }
            

        }//end login()


        public function logout()
        {
            // stop the PHP SESSION
            session_unset();
            session_destroy();
            $this->user_email = '';
            $this->user_name  = '';
            $this->logged_in  = false;

        }//end logout()


        public function is_logged_in() {
            return $this->logged_in;
        }


        private function check_login()
        {
            if (isset($_SESSION['email'])) {
                $this->user_email  = $_SESSION['email'];
                $this->logged_in = true;
            } else {
                unset($this->user_email);
                $this->logged_in = false;
            }

        }//end check_login()


    }//end class

    $session = new Session();

?>