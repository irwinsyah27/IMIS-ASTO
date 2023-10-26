<?php

function my_error_handler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno))
    {
        // This error code is not included in error_reporting
        return;
    }
    log_message('error', "$errstr @$errfile::$errline($errno)" );
    throw new ErrorException( $errstr, $errno, 0, $errfile, $errline );
}

set_error_handler("my_error_handler");

class Api extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	public function monitoringSpeedUnit()
	{
		$date = $this->input->get('date')
			? $this->input->get('date')
			: date('Y-m-d');

		// $setting = $this->db->get('sync_setting_tracker')->row();

		$sql = "
            SELECT
                    nip,
                    nama_operator,
                    unit,
                    shift,
                    AVG(speed) AS average_speed,
                    MAX(speed) AS max_speed,
                    COUNT(CASE WHEN speed >= 56 THEN 1 END) total_over_speed,
                    COUNT(CASE WHEN speed BETWEEN 56 AND 60.99 THEN 1 END) overspeed1,
                    COUNT(CASE WHEN speed BETWEEN 61 AND 65.99 THEN 1 END) overspeed2,
                    COUNT(CASE WHEN speed BETWEEN 66 AND 70.99 THEN 1 END) overspeed3,
                    COUNT(CASE WHEN speed BETWEEN 71 AND 75 THEN 1 END) overspeed4,
                    COUNT(CASE WHEN speed > 75 THEN 1 END) overspeed5
            FROM (

            SELECT					
                                    geo.nip,
                                    opr.nama_operator,
                                    geo.unit,
                                    opr.shift,
                                    geo.speed,
                                    geo.time_stamp
                        FROM log_geolocation AS geo inner join (
                                    SELECT
                                            b.nama AS nama_operator, a.nip, a.unit, a.shift
                                        FROM daily_absent a
                                        INNER JOIN master_employee b ON a.nip = b.nrp
                                        WHERE a.date = '{$date}'
                                ) AS opr
                                ON geo.nip = opr.nip and geo.unit = opr.unit
                        WHERE 
                            speed > 0
                            AND (DATE(time_stamp) = '{$date}' and shift = 1)
                            OR (DATE(time_stamp) between '{$date}' and ADDDATE('{$date}', 1) and shift = 2)
            ) AS A GROUP BY nip,nama_operator,unit,shift
        ";
        
        $data = $this->db->query($sql)->result();

		$this->return_json($data);
    }
    
    public function fatigueMonitoring() {
        
        $date = $this->input->get('date');
        $lokasi = $this->input->get('lokasi');
        $nrp_gl = $this->input->get('nrp_gl');

      
    
        $sql = "
                select * from vw_status_fatigue
                where   (date_prajob = '{$date}' or '{$date}' = '')
                        and (master_lokasi_id = '{$lokasi}' or '{$lokasi}' = '')
                        and (nrp_gl = '{$nrp_gl}' or '{$nrp_gl}' = '')
                        ORDER BY date_prajob desc
               ";
        
        $data = $this->db->query($sql)->result();
        $this->return_json($data);

    }

    public function getCtFpi()
    {
        $date = $this->input->get('date')
			? $this->input->get('date')
			: date('Y-m-d');

        $query = "SELECT
                da.nip,
                da.unit,
                da.daily_absent_id,
                mem.nama AS nama_operator,
                meg.keterangan AS egi,
                meq.master_equipment_id,
                da.date,
                da.time_start_position_station,
                da.time_stop_position_station,
                da.shift
            FROM daily_absent da
            LEFT JOIN master_employee mem ON da.nip = mem.nrp
            LEFT JOIN master_equipment meq ON da.unit = meq.new_eq_num
            LEFT JOIN master_egi meg ON meq.master_egi_id = meg.master_egi_id
            WHERE da.date = '{$date}'
            ORDER BY mem.nama";

        $data = $this->db->query($query)->result();

        foreach ($data as $d)
        {
            $query = "SELECT
                    `ct_fpi`.*,
                    TIME_TO_SEC(TIMEDIFF(TIMEDIFF(`ct1_end`, `ct1_start`), `ct1_idle`)) / (60*60) AS `ct1`,
                    TIME_TO_SEC(TIMEDIFF(TIMEDIFF(`ct2_end`, `ct2_start`), `ct2_idle`)) / (60*60) AS `ct2`,
                    TIME_TO_SEC(TIMEDIFF(TIMEDIFF(`ct3_end`, `ct3_start`), `ct3_idle`)) / (60*60) AS `ct3`
                FROM `ct_fpi`
                WHERE
                    DATE(`ct1_start`) = '{$d->date}'
                    AND `ct_fpi`.`unit` = '$d->unit'
                    AND `ct_fpi`.`nrp` = '$d->nip'
            ";

            $ct = $this->db->query($query)->row();

            // kalau ga ada cari di log_cycle_time
            if (!$ct)
            {
                $query1 = "SELECT * FROM `log_cycle_time`
                    WHERE
                        `nip` = '{$d->nip}'
                        AND `unit` = '{$d->unit}'
                        AND DATE(`datetime_start`) = '{$date}'
                ";

                $result = $this->db->query($query1)->result();

                $ct_index = 1;
                $ct_temp = ['nrp' => $d->nip, 'unit' => $d->unit];

                foreach ($result as $r)
                {
                    if ($ct_index == 1) {
                        $ct_temp += [
                            'ct1_start' => $r->datetime_start ? $r->datetime_start : '0000-00-00 00:00:00',
                            'ct1_end' => $r->datetime_end ? $r->datetime_end : '0000-00-00 00:00:00',
                            'ct1_idle' => $r->total_idle ? $this->secToTime($r->total_idle) : '00:00:00',
                            'fpi1' => $r->fpi ? $r->fpi : 0,
                        ];
                    }

                    if ($ct_index == 2) {
                        $ct_temp += [
                            'ct2_start' => $r->datetime_start ? $r->datetime_start : '0000-00-00 00:00:00',
                            'ct2_end' => $r->datetime_end ? $r->datetime_end : '0000-00-00 00:00:00',
                            'ct2_idle' => $r->total_idle ? $this->secToTime($r->total_idle) : '00:00:00',
                            'fpi2' => $r->fpi ? $r->fpi : 0,
                        ];
                    }

                    if ($ct_index == 3) {
                        $ct_temp += [
                            'ct3_start' => $r->datetime_start ? $r->datetime_start : '0000-00-00 00:00:00',
                            'ct3_end' => $r->datetime_end ? $r->datetime_end : '0000-00-00 00:00:00',
                            'ct3_idle' => $r->total_idle ? $this->secToTime($r->total_idle) : '00:00:00',
                            'fpi3' => $r->fpi ? $r->fpi : 0,
                        ];
                    }

                    $ct_index++;
                }

                if (count($result) < 3) {
                    $ct_temp += [
                        'ct3_start' => '0000-00-00 00:00:00',
                        'ct3_end' => '0000-00-00 00:00:00',
                        'ct3_idle' => '00:00:00',
                        'fpi3' => 0,
                    ];
                }

                if (count($result) < 2) {
                    $ct_temp += [
                        'ct2_start' => '0000-00-00 00:00:00',
                        'ct2_end' => '0000-00-00 00:00:00',
                        'ct2_idle' => '00:00:00',
                        'fpi2' => 0,
                    ];
                }

                // insert kalau ada datanya dan tanggal gak sama dengan tanggal hari ini
                // tanggal hari ini datanya masih blm valid
                if (count($result) > 0 && $date != date('Y-m-d')) {
                    $this->db->insert('ct_fpi', $ct_temp);
                    // ulangi lagi query-nya
                    $ct = $this->db->query($query)->row();
                }

                // kalau ga pake data dari log_cycle_time
                else {
                    $ct_temp += ['id' => null];
                    $ct = $ct_temp;
                }
            }

            $d->ct = $ct;
        }

        $this->return_json($data);
    }

    public function getSingleCtFpi($daily_absent_id, $ct_id)
    {
        $query = "SELECT
                da.nip,
                da.unit,
                da.daily_absent_id,
                mem.nama,
                meg.keterangan AS egi,
                meq.master_equipment_id,
                da.date,
                da.date_out,
                da.time_start_position_station,
                da.time_stop_position_station,
                da.shift
            FROM daily_absent da
            LEFT JOIN master_employee mem ON da.nip = mem.nrp
            LEFT JOIN master_equipment meq ON da.unit = meq.new_eq_num
            LEFT JOIN master_egi meg ON meq.master_egi_id = meg.master_egi_id
            WHERE da.daily_absent_id = {$daily_absent_id}
            ORDER BY mem.nama";

        $data = $this->db->query($query)->row();

        $sql = "SELECT
                `ct_fpi`.*,
                TIME_TO_SEC(TIMEDIFF(TIMEDIFF(`ct1_end`, `ct1_start`), `ct1_idle`)) / (60*60) AS `ct1`,
                TIME_TO_SEC(TIMEDIFF(TIMEDIFF(`ct2_end`, `ct2_start`), `ct2_idle`)) / (60*60) AS `ct2`,
                TIME_TO_SEC(TIMEDIFF(TIMEDIFF(`ct3_end`, `ct3_start`), `ct3_idle`)) / (60*60) AS `ct3`
            FROM `ct_fpi`
            WHERE `ct_fpi`.`id` = '{$ct_id}'
        ";

        $data->ct = $this->db->query($sql)->row();
        $this->return_json($data);
    }

    public function saveCtFpi()
    {
        $input = json_decode(file_get_contents("php://input"));
        $time_start = explode(' ', $input->time_start);
        $time_stop = explode(' ', $input->time_stop);

        try {
            // insert to daily_absent first
            $absent_data = [
                'nip' => $input->nrp,
                'date' => $input->date,
                'date_out' => $time_stop[0],
                'unit' => $input->unit,
                'shift' => $input->shift,
                'time_in' => $time_start[1],
                'time_out' => $time_stop[1],
                'time_start_position_station' => $time_start[1],
                'time_stop_position_station' => $time_stop[1],
            ];
        } catch (Exception $e) {
            $this->return_json([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }

        if ($input->daily_absent_id > 0) {
            $this->db
                ->where('daily_absent_id', $input->daily_absent_id)
                ->update('daily_absent', $absent_data);
        } else {
            $this->db->insert('daily_absent', $absent_data);
        }

        try {
            $ct_data = [
                'nrp'       => $input->nrp,
                'unit'      => $input->unit,
                'ct1_start' => isset($input->ct1_start) ? $input->ct1_start : '0000-00-00 00:00:00',
                'ct2_start' => isset($input->ct2_start) ? $input->ct2_start : '0000-00-00 00:00:00',
                'ct3_start' => isset($input->ct3_start) ? $input->ct3_start : '0000-00-00 00:00:00',
                'ct1_end'   => isset($input->ct1_end) ? $input->ct1_end : '0000-00-00 00:00:00',
                'ct2_end'   => isset($input->ct2_end) ? $input->ct2_end : '0000-00-00 00:00:00',
                'ct3_end'   => isset($input->ct3_end) ? $input->ct3_end : '0000-00-00 00:00:00',
                'ct1_idle'  => isset($input->ct1_idle) ? $input->ct1_idle : '00:00:00',
                'ct2_idle'  => isset($input->ct2_idle) ? $input->ct2_idle : '00:00:00',
                'ct3_idle'  => isset($input->ct3_idle) ? $input->ct3_idle : '00:00:00',
                'fpi1'      => isset($input->fpi1) ? $input->fpi1 : 0,
                'fpi2'      => isset($input->fpi2) ? $input->fpi2 : 0,
                'fpi3'      => isset($input->fpi3) ? $input->fpi3 : 0,
                'last_update' => date('Y-m-d H:i:s')
            ];
        } catch (Exception $e) {
            $this->return_json([
                'status' => 0,
                'message' => $e->getMessage()
            ]);
        }

        if ($input->ct_id > 0) {
            $this->db->where('id', $input->ct_id)->update('ct_fpi', $ct_data);
        } else {
            $this->db->insert('ct_fpi', $ct_data);
        }

        return $this->return_json([
            'status' => 1,
            'message' => 'SUCCESS!'
        ]);
    }

    public function deleteCtFpi()
    {
        $input = json_decode(file_get_contents("php://input"));

        $this->db
            ->where('daily_absent_id', $input->daily_absent_id)
            ->delete('daily_absent');

        if ($this->db->affected_rows() > 0) {
            $this->db->where('id', $input->ct_id)->delete('ct_fpi');
        }

        return $this->return_json(['status' => $this->db->affected_rows()]);
    }

    public function getUnit()
    {
        $this->return_json(
			$this->db
				->select('new_eq_num AS name')
				->get('master_equipment')->result()
		);
    }

    public function getEmployee()
    {
        $this->return_json(
			$this->db
				->select('`nrp`, `nama`')
				->get('master_employee')->result()
		);
    }

    protected function secToTime($seconds)
    {
        $hours = floor($seconds / 3600);
        $mins = floor($seconds / 60 % 60);
        $secs = floor($seconds % 60);
        return sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
    }

	protected function return_json($data)
	{
		header('Content-Type: application/json');
		echo json_encode($data, JSON_NUMERIC_CHECK);
		exit();
	}
}
