<?php

	require_once __DIR__ . '/../utils/Constants.php';
	require_once __DIR__ . '/../log4php/Logger.php';
	Logger::configure(__DIR__ . '/../config/logger-config.xml');

	class DBConnector {
		//Set the configuration of your MySQL server
		private $dbServername = 'localhost';
		private $dbUsername = 'root';
		private $dbPassword = 'root';
		private $dbName = 'test_honeycakes';

		protected $dbc; 
		protected $log;

		function __construct(){
			$this->log = Logger::getLogger(__CLASS__);
			$this->dbc = mysqli_connect ($this->dbServername,$this->dbUsername,$this->dbPassword,$this->dbName);
			if (mysqli_connect_errno()) {
			    echo "Could not establish database connection!<br>";
			    exit();
			}
		}

		protected function closeConnection(){
			$this->log->info(__FUNCTION__ . SPACE . METHOD_STARTS);
			mysqli_close($this->dbc);
			$this->log->info(__FUNCTION__ . SPACE . METHOD_ENDS);
		}

	}

?>