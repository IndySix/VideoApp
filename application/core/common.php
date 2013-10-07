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
 * @copyright   Copyright (c) 20012 - 2012, Martens.me.
 * @license		http://martens.me/license.html
 * @link		http://martens.me
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * Common Functions
 *
 * Loads the functions.
 *
 * @package		MartensMCV
 * @subpackage          Core
 * @category            Core
 * @author		Maikel Martens
 */
// ------------------------------------------------------------------------

/*
 * Load the general settings
 */

include __CONFIG_PATH.'config.php';

/*
 * Keep track of wich class is already loaded
 */

$loaded_class = array();

// ------------------------------------------------------------------------

/**
 * Class Loader
 *
 * Loads file with name with $param.php
 *
 * @access	public
 * @param	string	the class name being requested
 * @param	string	the directory where the class should be found, default libraries
 * @return	boolean
 */
if (!function_exists('load_class')) {

    function load_class($class, $path = "libraries") {
        global $loaded_class;
        $file = __APPLICATION_PATH . $path . "/" . $class . ".php";
        if (is_readable($file) && !in_array($class, $loaded_class)) {
            $loaded_class[] = $class;
            include($file);
            return true;
        } else {
            return false;
        }
    }

}

/**
 * isValidUrlData
 *
 * Chekcs if given string is valid url data
 * valid url data is: a-z A-Z 0-9 . / : - _ ~ %
 *
 * @access	public
 * @param	string	Data needed to check
 * @return	boolean
 */
if (!function_exists('isValidUrlData')) {

    function isValidUrlData($url) {
        return !preg_match('/[^a-zA-Z0-9\.\/\:\-\_\~\%]/', $url);
    }

}

/**
 * isValidUrlData
 *
 * Chekcs if given string is valid url data
 * valid url data is: a-z A-Z 0-9 . / : - _ ~ %
 *
 * @access	public
 * @param	string	Data needed to check
 * @return	boolean
 */
if (!function_exists('baseUrl')) {
    
    function baseUrl($request = ''){
        global $baseURL;
        if($baseURL == ''){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
            return $protocol . "://" . $_SERVER['HTTP_HOST'] .'/'.$request;
        } else {
           return $baseURL.$request; 
        }
    }
    
}


/**
 * randomString
 *
 * Generate an random string.
 *
 * @access  public
 * @param   int     length of the string
 * @return  String
 */
if(!function_exists('randomString')){
    function randomString($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }
}


/**
 * redirect
 *
 * Redirect to controller/function or an url.
 *
 * @access  public
 * @param   string  url
 * @return  void
 */
if ( ! function_exists('redirect')) {
    function redirect($url = '') {
        if (!preg_match('#^https?://#i', $url)) {
            $url = baseUrl($url);
        }
        header("Location: ".$url, TRUE);
    }
}

/* load super class controller */
load_class("controller", "core");

/* load super class model */
load_class("model", "core");

/* End of file common.php */
/* Location: ./application/core/common.php */
