<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools_group_model extends CI_Model{
	public function __construct()
	{
		$this->load->database();
		//$this->load->library('session');
	}

	public function getToolGroupId($name){
		// get the tool_group_id from name
		$query = $this->db->select('id')->from('tool_group')->where('name', $name)->get();
		if($query->num_rows() == 1){
			// store the result and return the group_id
			$result = $query->result_array();
			return $result[0]['id'];
		}else{
			return 0;
		}
	}

	public function addToolGroup($data){
		/*
		 * add tool group in the table and also add the user to whom this group belongs
		 * enter in users_tools_group
		 */
		$username = $data['username'];
		unset($data['username']);
		if($this->db->insert('tool_group', $data)){
			// if group is added then add the user for this group
			$query = $this->db->select('id')->from('users')->where('username', $username)->get();

			if($query->num_rows() == 1){
				// if username exists then add the user_id and group_id in the users_tools_group_table
				$tool_group_id = $this->getToolGroupId($data['name']);
				if(!$tool_group_id){
					return false;
				}
				$result = $query->result_array();
				$user_id= $result[0]['id'];
				$user_group = array('user_id'=>$user_id, 'tool_group_id'=>$tool_group_id);
				if($this->db->insert('users_tools_group', $user_group)){
					echo "hey";
					return true;
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
	}

	public function getToolGroups(){
		$this->db->select('name')->from('tool_group');
		$query = $this->db->get();

		if($query->num_rows() > 0){
			// var_dump($query->result_array());
			return $query->result_array();
		}else{
			return 0;
		}
	}
}
