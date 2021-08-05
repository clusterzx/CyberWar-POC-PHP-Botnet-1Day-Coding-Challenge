<?php
include "inc/db.php";
include 'inc/config.php';

$db = new db($dbhost, $dbuser, $dbpass, $dbname);

if (
    isset($_POST["clid"]) &&
    isset($_POST["apikey"]) &&
    isset($_POST["authkey"]) &&
    isset($_POST["getJob"])
) {
    if ($apikey == $_POST["apikey"]) {
        $clid = $_POST["clid"];
        $authkey = $_POST["authkey"];
        $options = ["cost" => 10];
        $hashId = password_hash($_POST["clid"], PASSWORD_BCRYPT, $options);

        // -------------------CHECK IF ALREADY EXISTS-----------------------------------
        //$query2 = "SELECT current_task FROM `clients` WHERE `clientid` = '" . $clid . "'";

        $query2 = "SELECT * FROM `clients` WHERE `clientid` = '" . $clid . "' AND authkey = '" . $authkey . "'";
        $result2 = $db->query($query2)->fetchArray();

        if ($authkey == $result2['authkey']){
            echo $result2['current_task'];
        }else{
            echo "404";
        }
    } else {
        echo "404";
    }
}

if (
    isset($_POST["clid"]) &&
    isset($_POST["apikey"]) &&
    isset($_POST["authkey"]) &&
    isset($_POST["deleteJob"])
) {
    if ($apikey == $_POST["apikey"]) {
        $clid = $_POST["clid"];
        $authkey = $_POST["authkey"];
        $options = ["cost" => 10];
        $hashId = password_hash($_POST["clid"], PASSWORD_BCRYPT, $options);

        // -------------------CHECK IF ALREADY EXISTS-----------------------------------
        //$query2 = "SELECT current_task FROM `clients` WHERE `clientid` = '" . $clid . "'";

        $query2 = "SELECT * FROM `clients` WHERE `clientid` = '" . $clid . "' AND authkey = '" . $authkey . "'";
        $result2 = $db->query($query2)->fetchArray();

        if ($authkey == $result2['authkey']){
            $query3 = "UPDATE clients SET last_task = current_task, current_task = '' WHERE clientid = '" . $clid . "';";
            $result3 = $db->query($query3);
        }else{
            echo "404";
        }
    } else {
        echo "404";
    }
}
?>
