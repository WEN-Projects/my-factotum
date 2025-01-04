<?php
function add_to_subscription_sendinblue( $email ) {
	require_once( get_template_directory() . '/vendor/autoload.php' );
	$api_key = get_field("send_in_blue_api_key","option");
// Configure API key authorization: api-key
	$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey( 'api-key', $api_key );

	$apiInstance   = new SendinBlue\Client\Api\ContactsApi(
		new GuzzleHttp\Client(),
		$config
	);
	$createContact = new \SendinBlue\Client\Model\CreateContact(); // \SendinBlue\Client\Model\CreateContact | Values to create a contact

	$createContact['email'] = $email;

	try {
		$result = $apiInstance->createContact( $createContact );

		return $result;
	} catch ( Exception $e ) {
//		echo 'Exception when calling ContactsApi->createContact: ', $e->getMessage(), PHP_EOL;
		return false;
	}


}

function remove_from_subscription_sendinblue( $email ) {
	require_once( get_template_directory() . '/vendor/autoload.php' );
	$api_key = get_field("send_in_blue_api_key","option");
// Configure API key authorization: api-key
	$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey( 'api-key', $api_key );

	$apiInstance = new SendinBlue\Client\Api\ContactsApi(
	// If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
	// This is optional, `GuzzleHttp\Client` will be used as default.
		new GuzzleHttp\Client(),
		$config
	);
	$identifier  = $email; // string | Email (urlencoded) OR ID of the contact

	try {
		$apiInstance->deleteContact( $identifier );
		return true;
	} catch ( Exception $e ) {
//		echo 'Exception when calling ContactsApi->deleteContact: ', $e->getMessage(), PHP_EOL;
		return false;
	}

}








