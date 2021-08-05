<?php
include 'inc/config.php';
if (isset($_GET['logout'])) {
    session_start();
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),'',0,'/');
    session_regenerate_id(true);
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);
        setcookie($name, '', time()-1000, '/');
    }
    header("Location: login.php");
    die();
}
    include "inc/db.php";

    $db = new db($dbhost, $dbuser, $dbpass, $dbname);

    session_start();
    $username = "";
    $lastLogin = "";
    if(isset($_SESSION['username'])) {
    die('Sie sind bereits eingeloggt... <a href="index.php">Zur Übersicht</a>');
    }
    // If the values are posted, insert them into the database.
    if (isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $options = array("cost"=>10);
        $hashPassword = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
        // -----------------------------------------------------------------------------
        $query2 = "SELECT * FROM `users` WHERE `username` = '".$username."'";
        $result2 = $db->query($query2)->fetchArray();
        if(!password_verify($password, $result2['password'])){
            $fmsg = 'Wrong Username or wrong password.<hr/>
                If you forgot your password you have to change it in the DB manually';
            // die();
        } else {     
        // -----------------------------------------------------------------------------
            // LOGIN +
            $queryLastLogin = "UPDATE users SET lastlogin=CURDATE() WHERE username='" . $username . "';";
            $resultLastLogin = $db->query($queryLastLogin);
            $lastLogin = date('Y/m/d H:i:s');
            $_SESSION['valid'] = true;
            $_SESSION['timeout'] = time();
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['lastLogin'] = $lastLogin;
            $smsg = "Success! You'll be redirected in 3 seconds.... ".$_POST['email']."";
            ob_start();
            sleep(3);
            header("Location: index.php");
        }
    }
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login - CyberWar</title>
    <meta name="description" content="CyberWar Botnet Management Console">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-12 col-xl-10">
                <div class="card shadow-lg o-hidden border-0 my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h4 class="text-dark mb-4">Welcome Back to CyberWar!</h4>
                                        <?php if(isset($smsg)){ ?><div class="alert alert-success" role="alert"> <?php echo $smsg; ?> </div><?php } ?><?php if(isset($fmsg)){ ?><div class="alert alert-danger" role="alert"> <?php echo $fmsg; ?> </div><?php } ?>
                                    </div>
                                    <form method="post" class="user">
                                        <div class="mb-3"><input class="form-control form-control-user" type="text" id="username" aria-describedby="usernameHelp" placeholder="Enter Username" name="username"></div>
                                        <div class="mb-3"><input class="form-control form-control-user" type="password" id="password" placeholder="Password" name="password"></div>
                                        <div class="mb-3">
                                        </div><button class="btn btn-primary d-block btn-user w-100" type="submit">Login</button>
                                        <hr>
                                    </form>
                                    <div class="text-center"></div>
                                    <div class="text-center"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
    <script src="assets/js/script.min.js"></script>
</body>

</html>