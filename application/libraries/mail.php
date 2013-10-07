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
 * Mail class
 *
 * Mail class for sending simple emails in plain text or HTML.
 * 
 * @package		MartensMCV
 * @subpackage          Libraries
 * @category            Libraries
 * @author		Maikel Martens
 */
// ------------------------------------------------------------------------
class mail {
    /* @var Array contains to email-addresses where email is send to. */

    private $to;

    /* @var Array contains cc email-addresses where email is send to. */
    private $cc;

    /* @var Array contains bcc email-addresses where email is send to. */
    private $bcc;

    /* @var String contains name where email is from. */
    private $from;

    /* @var String contains email-address where email is from and where to reply to. */
    private $replyto;

    /* @var String contains email subject. */
    private $subject;

    /* @var String contains email message. */
    private $message;

    /* @var Boolean contains if html is used in email. */
    private $isHTMl;

    /* @var String contains error message. */
    private $error;

    /**
     * Constructer
     *
     * Create mail en set default values.
     *
     * @access	public
     * @return	void
     */
    public function __construct() {
        /* Load config */
        include __CONFIG_PATH.'config.php';
        $this->setFrom($defaultEmail, $defaultEmailName);
        
        $this->to = array();
        $this->cc = array();
        $this->bcc = array();
        $this->isHTMl = false;
    }

    /**
     * send
     *
     * Send email returns true when succeeded, false when failed, get error
     * with getError()
     *
     * @access	public
     * @return	boolean
     */
    public function send() {
        /* Checks if there are recipients */
        if (empty($this->to)) {
            $this->error = 'Error no recipients to send mail to!';
            return false;
        }
        /* Checks if there is a subject */
        if (empty($this->subject)) {
            $this->error = 'Error no subject is set!';
            return false;
        }
        /* Checks if there is a message */
        if (empty($this->message)) {
            $this->error = 'error no message is set!';
            return false;
        }
        /* Send mail, when fails create message return false */
        $to = implode(',', $this->to);
        if (mail($to, $this->subject, $this->message, $this->createHeader())) {
            return true;
        } else {
            $this->error = "Mail could not been sent!";
            return false;
        }
    }

    /**
     * createHeader
     *
     * Creates the mail header
     *
     * @access	public
     * @return	String  header
     */
    private function createHeader() {
        $headers = "";
        /* when isHTML set add headers for html email */
        if ($this->isHTMl) {
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        }
        /* When from is set add headers for from and replyto */
        if (!empty($this->from)) {
            $headers .= 'From: ' . $this->from . ' <' . $this->replyto . '>' . "\r\n";
            $headers .= 'Reply-To: ' . $this->replyto . "\r\n";
        }
        /* When cc is set add header for cc emails */
        if (!empty($this->cc)) {
            $headers .= 'Cc: ' . implode(',', $this->cc) . "\r\n";
        }
        /* When bcc is set add header for bcc emails */
        if (!empty($this->bcc)) {
            $headers .= 'Bcc: ' . implode(',', $this->bcc) . "\r\n";
        }
        return $headers;
    }

    /**
     * validateEmail
     *
     * Checks if the given String is a valid email adres
     *
     * @access	public
     * @param   String  email
     * @return	boolean
     */
    private function validateEmail($email) {
        if (false !== filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * addTo
     *
     * Add email adres to the send-to array. gives true when valid email is given,
     * gives false when invalid email is given.
     * 
     * Throw Exception when no String is given!
     *
     * @access	public
     * @param   String  email
     * @return	boolean
     */
    public function addTo($email) {
        if (is_string($email)) {
            if ($this->validateEmail($email)) {
                $this->to[] = $email;
                return true;
            }
            return false;
        } else {
            throw new Exception("No strings given when Strings expected in addTo!");
        }
    }

    /**
     * addCc
     *
     * Add email adres to the cc array. gives true when valid email is given,
     * gives false when invalid email is given.
     * 
     * Throw Exception when no String is given!
     *
     * @access	public
     * @param   String  email
     * @return	boolean
     */
    public function addCc($email) {
        if (is_string($email)) {
            if ($this->validateEmail($email)) {
                $this->cc[] = $email;
                return true;
            }
            return false;
        } else {
            throw new Exception("No strings given when Strings expected in addCc!");
        }
    }

    /**
     * addBcc
     *
     * Add email adres to the bcc array. gives true when valid email is given,
     * gives false when invalid email is given.
     * 
     * Throw Exception when no String is given!
     *
     * @access	public
     * @param   String  email
     * @return	boolean
     */
    public function addBcc($email) {
        if (is_string($email)) {
            if ($this->validateEmail($email)) {
                $this->bcc[] = $email;
                return true;
            }
            return false;
        } else {
            throw new Exception("No strings given when Strings expected in addBcc!");
        }
    }

    /**
     * setFrom
     *
     * Add email adres to replyto and name to from. gives true when valid email is given,
     * gives false when invalid email is given.
     * 
     * Throw Exception when no String is given!
     *
     * @access	public
     * @param   String  email
     * @param   String  name
     * @return	boolean
     */
    public function setFrom($email, $name) {
        if (is_string($email) && is_string($name)) {
            $this->from = $name;
            if ($this->validateEmail($email)) {
                $this->replyto = $email;
                return true;
            }
            return false;
        } else {
            throw new Exception("No strings given when Strings expected in addFrom!");
        }
    }

    /**
     * setSubject
     *
     * Set subject of the email.
     * 
     * Throw Exception when no String is given!
     *
     * @access	public
     * @param   String  subject
     * @return	void
     */
    public function setSubject($subjec) {
        if (is_string($subjec)) {
            $this->subject = $subjec;
        } else {
            throw new Exception("No strings given when Strings expected in setSubject!");
        }
    }

    /**
     * setMessage
     *
     * Set message of the email.
     * 
     * Throw Exception when no String is given!
     *
     * @access	public
     * @param   String  message
     * @return	void
     */
    public function setMessage($message) {
        if (is_string($message)) {
            $this->message = $message;
        } else {
            throw new Exception("No strings given when Strings expected in setMessage!");
        }
    }

    /**
     * setIsHTML
     *
     * Set isHTMl to enable HTML in email. 
     * 
     * Throw Exception when no boolean is given!
     *
     * @access	public
     * @param   boolean  
     * @return	void
     */
    public function setIsHTML($boolean) {
        if (is_bool($boolean)) {
            $this->isHTMl = $boolean;
        } else {
            throw new Exception("No boolean given when boolean expected in setIsHTML!");
        }
    }

    /**
     * getError
     *
     * When error is set, returns String with error message. when no error set
     * return false.
     *
     * @access	public
     * @return	String
     */
    public function getError() {
        if (empty($this->error)) {
            return false;
        }
        return $this->error;
    }

}
/* End of file mail.php */
/* Location: ./application/libraries/mail.php */