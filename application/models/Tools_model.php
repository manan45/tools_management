<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools_model extends CI_Model {
	public function __construct()
	{
		$this->load->database();
	}

	public function getGroupIdByName($group_name){
		/*
		 * get the group id of the group by group name
		 */
		$this->db->select('id')->from('tool_group')->where('name', $group_name);
		$query = $this->db->get();
		if($query->num_rows() == 1 ){
			// get the result and return the group id
			$result = $query->result_array();
			if($result){
				return $result[0]['id'];
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}

	public function checkValueExistsOrNot($name, $table_name){
		/*
		 * check if a certain value already exists in database or not
		 */
		$this->db->select('*')->from($table_name)->where('name', $name);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	public function addToolInGroup($data){
		/*
		 * add the tool to group
		 * the group id is taken from getGroupIdByName function of the class
		 * for each tool group is added in tools_details table
		 */
		$tool_group_name = $data['group_name'];
		$name = $data['name'];
		$cost = $data['cost'];
		if($this->checkValueExistsOrNot($name, 'tools')){
			return false;
		}
		$group_id = $this->getGroupIdByName($tool_group_name);
		// remove group name from data array
		unset($data['group_name']);
		if($this->db->insert('tools', $data)){
			// get the id of tool inserted
			$this->db->select('id')->from('tools')->where($data);
			$query = $this->db->get();
			// var_dump($query->result_array());
			if($query->num_rows() == 1){
				$result = $query->result_array();
				$tool_id = $result[0]['id'];
				$tool_detail = array('tool_id'=>$tool_id, 'tool_group_id'=>$group_id);
				// var_dump($tool_detail);
				if($this->db->insert('tools_details', $tool_detail)){
					// if tool is added to group return true
					return true;
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}
		else{
			return false;
		}
	}

	public function getTools(){
		/*
		 * returns the all tools present in database along with the group names
		 */
		$query = $this->db->query("select tools.name, tools.cost, tool_group.name as group_name from tools, tool_group, tools_details where tools_details.tool_id = tools.id and tool_group.id = tools_details.tool_group_id");
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return 0;
		}
	}

	public function getToolIdByName($name){
		$this->db->select('id')->from('tools')->where('name', $name);
		$query = $this->db->get();
		if($query->num_rows() >= 1){
			echo'hey';
			$results = $query->result_array();
			return $results[0]['id'];
		}else{
			return false;
		}
	}

	public function getPurchaseDate($tool_id){
		$query = $this->db->select('purchase_date')->from('purchase')->where('tool_id', $tool_id)->get();
		if($query->num_rows() > 0){
			return $query->result_array();
		}else{
			return false;
		}
	}

	public function purchaseTool($name){
		/*
		 * purchase the tool and return the purchase history along with tool name
		 */
		$result = $this->getToolIdByName($name);
		if(!$result){
			return 0;
		}else{
			$data = array('tool_id'=>$result);
			$query = $this->db->insert('purchase', $data);
			if($query){
				$date = $this->getPurchaseDate($result);
				$purchase_history = array();
				$i = 1;
				foreach($date as $row){
					$purchase_history[] = array($i => $row['purchase_date']);
					$i += 1;
				}
				// var_dump($purchase_history);
				//if query is done get the tools details along with purchase date and send as a response
				return array('message'=>"Tool Purchased", 'tool_name'=>$name, 'purchase_history'=>$purchase_history);
			}else{
				return 0;
			}
		}

	}
}
