<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resource extends CI_Controller {

	public function create()
	{
		$this->form_validation->set_rules('role', 'role', 'required');
		validate($this, 'resource', 'create', function($token, $output)
		{
			$role = $this->input->post('role');
			$this->Resources->create($role);
			$output['status'] = true;
			return $output;
		});
	}

	public function table()
	{
		$this->form_validation->set_rules('params', 'params', 'required');
		$this->form_validation->set_rules('role', 'role', 'required');
		validate($this, 'resource', 'read', function($token, $output)
		{
			$params = json_decode(stripslashes($this->input->post('params')));
			$role = $this->input->post('role');
			$table = $this->Resources->table($params, $role);
			$output['status'] = true;
	        $output['total'] = $table['total'];
	        $output['resources'] = $table['resources'];
			return $output;
		});
	}

	public function delete()
	{
		$this->form_validation->set_rules('role', 'role', 'required');
		validate($this, 'resource', 'delete', function($token, $output)
		{
			$role = $this->input->post('role');
			$this->Resources->delete($role);
			$output['status'] = true;
			return $output;
		});
	}

}

/* End of file resource.php */
/* Location: ./application/controllers/resource.php */