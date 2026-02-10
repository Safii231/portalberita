<?php
session_start();
if (!isset($_SESSION['id_user']) || $_SESSION['role'] != "admin")
    header("Location: login.php");
    exit;

require_once "../classes/user.php";
$user = new User();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $user->delete($id);
}
header("Location: users.php");
?>