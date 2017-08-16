<?php

	require_once 'Honey.php';
	require_once __DIR__ . '/../dao/CakeDao.php';

	class Cake extends Honey{

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
					$result = $this->getCakesList();
					break;
				default:
					break;
			}
			return $result;
		}

		private function getCakesList(){
			$this->log->info(H_LINE);
			$this->log->info(__FUNCTION__ . SPACE . METHOD_STARTS);
			$userDao = new CakeDao();
			$response = $userDao->getCakesList();
			$this->log->info(__FUNCTION__ . SPACE . METHOD_ENDS);
			return $response;
		}

	}

?>