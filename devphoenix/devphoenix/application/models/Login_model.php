<?php
class Login_model extends CI_MODEL { 
	function __construct(){
		parent::__construct();
	}
	  
	public function auth($username , $passwd ){ 
		$username = strtolower(trim($username));
		$passwd = strtolower(trim($passwd));
 		$sql = "SELECT * FROM user WHERE username = '".$this->db->escape_str($username)."'  AND passwd ='".md5($this->db->escape_str($passwd))."' LIMIT 1";
		# echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 
	public function akses_level($id ){ 
 		$sql = "SELECT 
 					a.user_id, 
 					a.view,
 					a.add,
 					a.edit,
 					a.del,
 					b.module_name
 				FROM 
 					user_akses a 
 				INNER JOIN 
 					user_menu b 
 				ON 
 					a.user_menu_id = b.user_menu_id
 				WHERE
 				 a.user_id = '".$id."' LIMIT 1";
		# echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
	} 
	public function akses_level_user($id, $module_name ){ 
 		$sql = "SELECT 
 					a.user_id, 
 					a.view,
 					a.add,
 					a.edit,
 					a.del,
 					b.module_name
 				FROM 
 					user_akses a 
 				INNER JOIN 
 					user_menu b 
 				ON 
 					a.user_menu_id = b.user_menu_id
 				WHERE
 				 a.user_id = '".$id."' 
 				 AND b.module_name = '".$module_name."' LIMIT 1";
		# echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
	} 

	
}