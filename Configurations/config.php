<?php 
$host = $_SERVER['HTTP_HOST'];

if (strpos($host, 'gdedutech.com') !== false) {
    $conn = new mysqli("srv1752.hstgr.io", "u229215627_edutech", "Azl@n2002", "u229215627_edutech");

} else {
    $conn = new mysqli("localhost", "root", "", "gd_edu_tech");
}

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// define('UPLOADS_DIR', __DIR__ . '/uploads/');
$adminMail="gdedutech24@gmail.com";
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

?>
