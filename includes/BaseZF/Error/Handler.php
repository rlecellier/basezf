<?php
/**
 * Handler class in /BazeZF/Error
 *
 * @category   BazeZF_Core
 * @package    BazeZF
 * @copyright  Copyright (c) 2008 BazeZF
 * @author     Harold Thétiot (hthetiot)
 */

class BaseZF_Error_Handler
{
    static private $_INSTANCE = false;

    private $_oldErrorhandler = null;
    
    private function __construct()
    {
        $this->_oldErrorhandler = set_error_handler(array($this, 'newErrorhandler'));
    }
    
    static public function getInstance()
    {
        if (!self::$_INSTANCE instanceof self) {
            self::$_INSTANCE = new self();
        }
        
        return self::$_INSTANCE;
    }
    
    public function newErrorhandler($code, $message, $file, $line)
    {
	  // if error_reporting() == 0 then it was a
	  // suppressed error with the @-operator!
	  //(We don't want to handle that kind of errors!)
        if (error_reporting() != 0) {
            $message = '(' . $this->_getErrorType($code) . ') ' . $message;
            throw new BaseZF_Error_Exception($message, $code, $file, $line);
        }
    }

    private function _getErrorType($errorNo)
    {
		$errortype = array (
            E_ERROR              => 'Error',
            E_WARNING            => 'Warning',
            E_PARSE              => 'Parsing Error',
            E_NOTICE             => 'Notice',
            E_CORE_ERROR         => 'Core Error',
            E_CORE_WARNING       => 'Core Warning',
            E_COMPILE_ERROR      => 'Compile Error',
            E_COMPILE_WARNING    => 'Compile Warning',
            E_USER_ERROR         => 'User Error',
            E_USER_WARNING       => 'User Warning',
            E_USER_NOTICE        => 'User Notice',
            E_STRICT             => 'Runtime Notice',
            E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
        );
        
		return $errortype[$errorNo];
    }
}
