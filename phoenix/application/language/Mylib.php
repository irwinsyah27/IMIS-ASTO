<?php  
class Mylib { 
    function __construct()
    {
        $this->ci =& get_instance();    // get a reference to CodeIgniter.
    }
     
    /****** FORM LIB ******/
 
    function textbox($var_name="",$value="", $readonly="", $placeholder="", $inlinehelp="", $class = "")
    { 
        $output = '';
        $output .= "<input name=\"".$var_name."\" id=\"".$var_name."\" type=\"text\" class=\"form-control input-inline input-medium  $class \" placeholder=\"".$placeholder."\" value=\"".$value."\" ".$readonly.">
                    <span class=\"help-inline\">".$inlinehelp."</span>"; 
        echo $output;
    } 
    function textbox_special($var_name="",$value="", $readonly="", $placeholder="", $inlinehelp="")
    { 
        $output = '';
        $output .= "<input size=\"4\" name=\"".$var_name."\" id=\"".$var_name."\" type=\"text\" class=\"form-control \" placeholder=\"".$placeholder."\" value=\"".$value."\" \"".$readonly."\">
                    "; 
        echo $output;
    } 
    function inputdate($var_name="",$value="", $readonly="", $placeholder="", $inlinehelp="")
    { 
        $output = '';
        $output .= "<input name=\"".$var_name."\" id=\"".$var_name."\" type=\"text\" class=\"form-control input-inline input-medium date-picker\" placeholder=\"".$placeholder."\" value=\"".$value."\" \"".$readonly."\">
                    <span class=\"help-inline\">".$inlinehelp."</span>"; 
        echo $output;
    } 
 

 

    function inputfile($var_name="",$value="", $readonly="false", $placeholder="", $inlinehelp="")
    { 
        $output = '';
        $output .= '<div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="input-group input-large"> 
                                <input type="file" name="'.$var_name.'" id="'.$var_name.'"> </span> 
                        </div>
                    </div>'; 
        echo $output;
    } 
    function inputfile_image($var_name="",$value="", $readonly="false", $placeholder="", $inlinehelp="")
    { 
        $output = '';
        $output .= '<div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt="" /> </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                        <div>
                            <span class="btn default btn-file">
                                <span class="fileinput-new"> Select image </span>
                                <span class="fileinput-exists"> Change </span>
                                <input type="file" name="'.$var_name.'"> </span>
                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                        </div>
                    </div>'; 
        echo $output;
    } 
    function textarea($var_name="",$value="", $rows="3")
    { 
        $output = '';
        $output .= "<textarea name=\"".$var_name."\" id=\"".$var_name."\" class=\"form-control\" rows=\"".$rows."\">".$value."</textarea>"; 
        echo $output;
    } 
    function radiobox($var_name="",$value="", $array_data="")
    { 
        $output = '<div class="mt-radio-list">';

        FOREACH($array_data AS $key=>$val) {  
            $checked = "";
            if ($key == $value) $checked = " checked";
            $output .= '<label class="mt-radio">
                            <input type="radio" name="'.$var_name.'" id="'.$var_name.'" value="'.$key.'" '.$checked.'> &nbsp;&nbsp;'.$val.'
                            <span></span>
                        </label>&nbsp;&nbsp;&nbsp;&nbsp;';
        }

        $output .= "</div>";                                              
 
        echo $output;
    }  
    function checkbox($var_name="",$value="", $array_data="")
    {   
        # $array_data ::  arr[key] = val
        # $value ::  arr[key] = key

        $output = '';
        $output = '<div class="mt-checkbox-list">';

        if (is_array($array_data) && count($array_data) > 0) {
            FOREACH ($array_data AS $key => $val) { 
                $checked = ""; 
                IF (isset($value[$key]) && $value[$key] != "") {
                    $checked = " checked"; 
                }

                $output .= '<label class="mt-checkbox mt-checkbox-outline">
                                <input type="checkbox" name="'.$var_name.'[]" id="'.$var_name.'[]" value="'.$key.'" '.$checked.'>&nbsp;&nbsp;'.$val.'
                                <span></span>
                            </label>';
            }                                                                            
        }   

        $output .= "</div>";                                              
 
        echo $output;
    }  
    function selectbox($var_name="",$value="", $array_data="", $multiple = "", $class_name = "chosen-select", $placeholder="", $inlinehelp="")
    { 
        # $array_data ::  arr[key] = val
        $output = '';
        $output .= "<select class=\"".$class_name."\" name=\"".$var_name."\" id=\"".$var_name."\" ".$multiple.">";

        if (is_array($array_data) && count($array_data) > 0) {
            FOREACH ($array_data AS $key => $val) {
                $selected = "";
                if ($value == $key) $selected = " selected";
                $output .= "<option  value=\"".$key."\" ".$selected.">".$val."</option>";
            }                                                                            
        } 

        $output .= "</select>";                                                 
 
        echo $output;
    }  
    function selectbox_multiple($var_name="",$value="", $array_data="", $multiple = "multiple", $class_name = "form-control select2_sample1", $placeholder="", $inlinehelp="")
    { 
        # $array_data ::  arr[key] = val
        # $value ::  arr[key] = key

        $output = '';
        $output .= "<select class=\"".$class_name."\" name=\"".$var_name."\" id=\"".$var_name."\" ".$multiple.">";

        if (is_array($array_data) && count($array_data) > 0) {
            FOREACH ($array_data AS $key => $val) { 
                    $selected = "";
                IF (isset($value[$key]) && $value[$key] != "") {
                    $selected = " selected"; 
                } 
                $output .= "<option  value=\"".$key."\" ".$selected.">".$val."</option>";
            }                                                                            
        } 

        $output .= "</select>";                                                 
 
        echo $output;
    }  

    /****** END FORM LIB ******/
    
    function generate2darray($table, $key, $val, $where = "",$val2 = "")
    { 
        $output = '';
        $sql    = 'select * from '.$table. " ";
        if ($where <> "") $sql .= " WHERE ". $where;
        $sql .= "ORDER BY $val";
        $query = $this->ci->db->query($sql);
   
        foreach ($query->result() as $row) {
            $id             = $row->$key; 
            if ($val2 == "") {
                $output[$id]    = $row->$val;
            } else {
                $output[$id]    = $row->$val.' - '. $row->$val2;
            }
        }  
        return $output;
    } 
    function generateparent($table, $key, $val, $arr_where)
    {  
        $data = ""; $id = "";
        $sql    = "select * from ".$table." WHERE parent_id = '0' AND is_topik='0' ORDER BY no_urut"; 
        #echo $sql."<br>\n";
        $query = $this->ci->db->query($sql);

        $data["0"]      = "-";
   
        foreach ($query->result() as $rs) {
            $id         = $rs->$key;
            $data[$id]  = $rs->$val; 
            $label      = $data[$id];
            $data       = $this->getchild($data, $table, $key, $val, $id, $label);
        }  
        #print_r($data);exit;
        return $data;
    } 
    function getchild($data, $table, $key, $val, $id, $label) { 
        $sql    = "select * from ".$table." WHERE parent_id = $id AND is_topik='0' ORDER BY no_urut"; 
        #echo $sql."<br>\n";
        $query = $this->ci->db->query($sql);
   
        foreach ($query->result() as $rs) {
            $id         = $rs->$key;
            $data[$id]  = $label ." - ". $rs->$val; 
            $label      = $data[$id];
            $data       = $this->getchild($data, $table, $key,$val, $id, $label);
        }  
        return $data;
    }
    //////
    
    function inputfile_ace($var_name="",$value="", $readonly="false", $placeholder="", $inlinehelp="")
    { 
        $output = '';
        $output .= '<label class="ace-file-input">
                        <input id="'.$var_name.'" type="file">
                        <span class="ace-file-container selected" data-title="Change">
                        <span class="ace-file-name" data-title="">
                        </span>
                        <a class="remove" href="#">
                    </label>'; 
        echo $output;
    } 
    
    function selisih_jam($jam_awal="",$jam_akhir="")
    {  
        $l = explode(":", $jam_awal);
        $dtawal = mktime($l[0],$l[1], $l[2],"1","1","1");
        $la = explode(":", $jam_akhir);
        $dtakhir = mktime($la[0],$la[1], $la[2],"1","1","1");

        $dtselisih = $dtakhir - $dtawal;

        $totalmenit = $dtselisih / 60;
        echo "Total Menit : ". $totalmenit."<br>";
        echo "menit / 60 : ". ($totalmenit / 60) ."<br>";
        $jam = explode(".", $totalmenit / 60);
        echo "Jam : ". $jam[0]."<br>"; 
        $sisamenit = (($totalmenit / 60) - $jam[0]) * 60;
        echo "menit : ". number_format($sisamenit,2)."<br>"; 
        
    } 
    function get_jam($jam_awal)
    {  
        $l = explode(":", $jam_awal);
        return $l[0]; 
    } 
    function get_prosentase_lama_tidur($jam_awal)
    {  
        $l = explode(":", $jam_awal);
        if ($l[0]>=6) return 20; else return 0; 
    } 
    function get_point_fatique($persentase)
    {  
        if ($persentase >= 81) {
            return 3;
        } else if ($persentase >=66 && $persentase <= 80) {
            return 2;
        } else {
            return 1;
        }
    } 
    function get_point_spo($persentase)
    {  
        if ($persentase >= 95) {
            return 3;
        } else if ($persentase >=90 && $persentase <= 94) {
            return 2;
        } else {
            return 1;
        }
    } 
    function get_point_bpm($persentase)
    {  
        if ($persentase > 100) {
            return 2;
        } else if ($persentase >=50 && $persentase <= 100) {
            return 3;
        } else {
            return 1;
        }
    } 
    function get_nilai_pengawasan($var_1, $var_2, $var_3)
    {  
        $nilai_terendah = min($var_1, $var_2, $var_3);
        return $nilai_terendah;
    } 
    function get_status_pengawasan($var_1, $var_2, $var_3)
    {  
        $nilai_terendah = min($var_1, $var_2, $var_3);
        if ($nilai_terendah == 3) {
            return "DISETUJUI";
        } else if ($nilai_terendah == 2) {
            return "BUTUH PENGAWASAN";
        } else {
            return "TIDAK DISETUJUI";
        }
    } 
} 
?>