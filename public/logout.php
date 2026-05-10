<?php

require_once('../src/db.php');
session_start();
session_destroy();
header("Location: login.php");
exit();
