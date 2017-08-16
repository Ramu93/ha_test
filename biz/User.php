<?php

	require_once 'Honey.php';
	require_once __DIR__ . '/../dao/UserDao.php';

	class User extends Honey{

		private $caseId;
		private $method;
		private $inputDataMap;

		function __construct($caseId, $method, $inputDataMap){
			parent::__construct();
			$this->log = Logger::getLogger(__CLASS__);
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
			$this->log->info(H_LINE);
			$this->log->info(__FUNCTION__ . SPACE . METHOD_STARTS);
			$userDao = new UserDao();
			$response = $userDao->getUser($this->caseId);
			$this->log->info(__FUNCTION__ . SPACE . METHOD_ENDS);
			return $response;
		}

		private function createUser(){
			$this->log->info(H_LINE);
			$this->log->info(__FUNCTION__ . SPACE . METHOD_STARTS);
			$userDao = new UserDao();
			$response = $userDao->createUser($this->inputDataMap);
			$this->log->info(__FUNCTION__ . SPACE . METHOD_ENDS);
			return $response;
		}

		private function updateUser(){
			$this->log->info(H_LINE);
			$this->log->info(__FUNCTION__ . SPACE . METHOD_STARTS);
			$userDao = new UserDao();
			$response = $userDao->updateUser($this->inputDataMap, $this->caseId);
			$this->log->info(__FUNCTION__ . SPACE . METHOD_ENDS);
			return $response;
		}

	}

?>