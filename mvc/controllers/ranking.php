<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ranking extends Admin_Controller {
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
		$this->load->model("ranking_m");
		$this->load->model("exam_m");
		$this->load->model("classes_m");
		$this->load->model("subject_m");
		$this->load->model("student_info_m");
		$this->load->model("parentes_info_m");
		$this->load->model("parentes_m");
		$this->load->model("reportforms_m");
		$this->load->model("student_m");
		$this->load->model("section_m");
		$this->load->model("grade_m");
		$this->load->model("term_m");
		$this->load->model("student_averages_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('ranking', $language);	
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("ranking_examID"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("ranking_classesID"),
				'rules' => 'trim|required|xss_clean'
			)
		);
		return $rules;
	}

	public function index() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$this->data['set_exam'] = 0;
			$this->data['set_classes'] = 0;
			$this->data['set_year'] = 0;
			$this->data['set_term'] = 0;
			$this->data['exams'] = $this->exam_m->get_exam();
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data['years']=$this->ranking_m->get_years();
			$this->data['terms']=$this->term_m->get_term();
			if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "ranking/search";
						$this->load->view('_layout_main', $this->data);
					} else {

						$examIDd = $this->input->post('examID');
						$classesIDd = $this->input->post('classesID');
						$year = $this->input->post('year');
						$termID = $this->input->post('termID');
						if ((int)$examIDd && (int)$classesIDd) {
							redirect(base_url("ranking/ranking/".$classesIDd.'/'.$examIDd.'/'.$year.'/'.$termID));
						}
						
					}
				}

			$classesID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$examID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			$year = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(5)));
			$termID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(6)));

			if ((int)$classesID && (int)$examID && (int)$year && (int)$termID) {
				//$this->data["grades"] = $this->grade_m->get_grade();
				$term=$this->reportforms_m->get_term_name($termID);
				$this->data['term_name'] = $term->term_name;
				$this->data['year'] = $year;
				$this->data['students'] = $this->ranking_m->get_student($classesID);
				$this->data['subjects'] = $this->ranking_m->get_subject($classesID);
				$this->data['results']=$this->ranking_m->get_join_all($classesID,$examID);

				if($this->data['results']) {
					$sections = $this->section_m->get_order_by_section(array("classesID" => $classesID));
					$this->data['sections'] = $sections;
					foreach ($sections as $key => $section) {
						$this->data['allsection'][$section->section] = $this->ranking_m->get_join_all_wsection($classesID, $section->sectionID);
					}
				} else {
					$this->data['ranking'] = NULL;
				}
				$this->data['examID'] = $examID;
				$this->data['classesID'] = $classesID;

				$this->data["exam"] = $this->exam_m->get_exam($examID);
				$year = date("Y");
				$this->data["students"] = $this->student_m->get_order_by_student(array("classesID" => $classesID));
				$this->data["subview"] = "ranking/ranking_details";
				$this->load->view('_layout_main', $this->data);
				}else{
					$this->data["subview"] = "ranking/search";
					$this->load->view('_layout_main', $this->data);
				}
				
				
			// }else{ 
			// 	$classesID=$this->input->post('classesID');
			// 	$examID = $this->input->post('examID');
			// 	if($_POST) {
			// 		$this->data['set'] = $classesID;
			// 		$this->data['examID'] = $examID;
			// 		$this->data['classesID'] = $classesID;
			// 		$rules = $this->rules();
			// 		$this->form_validation->set_rules($rules);
			// 		if ($this->form_validation->run() == FALSE) {
			// 		} else {
			// 			$examID = $this->input->post('examID');
			// 			$classesID = $this->input->post('classesID');
			// 			$this->data['set_exam'] = $examID;
			// 			$this->data['set_classes'] = $classesID;

			// 			$exam = $this->exam_m->get_exam($examID);
			// 			$year = date("Y");
			// 			$students = $this->student_m->get_order_by_student(array("classesID" => $classesID));

			// 			$this->data["subview"] = "ranking/ranking/".$classesID.'/'.$examID;
			// 			$this->load->view('_layout_main', $this->data);
			// 		}
			// 	}
			// 	$this->data['exams'] = $this->exam_m->get_exam();
			// 	$this->data['classes'] = $this->classes_m->get_classes();
			// 	$this->data["subview"] = "ranking/search";
			// 	$this->load->view('_layout_main', $this->data);
			// }
		} elseif($usertype == "Student") {
			$student = $this->student_info_m->get_student_info();
			$this->data['ranking'] = $this->student_info_m->get_join_all_ranking_wsection($student->classesID, $student->sectionID);
			$this->data["subview"] = "ranking/index";
			$this->load->view('_layout_main', $this->data);
		} elseif($usertype == "Parent") {
			$username = $this->session->userdata("username");
			$parent = $this->parentes_m->get_single_parentes(array('username' => $username));
			$this->data['students'] = $this->student_m->get_order_by_student(array('parentID' => $parent->parentID));
			$id = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			if((int)$id) {
				$checkstudent = $this->student_m->get_single_student(array('studentID' => $id));
				if(count($checkstudent)) {
					$classesID = $checkstudent->classesID;
					$this->data['set'] = $id;
					$this->data['ranking'] = $this->student_info_m->get_join_all_ranking_wsection($checkstudent->classesID, $checkstudent->sectionID);
					$this->data["subview"] = "ranking/index_parent";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "ranking/search_parent";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function add() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin") {

			$this->data['classes'] = $this->ranking_m->get_classes();
			$this->data['exams'] = $this->ranking_m->get_exam();
			$classesID = $this->input->post("classesID");
			
			if($classesID != 0) {
				$this->data['subjects'] = $this->ranking_m->get_subject($classesID);
				$this->data['sections'] = $this->section_m->get_order_by_section(array("classesID" => $classesID));
			} else {
				$this->data['subjects'] = "empty";
				$this->data['sections'] = "empty";
			}
			$this->data['subjectID'] = 0;
			$this->data['sectionID'] = 0;

			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data["subview"] = "ranking/add";
					$this->load->view('_layout_main', $this->data);			
				} else {
					$array = array(
						"examID" => $this->input->post("examID"),
						"classesID" => $this->input->post("classesID"),
						"sectionID" => $this->input->post("sectionID"),
						"subjectID" => $this->input->post("subjectID"),
						"edate" => date("Y-m-d", strtotime($this->input->post("date"))),
						"examfrom" => $this->input->post("examfrom"),
						"examto" => $this->input->post("examto"),
						"room" => $this->input->post("room"),
						"year" => date("Y")
					);

					$this->ranking_m->insert_examschedule($array);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("ranking/index"));
				}
			} else {
				$this->data["subview"] = "ranking/add";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin") {
			$id = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$url = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			if((int)$id && (int)$url) {
				$this->data['ranking'] = $this->ranking_m->get_examschedule($id);
				if($this->data['ranking']) {
					$classID = $this->data['ranking']->classesID;
					$this->data['subjects'] = $this->ranking_m->get_subject($classID);
					$this->data['classes'] = $this->ranking_m->get_classes();
					$this->data['exams'] = $this->ranking_m->get_exam();
					$this->data['sections'] = $this->section_m->get_order_by_section(array("classesID" => $classID));
					$this->data['set'] = $url;
					if($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "ranking/edit";
							$this->load->view('_layout_main', $this->data);			
						} else {
							$array = array(
								"examID" => $this->input->post("examID"),
								"classesID" => $this->input->post("classesID"),
								"sectionID" => $this->input->post("sectionID"),
								"subjectID" => $this->input->post("subjectID"),
								"edate" => date("Y-m-d", strtotime($this->input->post("date"))),
								"examfrom" => $this->input->post("examfrom"),
								"examto" => $this->input->post("examto"),
								"room" => $this->input->post("room")
							);

							$this->ranking_m->update_examschedule($array, $id);
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("ranking/index/$url"));
						}
					} else {
						$this->data["subview"] = "ranking/edit";
						$this->load->view('_layout_main', $this->data);
					}
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function delete() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin") {
			$id = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$classes = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			if((int)$id && (int)$classes) {
				$this->ranking_m->delete_examschedule($id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("ranking/index/$classes"));
			} else {
				redirect(base_url("ranking/index"));
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}
	public function ranking_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("ranking/index/$classID");
			echo $string;
		} else {
			redirect(base_url("ranking/index"));
		}
	}

	public function student_list() {
		$studentID = $this->input->post('id');
		if((int)$studentID) {
			$string = base_url("ranking/index/$studentID");
			echo $string;
		} else {
			redirect(base_url("ranking/index"));
		}
	}

	function date_valid($date) {
		if(strlen($date) <10) {
			$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
	     	return FALSE;
		} else {
	   		$arr = explode("-", $date);   
	        $dd = $arr[0];            
	        $mm = $arr[1];              
	        $yyyy = $arr[2];
	      	if(checkdate($mm, $dd, $yyyy)) {
	      		return TRUE;
	      	} else {
	      		$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
	     		return FALSE;
	      	}
	    } 
	} 

	function allsubject() {
		if($this->input->post('subjectID') == 0) {
			$this->form_validation->set_message("allsubject", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	function subjectcall() {
		$classID = $this->input->post('id');

		if((int)$classID) {
			$allclasses = $this->ranking_m->get_subject($classID);
			echo "<option value='0'>", $this->lang->line("ranking_select_subject"),"</option>";
			foreach ($allclasses as $value) {
				echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
			}
		} 
	}

	function sectioncall() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$allsection = $this->section_m->get_order_by_section(array("classesID" => $classID));
			echo "<option value='0'>", $this->lang->line("ranking_select_section"),"</option>";
			foreach ($allsection as $value) {
				echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
			}
		} 
	}

	function allexam() {
		$examID = $this->input->post('examID');
		if($examID === '0') {
			$this->form_validation->set_message("allexam", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	function allclasses() {
		$examID = $this->input->post('classesID');
		if($examID === '0') {
			$this->form_validation->set_message("allclasses", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	function allsection() {
		$sectionID = $this->input->post('sectionID');
		if($sectionID === '0') {
			$this->form_validation->set_message("allsection", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	function pastdate_check() {
		$date = strtotime($this->input->post("date"));
		$now_date = strtotime(date("Y-m-d"));
		if($date < $now_date) {
			$this->form_validation->set_message("pastdate_check", "The %s field is past");
	     	return FALSE;
		}
		return TRUE;
	}
}

/* End of file examschedule.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/examschedule.php */