<head>

    <link rel="stylesheet" href="../style.css" />

</head>

<?php

    // Author: Gholamreza
    // analytics.php
    //  contains table with most searched movies,

    require_once '../private/initialise.php';

?>

<!-- Top Searches Table -->
<h1 style="text-align: center; margin-top: 5vh;">Analytics</h1><br/>
<center><a href="../index.php">< Back</a></center>
<table style="margin-top: 50px;">
    <tr>    
        <th>Last Streamed</th>
        <th>Total Searches</th>
        <th>Title</th>
    </tr>
    <?php

        
        // Choose the top 10 searched movies from the top_searches table
        $sql = "SELECT SearchAmount, Title, ID, LastUpdate FROM top_searches ORDER BY LastUpdate DESC LIMIT 10";
        $result = query($sql);

        if (mysqli_num_rows($result) > 0) {
            // echo the top 10 search results
            // into a new row in the table
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                    // get the time difference between current time and LastUpdate,
                    //  needs to be consistent with server time, and difference shown in minutes

                    $lastUpdate = get_time($row['LastUpdate']);
                    $time_difference = get_time_difference($lastUpdate);

                    echo '<td style="text-align: center;">' . get_time_string($time_difference) . ' ago</td>';
                    echo '<td style="text-align: center;">' . $row['SearchAmount'] . '</td>';
                    echo '<td>' . $row['Title'] . '</td>';
                echo '</tr>';

                
            }
        }

    ?>

</table>