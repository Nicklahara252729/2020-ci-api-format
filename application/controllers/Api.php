<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/RestController.php');
use chriskacerguis\RestServer\RestController;

/*
// Informational
HTTP_CONTINUE = 100;
HTTP_SWITCHING_PROTOCOLS = 101;
HTTP_PROCESSING = 102;            

// Success
* The request has succeeded
HTTP_OK = 200;
	 
* The server successfully created a new resource
HTTP_CREATED = 201;
HTTP_ACCEPTED = 202;
HTTP_NON_AUTHORITATIVE_INFORMATION = 203;

* The server successfully processed the request, though no content is returned
HTTP_NO_CONTENT = 204;
HTTP_RESET_CONTENT = 205;
HTTP_PARTIAL_CONTENT = 206;
HTTP_MULTI_STATUS = 207;          
HTTP_ALREADY_REPORTED = 208;      
HTTP_IM_USED = 226;               

// Redirection
HTTP_MULTIPLE_CHOICES = 300;
HTTP_MOVED_PERMANENTLY = 301;
HTTP_FOUND = 302;
HTTP_SEE_OTHER = 303;

* The resource has not been modified since the last request
HTTP_NOT_MODIFIED = 304;
HTTP_USE_PROXY = 305;
HTTP_RESERVED = 306;
HTTP_TEMPORARY_REDIRECT = 307;
HTTP_PERMANENTLY_REDIRECT = 308;  

// Client Error
* The request cannot be fulfilled due to multiple errors
HTTP_BAD_REQUEST = 400;

* The user is unauthorized to access the requested resource
HTTP_UNAUTHORIZED = 401;
HTTP_PAYMENT_REQUIRED = 402;

* The requested resource is unavailable at this present time
HTTP_FORBIDDEN = 403;

* The requested resource could not be found
HTTP_NOT_FOUND = 404;

* The request method is not supported by the following resource
HTTP_METHOD_NOT_ALLOWED = 405;

* The request was not acceptable
HTTP_NOT_ACCEPTABLE = 406;
HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
HTTP_REQUEST_TIMEOUT = 408;

* The request could not be completed due to a conflict with the current state
* of the resource
HTTP_CONFLICT = 409;
HTTP_GONE = 410;
HTTP_LENGTH_REQUIRED = 411;
HTTP_PRECONDITION_FAILED = 412;
HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
HTTP_REQUEST_URI_TOO_LONG = 414;
HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
HTTP_EXPECTATION_FAILED = 417;
HTTP_I_AM_A_TEAPOT = 418;                                               
HTTP_UNPROCESSABLE_ENTITY = 422;                                        
HTTP_LOCKED = 423;                                                      
HTTP_FAILED_DEPENDENCY = 424;                                           
HTTP_RESERVED_FOR_WEBDAV_ADVANCED_COLLECTIONS_EXPIRED_PROPOSAL = 425;   
HTTP_UPGRADE_REQUIRED = 426;                                            
HTTP_PRECONDITION_REQUIRED = 428;                                       
HTTP_TOO_MANY_REQUESTS = 429;                                           
HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;                             

// Server Error
* The server encountered an unexpected error
* Note: This is a generic error message when no specific message
* is suitable
HTTP_INTERNAL_SERVER_ERROR = 500;

* The server does not recognise the request method
HTTP_NOT_IMPLEMENTED = 501;
HTTP_BAD_GATEWAY = 502;
HTTP_SERVICE_UNAVAILABLE = 503;
HTTP_GATEWAY_TIMEOUT = 504;
HTTP_VERSION_NOT_SUPPORTED = 505;
HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;                        
HTTP_INSUFFICIENT_STORAGE = 507;                                        
HTTP_LOOP_DETECTED = 508;                                               
HTTP_NOT_EXTENDED = 510;                                                
*/

class Api extends RestController {

	public function __construct(){
		parent::__construct();
	}

	

	public function generateTokenJwt_get(){
		$token_payload = ['time' => time()];  
		$token 		   = AUTHORIZATION::generateToken($token_payload);
		$this->response([
			'status' => $token,
		], RestController::HTTP_OK);
	}

	// get semua data
	public function index_get(){
		$verify = AUTHORIZATION::verify_request(); 
		$data   = $this->m_user->viewUser()->result();
		if($data):
			$response['data']    = $data;
			$response['status']  = TRUE;
			$status   			 = RestController::HTTP_OK;
		else:
			$response['message'] = "Data tidak ditemukan";
			$response['status']  = TRUE;
			$status	  			 = RestController::HTTP_NO_CONTENT;
		endif;
		$this->response($response,$status);
	}

	// nambah data
	function index_post() {
		$saveData = $this->m_user->saveUser();
		if($saveData['status'] == TRUE):
			$response['data']    = $data;
			$response['status']  = TRUE;
			$status   			 = RestController::HTTP_CREATED;
		else:
			$response['message'] = "Data tidak ditemukan";
			$response['status']  = TRUE;
			$status	  			 = RestController::HTTP_BAD_GATEWAY;
		endif;
		$this->response($response,$status);
	}
	
	// update data
    function index_put() {
		$data = [
			'id'		=> trim($this->put('id','true')),
			'nama'		=> trim($this->put('nama','true')),
			'email'		=> trim($this->put('email','true')),
			'username'	=> trim($this->put('username','true')),
			'password'	=> md5(trim($this->put('password','true'))),
			'level'		=> trim($this->put('level','true')),
		];
		$updateData = $this->m_user->updateUser($data);
        if($updateData['status'] == TRUE):
            $response['data']    = $data;
			$response['status']  = TRUE;
			$status   			 = RestController::HTTP_CREATED;
		else:
			$response['message'] = "Data tidak ditemukan";
			$response['status']  = TRUE;
			$status	  			 = RestController::HTTP_BAD_GATEWAY;
		endif;
		$this->response($response,$status);
	}
	
	//Menghapus data
    function index_delete() {
        $id = trim($this->delete('id'));
        $deleteData = $this->m_user->deleteUser($id);
        if($deleteData == TRUE):
            $response['data']    = $data;
			$response['status']  = TRUE;
			$status   			 = RestController::HTTP_CREATED;
		else:
			$response['message'] = "Data tidak ditemukan";
			$response['status']  = TRUE;
			$status	  			 = RestController::HTTP_BAD_GATEWAY;
		endif;
		$this->response($response,$status);
	}
	
	// get by id semua data
	public function indexById_get(){
		$id = trim($this->get('id'));
		$data = $this->m_user->getUserById($id)->row();
		if($data):
			$response['data']    = $data;
			$response['status']  = TRUE;
			$status   			 = RestController::HTTP_OK;
		else:
			$response['message'] = "Data tidak ditemukan";
			$response['status']  = TRUE;
			$status	  			 = RestController::HTTP_NO_CONTENT;
		endif;
		$this->response($response,$status);
	}
}
