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

            <p>Welcome to The Movie Database! We feature over 2,000 titles.
            <br/><br/><a href="public/analytics.php">View the recent analytics</a><br/><br/>
            <strong>Here is a list of the top rated movies:</strong></p>

            <?php
                include 'public/most_rated.php';
            ?>

        </div>

    </body>

</html>
