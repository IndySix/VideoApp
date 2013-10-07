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
 * uri class
 *
 * Gives url segements
 *
 * @package		MartensMCV
 * @subpackage          Core
 * @category            Core
 * @author		Maikel Martens
 */
// ------------------------------------------------------------------------

class uri {

    private $segments;
    
    /**
     *
     * constructer
     * 
     * Create the object and set the segments
     *
     * @return  void
     *
     */
    function __construct($segments = array()) {
        $this->segments = $segments;
    }
    
    /**
     *
     * segment
     * 
     * Returns specific segment.
     *
     * @param   int     Segment to return  .
     * @param   -       Set value to return when segment is not set.
     * @return  String
     *
     */
    public function segment($number, $returnValue = FALSE){
        if(isset($this->segments[$number-1])){
            return $this->segments[$number-1];
        }
        return $returnValue;
    }
    
     /**
     *
     * uri_string
     * 
     * Gives back the uri string
     *
     * @return  String
     *
     */
    public function uri_string(){
        return implode('/', $this->segments);
    }
}
/* End of file uri.php */
/* Location: ./application/core/uri.php */