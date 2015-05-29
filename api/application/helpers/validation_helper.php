<?php

class Validation {

	public static function validate($context, $class, $function, $callback) {
		$output = array();
		$output['status'] = false;
		$token = false;
		if (!empty($class)) {
			$token = ACL::authenticate($class, $function);
			if ($token == false) {
				if (defined('PHPUNIT_TEST')) {
					return json_encode(array('output' => $output));
				} else {
					$context->load->view('json', array('output' => $output));
				}
			}
		}
		$context->form_validation->set_error_delimiters('', '');
		$validated = $context->form_validation->run();
		if ($validated)
		{
			$output = $callback($token, $output);
		}
		else
		{
			$output['errors'] = validation_errors();
		}
		if (array_key_exists('errors', $output)) {
			$errors = explode("\n", $output['errors']);
			foreach ($errors as $key => $error) {
				$errors[$key] = json_decode($error);
			}
			$output['errors'] = $errors;
		}
		if (defined('PHPUNIT_TEST')) {
			return json_encode(array('output' => $output));
		} else {
			$context->load->view('json', array('output' => $output));
		}
	}

}