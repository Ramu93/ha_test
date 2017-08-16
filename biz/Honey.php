<?php
	
	require_once __DIR__ . '/../utils/Constants.php';
	require_once __DIR__ . '/../log4php/Logger.php';
	Logger::configure(__DIR__ . '/../config/logger-config.xml');

	abstract class Honey {

		protected $log;

		function __construct(){
			$this->log = Logger::getLogger(__CLASS__);
		}

		abstract public function executeAction();

	}
	
?>