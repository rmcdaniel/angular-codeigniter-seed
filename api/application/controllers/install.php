<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Install extends CI_Controller {

	public function index()
	{
		$this->load->dbforge();

		// create acl table
		$fields = array(
	        'key' => array(
			     'type' => 'VARCHAR',
			     'constraint' => '255'
			),
	        'value' => array(
			     'type' => 'TEXT'
			)
		);
		$this->dbforge->drop_table('acl');
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('key', true);
		$this->dbforge->create_table('acl');

		// create users table
		$fields = array(
	        'id' => array(
			     'type' => 'INT',
			     'constraint' => 11, 
			     'null' => false,
			     'auto_increment' => true
			),
	        'email' => array(
			     'type' => 'VARCHAR',
			     'constraint' => '255',
			     'null' => false
			),
	        'password' => array(
			     'type' => 'VARCHAR',
			     'constraint' => '255',
			     'null' => false
			)
		);
		$this->dbforge->drop_table('users');
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('users');
		
		// create administrator role and make the first use an administrator
        $acl = new ACL();
		$acl->addRole('administrator');
		$acl->addUserRoles(1, 'administrator');

		// default resources to protect
		$acl->addResource('administrator');
        $acl->addPermissions('administrator', 'administrator', 'read');

		$acl->addResource('user');
        $acl->addPermissions('administrator', 'user', ['create', 'read', 'update', 'delete']);

		$acl->addResource('role');
        $acl->addPermissions('administrator', 'role', ['create', 'read', 'update', 'delete']);

		$acl->addResource('resource');
        $acl->addPermissions('administrator', 'resource', ['create', 'read', 'update', 'delete']);
        
        // custom resources

        // ...
        // ... add your custom resources to protect here
        // ...
	}
	
}

/* End of file install.php */
/* Location: ./application/controllers/install.php */