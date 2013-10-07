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
 * Model class
 *
 * Super class extends for models
 *
 * @package		MartensMCV
 * @subpackage          Core
 * @category            Core
 * @author		Maikel Martens
 */
// ------------------------------------------------------------------------

class model {

    /**
     * __get
     *
     * Allows models to access Controller's loaded classes using the same
     * syntax as controllers.
     *
     * @param	string
     * @access private
     */
    function __get($key) {
        $c = controller::get_instance();
        return $c->$key;
    }

}

/* End of file model.php */
/* Location: ./application/core/model.php */