<?php
require_once 'config.php';
header('Location: ' . (!empty($_SESSION['admin_id']) ? 'dashboard.php' : 'login.php'));
exit;
