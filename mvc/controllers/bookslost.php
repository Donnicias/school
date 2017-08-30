<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bookslost extends Admin_Controller {
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
		$this->load->model("bookslost_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('bookslost', $language);	
	}

	public function index() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Librarian" || $usertype == "Student" || $usertype == "Parent" || $usertype == "Teacher") {
			$this->data['books'] = $this->bookslost_m->get_books_lost_per_student();
			$this->data["subview"] = "bookslost/index";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function view() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Librarian") {
			$lid = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$studentID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			if ((int)$lid && (int)$studentID) {
				$this->data['lID'] = $lid;
				$this->data['studentID'] =$studentID;	
				$this->data['books'] = $this->bookslost_m->get_books_lost_by_student($lid);
				$this->data['studentDetails']=$this->bookslost_m->get_student_details($lid);
				$this->data["subview"] = "bookslost/view";
				$this->load->view('_layout_main', $this->data);	
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}
}

/* End of file book.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/book.php */