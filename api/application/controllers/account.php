<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

	private function authenticate()
	{
		$this->form_validation->set_rules('token', 'token', 'required');
		$validated = $this->form_validation->run();
		if ($validated)
		{
			$token = $this->input->post('token');
			return JWT::decode($token, $this->config->item('jwt_key'));
		}
		else
		{
			$output['status'] = false;
			$output['errors'] = validation_errors();
			$this->load->view('json', array('output' => $output));
			return false;
		}
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
			$id = $this->Accounts->login($email, $password);
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
		$this->form_validation->set_rules('email', 'email', 'required|valid_email|is_unique[accounts.email]|max_length[256]');
		$this->form_validation->set_rules('password', 'password', 'required|min_length[8]|max_length[256]');
		$this->form_validation->set_error_delimiters('', '');
		$validated = $this->form_validation->run();
		if ($validated)
		{
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$this->Accounts->register($email, $password);
			$output['status'] = true;
		}
		else
		{
			$output['errors'] = validation_errors();
		}
		$this->load->view('json', array('output' => $output));
	}
	
	public function information() {
		$output = array();
		$output['status'] = false;
		$token = $this->authenticate();
		if ($token !== false)
		{
			$output['status'] = true;
			$output['message'] = 'Hello!';
		}
		else
		{
			$output['errors'] = 'You must login first.';
		}
		$this->load->view('json', array('output' => $output));
	}
	
}

/* End of file account.php */
/* Location: ./application/controllers/account.php */
