<?php
	
	/**
	 * CommonUtils class common utility methods.
	 * 
	 * @author Ramu Ramasamy
	 * @version 1.0
	 */
	class CommonUtils {

		/**
		 * generateSafeString returns a safe string that is md5 encryped first and then the
		 * string is base64 encoded.
		 * 
		 * @param rawString
		 * @return safeString
		 */
		public function generateSafeString($rawString){
			$safeString = base64_encode(md5($rawString));
			return $safeString;
		}

		/**
		 * generateRandomNumber method generates a 6 digit random number.
		 * 
		 * @return randomNumber
		 */
		public function generateSixDigitRandomNumber(){
			$randomNumber = mt_rand(100000, 999999);
			return $randomNumber;
		}
	}

?>