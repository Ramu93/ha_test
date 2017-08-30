<?php
	
	require_once __DIR__ . '/../utils/Constants.php';
	require_once __DIR__ . '/../lib/log4php/Logger.php';
	Logger::configure(__DIR__ . '/../config/logger-config.xml');

	/**
	 * Honey is the root class for all the classes under biz directory.
	 * 
	 * @author Ramu Ramasamy
	 * @version 1.0
	 */
	abstract class Honey {

		protected $log;

		/**
		 * instantiates log4php.
		 */
		function __construct(){
			$this->log = Logger::getLogger(__CLASS__);
		}

		/**
		 * exexuteAction method is used to get the execute the requested action based on the 
		 * HTTP method.
		 */
		abstract public function executeAction();

	}
	
?>