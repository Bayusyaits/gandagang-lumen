<?php
use Illuminate\Support\Facades\Crypt;

//aliases
function aliasResponseUserData($data = [], $for = '', $object = 'first')
{
	$response = [];
	if (isset($data) && !empty($data) && empty($for) && $object == 'get'){
		foreach ($data as $key => $value) {
			# code...
			$response[$key]['userToken']             = Crypt::encrypt($data[$key]['userToken']);
			$response[$key]['email']                 = $data[$key]['userEmail'];
			$response[$key]['email']                 = $data[$key]['userEmail'];
			$response[$key]['salutation']            = $data[$key]['salutation'];
			$response[$key]['firstName']             = $data[$key]['userFirstName'];
			$response[$key]['lastName']              = $data[$key]['userLastName'];
			$response[$key]['username']              = $data[$key]['userName'];
			$response[$key]['mobilePrefix']          = $data[$key]['userMobilePrefix'];
			$response[$key]['phoneNumber']           = $data[$key]['userPhoneNumber'];
			$response[$key]['status']                = $data[$key]['userStatus'];
			$response[$key]['type']                  = $data[$key]['userType'];
			$response[$key]['role']                  = $data[$key]['userRole'];
			$response[$key]['verifyEmailDate']       = $data[$key]['userVerifyEmailDate'];
			$response[$key]['verifyPhoneNumberDate'] = $data[$key]['userVerifyPhoneNumberDate'];
			$response[$key]['verifyDataDate']        = $data[$key]['userVerifyDataDate'];
			$response[$key]['verifyData']            = $data[$key]['userVerifyData'];
			$response[$key]['agreePrivacy']          = $data[$key]['userAgreePrivacy'];
			$response[$key]['agreeSubscribe']        = $data[$key]['userAgreeSubscribe'];
		}
	} else if (isset($data) && !empty($data) && empty($for) && $object == 'first')
	{
		$response['userToken']             = Crypt::encrypt($data['userToken']);
		$response['email']                 = $data['userEmail'];
		$response['salutation']            = $data['salutation'];
		$response['firstName']             = $data['userFirstName'];
		$response['lastName']              = $data['userLastName'];
		$response['username']              = $data['userName'];
		$response['mobilePrefix']          = $data['userMobilePrefix'];
		$response['phoneNumber']           = $data['userPhoneNumber'];
		$response['status']                = $data['userStatus'];
		$response['type']                  = $data['userType'];
		$response['role']                  = $data['userRole'];
		$response['verifyEmailDate']       = $data['userVerifyEmailDate'];
		$response['verifyPhoneNumberDate'] = $data['userVerifyPhoneNumberDate'];
		$response['verifyDataDate']        = $data['userVerifyDataDate'];
		$response['verifyData']            = $data['userVerifyData'];
		$response['agreePrivacy']          = $data['userAgreePrivacy'];
		$response['agreeSubscribe']        = $data['userAgreeSubscribe'];

	}
	return $response;
}
