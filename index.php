<?php

/**
 * A simple PHP MVC CRUD application
 * to cover Formstack Software Engineer Assignment
 *
 * @package FSEA-test
 * @author Fabio Marzocca
 * @link https://github.com/fmarzocca/FSEA-test
 * @license http://opensource.org/licenses/MIT MIT License
 * May 2017
 */
require_once 'controller/UsersController.php';
require 'db.inc.php';
require 'vendor/autoload.php';

$controller = new \FSEA\Controller\UsersController();

$controller->handleRequest();
