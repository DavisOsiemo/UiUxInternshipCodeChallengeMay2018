<?php
//Establishing connection to MySQL DB using localhost


$servername = 'localhost';
$username = 'root';
$password = "";
$database = "smsapp";
$dbport = 3306;



    // Create connection
    $db = new mysqli($servername, $username, $password, $database, $dbport);

    // Check connection
    if ($db->connect_error) {
        header('Content-type: text/plain');
        //log error to file/db $e-getMessage()
        die("END An error was encountered. Please try again later");
    }
?>
