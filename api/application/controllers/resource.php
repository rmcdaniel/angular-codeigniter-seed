<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resource extends REST_Controller {

	public function table()
	{
		$this->form_validation->set_rules('params', 'params', 'required');
		$this->form_validation->set_rules('role', 'role', 'required');
		return Validation::validate($this, 'resource', 'read', function($token, $output)
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

}

/* End of file resource.php */
/* Location: ./application/controllers/resource.php */