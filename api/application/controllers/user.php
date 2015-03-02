<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
	    parent::__construct();
		ACL::registerClass(__CLASS__);
	}

	public function login()
	{
		$output = array();
		$output['status'] = false;
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|max_length[256]');
		$this->form_validation->set_rules('password', 'password', 'required|min_length[8]|max_length[256]');
		$this->form_validation->set_error_delimiters('', '');
		$validated = $this->form_validation->run();
		if ($validated)
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
				$output['errors'] = 'The email/password combination entered is invalid.';
			}
		}
		else
		{
			$output['errors'] = validation_errors();
		}
		$this->load->view('json', array('output' => $output));
	}
	
	public function register()
	{
		$output = array();
		$output['status'] = false;
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|is_unique[users.email]|max_length[256]');
		$this->form_validation->set_rules('password', 'password', 'required|min_length[8]|max_length[256]');
		$this->form_validation->set_error_delimiters('', '');
		$validated = $this->form_validation->run();
		if ($validated)
		{
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$this->Users->register($email, $password);
			$output['status'] = true;
		}
		else
		{
			$output['errors'] = validation_errors();
		}
		$this->load->view('json', array('output' => $output));
	}
	
	public function information() {
		$token = authenticate(__CLASS__, __FUNCTION__);
		if ($token == false) return;

		$output = array();
		$output['status'] = true;
		$output['message'] = 'Hello!';
		$this->load->view('json', array('output' => $output));
	}

	public function permissions()
	{
		$token = authenticate('', '');
		if ($token == false) return;

		$output = array();
		$output['status'] = false;
		$this->form_validation->set_rules('resource', 'resource', 'required');
		$this->form_validation->set_error_delimiters('', '');
		$validated = $this->form_validation->run();
		if ($validated)
		{
			$resource = $this->input->post('resource');
			$acl = new ACL();
			$permissions = $acl->userPermissions($token->id, $resource);
			$output['status'] = true;
			$output['resource'] = $resource;
			$output['permissions'] = $permissions;
		}
		else
		{
			$output['errors'] = validation_errors();
		}
		$this->load->view('json', array('output' => $output));
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
			$table = $this->Users->table($params);
			$output['status'] = true;
	        $output['total'] = $table['total'];
	        $output['users'] = $table['users'];
		}
		else
		{
			$output['errors'] = validation_errors();
		}
		$this->load->view('json', array('output' => $output));
	}

	public function create()
	{
	}
	
	public function read()
	{
		$token = authenticate(__CLASS__, __FUNCTION__);
		if ($token == false) return;

		$output = array();
		$output['status'] = false;
		$this->form_validation->set_rules('id', 'id', 'required');
		$this->form_validation->set_error_delimiters('', '');
		$validated = $this->form_validation->run();
		if ($validated)
		{
			$id = $this->input->post('id');
			$user = $this->Users->read($id);
			$output['status'] = true;
	        $output['user'] = $user;
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
		$this->form_validation->set_rules('user', 'user', 'required');
		$this->form_validation->set_error_delimiters('', '');
		$validated = $this->form_validation->run();
		if ($validated)
		{
			$user = json_decode(stripslashes($this->input->post('user')));
			$user = $this->Users->update($user);
			$output['status'] = true;
	        $output['user'] = $user;
		}
		else
		{
			$output['errors'] = validation_errors();
		}
		$this->load->view('json', array('output' => $output));
	}

	public function delete()
	{
	}
	
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */