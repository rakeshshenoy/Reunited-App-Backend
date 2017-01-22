<?php
	require_once 'vendor/autoload.php';
	require_once 'HTTP/Request2.php';
	require_once 'dbConnect.php'

	use WindowsAzure\Common\ServicesBuilder;
	use MicrosoftAzure\Storage\Common\ServiceException;

	function getFaceID($x)
	{
		$request->setBody($x);
		try
		{
		    $response = $request->send();
		}
		catch (HttpException $ex)
		{
		    echo $ex;
		}
		$y = $response->getBody();
		return json_decode($y)[0]->{"faceId"};
	}

	if($_SERVER['REQUEST_METHOD']=='POST'){
		$image = $_POST['image'];

		$connectionString = "DefaultEndpointsProtocol='https';AccountName='rakeshphotos';AccountKey='f2HkSZYQm65pkJ9L4ungJqD7FIMU+5oGIdpxug1eL4YQxz7IuS4ZxIA4Q56BfpNBHL4Zo5cBY0kHFQs7yjUtwg=='";

		// Create blob REST proxy.
		$blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);
		try    {
			$request = new Http_Request2('https://westus.api.cognitive.microsoft.com/face/v1.0/detect');
			$url = $request->getUrl();
			$headers = array(
			    // Request headers
			    'Content-Type' => 'application/octet-stream',
			    'Ocp-Apim-Subscription-Key' => 'dd51642516ac431a9c593b4c78b8a806',
			);

			$request->setHeader($headers);

			$parameters = array(
			    // Request parameters
			    'returnFaceId' => 'true'
			);

			$url->setQueryVariables($parameters);
			$request->setMethod(HTTP_Request2::METHOD_POST);

			//$content = base64_decode($image);
			//$mainFaceID = getFaceID($content);
			//var_dump($mainFaceID);

			//while
			// Get blob.
			$blob = $blobRestProxy->getBlob("photos", 10);
			$x = $blob->getContentStream();
			$faceID = getFaceID($x);
			var_dump($faceID);

		}
		catch(ServiceException $e){
			$code = $e->getCode();
			$error_message = $e->getMessage();
			echo $code.": ".$error_message."<br />";
	}
?>