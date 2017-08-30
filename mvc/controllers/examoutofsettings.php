<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Examoutofsettings extends Admin_Controller {
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
		$this->load->model("student_m");
		$this->load->model("examoutofsettings_m");
		$this->load->model("term_m");
		$this->load->model("grade_m");
		$this->load->model("classes_m");
		$this->load->model("exam_m");
		$this->load->model("subject_m");
		$this->load->model("reportforms_m");
		$this->load->model("user_m");
		$this->load->model("reportcards_m");
		$this->load->model("section_m");
		$this->load->model("parentes_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('examoutofsettings', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("examoutofsettings_exam"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_exam'
			),
			array(
				'field' => 'outof',
				'label' => $this->lang->line("examoutofsettings_outof"),
				'rules' => 'trim|required|xss_clean|max_length[3]|min_length[2]|numeric'
			),
			array(
				'field' => 'term_id',
				'label' => $this->lang->line("examoutofsettings_term"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_exam'
			),
			array(
				'field' => 'year',
				'label' => $this->lang->line("examoutofsettings_year"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_year'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("examoutofsettings_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_classes'
			),
			array(
				'field' => 'subjectID',
				'label' => $this->lang->line("examoutofsettings_subject"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_subject'
			)
		);
		return $rules;
	}

	protected function rules2() {
		$rules2 = array(
			array(
				'field' => 'subjectMark',
				'label' => $this->lang->line("subjectMark"),
				'rules' => 'trim|required|numeric|xss_clean|max_length[11]'
			)
		);
		return $rules2;
	}


	public function index() {
		$usertype = $this->session->userdata("usertype");
		if(($usertype == "Admin") || ($usertype == "Teacher")){
			$classID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$examID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			$term_id = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(5)));
			$year = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(6)));
			$this->data['set_term'] = 0;
			$this->data['set_year'] = 0;
			$this->data['years']=$this->reportcards_m->get_years();
			$this->data['terms']=$this->term_m->get_term();
			if((int)$classID && (int)$examID && (int)$term_id && (int)$year){
				$this->data['set'] = $classID;
				$this->data['examID']=$examID;
				$this->data['term_id']=$term_id;
				$this->data['year']=$year;
				$termm=$this->reportforms_m->get_term_name($term_id);
				$term_name=$termm->term_name;
				$this->data["term_name"]=$term_name;
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["exams"] = $this->exam_m->get_exam();
				$this->data['subjects'] = $this->examoutofsettings_m->get_mark($classID,$examID,$term_name,$year);
				$this->data["subview"] = "examoutofsettings/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["exams"] = $this->exam_m->get_exam();
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["subview"] = "examoutofsettings/search";
				$this->load->view('_layout_main', $this->data);
			}
		}else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
		
	}

	public function edit() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin") {
			$this->data['set_subject'] = 0;
			$this->data['set_classes'] = 0;
			$this->data['set_term_name'] = 0;
			$this->data['set_subjectMark']=0;
			$classesID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$subjectID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			$examID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(5)));
			$term_id = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(6)));
			$year = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(7)));
			if((int)$classesID && (int)$subjectID && (int)$examID && (int)$term_id && (int)$year) {
				$termm=$this->reportforms_m->get_term_name($term_id);
				$term_name=$termm->term_name;
				$this->data['year']=$year;
				$this->data['term_name']=$term_name;
				$this->data['subject_data'] = $this->examoutofsettings_m->get_subject_data($classesID,$subjectID,$examID,$term_name,$year);
				if($_POST) {
					$rules2 = $this->rules2();
					$this->form_validation->set_rules($rules2);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "examoutofsettings/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
						$term_name=$this->input->post('term_name');
						$class = $this->input->post('class');
						$subject = $this->input->post('subject');
						$subjectMark=$this->input->post('subjectMark');
						$array = array(
										"classesID" => $classesID,
										"subjectID" => $subjectID,
										"term_name" => $term_name
									);
						$subjectMark=array("out_of"=>$subjectMark);
						$state=$this->examoutofsettings_m->update_out_of_mark($array,$subjectMark);
						if($state=true){
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("examoutofsettings/index/$classesID/$examID/$term_id/$year"));
						}else{
							echo "error occurred";
						}
					}
				}else{
					$this->data["subview"] = "examoutofsettings/edit";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["subview"] = "examoutofsettings/search";
				$this->load->view('_layout_main', $this->data);
			}
		}else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
		
	}
	public function delete() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin") {
			$classesID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$subjectID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			$examID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(5)));
			$term_id = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(6)));
			$year = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(7)));

			if ((int)$classesID && (int)$subjectID && (int)$examID && (int)$term_id && (int)$year) {
				$termm=$this->reportforms_m->get_term_name($term_id);
				$term_name=$termm->term_name;
				$this->data['subject_data'] = $this->examoutofsettings_m->get_subject_data($classesID,$subjectID,$examID,$term_name,$year);
				if($this->data['subject_data']) {
						$class = $this->input->post('class');
						$subject = $this->input->post('subject');
						$array = array(
										"classesID" => $classesID,
										"subjectID" => $subjectID,
										"term_name" => $term_name
									);
					$state=$this->examoutofsettings_m->delete_out_of_mark($array);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("examoutofsettings/index/$classesID/$examID/$term_id/$year"));
				} else {
					//redirect(base_url("examoutofsettings/search"));
					$this->data["exams"] = $this->exam_m->get_exam();
					$this->data['classes'] = $this->student_m->get_classes();
					$this->data["subview"] = "examoutofsettings/search";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				//redirect(base_url("examoutofsettings/index/$classesID"));
				$this->data["exams"] = $this->exam_m->get_exam();
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["subview"] = "examoutofsettings/search";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}
	public function add() {
		$usertype = $this->session->userdata("usertype");
		if(($usertype == "Admin") || ($usertype == "Teacher")) {
			$this->data['students'] = array();
			$this->data['set_exam'] = 0;
			$this->data['set_year'] = 0;
			$this->data['set_classes'] = 0;
			$this->data['set_subject'] = 0;
			$this->data['set_term'] = 0;
			$this->data['set_out_of'] = 0;
			$this->data['years']=$this->reportcards_m->get_years();
			$classesID = $this->input->post("classesID");
			if($classesID != 0) {
					$this->data['subjects'] = $this->subject_m->get_subject_call($classesID);
					
			} else {
				$this->data['subjects'] = 0;
			}
			$this->data['terms']=$this->term_m->get_active_term(array('term_status' =>1));
			$this->data['exams'] = $this->exam_m->get_exam();
			$this->data['classes'] = $this->classes_m->get_classes();
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data['subjectsmarks']=null;
					$this->data["subview"] = "examoutofsettings/add";
					$this->load->view('_layout_main', $this->data);
				} else {
					$examID = $this->input->post('examID');
					$classesID = $this->input->post('classesID');
					$subjectID = $this->input->post('subjectID');
					$term_id = $this->input->post('term_id');
					$year = $this->input->post('year');
					$out_of = $this->input->post('outof');
					$this->data['set_exam'] = $examID;
					$this->data['set_year'] = $year;
					$this->data['set_classes'] = $classesID;
					$this->data['set_subject'] = $subjectID;
					$this->data['set_term']=$term_id;
					$this->data['set_out_of']=$out_of;
					$this->data['out_of']=$out_of;
					$termm=$this->reportforms_m->get_term_name($term_id);
					$term_name=$termm->term_name;
					$exam = $this->exam_m->get_exam($examID);
					$subject = $this->subject_m->get_subject($subjectID);
					$term=$this->term_m->get_active_term(array('term_status' =>1));
					$students = $this->student_m->get_order_by_student(array("classesID" => $classesID));

						if(count($students)) {
							foreach ($students as $student) {
								$studentID = $student->studentID;
								$in_student = $this->examoutofsettings_m->get_order_by_mark(array("term_name"=>$term_name,"year"=>$year,"examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID, "studentID" => $studentID));
								if(!count($in_student)) {
									$array = array(
										"examID" => $examID,
										"exam" => $exam->exam,
										"studentID" => $studentID,
										"classesID" => $classesID,
										"subjectID" => $subjectID,
										"subject" => $subject->subject,
										"out_of" => $out_of,
										"term_name" => $term_name,
										"year" => $year
									);
									$this->examoutofsettings_m->insert_mark($array);
									$this->session->set_flashdata('success', $this->lang->line('menu_success'));
								}
							}
							$this->data['students'] = $students;
							$all_student = $this->examoutofsettings_m->get_order_by_mark(array("term_name"=>$term_name,"year"=>$year,"examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID));
							$this->data['marks'] = $all_student;
						}
					$this->data['term_id']=$term_id;
					$this->data['examID']=$examID;
					$this->data['year']=$year;
					$this->data['subjectsmarks']= $this->examoutofsettings_m->get_subject_data($classesID,$subjectID,$examID,$term_name,$year);
					$this->data["subview"] = "examoutofsettings/add";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data['subjectsmarks']= $this->examoutofsettings_m->get_subject_data(0,0,0,0,0);
				$this->data["subview"] = "examoutofsettings/add";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	function examoutofsettings_send() {
		$examID = $this->input->post("examID");
		$classesID = $this->input->post("classesID");
		$subjectID = $this->input->post("subjectID");
		$inputs = $this->input->post("inputs");
		$out_of = $this->input->post("out_of");
		$exploade = explode("$" , $inputs);
		$exploade_out_of = explode("$" , $out_of);
		$ex_array = array();
		$ex_array_out_of = array();
		foreach ($exploade as $key => $value) {
			if($value == "") {
				break;
			} else {
				$ar_exp = explode(":", $value);
				$ex_array[$ar_exp[0]] = $ar_exp[1];
			}
		}

		foreach ($exploade_out_of as $key => $value) {
			if($value == "") {
				break;
			} else {
				$ar_exp_out_of = explode(":", $value);
				$ex_array_out_of[$ar_exp_out_of[0]] = $ar_exp_out_of[1];
			}
		}

		$students = $this->student_m->get_order_by_student(array("classesID" => $classesID));
		foreach ($students as $student) {
			foreach ($ex_array as $key => $mark) {
				if($key == $student->studentID) {
					$array = array("mark" => $mark);
					$this->examoutofsettings_m->update_examoutofsettings_classes($array, array("examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID, "studentID" => $student->studentID));
					break;
				}
			}
			foreach ($ex_array_out_of as $key => $outof) {
				if($key == $student->studentID) {
					$array = array("out_of" => $outof);
					$this->examoutofsettings_m->update_examoutofsettings_classes($array, array("examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID, "studentID" => $student->studentID));

					break;
				}
			}
		}
		$this->session->set_flashdata('success', $this->lang->line('menu_success'));
	}

	function examoutofsettings_list() {
		$classID = $this->input->post('classesID');
		$examID = $this->input->post('examID');
		$term_id = $this->input->post('term_id');
		$year = $this->input->post('year');
		if((int)$classID && (int)$examID) {
			$string = base_url("examoutofsettings/index/$classID/$examID/$term_id/$year");
			echo $string;
		} else {
			redirect(base_url("examoutofsettings/index"));
		}
	}

	function student_list() {
		$studentID = $this->input->post('id');
		if((int)$studentID) {
			$string = base_url("examoutofsettings/index/$studentID");
			echo $string;
		} else {
			redirect(base_url("examoutofsettings/index"));
		}
	}

	function subjectcall() {
		$usertype = $this->session->userdata("usertype");
		$id = $this->input->post('id');
		if((int)$id) {
			if($usertype == "Admin") {
				$allsubject = $this->subject_m->get_order_by_subject(array("classesID" => $id));
				echo "<option value='0'>", $this->lang->line("examoutofsettings_select_subject"),"</option>";
				foreach ($allsubject as $value) {
					echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
				}
			} elseif($usertype == "Teacher") {
				$username = $this->session->userdata("username");
				$teacher = $this->user_m->get_username_row("teacher", array("username" => $username));
				$allsubject = $this->subject_m->get_order_by_subject(array("classesID" => $id, "teacherID" => $teacher->teacherID));
				echo "<option value='0'>", $this->lang->line("examoutofsettings_select_subject"),"</option>";
				foreach ($allsubject as $value) {
					echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
				}
			}
		}
	}

	function outofmarkcall() {
		$usertype = $this->session->userdata("usertype");
		$sid = $this->input->post('sid');
		$cid = $this->input->post('cid');
		$eid = $this->input->post('eid');
		$tid = $this->input->post('tid');
		$year = $this->input->post('year');
		if((int)$sid && (int)$cid && (int)$eid && (int)$tid && (int)$year){
			$term=$this->reportforms_m->get_term_name($tid);
			$term_name=$term->term_name;
				$allsubject = $this->data['subject_data'] = $this->examoutofsettings_m->get_subject_data($cid,$sid,$eid,$term_name,$year);
				foreach ($allsubject as $allsubject) {
					echo '
						<input type="text" class="form-control" id="outof" name="outof" value=" '
							.$allsubject->subjectMark.'">';
		}
	}
}

	function check_exam() {
		if($this->input->post('examID') == 0) {
			$this->form_validation->set_message("check_exam", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	function check_year() {
		if($this->input->post('year') == 0) {
			$this->form_validation->set_message("check_year", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	function check_classes() {
		if($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("check_classes", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	function check_subject() {
		if($this->input->post('subjectID') == 0) {
			$this->form_validation->set_message("check_subject", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}
}

/* End of file class.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/class.php */
