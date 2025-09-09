<?php
require_once 'includes/auth.php';

// Logout user
Auth::logout();

// Redirect to login page
header('Location: login.php');
exit;
?>
