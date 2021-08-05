<?php
include "inc/db.php";
include 'inc/config.php';

if (isset($_POST["ping"]) && isset($_POST["clid"])) {
    $db        = new db($dbhost, $dbuser, $dbpass, $dbname);
    $addQuery  = "UPDATE clients SET last_seen = now() WHERE clientid = '" . $_POST["clid"] . "';";
    $addResult = $db->query($addQuery);
    $db->close();
    echo "100";
} else {
    echo "404";
}
?>