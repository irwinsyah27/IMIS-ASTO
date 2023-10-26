<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Master_fuel_tank extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (empty($_SESSION["id"]))  header("location:login");
	}

	public function index()
	{
		$this->load->view(_TEMPLATE , [
			'title'		=> 'Master Fuel Tank',
			'sview'		=> 'master_fuel_tank/index',
			'fuelTanks'	=> $this->db->order_by('name ASC')
							->get('master_fuel_tank')->result(),
			'check_menu'=> ['parent_menu' => 'master_data', 'sub_menu' => 'master_fuel_tank'],
			'breadcrumb'=> '<li>
									<i class="ace-icon fa fa-home home-icon"></i>
									<a href="#">Home</a>
								</li>
								<li class="active">Master Fuel Tank</li>'
		]);
	}

	public function view()
	{
		$this->index();
	}

	public function add()
	{
		if ($data = $this->input->post('ft'))
		{
			$this->db->insert('master_fuel_tank', $data);
			redirect('/master_fuel_tank');
		}

		else
		{
			$this->load->view(_TEMPLATE, [
				'title' => 'Add Master Fuel Tank',
				'sview' => 'master_fuel_tank/add',
				'js' => 'master_fuel_tank/js_form',
				'check_menu'=> ['parent_menu' => 'master_data', 'sub_menu' => 'master_fuel_tank'],
				'breadcrumb'=> '<li>
										<i class="ace-icon fa fa-home home-icon"></i>
										<a href="#">Home</a>
									</li>
									<li class="active">Master Fuel Tank</li>'
			]);
		}
	}

	public function edit($id)
	{
		if ($data = $this->input->post('ft'))
		{
			$this->db->update('master_fuel_tank', $data, ['id' => $id]);
			redirect('/master_fuel_tank');
		}

		else
		{
			$this->load->view(_TEMPLATE, [
				'title' => 'Edit Master Fuel Tank',
				'sview' => 'master_fuel_tank/edit',
				'js' => 'master_fuel_tank/js_form',
				'ft' => $this->db->where(['id' => $id])->get('master_fuel_tank')->row(),
				'check_menu'=> ['parent_menu' => 'master_data', 'sub_menu' => 'master_fuel_tank'],
				'breadcrumb'=> '<li>
										<i class="ace-icon fa fa-home home-icon"></i>
										<a href="#">Home</a>
									</li>
									<li class="active">Master Fuel Tank</li>'
			]);
		}
	}

	public function delete()
	{
		$this->db->delete('master_fuel_tank', ['id' => $this->input->post('id')]);
		redirect('/master_fuel_tank');
	}
}
