<?php
require_once 'lib/config.php';
require_once 'lib/session.php';

session_regenerate_id(true);
session_destroy();
unset($_SESSION);
header('Location: login.php');
