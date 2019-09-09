<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model{
	public function __construct()
	{
		$this->load->database();
		$this->load->library('session');
	}

	public function checkUserExists($username){
		// check whether username exists in table or not
		$this->db->select('*')->from('users')->where('username', $username);
		$query = $this->db->get();
		if($query->num_rows() > 1){
			return false;
		}else{
			return true;
		}
	}
	public function addUser($data){
		$hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
		if(!$this->checkUserExists($data['username'])){
			return false;
		}
		$array = array('username'=> $data['username'],'password'=> $hashed_password);
		$result = $this->db->insert('users', $array);
		if($result){
			return true;
		}else{
			return false;
		}
	}

	public function login($data){
		$username = $data['username'];
		$password = $data['password'];
		$this->db->select('username, password, id')->from('users')->where('username', $username);
		$query = $this->db->get();
		if($query->num_rows() == 1){
			$result = $query->result_array();
			// var_dump($result[0]['password']);
			if(password_verify($password, $result[0]['password'])){
				$this->session->set_userdata('user_id', $result[0]['id']);
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}
