<?php 
$host = $_SERVER['HTTP_HOST'];

if (strpos($host, 'edutech.intelexsolutions-test.site/') !== false) {
    $conn = new mysqli("localhost", "u593219986_edutech", "GdEdutech@1234", "u593219986_edutech");

} else {
    $conn = new mysqli("localhost", "root", "", "gd_edu_tech");

}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

}

define('UPLOADS_DIR', __DIR__ . '/uploads/');


?>
