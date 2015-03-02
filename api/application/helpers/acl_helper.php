<?php

abstract class Backend {
    abstract function read($key);
    abstract function write($key, $value);
}

class BackendRedis extends Backend {
    private $redis;

    function __construct($host, $port) {
        $this->redis = new Redis();
	    $this->redis->connect($host, $port);
    }

    function read($key) {
        $value = $this->redis->get($key);
        return empty($value) ? array() : json_decode($value);
    }

    function write($key, $value) {
        $this->redis->set($key, json_encode($value));
    }
}

class BackendCodeIgniter extends Backend {
    private $db;

    function __construct() {
        $ci = &get_instance();
        $this->db = $ci->db;
    }

    function read($key) {
		$this->db->select('value');
		$this->db->from('acl');
		$this->db->where('key', $key);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1)
		{
			$result = $query->result();
			return json_decode($result[0]->value);
		}
		return array();
    }

    function write($key, $value) {
		$this->db->select('value');
		$this->db->from('acl');
		$this->db->where('key', $key);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() == 1)
		{
			$result = $query->result();
			$result[0]->value = json_encode($value);
            $this->db->where('key', $key);
            $this->db->update('acl', $result[0]);
		}
		else
		{
    		$this->db->set('key', $key);
    		$this->db->set('value', json_encode($value));
    		$this->db->insert('acl');
		}
    }
}

class ACL {

    private $backend;

    function __construct() {
        $this->backend = new BackendCodeIgniter();
    }

    function roles() {
        return $this->backend->read("roles");
    }
   
    function addRole($role) {
        $role = is_array($role) ? $role : array($role);
        $this->backend->write("roles", array_unique(array_merge($this->roles(), $role)));
    }

    function removeRole($role) {
        $role = is_array($role) ? $role : array($role);
        $this->backend->write("roles", array_diff($this->roles(), $role));
    }

    function resources() {
        return $this->backend->read("resources");
    }
   
    function addResource($resource) {
        $resource = is_array($resource) ? $resource : array($resource);
        $this->backend->write("resources", array_unique(array_merge($this->resources(), $resource)));
    }

    function removeResource($resource) {
        $resource = is_array($resource) ? $resource : array($resource);
        $this->backend->write("resources", array_diff($this->resources(), $resource));
    }

    function userRoles($user) {
        return $this->backend->read("user_roles::$user");
    }
   
    function addUserRoles($user, $roles) {
        $roles = is_array($roles) ? $roles : array($roles);
        $this->backend->write("user_roles::$user", array_unique(array_merge($this->userRoles($user), $roles)));
    }

    function removeUserRoles($user, $roles) {
        $roles = is_array($roles) ? $roles : array($roles);
        $this->backend->write("user_roles::$user", array_diff($this->userRoles($user), $roles));
    }

    function roleParents($role) {
        return $this->backend->read("role_parents::$role");
    }

    function addRoleParents($role, $parents) {
        $parents = is_array($parents) ? $parents : array($parents);
        $this->backend->write("role_parents::$role", array_unique(array_merge($this->roleParents($role), $parents)));
    }

    function removeRoleParents($role, $parents) {
        $parents = is_array($parents) ? $parents : array($parents);
        $this->backend->write("role_parents::$role", array_diff($this->roleParents($role), $parents));
    }

    function resourceRolePermissions($resource, $role) {
        return $this->backend->read("resource_role_permissions::$resource::$role");        
    }

    function addPermissions($roles, $resources, $permissions) {
        $roles = is_array($roles) ? $roles : array($roles);
        $resources = is_array($resources) ? $resources : array($resources);
        $permissions = is_array($permissions) ? $permissions : array($permissions);
        foreach ($resources as $resource) {
            foreach ($roles as $role) {
                $this->backend->write("resource_role_permissions::$resource::$role", array_unique(array_merge($this->resourceRolePermissions($resource, $role), $permissions)));
            }
        }
    }

    function removePermissions($roles, $resources, $permissions) {
        $roles = is_array($roles) ? $roles : array($roles);
        $resources = is_array($resources) ? $resources : array($resources);
        $permissions = is_array($permissions) ? $permissions : array($permissions);
        foreach ($resources as $resource) {
            foreach ($roles as $role) {
                $this->backend->write("resource_role_permissions::$resource::$role", array_diff($this->resourceRolePermissions($resource, $role), $permissions));
            }
        }
    }

    function rolePermissions($role, $resource, $checked = array()) {
        if (in_array($role, $checked)) return;
        $checked[] = $role;
        $parents = $this->roleParents($role);
        $permissions = array();
        foreach ($parents as $parent) {
            $permissions = array_unique(array_merge($permissions, $this->rolePermissions($parent, $resource, $checked)));
        }
        return array_unique(array_merge($permissions, $this->resourceRolePermissions($resource, $role)));
    }

    function userPermissions($user, $resource) {
        $roles = $this->userRoles($user);
        $permissions = array();
        foreach ($roles as $role) {
            $permissions = array_unique(array_merge($permissions, $this->rolePermissions($role, $resource)));
        }
        return $permissions;
    }

    function isAllowed($user, $resource, $permissions) {
        $permissions = is_array($permissions) ? $permissions : array($permissions);
        $user_permissions = $this->userPermissions($user, $resource);
        $result = array_diff($permissions, $user_permissions);
        return empty($result);
    }
    
    static function registerClass($class) {
        $acl = new ACL();
		$acl->addRole('administrator');
		$acl->addUserRoles(1, 'administrator');
		$acl->addResource($class);
        foreach (get_public_methods($class) as $method) {
            if (($method !== '__construct') && ($method !== 'get_instance')) {
        		$acl->addPermissions('administrator', $class, $method);
            }
        }
    }

}

function isAllowed($user, $resource, $permissions) {
    $acl = new ACL();
    return $acl->isAllowed($user, $resource, $permissions);
}

function get_public_methods($className) {
    $returnArray = array();
    foreach (get_class_methods($className) as $method) {
        $reflect = new ReflectionMethod($className, $method);
        if($reflect->isPublic()) {
            array_push($returnArray, $method);
        }
    }
    return $returnArray;
}

function authenticate($resource, $permissions)
{
    $ci = &get_instance();
	$ci->form_validation->set_rules('token', 'token', 'required');
	$validated = $ci->form_validation->run();
	if ($validated)
	{
		$token = $ci->input->post('token');
	    $token = JWT::decode($token, $ci->config->item('jwt_key'));
		if ($token == false)
		{
			$output['status'] = false;
			$output['errors'] = 'You must login first.';
			$ci->load->view('json', array('output' => $output));
		}
		else
		{
			if (!empty($permissions) && !isAllowed($token->id, $resource, $permissions))
			{
				$token = false;
				$output['status'] = false;
				$output['errors'] = 'You do not have access to that resource.';
				$ci->load->view('json', array('output' => $output));
				return false;
			}
			return $token;
		}
	}
	else
	{
		$output['status'] = false;
		$output['errors'] = validation_errors();
		$ci->load->view('json', array('output' => $output));
		return false;
	}
}
