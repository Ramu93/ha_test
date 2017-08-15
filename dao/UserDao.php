<?php
	
	require_once __DIR__ . '/../db/DBConnector.php';
	require_once __DIR__ . '/../db/DBWrapper.php';

	class UserDao extends DBConnector {

		private $db;
		private $tableName = 'users';

		function __construct(){
			$this->establishConnection();
			$this->db = new DBWrapper($this->dbc);
		}

		public function getUser($userId){
			$query = "SELECT * FROM $this->tableName WHERE user_id='$userId'";
			$db = $this->db;
			$resultMap = $db->selectOperation($query);
			$this->closeConnection();
			$result['rowCount'] = $resultMap['row_count'];
			for($iterator = 0; $iterator < $resultMap['row_count']; $iterator++){
				$result['resultData'][$iterator]['firstName'] = $resultMap['result_data'][$iterator]['first_name'];
				$result['resultData'][$iterator]['lastName'] = $resultMap['result_data'][$iterator]['last_name'];
				$result['resultData'][$iterator]['email'] = $resultMap['result_data'][$iterator]['email'];
				$result['resultData'][$iterator]['mobile'] = $resultMap['result_data'][$iterator]['mobile'];
				$result['resultData'][$iterator]['dob'] = $resultMap['result_data'][$iterator]['date_of_birth'];
			}
			return $result;
		}

		public function createUser($inputDataMap){			
			$dbDataMap = array();
			$dbDataMap['first_name'] = $inputDataMap['firstName'];
			$dbDataMap['last_name'] = $inputDataMap['lastName'];
			$dbDataMap['email'] = $inputDataMap['email'];
			$dbDataMap['mobile'] = $inputDataMap['mobile'];
			$dbDataMap['date_of_birth'] = $inputDataMap['dob'];
			$db = $this->db;
			$resultMap = $db->insertOperation($this->tableName, $dbDataMap);
			$this->closeConnection();
			$result['status'] = $resultMap['status'];
			$result['lastCreatedUserId'] = $resultMap['last_insert_id'];
			$result['affectedRows'] = $resultMap['affected_rows'];
			return $result;
		}

		public function updateUser($inputDataMap, $userId){
			$dbDataMap = array();
			$dbDataMap['first_name'] = $inputDataMap['firstName'];
			$dbDataMap['last_name'] = $inputDataMap['lastName'];
			$dbDataMap['email'] = $inputDataMap['email'];
			$dbDataMap['mobile'] = $inputDataMap['mobile'];
			$dbDataMap['date_of_birth'] = $inputDataMap['dob'];

			$conditionMap = array();
			$conditionMap['user_id'] = $userId;
			$db = $this->db;
			$resultMap = $db->updateOperation($this->tableName, $dbDataMap, $conditionMap);
			$this->closeConnection();
			$result['status'] = $resultMap['status'];
			$result['affectedRows'] = $resultMap['affected_rows'];
			return $result;
		}
		
	}
	
?>