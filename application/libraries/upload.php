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
 * Upload class
 *
 * Upload class for uploading a file.
 *
 * @package		MartensMCV
 * @subpackage          Libraries
 * @category            Libraries
 * @author		Maikel Martens
 */
// ------------------------------------------------------------------------

class upload {
    /* @var String contains directory where the files should be uploaded. */
    private $uploadDirectory;

    /* @var array contains the information about the file. */
    private $uploadedFile;

    /* @var String contains the file path of the uploaded file. */
    private $uploadedFilePath;

    /* @var String contains the file name of the uploaded file. */
    private $uploadedFileName;

    /* @var array contains allowed mimes. */
    private $allowedMimes;

    /* @var int contains the maximum file size. */
    private $maximumFileSize;

    /* @var String contains valid extansions */
    private $validExtensions;

    /* @var boolean value to check for images */
    private $isImage;

    /* @var boolean value to check if random strings is added to file */
    private $addRandom;

    /* @var int contains maximum width of image */
    private $maximumWidth;

    /* @var int contains maximum height of image */
    private $maximumHeight;

    /* @var array contains errors messages */
    private $errors = array();

    /**
     * Constructer
     *
     * Create uploader en set default values for:
     * $isImage         false
     * $message         array
     * $UploadedFiles   array
     *
     * @access  public
     * @return  void
     */
    function __construct() {
        /* Inlude allowed mimes */
        include __CONFIG_PATH . 'mimes.php';
        $this->allowedMimes = $mimes;

        /* Set default options */
        $this->addRandom = true;
        $this->isImage = false;
        $this->validExtensions = '*';
        $this->setUploadDirectory('data/uploads');
    }

    /**
     * getRandomString
     *
     * Creates an random string, used to add on files
     *
     * @access  private
     * @param   int     Length of the return string
     * @return  String
     */
    private function getRandomString() {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        $size = strlen($chars);
        for ($i = 0; $i < 8; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }

    /**
     * getValidFileTypes
     *
     * returns an array with valid file type mimes 
     *
     * @access  private
     * @return  array
     */
    private function getValidFileTypes() {
        $validFileTypes = array();
        if ($this->validExtensions != '*') {
            $extensions = explode('|', $this->validExtensions);

            /* Create the array with mimes only with the allowed extensions */
            foreach ($this->allowedMimes as $extension => $validMimes) {
                foreach ($extensions as $validExtension) {
                    if ($validExtension == $extension) {
                        if (is_array($validMimes)) {
                            $validFileTypes = array_merge($validFileTypes, $validMimes);
                        } else {
                            $validFileTypes[] = $validMimes;
                        }
                    }
                }
            }
        } else {
            foreach ($this->allowedMimes as $validMimes) {
                if (is_array($validMimes)) {
                    $validFileTypes = array_merge($validFileTypes, $validMimes);
                } else {
                    $validFileTypes[] = $validMimes;
                }
            }
        }
        return $validFileTypes;
    }

    /**
     * validateExtension
     *
     * Checks if the file match the given valid extension and file types, 
     * when validExtension not is set it wil return true;
     *
     * @access  private
     * @return  boolean
     */
    private function validateExtension() {
        if ($this->validExtensions != '*') {
            /* Get file extension */
            $x = explode('.', $this->uploadedFile['name']);
            $extension = strtolower( $x[count($x) - 1] );

            /* Get file type */
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $type = finfo_file($finfo, $this->uploadedFile['tmp_name']);

            $extensions = explode('|', $this->validExtensions);

            /* When it's not a valid extension or file create message */
            if (!in_array($extension, $extensions) || !in_array($type, $this->getValidFileTypes())) {
                $this->errors[] = "File type is not allowed!";
                return false;
            }
        }
        return true;
    }

    /**
     * validateSize
     *
     * Checks if the files are not biger then given maximumFileSize, when maximumFileSize
     * not is set it wil return true;
     *
     * @access  private
     * @return  boolean
     */
    private function validateSize() {
        if (!empty($this->maximumFileSize)) {
            if ($this->uploadedFile['size'] > $this->maximumFileSize) {
                $size = (int) ($this->uploadedFile['size'] / 1000);
                $this->errors[] = "File is too big. It must be less than "
                        . $this->maximumFileSize / 1000 . " kB and it is " . $size . " kB.";
                return false;
            }
        }
        return true;
    }

    /**
     * validateImages
     *
     * Checks if the files are images and not biger then given maximumWidth and
     * maximumHeight.
     *
     * @access  private
     * @return  boolean
     */
    private function validateImages() {
        if ($this->isImage) {
            $controle = true;

            $imageDate = getimagesize($this->uploadedFile['tmp_name']);

            /* Validate if file is a image */
            if ($imageDate !== false && ($imageDate['mime'] == "image/png" || $imageDate['mime'] == "image/jpeg" || $imageDate['mime'] == "image/jpeg")) {

                /* Validate size of the image  */
                if ($imageDate [0] > $this->maximumWidth) {
                    $controle = false;
                    $this->errors[] = "Image exceeds the maximum width of " . $this->maximumWidth . "px.";
                }
                if ($imageDate [1] > $this->maximumHeight) {
                    $controle = false;
                    $this->errors[] = "Image exceeds the maximum height of " . $this->maximumHeight . "px.";
                }
            } else {
                $controle = false;
                $this->errors[] = "File is not a valid image!";
            }
        
        return $controle;
        }
        return true;
    }

    /**
     * uploadFile
     *
     * Upload file gives boolean when file gives a error message.
     *
     * @access  public
     * @return  boolean
     */
    public function uploadFile() {
        $file = $this->uploadedFile;
        if (!is_uploaded_file($file['tmp_name'])) {
            $this->errors[] = "No file uploaded";
            return false;
        }
        $controle = true;

        /* Validate the file size */
        if (!$this->validateSize()) {
            $controle = false;
        }

        /* Validate the file extenions */
        if (!$this->validateExtension()) {
            $controle = false;
        }

        /* Validate images when isImages is true */
        if (!$this->validateImages()) {
            $controle = false;
        }

        /* move files to uploadDirectory */
        if ($controle) {

            /* Add random string to file when addRandom is set to true */
            $randStr = $this->addRandom ? $this->getRandomString().'_' : '';
            $fileDestination = $this->uploadDirectory . '/' . $randStr . str_replace(' ', '_', $file['name']);

            /* Validate if file not already exists */
            if($this->addRandom) {
                while (file_exists($fileDestination)) 
                    $fileDestination = $this->uploadDirectory . '/' . $this->randomString(10) . "_" . str_replace(' ', '_', $file['name']);
            } else {
                if(file_exists($fileDestination)){
                    $this->errors[] = "There is already a file uploaded named ".$file['name'];
                    return false;
                }
            }

            /* move the file */
            if (!move_uploaded_file($file['tmp_name'], $fileDestination)) {
                $this->errors[] = "File could not be uploaded!";
                $controle = false;
            } else {
                $name = explode('/', $fileDestination);
                $this->uploadedFilePath = $fileDestination;
                $this->uploadedFileName = $name[count($name) - 1];
            }
        }
        return $controle;
    }

    /**
     * Loadfile
     *
     * Load a single file in class throws Exception if not all the array field 
     * are set or when no array is given
     *
     * @access  public
     * @param   array   
     * @return  void
     */
    public function loadFile($file) {
        if (is_array($file)) {
            if (isset($file['name']) && isset($file['type']) && isset($file['tmp_name']) && isset($file['size'])) {
                $this->errors = array();
                $this->uploadedFilePath = '';
                $this->uploadedFileName = '';
                $this->uploadedFile = $file;
            } else {
                throw new Exception("Not evrything is set in array in loadFile!");
            }
        } else {
            throw new Exception("No array given when array expected in loadFile!");
        }
    }

    /**
     * setUploadDirectory
     *
     * Set the directory path from __SITE_PATH, throws Exception when not valid directory is given.
     *
     * @access  public
     * @param   String  path  
     * @return  void
     */
    public function setUploadDirectory($path) {
        $path = realpath(__SITE_PATH . $path).'/';
        if (is_writable($path)) {
            $this->uploadDirectory = $path;
        } else {
            throw new Exception("No valid directory was given or not writeable in setUploadDirectory! " . $path);
        }
    }

    /**
     * setMaximumFileSize
     *
     * Set the maximum file size in kB, throws Exception when no int is given.
     *
     * @access  public
     * @param   int     size  
     * @return  void
     */
    public function setMaximumFileSize($size) {
        if (is_numeric($size)) {
            $this->maximumFileSize = (int) ($size * 1000);
        } else {
            throw new Exception("No int given when int expected in setMaximumFileSize!");
        }
    }

    /**
     * setValidExtensions
     *
     * Set the valid extensions in a string separated by | 
     * throws Exception when no String is given.
     *
     * @access  public
     * @param   String    Extensions  
     * @return  void
     */
    public function setValidExtensions($extensions) {
        if (is_string($extensions)) {
            $this->validExtensions = $extensions;
        } else {
            throw new Exception("No String was given when String expected in setValidExtensions!");
        }
    }

    /**
     * setIsImage
     *
     * Set the isImage boolean
     * throws Exception when no boolean is given.
     *
     * @access  public
     * @param   boolean     boolean
     * @return  void
     */
    public function setIsImage($boolean) {
        if (is_bool($boolean)) {
            $this->isImage = $boolean;
        } else {
            throw new Exception("No boolean given when boolean expected in setIsImage!");
        }
    }

    /**
     * setAddRandomString
     *
     * When set it wil add random string to file, if file already exsist it 
     * also will add random string.
     * throws Exception when no boolean is given.
     *
     * @access  public
     * @param   boolean     boolean
     * @return  void
     */
    public function setAddRandomString($boolean) {
        if (is_bool($boolean)) {
            $this->addRandom = $boolean;
        } else {
            throw new Exception("No boolean given when boolean expected in setAddRandomString!");
        }
    }

    /**
     * setMaximumWidth
     *
     * Set the maximum width for images
     * throws Exception when no int is given.
     *
     * @access  public
     * @param   int     width
     * @return  void
     */
    public function setMaximumWidth($width) {
        if (is_numeric($width)) {
            $this->maximumWidth = $width;
        } else {
            throw new Exception("No int given when int expected in setMaximumWidth!");
        }
    }

    /**
     * setMaximumHeight
     *
     * Set the maximum height for images
     * throws Exception when no int is given.
     *
     * @access  public
     * @param   int     height
     * @return  void
     */
    public function setMaximumHeight($height) {
        if (is_numeric($height)) {
            $this->maximumHeight = $height;
        } else {
            throw new Exception("No int given when int expected in setMaximumHeight!");
        }
    }

    /**
     * getErrors
     *
     * Get error messages, returns array when there are no messages it wil return a
     * empty array.
     *
     * @access  public
     * @return  array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * getFilePath
     *
     * Get the filepath where the uploaded file is moved to
     *
     * @access  public
     * @return  String
     */
    public function getFilePath(){
        return $this->uploadedFilePath;
    }

    /**
     * getFileName
     *
     * Get the filename of the moved uplouded file.
     *
     * @access  public
     * @return  String
     */
    public function getFileName(){
        return $this->uploadedFileName;

    }
}
/* End of file upload.php */
/* Location: ./application/libraries/upload.php */