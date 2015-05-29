<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CLI extends CI_Controller {

	public function index()
	{
	}

	public function install()
	{
		if (!$this->input->is_cli_request()) return;
		
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
		
		// default resources to protect
		$acl = new ACL();
		$acl->addResource('administrator');
		$acl->addResource('user');
		$acl->addResource('role');
		$acl->addResource('resource');

		// create administrator role and grant all access
		$acl->addRole('administrator');
		$acl->addPermissions('administrator', 'administrator', 'read');
		$acl->addPermissions('administrator', 'user', ['create', 'read', 'update', 'delete']);
		$acl->addPermissions('administrator', 'role', ['create', 'read', 'update', 'delete']);
		$acl->addPermissions('administrator', 'resource', ['create', 'read', 'update', 'delete']);
        
		// custom resources

		// ...
		// ... add your custom resources to protect here
		// ...
        
		if (!defined('PHPUNIT_TEST'))
			echo "installed\r\n";
	}

	public function add($type, $email, $password)
	{
		if (!$this->input->is_cli_request()) return;

		if ($type == 'user')
		{
			$this->Users->register($email, $password);
	
			if (!defined('PHPUNIT_TEST'))
				echo "user added\r\n";
		}
		else if ($type == 'administrator')
		{
			$id = $this->Users->register($email, $password);
			$acl = new ACL();
			$acl->addUserRoles($id, 'administrator');

			if (!defined('PHPUNIT_TEST'))
				echo "administrator added\r\n";
		}
	}
	
}

/* End of file cli.php */
/* Location: ./application/controllers/cli.php */