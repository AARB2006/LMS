<?php

session_start();
session_destroy();
header("Location: ../landing pages/index.php");
exit();

