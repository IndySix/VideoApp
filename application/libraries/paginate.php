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
 * Paginate
 *
 * Generates pages numbers and forward back buttons.
 * 
 * @package		MartensMCV
 * @subpackage          Libraries
 * @category            Libraries
 * @author		Maikel Martens
 */
// ------------------------------------------------------------------------
class paginate {
    /* @var int contains total items. */
    private $itemsTotal;
    
    /* @var int cantains items shown per page. */
    private $itemsPerPage;
    
    /* @var int contains current page showing. */
    private $currentPage;
    
    /* @var int contains how long the range is to been shown. */
    private $range;
    
    /* @var int contains how many pages there are. */
    private $numPages;
    
    /* @var String contains the base url. */
    private $baseURL;
    
    /**
     * Constructer
     *
     * Create paginate en set default values.
     *
     * @access	public
     * @return	void
     */
    function __construct() {
        /* default values */
        $this->currentPage = 1;
        $this->itemsPerPage = 25;
        $this->range = 7;
    }
    
    /**
     * getPaginate
     *
     * Create the paginate and returns it as an string.
     *
     * @access	public
     * @return	String
     */
    function getPaginate(){
        if(!empty($this->itemsTotal)){
            $this->setNumPages();
            /* Start of paginate */
            $paginate = '<div class="paginate">'."\n";
            
            /* Set page back link */
            if(($pageBack = $this->currentPage-1) >= 1){
                $paginate .='<a href="'.$this->baseURL.$pageBack.'"><</a> '."\n";
            }
            
            /* set the begin of the paginate and check if not negative */
            if(($begin = $this->currentPage - (int)($this->range/2)) < 1){
                $begin = 1;
            }
            
            /* create pages links */
            $totaal = $begin+$this->range;
            for($begin; $begin < $totaal; $begin++) {
                if($begin == $this->currentPage){
                    $paginate .= '<span class="current">'.$begin.'</span> '."\n";
                } else {
                    $paginate .= '<a href="'.$this->baseURL.$begin.'">'.$begin."</a>\n";
                }
                if($begin >= $this->numPages){
                    break;
                }
            }
            
            /* set page forward */
            if(($pageForward = $this->currentPage+1) <= $this->numPages){
                $paginate .='<a href="'.$this->baseURL.$pageForward.'">></a> '."\n";
            }
            /* end paginate */
            $paginate .='</div>'."\n";
            return $paginate;
        }
        return false;
    }
    
    /**
     * setNumPages
     *
     * Sets the number of pages.
     *
     * @access	private
     * @return	void
     */
    private function setNumPages(){
        $numPages = (int)($this->itemsTotal / $this->itemsPerPage);
        if($this->itemsTotal % $this->itemsPerPage != 0){
            $numPages+=1;
        }
        $this->numPages = $numPages;
    }
    
    /**
     * getLimitStart
     *
     * Retruns in value where the limit start.
     *
     * @access	public
     * @return	int
     */
    function getLimitStart(){
        $page = $this->currentPage-1;
        return $page * $this->itemsPerPage;
    }
    
    /**
     * getLimitMany
     *
     * Retruns in value how many rows must be returns by limit.
     *
     * @access	public
     * @return	int
     */
    function getLimitMany(){
        return $this->itemsPerPage;
    }
    
    /**
     * setItemsTotal
     *
     * Set the total items in list.
     *
     * @access	public
     * @param   int
     * @return	boolean
     */
    function setItemsTotal($total) {
        if(is_numeric($total) && $total > 0){
            $this->itemsTotal = $total;
            return true;
        }
        return false;
    }
    
    /**
     * setItemsPerPage
     *
     * Set the amount of items to show.
     *
     * @access	public
     * @param   int
     * @return	boolean
     */
    function setItemsPerPage($items){
        if(is_numeric($items) && $items > 0){
            $this->itemsPerPage = $items;
            return true;
        }
        return false;
    }
    
    /**
     * setCurrentPage
     *
     * Set the current page number.
     *
     * @access	public
     * @param   int
     * @return	boolean
     */
    function setCurrentPage($currenPage) {
        if(is_numeric($currenPage) && $currenPage > 0) {
            $this->currentPage = $currenPage;
            return true;
        }
        return false;
    }
    
    /**
     * setBaseUrl
     *
     * Set the base url used in the links. Give the controller/action.
     * Example: setBaseUrl('home/index');
     *
     * @access	public
     * @param   String  controller/action
     * @return	boolean
     */
    function setBaseUrl($url){
        $this->baseURL = baseUrl($url).'/';
    }
}
/* End of file paginate.php */
/* Location: ./application/libraries/paginate.php */