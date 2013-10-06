<?php

//require_once 'AWSSDKforPHP/sdk.class.php'; 
require 'vendor/autoload.php';
use Aws\DynamoDb\Enum\Type;

use Aws\DynamoDb\DynamoDbClient;
// Instantiate the client with your AWS access keys
$aws = Aws\Common\Aws::factory('../config.php');
$client = $aws->get('dynamodb');

//$dynamodb = new AmazonDynamoDB();
//$dynamodb->set_hostname("https://dynamodb.us-west-2.amazonaws.com");

//$put_response = $client->putItem(array( 
//	'TableName' => 'songs',
//	'Item' => array( 
//		'song_id' => array(Type::NUMBER => 1) ,
//		'movies' => array(Type::STRING => 'abc')
//) )); 

?>
