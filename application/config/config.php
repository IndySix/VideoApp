<?php

if (!defined('__SITE_PATH'))
    exit('No direct script access allowed');
/*
  | -------------------------------------------------------------------
  | MartensMVC SETTINGS
  | -------------------------------------------------------------------
  | This file will contain the settings for MartensMVC.
  |
 */

/* -------------------------------------------------------------------
 * Base Site URL
 * -------------------------------------------------------------------
 * 
 * URL to your CodeIgniter root. Typically this will be your base URL,
 * WITH a trailing slash:
 *
 * 	http://example.com/
 *
 * If this is not set then MartensMVC will guess the protocol, domain and
 * path to your installation. */
$baseURL = 'https://server.martens.me/indysix/';


/* -------------------------------------------------------------------
 * debugOn
 * -------------------------------------------------------------------
 * 
 * When set to true it wil display exception on page. 
 */
$debugOn = true;

/* -------------------------------------------------------------------
 * Sessions save locations
 * -------------------------------------------------------------------
 * 
 * Sets the sessions save location, default is /data/sessions/
 * 
 * !!!RECOMMENDED TO CHANGE SAVE LOCATION OUTSIDE THE WEBSITE DIRECTORY!!!
 * 
 */
$sessionSaveLocation = __SITE_PATH.'data/sessions/';


/* -------------------------------------------------------------------
 * Default email address and name
 * -------------------------------------------------------------------
 * 
 * Sets the default from and reply to mail address
 * 
 */
$defaultEmail = 'info@martens.MCV';
$defaultEmailName = 'MartensMCV';


/* -------------------------------------------------------------------
 * Auto-load libraries
 * ------------------------------------------------------------------- 
 * Example:
 *      $autoload['libraries'] = array('databse', 'mail', 'upload');
 */
$autoload['libraries'] = array('database', 'session');


/* -------------------------------------------------------------------
 * Auto-load models
 * -------------------------------------------------------------------
 * Example:
 *      $autoload['model'] = array('model1', 'model2');
 */
$autoload['model'] = array('secure', 'login');

/* End of file config.php */
/* Location: ./application/config/config.php */


