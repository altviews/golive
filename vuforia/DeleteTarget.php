<?php
require_once 'VWSConstants.php';
require_once 'HTTP/Request2.php';
require_once 'SignatureBuilder.php';

class DeleteTarget{

	//Server Keys
	// private $access_key 	= "bcef0961f54805c6a17a92d8fc585ebbb1af2686";
	// private $secret_key 	= "11d5690ed195607dcb435e67c13b0ed5084fccac";
	
	private $targetId 		= "";	
	private $requestPath 	= "/targets/";
	private $request;
	
	function DeleteTarget(){
		// $this->execDeleteTarget();
	}
	
	public function SetRequestPath( $str_targetId ) {
		$this->targetId = $str_targetId;
		$this->requestPath = $this->requestPath . $this->targetId;
	}

	public function execDeleteTarget(){

		$this->request = new HTTP_Request2();
		$this->request->setMethod( HTTP_Request2::METHOD_DELETE );
		
		$this->request->setConfig(array(
				'ssl_verify_peer' => false
		));

		$this->request->setURL( VWSAPI_SERVER_PATH . $this->requestPath );

		// Define the Date and Authentication headers
		$this->setHeaders();

		try {

			$response = $this->request->send();

			if (200 == $response->getStatus()) {
				return $response->getBody();
			} else {
				return 'failed';
			}
		} catch (HTTP_Request2_Exception $e) {
			return 'failed';
		}


	}

	private function setHeaders(){
		$sb = 	new SignatureBuilder();
		$date = new DateTime("now", new DateTimeZone("GMT"));

		// Define the Date field using the proper GMT format
		$this->request->setHeader('Date', $date->format("D, d M Y H:i:s") . " GMT" );
		// Generate the Auth field value by concatenating the public server access key w/ the private query signature for this request
		$this->request->setHeader("Authorization" , "VWS " . VWSAPI_SERVER_ACCESSKEY . ":" . $sb->tmsSignature( $this->request , VWSAPI_SERVER_SECRETKEY ));

	}
}
?>
