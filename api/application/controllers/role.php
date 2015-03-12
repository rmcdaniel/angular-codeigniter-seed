<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Role extends CI_Controller {

	public function create()
	{
		$this->form_validation->set_rules('role', 'role', 'required');
		validate($this, 'role', 'create', function($token, $output)
		{
			$role = $this->input->post('role');
			$this->Roles->create($role);
			$output['status'] = true;
			return $output;
		});
	}

	public function table()
	{
		$this->form_validation->set_rules('params', 'params', 'required');
		validate($this, 'role', 'read', function($token, $output)
		{
			$params = json_decode(stripslashes($this->input->post('params')));
			$table = $this->Roles->table($params);
			$output['status'] = true;
	        $output['total'] = $table['total'];
	        $output['roles'] = $table['roles'];
			return $output;
		});
	}
	public function read()
	{
		$this->form_validation->set_rules('role', 'role', 'required');
		$this->form_validation->set_rules('resource', 'resource', 'required');
		validate($this, 'role', 'read', function($token, $output)
		{
			$role = $this->input->post('role');
			$resource = $this->input->post('resource');
			$role = $this->Roles->read($role, $resource);
			$output['status'] = true;
	        $output['role'] = $role;
			return $output;
		});
	}

	public function update()
	{
		$this->form_validation->set_rules('role', 'role', 'required');
		$this->form_validation->set_rules('resource', 'resource', 'required');
		$this->form_validation->set_rules('permissions', 'permissions', 'required');
		validate($this, 'role', 'update', function($token, $output)
		{
			$role = $this->input->post('role');
			$resource = $this->input->post('resource');
			$permissions = json_decode(stripslashes($this->input->post('permissions')));
			$role = $this->Roles->update($role, $resource, $permissions);
			$output['status'] = true;
	        $output['role'] = $role;
			return $output;
		});
	}

	public function delete()
	{
		$this->form_validation->set_rules('role', 'role', 'required');
		validate($this, 'role', 'delete', function($token, $output)
		{
			$role = $this->input->post('role');
			$this->Roles->delete($role);
			$output['status'] = true;
			return $output;
		});
	}

}

/* End of file role.php */
/* Location: ./application/controllers/role.php */