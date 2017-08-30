<?php

	require_once __DIR__ . '/../db/DBConnector.php';
	require_once __DIR__ . '/../db/DBWrapper.php';
	require_once __DIR__ . '/../utils/CommonUtils.php';
	require_once __DIR__ . '/../utils/TableNameConstants.php';

	/**
	 * Authenticator class is responsible user authentication.
	 * 
	 * @author Ramu Ramasamy
	 * @version 1.0
	 */
	class Authenticator extends DBConnector {

		private $db;
		private $loginId;
		private $userInputPassword;
		private $usersTable = 'users';
		private $credentialsTable = 'user_credentials';

		function __construct($loginId, $userInputPassword){
			parent::__construct();
			$this->db = new DBWrapper($this->dbc);
			$this->loginId = $loginId;
			$this->userInputPassword = $userInputPassword;
		}

		/**
		 * checkIfUserExists method checks if the provided user exists.
		 * 
		 * @return isAvailable
		 */
		public function checkIfUserExists(){
			$loginId = $this->loginId;
			$isAvailable = false;
			$query = "SELECT count(*) as 'count' FROM " . USERS . " WHERE email='$loginId' OR mobile='$loginId'";
			$db = $this->db;
			$resultMap = $db->selectOperation($query);
			if($resultMap['result_data'][0]['count'] == 1){
				$isAvailable = true;
			}
			return $isAvailable;
		}

		/**
		 * comparePassword method compares the password provied by the user and the password
		 * available in the DB.
		 * 
		 * @return isMatching
		 */
		public function comparePassword() {
			$loginId = $this->loginId;
			$userInputPassword = $this->userInputPassword;
			$isMatching = false;
			$utils = new CommonUtils();
			$query = "SELECT uc.password FROM " . USERS . " u, " . USER_CREDENTIALS . " uc WHERE (u.email='$loginId' OR u.mobile='$loginId') AND u.user_id=uc.user_id";
			$db = $this->db;
			$resultMap = $db->selectOperation($query);
			if($resultMap['result_data'][0]['password'] == $utils->generateSafeString($userInputPassword)){
				$isMatching = true;
			}
			// file_put_contents("testlog.log", "\n".print_r($resultMap, true), FILE_APPEND | LOCK_EX);
			return $isMatching;
		}

		/**
		 * getUserId method fetches userId provided the loginId (email or mobile).
		 * 
		 * @return isAvailable
		 */
		public function getUserId(){
			$loginId = $this->loginId;
			$userId = '';
			$query = "SELECT user_id FROM " . USERS . " WHERE email='$loginId' OR mobile='$loginId'";
			$db = $this->db;
			$resultMap = $db->selectOperation($query);
			// file_put_contents("testlog.log", "\n".print_r($resultMap, true), FILE_APPEND | LOCK_EX);
			$userId = $resultMap['result_data'][0]['user_id'];
			return $userId;
		}

	}

?>