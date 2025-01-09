<?php
session_start();
session_destroy();
header("Location: /mcserverlist/");
exit();
?> 