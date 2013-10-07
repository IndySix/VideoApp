<?php
/**
* MartensMCV is an simple and smal framework that make use of OOP and MVC patern.
* Copyright (C) 2012 Maikel Martens
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/* * * define the site path * * */
$site_path = realpath(dirname(__FILE__)).'/';
define('__SITE_PATH', $site_path);

/* * * define the application path * * */
$application_path = realpath(dirname(__FILE__)).'/application/';
define('__APPLICATION_PATH', $application_path);

/* * * define the config path * * */
$config_path = realpath(dirname(__FILE__)).'/application/config/';
define('__CONFIG_PATH', $config_path);

/* * * include the common.php file * * */
include __APPLICATION_PATH . 'core/common.php';

/* * * include the class route * * */
load_class("route", "core");

/* * * Load the route * * */
$route = new route("controllers");

/* * * Setup the controller * * */
$route->getController();

/* * * define the controller name * * */
define('__CONTROLLER_NAME', $route->getControllerName());

/* * * Include the global gonfig gile * * */
include __CONFIG_PATH.'global.php';

/* * * Run the controller * * */
$route->load();
