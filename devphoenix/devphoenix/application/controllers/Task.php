<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Task extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		if (empty($_SESSION["id"])) {
			header("location:login");
		}

		$this->load->library('upload', [
			'upload_path' => './uploads/',
			'allowed_types' => 'gif|png|jpg|jpeg|pdf|xls|xlsx|doc|docx|zip',
		]);
	}

	public function index()
	{
		$q 			= $this->input->get('q');
		$status 	= $this->input->get('status');
		$is_scope 	= $this->input->get('is_scope');
		$priority 	= $this->input->get('priority');
		$order_field= $this->input->get('order_field', 'ctime');
		$order_dir  = $this->input->get('order_dir', 'DESC');

		$params = "(tasks.title LIKE '%$q%' OR tasks.description LIKE '%$q%' OR c.nama LIKE '%$q%' OR o.nama LIKE '%$q%')";

		if ($status != '') {
			$params .= " AND tasks.status = $status";
		}

		if ($is_scope != '') {
			$params .= " AND tasks.is_scope = $is_scope";
		}

		if ($priority != '') {
			$params .= " AND tasks.priority = $priority";
		}

		$pagination = [
			'base_url' => base_url('task/index'),
			'reuse_query_string' => TRUE,
			'total_rows' => $this->db
							->where($params)
							->join('user c', 'tasks.user_id=c.user_id', 'LEFT')
							->join('user o', 'tasks.owner_id=o.user_id', 'LEFT')
							->count_all_results('tasks'),
			'per_page' => 10
		];

		$tasks = $this->db
				->select('tasks.*, c.nama AS creator, o.nama AS pic')
				->where($params)
				->join('user c', 'tasks.user_id=c.user_id', 'LEFT')
				->join('user o', 'tasks.owner_id=o.user_id', 'LEFT')
				->order_by($order_field, $order_dir)
				->get('tasks', $pagination['per_page'], $this->uri->segment(3))->result();

		$this->load->library('pagination');
		$this->pagination->initialize($pagination);

		if ($this->input->is_ajax_request()) {
			echo json_encode([
				'table' => $this->load->view('task/_list', ['tasks' => $tasks], true),
				'pager' => $this->load->view('task/_pager', [], true),
				'total' => $pagination['total_rows'],
				'status'=> $status,
			]);
			exit();
		}

		$this->load->view(_TEMPLATE , [
			'title'			=> 'Task List',
			'sview'			=> 'task/index',
			'js'			=> 'task/js_index',
			'tasks'			=> $tasks,
			'pagination'	=> $pagination,
			'check_menu'	=> ['parent_menu' => '', 'sub_menu' => 'task'],
			'breadcrumb'	=> '<li>
									<i class="ace-icon fa fa-home home-icon"></i>
									<a href="'.base_url().'">Home</a>
								</li>
								<li class="active">Task List</li>'
		]);
	}

	public function view($id)
	{
		$task = $this->db
				->select('tasks.*, c.nama AS creator, o.nama AS pic')
				->join('user c', 'tasks.user_id=c.user_id', 'LEFT')
				->join('user o', 'tasks.owner_id=o.user_id', 'LEFT')
				->where('id', $id)->get('tasks')->row();

		if ($post = $this->input->post('comment'))
		{
			$this->load->library('form_validation');

			$this->form_validation->set_rules([
				[
					'field' => 'comment[comment]',
					'label'	=> 'Comment',
					'rules'	=> 'required'
				]
			]);

			if ($this->form_validation->run() == TRUE)
			{
				$post['task_id'] 	= $id;
				$post['ctime']		= $post['utime'] = time();
				$post['user_id']	= $_SESSION['id'];

				if ($this->upload->do_upload('file'))
				{
					$file = $this->upload->data();
					$post['file'] = '/uploads/'.$file['file_name'];
				}

				// insert comment
				$this->db->insert('task_comments', $post);

				$task_update = ['utime' => time()];

				$history = [
					'task_id'	=> $id,
					'utime'		=> time(),
					'status'	=> $post['status'],
					'owner_id'	=> $post['owner_id'],
					'priority'	=> $post['priority'],
					'is_scope'	=> $post['is_scope'],
					'user_id'	=> $_SESSION['id'],
				];

				// status berubah
				if ($task->status !== $post['status'])
				{
					$task_update['status'] = $post['status'];
					$history['remark'] = 'Task status changed to ';
				}

				// owner berubah
				if ($task->user_id !== $post['owner_id'])
				{
					$task_update['owner_id'] = $post['owner_id'];
					$history['remark'] = 'Task owner changed to ';
				}

				// priority berubah
				if ($task->priority !== $post['priority'])
				{
					$task_update['priority'] = $post['priority'];
					$history['remark'] = 'Task priority changed to ';
				}

				// scope berubah
				if ($task->is_scope !== $post['is_scope'])
				{
					$task_update['is_scope'] = $post['is_scope'];
					$history['remark'] = 'Task scope changed to ';
				}

				$this->db->insert('task_histories', $history);
				$this->db->update('tasks', $task_update, ['id' => $id]);
				redirect('/task/view/'.$task->id);
			}
		}

		$this->load->view(_TEMPLATE , [
			'title'			=> 'Task Detail',
			'sview'			=> 'task/view',
			'js'			=> 'task/js_form',
			'task'			=> $task,
			'comments'		=> $this->db
								->select('task_comments.*, user.nama AS user')
								->join('user', 'user.user_id=task_comments.user_id', 'LEFT')
								->where('task_id', $task->id)
								->get('task_comments')->result(),
			'check_menu'	=> ['parent_menu' => '', 'sub_menu' => 'task'],
			'breadcrumb'	=> '<li>
									<i class="ace-icon fa fa-home home-icon"></i>
									<a href="'.base_url().'">Home</a>
								</li>
								<li><a href="'.base_url('task').'">Task List</a></li>
								<li class="active">View</li>'
		]);
	}

	public function add()
	{
		if ($post = $this->input->post('task'))
		{
			$this->load->library('form_validation');

			$this->form_validation->set_rules([
				[
					'field' => 'task[title]',
					'label'	=> 'Title',
					'rules'	=> 'required'
				],[
					'field' => 'task[description]',
					'label'	=> 'Description',
					'rules'	=> 'required'
				],[
					'field' => 'task[priority]',
					'label'	=> 'Priority',
					'rules'	=> 'required'
				],[
					'field' => 'task[owner_id]',
					'label'	=> 'PIC',
					'rules'	=> 'required'
				],
			]);

			if ($this->form_validation->run() == TRUE)
			{
				$post['ctime']		= $post['utime'] = time();
				$post['user_id'] 	= $_SESSION['id'];

				if ($this->upload->do_upload('file'))
				{
					$file = $this->upload->data();
					$post['file'] = '/uploads/'.$file['file_name'];
				}

				$this->db->insert('tasks', $post);

				// add history
				$this->db->insert('task_histories', [
					'utime'		=> time(),
					'user_id'	=> $_SESSION['id'],
					'task_id'	=> $this->db->insert_id(),
					'status'	=> 0,
					'priority'	=> $post['priority'],
					'remark'	=> 'Task created by '
				]);

				redirect('/task');
			}

		}

		$this->load->view(_TEMPLATE, [
			'title' 		=> 'Add Task',
			'sview' 		=> 'task/add',
			'js'			=> 'task/js_form',
			'check_menu'	=> ['parent_menu' => '', 'sub_menu' => 'task'],
			'breadcrumb'	=> '<li>
									<i class="ace-icon fa fa-home home-icon"></i>
									<a href="'.base_url().'">Home</a>
								</li>
								<li><a href="'.base_url('task').'">Task List</a></li>
								<li class="active">Create</li>'
		]);
	}

	public function edit($id)
	{
		$task = $this->db->where(['id' => $id])->get('tasks')->row();

		if ($post = $this->input->post('task'))
		{
			$this->load->library('form_validation');

			$this->form_validation->set_rules([
				[
					'field' => 'task[title]',
					'label'	=> 'Title',
					'rules'	=> 'required'
				],[
					'field' => 'task[description]',
					'label'	=> 'Description',
					'rules'	=> 'required'
				],[
					'field' => 'task[priority]',
					'label'	=> 'Priority',
					'rules'	=> 'required'
				],[
					'field' => 'task[owner_id]',
					'label'	=> 'PIC',
					'rules'	=> 'required'
				],[
					'field' => 'task[is_scope]',
					'label'	=> 'Scope',
					'rules'	=> 'required'
				],
			]);

			if ($this->form_validation->run() == TRUE)
			{
				$post['utime'] = time();

				if ($this->upload->do_upload('file'))
				{
					if ($task->file && file_exists('.'.$task->file)) {
						unlink('.'.$task->file);
					}

					$file = $this->upload->data();
					$post['file'] = '/uploads/'.$file['file_name'];
				}

				$this->db->update('tasks', $post, ['id' => $id]);
				redirect('/task/view/'.$task->id);
			}
		}

		$this->load->view(_TEMPLATE, [
			'title' 		=> 'Edit Task',
			'sview' 		=> 'task/edit',
			'js'			=> 'task/js_form',
			'task' 			=> $task,
			'check_menu'	=> ['parent_menu' => '', 'sub_menu' => 'task'],
			'breadcrumb'	=> '<li>
									<i class="ace-icon fa fa-home home-icon"></i>
									<a href="'.base_url().'">Home</a>
								</li>
								<li><a href="'.base_url('task').'">Task List</a></li>
								<li class="active">Edit</li>'
		]);
	}

	public function delete($id)
	{
		$task = $this->db->where('id', $id)->get('tasks')->row();
		$this->db->delete('tasks', ['id' => $id]);
		$this->db->delete('task_histories', ['task_id' => $id]);

		// hapus file task
		if ($task->file && file_exists('.'.$task->file)) {
			unlink('.'.$task->file);
		}

		$comments = $this->db
					->where('task_id', $task->id)
					->get('task_comments')->result();

		foreach ($comments as $c) {
			// hapus file comments
			if ($c->file && file_exists('.'.$c->file)) {
				unlink('.'.$c->file);
			}
		}

		// hapus semua comments
		$this->db->delete('task_comments', ['task_id' => $id]);

		if ($this->input->is_ajax_request()) {
			echo json_encode(['status' => 1]);
			exit();
		}


		redirect('/task');

	}
}
