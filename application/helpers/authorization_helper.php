<?php

class AUTHORIZATION
{
    public static function validateTimestamp($token)
    {
        $CI =& get_instance();
        $token = self::validateToken($token);
        if ($token != false && (now() - $token->timestamp < ($CI->config->item('token_timeout') * 60))) {
            return $token;
        }
        return false;
    }

    public static function validateToken($token)
    {
        $CI =& get_instance();
        return JWT::decode($token, $CI->config->item('jwt_key'));
    }

    public static function generateToken($data)
    {
        $CI =& get_instance();
        return JWT::encode($data, $CI->config->item('jwt_key'));
    }

    public static function verify_request(){
        // Get all the headers
        $CI =& get_instance();
		$headers = $CI->input->request_headers();

		// Extract the token
		$token = $headers['Authorization'];

		// Use try-catch
		// JWT library throws exception if the token is not valid
		try {
			// Validate the token
			// Successfull validation will return the decoded user data else returns false
			$data = AUTHORIZATION::validateToken($token);
			if ($data === false) {
				$status = 401;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
				$CI->response($response, $status);

				exit();
			} else {
				return $data;
			}
		} catch (Exception $e) {
			// Token is invalid
			// Send the unathorized access message
			$status = 401;
			$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
			$CI->response($response, $status);
		}
	}

}