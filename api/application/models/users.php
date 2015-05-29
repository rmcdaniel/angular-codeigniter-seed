<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Model {

	public function login($email, $password)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('email', $email);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1)
		{
			$result = $query->result();
			if (Password::validate_password($password, $result[0]->password))
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
		$this->db->set('password', Password::create_hash($password));
		$this->db->insert('users');
		return $this->db->insert_id();
	}

	public function table($params)
	{
		$limit = intval($params->count);
		$offset = intval(($params->page - 1) * $params->count);
		$sorting = get_object_vars($params->sorting);
		$direction = reset($sorting);
		$key = key($sorting);
		if ($limit > 100) return;
		if ($limit < 0) return;
		if ($offset < 0) return;
		if (!in_array($direction, array('asc', 'desc'))) return;
		if (!in_array($key, array('id', 'email'))) return;
		$this->db->select('*');
		$this->db->from('users');
		$this->db->order_by($key, $direction);
		$this->db->limit($limit, $offset);
		$query = $this->db->get();
		if ($query->num_rows() >= 1)
		{
			$results = $query->result();
		}
		$users = array();
		foreach ($results as $result)
		{
			$user = new stdClass();
			$user->id = $result->id;
			$user->email = $result->email;
			$users[] = $user;
		}
		$total = $this->db->count_all('users');
        
		$data = array();
		$data['total'] = $total;
		$data['users'] = $users;
		return $data;
	}	

	public function read($id)
	{
		$user = new stdClass();

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('id', $id);
		$this->db->limit(1);

		$query = $this->db->get();
		if ($query->num_rows() == 1)
		{
			$result = $query->result();
			$user->id = $result[0]->id;
			$user->email = $result[0]->email;
			$acl = new ACL();
			$user->roles = $acl->userRoles($user->id);
		}

		return $user;
	}

	public function update($user)
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('id', $user->id);
		$this->db->limit(1);

		$query = $this->db->get();
		if ($query->num_rows() == 1)
		{
			$acl = new ACL();
			$roles = $acl->userRoles($user->id);
			if ($user->roles != $roles)
			{
				$acl->removeUserRoles($user->id, $roles);
				$acl->addUserRoles($user->id, $user->roles);
			}
			$this->db->where('id', $user->id);
			$this->db->update('users', $user);             
            
			$user = $this->read($user->id);
		}

		return $user;
	}	

	public function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('users');
		$this->db->limit(1);
	}
    
}

/* End of file users.php */
/* Location: ./application/models/users.php */