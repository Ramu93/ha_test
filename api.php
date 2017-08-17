<?php

	require_once 'biz/User.php';
	require_once 'biz/Cake.php';
	
	/**
	 * @author Ramu Ramasamy
	 */


	/** Gets the method type */
	$method = $_SERVER['REQUEST_METHOD'];

	$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));

	/** Gets the JSON input from HTTP request and converts it to an associative array */
	$data = json_decode(file_get_contents('php://input'),true);
	
	/** $case holds the ID value that is passed as the second param in the URL. Default value
	 * for $case is -1 
	 */
	$case = $request[0];
	$caseId = -1;
	if(count($request) > 1){
		$caseId = $request[1];
	}

	/** determines the case and executes the corresponding method */
	switch ($case) {
	  case 'user':
		  	$response = executeUserCase();
		  	break;
	  case 'cakes':
		  	$response = executeCakeCase();
		  	break;
	}

	/** API Response - JSON */
	header('Content-Type: application/json');
	echo json_encode($response);

	/** 
	 * executeUserCase method instantiates User object and executes the action 
	 * @returns $response
	 */
	function executeUserCase(){
		global $method, $data, $caseId;
		$user = new User($caseId, $method, $data);
	  	$response = $user->executeAction();
		// file_put_contents("testlog.log", "\n".print_r($response, true), FILE_APPEND | LOCK_EX);
		return $response;
	}

	/** 
	 * executeCakeCase method instantiates Cake object and executes the action 
	 * @returns $response
	 */
	function executeCakeCase(){
		global $method, $data, $caseId;
		$cake = new Cake($caseId, $method, $data);
		$response = $cake->executeAction();
		// file_put_contents("testlog.log", "\n".print_r($response, true), FILE_APPEND | LOCK_EX);
		return $response;
	}
	 

?>