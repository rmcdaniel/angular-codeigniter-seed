<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roles extends CI_Model {

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
        if (!in_array($key, array('role'))) return;

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
