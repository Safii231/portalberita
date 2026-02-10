<?php
session_start();
if (!isset($_SESSION['id_user'])) header("Location: login.php");

require_once "../classes/berita.php";
$berita = new Berita();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    $berita->delete($id);
}
header("Location: berita.php");
?>