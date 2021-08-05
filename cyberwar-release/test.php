<?php
$dir    = 'generate';
$files1 = scandir($dir);

foreach($files1 as $file){
    unset($file);
}

exec("builder.exe http://localhost/cyberwar/ secret1337secret1337secret1337 True");

$fileArr = scandir($dir);

foreach($fileArr as $file){
    echo $file;
}

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


?>