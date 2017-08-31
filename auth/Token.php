<?php

	require_once __DIR__ . '/../db/DBConnector.php';
	require_once __DIR__ . '/../db/DBWrapper.php';
	require_once __DIR__ . '/../utils/CommonUtils.php';
	require_once __DIR__ . '/../utils/Constants.php';
	require_once __DIR__ . '/../utils/TableNameConstants.php';

	/**
	 * Token class is responsible for generating, refreshing and token based operations.
	 * 
	 * @author Ramu Ramasamy
	 * @version 1.0
	 */
	class Token extends DBConnector {

		private $db;

		function __construct(){
			parent::__construct();
			$this->db = new DBWrapper($this->dbc);
		}

		/**
		 * generateToken method generates a new token for a given user.
		 * 
		 * @return token
		 */
		public function generateToken($userId){
			$dbDataMap = array();
			$result = array();
			$utils = new CommonUtils();
			$randomNumber = $utils->generateSixDigitRandomNumber();
			$emailMobileStr = $this->getEmailMobile($userId);
			$currentTimeStamp = date("Y-m-d H:i:s");
			$safeRandomNumber = $utils->generateSafeString($randomNumber);
			$safeEmailMobileStr = $utils->generateSafeString($emailMobileStr);
			$safeTimeStamp = $utils->generateSafeString($currentTimeStamp);
			$token = $safeRandomNumber . $safeEmailMobileStr . $safeTimeStamp;
			$validTillTime = date("Y-m-d H:i:s", strtotime('+48 hours'));
			$dbDataMap['user_id'] = $userId;
			$dbDataMap['token'] = $token;
			$dbDataMap['valid_till'] = $validTillTime;
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
			$userId = $this->getUserId($oldToken);
			$result = $this->generateToken($userId);
			$newToken = $result['token'];
			return $newToken;
		}

		/**
		 * checkTokenValidity method determines whether the passed token is expired.
		 * returns false if the token is expired
		 * returns true if the token is vaild
		 * 
		 * @param token
		 * @return tokenData
		 */
		public function checkTokenValidity($token){
			$tokenData = array();
			$tokenData['isTokenValid'] = true;
			$query = "SELECT * FROM " . TOKENS . " WHERE token='$token'";
			$db = $this->db;
			$resultMap = $db->selectOperation($query);
			if($resultMap['rowCount'] > 0){
				$expiringTime = $resultMap['result_data'][0]['valid_till'];
				$currentTimeStamp = date("Y-m-d H:i:s");
				if($currentTimeStamp >= $expiringTime){
					$tokenData['isTokenValid'] = false;
					$tokenData['cause'] = UNAUTH_2;
				}
			} else {
				$tokenData['isTokenValid'] = false;
				$tokenData['cause'] = UNAUTH_1;
			}
			
			return $tokenData;
		}

		/**
		 * getUserId method fetches the userId based on the given token.
		 * 
		 * @param token
		 * @return userId
		 */
		public function getUserId($token){
			$query = "SELECT user_id FROM " . TOKENS . " WHERE token='$token'";
			$db = $this->db;
			$resultMap = $db->selectOperation($query);
			$userId = $resultMap['result_data'][0]['user_id'];
			return $userId;
		}

		/**
		 * getEmailMobile method fetches email and mobile number of the user based on
		 * userId given. Email and Mobile number are concatenated. 
		 * Tokens are formed using both the mobile number and email address.
		 * 
		 * @return resultMap
		 */
		private function getEmailMobile($userId){
			$emailMobileStr = '';
			$query = "SELECT * FROM " . USERS . " WHERE user_id='$userId'";
			$db = $this->db;
			$resultMap = $db->selectOperation($query);
			$emailMobileStr = $resultMap['result_data'][0]['email'] . $resultMap['result_data'][0]['mobile'];
			return $emailMobileStr;
		}


	}

?>