<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reportforms extends Admin_Controller {
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
		$this->load->model("reportforms_m");
		$this->load->model("examoutofsettings_m");
		$this->load->model("term_m");
		$this->load->model("grade_m");
		$this->load->model("classes_m");
		$this->load->model("exam_m");
		$this->load->model("subject_m");
		$this->load->model("user_m");
		$this->load->model("section_m");
		$this->load->model("parentes_m");
		$this->load->model("ranking_m");
		$this->load->library('fpdf/pdf'); // Load library
		$this->pdf->fontpath = 'fpdf/font/'; // Specify font folder
		$language = $this->session->userdata('lang');
		$this->lang->load('reportforms', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'term_id',
				'label' => $this->lang->line("reportforms_term"),
				'rules' => 'trim|required|xss_clean|callback_check_term|callback_unique_term_id'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("reportforms_classes"),
				'rules' => 'trim|required|xss_clean|callback_check_classes'
			),
			array(
				'field' => 'note',
				'label' => $this->lang->line("reportforms_note"),
				'rules' => 'trim|required|xss_clean'
			)
			,
			array(
				'field' => 'closing_date',
				'label' => $this->lang->line("reportforms_closing_date"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'opening_date',
				'label' => $this->lang->line("reportforms_next_term_opening_date"),
				'rules' => 'trim|required|xss_clean'
			)
		);
		return $rules;
	}


	public function index() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$classID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			if((int)$classID) {
				$this->data['classID'] = $classID;
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["exams"] = $this->exam_m->get_exam();
				$class=$this->ranking_m->get_class($classID);
				foreach ($class as $keyvalue) {
					$this->data['class']=$keyvalue->classes;
				}
				$this->data['reportforms']= $this->reportforms_m->get_settings($classID);
				$this->data["subview"] = "reportforms/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["exams"] = $this->exam_m->get_exam();
				$this->data["subview"] = "reportforms/search";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function add() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" ) {
			$this->data['set_exam'] = 0;
			$this->data['set_classes'] = 0;
			$this->data['set_term'] = 0;
			$classesID = $this->input->post("classesID");
			$this->data['terms']=$this->term_m->get_active_term(array('term_status' =>1));
			$this->data['exams'] = $this->exam_m->get_exam();
			$this->data['classes'] = $this->classes_m->get_classes();
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					
					$this->data["subview"] = "reportforms/add";
					$this->load->view('_layout_main', $this->data);
				} else {
					$classesID = $this->input->post('classesID');
					$term_id = $this->input->post('term_id');	
					$note = $this->input->post('note');
					$closing_date = $this->input->post('closing_date');
					$opening_date = $this->input->post('opening_date');
					
					$examsID=array();
					$j=1;
					foreach ($this->data['exams'] as $value) {
						if ($this->input->post("exam".$j)!='') {
							array_push($examsID,$this->input->post("exam".$j));
						}
						$j++;
					}
					$string="";
					$k=1;
						foreach ($examsID as $value) {
							$string.= $value;
							if ($k<sizeof($examsID)) {
								$string.= ",";
							}
							$k++;
						}
					$this->data['set_exam'] = $examsID;
					$this->data['set_classes'] = $classesID;
					$this->data['set_term']=$term_id;
					$year = date("Y");

					$array = array(
						"classesID" => $classesID,
						"examsID" => $string,
						"termID" => $term_id,
						"year" => $year,
						"closing_date" => $closing_date,
						"next_term_opening_date" => $opening_date,
						"note" => $note
					);
					if ($string !="") {
						if($this->reportforms_m->insert_setting($array)){
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("reportforms/index/$classesID"));
						}else{
								echo "null";
						}
					}else{
						echo '<script>
							alert("You must select atleast one exam!");
						</script>';
					}
						
					
					

					$this->data["subview"] = "reportforms/add";
					$this->load->view('_layout_main', $this->data);
				}
			} else {

				$this->data['subjectsmarks'] = $this->examoutofsettings_m->get_mark(0,0,0,0);
				$this->data["subview"] = "reportforms/add";
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
			$rf_id = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$classID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			$this->data['set_exam'] = 0;
			$this->data['set_classes'] = 0;
			$this->data['set_term'] = 0;
			if((int)$rf_id && (int)$classID) {
				$this->data['term']=$this->reportforms_m->get_unique_term($rf_id);
				$this->data['terms']=$this->term_m->get_active_term(array('term_status' =>1));
					$this->data['classID'] = $classID;
					$this->data['classes'] = $this->student_m->get_classes();
					$this->data["exams"] = $this->exam_m->get_exam();
				
				$this->data['term_id'] = $rf_id;
				if($this->data['term']) {
					if($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "reportforms/edit";
							$this->load->view('_layout_main', $this->data);
						} else {

							$classesID = $this->input->post('classesID');
							$term_id = $this->input->post('term_id');	
							$note = $this->input->post('note');
							$closing_date = $this->input->post('closing_date');
							$opening_date = $this->input->post('opening_date');
							
							$examsID=array();
							$j=1;
							foreach ($this->data['exams'] as $value) {
								if ($this->input->post("exam".$j)!='') {
									array_push($examsID,$this->input->post("exam".$j));
								}
								$j++;
							}
							$string="";
							$k=1;
								foreach ($examsID as $value) {
									$string.= $value;
									if ($k<sizeof($examsID)) {
										$string.= ",";
									}
									$k++;
								}
							$this->data['set_exam'] = $examsID;
							$this->data['set_classes'] = $classesID;
							$this->data['set_term']=$term_id;
							$year = date("Y");

							$array = array(
								"classesID" => $classesID,
								"examsID" => $string,
								"termID" => $term_id,
								"year" => $year,
								"closing_date" => $closing_date,
								"next_term_opening_date" => $opening_date,
								"note" => $note
							);
							if ($string !="") {
								if($this->reportforms_m->update_setting($array, $rf_id)){
									$this->session->set_flashdata('success', $this->lang->line('menu_success'));
									redirect(base_url("reportforms/index/$classID"));
								}else{
										echo "null";
								}
							}else{
								echo '<script>
									alert("You must select atleast one exam!");
								</script>';
								$this->data["subview"] = "reportforms/edit";
								$this->load->view('_layout_main', $this->data);
							}
							
						}
					} else {
						$this->data["subview"] = "reportforms/edit";
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
			$rf_id = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$classID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			if ((int)$rf_id && (int)$rf_id) {
				$this->data['term'] = $this->reportforms_m->get_unique_term($rf_id);
				if($this->data['term']) {
					$this->reportforms_m->delete_setting($rf_id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("reportforms/index/$classID"));
				} else {
					redirect(base_url("reportforms/index"));
				}
			} else {
				redirect(base_url("reportforms/index/$classID"));
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	function reportforms_list() {
		$year = $this->input->post('year');
		$classID = $this->input->post('classID');
		if((int)$classID && (int)$year) {
			$string = base_url("reportforms/index/$classID");
			echo $string;
		} else {
			redirect(base_url("reportforms/index"));
		}
	}

	function year_call() {
			$usertype = $this->session->userdata("usertype");
			$classID = $this->input->post('classID');
			if((int)$classID) {
				$years =  $this->reportforms_m->get_years($classID);
				echo "<option value='0'>", $this->lang->line("reportforms_select_year"),"</option>";
				foreach ($years as $yr) {
					echo "<option id='year' value=\"$yr->year\">",$yr->year,"</option>";
				}
			}
	}
	function check_classes() {
		if($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("check_classes", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}
	function check_term() {
		if($this->input->post('term_id') == 0) {
			$this->form_validation->set_message("check_term", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}
	function check_exam() {
		if($this->input->post('examID') == 0) {
			$this->form_validation->set_message("check_exam", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function unique_term_id() {
		$classID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
		$yr=date("Y");
		if((int)$classID) {
			$term=$this->reportforms_m->get_unique_term_id($classID,$yr,$this->input->post('term_id'));
			if(count($term)) {
				$this->form_validation->set_message("unique_term_id", "%s already set");
				return FALSE;
			}
			return TRUE;
		} else {
			$term=$this->reportforms_m->get_unique_term_id($this->input->post('classesID'),$yr,$this->input->post('term_id'));

			if(count($term)) {
				$this->form_validation->set_message("unique_term_id", "%s already set");
				return FALSE;
			}
			return TRUE;
		}
	}

}

/* End of file class.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/class.php */
