<html>

<!-- 

        Members.php

 -->

    <head>

        <title>Members Area</title>
        <link rel="stylesheet" href="../style.css" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

        <script>
            // Author : Sean
            // JavaScript function to allow user to click on ID and update form
            function popFields(x) {
                var tabRows = document.getElementById("users_table").rows.length;
                for (var i = 1; i < tabRows; i++) {
                    if (document.getElementById("users_table").rows[i].cells[0].innerHTML==x) {
                        document.forms["adminForm"]["id"].value = document.getElementById("users_table").rows[i].cells[0].innerHTML;
                        document.forms["adminForm"]["email"].value = document.getElementById("users_table").rows[i].cells[2].innerHTML;
                        document.forms["adminForm"]["mail_options"].value = document.getElementById("users_table").rows[i].cells[3].innerHTML;
                    }
                }
            }
        </script>

    </head>

    <body>
        <!-- Navigation Menu -->
        <ul id="nav">

            <li><a href="../index.php">Home</a></li>
            <li><a href="search.php">Search</a></li>
            <li class="active"><a href="members.php">Members</a></li>

        </ul>

        <?php 
            require_once '../private/initialise.php';
            if ($session->is_logged_in()) {?>

                <!-- Show Page Content if logged in -->
                <div id="content">
                    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>

                    <?php 
                    // check that user is admin
                    if (user_is_admin($_SESSION["email"])) {
                        // The member belongs to Admin / ACME user group

                        if (user_needs_to_set_password($_SESSION['email'])) {
                            include 'set_password.php';
                        } else if (!isset($_SESSION['password'])) {

                            // ask for password
                            include 'enter_password.php';

                        } else {
                            // Update record clicked
                            if (isset($_POST['update_record'])) {
                                $sql = 'UPDATE members SET MailingOption="' . $_POST['mail_options'] . '" WHERE ID="' . $_POST['id'] . '"';
                                $result = query($sql);
                                if ($result) {
                                    echo 'Updated 1 record.';
                                }
                            }?>
                            
                                <form id="adminForm" method="post">
                                    <input type="text" name="id" class="id_input" placeholder="ID" readonly />

                                    <input type="text" name="email" placeholder="Email" />

                                    <select name="mail_options">
                                        <?php
                                            $sql = 'SELECT DISTINCT MailingOption FROM members';
                                            $result = query($sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $mail = $row["MailingOption"];
                                                echo '<option value="'.$mail.'">'.$mail.'</option>';
                                            }
                                            
                                        ?>
                                    </select>

                                    <input type="submit" value="Update" name="update_record" class="update_record" /><br/>
                                </form>

                                    <!-- All Users table -->
                                <table id="users_table">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>MailingOption</th>
                                        <th>Group</th>
                                    </tr>

                                    <?php
                                    $result = get_all_users_groups();
                                    // get all users in members,
                                    //  display them in a table row
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '  <tr>
                                                    <td onclick="popFields('.$row["ID"].')">' . $row['ID'] . '</td>
                                                    <td>' . $row['Name'] . '</td>
                                                    <td>' . $row['Email'] . '</td>
                                                    <td>' . $row['MailingOption'] . '</td>
                                                    <td>' . $row['Group_Name'] . '</td>
                                                </tr>';
                                    }
                                    mysqli_free_result($result);
                                    ?>

                                </table>
                                <!-- End of Users table -->
                            <?php
                        }
                    } else {
                        // this client is a regular user
                    }
                    ?>

                </div> <!-- id="content" -->

            <?php
            } else { // user must log in ?>
                <div id="content">
                    <h1>You are not logged in.</h1>
                    <center>
                        <?php echo '<a href="../index.php">Go Back</a>' ?>
                    </center>
                </div>

            <?php
            }?>
                    
        

    </body>

</html>