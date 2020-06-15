<?php

    require_once '../private/initialise.php';

    ?>

    <form id="password_form" method="post" style="text-align: center; top: 3vh;">
        Please enter your password.<br/>
        <input type="text" name="password" class="password" placeholder="Enter Password" />
        <input type="submit" name="set_password" class="set_password" value="Go" />
    </form>

<?php

    if (isset($_POST['set_password']) && isset($_POST['password'])) {
        // set the users' password...
        //  if it reaches a minimum criteria: Minimum eight characters, at least one letter and one number
        if ($session->authorise($_SESSION['id'], $_POST['password'])) {
            // correct password, refresh
            header("Refresh:0");
        } else {
            // incorrect password
            echo 'Incorrect password. Try again.';
        }
    }

?>