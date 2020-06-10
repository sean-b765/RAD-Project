<html>

    <head>

        <title>Movies - Home</title>
        <link rel="stylesheet" href="style.css" />

    </head>

    <body>
        <!-- Navigation -->
        <ul id="nav">

            <li class="active"><a href="index.php">Home</a></li>
            <li><a href="public/search.php">Search</a></li>

        </ul>

        <!-- Sign Up/Sign In 
                by Sean -->
        <form class="login" method="post">
            <?php
                require_once 'private/initialise.php';

                if ($session->is_logged_in()) {
                    echo 'Welcome, ' . $_SESSION['username'] . '<br/>';
                    echo '  <input type="submit" class="login-button logout" name="logout" value="Log out" />';

                    if (isset($_POST['logout'])) {
                        $session->logout();
                        header('Location: ' . $_SERVER["PHP_SELF"]);
                    }
                } else {
                    if (!$submitted = isset($_GET['user']) && isset($_GET['email']) !== '') {
                        echo '<input type="text" name="username" placeholder="Username:" /><br/>
                        <input type="email" name="email" placeholder="Email:" /><br/>';
                    } else {
                        // add radio buttons to form, as the user does not exist,
                        //  and we will get them to sign up
                        // also alter the input to include the user/email from URL
                        echo '  <input type="text" name="username" value="' . $_GET['user'] . '" /><br/>
                                <input type="email" name="email" value="' . $_GET['email'] . '" /><br/>';
    
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
    
                        // if the user has already submitted their user/email, they are 
                        if ($submitted) {
                            // get mailing option
                            if (isset($_POST['newsletter'])) {
                                $mailing_option = $_POST['newsletter'];

                                // the user is going to sign up!
                                if (add_user($username, $email, $mailing_option) === true) {
                                    echo 'Added new user.<br/>';
                                    // log in the session, and redirect to same page
                                    $session->login($username, $email);
                                    header('Location: ' . $_SERVER["PHP_SELF"]);
                                } else {
                                    echo 'Error when adding new user.<br/>';
                                }
                            }
                        } else {
    
                            // username and email should not be empty
                            if ($username !== '' && $email !== '') {
                                // username and email wasn't empty, proceed
                                if (user_exists($username, $email)) {
                                    // log in the session, and redirect to same page
                                    $session->login($username, $email);
                                    header('Location: ' . $_SERVER["PHP_SELF"]);
                                    // redirect the user to members.php page!!
                                } else {
                                    // update the sign in form to include mailing options
                                    header('Location: ' . $_SERVER["PHP_SELF"] . '?user=' . $username . '&email=' . $email);
                                }
                            }
                        }
                    }
    
                    echo '<input type="submit" name="submitLogin" class="login-button submit" value="Log In"/>';
    
                } // end if
                
            ?>
        </form>

        

        <!-- Content -->
        <div id="content" class="home">

            <h1>Movie Database</h1>

            <p>Welcome to my Movie Database! We feature over 2,000 titles.
            <br/>
            Here is a list of the top searches: </p>

            <!-- Top Searches Table -->
            <table>
                <tr>    
                    <th># Of Searches</th>
                    <th>Title <br/><a href="public/top_movies.php" target="_blank">View Chart</a></th>
                </tr>
                <?php

                    
                    // Choose the top 10 searched movies from the top_searches table
                    $sql = "SELECT SearchAmount, Title, ID FROM top_searches ORDER BY SearchAmount DESC LIMIT 10";
                    $result = query($sql);

                if (mysqli_num_rows($result) > 0) {
                    // echo the top 10 search results
                    // into a new row in the table
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                            echo '<td>' . $row['SearchAmount'] . '</td>';
                            echo '<td>' . $row['Title'] . '</td>';
                        echo '</tr>';
                    }
                }

                ?>

            </table>

        </div>

    </body>

</html>
