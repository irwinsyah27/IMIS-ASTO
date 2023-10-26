<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * FOR ANDROID APP
 */
class FuelApi extends CI_Controller
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
                'fuel_tank_id'		=> $r->fuel_tank_id,
                'nrp'               => $r->nrp,
                'equipment_id'      => $r->equipment_id,
                'shift'             => $r->shift,
                'total_liter'       => $r->total_liter,
                'total_realisasi'   => $r->total_realisasi,
                'date_fill'         => $r->date_fill,
                'time_fill_start'   => $r->time_fill_start,
                'time_fill_end'     => $r->time_fill_end,
                'hm'                => $r->hm,
                'km'                => $r->km,
                'hm_last'           => $r->hm_last,
                'km_last'           => $r->km_last,
                'realisasi_by'      => $r->realisasi_by,
                'date_realisasi'    => $r->date_realisasi,
                'date_insert'    	=> $r->date_insert,
                'insert_by'      	=> $r->insert_by,
                'insert_from'       => 2 // ANDROID
            ];

			// check duplikasi
			$exists = $this->db->where($data)->get('fuel_refill')->row();

			if ($exists) {
				continue;
			}

            $this->db->insert('fuel_refill', $data);
        }

        $ret = (count($ids) > 0)
            ? ['ids' => implode(',', $ids), 'success' => true]
            : ['success' => false];

        $this->return($ret);
    }

    public function getEmployee()
    {
        $this->return(
			$this->db
	            ->select('operator_id AS id, nrp, nama')
	            ->get('master_employee')->result()
		);
    }

    public function getUser()
    {
        $this->return(
			$this->db
				->select('user_id AS id, nama AS name, username, passwd AS password')
				->get('user')->result()
		);
    }

    public function getEquipment()
    {
        $this->return(
			$this->db
	            ->select('master_equipment_id AS id, new_eq_num AS name, master_egi_id as egi_id')
	            ->get('master_equipment')->result()
		);
    }

    public function getFuelTank()
    {
        $this->return($this->db->get('master_fuel_tank')->result());
    }

    public function getDailyAbsent()
    {
        $this->return(
			$this->db
				->select('
					daily_absent.nip,
					daily_absent.date,
					daily_absent.date_out,
					daily_absent.time_in,
					daily_absent.time_out,
					daily_absent.shift,
					daily_absent.total_cycle_time,
					daily_absent.unit,
					daily_absent.hm_awal,
					daily_absent.hm_akhir,
					daily_absent.date_insert,
					master_equipment.master_equipment_id AS equipment_id
				')
				->join('master_equipment', 'daily_absent.unit = master_equipment.new_eq_num', 'INNER')
				->order_by('date_insert', 'DESC')->limit(1000)
				->get('daily_absent')->result()
		);
    }

    public function getSetting()
    {
        $this->return($this->db->get('master_table_setting')->row());
    }

	public function getLastTransaction()
	{
		$this->return(
			$this->db
				->order_by('fuel_refill_id', 'DESC')
				->limit(3000)
				->get('fuel_refill')->result()
		);
	}

    protected function return($data)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        echo json_encode($data);
        exit();
    }

}
