<?php 
$host = $_SERVER['HTTP_HOST'];

if (strpos($host, 'gdedutech.in') !== false) {
    $conn = new mysqli("localhost", "u593219986_root", "GdGold&Diamonds1234", "u593219986_gd_gold");

} else {
    $conn = new mysqli("localhost", "root", "", "gd_edu_tech");

}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

}

define('UPLOADS_DIR', __DIR__ . '/uploads/');

?>
