<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resource extends CI_Controller {

	public function __construct()
	{
	    parent::__construct();
		ACL::registerClass(__CLASS__);
	}

	public function table()
	{
		$token = authenticate(__CLASS__, 'read');
		if ($token == false) return;

		$output = array();
		$output['status'] = false;
		$this->form_validation->set_rules('params', 'params', 'required');
		$this->form_validation->set_rules('role', 'role', 'required');
		$this->form_validation->set_error_delimiters('', '');
		$validated = $this->form_validation->run();
		if ($validated)
		{
			$params = json_decode(stripslashes($this->input->post('params')));
			$role = $this->input->post('role');
			$table = $this->Resources->table($params, $role);
			$output['status'] = true;
	        $output['total'] = $table['total'];
	        $output['resources'] = $table['resources'];
		}
		else
		{
			$output['errors'] = validation_errors();
		}
		$this->load->view('json', array('output' => $output));
	}

	public function create()
	{
		$token = authenticate(__CLASS__, __FUNCTION__);
		if ($token == false) return;

		$output = array();
		$output['status'] = false;
		$this->form_validation->set_rules('role', 'role', 'required');
		$this->form_validation->set_error_delimiters('', '');
		$validated = $this->form_validation->run();
		if ($validated)
		{
			$role = $this->input->post('role');
			$this->Resources->create($role);
			$output['status'] = true;
		}
		else
		{
			$output['errors'] = validation_errors();
		}
		$this->load->view('json', array('output' => $output));
	}

	public function read()
	{
	}

	public function update()
	{
	}

	public function delete()
	{
		$token = authenticate(__CLASS__, __FUNCTION__);
		if ($token == false) return;

		$output = array();
		$output['status'] = false;
		$this->form_validation->set_rules('role', 'role', 'required');
		$this->form_validation->set_error_delimiters('', '');
		$validated = $this->form_validation->run();
		if ($validated)
		{
			$role = $this->input->post('role');
			$this->Resources->delete($role);
			$output['status'] = true;
		}
		else
		{
			$output['errors'] = validation_errors();
		}
		$this->load->view('json', array('output' => $output));
	}

}

/* End of file resource.php */
/* Location: ./application/controllers/resource.php */