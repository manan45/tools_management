<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');

class Users extends REST_Controller{
	public function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('users_model');
	}

	public function index_post(){
		$username = $this->post('username');
		$password = $this->post('password');
		if(!$username || !$password){
			$this->response('Please enter the all the details',REST_Controller::HTTP_BAD_REQUEST);
		}else{
			$data = array('username'=> $username, 'password'=> $password);
			$result = $this->users_model->addUser($data);
			if($result){
				$this->response(array('message'=>"User is added", 'data'=>$data), REST_Controller::HTTP_CREATED);
			}else{
				$this->response('user not added', REST_Controller::HTTP_BAD_REQUEST);
			}
		}
	}

	public function login_post(){
		$username = $this->post('username');
		$password = $this->post('password');
		if(!$username || !$password ){
			$this->response('Enter the valid details',REST_Controller::HTTP_BAD_REQUEST);
		}else {
			$data = array('username' => $username, 'password' => $password);
			$login = $this->users_model->login($data);
			if ($login) {
				$this->response('user logged in', REST_Controller::HTTP_OK);
			} else {
				$this->response('user details are not correct', REST_Controller::HTTP_BAD_REQUEST);
			}
		}
	}
}
