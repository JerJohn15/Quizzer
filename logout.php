<?php

include 'conf/db.conf';

session_destroy();
unset($_SESSION);
header('Location: index.php');
exit;
