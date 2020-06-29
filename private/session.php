<?php

    require 'initialise.php';

    class Session
    {

        // boolean which reflects session status
        private $logged_in;

        public $user_name;
        // user email is unique for Member accounts
        public $user_email;
        public $user_id;
        public $user_password;

        public $user_group;
        

        function __construct()
        {
            session_start();
            // check login will detect if the user is already logged in, when switching pages
            $this->check_login();

        }//end __construct()


        public function login($user, $email)
        {
            // get ID from user, email to store with Email, Name in SESSION
            $result = get_users_group($email);

            $row = mysqli_fetch_assoc($result);

            if (user_exists($email)) {
                $this->user_email = $_SESSION['email'] = $email;
                $this->user_name  = $_SESSION['username'] = $user;
                $this->user_id    = $_SESSION['id'] = $row['ID'];
                $this->user_group = $_SESSION['group'] = $row['Group_Name'];
                $this->logged_in  = true;
            } else {
                // do nothing
            }
            

        }//end login()


        public function logout()
        {
            // stop the PHP SESSION, unset & clear variables
            session_unset();
            session_destroy();
            $this->user_email    = '';
            $this->user_name     = '';
            $this->user_id       = '';
            $this->user_password = '';
            $this->logged_in     = false;

        }//end logout()


        // function to authorise admin accounts
        public function authorise($user_id, $password) {
            if ($this->is_logged_in()) {
                $real_password = get_password($user_id);
                // if admin's entered password equals their actual password
                if ($real_password === $password) {
                    $this->user_password = $_SESSION['password'] = $password;
                    return true;
                }
            }
            return false;
        }


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