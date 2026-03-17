<?php
require_once '../../../backend/config/functions.php';
requireRole('secretary');
// Dashboard removed for secretary role — redirect to schedule index
header('Location: ../schedule/index.php');
exit;
?>