<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * FOR ANDROID APP
 */
class MofonApi extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->return(['status' => 1]);
    }

    public function upload()
    {
        $rows   		= json_decode($this->input->post('rows'));
        // $lastPosition   = json_decode($this->input->post('lastPosition'));
        // $fuelTankId   	= $this->input->post('fuelTankId');
        $ids    		= [];

		// update last location
		// $this->db->update('master_fuel_tank', $lastPosition, ['id' => $fuelTankId]);

        foreach($rows as $r)
        {
            $ids[] = $r->id;

            $data = [
                'nrp_gl'		        => $r->nrp_gl,
                'nrp_opr'               => $r->nrp_opr,
                'date_prajob'           => $r->date_prajob,
                'shift'                 => $r->shift,
                'master_lokasi_id'      => $r->master_lokasi_id,
                'master_equipment_id'   => $r->master_equipment_id,
                'mulai_tidur_hari_ini'  => $r->mulai_tidur_hari_ini,
                'akhir_tidur_hari_ini'  => $r->akhir_tidur_hari_ini,
                'mulai_tidur_kemarin'   => $r->mulai_tidur_kemarin,
                'akhir_tidur_kemarin'   => $r->akhir_tidur_kemarin,
                'minum_obat'            => $r->minum_obat,
                'ada_masalah'           => $r->ada_masalah,
                'siap_bekerja'          => $r->siap_bekerja,
                'persetujuan_bekerja_id'=> $r->persetujuan_bekerja_id,
                'insert_by'             => $r->insert_by,
                'date_insert'    	    => $r->date_insert,
               
            ];

			// check duplikasi
			$exists = $this->db->where($data)->get('fatigue_monitor')->row();

			if ($exists) {
				continue;
			}

            $this->db->insert('fatigue_monitor', $data);
        }

        $ret = (count($ids) > 0)
            ? ['ids' => implode(',', $ids), 'success' => true]
            : ['success' => false];

        $this->return($ret);
    }

    public function getEmployee()
    {
        $posisi_id = array(5,8); // 5 = GL , 8 = Operator
        $this->return(
			$this->db
                ->select('operator_id AS id, nrp, nama, master_posisi_id')
                ->where_in('master_posisi_id',$posisi_id)
	        ->get('master_employee')->result()
		);
    }

    public function getUser()
    {
        $this->return(
			$this->db
				->select('user_id AS id, nama AS name, username, passwd AS password, user_menu_id')
				->get('vw_user_by_menu')->result()
		);
    }

    public function getEquipment()
    {
        $this->return(
			$this->db
	            ->select('master_equipment_id AS id, new_eq_num AS name')
	            ->get('master_equipment')->result()
		);
    }

    public function getLocation() {
        $this->return(
			$this->db
	            ->select('master_lokasi_id AS id,lokasi')
	            ->get('master_lokasi')->result()
		);
    }

    public function getLastTransaction() {
        $this->return(
            $this->db
                ->order_by('fm_id', 'DESC')
	            ->limit(1000)
	            ->get('fatigue_monitor')->result()
		);
    }

    public function getSetting()
    {
        $this->return($this->db->get('master_table_setting')->row());
    }

    protected function return($data)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        echo json_encode($data);
        exit();
    }

}
