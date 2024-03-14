<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: halaman_login.php");
}