<?php
include "inc/db.php";
include 'inc/config.php';

$db = new db($dbhost, $dbuser, $dbpass, $dbname);

if (
    isset($_POST["insert"]) &&
    isset($_POST["clid"]) &&
    isset($_POST["apikey"]) &&
    isset($_POST["ip"]) &&
    isset($_POST["os"])
) {
    if ($apikey == $_POST["apikey"]) {
        $clid = $_POST["clid"];
        $options = ["cost" => 10];
        $hashId = password_hash($_POST["clid"], PASSWORD_BCRYPT, $options);
        // -------------------CHECK IF ALREADY EXISTS-----------------------------------
        $query2 = "SELECT * FROM `clients` WHERE `clientid` = '" . $clid . "'";
        $result2 = $db->query($query2);
        $result2 = $result2->numRows();
        if ($result2 == 1) {
            echo "101";
        } else {
            // -----------------------------------------------------------------------------
            // ADD NEW CLIENT +
            $db2 = new db($dbhost, $dbuser, $dbpass, $dbname);
            //$addQuery = "INSERT INTO clients (clientid, ip, os, install_date, last_seen) VALUES ('testid', '129.1.1.0', 'Windows 11 88bit', NOW(), NOW());";
            $addQuery =
                "INSERT INTO clients (clientid, authkey, ip, os, install_date, last_seen) VALUES ('" .
                $_POST["clid"] .
                "', '" .
                $_POST["authkey"] .
                "', '" .
                $_POST["ip"] .
                "', '" .
                $_POST["os"] .
                "', 
                CURDATE(), NOW())";
           $addResult = $db->query($addQuery);
           $db->close();
            echo "100";
        }
    } else {
        echo "404";
    }
}
?>
