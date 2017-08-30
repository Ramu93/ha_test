<?php

	require_once __DIR__ . '/../db/DBConnector.php';
	require_once __DIR__ . '/../db/DBWrapper.php';
	require_once __DIR__ . '/../utils/CommonUtils.php';
	require_once __DIR__ . '/../utils/TableNameConstants.php';

	/**
	 * Token class is responsible for generating, refreshing and destroying tokens.
	 * 
	 * @author Ramu Ramasamy
	 * @version 1.0
	 */
	class Token extends DBConnector {

		private $db;
		private $userId;

		function __construct($userId){
			parent::__construct();
			$this->db = new DBWrapper($this->dbc);
			$this->userId = $userId;
		}


		/**
		 * generateToken method generates a new token for a given user.
		 * 
		 * @return token
		 */
		public function generateToken(){
			$dbDataMap = array();
			$result = array();
			$utils = new CommonUtils();
			$randomNumber = $utils->generateSixDigitRandomNumber();
			$emailMobileStr = $this->getEmailMobile($this->userId);
			$timeStamp = date("Y-m-d H:i:s");
			$safeRandomNumber = $utils->generateSafeString($randomNumber);
			$safeEmailMobileStr = $utils->generateSafeString($emailMobileStr);
			$safeTimeStamp = $utils->generateSafeString($timeStamp);
			$token = $safeRandomNumber . $safeEmailMobileStr . $safeTimeStamp;
			$dbDataMap['user_id'] = $this->userId;
			$dbDataMap['token'] = $token;
			$db = $this->db;
			$resultMap = $db->insertOperation(TOKENS, $dbDataMap);
			$result['status'] = $resultMap['status'];
			$result['token'] = $token;
			return $result;
		}

		/**
		 * refreshToken method generates a new token based on the old token provided
		 * 
		 * @param oldToken
		 * @return newToken
		 */
		public function refreshToken($oldToken){

		}

		/**
		 * getEmailMobile method fetches email and mobile number of the user based on
		 * userId given. Email and Mobile number are concatenated. 
		 * Tokens are formed using both the mobile number and email address.
		 * 
		 * @return resultMap
		 */
		private function getEmailMobile(){
			$userId = $this->userId;
			$emailMobileStr = '';
			$query = "SELECT * FROM " . USERS . " WHERE user_id='$userId'";
			$db = $this->db;
			$resultMap = $db->selectOperation($query);
			$emailMobileStr = $resultMap['result_data'][0]['email'] . $resultMap['result_data'][0]['mobile'];
			return $emailMobileStr;
		}


	}

?>