<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role extends CI_Controller {

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
		$this->form_validation->set_error_delimiters('', '');
		$validated = $this->form_validation->run();
		if ($validated)
		{
			$params = json_decode(stripslashes($this->input->post('params')));
			$table = $this->Roles->table($params);
			$output['status'] = true;
	        $output['total'] = $table['total'];
	        $output['roles'] = $table['roles'];
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
			$this->Roles->create($role);
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
		$token = authenticate(__CLASS__, __FUNCTION__);
		if ($token == false) return;

		$output = array();
		$output['status'] = false;
		$this->form_validation->set_rules('role', 'role', 'required');
		$this->form_validation->set_rules('resource', 'resource', 'required');
		$this->form_validation->set_error_delimiters('', '');
		$validated = $this->form_validation->run();
		if ($validated)
		{
			$role = $this->input->post('role');
			$resource = $this->input->post('resource');
			$role = $this->Roles->read($role, $resource);
			$output['status'] = true;
	        $output['role'] = $role;
		}
		else
		{
			$output['errors'] = validation_errors();
		}
		$this->load->view('json', array('output' => $output));
	}

	public function update()
	{
		$token = authenticate(__CLASS__, __FUNCTION__);
		if ($token == false) return;

		$output = array();
		$output['status'] = false;
		$this->form_validation->set_rules('role', 'role', 'required');
		$this->form_validation->set_rules('resource', 'resource', 'required');
		$this->form_validation->set_rules('permissions', 'permissions', 'required');
		$this->form_validation->set_error_delimiters('', '');
		$validated = $this->form_validation->run();
		if ($validated)
		{
			$role = $this->input->post('role');
			$resource = $this->input->post('resource');
			$permissions = json_decode(stripslashes($this->input->post('permissions')));
			$role = $this->Roles->update($role, $resource, $permissions);
			$output['status'] = true;
	        $output['role'] = $role;
		}
		else
		{
			$output['errors'] = validation_errors();
		}
		$this->load->view('json', array('output' => $output));
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
			$this->Roles->delete($role);
			$output['status'] = true;
		}
		else
		{
			$output['errors'] = validation_errors();
		}
		$this->load->view('json', array('output' => $output));
	}

}

/* End of file role.php */
/* Location: ./application/controllers/role.php */