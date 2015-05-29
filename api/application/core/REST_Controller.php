<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class REST_Controller extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	private function __load()
	{
		$className = get_class($this);
		$modelName = $className . 's';
		$this->model = $this->$modelName;
		$this->resource = strtolower($className);
		$this->resources = strtolower($modelName);
	}

	public function table()
	{
		$this->__load();
		$this->form_validation->set_rules('params', 'params', 'required');
		return Validation::validate($this, $this->resource, 'read', function($token, $output)
		{
			$params = json_decode(stripslashes($this->input->post('params')));
			$table = $this->model->table($params);
			$output['status'] = true;
			$output['total'] = $table['total'];
			$output[$this->resources] = $table[$this->resources];
			return $output;
		});
	}

	public function create()
	{
		$this->__load();
		$this->form_validation->set_rules($this->resource, $this->resource, 'required');
		return Validation::validate($this, $this->resource, 'create', function($token, $output)
		{
			$resource = $this->input->post($this->resource);
			$this->model->create($resource);
			$output['status'] = true;
			return $output;
		});
	}

	public function read()
	{
		$this->__load();
		$this->form_validation->set_rules('id', 'id', 'required');
		return Validation::validate($this, $this->resource, 'read', function($token, $output)
		{
			$id = $this->input->post('id');
			$resource = $this->model->read($id);
			$output['status'] = true;
			$output[$this->resource] = $resource;
			return $output;
		});
	}

	public function update()
	{
		$this->__load();
		$this->form_validation->set_rules($this->resource, $this->resource, 'required');
		return Validation::validate($this, $this->resource, 'update', function($token, $output)
		{
			$resource = json_decode(stripslashes($this->input->post($this->resource)));
			$resource = $this->model->update($resource);
			$output['status'] = true;
			$output[$this->resource] = $resource;
			return $output;
		});
	}

	public function delete()
	{
		$this->__load();
		$this->form_validation->set_rules('id', 'id', 'required');
		return Validation::validate($this, $this->resource, 'delete', function($token, $output)
		{
			$id = $this->input->post('id');
			$this->model->delete($id);
			$output['status'] = true;
			return $output;
		});
	}	

}
