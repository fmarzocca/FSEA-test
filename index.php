<?php

require_once 'controller/UsersController.php';
require 'db.inc.php';

$controller = new UsersController();

$controller->handleRequest();

?>
