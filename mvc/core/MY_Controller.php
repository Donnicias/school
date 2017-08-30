<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
/*
| -----------------------------------------------------
| PRODUCT NAME: 	 SCHOOL MANAGEMENT SYSTEM
| -----------------------------------------------------
| AUTHOR:			
| -----------------------------------------------------
| EMAIL:			
| -----------------------------------------------------
| COPYRIGHT:		
| -----------------------------------------------------
| WEBSITE:			www.niftecs.com
| -----------------------------------------------------
*/
	public $data = array();

	public function __construct() {
		parent::__construct();
		$this->data['errors'] = array();
	}

}

/* End of file MY_Controller.php */
/* Location: .//D/xampp/htdocs/school/mvc/core/MY_Controller.php */