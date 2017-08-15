<?php

	require_once 'Honey.php';
	require_once __DIR__ . '/../dao/UserDao.php';

	class User extends Honey{

		private $caseId;
		private $method;
		private $inputDataMap;

		function __construct($caseId, $method, $inputDataMap){
			$this->caseId = $caseId;
			$this->method = $method;
			$this->inputDataMap = $inputDataMap;
		}

		//overridded function
		public function executeAction(){
			$result = array();
			switch($this->method){
				case 'GET':
					$result = $this->getUser();
					break;
				case 'POST':
					$result = $this->createUser();
					break;
				case 'PUT':
					$result = $this->updateUser();
					break;
				default:
					break;
			}
			return $result;
		}

		private function getUser(){
			$userDao = new UserDao();
			return $userDao->getUser($this->caseId);
		}

		private function createUser(){
			$userDao = new UserDao();
			return $userDao->createUser($this->inputDataMap);
		}

		private function updateUser(){
			$userDao = new UserDao();
			return $userDao->updateUser($this->inputDataMap, $this->caseId);
		}

	}

?>