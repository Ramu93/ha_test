<?php

	class DBConnector {
		//Set the configuration of your MySQL server
		private $dbServername = 'localhost';
		private $dbUsername = 'root';
		private $dbPassword = 'root';
		private $dbName = 'test_honeycakes';

		protected $dbc; 

		function establishConnection(){
			$this->dbc = mysqli_connect ($this->dbServername,$this->dbUsername,$this->dbPassword,$this->dbName);
			if (mysqli_connect_errno()) {
			    echo "Could not establish database connection!<br>";
			    exit();
			}
		}

		protected function closeConnection(){
			mysqli_close($this->dbc);
		}

	}

?>