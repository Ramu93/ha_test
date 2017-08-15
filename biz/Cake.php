<?php

	require_once 'Honey.php';
	require_once __DIR__ . '/../dao/CakeDao.php';

	class Cake extends Honey{

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
					$result = $this->getCakesList();
					break;
				default:
					break;
			}
			return $result;
		}

		private function getCakesList(){
			$userDao = new CakeDao();
			return $userDao->getCakesList();
		}

	}

?>