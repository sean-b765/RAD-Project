<?php

    require_once '../private/initialise.php';

    ?>

    <form id="password_form" method="post" style="text-align: center; top: 3vh;">
        Please set your password. Minimum 8 characters, atleast one letter and one number.<br/>
        <input type="text" name="password" class="password" placeholder="New Password" />
        <input type="submit" name="set_password" class="set_password" value="Set" />
    </form>

<?php

    if (isset($_POST['set_password']) && isset($_POST['password'])) {
        // set the users' password...
        //  if it reaches a minimum criteria: Minimum eight characters, at least one letter and one number
        if (preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $_POST['password'])) {
            set_password($_SESSION['id'], $_POST['password']);
            $session->authorise($_SESSION['id'], $_POST['password']);
            header("Refresh:0");
        } else {
            echo '<center>Password does not meet requirements.</center>';
        }
    }

?>