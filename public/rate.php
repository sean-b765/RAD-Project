<?php

    require_once '../private/initialise.php';

    //data: {stars:x, movie_id:movie_id, s_id:id}
    // data is sent here after user rates a movie, from ajax POST request in star_rating.js
    // call insert_member_rating to insert/update the database

    // this page is POSTED to every time a user rates a movie

    $result = null;

    if (isset($_POST['s_id']) && isset($_POST['stars'])  &&  isset($_POST['movie_id'])) {
        $result = insert_member_rating($_POST['stars'], $_POST['movie_id'], $_POST['s_id']);

        // send update to all clients via WebSocket
        //  as the ratings have changed

        echo json_encode('MemberID: ' . $_POST['s_id'] . ' MovieID: ' . $_POST['movie_id'] . ' Stars: ' . $_POST['stars']);
    } else {
        // a POST request was sent here without any variables ...
    }

?>