<?php 

include 'conf/db.conf';

if (isset($_POST["email"])) {
    //check if its ajax request, exit script if its not
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        die();
    }

    // Input validation is good!
    $email = filter_var($_POST["email"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);

    //check username in db, get the return count and respond
    $results = pg_query($pgh, "SELECT email FROM users WHERE email='$email'");
    if (pg_num_rows($results) == 0) {
        die('OK');
    } else {
        die('NOT_OK');
    }
}
