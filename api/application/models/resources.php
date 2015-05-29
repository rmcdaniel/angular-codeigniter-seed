<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resources extends CI_Model {

	public function table($params, $role)
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
		if (!in_array($key, array('resource'))) return;

		$acl = new ACL();
		$resources = $acl->resources();
		sort($resources);
		foreach ($resources as $key => $value) {
			$resource = new stdClass();
			$resource->name = $value;
			$resource->permissions = $acl->rolePermissions($role, $resource->name);
			$resources[$key] = $resource;
		}

		$data = array();
		$data['total'] = count($resources);
		$data['resources'] = $resources;
		return $data;
	}
    
	public function create($resource)
	{
		$acl = new ACL();
		$acl->addResource($resource);
	}    
    
	public function delete($resource)
	{
		$acl = new ACL();
		$acl->removeResource($resource);
	}    
    
}

/* End of file resources.php */
/* Location: ./application/models/resources.php */
