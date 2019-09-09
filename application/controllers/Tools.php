<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');


class Tools extends REST_Controller {
	public function __construct($config = 'rest')
	{
		parent::__construct($config);
		$this->load->model('tools_model');
	}

	public function index_post(){
		/**
		 * add a new group tools table
		 * the request will take 3 parameters ['name', 'cost', 'group_name'] from user
		 * the tool will be then added to that particular group
		 **/
		$name = $this->post('name');
		$cost = $this->post('cost');
		$group_name = $this->post('group_name');
		if (!$name || !$cost || !$group_name){
			// if name, cost and group name not provided raise bad request
			$this->response(array('message'=>"please enter all the details"), REST_Controller::HTTP_BAD_REQUEST);
		}else{
			// store values in array and pass in the addToolInGroup method of tools_model
			$data = array("name"=>$name, "cost"=>$cost, 'group_name' => $group_name);
			$result = $this->tools_model->addToolInGroup($data);
			if($result){
				// if result is obtained give the response to user
				$this->response($data,REST_Controller::HTTP_ACCEPTED);
			}
			else{
				// raise the bad request if tool is not added
				$this->response(array("message"=>"tool not added in database"), REST_Controller::HTTP_BAD_REQUEST);
			}
		}
	}

	public function index_get(){
		/*
		 * get all tools details along with tool_group_name
		 */
		$result = $this->tools_model->getTools();
		if($result){
			$this->response($result, REST_Controller::HTTP_OK);
		}else{
			$this->response('no tools found in the table',REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	public function purchase_post(){
		/*
		 * add the purchase details in purchase table
		 * the request will require the tool name parameter
		 * then will add the tool in the database
		 */
		$name = $this->post('name');
		if(!$name){
			$this->response('enter the name of tool for purchase', REST_Controller::HTTP_BAD_REQUEST);
		}else{
			$result = $this->tools_model->purchaseTool($name);
			if($result){
				$this->response($result, REST_Controller::HTTP_CREATED);
			}else{
				$this->response('Purchase cannot be done', self::HTTP_BAD_REQUEST);
			}
		}
	}
}
