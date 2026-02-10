<?php
session_start(); // Mulai session
session_unset(); // Hapus semua variabel session
session_destroy(); // Hapus session

// Redirect ke halaman login
header("Location: login.php");
exit;
?>