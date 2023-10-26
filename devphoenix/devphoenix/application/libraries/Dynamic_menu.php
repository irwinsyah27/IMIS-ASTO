<?php 
/*
 * Dynmic_menu.php
 */
class Dynamic_menu {
 
    private $ci;                // para CodeIgniter Super Global Referencias o variables globales
    private $id_menu            = 'id="menu"';
    private $class_menu         = 'class="menu"';
    private $class_parent       = 'class="parent"';
    private $class_last         = 'class="last"';
    // --------------------------------------------------------------------
    /**
     * PHP5        Constructor
     *
     */
    function __construct()
    {
        $this->ci =& get_instance();    // get a reference to CodeIgniter. 
        $this->ci->load->library('session');
 
        if ($this->ci->session->userdata("id") <> "") {

            $rs     = $this->ci->db->query("SELECT * FROM user_akses WHERE user_id=".$this->ci->session->userdata("id"));

            if (count($rs->result()) > 0) {
               foreach ($rs->result() as $r) {
                    $dt[$r->user_menu_id]["view"] = $r->view;
                    $dt[$r->user_menu_id]["add"] = $r->add;
                    $dt[$r->user_menu_id]["edit"] = $r->edit;
                    $dt[$r->user_menu_id]["del"] = $r->del;
                }
            }
            $this->user_akses         = $dt;
        }
    }
    // --------------------------------------------------------------------
     /**
     * build_menu($table, $type)
     *
     * Description:
     *
     * builds the Dynaminc dropdown menu
     * $table allows for passing in a MySQL table name for different menu tables.
     * $type is for the type of menu to display ie; topmenu, mainmenu, sidebar menu
     * or a footer menu.
     *
     * @param    string    the MySQL database table name.
     * @param    string    the type of menu to display.
     * @return    string    $html_out using CodeIgniter achor tags.
     */
 
    function build_menu($type="",$check_menu="")
    {
        $menu = array();
 
        $query = $this->ci->db->query("select * from user_menu where parent_id = 0 AND show_menu = '1' order by um_order");
  
        $html_out = '<ul class="nav nav-list">';

        foreach ($query->result() as $row) {
            $id             = $row->user_menu_id;
            $title          = $row->title;
            $link_type      = $row->link_type;
            $page_id        = $row->page_id;
            $module_name    = $row->module_name;
            $url            = $row->url;
            $uri            = $row->uri;
            $user_group_id  = $row->user_group_id;
            $position       = $row->position;
            $target         = $row->target;
            $parent_id      = $row->parent_id;
            $is_parent      = $row->is_parent;
            $show_menu      = $row->show_menu;
            $um_class       = $row->um_class;
            $um_order       = $row->um_order;

            if ($show_menu && $parent_id == 0)  { 
                if ($is_parent == TRUE) { 
                $active = "";
                if ($module_name == $check_menu["parent_menu"]) $active = "active open";
                    if (isset($this->user_akses[$id]["view"]) && $this->user_akses[$id]["view"] == 1) {
    $html_out .= '<li class="'.$active.'">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon fa '.$um_class.'"></i>
                            <span class="menu-text"> '.$title.' </span>

                            <b class="arrow fa fa-angle-down"></b>
                        </a>

                        <b class="arrow"></b>'; 

                    }
                }  else {
                $active = "";
                if ($module_name == $check_menu["sub_menu"]) $active = "active";
                    if (isset($this->user_akses[$id]["view"]) && $this->user_akses[$id]["view"] == 1) {
$html_out .= '<li class="'.$active.'">
                    <a href="'.base_url($url).'">
                        <i class="menu-icon fa '.$um_class.'"></i>
                        <span class="menu-text"> '.$title.'</span>
                    </a>

                    <b class="arrow"></b>
                ';
                    }
                } 
            } 
            $html_out .= $this->get_childs($id,$check_menu); 
        } 
 
        $html_out .= '</ul>';
 
        return $html_out;
    }
     /**
     * get_childs($menu, $parent_id) - SEE Above Method.
     *
     * Description:
     *
     * Builds all child submenus using a recurse method call.
     *
     * @param    mixed    $id
     * @param    string    $id usuario
     * @return    mixed    $html_out if has subcats else FALSE
     */
    function get_childs($id, $check_menu ="")
    {
        $has_subcats = FALSE;
        $html_out  = '';
        $html_out  .= '<ul class="submenu">';  

        $query = $this->ci->db->query("select * from user_menu where parent_id = $id AND show_menu = '1'  ORDER BY um_order");
 
        foreach ($query->result() as $row) {
            $id             = $row->user_menu_id;
            $title          = $row->title;
            $link_type      = $row->link_type;
            $page_id        = $row->page_id;
            $module_name    = $row->module_name;
            $url            = $row->url;
            $uri            = $row->uri;
            $user_group_id   = $row->user_group_id;
            $position       = $row->position;
            $target         = $row->target;
            $parent_id      = $row->parent_id;
            $is_parent      = $row->is_parent;
            $show_menu      = $row->show_menu;
            $um_class       = $row->um_class;
            $um_order       = $row->um_order;


            if ($um_class =="") $um_class = "fa-caret-right";

            $has_subcats = TRUE;

            if ($is_parent == TRUE) {
                $active = "";
                if ($module_name == $check_menu["parent_menu"]) $active = "active open";
                if (isset($this->user_akses[$id]["view"]) && $this->user_akses[$id]["view"] == 1) {
    $html_out .= '<li class="'.$active.'">
                    <a href="#" class="dropdown-toggle">
                        <i class="menu-icon fa '.$um_class.'"></i>
                        <span class="menu-text"> '.$title.' </span>

                        <b class="arrow fa fa-angle-down"></b>
                    </a>

                    <b class="arrow"></b>';
                }
            } else {
                $active = "";
                if ($module_name == $check_menu["sub_menu"]) $active = "active";
                if (isset($this->user_akses[$id]["view"]) && $this->user_akses[$id]["view"] == 1) {
                    $html_out .= '<li class="'.$active.'">
                        <a href="'.base_url($url).'">
                            <i class="menu-icon fa '.$um_class.'"></i>
                            '.$title.' 
                        </a>

                        <b class="arrow"></b>
                    </li> ';
                }
            }

            $html_out .= $this->get_childs($id, $check_menu);
        }
        $html_out .= '</li></ul>';
 
        return ($has_subcats) ? $html_out : FALSE; 
    }
}
 
// ------------------------------------------------------------------------
// End of Dynamic_menu Library Class.
// ------------------------------------------------------------------------
/* End of file Dynamic_menu.php */
/* Location: ../application/libraries/Dynamic_menu.php */
?>