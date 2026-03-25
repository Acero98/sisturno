<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config.php";

if(!isset($_SESSION['id_usuario'])){
    header("Location: login.php");
    exit();
}