<?php
// Clear the JWT cookie by setting its expiration date in the past
setcookie("auth_token", "", time() - 3600, "/");

// Redirect to the login page after logout
header("Location: login.php");
exit();
?>
