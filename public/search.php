<html>

    <head>

        <title>Search Results</title>
        <link rel="stylesheet" href="../style.css" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    </head>

    <body>
        <!-- Navigation Menu -->
        <ul id="nav">

            <li><a href="../index.php">Home</a></li>
            <li class="active"><a href="search.php">Search</a></li>

        </ul>

        <!-- HTML Search Form -->
        <form class="search_form" method="post">
            <input type="text" class="searchInput" placeholder="Title" name="searchTxt" />
            <input type="submit" class='search' name='searchBtn' value="Search" /><br/><br/>

            <!-- YEAR 
                    A datalist contains autofill options -->
            <input type="text" name="year" placeholder="Year" list="years"/>
            <datalist id="years">
                <?php
                // for the YEAR, GENRE, get the range of values using DISTINCT
                //      to add to the datalist autofill, to help user.
                    require_once '../private/initialise.php';
                    $sql = 'SELECT DISTINCT Year FROM movies ORDER BY Year DESC';
                    $result = query($sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value=' . $row['Year'] . '>'. $row["Year"] .'</option>';
                    }
                }
                ?>
            </datalist>

            <!-- GENRE -->
            <input type="text" name="genre" placeholder="Genre" list="genres" />
            <datalist id="genres">
                <?php
                    $sql = 'SELECT DISTINCT Genre FROM movies ORDER BY Genre ASC';
                    $result = query($sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value=' . $row['Genre'] . '>'. $row["Genre"] .'</option>';
                    }
                }
                ?>
            </datalist><br/>
            
            <!-- RATING -->
            <select name="rating" class="rating_chooser">
                    <option value="Any">Any Rating</option>
                <?php
                    // get the DISTINCT rating values to add as a combo-box option.
                    $sql = 'SELECT DISTINCT Rating FROM movies';
                    $result = query($sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value=' . $row['Rating'] . '>'. $row["Rating"] .'</option>';
                    }
                }
                ?>
            </select>

        </form>        

        <!-- Page Content (Search Results) -->
        <div id="content" class="search_content">
            
            <?php
                $filteredSearch = false; // detect if filters have been applied
                // the title can be empty if filteredSearch is true

            // set the variables using POST
            if (isset($_POST['year']) && isset($_POST['genre']) && isset($_POST['rating'])) {
                $year = $_POST['year'];
                $genre = $_POST['genre'];
                $rating = $_POST['rating'];
                // if the rating is any, change to an empty string
                if ($rating === 'Any') {
                    $rating = '';
                }
            }
            // if user has pressed search button, get the searchTerm 
            //      and check if filters have been applied
            if (isset($_POST['searchBtn'])) {
                $searchTerm = $_POST['searchTxt'];
                    
                if (!empty($year) || !empty($genre) || !empty($rating)) {
                    $filteredSearch = true;                        
                }
            } // end if

            // Generate SQL query in this IF block by concatenation
            if (!empty($searchTerm) || $filteredSearch) {
                $sql = "SELECT * FROM movies";
                // chain of IF statements will apply filters to SQL
                if ($filteredSearch) {
                    $filtered = false; 
                    // this boolean will tell the following to append the SQL
                    //          using AND [col=val] or WHERE [col=val]

                    if (!empty($year)) {
                        $filtered = true;
                        // if the $year variable is not empty, then it has been filtered.
                        $sql .= " WHERE Year='". $year ."'";
                    }
                    if (!empty($genre)) {
                        if ($filtered) {
                            // if year was not empty, use AND instead of WHERE
                            $sql .= " AND Genre='". $genre ."'";
                        } else {
                            $sql .= " WHERE Genre='". $genre ."'";
                        }
                        $filtered = true;              
                    }
                    if (!empty($rating)) {
                        if ($filtered) {
                            // if year OR genre was not empty, use AND
                            $sql .= " AND Rating='". $rating ."'";
                        } else {
                            $sql .= " WHERE Rating='". $rating ."'";
                        }     
                        $filtered = true;                       
                    }
                    if (!empty($searchTerm)) {
                        $sql .= " AND Title LIKE '%". $searchTerm ."%'";
                    }
                } else {
                    // no filters were applied, but searchTerm still needs to be added to SQL
                    $sql .= " WHERE Title LIKE '%" . $searchTerm . "%'";
                }

                // get the query result
                $result = query($sql);

                // create the HTML table
                if ($result) {
                    if (mysqli_num_rows($result) > 0) {
                        // table headers
                        echo '  
                                <table class="movie_table">
                                    <tr>
                                        <th>Title</th>
                                        <th>Year</th>
                                        <th>Genre</th>
                                        <th>Rating</th>
                                        <th>Studio</th>
                                        <th>Status</th>
                                    </tr>';
                        echo '<p style="margin: 50px 0px 20px 0px;">' . mysqli_num_rows($result) . ' results were found.</p>';
                            // echo out how many movies were found
                        $count = 0;
    
                        // create the records in the HTML table, by looping through the rows
                        while ($row = mysqli_fetch_assoc($result)) {
                            // for the first 5 search results, add these to the top_searches!!
                            if ($count < 5) {
                                $count++;
                                
                                $sql = "SELECT * FROM top_searches WHERE ID='" . $row['ID'] . "'";
                                $result_srch = query($sql);
    
                                $record = mysqli_fetch_assoc($result_srch);
                                // check if we need to UPDATE or INSERT to top_searches table
                                if (mysqli_num_rows($result_srch) > 0) {
                                    $top_searches = "UPDATE top_searches SET SearchAmount=" . ($record['SearchAmount'] + 1) . " WHERE ID='" . $record['ID'] . "';";
                                } else {
                                    $top_searches = "INSERT INTO top_searches (ID, Title, SearchAmount) VALUES
                                            ('" . $row['ID'] . "', '" . $row['Title'] . "', 1);";
                                }
                                    
                                query($top_searches);
    
                            } //end if (count<5)
                            
                            // echo out the movie data for the HTML table
                            echo '  <tr>
                                            <td>' . $row['Title'] . '</td>
                                            <td> ' . $row['Year'] . ' </td>
                                            <td> ' . $row['Genre'] . ' </td>
                                            <td> ' . $row['Rating'] . ' </td>
                                            <td> ' . $row['Studio'] . ' </td>
                                            <td> ' . $row['Status'] . ' </td>
                                        </tr>';
                        }
                        echo '</table>';
                    } else {
                        echo 'No movies were found. Please ensure you have correct spelling.';                    
                    }
                } else {
                    echo 'Please check your input.';
                }
            } else {
                echo 'Search for a Title, or use the filters.';
            } // end if 

            ?>
        </div> <!-- id="content" -->
    </body>

</html>
