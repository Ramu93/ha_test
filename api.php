<?php

	require_once 'biz/User.php';
	require_once 'biz/Cake.php';

	$method = $_SERVER['REQUEST_METHOD'];
	$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
	//file_put_contents("testlog.log", "\n".$request[1], FILE_APPEND | LOCK_EX);
	$data = json_decode(file_get_contents('php://input'),true);
	//file_put_contents("testlog.log", "\n".$input['first_name'], FILE_APPEND | LOCK_EX);

	$case = $request[0];
	$caseId = -1;
	if(count($request) > 1){
		$caseId = $request[1];
	}

	switch ($case) {
	  case 'user':
		  	$response = executeUserCase();
		  	break;
	  case 'cakes':
		  	$response = executeCakeCase();
		  	break;
	}

	header('Content-Type: application/json');
	echo json_encode($response);


	function executeUserCase(){
		global $method, $data, $caseId;
		$user = new User($caseId, $method, $data);
	  	$response = $user->executeAction();
		// file_put_contents("testlog.log", "\n".print_r($response, true), FILE_APPEND | LOCK_EX);
		return $response;
	}

	function executeCakeCase(){
		global $method, $data, $caseId;
		$cake = new Cake($caseId, $method, $data);
		$response = $cake->executeAction();
		// file_put_contents("testlog.log", "\n".print_r($response, true), FILE_APPEND | LOCK_EX);
		return $response;
	}
	 

?>