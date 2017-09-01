<?php
	
	require_once 'auth/Authenticator.php';
	require_once 'auth/Token.php';
	require_once 'utils/Constants.php';
	require_once 'utils/CaseConstants.php';

	require_once 'biz/User.php';
	require_once 'biz/Cake.php';
	
	/**
	 * Honeycakes REST API - handles all HTTP requests and responses.
	 * 
	 * @author Ramu Ramasamy
	 * @version 1.0
	 */

	/** Default time zone set to India */
	date_default_timezone_set('Asia/Calcutta');

	/** Gets the method type */
	$method = $_SERVER['REQUEST_METHOD'];

	$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));

	/** Gets the JSON input from HTTP request and converts it to an associative array */
	$data = json_decode(file_get_contents('php://input'), true);

	/** 
	 * $case holds the ID value that is passed as the second param in the URL. Default value
	 * for $case is -1
	 */
	$case = $request[0];
	$caseId = -1;
	if(count($request) > 1){
		$caseId = $request[1];
	}

	/** token from URL */
	$token = '';

	if($case == CASE_LOGIN){
		$response = loginUser();
	} else {
		if(isset($_GET['token'])){
			$token = $_GET['token'];
		}
	}

	/** checks if the token is valid. If the token is valid then the case is executed */
	if( $token != '' && isTokenValid($token) ){
		/** determines the case and executes the corresponding method */
		switch ($case) {
		  	case CASE_USER:
			  	$response = executeUserCase();
			  	break;
		  	case CASE_CAKES:
			  	$response = executeCakeCase();
			  	break;
		}
	} else if($case != CASE_LOGIN){
		$response = array();
		$response['status'] = UNAUTH_ACCESS;
		$cause = getTokenInvalidCause($token);
		if($cause == UNAUTH_1){
			$message = UNAUTH_CAUSE_1;
			setHttpStatus(401);
		} else {
			$message = UNAUTH_CAUSE_2;
			setHttpStatus(408);
		}
		$response['message'] = $message;
	}

	/** API Response - JSON */
	header('Content-Type: application/json');
	echo json_encode($response);


	/** 
	* setHttpStatus method is used to set the http status code  
	* 
	* @param statusCode 
	*/
	function setHttpStatus($statusCode) {
		http_response_code($statusCode);
	}

	/**
	 * isTokenValid method checks if the token is valid.
	 * 
	 * @param token
	 * @return isValid
	 */
	function isTokenValid($token){
		$tokenObj = new Token();
		$isValid = ($tokenObj->checkTokenValidity($token))['isTokenValid'];
		return $isValid;
	}

	/**
	 * getTokenInvalidCause method finds the reason what made the token ivalid.
	 * 
	 * @param token
	 * @return isValid
	 */
	function getTokenInvalidCause($token){
		$tokenObj = new Token();
		$cause = ($tokenObj->checkTokenValidity($token))['cause'];
		return $cause;
	}

	/**
	 * getUserId method fetches userId based on the token given.
	 * 
	 * @param token
	 * @return isValid
	 */
	function getUserId($token){
		$tokenObj = new Token();
		$userId = $tokenObj->getUserId($token);
		return $userId;
	}
	
	/**
	 * loginUser method establishes a new session for the user by generating a token.
	 * 
	 * @return response	
	 */
	function loginUser(){
		global $method, $data;
		$response = array();
		if($method == 'POST'){
			$auth = new Authenticator($data['loginId'], $data['password']);
			if($auth->checkIfUserExists()){
				if($auth->comparePassword()){
					$userId = $auth->getUserId();
					$tokenObj = new Token();
					$response = $tokenObj->generateToken($userId);
					$tokenObj->closeConnection(); //closes all the established connections
				} else {
					$response['status'] = FAILURE;
					$response['message'] = INCORRECT_PASSWORD;
				}
			} else {
				$response['status'] = FAILURE;
				$response['message'] = USER_DOES_NOT_EXIST;
			}
		} else {
			$response['status'] = FAILURE;
			$response['message'] = INVALID_REQUEST;
		}
		return $response;
	}

	/** 
	 * executeUserCase method instantiates User object and executes the action. 
	 * 
	 * @return $response
	 */
	function executeUserCase(){
		global $method, $data, $caseId;
		$user = new User($caseId, $method, $data);
	  	$response = $user->executeAction();
		// file_put_contents("testlog.log", "\n".print_r($response, true), FILE_APPEND | LOCK_EX);
		return $response;
	}

	/** 
	 * executeCakeCase method instantiates Cake object and executes the action. 
	 * 
	 * @return $response
	 */
	function executeCakeCase(){
		global $method, $data, $caseId;
		$cake = new Cake($caseId, $method, $data);
		$response = $cake->executeAction();
		// file_put_contents("testlog.log", "\n".print_r($response, true), FILE_APPEND | LOCK_EX);
		return $response;
	}
	 

?>