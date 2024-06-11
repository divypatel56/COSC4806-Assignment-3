<?php
//Function to connect with database
function db_connect() {
    try {
        // PDO instance with database connection details
        $dbh = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_DATABASE, DB_USER, DB_PASS);
        //Return database handles if successfull
        return $dbh;
    } catch (PDOException $e) {
        //catch exception and return the error messsge
        echo 'Connection failed: ' . $e->getMessage();
        exit();
    }
}
?>