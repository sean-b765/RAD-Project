<?php

    // Author: Sean
    // most_rated.php
    //  contains table with most rated movies,

?>

<!-- Top Searches Table -->
<table id="analytics">
    <tr>    
        <th>Rating</th>
        <th># Of Ratings</th>
        <th>Title</th>
    </tr>
    <?php

        
        // Choose the top 10 searched movies from the top_searches table
        $sql = "SELECT Title, AvgRating, NumberOfRatings FROM movies 
                WHERE AvgRating IS NOT NULL
                ORDER BY AvgRating DESC 
                LIMIT 10";
        $result = query($sql);

        if (mysqli_num_rows($result) > 0) {
            // echo the top 10 search results
            // into a new row in the table
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                    // Round Avg to two decimal places
                    echo '<td style="font-weight: bold;">' . number_format((float)$row['AvgRating'], 2, '.', '')  . ' / 5</td>';
                    echo '<td style="text-align: center;">' . $row['NumberOfRatings'] . '</td>';
                    echo '<td>' . $row['Title'] . '</td>';
                echo '</tr>';
            }
        }

        // close connection to db
        close_db();

    ?>

</table>