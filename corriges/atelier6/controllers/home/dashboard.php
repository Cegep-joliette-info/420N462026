<?php
/** @var $bd PDO */
require_once('../../config.php');

if (!isset($_SESSION['username'])) {
    header('Location: ../../index.php');
    exit();
}
$succes = $_SESSION['success'] ?? null;
unset($_SESSION['success']);

require('../../views/home/dashboard.php');