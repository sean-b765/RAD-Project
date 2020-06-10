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
            <li><a href="public/members.php">Members</a></li>

        </ul>

        <?php include 'public/login.php'; ?> 

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
