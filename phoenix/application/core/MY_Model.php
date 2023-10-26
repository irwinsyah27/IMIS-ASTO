<?php
class MY_Model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function array_from_post($fields){
		$data = array();
		foreach ($fields as $field) {
			$data[$field] = $this->input->post($field);
		}
		return $data;
	}
	
	function db_insert($table_name, $post_array)
    {
    	$insert = $this->db->insert($table_name,$post_array);
    	if($insert)
    	{
    		return $this->db->insert_id();
    	}
    	return false;
    }

	function db_update($table_name, $post_array, $primary_key_array)
    { 
    	return $this->db->update($table_name, $post_array, $primary_key_array);
    }

    function db_delete($table_name, $primary_key_array)
    { 
    	#$this->db->limit(1);
    	return $this->db->delete($table_name, $primary_key_array);
    	#if( $this->db->affected_rows() != 1)
    	#	return false;
    	#else
    	#	return true;
    }
    public function get($table_name)
    { 
    	return $this->db->get($table_name);
    }
    public function get_where($table_name, $where)
    { 
    	return $this->db->get_where($table_name,$where);
    }

    public function get_result_row($table_name, $primary_key_array)
    { 
    	return $this->db->get_where($table_name, $primary_key_array);
    }

    public function get_row($table_name, $key, $val)
    { 
    	$sql = "SELECT * FROM ".$table_name ." WHERE ".$key." = '". $val."'";
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs;
    }

    public function get_record($table_name, $key, $val)
    { 
    	$sql = "SELECT * FROM ".$table_name ." WHERE ".$key." = ". $val;
    	# echo $sql;exit;
		$res = $this->db->query($sql);
		$rs = $res->result_array(); 
		return $rs;
    }

    public function get_all_data($table_name, $key, $val)
    { 
    	$sql = "SELECT * FROM ".$table_name ." WHERE ".$key." = ". $val; 
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
    }

    public function getAllDataLike($table_name, $key, $val)
    { 
    	$sql = "SELECT * FROM ".$table_name ." WHERE ".$key." like '". $val."%'"; 
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		return $rs;
    }

    public function return_show_selectbox($table_name, $key, $val, $var_name, $option, $label)
    { 
    	$data = "";
    	$sql = "SELECT * FROM ".$table_name ." WHERE ".$key." = ". $val; 
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		if (count($rs) > 0)  {
			$data .= "<select name=\"".$var_name."\" id=\"".$var_name."\" class=\"form-control select2_sample1\">";
			FOREACH ($rs AS $r) {
				$data .= "<option value=\"".$r[$option]."\">".$r[$label]."</option>";
			}
			$data .= "</select>";
		}
		return $data;
    }
    public function return_show_selectbox_list_kab($table_name, $key, $val, $var_name, $option, $label)
    { 
    	$data = "";
    	$sql = "SELECT *, case when substring(kodeBPS, 3, 1) = '7' then concat('KOTA ', namaKabupaten) else namaKabupaten end as namaKabupaten FROM ".$table_name ." WHERE ".$key." = ". $val." ORDER BY namaKabupaten"; 
		$res = $this->db->query($sql);
		$rs = $res->result_array();
		if (count($rs) > 0)  {
			$data .= "<select name=\"".$var_name."\" id=\"".$var_name."\" class=\"form-control select2_sample1\">";
			FOREACH ($rs AS $r) {
				$data .= "<option value=\"".$r[$option]."\">".$r[$label]."</option>";
			}
			$data .= "</select>";
		}
		return $data;
    }
    public function is_record_in_other_table($table_name, $key, $val)
    { 
    	$sql = "SELECT count(*) as total FROM ".$table_name ." WHERE ".$key." = '". $val."'";
		$res = $this->db->query($sql);
		$rs = $res->row_array();
		return $rs["total"];
    }

	public function get_last_id($table_name, $pk) { 
		$sql = "SELECT $pk FROM $table_name  ORDER BY $pk DESC LIMIT 1";
		$res = $this->db->query($sql);
		$r   = $res->row_array(); 
		return $r[$pk];
	}
    
	public function export_to_excel($colnames, $colfields, $data, $header="",$file="export", $footer ="")
	{ 
		$string_to_export = "";
		if ($header <> "") $string_to_export .= $header."\n\n";
		
		foreach ($colnames AS $k=>$v) {
			$string_to_export .= $v. "\t";
		} 
		$string_to_export .= "\n";

		foreach ($data AS $key => $value)
		{  
			foreach ($colfields AS $k=>$v) {
				$string_to_export .= $this->_trim_export_string($value[$v])."\t"; 
			}
			$string_to_export .= "\n";
		} 
		if ($footer <> "") $string_to_export .= "\n\n".$footer."\n\n";

		// Convert to UTF-16LE and Prepend BOM
		$string_to_export = "\xFF\xFE" .mb_convert_encoding($string_to_export, 'UTF-16LE', 'UTF-8');

		$filename = $file."_".date("Y-m-d_H:i:s").".xls";

		header('Content-type: application/vnd.ms-excel;charset=UTF-16LE');
		header('Content-Disposition: attachment; filename='.$filename);
		header("Cache-Control: no-cache");
		echo $string_to_export;
		die();
	}
	
	public function print_webpage($colnames, $colfields, $data)
	{
		$string_to_print = "<meta charset=\"utf-8\" /><style type=\"text/css\" >
		#print-table{ color: #000; background: #fff; font-family: Verdana,Tahoma,Helvetica,sans-serif; font-size: 13px;}
		#print-table table tr td, #print-table table tr th{ border: 1px solid black; border-bottom: none; border-right: none; padding: 4px 8px 4px 4px}
		#print-table table{ border-bottom: 1px solid black; border-right: 1px solid black}
		#print-table table tr th{text-align: left;background: #ddd}
		#print-table table tr:nth-child(odd){background: #eee}
		</style>";
		$string_to_print .= "<div id='print-table'>";

		$string_to_print .= '<table width="100%" cellpadding="0" cellspacing="0" ><tr>';
		foreach ($colnames AS $k=>$v) {
			$string_to_print .= "<th>".$v. "</th>";
		} 
		$string_to_print .= "</tr>";

		foreach ($data AS $key => $value)
		{  
			$string_to_print .= "<tr>";
			foreach ($colfields AS $k=>$v) {
				$string_to_export .= "<td>".$this->_trim_print_string($value[$v])."</td>";
			}
			$string_to_print .= "</tr>";
		} 

		$string_to_print .= "</table></div>";

		echo $string_to_print;
		die();
	}
	
	public function _trim_export_string($value)
	{
		$value = str_replace(array("&nbsp;","&amp;","&gt;","&lt;"),array(" ","&",">","<"),$value);
		return  strip_tags(str_replace(array("\t","\n","\r"),"",$value));
	}

	public function _trim_print_string($value)
	{
		$value = str_replace(array("&nbsp;","&amp;","&gt;","&lt;"),array(" ","&",">","<"),$value);

		//If the value has only spaces and nothing more then add the whitespace html character
		if(str_replace(" ","",$value) == "")
			$value = "&nbsp;";

		return strip_tags($value);
	}

	function escape_str($value)
    {
    	return $this->db->escape_str($value);
    } 

	public function user_akses($var="") { 
		if ($var <> "" && $_SESSION["id"] <> "") { 
			$sql = "SELECT a.* 
					FROM user_akses a 
					INNER JOIN user_menu b 
					ON a.user_menu_id = b.user_menu_id 
					WHERE a.user_id = ".$_SESSION["id"]." AND module_name = '".$var."'";
			$res = $this->db->query($sql);
			$rs   = $res->row_array(); 
			return $rs;
		}
	}

	public function check_id_and_insert($table, $field, $val) {
		$sql = "SELECT * from $table WHERE LOWER($field) = '".strtolower(trim($val))."'";
		$res  = $this->db->query($sql);
		$rs = $res->row_array();
		if ($rs[$pk] =="") {
			$sqli = "INSERT INTO $table ($field) VALUES ('".trim($val)."')";
			$resi = $this->db->query($sqli);
			$lastid = $this->db->insert_id();
			return $lastid;
		} else {
			return $rs[$pk];
		}

	}

}