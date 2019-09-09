<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');


class Tools_group extends REST_Controller{
	public function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('tools_group_model');
	}

	public function index_get(){
		$result = $this->tools_group_model->getToolGroups();
		if($result){
			$this->response($result, REST_Controller::HTTP_OK);
		}else{
			$this->response('no tools group found in the table',REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function index_post(){
		/*
		 *  take the username and name of the tool group and add the tool group to that user
		 */
		$name = $this->post('name');
		$username = $this->post('username');
		if(!$name || !$username){
			// if inputs are not present
			$this->response('Enter the details', REST_Controller::HTTP_BAD_REQUEST);
		}else{
			// send the input data to model
			$data = array('name'=> $name, 'username'=> $username);
			$result = $this->tools_group_model->addToolGroup($data);
			if($result){
				$this->response(array('message'=> 'group is added', 'data'=> $data), REST_Controller::HTTP_CREATED);
			}else{
				$this->response('Group not added', REST_Controller::HTTP_BAD_REQUEST);
			}
		}
	}

}
