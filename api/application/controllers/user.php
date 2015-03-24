<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function login()
	{
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|max_length[256]');
		$this->form_validation->set_rules('password', 'password', 'required|min_length[8]|max_length[256]');
		validate($this, '', '', function($token, $output)
		{
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$id = $this->Users->login($email, $password);
			if ($id != false) {
				$token = array();
				$token['id'] = $id;
				$output['status'] = true;
				$output['email'] = $email;
				$output['token'] = JWT::encode($token, $this->config->item('jwt_key'));
			}
			else
			{
				$output['errors'] = '{"type": "invalid"}';
			}
			return $output;
		});
	}

	public function register()
	{
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|is_unique[users.email]|max_length[256]');
		$this->form_validation->set_rules('password', 'password', 'required|min_length[8]|max_length[256]');
		validate($this, '', '', function($token, $output)
		{
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$this->Users->register($email, $password);
			$output['status'] = true;
			return $output;
		});
	}

	public function permissions()
	{
		$this->form_validation->set_rules('resource', 'resource', 'required');
		validate($this, 'user', '', function($token, $output)
		{
			$resource = $this->input->post('resource');
			$acl = new ACL();
			$permissions = $acl->userPermissions($token->id, $resource);
			$output['status'] = true;
			$output['resource'] = $resource;
			$output['permissions'] = $permissions;
			return $output;
		});
	}

	public function table()
	{
		$this->form_validation->set_rules('params', 'params', 'required');
		validate($this, 'user', 'read', function($token, $output)
		{
			$params = json_decode(stripslashes($this->input->post('params')));
			$table = $this->Users->table($params);
			$output['status'] = true;
	        $output['total'] = $table['total'];
	        $output['users'] = $table['users'];
			return $output;
		});
	}
	
	public function read()
	{
		$this->form_validation->set_rules('id', 'id', 'required');
		validate($this, 'user', 'read', function($token, $output)
		{
			$id = $this->input->post('id');
			$user = $this->Users->read($id);
			$output['status'] = true;
	        $output['user'] = $user;
			return $output;
		});
	}

	public function update()
	{
		$this->form_validation->set_rules('user', 'user', 'required');
		validate($this, 'user', 'update', function($token, $output)
		{
			$user = json_decode(stripslashes($this->input->post('user')));
			$user = $this->Users->update($user);
			$output['status'] = true;
	        $output['user'] = $user;
			return $output;
		});
	}

	public function delete()
	{
		$this->form_validation->set_rules('id', 'id', 'required');
		validate($this, 'user', 'delete', function($token, $output)
		{
			$id = $this->input->post('id');
			$this->Users->delete($id);
			$output['status'] = true;
			return $output;
		});
	}	
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */