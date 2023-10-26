<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * FOR ANDROID APP
 */
class Tracker extends CI_Controller
{
    public $maxspeed = 57;

    function __construct()
    {
        parent::__construct();
        $setting = $this->db->limit(1)->get('sync_setting_tracker')->row();
        $this->maxspeed = $setting->max_speed;
    }

    public function index()
    {
        $this->return(['status' => 1]);
    }

    public function syncAdmin()
    {
        $data = $this->db->limit(1)->get('sync_admin');
        $this->return($data->row());
    }

    public function syncStation()
    {
        $data = $this->db->get('sync_station');
        $this->return($data->result());
    }

    public function registerDevice()
    {
        $data       = $this->input->get();
        $param      = ['device_id' => $data['device_id']];
        $device     = $this->db->where($param)->get('sync_unit');

        $newData    = [
            'device_id'         => $data['device_id'],
            'unit'              => $data['unit'],
            'date_last_update'  => date('Y-m-d H:i:s'),
            'active'            => 1
        ];

        if ($device->row()) {
            $this->db->update('sync_unit', $newData, $param);
        } else {
            $this->db->insert('sync_unit', $newData);
        }

        $this->return($this->db->where($param)->get('sync_unit')->row());
    }

    public function syncSettingTracker()
    {
        $data = $this->db->limit(1)->get('sync_setting_tracker');
        $this->return($data->row());
    }

    public function syncOperator()
    {
        $data = $this->db->get('sync_operator');
        $this->return($data->result());
    }

    public function uploadLog()
    {
        $rows   = json_decode($this->input->post('rows'));
        $ids    = [];

        foreach($rows as $r)
        {
            $ids[] = $r->id;

            $geoLog = [
                'latitude'          => $r->latitude,
                'longitude'         => $r->longitude,
                'altitude'          => $r->altitude,
                'heading'           => $r->heading,
                'accuracy'          => $r->accuracy,
                'speed'             => $r->speed,
                'status'            => $r->status,
                'hm'                => $r->hm,
                'device_id'         => $r->device_id,
                'nip'               => $r->nip,
                'unit'              => $r->unit,
                'date_insert'       => date('Y-m-d H:i:s'),
                'time_stamp'        => date('Y-m-d H:i:s', $r->timestamp_device),
                'time_stamp_gps'    => date('Y-m-d H:i:s', $r->timestamp)
            ];

			$checkParams = [
				'time_stamp' 	=> date('Y-m-d H:i:s', $r->timestamp_device),
				'unit' 			=> $r->unit,
				'nip' 			=> $r->nip,
				'status'		=> $r->status,
				'device_id'		=> $r->device_id
			];

			if ($this->db->get_where('log_geolocation', $checkParams)->num_rows() == 0)
			{
				$this->db->insert('log_geolocation', $geoLog); // ok

	            $this->updateCurrentPosition($geoLog); // ok

	            // SUDAH OK
	            if ($r->status == 'overspeed') {
	                $this->logOverspeed($geoLog);
	            }

	            if ($r->status == 'login' || $r->status == 'logout') {
	                $this->logAbsen();
	            }

	            if ($r->status == 'start' || $r->status == 'stop') {
	                $this->logIdle('start');
	            }

	            if ($r->status == 'one-sycle') {
	                $this->logOneCycle();
	            }
			}
        }

        $ret = (count($ids) > 0)
            ? ['ids' => implode(',', $ids), 'success' => true]
            : ['success' => false];

        $this->return($ret);
    }

    public function getEquipment()
    {
        $units = $this->db
            ->select('new_eq_num AS name')
            ->get('master_equipment');

        $this->return($units->result());
    }

	// belum ok
    public function uploadGeofenceLog()
    {
        $rows   = json_decode($this->input->post('rows'));
        $ids    = [];

        foreach($rows as $r)
        {
            $ids[] = $r->id;

            $geofenceLog = [
                'lat'               => $r->latitude,
                'lon'               => $r->longitude,
                'device_id'         => $r->device_id,
                'station_id'        => $r->station_id,
                'nip'               => $r->nip,
                'unit'              => $r->unit,
                'date_in'           => $r->date_in,
                'time_in'           => $r->time_in,
                'date_out'          => $r->date_out,
                'time_out'          => $r->time_out
            ];

            $this->db->insert('log_coverage_in', $geofenceLog);
        }

        $ret = (count($ids) > 0)
            ? ['ids' => implode(',', $ids), 'success' => true]
            : ['success' => false];

        $this->return($ret);
    }

	// ok
    protected function updateCurrentPosition($geoLog)
    {
		$params = ['device_id' => $geoLog['device_id']];
		unset($geoLog['hm'], $geoLog['status'], $geoLog['time_stamp_gps']);

		if ($this->db->get_where('current_unit_position', $params)->num_rows())
		{
			$this->db->update('current_unit_position', $geoLog, $params);
		}

		else
		{
			$this->db->insert('current_unit_position', $geoLog);
		}
    }

    // SUDAH OK
    protected function logOverspeed($geoLog)
    {
        // 3 data ini doank yg ga ada di table log_over_speed
        unset($geoLog['hm'], $geoLog['status'], $geoLog['time_stamp_gps']);
        $geoLog['date_insert'] = date('Y-m-d H:i:s');
        $this->db->insert('log_over_speed', $geoLog);
    }

    protected function logAbsen($geoLog)
    {
		// check apakah sudah login
		$fullDate 	= new DateTime($geoLog['timestamp']);
		$sudahAbsen = $this->db
						->where('nip', $geoLog['nip'])
						->where('date <=', $fullDate->format('Y-m-d'))
						->where('date_out IS NULL')
						->where('time_out IS NULL')
						->limit(1)
						->get('daily_absent')
						->num_rows();

        if ($geoLog['status'] == 'login')
		{
			if ($sudahAbsen == 0) // belum absen, insert data
			{
				$data = [
					'device_id' => $geoLog['device_id'],
					'unit'		=> $geoLog['unit'],
					'nip'		=> $geoLog['nip'],
					'date'		=> $fullDate->format('Y-m-d'),
					'time_in'	=> $fullDate->format('H:i:s'),
					'hm_awal'	=> $geoLog['hm'],
					'shift'		=> $this->shift($fullDate->format('H')),
					'latitude_start'	=> $geoLog['latitude'],
					'longitude_start'	=> $geoLog['longitude'],
					'time_start_position_station' => $geoLog['timestamp']
				];

				$this->db->insert('daily_absent', $data);

				// belum jalan
				if ($this->db->affected_rows() > 0) {
					// $this->startCycle($data);
				}
			}
        }

        if ($geoLog['status'] == 'logout')
		{
			if ($sudahAbsen)
			{
				$data = [
					'date_out'	=> $fullDate->format('Y-m-d'),
					'time_out'	=> $fullDate->format('H:i:s'),
					'hm_akhir'	=> $geoLog['hm'],
					'latitude_end'	=> $geoLog['latitude'],
					'longitude_end'	=> $geoLog['longitude'],
					'time_stop_position_station' => $geoLog['timestamp']
				];

				$this->db
					->where('nip', $geoLog['nip'])
					->where('date <=', $fullDate->format('Y-m-d'))
					->where('date_out IS NULL')
					->where('time_out IS NULL')
					->update('daily_absent', $data);

				// belum jalan
				if ($this->db->affected_rows() > 0) {
					// $this->endCycle($data);
				}
			}
        }

    }

    protected function logIdle()
    {

    }

    protected function logOneCycle()
    {

    }

    protected function updateFpi()
    {

    }

    public function update()
    {
        $this->load->helper('download');
        force_download('phoenix-latest.apk', null);
    }

    protected function shift($jam) {
        return ($jam > 0 && $jam <= 15) ? 1 : 2;
    }

    protected function return($data)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        echo json_encode($data);
        exit();
    }

}
