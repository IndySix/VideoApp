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

if (!defined('__SITE_PATH')) exit('No direct script access allowed');
/**
 * MartensMVC
 *
 * An MVC framework for PHP/MYSQL
 *
 * @author		Maikel Martens
 * @copyright           Copyright (c) 20012 - 2012, Martens.me.
 * @license		http://martens.me/license.html
 * @link		http://martens.me
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * Route class
 *
 * Loads the controller
 *
 * @package		MartensMCV
 * @subpackage          Core
 * @category            Core
 * @author		Maikel Martens
 */
// ------------------------------------------------------------------------

class route {
    /* The controller path */

    private $path;
    /* The arguments */
    private $args = array();
    /* The controller file path/file.php */
    private $file;
    /* The controller name */
    private $controller = "";
    /* The controller action */
    private $action = "index";

    /**
     *
     * Constructer
     *
     * @param   string  controller directory path
     * @return  void
     *
     */
    function __construct($path) {
        if (!is_dir(__APPLICATION_PATH.$path)) {
            throw new Exception('Invalid controller path: `' . $path . '`');
        }
        $this->path = __APPLICATION_PATH.$path;
    }

    // ------------------------------------------------------------------------

    /**
     *
     * loader
     * 
     * Load the controller
     *
     * @return  void
     *
     */
    function load() {
        /* if file not exsist show error404 */
        if (!is_readable($this->file)) {
            include __APPLICATION_PATH.'errors/error404.php';
            return;
        }

        /* load controller and uri */
        load_class($this->controller, "controllers");
        load_class('uri', 'core');

        /* Create new controller and create uri */
        $controller = new $this->controller();
        $controller->uri = new uri($this->args);
        
        /* check if the action is callable */
        if (!is_callable(array($controller, $this->action))) {
            include __APPLICATION_PATH.'errors/error404.php';
            return;
        } else {
            $action = $this->action;
        }

        try{
            /* Auto-load */
            $controller->load->_autoLoad();

            /* Run action */
            $controller->$action();
        } catch(Exception $e){
            /* Inlcude config */
            include __CONFIG_PATH.'config.php';
            if($debugOn) {
                /* Displays Exception in html DIV */
                $trace = $e->getTrace();
                $message = $e->getMessage();
                $filepath = $trace[0]['file'];
                $line = $trace[0]['line'];
                $originalFilepath = $e->getFile();
                $originalLine = $e->getLine();
                include __APPLICATION_PATH.'errors/exception.php';
            }
        }
    }

    // ------------------------------------------------------------------------

    /**
     *
     * getController
     * 
     * Set the controller variables
     *
     * @return  void
     *
     */
    function getController() {
        /* Inlcude config */
        include __CONFIG_PATH.'route.php';

        if (empty($_GET['url'])) {
            $this->controller = $defaultpage;
            $this->action = "index";
        } else {
            if (isValidUrlData($_GET['url'])) {
                /* get parts of route */
                $parts = explode('/', $_GET['url']);
                $this->controller = $parts[0];
                /* Set action when set */
                if (isset($parts[1]) && trim($parts[1]) != "") {
                    $this->action = $parts[1];
                }
                /* set arguments */
                $this->args = $parts;
            } else {
                $this->controller = 'error404';
            }
        }
        /* set the file path */
        $this->file = $this->path . '/' . $this->controller . '.php';
    }

    /**
     *
     * getControllerName
     * 
     * Returns the controller name
     *
     * @return  String
     *
     */
    function getControllerName() {
        return $this->controller;
    }

}

/* End of file route.php */
/* Location: ./application/core/route.php */
