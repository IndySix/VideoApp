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
 * Sessoin class
 *
 * Session class for storing session data.
 *
 * @package		MartensMCV
 * @subpackage          Libraries
 * @category            Libraries
 * @author		Maikel Martens
 */
// ------------------------------------------------------------------------

class session {
    /* @var array contains data of session. */
    private $data = array();

    /* @var String contains the save location of the sessions. */
    private $saveLocation;

    /**
     * Constructer
     *
     * Create session and load session when set.
     * Create new cookie for session and saves session
     * Clean Sessions older then 1 hour 
     *
     * @access	public
     * @return	void
     */
    function __construct() {
        /* Set and validate sessions save location */
        $this->setSaveLocatione();
        /* Save user agent for validating session */
        if(isset($_SERVER['HTTP_USER_AGENT']))
            $this->data['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
        else
            $this->data['userAgent'] = "";

        /* Check if cookie is set and set sessionID */
        if (isset($_COOKIE['sessionID'])) {
            $this->data['sessionID'] = $_COOKIE['sessionID'];

            /* When session loads fails create new sessionID */
            if (!$this->load()) {
                $this->data['sessionID'] = randomString(16);
            }
        } else {
            $this->data['sessionID'] = randomString(16);
        }

        /* Save session and create new cookie */
        $this->setCookie('sessionID', $this->data['sessionID']);
        $this->save();

        /* Clean Sessions older then 1 hour */
        $this->clean();
    }

    /**
     * setSaveLocation
     *
     * Set the save location for the sessions from the config file.
     *
     * @access	private
     * @return	void
     */
    private function setSaveLocatione() {
        include __CONFIG_PATH . 'config.php';
        $saveLocation = realpath($sessionSaveLocation) . '/';
        if (is_dir($saveLocation)) {
            $this->saveLocation = $saveLocation;
        } else {
            throw new Exception('The sessions save location is not a valid directory!');
        }
    }

    /**
     * setCookie
     *
     * Creates a new cookie
     *
     * @access	private
     * @param	String    name of cookie
     * @param	String    value of cookie
     * @param	int       expire tim in seconds
     * @return	void
     */
    private function setCookie($name, $value, $expire = 3600) {
        $expire = time() + $expire;

        /* Get domain */
        $domain = $_SERVER['SERVER_NAME'];
        /* get if http or https is used */
        if (isset($_SERVER['HTTPS'])) {
            $secure = true;
        } else {
            $secure = false;
        }
        /* Create cookie */
        setcookie($name, $value, $expire, "/", $domain, $secure, true);
    }

    /**
     * save
     *
     * Saves session to file
     *
     * @access	private
     * @return	void
     */
    private function save() {
        $file = $this->saveLocation . $this->data['sessionID'];
        /* Serialize data array */
        $serdate = serialize($this->data);
        /* Open file and write serialized array to file  */
        $fh = fopen($file, 'w') or die("can't open file");
        fwrite($fh, $serdate);
        fclose($fh);
    }

    /**
     * load
     *
     * load session data
     *
     * @access	private
     * @return	boolean
     */
    private function load() {
        $file = $this->saveLocation . $this->data['sessionID'];
        /* Check file is readable */
        if (is_readable($file)) {
            /* open file and fet serialized array */
            $fh = fopen($file, 'r');
            $theData = fread($fh, filesize($file));
            fclose($fh);
            
            /* unserialize array and check if sessionID and userAgent match*/
            $data = unserialize($theData);
            if ($data['sessionID'] == $this->data['sessionID']
                    && $data['userAgent'] == $this->data['userAgent']) {
                $this->data = $data;
                return true;
            }
        }
        return false;
    }

    /**
     * clean
     *
     * Clean Sessions older then 1 hour.
     *
     * @access	private
     * @return	void
     */
    private function clean() {
        foreach (scandir($this->saveLocation) as $file) {
            /* skip files '.', '..' and '.htaccess' */
            if ($file == '.' || $file == '..' || $file == '.htaccess') {
                continue;
            }
            /* When file is modified more then one hour ago, delete file */
            $fileLocation = $this->saveLocation . $file;
            $filelastmodified = filemtime($fileLocation);
            if ((time() - $filelastmodified) > 3600) {
                unlink($fileLocation);
            }
        }
    }

    /**
     * set
     *
     * set date in sessions array and save session
     *
     * @access	public
     * @param   String  key
     * @param   -       value            
     * @return	void
     */
    function set($name, $value) {
        if ($name != "userAgent" && $name != "sessionID") {
            $this->data[$name] = $value;
            $this->save();
        }
    }
    
    /**
     * get
     *
     * Get the data out of session array, when an array is stored you can give 
     * the key for that array as second parameter 
     *
     * @access	public
     * @param   String  key
     * @param   String  secondKey  
     * @return	void
     */
    function get($key, $secondKey = false) {
        if ($key != "userAgent" && $key != "sessionID" && isset($this->data[$key])) {
            if($secondKey === false){
                return $this->data[$key];
            } else {
                return $this->data[$key][$secondKey];
            }
            
        }
        return null;
    }

}
/* End of file session.php */
/* Location: ./application/libraries/session.php */
