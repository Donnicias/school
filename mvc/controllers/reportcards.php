<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reportcards extends Admin_Controller {
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
		$this->load->model("reportcards_m");
		$this->load->model("classes_m");
		$this->load->model("student_m");
		$this->load->model("mark_m");
		$this->load->model("grade_m");
		$this->load->model("exam_m");
		$this->load->model("subject_m");
		$this->load->model("term_m");
		$this->load->model("ranking_m");
		$this->load->model("user_m");
		$this->load->model("section_m");
		$this->load->model("parentes_m");
		$this->load->model("reportforms_m");
		$this->load->model("student_end_term_averages_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('reportcards', $language);	
	}

	public function index() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$this->data['students'] = array();
			$this->data['set_year'] = 0;
			$this->data['set_classes'] = 0;
			$this->data['set_term'] = 0;
			$classesID = $this->input->post("classesID");
			$this->data['terms']=$this->term_m->get_term();
			if($classesID != 0) {
				if($usertype == "Admin") {
					$this->data['subjects'] = $this->subject_m->get_subject_call($classesID);
					$this->data['sections'] = $this->section_m->get_allsection($classesID);
				} elseif($usertype == "Teacher"){
					$username = $this->session->userdata("username");
					$teacher = $this->user_m->get_username_row("teacher", array("username" => $username));
					$this->data['subjects'] = $this->subject_m->get_order_by_subject(array("classesID" => $classesID, "teacherID" => $teacher->teacherID));
					$this->data['sections'] = $this->section_m->get_allsection($classesID);
				}
			} else {
				$this->data['subjects'] = 0;
				$this->data['sections'] = 0;
			}
			$this->data['exams'] = $this->exam_m->get_exam();
			$this->data['years']=$this->reportcards_m->get_years();
			$this->data['classes'] = $this->classes_m->get_classes();
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data["subview"] = "marksheets/index";
					$this->load->view('_layout_main', $this->data);
				} else {
					$classesID = $this->input->post('classesID');
					$year = $this->input->post('year');
					$termID = $this->input->post('termID');

					$this->data['set_year'] = $year;
					$this->data['set_classes'] = $classesID;
					$this->data['set_term'] = $termID;

					$exam = $this->exam_m->get_exam($examID);
					$subject = $this->subject_m->get_subject($subjectID);
					$year = date("Y");
					$students = $this->student_m->get_order_by_student(array("classesID" => $classesID));
					$sections=$this->section_m->get_allsection($classesID);

					$this->data["subview"] = "reportcards/index";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "reportcards/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function end_term_ranking() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$this->data['set_exam'] = 0;
			$this->data['set_classes'] = 0;
			$this->data['exams'] = $this->exam_m->get_exam();
			$this->data['classes'] = $this->classes_m->get_classes();

			$classesID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$year = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			$termID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(5)));


			if ((int)$classesID && (int)$year  && (int)$termID) {
				//$this->data["grades"] = $this->grade_m->get_grade();
				$term=$this->reportforms_m->get_term_name($termID);
				$examsID=$this->reportforms_m->get_examsID($classesID,$year);
				$this->data['students'] = $this->ranking_m->get_student($classesID);
				$this->data['subjects'] = $this->ranking_m->get_subject($classesID);
				$this->data['results']=$this->ranking_m->get_join_all_end_term($classesID,$examsID->examsID,$termID,$year,$term->term_name);
				if($this->data['results']) {
					$sections = $this->section_m->get_order_by_section(array("classesID" => $classesID));
					$this->data['sections'] = $sections;
					foreach ($sections as $key => $section) {
						$this->data['allsection'][$section->section] = $this->ranking_m->get_join_all_wsection($classesID, $section->sectionID);
					}
				} else {
					$this->data['ranking'] = NULL;
				}
				$this->data['examsID']=$examsID->examsID;
				$this->data['termID'] = $termID;
				$this->data['classesID'] = $classesID;
				$this->data['year'] = $year;
				$this->data['terms'] = $term->term_name;

				$year = date("Y");
				$this->data["students"] = $this->student_m->get_order_by_student(array("classesID" => $classesID));
				$this->data["subview"] = "reportcards/end_term_ranking_details";
				$this->load->view('_layout_main', $this->data);
				}else{
					$this->data["subview"] = "reportcards/index";
					$this->load->view('_layout_main', $this->data);
				}
				
				
		}else{
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	function report_cards(){
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$examID = $this->input->post('examID');
			$classesID = $this->input->post('classesID');
			$sectionID = $this->input->post('sectionID');

			if ((int)$examID && (int)$classesID && (int)$sectionID) {
				$this->data["exams"] = $this->exam_m->get_single_exam(array('examID'=>$examID));
				$this->data["classes"] = $this->classes_m->get_single_class(array('classesID'=>$classesID));
				$this->data["subjects"] = $this->subject_m->get_single_subject(array('subjectID'=>$subjectID));
				$this->data["section"] = $this->section_m->get_single_section(array('sectionID'=>$sectionID));
				$this->data["students"] = $this->student_m->get_order_by_studen_with_section($classesID, $sectionID);

				if($this->data["exams"] && $this->data["classes"]  && $this->data["subjects"] && $this->data["section"]  && $this->data["students"]) {
					$this->load->library('html2pdf');
				    $this->html2pdf->folder('./assets/pdfs/');
				    $file=null;
				    $file_data= $this->section_m->get_single_section(array('sectionID'=>$sectionID));
				    foreach ($file_data as $fdata) {
				    	$file=$fdata->section."_";
				    }
				    $file_data1= $this->subject_m->get_single_subject(array('subjectID'=>$subjectID));
				    foreach ($file_data1 as $fdata1) {
				    	$file.=$fdata1->subject;
				    }
				    $this->html2pdf->filename($file.'_marksheet'."_(".date("Y-m-d h:i:sa").")".'.pdf');
				    $this->html2pdf->paper('a4', 'portrait');
				    $this->data['panel_title'] = $this->lang->line('panel_title');
					$html = $this->load->view('marksheets/marksheet', $this->data, true);
					$this->html2pdf->html($html);
					$this->html2pdf->create();
				} else {
					$this->data["subview"] = "marksheets/hazzard_handler";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "marksheets/hazzard_handler";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "marksheets/hazzard_handler";
			$this->load->view('_layout_main', $this->data);
		}
	}
	
	protected function rules() {
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("reportcards_exam"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_exam'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("reportcards_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_classes'
			),
			array(
				'field' => 'subjectID',
				'label' => $this->lang->line("reportcards_subject"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_subject'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("reportcards_section"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_section'
			)
		);
		return $rules;
	}


	function reportforms_list() {
		$termID = $this->input->post('termID');
		$year = $this->input->post('year');
		$classID = $this->input->post('classID');
		if((int)$classID  && (int)$year && (int)$termID) {
			$string = base_url("reportcards/end_term_ranking/$classID/$year/$termID");
			echo $string;
		} else {
			redirect(base_url("reportcards/index"));
		}
	}

	function print_preview() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$examID = $this->input->post('examID');
			$classesID = $this->input->post('classesID');
			$subjectID = $this->input->post('subjectID');
			$sectionID = $this->input->post('sectionID');

			if ((int)$examID && (int)$classesID && (int)$sectionID) {
				$this->data["exams"] = $this->exam_m->get_single_exam(array('examID'=>$examID));
				$this->data["classes"] = $this->classes_m->get_single_class(array('classesID'=>$classesID));
				$this->data["subjects"] = $this->subject_m->get_single_subject(array('subjectID'=>$subjectID));
				$this->data["section"] = $this->section_m->get_single_section(array('sectionID'=>$sectionID));
				$this->data["students"] = $this->student_m->get_order_by_studen_with_section($classesID, $sectionID);

				if($this->data["exams"] && $this->data["classes"]  && $this->data["subjects"] && $this->data["section"]  && $this->data["students"]) {
					$this->load->library('html2pdf');
				    $this->html2pdf->folder('./assets/pdfs/');
				    $file=null;
				    $file_data= $this->section_m->get_single_section(array('sectionID'=>$sectionID));
				    foreach ($file_data as $fdata) {
				    	$file=$fdata->section."_";
				    }
				    $file_data1= $this->subject_m->get_single_subject(array('subjectID'=>$subjectID));
				    foreach ($file_data1 as $fdata1) {
				    	$file.=$fdata1->subject;
				    }
				    $this->html2pdf->filename($file.'_marksheet'."_(".date("Y-m-d h:i:sa").")".'.pdf');
				    $this->html2pdf->paper('a4', 'portrait');
				    $this->data['panel_title'] = $this->lang->line('panel_title');
					$html = $this->load->view('marksheets/marksheet', $this->data, true);
					$this->html2pdf->html($html);
					$this->html2pdf->create();
				} else {
					$this->data["subview"] = "marksheets/hazzard_handler";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "marksheets/hazzard_handler";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "marksheets/hazzard_handler";
			$this->load->view('_layout_main', $this->data);
		}
	}
}