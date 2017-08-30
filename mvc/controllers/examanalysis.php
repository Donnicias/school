<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Examanalysis extends Admin_Controller {
/*
| -----------------------------------------------------
| PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
| -----------------------------------------------------
| AUTHOR:			INILABS TEAM
| -----------------------------------------------------
| EMAIL:			info@inilabs.net
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY INILABS IT
| -----------------------------------------------------
| WEBSITE:			http://inilabs.net
| -----------------------------------------------------
*/
	function __construct() {
		parent::__construct();
		$this->load->model("examanalysis_m");
		$this->load->model("classes_m");
		$this->load->model("grade_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('examanalysis', $language);	
	}

	public function index() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin") {
			$this->data['examanalysis'] = $this->examanalysis_m->get_order_by_exam();
			$this->data['exams'] = $this->examanalysis_m->get_exam();
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data['grades'] = $this->grade_m->get_grade();
			$this->data["subview"] = "examanalysis/index";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'examanalysis', 
				'label' => $this->lang->line("examanalysis_name"), 
				'rules' => 'trim|required|xss_clean|max_length[60]|callback_unique_exam'
			), 
			array(
				'field' => 'date', 
				'label' => $this->lang->line("examanalysis_date"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid'
			), 
			array(
				'field' => 'note', 
				'label' => $this->lang->line("examanalysis_note"), 
				'rules' => 'trim|max_length[200]|xss_clean'
			)
		);
		return $rules;
	}

	public function graph() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin") {
			$this->data["subview"] = "examanalysis/graph";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}
}

/* End of file exam.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/examanalysis.php */