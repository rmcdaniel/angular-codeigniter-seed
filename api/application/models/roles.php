<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roles extends CI_Model {

	public function table($params)
	{
		$acl = new ACL();
		$roles = $acl->roles();

		$data = array();
		$data['total'] = count($roles);
		$data['roles'] = $roles;
		return $data;
	}
    
	public function create($role)
	{
		$acl = new ACL();
		$acl->addRole($role);
	}    

	public function read($role, $resource)
	{
		$acl = new ACL();
		return $acl->rolePermissions($role, $resource);
	}

	public function update($role, $resource, $permissions)
	{
		$acl = new ACL();
		$acl->removePermissions($role, $resource, $acl->rolePermissions($role, $resource));
		$acl->addPermissions($role, $resource, $permissions);
	}
    
	public function delete($role)
	{
		$acl = new ACL();
		$acl->removeRole($role);
	}    
    
}

/* End of file roles.php */
/* Location: ./application/models/roles.php */
