<?php
/** @var $bd PDO */
require_once('config.php');
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);

require('views/auth/login.php');
