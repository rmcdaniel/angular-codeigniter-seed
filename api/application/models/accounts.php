<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accounts extends CI_Model {

	public function login($email, $password)
	{
		$this->db->select('*');
		$this->db->from('accounts');
		$this->db->where('email', $email);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1)
		{
			$result = $query->result();
			if (validate_password($password, $result[0]->password))
			{
				return $result[0]->id;
			}
			return false;
		}
		return false;
	}
    
	public function register($email, $password)
	{
		$this->db->set('email',  $email);
		$this->db->set('password', create_hash($password));
		$this->db->insert('accounts');
	}
}

/* End of file accounts.php */
/* Location: ./application/models/accounts.php */