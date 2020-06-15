<!--
    Sign Up/Sign In
        by Sean

    Contains only the login form and script to log in

    use include 'login.php'; to include this script on the page
-->
<form class="login" method="post">
    <?php

        // require the initialise.php script,
        //  no matter what directory we are in
        if (file_exists('private/initialise.php')) {
            // we are in RAD-Project
            require_once 'private/initialise.php';
        } else if (file_exists('../private/initialise.php')) {
            // we are in RAD-Project/public
            require_once '../private/initialise.php';
        }          
        

        // if we are already logged in, add a logout button and display a welcome
        if ($session->is_logged_in()) {
            echo 'Welcome, ' . $_SESSION['username'] . '<br/>';

            // check if user needs to set their password
            if ($_SESSION['group'] === 'Admin' || $_SESSION['group'] === 'ACME') {
                if (user_needs_to_set_password($_SESSION['email'])) {
                    // link to members page or public/members page 
                    if (file_exists('public/members.php')) {
                        echo '<a href="public/members.php" style="color: red; text-decoration: none; font-weight: bold;">Change Password</a><br/>';
                    } else {
                        echo '<a href="members.php" style="color: red; text-decoration: none; font-weight: bold;">Change Password</a><br/>';
                    }
                }
            }

            echo '  <input type="submit" class="login-button logout" name="logout" value="Log Out" />';

            // when pressing logout, stop session and reload page
            if (isset($_POST['logout'])) {
                $session->logout();
                header('Location: ' . $_SERVER["PHP_SELF"]);
            }
        } else {
            // has not already submitted
            if (!$submitted = isset($_GET['user']) && isset($_GET['email']) !== '') {
                // show the Username Email fields
                echo '<input type="text" name="username" placeholder="Username" /><br/>
                <input type="email" name="email" placeholder="Email" /><br/>';
            } else { 
                // the URL will contain their username/email from 
                //      the last redirect (user did not exist)
                // add radio buttons to form,
                //  and we will get them to sign up

                // input containing values from URL
                $user   = $_GET['user'];
                $email  = $_GET['email'];

                echo '  <input type="text" name="username" value="' . $user . '" /><br/>
                        <input type="email" name="email" value="' . $email . '" /><br/>';

                // radio buttons for newsletter options
                echo '  <input type="radio" name="newsletter" value="Monthly">
                            <label for="monthly">Monthly Newsletter</label><br/>';
                echo '  <input type="radio" name="newsletter" value="None">
                            <label for="monthly">Don\'t send me news</label><br/>';

            
            }

            // handle login form submission here
            if (isset($_POST['submitLogin'])) {
                // get form variables
                $username = $_POST['username'];
                $email = $_POST['email'];

                // if the user has already submitted their user/email, 
                //  they are given mailing options
                if ($submitted) {
                    // get mailing option
                    if (isset($_POST['newsletter'])) {
                        $mailing_option = $_POST['newsletter'];

                        // the user is to be added!
                        if (add_user($username, $email, $mailing_option) === true) {
                            echo 'Added new user.<br/>';
                            // log in the session, and redirect to search page
                            $session->login($username, $email);
                            header('Location: ' . FULL_URL . 'public/search.php');
                        } else {
                            echo 'Error when adding new user.<br/>';
                        }
                    }
                } else {

                    // username and email should not be empty
                    if ($username !== '' && $email !== '') {
                        // username and email wasn't empty, proceed
                        if (user_exists($username, $email)) {
                            // log in the session, and redirect to SEARCH page
                            $session->login($username, $email);
                            header('Location: ' . FULL_URL . 'public/search.php');
                        } else {
                            // the user does not exist, so
                            //  redirect to same page but provide variables in URL for form input to retrieve again
                            header('Location: ' . $_SERVER["PHP_SELF"] . '?user=' . $username . '&email=' . $email);
                        }
                    }
                }
            }

            echo '<input type="submit" name="submitLogin" class="login-button submit" value="Log In"/>';

        } // end if
        
    ?>
</form>