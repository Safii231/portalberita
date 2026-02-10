<?php
session_start();
if (!isset($_SESSION['id_user'])) header("Location: login.php");

require_once "../classes/kategori.php";
$kategori = new Kategori();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $kategori->delete($id);
}
header("Location: kategori.php");
?>