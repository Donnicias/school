<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reportcardss extends Admin_Controller {
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
		$this->load->model("reportcardss_m");
		$this->load->model("reportcards_m");
		$this->load->model('reportforms_m');
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
		$language = $this->session->userdata('lang');
		$this->lang->load('reportcardss', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("transcripts_exam"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_exam'
			),
			array(
				'field' => 'outof',
				'label' => $this->lang->line("transcripts_outof"),
				'rules' => 'trim|required|xss_clean|max_length[3]|min_length[2]|numeric'
			),
			array(
				'field' => 'term_id',
				'label' => $this->lang->line("transcripts_term"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_exam'
			),
			array(
				'field' => 'examID',
				'label' => $this->lang->line("transcripts_term"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("transcripts_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_classes'
			),
			array(
				'field' => 'subjectID',
				'label' => $this->lang->line("transcripts_subject"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_subject'
			)
		);
		return $rules;
	}


	public function index() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$classID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$termID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			$year = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(5)));
			$this->data['set_term'] = 0;
			$this->data['set_year'] = 0;
			$this->data['years']=$this->reportcards_m->get_years();
			$this->data['termss']=$this->term_m->get_term();
			$term=$this->reportforms_m->get_term_name($termID);
			$examID=$this->reportforms_m->get_examsID($classID,$year);
			if((int)$classID && (int)$termID && (int)$year) {
				$this->data['set'] = $classID;
				$this->data['classID'] = $classID;
				//$this->data["grades"] = $this->grade_m->get_grade();
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["exams"] = $this->exam_m->get_exam();
				$this->data['terms'] = $term->term_name;
				$this->data['termID'] = $termID;
				$this->data['examsID']=$examID->examsID;
				$this->data['year'] = $year;
				$class=$this->ranking_m->get_class($classID);
				foreach ($class as $keyvalue) {
					$this->data['class']=$keyvalue->classes;
				}
				if ($classID==13 || $classID==14) {
					$this->data['student']=$this->ranking_m->gets_sstudents_end_term($classID,$examID->examsID,$year,$term->term_name);
				}else{
				 $this->data['student']= $this->ranking_m->get_students_average_end_term($examID->examsID,$classID,$year,$term->term_name);
				}
				$this->data["subview"] = "reportcardss/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["exams"] = $this->exam_m->get_exam();
				$this->data["subview"] = "reportcardss/search";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function stream() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$sectionID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$classID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			$examID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(5)));
			$this->data['set_term'] = 0;
			$this->data['set_year'] = 0;
			$this->data['years']=$this->reportcards_m->get_years();
			$this->data['terms']=$this->term_m->get_term();
			if((int)$sectionID && (int)$classID && (int)$examID) {
				$this->data['set'] = $classID;
				$this->data['sectionID'] = $sectionID;
				$this->data['classID'] = $classID;
				$this->data['examID'] = $examID;
				//$this->data["grades"] = $this->grade_m->get_grade();
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["exams"] = $this->exam_m->get_exam();
				$section=$this->ranking_m->get_section($sectionID);
				foreach ($section as $keyvalue) {
					$this->data['section']=$keyvalue->section;
				}
				if ($classID==13 || $classID==14) {
					$this->data['student']=$this->ranking_m->gets_sstudentss($sectionID,$classID,$examID);
				}else{
				 $this->data['student']= $this->ranking_m->gets_students_average($sectionID,$examID,$classID);
				}
				$this->data["subview"] = "reportcardss/stream";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["exams"] = $this->exam_m->get_exam();
				$this->data["subview"] = "reportcardss/filter";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function filter() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$this->data['students'] = array();
			$this->data['set_exam'] = 0;
			$this->data['set_classes'] = 0;
			$this->data['set_subject'] = 0;
			$this->data['set_term'] = 0;
			$this->data['set_out_of'] = 0;
			$classesID = $this->input->post("classesID");
			if($classesID != 0) {
				if($usertype == "Admin") {
					$this->data['subjects'] = $this->subject_m->get_subject_call($classesID);
				} elseif($usertype == "Teacher") {
					$username = $this->session->userdata("username");
					$teacher = $this->user_m->get_username_row("teacher", array("username" => $username));
					$this->data['subjects'] = $this->subject_m->get_order_by_subject(array("classesID" => $classesID, "teacherID" => $teacher->teacherID));
				}
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
					$this->data["subview"] = "transcripts/add";
					$this->load->view('_layout_main', $this->data);
				} else {
					$examID = $this->input->post('examID');
					$classesID = $this->input->post('classesID');
					$subjectID = $this->input->post('subjectID');
					$term_id = $this->input->post('term_id');
					$out_of = $this->input->post('outof');
					$this->data['set_exam'] = $examID;
					$this->data['set_classes'] = $classesID;
					$this->data['set_subject'] = $subjectID;
					$this->data['set_term']=$term_id;
					$this->data['set_out_of']=$out_of;
					$this->data['out_of']=$out_of;
					$exam = $this->exam_m->get_exam($examID);
					$subject = $this->subject_m->get_subject($subjectID);
					$term=$this->term_m->get_active_term(array('term_status' =>1));
					$year = date("Y");
					$students = $this->student_m->get_order_by_student(array("classesID" => $classesID));

						if(count($students)) {
							foreach ($students as $student) {
								$studentID = $student->studentID;
								$in_student = $this->reportcardss_m->get_order_by_transcripts(array("examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID, "studentID" => $studentID));
								if(!count($in_student)) {
									$array = array(
										"examID" => $examID,
										"exam" => $exam->exam,
										"studentID" => $studentID,
										"classesID" => $classesID,
										"subjectID" => $subjectID,
										"subject" => $subject->subject,
										"out_of" => $out_of,
										"term_name" => $term_id,
										"year" => $year
									);
									$this->reportcardss_m->insert_transcripts($array);
								}
							}
							$this->data['students'] = $students;
							$all_student = $this->reportcardss_m->get_order_by_transcripts(array("examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID));
							$this->data['transcriptss'] = $all_student;
						}

					$this->data["subview"] = "reportcardss/filter";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "reportcardss/filter";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	function transcripts_send() {
		$examID = $this->input->post("examID");
		$classesID = $this->input->post("classesID");
		$subjectID = $this->input->post("subjectID");
		$inputs = $this->input->post("inputs");
		$out_of = $this->input->post("out_of");
		$percentage = $this->input->post("percentage");
		$exploade = explode("$" , $inputs);
		$exploade_out_of = explode("$" , $out_of);
		$exploade_percentage = explode("$" , $percentage);
		$ex_array = array();
		$ex_array_out_of = array();
		$ex_array_percentage = array();
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

		foreach ($exploade_percentage as $key => $value) {
			if($value == "") {
				break;
			} else {
				$ar_exp_percentage = explode(":", $value);
				$ex_array_percentage[$ar_exp_percentage[0]] = $ar_exp_percentage[1];
			}
		}

		$students = $this->student_m->get_order_by_student(array("classesID" => $classesID));
		foreach ($students as $student) {
			foreach ($ex_array as $key => $transcripts) {
				if($key == $student->studentID) {
					$array = array("transcripts" => $transcripts);
					$this->reportcardss_m->update_transcripts_classes($array, array("examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID, "studentID" => $student->studentID));
					break;
				}
			}
			foreach ($ex_array_out_of as $key => $outof) {
				if($key == $student->studentID) {
					$array = array("out_of" => $outof);
					$this->reportcardss_m->update_transcripts_classes($array, array("examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID, "studentID" => $student->studentID));
					break;
				}
			}
			foreach ($ex_array_percentage as $key => $percent) {
				if($key == $student->studentID) {
					$array = array("percentage" => $percent);
					$this->reportcardss_m->update_transcripts_classes($array, array("examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID, "studentID" => $student->studentID));
					break;
				}
			}
		}
		echo $this->lang->line('reportcardss_success');
	}

	public function view() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$classID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$examID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			$studentID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(5)));
			$position = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(6)));
			$totalpoints = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(7)));
			//$meangrade = htmlentities(mysql_real_escape_string($this->uri->segment(8)));
			$totalstudents = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(8)));

			if ((int)$classID && (int)$examID && (int)$studentID && (int)$position) {

				$this->data["student"] = $this->student_m->get_student($studentID);
				$termss= $this->exam_m->get_term();
				$examss = $this->exam_m->get_exam_name($examID);
				foreach ($termss as $value) {
					$term=$value->term_name;
				}
				foreach ($examss as $value) {
					$exam=$value->exam;
				}
				$this->data["exam"]=$exam;
				$this->data["term"]=$term;

				$this->data["classes"] = $this->student_m->get_class($classID);
				if($this->data["student"] && $this->data["classes"]) {

					$this->data['set'] = $examID;
					$this->data['examID'] = $examID;
					$this->data['classID'] = $classID;
					$this->data['studentID'] = $studentID;
					$this->data['position'] = $position;
					$this->data['totalpoints'] = $totalpoints;
					//$this->data['meangrade'] = $meangrade;
					$this->data['totalstudents'] = $totalstudents;

					$this->data["exams"] = $this->exam_m->get_exam();
					$this->data["grades"] = $this->grade_m->get_grade(array('classesID'=>1,'subjectID'=>1));
					$this->data["transcriptss"] = $this->reportcardss_m->get_order_by_transcripts_with_highest_mark($classID,$studentID);
					$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);

					// dump($this->data["transcriptss"]);
					// die;


					$this->data["subview"] = "reportcardss/view";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} elseif($usertype == "Student") {
			$username = $this->session->userdata("username");
			$student = "";
			if($usertype == "Student") {
				$student = $this->user_m->get_username_row("student", array("username" => $username));
			} elseif($usertype == "Parent") {
				$user = $this->user_m->get_username_row("parent", array("username" => $username));
				$student = $this->student_m->get_student($user->studentID);
			}
			$this->data["student"] = $this->student_m->get_student($student->studentID);
			$this->data["classes"] = $this->student_m->get_class($student->classesID);
			if($this->data["student"] && $this->data["classes"]) {
				$this->data["exams"] = $this->exam_m->get_exam();
				$this->data["grades"] = $this->grade_m->get_grade();
				$this->data["transcriptss"] = $this->reportcardss_m->get_order_by_transcripts(array("studentID" => $student->studentID, "classesID" => $student->classesID));

				$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
				$this->data["subview"] = "reportcardss/view";
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
	public function view1() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$classID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$examID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			$studentID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(5)));
			$position = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(6)));
			$totalpoints = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(7)));
			//$meangrade = htmlentities(mysql_real_escape_string($this->uri->segment(8)));
			$totalstudents = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(8)));
			$term = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(9)));
			$year = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(10)));

			if ((int)$classID && !empty($examID) && (int)$studentID && (int)$position  && (int)$totalpoints && (int)$totalstudents && (int)$year  && !empty($term)) {
				
				$term_name=preg_replace('/[ -]+/', ' ', trim($term));
				$examID=preg_replace('/[ -]+/', ',', trim($examID));
				$student = $this->student_m->get_student($studentID);
				$examss = $this->exam_m->get_exam_name($examID);
				$examinations=$this->reportforms_m->get_exams($examID);
				foreach ($examss as $value) {
					$exam=$value->exam;
				}
				$exam=$exam;
				$class=$this->ranking_m->get_class($classID);
				foreach ($class as $keyvalue) {
					$class=$keyvalue->classes;
				}
				$array_key=array(
					"term_name"=>$term_name
					);
				$termID=$this->reportcardss_m->get_term_id($array_key);
				$arrayy=array(
					"classesID"=>$classID,
					"termID"=>$termID->term_id,
					"year"=>$year
					);
				$term_dates=$this->reportcardss_m->get_dates($arrayy);
				if($student) {
					$this->pdf->AddPage();
					$this->pdf->Image(base_url('uploads/images/header.png'),10,5,190);
				    $this->pdf->SetFont('Arial','B',13);
				    $this->pdf->ln(5);
				    $this->pdf->Cell(0,6,"KIARENI E.L.C.K MIXED SECONDARY SCHOOL",0,1,'C');
				    $this->pdf->SetFont('Arial','B',12);
				    $this->pdf->Cell(0,6,"P.O. BOX 1467 - 40200, KISII",0,1,'C');
				    $this->pdf->Cell(0,6,"www.kiareni.sc.ke",0,1,'C');
				    $this->pdf->ln(5);
				    $this->pdf->Cell(0,6,"Official Report Form",0,1,'C');
				    $this->pdf->Cell(0,6,$term_name."/".$year,0,1,'C');
				    $this->pdf->ln(6);
				    $this->pdf->Image(base_url('uploads/images/site.png'),10,18,30);
				    $this->pdf->Image(base_url('uploads/images/'.$student->photo),170,18,30);
				    $this->pdf->Cell(70,6,ucwords(strtolower($student->name)),0,0,'L');
				    $this->pdf->Cell(50,6,"CLass ".$student->section,0,0,'C');
				    $this->pdf->Cell(0,6,"Adm No  ".$student->roll,0,1,'R');
				    $oposition=$this->ranking_m->get_overall_position_end_term($examID,$classID,$student->roll,$year,$term_name);
					$cposition=$this->ranking_m->get_class_position_end_term($examID,$classID,$student->sectionID,$student->roll,$year,$term_name);
				    //print_r($cposition);
				    $transcriptss= $this->reportcardss_m->get_order_by_transcripts_with_highest_mark_end_term($classID,$studentID,$examID,$term_name,$year);
					$header = array('Code', 'Subject Name', 'Score', 'Remarks','Initials');
					//$this->vFancyTable($header,$classID,$transcriptss,$cposition,$oposition,$totalpoints);
					$header = array('Code', 'Subject Name');
					    foreach ($examinations as $exam) {
					    	array_push($header,ucwords(strtolower($exam->exam)));
					    }
					    array_push($header,'Average');
						array_push($header,'Remarks');
						array_push($header,'Initials');

						
						$this->vFancyTable($header,$student,$classID,$transcriptss,$cposition,$oposition,$examinations,$examID,$year,$student->sectionID,$student->roll,$totalpoints);
						$this->pdf->ln(5);
						$this->pdf->SetFont('','B');
					    $this->pdf->Cell(0,5,"CALENDAR",0,1,'L');
					    $this->pdf->SetFont('','');
					    $this->pdf->Cell(0,5,"Closing date: ".$term_dates->closing_date,0,0,'L');
					    $this->pdf->Cell(0,5,"Opening Date: ".$term_dates->next_term_opening_date,0,1,'R');
					    $this->pdf->ln(1);
					    $this->pdf->SetFont('Courier','B');
					    $this->pdf->Cell(0,4,"Date Generated: ".date("d/m/Y"),0,0,'C');
					    $this->pdf->Image(base_url('uploads/images/footer.png'),10,283,190);
					$this->pdf->Output();
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		}else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}
	function vFancyTable($header,$student,$classID,$transcriptss,$cposition,$oposition,$examinations,$examIDs,$year,$sectionID,$roll,$totalpoints){
				    $this->pdf->SetFillColor(155,155,155);
				    $this->pdf->SetTextColor(255);
				    $this->pdf->SetDrawColor(192,192,192);
				    $this->pdf->SetLineWidth(.3);
				    $this->pdf->SetFont('Arial','B',10);
				    // Header
				    $w = array();
				    $student_overall_grade="";
				    $f3and4grade="";
				    if(sizeof($examinations)>2){
				    	array_push($w, 10);
				    	array_push($w, 34);
				    	foreach ($examinations as $key) {
					    	array_push($w, 18);
					    }
					    array_push($w, 25);
					    array_push($w, 45);
					    array_push($w, 25);
				    }else{
				    	array_push($w, 15);
				    	array_push($w, 40);
				    	foreach ($examinations as $key) {
					    	array_push($w, 20);
					    }
					    array_push($w, 25);
					    array_push($w, 47);
					    array_push($w, 25);
				    }
				    
				    
				    for($i=0;$i<count($header);$i++)
				        $this->pdf->Cell($w[$i],7,$header[$i],1,0,'L',true);
				    $this->pdf->Ln();
				    // Color and font restoration
				    $this->pdf->SetFillColor(224,235,255);
				    $this->pdf->SetTextColor(0);
				    // Data
				    $fill = false;
				    $ttmark=false;
				    $i=0;
				    $j=0;
				    $this->pdf->SetFont('Arial','',10);

					if ($classID==13 || $classID==14) {
					      $totalstudents=count($student);
						if ($totalstudents>0) {
							foreach ($transcriptss as $transcripts) {
								$this->pdf->SetFont('','');
						      	$this->pdf->Cell($w[0],6,$transcripts->sc,'LR',0,'L',$fill);
						        $this->pdf->Cell($w[1],6,$transcripts->subject,'LR',0,'L',$fill);
						        $grades=$this->grade_m->get_grade(array("subjectID"=>$transcripts->subjectID));
						        foreach ($examinations as $value) {
						        	$subject_score=$this->ranking_m->get_subjects_marks_end_term($transcripts->studentID,$value->examID,$transcripts->subjectID,$transcripts->year,$transcripts->term_name);
						        	if (count($subject_score) !=0) {
							        	foreach ($subject_score as $keyvalue) {
						        			if(count($grades)) {
								              foreach ($grades as $grade) {
								                  if($grade->gradefrom <= round($keyvalue->mark,0) && $grade->gradeupto >= round($keyvalue->mark,0)) {
								                  	$this->pdf->Cell($w[3],6,round($keyvalue->mark,0).' '.$grade->grade,'LR',0,'L',$fill);
								                  }
								              }
									        }
							        	}
						        	}else{
						        			$this->pdf->Cell($w[3],6,'...','LR',0,'L',$fill);
						        	}
						        }
						        if (sizeof($examinations)>2) {
						        	$gradees=$this->grade_m->get_grade(array("subjectID"=>$transcripts->subjectID));
									if(count($gradees)) {
						              foreach ($gradees as $grade) {
						                  if($grade->gradefrom <= round($transcripts->mark,0) && $grade->gradeupto >= round($transcripts->mark,0)) {
						                  	$this->pdf->Cell($w[5],6,round($transcripts->mark,0).' '.$grade->grade,'LR',0,'L',$fill);
						                  	$this->pdf->SetFont('','I');
						                      if ($transcripts->sc ==102) {
						                         $this->pdf->Cell($w[6],6,$this->lang->line('reportcardss_'.$grade->note),'LR',0,'L',$fill);
						                      }else{
						                          $this->pdf->Cell($w[6],6,$grade->note,'LR',0,'L',$fill);
						                      }
						                  }
						              }
						          }

						        $this->pdf->Cell($w[7],6,$transcripts->initials,'LR',0,'L',$fill);
								}else{
									if(count($grades)) {
						              foreach ($grades as $grade) {
						                  if($grade->gradefrom <= $transcripts->mark && $grade->gradeupto >= $transcripts->mark) {
						                  	$this->pdf->Cell($w[5],6,round($transcripts->mark,0).' '.$grade->grade,'LR',0,'L',$fill);
						                  	$this->pdf->SetFont('','I');
						                      if ($transcripts->sc ==102) {
						                         $this->pdf->Cell($w[6],6,$this->lang->line('reportcardss_'.$grade->note),'LR',0,'L',$fill);
						                      }else{
						                          $this->pdf->Cell($w[6],6,$grade->note,'LR',0,'L',$fill);
						                      }
						                  }
						              }
						          }
						        $this->pdf->Cell($w[7],6,$transcripts->initials,'LR',0,'L',$fill);
								}
						          
						        $this->pdf->SetFont('','B');
						        $this->pdf->Ln();
						        $fill = !$fill;
						      }
					           $kgrades = $this->grade_m->get_grade(array("classesID"=>1,"subjectID"=>1));
					              if(count($kgrades)) {
					                  foreach ($kgrades as $grade) {
					                      if($grade->point <= round($oposition['tp']/7,0) && $grade->point >= round($oposition['tp']/7,0)) {
					                              $ssgrade=$grade->grade;
					                              $student_overall_grade=$grade->grade;
					                      }
					                  }
					              }
					      	$this->pdf->SetFont('Arial','B',9);
					      	$this->pdf->Cell(62,6,strtoupper("End term results summary (S.E. ".count($transcriptss).')'),0,0,'C');
							$this->pdf->Cell(65,6,"T.Points: ".$oposition['tp'],0,0,'R');
							$this->pdf->Cell(35,6,"M.Grade: ".$ssgrade,0,0,'R');
							if ($classID==13 || $classID==14) {
								$this->pdf->Cell(35,6,"Position: ".$oposition['position'].'/'.$oposition['totalstudents'],0,1,'L');
							}else{
								$this->pdf->Cell(25,6,"O.Pos: ".$oposition['position'].'/'.$oposition['totalstudents'],0,0,'L');
								$this->pdf->Cell(25,6,"C.Pos: ".$cposition['position'].'/'.$cposition['totalstudents'],0,1,'L');
							}
						}else{
							$this->pdf->Cell(40,6,"No Data Found!",0,0,'L');
						} 
					  }else{
					  	$totalmarks=0;
					      foreach ($transcriptss as $transcripts) {
					      	$totalstudents=count($student);
					      	$this->pdf->SetFont('Arial','',9);
					      	$this->pdf->Cell($w[0],6,$transcripts->sc,'LR',0,'L',$fill);
					        $this->pdf->Cell($w[1],6,$transcripts->subject,'LR',0,'L',$fill);
					      	$grades=$this->grade_m->get_grade(array("subjectID"=>$transcripts->subjectID));
					      	foreach ($examinations as $value) {
					        	$subject_score=$this->ranking_m->get_subjects_marks_end_term($transcripts->studentID,$value->examID,$transcripts->subjectID,$transcripts->year,$transcripts->term_name);
					        	if (count($subject_score) !=0) {
						        	foreach ($subject_score as $keyvalue) {
						        			if(count($grades)) {
								              foreach ($grades as $grade) {
								                  if($grade->gradefrom <= round($keyvalue->mark,0) && $grade->gradeupto >= round($keyvalue->mark,0)) {
								                  	$this->pdf->Cell($w[3],6,round($keyvalue->mark,0).' '.$grade->grade,'LR',0,'L',$fill);
								                  }
								              }
									        }
						        		
						        		
						        	}
					        	}else{
					        		$this->pdf->Cell($w[3],6,'...','LR',0,'L',$fill);
					        	}
					        }
					          
				              	if (sizeof($examinations)>2) {
					              		if(count($grades)) {
							              foreach ($grades as $grade) {
							              	if (is_numeric($transcripts->mark)) {
							              		if($grade->gradefrom <= round($transcripts->mark,0) && $grade->gradeupto >= round($transcripts->mark,0)) {
								                  	$this->pdf->Cell($w[5],6,round($transcripts->mark,0).' '.$grade->grade,'LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                      if ($transcripts->sc ==102) {
								                         $this->pdf->Cell($w[6],6,$this->lang->line('reportcardss_'.$grade->note),'LR',0,'L',$fill);
								                      }else{
								                          $this->pdf->Cell($w[6],6,$grade->note,'LR',0,'L',$fill);
								                      }
								                  }
							              	}else{
							              		if ($transcripts->mark==="X") {
							              			$this->pdf->Cell($w[5],6,'X','LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[6],6,'Not Done','LR',0,'L',$fill);
							              		}else if($transcripts->mark==="x"){
							              			$this->pdf->Cell($w[5],6,'X','LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[6],6,'Not Done','LR',0,'L',$fill);
							              		}else if($transcripts->mark==="n"){
							              			$this->pdf->Cell($w[5],6,' ','LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[6],6,'Not Taking','LR',0,'L',$fill);
							              		}else if($transcripts->mark===""){
							              			$this->pdf->Cell($w[5],6,'-','LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[6],6,'Missing Mark','LR',0,'L',$fill);
							              		}else if($transcripts->mark==="N"){
							              			$this->pdf->Cell($w[5],6,'','LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[6],6,'Not Taking','LR',0,'L',$fill);
							              		}else{
							              			$this->pdf->Cell($w[5],6,$transcripts->mark,'LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[6],6,'Undefined','LR',0,'L',$fill);
							              		}
							              	}
							            }
							        }
						            $this->pdf->Cell($w[7],6,$transcripts->initials,'LR',0,'L',$fill);
				              	}else{
				              		foreach ($grades as $grade) {
					              		if (is_numeric($transcripts->mark)) {
					              			if($grade->gradefrom <= round($transcripts->mark,0) && $grade->gradeupto >= round($transcripts->mark,0)) {
							                  	$this->pdf->Cell($w[4],6,round($transcripts->mark,0).' '.$grade->grade,'LR',0,'L',$fill);
							                  	$this->pdf->SetFont('','I');
							                      if ($transcripts->sc ==102) {
							                         $this->pdf->Cell($w[5],6,$this->lang->line('reportcardss_'.$grade->note),'LR',0,'L',$fill);
							                      }else{
							                          $this->pdf->Cell($w[5],6,$grade->note,'LR',0,'L',$fill);
							                      }
							                  }
						              	}else{
						              		if ($transcripts->mark==="X") {
						              			$this->pdf->Cell($w[4],6,'X','LR',0,'L',$fill);
							                  	$this->pdf->SetFont('','I');
							                    $this->pdf->Cell($w[5],6,'Not Done','LR',0,'L',$fill);
						              		}else if($transcripts->mark==="x"){
						              			$this->pdf->Cell($w[4],6,'X','LR',0,'L',$fill);
							                  	$this->pdf->SetFont('','I');
							                    $this->pdf->Cell($w[5],6,'Not Done','LR',0,'L',$fill);
						              		}else if($transcripts->mark==="n"){
						              			$this->pdf->Cell($w[4],6,' ','LR',0,'L',$fill);
							                  	$this->pdf->SetFont('','I');
							                    $this->pdf->Cell($w[5],6,'Not Taking','LR',0,'L',$fill);
						              		}else if($transcripts->mark===""){
						              			$this->pdf->Cell($w[4],6,'-','LR',0,'L',$fill);
							                  	$this->pdf->SetFont('','I');
							                    $this->pdf->Cell($w[5],6,'Missing Mark','LR',0,'L',$fill);
						              		}else if($transcripts->mark==="N"){
						              			$this->pdf->Cell($w[4],6,'','LR',0,'L',$fill);
							                  	$this->pdf->SetFont('','I');
							                    $this->pdf->Cell($w[5],6,'Not Taking','LR',0,'L',$fill);
						              		}else{
						              			$this->pdf->Cell($w[4],6,$transcripts->mark,'LR',0,'L',$fill);
							                  	$this->pdf->SetFont('','I');
							                    $this->pdf->Cell($w[5],6,'Undefined','LR',0,'L',$fill);
						              		}
						              	}
							        }
					              	$this->pdf->Cell($w[6],6,$transcripts->initials,'LR',0,'L',$fill);
				              	}
					        $this->pdf->SetFont('','B');
					        $this->pdf->Ln();
					        $fill = !$fill;
					        
		                  $totalmarks+=round($transcripts->mark,0);
		                  //echo "totalpoints:".$totalmarks."+(".round($transcripts->mark,0).")<br/>";
					  }

				    // Closing line
				    //$this->pdf->Cell(array_sum($w),0,'','T');
				    $avmarks=round(($totalmarks/count($transcriptss)),0);
				           $kgrades = $this->grade_m->get_overal_performance_grading();
				              if(count($kgrades)) {
				                  foreach ($kgrades as $grade) {
				                      if($grade->gradeFrom <= $avmarks && $grade->gradeTo >= $avmarks) {
				                              $ssgrade=$grade->grade;
				                              $student_overall_grade=$grade->grade;
				                      }
				                  }
				              }
				      	//$this->pdf->SetFont('Arial','',9);
				        $this->pdf->Cell(96,6,"END TERM RESULTS SUMMARY",0,0,'L');
						$this->pdf->Cell(25,6,"T.Marks: ".round($totalmarks,0),0,0,'L');
						$this->pdf->Cell(25,6,"M.Grade: ".$ssgrade,0,0,'L');
							$this->pdf->Cell(25,6,"O.Pos: ".$oposition['position'].'/'.$oposition['totalstudents'],0,0,'L');
							$this->pdf->Cell(25,6,"C.Pos: ".$cposition['position'].'/'.$cposition['totalstudents'],0,1,'L');
						
					  }
				    // Closing line
				    $this->pdf->Cell(array_sum($w),0,'','T');
				    $this->pdf->ln(5);
				    $this->pdf->Cell(0,5,"KCPE ANALYSIS",0,1,'L');
				    $this->pdf->Cell(35,5,"Mark",1,0,'L');
				    $this->pdf->Cell(25,5,"Grade",1,0,'L');
				    $this->pdf->Cell(35,5,"Position",1,0,'L');
				    $this->pdf->Cell(62,5,"Former Primary",1,0,'L');
				    $this->pdf->Cell(35,5,"KCPE Year",1,1,'L');
				    $this->pdf->SetFont('','');
				    $kcpe_data=$this->student_m->get_kcpe_analysis_details($classID,$transcripts->studentID);
				    $this->pdf->Cell(35,5,$kcpe_data['mark'].'/500',1,0,'L');
				    $this->pdf->Cell(25,5,$kcpe_data['grade'],1,0,'L');
				    $this->pdf->Cell(35,5,$kcpe_data['position'].'/'.$kcpe_data['totalstudents'],1,0,'L');
				    $this->pdf->Cell(62,5,"",1,0,'L');
				    $this->pdf->Cell(35,5,$kcpe_data['kcpe_year'],1,1,'L');
				    $this->pdf->ln(5);
				    $this->pdf->SetFont('','B');
				    $this->pdf->Cell(0,5,"STUDENT PROGRESSIVE ANALYSIS",0,1,'L');
				    $terms=$this->reportcardss_m->get_terms();
				    $tno=count($terms);
				    $daviations=array();
				    $m=1;
				    $n=1;
				    $o=1;
				    $p=1;
				    $q=1;
				    foreach ($terms as $value) {
				    	$this->pdf->Cell(30,5,ucwords(strtolower($value->term_name)),1,0,'L');
				    	if ($m==$tno) {
				    		$this->pdf->Cell(60,5,"",0,0,'L');
				    		$this->pdf->Cell(42,5,"Deviation",1,1,'L');
				    	}
				    	$m++;
				    }
				    $this->pdf->SetFont('','');


				    $examzID1=NULL;
				    $examzID2=NULL;
				    $examzID3=NULL;
//term 1
				    	$array1 = array('termID' =>4,
				    	"year"=>$year,
				    	"classesID"=>$classID);
				    	$examz1=$this->reportcardss_m->get_exams($array1);
				    	// if (!count($examz)) {
				    	// 	echo " Please make sure the reportcard settings for the class, term and year are correct! Thank you.";
				    	// 	exit;
				    	// }
				    	foreach ($examz1 as $keyvalue) {
				    		$examzID1=$keyvalue->examsID;
				    	}
				    	if ($examzID1 == NULL) {
				    		$examzID1="1,2,3";
				    	}
				    	$opos1=$this->ranking_m->get_overall_position_end_term($examzID1,$classID,$roll,$year,"Term 1");
				    	$endtermmark1=$this->reportcardss_m->get_total_marks($classID,$transcripts->studentID,$examzID1,"Term 1",$year);
				    	$cpos1=$this->ranking_m->get_class_position_end_term($examzID1,$classID,$sectionID,$roll,$year,"Term 1");


//Term 2

				    	$array2 = array('termID' =>5,
				    	"year"=>$year,
				    	"classesID"=>$classID);
				    	$examz2=$this->reportcardss_m->get_exams($array2);
				    	// if (!count($examz)) {
				    	// 	echo " Please make sure the reportcard settings for the class, term and year are correct! Thank you.";
				    	// 	exit;
				    	// }
				    	foreach ($examz2 as $keyvalue) {
				    		$examzID2=$keyvalue->examsID;
				    	}
				    	if ($examzID2 == NULL) {
				    		$examzID2="1,2,3";
				    	}
				    	$opos2=$this->ranking_m->get_overall_position_end_term($examzID2,$classID,$roll,$year,"Term 2");
				    	$endtermmark2=$this->reportcardss_m->get_total_marks($classID,$transcripts->studentID,$examzID2,"Term 2",$year);
				    	$cpos2=$this->ranking_m->get_class_position_end_term($examzID2,$classID,$sectionID,$roll,$year,"Term 2");


//Term 3

				    	$array3 = array('termID' =>6,
				    	"year"=>$year,
				    	"classesID"=>$classID);
				    	$examz3=$this->reportcardss_m->get_exams($array3);
				    	// if (!count($examz)) {
				    	// 	echo " Please make sure the reportcard settings for the class, term and year are correct! Thank you.";
				    	// 	exit;
				    	// }
				    	foreach ($examz3 as $keyvalue) {
				    		$examzID3=$keyvalue->examsID;
				    	}
				    	if ($examzID3 == NULL) {
				    		$examzID3="1,2,3";
				    	}
			    		$opos3=$this->ranking_m->get_overall_position_end_term($examzID3,$classID,$roll,$year,"Term 3");
				    	$endtermmark3=$this->reportcardss_m->get_total_marks($classID,$transcripts->studentID,$examzID3,"Term 3",$year);
				    	$cpos3=$this->ranking_m->get_class_position_end_term($examzID3,$classID,$sectionID,$roll,$year,"Term 3");
				    	







// Total point and total marks

				    	if ($classID==13 || $classID==14) {
					    	if ($opos1['tp']==0) {
					    		$this->pdf->Cell(30,5,'T.Points: ...',1,0,'L');
					    		$deviations["Term 1"]=0;

					    	}else{
					    		$this->pdf->Cell(30,5,'T.Points: '.$opos1['tp'],1,0,'L');
					    		$deviations["Term 1"]=$opos1['tp'];
					    	}

					    	if ($opos2['tp']==0) {
					    		$this->pdf->Cell(30,5,'T.Points: ...',1,0,'L');
					    		$deviations["Term 2"]=0;
					    	}else{
					    		$this->pdf->Cell(30,5,'T.Points: '.$opos2['tp'],1,0,'L');
					    		$deviations["Term 2"]=$opos2['tp'];
					    	}

					    	if ($opos3['tp']==0) {
					    		$this->pdf->Cell(30,5,'T.Points: ...',1,0,'L');
					    		$deviations["Term 3"]=0;
					    	}else{
					    		$this->pdf->Cell(30,5,'T.Points: '.$opos3['tp'],1,0,'L');
					    		$deviations["Term 3"]=$opos['tp'];
					    	}



					    		$this->pdf->Cell(60,5,"",0,0,'L');
					    		if ($deviations['Term 1'] !='' && $deviations['Term 2'] !='') {
					    			$this->pdf->Cell(42,5,"Term 2:".($deviations['Term 2']-$deviations['Term 1']),1,1,'L');
					    		}else{
					    			$this->pdf->Cell(42,5,"Term 2:...",1,1,'L');
					    		}


					    	
					    }else{
					    	
					    	if ($endtermmark1==0) {
								$this->pdf->Cell(30,5,'T.Marks: ...',1,0,'L');	
								$deviations["Term 1"]=0;	
					    	}else{
					    		$this->pdf->Cell(30,5,'T.Marks: '.round($endtermmark1,0),1,0,'L');
					    		$deviations["Term 1"]=round($endtermmark1,0);
					    	}
					    	if ($endtermmark2==0) {
								$this->pdf->Cell(30,5,'T.Marks: ...',1,0,'L');	
								$deviations["Term 2"]=0;	
					    	}else{
					    		$this->pdf->Cell(30,5,'T.Marks: '.round($endtermmark2,0),1,0,'L');
					    		$deviations["Term 2"]=round($endtermmark2,0);
					    	}
					    	if ($endtermmark3==0) {
								$this->pdf->Cell(30,5,'T.Marks: ...',1,0,'L');	
								$deviations["Term 3"]=0;	
					    	}else{
					    		$this->pdf->Cell(30,5,'T.Marks: '.round($endtermmark3,0),1,0,'L');
					    		$deviations["Term 3"]=round($endtermmark3,0);
					    	}

					    		$this->pdf->Cell(60,5,"",0,0,'L');
					    		if ($deviations['Term 1'] !='' && $deviations['Term 2'] !='') {
					    			$this->pdf->Cell(42,5,"Term 2:".($deviations['Term 2']-$deviations['Term 1']),1,1,'L');
					    		}else{
					    			$this->pdf->Cell(42,5,"Term 2:...",1,1,'L');
					    		}
					    	
					    }


// Mean Grade
					    if ($classID !=13 && $classID !=14) {
					    		$kygrades = $this->grade_m->get_overal_performance_grading();
					    		if(count($kygrades)) {
					    			if ($endtermmark1 !=0) {
					    				foreach ($kygrades as $grade) {
						                      if($grade->gradeFrom <= round($endtermmark1/count($transcriptss),0) && $grade->gradeTo >= round($endtermmark1/count($transcriptss),0)) {
						                           	$this->pdf->Cell(30,5,'M.Grade: '.$grade->grade,1,0,'L');
						                      }
						                  }
					    			}else{
					    				$this->pdf->Cell(30,5,'M.Grade: ...',1,0,'L');
					    			}

					    			if ($endtermmark2 !=0) {
					    				foreach ($kygrades as $grade) {
						                      if($grade->gradeFrom <= round($endtermmark2/count($transcriptss),0) && $grade->gradeTo >= round($endtermmark2/count($transcriptss),0)) {
						                           	$this->pdf->Cell(30,5,'M.Grade: '.$grade->grade,1,0,'L');
						                      }
						                  }
					    			}else{
					    				$this->pdf->Cell(30,5,'M.Grade: ...',1,0,'L');
					    			}

					    			if ($endtermmark3 !=0) {
					    				foreach ($kygrades as $grade) {
						                      if($grade->gradeFrom <= round($endtermmark3/count($transcriptss),0) && $grade->gradeTo >= round($endtermmark3/count($transcriptss),0)) {
						                           	$this->pdf->Cell(30,5,'M.Grade: '.$grade->grade,1,0,'L');
						                      }
						                  }
					    			}else{
					    				$this->pdf->Cell(30,5,'M.Grade: ...',1,0,'L');
					    			}
					                  
					            }
				    		}else{
				    			if ($opos1['avg_points'] !=0) {
				    				$ttp1 = $opos1['avg_points'];
				    			}else{
				    				$ttp1 = '';
				    			} 
				    			if ($opos2['avg_points'] !=0) {
				    				$ttp2 = $opos2['avg_points'];
				    			}else{
				    				$ttp2 = '';
				    			} 
				    			if ($opos3['avg_points'] !=0) {
				    				$ttp3 = $opos3['avg_points'];
				    			}else{
				    				$ttp3 = '';
				    			}
				    			
				    			
				    			
					    		$kddygrades = $this->grade_m->get_overal_performance_grading();
					    		if(count($kddygrades)) {
					    			if (!empty($ttp1)) {
					    				foreach ($kddygrades as $grade) {
						                      if($grade->points <= $ttp1 && $grade->points >= $ttp1) {
						                           	$this->pdf->Cell(30,5,'M.Grade: '.$student_overall_grade,1,0,'L');
						                      }
						                }
					    			}else{
					    				$this->pdf->Cell(30,5,'M.Grade: ...',1,0,'L');
					    			}
					    			if (!empty($ttp2)) {
					    				foreach ($kddygrades as $grade) {
						                      if($grade->points <= $ttp2 && $grade->points >= $ttp2) {
						                           	$this->pdf->Cell(30,5,'M.Grade: '.$student_overall_grade,1,0,'L');
						                      }
						                }
					    			}else{
					    				$this->pdf->Cell(30,5,'M.Grade: ...',1,0,'L');
					    			}
					    			if (!empty($ttp3)) {
					    				foreach ($kddygrades as $grade) {
						                      if($grade->points <= $ttp3 && $grade->points >= $ttp3) {
						                           	$this->pdf->Cell(30,5,'M.Grade: '.$student_overall_grade,1,0,'L');
						                      }
						                }
					    			}else{
					    				$this->pdf->Cell(30,5,'M.Grade: ...',1,0,'L');
					    			}
					            }
				    		}
					    		$this->pdf->Cell(60,5,"",0,0,'L');
					    		$this->pdf->Cell(42,5,"Term 3:...",1,1,'L');
					    	

// Overall Position
					    	if ($classID ==13 || $classID==14) {
					    		if ($opos1['position'] !="") {
									$this->pdf->Cell(30,5,'Position: '.$opos1['position'].'/'.$opos1['totalstudents'],1,0,'L');	
						    	}else{
						    		$this->pdf->Cell(30,5,'Position: ...',1,0,'L');
						    	}
						    	if ($opos2['position'] !="") {
									$this->pdf->Cell(30,5,'Position: '.$opos2['position'].'/'.$opos2['totalstudents'],1,0,'L');	
						    	}else{
						    		$this->pdf->Cell(30,5,'Position: ...',1,0,'L');
						    	}
						    	if ($opos3['position'] !="") {
									$this->pdf->Cell(30,5,'Position: '.$opos3['position'].'/'.$opos3['totalstudents'],1,0,'L');	
						    	}else{
						    		$this->pdf->Cell(30,5,'Position: ...',1,0,'L');
						    	}
					    	}else{
					    		if ($opos1['position'] !="") {
									$this->pdf->Cell(30,5,'Position: '.$opos1['position'].'/'.$opos1['totalstudents'],1,0,'L');	
						    	}else{
						    		$this->pdf->Cell(30,5,'Position: ...',1,0,'L');
						    	}
						    	if ($opos2['position'] !="") {
									$this->pdf->Cell(30,5,'Position: '.$opos2['position'].'/'.$opos2['totalstudents'],1,0,'L');	
						    	}else{
						    		$this->pdf->Cell(30,5,'Position: ...',1,0,'L');
						    	}
						    	if ($opos3['position'] !="") {
									$this->pdf->Cell(30,5,'Position: '.$opos3['position'].'/'.$opos3['totalstudents'],1,0,'L');	
						    	}else{
						    		$this->pdf->Cell(30,5,'Position: ...',1,0,'L');
						    	}
					    	}
							    
						    		$this->pdf->Cell(60,5,"",0,0,'L');
						    		if ($deviations['Term 1'] !='' && $deviations['Term 2'] !='' && $deviations['Term 3'] !='') {
						    			$this->pdf->Cell(42,5,"Overall: ".$deviations['Term 2']-$deviations['Term 1'],1,1,'L');
						    		}else{
						    			$this->pdf->Cell(42,5,"Overall:...",1,1,'L');
						    		}





//CLass position
						   if ($classID==13 || $classID==14) {
					    	
						    }else{
						    	if ($cpos1['position']!="") {
									$this->pdf->Cell(30,5,'C.Pos: '.$cpos1['position'].'/'.$cpos1['totalstudents'],1,0,'L');   		
						    	}else{
						    		$this->pdf->Cell(30,5,'C.Pos: ...',1,0,'L');
						    	}
						    	if ($cpos2['position']!="") {
									$this->pdf->Cell(30,5,'C.Pos: '.$cpos2['position'].'/'.$cpos2['totalstudents'],1,0,'L');   		
						    	}else{
						    		$this->pdf->Cell(30,5,'C.Pos: ...',1,0,'L');
						    	}
						    	if ($cpos3['position']!="") {
									$this->pdf->Cell(30,5,'C.Pos: '.$cpos3['position'].'/'.$cpos3['totalstudents'],1,0,'L');   		
						    	}else{
						    		$this->pdf->Cell(30,5,'C.Pos: ...',1,0,'L');
						    	}
						    }
							    
						    	
						    		$this->pdf->Cell(60,5,"",0,1,'L');























































				    $this->pdf->ln(5);
				    $this->pdf->SetFont('','B');
				    $this->pdf->Cell(0,5,"REMARKS",0,1,'L');
				    $classTeacher='';
				    foreach ($transcriptss as $value) {
				    	$classTeacher=$value->teacher;
				    }
				    $this->pdf->SetFont('','');
				    $this->pdf->Cell(0,5,"Class Teacher -".$classTeacher,0,1,'L');
				    $this->pdf->SetFont('Courier','I');
				    if (!empty($student_overall_grade)) {
				    	if ($student_overall_grade==="A" || $student_overall_grade==="A-") {
				    		$this->pdf->Cell(0,5,"Excellent performance keep it up.",0,1,'L');
				    	}else if ($student_overall_grade==="B+" || $student_overall_grade==="B" || $student_overall_grade==="B-") {
				    		$this->pdf->Cell(0,5,"Good performance, aim high.",0,1,'L');
				    	}else if ($student_overall_grade==="C+" || $student_overall_grade==="C" || $student_overall_grade==="C-") {
				    		$this->pdf->Cell(0,5,"Average performance, improve you can do better.",0,1,'L');
				    	}else if ($student_overall_grade==="D+" || $student_overall_grade==="D" || $student_overall_grade==="D-") {
				    		$this->pdf->Cell(0,5,"Weak performance, improve for better grades.",0,1,'L');
				    	}else if ($student_overall_grade==="E") {
				    		$this->pdf->Cell(0,5,"Poor performance, work hard.",0,1,'L');
				    	}else{
				    		$this->pdf->Cell(0,5,"Undefined Grade.",0,1,'L');
				    	}
				    }else{
				    	$this->pdf->Cell(0,5,"Remark is not set!",0,1,'L');
				    }
				    $this->pdf->SetFont('Arial','');
				    $this->pdf->Cell(0,5,"Signature .......................................................",0,1,'L');
				    $this->pdf->ln(5);
				    $this->pdf->SetFont('','');
				    $princiData=$this->reportcardss_m->get_principal();
				    $this->pdf->Cell(0,5,"Principal -".$princiData->name,0,1,'L');
				    $this->pdf->SetFont('Courier','I');
				    if (!empty($student_overall_grade)) {
				    	if ($student_overall_grade==="A" || $student_overall_grade==="A-") {
				    		$this->pdf->Cell(0,5,"Excellent performance keep it up.",0,1,'L');
				    	}else if ($student_overall_grade==="B+" || $student_overall_grade==="B" || $student_overall_grade==="B-") {
				    		$this->pdf->Cell(0,5,"Good performance, aim high.",0,1,'L');
				    	}else if ($student_overall_grade==="C+" || $student_overall_grade==="C" || $student_overall_grade==="C-") {
				    		$this->pdf->Cell(0,5,"Average performance, improve you can do better.",0,1,'L');
				    	}else if ($student_overall_grade==="D+" || $student_overall_grade==="D" || $student_overall_grade==="D-") {
				    		$this->pdf->Cell(0,5,"Weak performance, improve for better grades.",0,1,'L');
				    	}else if ($student_overall_grade==="E") {
				    		$this->pdf->Cell(0,5,"Poor performance, work hard.",0,1,'L');
				    	}else{
				    		$this->pdf->Cell(0,5,"Undefined Grade.",0,1,'L');
				    	}
				    }else{
				    	$this->pdf->Cell(0,5,"Remark is not set!",0,1,'L');
				    }
				    $this->pdf->SetFont('Arial','');
				    $this->pdf->Cell(0,5,"Signature .......................................................",0,1,'L');
				    $this->pdf->ln(5);
				    $this->pdf->SetFont('','B');
				    $this->pdf->Cell(0,5,"FEE BALANCE",0,1,'L');
				    $this->pdf->SetFont('','');
				    $this->pdf->Cell(0,5,"Your outstanding fee balance is Ksh..................................",0,1,'L');
				
	}
	public function alltranscripts() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$classID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$termID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			$year = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(5)));
			if((int)$classID && (int)$year) {
				$examID=$this->reportforms_m->get_examsID($classID,$year);
				$examinations=$this->reportforms_m->get_exams($examID->examsID);
				$term=$this->reportforms_m->get_term_name($termID);

				$arrayy=array(
					"classesID"=>$classID,
					"termID"=>$termID,
					"year"=>$year
					);
				$term_dates=$this->reportcardss_m->get_dates($arrayy);
				$this->data["term"]=$term->term_name;
				$this->data['set'] = $classID;
				$this->data['classID'] = $classID;
				$examIDs = $examID->examsID;
				//$this->data["grades"] = $this->grade_m->get_grade();
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["classess"] = $this->student_m->get_class($classID);
				$subjects = $this->ranking_m->get_subject($classID);
				$this->data["exams"] = $this->exam_m->get_exam();
				$classs=$this->ranking_m->get_class($classID);
				foreach ($classs as $keyvalue) {
					$class=$keyvalue->classes;
				}
				if ($classID==13 || $classID==14) {
					$student=$this->ranking_m->gets_sstudents_end_term($classID,$examID->examsID,$year,$term->term_name);
				}else{
				 $student= $this->ranking_m->get_students_average_end_term($examID->examsID,$classID,$year,$term->term_name);
				} 


				
			    // Generate PDF by saying hello to the world
			    if(count($student)>0){
			    	foreach ($student as $key) {
				    	$this->pdf->AddPage();
				    	$this->pdf->Image(base_url('uploads/images/header.png'),10,5,190);
					    $this->pdf->SetFont('Arial','B',13);
					    $this->pdf->ln(5);
					    $this->pdf->Cell(0,6,"KIARENI E.L.C.K MIXED SECONDARY SCHOOL",0,1,'C');
					    $this->pdf->SetFont('Arial','B',12);
					    $this->pdf->Cell(0,6,"P.O. BOX 1467 - 40200, KISII",0,1,'C');
					    $this->pdf->Cell(0,6,"www.kiareni.sc.ke",0,1,'C');
					    $this->pdf->ln(5);
					    $this->pdf->Cell(0,6,"Official Report Form",0,1,'C');
					    $this->pdf->Cell(0,6,$term->term_name."/".$year,0,1,'C');
					    $this->pdf->ln(6);
					    $this->pdf->Image(base_url('uploads/images/site.png'),10,18,30);
					    $this->pdf->Image(base_url('uploads/images/'.$key->photo),170,18,30);
					    $this->pdf->Cell(70,6,'Name: '.$key->name,0,0,'L');
					    $this->pdf->Cell(50,6,"Class: ".$class,0,0,'C');
					    $this->pdf->Cell(0,6,"Adm No: ".$key->roll,0,1,'R');

					    $oposition=$this->ranking_m->get_overall_position_end_term($examID->examsID,$classID,$key->roll,$year,$term->term_name);
					    $cposition=$this->ranking_m->get_class_position_end_term($examID->examsID,$classID,$key->sectionID,$key->roll,$year,$term->term_name);
					    $transcriptss= $this->reportcardss_m->get_order_by_transcripts_with_highest_mark_end_term($classID,$key->studentID,$examID->examsID,$term->term_name,$year) ;
					    $header = array('Code', 'Subject Name');
					    foreach ($examinations as $exam) {
					    	array_push($header,ucwords(strtolower($exam->exam)));
					    }
					    array_push($header,'Average');
						array_push($header,'Remarks');
						array_push($header,'Initials');

						
						$this->FancyTable($header,$student,$classID,$transcriptss,$cposition,$oposition,$examinations,$examIDs,$year,$key->sectionID,$key->roll);
						$this->pdf->ln(5);
						$this->pdf->SetFont('','B');
					    $this->pdf->Cell(0,5,"CALENDAR",0,1,'L');
					    $this->pdf->SetFont('','');
					    $this->pdf->Cell(0,5,"Closing date: ".$term_dates->closing_date,0,0,'L');
					    $this->pdf->Cell(0,5,"Opening Date: ".$term_dates->next_term_opening_date,0,1,'R');
					    $this->pdf->ln(1);
					    $this->pdf->SetFont('Courier','B');
					    $this->pdf->Cell(0,4,"Date Generated: ".date("d/m/Y"),0,0,'C');
					    $this->pdf->Image(base_url('uploads/images/footer.png'),10,283,190);
					    
				    }
				    //print_r($transcriptss);
				    $this->pdf->Output();
			    }else{
			    	echo "Error!";
			    }
			    
				
			// More methods goes here
			} else {
				$this->data['set_term'] = 0;
				$this->data['set_year'] = 0;
				$this->data['years']=$this->reportcards_m->get_years();
				$this->data['termss']=$this->term_m->get_term();
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["exams"] = $this->exam_m->get_exam();
				$this->data["subview"] = "reportcardss/search";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}
	function FancyTable($header,$student,$classID,$transcriptss,$cposition,$oposition,$examinations,$examIDs,$year,$sectionID,$roll)
				{
				    $this->pdf->SetFillColor(155,155,155);
				    $this->pdf->SetTextColor(255);
				    $this->pdf->SetDrawColor(192,192,192);
				    $this->pdf->SetLineWidth(.3);
				    $this->pdf->SetFont('Arial','B',10);
				    // Header
				    $w = array();
				    $student_overall_grade="";
				    $f3and4grade="";
				    if(sizeof($examinations)>2){
				    	array_push($w, 10);
				    	array_push($w, 34);
				    	foreach ($examinations as $key) {
					    	array_push($w, 18);
					    }
					    array_push($w, 25);
					    array_push($w, 45);
					    array_push($w, 25);
				    }else{
				    	array_push($w, 15);
				    	array_push($w, 40);
				    	foreach ($examinations as $key) {
					    	array_push($w, 20);
					    }
					    array_push($w, 25);
					    array_push($w, 47);
					    array_push($w, 25);
				    }
				    
				    
				    for($i=0;$i<count($header);$i++)
				        $this->pdf->Cell($w[$i],7,$header[$i],1,0,'L',true);
				    $this->pdf->Ln();
				    // Color and font restoration
				    $this->pdf->SetFillColor(224,235,255);
				    $this->pdf->SetTextColor(0);
				    // Data
				    $fill = false;
				    $ttmark=false;
				    $i=0;
				    $j=0;
				    $this->pdf->SetFont('Arial','',10);
					if ($classID==13 || $classID==14) {
						$totalstudents=count($student);
						if ($totalstudents>0) {
							foreach ($transcriptss as $transcripts) {
								$this->pdf->SetFont('','');
						      	$this->pdf->Cell($w[0],6,$transcripts->sc,'LR',0,'L',$fill);
						        $this->pdf->Cell($w[1],6,$transcripts->subject,'LR',0,'L',$fill);
						        $grades=$this->grade_m->get_grade(array("subjectID"=>$transcripts->subjectID));
						        foreach ($examinations as $value) {
						        	$subject_score=$this->ranking_m->get_subjects_marks_end_term($transcripts->studentID,$value->examID,$transcripts->subjectID,$transcripts->year,$transcripts->term_name);
						        	if (count($subject_score) !=0) {
							        	foreach ($subject_score as $keyvalue) {
						        			if(count($grades)) {
								              foreach ($grades as $grade) {
								                  if($grade->gradefrom <= round($keyvalue->mark,0) && $grade->gradeupto >= round($keyvalue->mark,0)) {
								                  	$this->pdf->Cell($w[3],6,round($keyvalue->mark,0).' '.$grade->grade,'LR',0,'L',$fill);
								                  }
								              }
									        }
							        	}
						        	}else{
						        			$this->pdf->Cell($w[3],6,'...','LR',0,'L',$fill);
						        	}
						        }
						        if (sizeof($examinations)>2) {
						        	$gradees=$this->grade_m->get_grade(array("subjectID"=>$transcripts->subjectID));
									if(count($gradees)) {
						              foreach ($gradees as $grade) {
						                  if($grade->gradefrom <= round($transcripts->mark,0) && $grade->gradeupto >= round($transcripts->mark,0)) {
						                  	$this->pdf->Cell($w[5],6,round($transcripts->mark,0).' '.$grade->grade,'LR',0,'L',$fill);
						                  	$this->pdf->SetFont('','I');
						                      if ($transcripts->sc ==102) {
						                         $this->pdf->Cell($w[6],6,$this->lang->line('reportcardss_'.$grade->note),'LR',0,'L',$fill);
						                      }else{
						                          $this->pdf->Cell($w[6],6,$grade->note,'LR',0,'L',$fill);
						                      }
						                  }
						              }
						          }

						        $this->pdf->Cell($w[7],6,$transcripts->initials,'LR',0,'L',$fill);
								}else{
									if(count($grades)) {
						              foreach ($grades as $grade) {
						                  if($grade->gradefrom <= $transcripts->mark && $grade->gradeupto >= $transcripts->mark) {
						                  	$this->pdf->Cell($w[5],6,round($transcripts->mark,0).' '.$grade->grade,'LR',0,'L',$fill);
						                  	$this->pdf->SetFont('','I');
						                      if ($transcripts->sc ==102) {
						                         $this->pdf->Cell($w[6],6,$this->lang->line('reportcardss_'.$grade->note),'LR',0,'L',$fill);
						                      }else{
						                          $this->pdf->Cell($w[6],6,$grade->note,'LR',0,'L',$fill);
						                      }
						                  }
						              }
						          }
						        $this->pdf->Cell($w[7],6,$transcripts->initials,'LR',0,'L',$fill);
								}
						          
						        $this->pdf->SetFont('','B');
						        $this->pdf->Ln();
						        $fill = !$fill;
						      }
					           $kgrades = $this->grade_m->get_grade(array("classesID"=>1,"subjectID"=>1));
					              if(count($kgrades)) {
					                  foreach ($kgrades as $grade) {
					                      if($grade->point <= round($oposition['tp']/7,0) && $grade->point >= round($oposition['tp']/7,0)) {
					                              $ssgrade=$grade->grade;
					                              $student_overall_grade=$grade->grade;
					                      }
					                  }
					              }
					      	$this->pdf->SetFont('Arial','B',9);
					      	$this->pdf->Cell(62,6,strtoupper("End term results summary (S.E. ".count($transcriptss).')'),0,0,'C');
							$this->pdf->Cell(30,6,"T.Points: ".$oposition['tp'],0,0,'R');
							$this->pdf->Cell(35,6,"M.Grade: ".$ssgrade,0,0,'R');
							if ($classID==13 || $classID==14) {
								//$this->pdf->Cell(35,6,"Position: ".$oposition['position'].'/'.$oposition['totalstudents'],0,1,'L');
								$this->pdf->Cell(30,6,"O.Position: ".$oposition['position'].'/'.$oposition['totalstudents'],0,0,'L');
								$this->pdf->Cell(30,6,"C.Position: ".$cposition['position'].'/'.$cposition['totalstudents'],0,1,'L');
							}else{
								$this->pdf->Cell(25,6,"O.Pos: ".$oposition['position'].'/'.$oposition['totalstudents'],0,0,'L');
								$this->pdf->Cell(25,6,"C.Pos: ".$cposition['position'].'/'.$cposition['totalstudents'],0,1,'L');
							}
						}else{
							$this->pdf->Cell(40,6,"No Data Found!",0,0,'L');
						}  
					  }else{
					  	$totalmarks=0;
					      foreach ($transcriptss as $transcripts) {
					      	$totalstudents=count($student);
					      	$this->pdf->SetFont('Arial','',9);
					      	$this->pdf->Cell($w[0],6,$transcripts->sc,'LR',0,'L',$fill);
					        $this->pdf->Cell($w[1],6,$transcripts->subject,'LR',0,'L',$fill);
					      	$grades=$this->grade_m->get_grade(array("subjectID"=>$transcripts->subjectID));
					      	foreach ($examinations as $value) {
					        	$subject_score=$this->ranking_m->get_subjects_marks_end_term($transcripts->studentID,$value->examID,$transcripts->subjectID,$transcripts->year,$transcripts->term_name);
					        	if (count($subject_score) !=0) {
						        	foreach ($subject_score as $keyvalue) {
						        			if(count($grades)) {
								              foreach ($grades as $grade) {
								                  if($grade->gradefrom <= round($keyvalue->mark,0) && $grade->gradeupto >= round($keyvalue->mark,0)) {
								                  	$this->pdf->Cell($w[3],6,round($keyvalue->mark,0).' '.$grade->grade,'LR',0,'L',$fill);
								                  }
								              }
									        }
						        		
						        		
						        	}
					        	}else{
					        		$this->pdf->Cell($w[3],6,'...','LR',0,'L',$fill);
					        	}
					        }
					          
				              	if (sizeof($examinations)>2) {
					              		if(count($grades)) {
							              foreach ($grades as $grade) {
							              	if (is_numeric($transcripts->mark)) {
							              		if($grade->gradefrom <= round($transcripts->mark,0) && $grade->gradeupto >= round($transcripts->mark,0)) {
								                  	$this->pdf->Cell($w[5],6,round($transcripts->mark,0).' '.$grade->grade,'LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                      if ($transcripts->sc ==102) {
								                         $this->pdf->Cell($w[6],6,$this->lang->line('reportcardss_'.$grade->note),'LR',0,'L',$fill);
								                      }else{
								                          $this->pdf->Cell($w[6],6,$grade->note,'LR',0,'L',$fill);
								                      }
								                  }
							              	}else{
							              		if ($transcripts->mark==="X") {
							              			$this->pdf->Cell($w[5],6,'X','LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[6],6,'Not Done','LR',0,'L',$fill);
							              		}else if($transcripts->mark==="x"){
							              			$this->pdf->Cell($w[5],6,'X','LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[6],6,'Not Done','LR',0,'L',$fill);
							              		}else if($transcripts->mark==="n"){
							              			$this->pdf->Cell($w[5],6,' ','LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[6],6,'Not Taking','LR',0,'L',$fill);
							              		}else if($transcripts->mark===""){
							              			$this->pdf->Cell($w[5],6,'-','LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[6],6,'Missing Mark','LR',0,'L',$fill);
							              		}else if($transcripts->mark==="N"){
							              			$this->pdf->Cell($w[5],6,'','LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[6],6,'Not Taking','LR',0,'L',$fill);
							              		}else{
							              			$this->pdf->Cell($w[5],6,$transcripts->mark,'LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[6],6,'Undefined','LR',0,'L',$fill);
							              		}
							              	}
							            }
							        }
						            $this->pdf->Cell($w[7],6,$transcripts->initials,'LR',0,'L',$fill);
				              	}else{
				              		foreach ($grades as $grade) {
							            if (is_numeric($transcripts->mark)) {
						              		if (is_numeric($transcripts->mark)) {
						              			if($grade->gradefrom <= round($transcripts->mark,0) && $grade->gradeupto >= round($transcripts->mark,0)) {
								                  	$this->pdf->Cell($w[4],6,round($transcripts->mark,0).' '.$grade->grade,'LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                      if ($transcripts->sc ==102) {
								                         $this->pdf->Cell($w[5],6,$this->lang->line('reportcardss_'.$grade->note),'LR',0,'L',$fill);
								                      }else{
								                          $this->pdf->Cell($w[5],6,$grade->note,'LR',0,'L',$fill);
								                      }
								                  }
							              	}else{
							              		if ($transcripts->mark==="X") {
							              			$this->pdf->Cell($w[4],6,'X','LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[5],6,'Not Done','LR',0,'L',$fill);
							              		}else if($transcripts->mark==="x"){
							              			$this->pdf->Cell($w[4],6,'X','LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[5],6,'Not Done','LR',0,'L',$fill);
							              		}else if($transcripts->mark==="n"){
							              			$this->pdf->Cell($w[4],6,' ','LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[5],6,'Not Taking','LR',0,'L',$fill);
							              		}else if($transcripts->mark===""){
							              			$this->pdf->Cell($w[4],6,'-','LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[5],6,'Missing Mark','LR',0,'L',$fill);
							              		}else if($transcripts->mark==="N"){
							              			$this->pdf->Cell($w[4],6,'','LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[5],6,'Not Taking','LR',0,'L',$fill);
							              		}else{
							              			$this->pdf->Cell($w[4],6,$transcripts->mark,'LR',0,'L',$fill);
								                  	$this->pdf->SetFont('','I');
								                    $this->pdf->Cell($w[5],6,'Undefined','LR',0,'L',$fill);
							              		}
							              	}
							            }
							        }
					              	$this->pdf->Cell($w[6],6,$transcripts->initials,'LR',0,'L',$fill);
				              	}
					        $this->pdf->SetFont('','B');
					        $this->pdf->Ln();
					        $fill = !$fill;
					        
		                  $totalmarks+=round($transcripts->mark,0);
		                  //echo "totalpoints:".$totalmarks."+(".round($transcripts->mark,0).")<br/>";
					      }
					      $avmarks=round(($totalmarks/count($transcriptss)),0);
				           $kgrades = $this->grade_m->get_overal_performance_grading();
				              if(count($kgrades)) {
				                  foreach ($kgrades as $grade) {
				                      if($grade->gradeFrom <= $avmarks && $grade->gradeTo >= $avmarks) {
				                              $ssgrade=$grade->grade;
				                              $student_overall_grade=$grade->grade;
				                      }
				                  }
				              }
				      	//$this->pdf->SetFont('Arial','',9);
				        $this->pdf->Cell(96,6,"END TERM RESULTS SUMMARY",0,0,'L');
						$this->pdf->Cell(25,6,"T.Marks: ".round($totalmarks,0),0,0,'L');
						$this->pdf->Cell(25,6,"M.Grade: ".$ssgrade,0,0,'L');
							$this->pdf->Cell(25,6,"O.Pos: ".$oposition['position'].'/'.$oposition['totalstudents'],0,0,'L');
							$this->pdf->Cell(25,6,"C.Pos: ".$cposition['position'].'/'.$cposition['totalstudents'],0,1,'L');
						
					  }
				    // Closing line
				    $this->pdf->Cell(array_sum($w),0,'','T');
				    $this->pdf->ln(5);
				    $this->pdf->Cell(0,5,"KCPE ANALYSIS",0,1,'L');
				    $this->pdf->Cell(35,5,"Mark",1,0,'L');
				    $this->pdf->Cell(25,5,"Grade",1,0,'L');
				    $this->pdf->Cell(35,5,"Position",1,0,'L');
				    $this->pdf->Cell(62,5,"Former Primary",1,0,'L');
				    $this->pdf->Cell(35,5,"KCPE Year",1,1,'L');
				    $this->pdf->SetFont('','');
				    $kcpe_data=$this->student_m->get_kcpe_analysis_details($classID,$transcripts->studentID);
				    $this->pdf->Cell(35,5,$kcpe_data['mark'].'/500',1,0,'L');
				    $this->pdf->Cell(25,5,$kcpe_data['grade'],1,0,'L');
				    $this->pdf->Cell(35,5,$kcpe_data['position'].'/'.$kcpe_data['totalstudents'],1,0,'L');
				    $this->pdf->Cell(62,5,"",1,0,'L');
				    $this->pdf->Cell(35,5,$kcpe_data['kcpe_year'],1,1,'L');
				    $this->pdf->ln(5);
				    $this->pdf->SetFont('','B');
				    $this->pdf->Cell(0,5,"STUDENT PROGRESSIVE ANALYSIS",0,1,'L');
				    $terms=$this->reportcardss_m->get_terms();
				    $tno=count($terms);
				    $m=1;
				    $n=1;
				    $o=1;
				    $p=1;
				    $q=1;
				    foreach ($terms as $value) {
				    	$this->pdf->Cell(30,5,ucwords(strtolower($value->term_name)),1,0,'L');
				    	if ($m==$tno) {
				    		$this->pdf->Cell(60,5,"",0,0,'L');
				    		$this->pdf->Cell(42,5,"Deviation",1,1,'L');
				    	}
				    	$m++;
				    }
				    $this->pdf->SetFont('','');
				    foreach ($terms as $value) {
				    	$opos=$this->ranking_m->get_overall_position_end_term($examIDs,$classID,$roll,$year,$value->term_name);
					    if ($classID==13 || $classID==14) {
					    	if ($opos['tp']==0) {
					    		$this->pdf->Cell(30,5,'T.Points: ...',1,0,'L');
					    	}else{
					    		$this->pdf->Cell(30,5,'T.Points: '.$opos['tp'],1,0,'L');
					    	}
					    	if ($n==$tno) {
					    		$this->pdf->Cell(60,5,"",0,0,'L');
					    		$this->pdf->Cell(42,5,"Term 2: ...",1,1,'L');
					    	}
					    }else{
					    	$endtermmark=$this->reportcardss_m->get_total_marks($classID,$transcripts->studentID,$examIDs,$value->term_name,$year);
					    	if ($endtermmark==0) {
								$this->pdf->Cell(30,5,'T.Marks: ...',1,0,'L');		
					    	}else{
					    		$this->pdf->Cell(30,5,'T.Marks: '.round($endtermmark,0),1,0,'L');
					    	}
					    	if ($n==$tno) {
					    		$this->pdf->Cell(60,5,"",0,0,'L');
					    		$this->pdf->Cell(42,5,"Term 2: ...",1,1,'L');
					    	}
					    }
				    	$n++;
				    }
				    foreach ($terms as $value) {
				    	$oposs=$this->ranking_m->get_overall_position_end_term($examIDs,$classID,$roll,$year,$value->term_name);
				    		if ($classID !=13 && $classID !=14) {
				    			$markkk = $this->reportcardss_m->get_total_marks($classID,$transcripts->studentID,$examIDs,$value->term_name,$year);
					    		$kygrades = $this->grade_m->get_overal_performance_grading();
					    		if(count($kygrades)) {
					    			if ($markkk !=0) {
					    				foreach ($kygrades as $grade) {
						                      if($grade->gradeFrom <= round($markkk/count($transcriptss),0) && $grade->gradeTo >= round($markkk/count($transcriptss),0)) {
						                           	$this->pdf->Cell(30,5,'M.Grade: '.$grade->grade,1,0,'L');
						                      }
						                  }
					    			}else{
					    				$this->pdf->Cell(30,5,'M.Grade: ...',1,0,'L');
					    			}
					                  
					            }
				    		}else{
				    			$cpos=$this->ranking_m->get_class_position_end_term($examIDs,$classID,$sectionID,$roll,$year,$value->term_name);

				    			$ttp = $cpos['avg_points'];
					    		$kddygrades = $this->grade_m->get_overal_performance_grading();
					    		if(count($kddygrades)) {
					    			if (!empty($ttp)) {
					    				foreach ($kddygrades as $grade) {
						                      if($grade->points <= $ttp && $grade->points >= $ttp) {
						                           	$this->pdf->Cell(30,5,'M.Grade: '.$student_overall_grade,1,0,'L');
						                      }
						                }
					    			}else{
					    				$this->pdf->Cell(30,5,'M.Grade: ...',1,0,'L');
					    			}
					            }
				    		}
					    		
					    	if ($o==$tno) {
					    		$this->pdf->Cell(60,5,"",0,0,'L');
					    		$this->pdf->Cell(42,5,"Term 3: ...",1,1,'L');
					    	}
				    	$o++;
				    }
				    foreach ($terms as $value) {
				    	$opos2=$this->ranking_m->get_overall_position_end_term($examIDs,$classID,$roll,$year,$value->term_name);
				    	if ($classID ==13 || $classID==14) {
				    		if ($opos2['position'] !="") {
								$this->pdf->Cell(30,5,'Position: '.$opos2['position'].'/'.$opos2['totalstudents'],1,0,'L');	
					    	}else{
					    		$this->pdf->Cell(30,5,'Position: ...',1,0,'L');
					    	}
				    	}else{
				    		if ($opos2['position'] !="") {
								$this->pdf->Cell(30,5,'O.Pos: '.$opos2['position'].'/'.$opos2['totalstudents'],1,0,'L');	
					    	}else{
					    		$this->pdf->Cell(30,5,'O.Pos: ...',1,0,'L');
					    	}
				    	}
						    
					    	if ($p==$tno) {
					    		$this->pdf->Cell(60,5,"",0,0,'L');
					    		$this->pdf->Cell(42,5,"Overall: ...",1,1,'L');
					    	}
				    	$p++;
				    }
				    foreach ($terms as $value) {
					    $cpos3=$this->ranking_m->get_class_position_end_term($examIDs,$classID,$sectionID,$roll,$year,$value->term_name);
					    if ($classID==13 || $classID==14) {
					    	
					    }else{
					    	if ($cpos3['position']!="") {
								$this->pdf->Cell(30,5,'C.Pos: '.$cpos3['position'].'/'.$cpos3['totalstudents'],1,0,'L');   		
					    	}else{
					    		$this->pdf->Cell(30,5,'C.Pos: ...',1,0,'L');
					    	}
					    }
						    
					    	
					    	if ($q==$tno) {
					    		$this->pdf->Cell(60,5,"",0,1,'L');
					    	}
				    	$q++;
				    }
				    $this->pdf->ln(5);
				    $this->pdf->SetFont('','B');
				    $this->pdf->Cell(0,5,"REMARKS",0,1,'L');
				    $classTeacher='';
				    foreach ($transcriptss as $value) {
				    	$classTeacher=$value->teacher;
				    }
				    $this->pdf->SetFont('','');
				    $this->pdf->Cell(0,5,"Class Teacher -".$classTeacher,0,1,'L');
				    $this->pdf->SetFont('Courier','I');
				    if (!empty($student_overall_grade)) {
				    	if ($student_overall_grade==="A" || $student_overall_grade==="A-") {
				    		$this->pdf->Cell(0,5,"Excellent performance keep it up.",0,1,'L');
				    	}else if ($student_overall_grade==="B+" || $student_overall_grade==="B" || $student_overall_grade==="B-") {
				    		$this->pdf->Cell(0,5,"Good performance, aim high.",0,1,'L');
				    	}else if ($student_overall_grade==="C+" || $student_overall_grade==="C" || $student_overall_grade==="C-") {
				    		$this->pdf->Cell(0,5,"Average performance, improve you can do better.",0,1,'L');
				    	}else if ($student_overall_grade==="D+" || $student_overall_grade==="D" || $student_overall_grade==="D-") {
				    		$this->pdf->Cell(0,5,"Weak performance, improve for better grades.",0,1,'L');
				    	}else if ($student_overall_grade==="E") {
				    		$this->pdf->Cell(0,5,"Poor performance, work hard.",0,1,'L');
				    	}else{
				    		$this->pdf->Cell(0,5,"Undefined Grade.",0,1,'L');
				    	}
				    }else{
				    	$this->pdf->Cell(0,5,"Remark is not set!",0,1,'L');
				    }
				    $this->pdf->SetFont('Arial','');
				    $this->pdf->Cell(0,5,"Signature .......................................................",0,1,'L');
				    $this->pdf->ln(5);
				    $this->pdf->SetFont('','');
				    $princiData=$this->reportcardss_m->get_principal();
				    $this->pdf->Cell(0,5,"Principal -".$princiData->name,0,1,'L');
				    $this->pdf->SetFont('Courier','I');
				    if (!empty($student_overall_grade)) {
				    	if ($student_overall_grade==="A" || $student_overall_grade==="A-") {
				    		$this->pdf->Cell(0,5,"Excellent performance keep it up.",0,1,'L');
				    	}else if ($student_overall_grade==="B+" || $student_overall_grade==="B" || $student_overall_grade==="B-") {
				    		$this->pdf->Cell(0,5,"Good performance, aim high.",0,1,'L');
				    	}else if ($student_overall_grade==="C+" || $student_overall_grade==="C" || $student_overall_grade==="C-") {
				    		$this->pdf->Cell(0,5,"Average performance, improve you can do better.",0,1,'L');
				    	}else if ($student_overall_grade==="D+" || $student_overall_grade==="D" || $student_overall_grade==="D-") {
				    		$this->pdf->Cell(0,5,"Weak performance, improve for better grades.",0,1,'L');
				    	}else if ($student_overall_grade==="E") {
				    		$this->pdf->Cell(0,5,"Poor performance, work hard.",0,1,'L');
				    	}else{
				    		$this->pdf->Cell(0,5,"Undefined Grade.",0,1,'L');
				    	}
				    }else{
				    	$this->pdf->Cell(0,5,"Remark is not set!",0,1,'L');
				    }
				    $this->pdf->SetFont('Arial','');
				    $this->pdf->Cell(0,5,"Signature .......................................................",0,1,'L');
				    $this->pdf->ln(5);
				    $this->pdf->SetFont('','B');
				    $this->pdf->Cell(0,5,"FEE BALANCE",0,1,'L');
				    $this->pdf->SetFont('','');
				    $this->pdf->Cell(0,5,"Your outstanding fee balance is Ksh..................................",0,1,'L');

				}
	public function alltranscripts1() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$classID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$examID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			if((int)$classID && (int)$examID) {
				$termss= $this->exam_m->get_term();
				$examss = $this->exam_m->get_exam_name($examID);
				foreach ($termss as $value) {
					$term=$value->term_name;
				}
				foreach ($examss as $value) {
					$exam=$value->exam;
				}
				$this->data["exam"]=$exam;
				$this->data["term"]=$term;
				$this->data['set'] = $classID;
				$this->data['classID'] = $classID;
				$this->data['examID'] = $examID;
				$this->data["grades"] = $this->grade_m->get_grade();
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["classess"] = $this->student_m->get_class($classID);
				$this->data['subjects'] = $this->ranking_m->get_subject($classID);
				$this->data["exams"] = $this->exam_m->get_exam();
				$class=$this->ranking_m->get_class($classID);
				foreach ($class as $keyvalue) {
					$this->data['class']=$keyvalue->classes;
				}
				if ($classID==13 || $classID==14) {
					$this->data['student']=$this->ranking_m->gets_sstudents($classID,$examID);
				}else{
				 $this->data['student']= $this->ranking_m->get_students_average($examID,$classID);
				}
				$this->load->library('html2pdf');
			    $this->html2pdf->folder('./assets/pdfs/');
			    $this->html2pdf->filename('Report.pdf');
			    $this->html2pdf->paper('a4', 'portrait');
			    $this->data['panel_title'] = $this->lang->line('panel_title');
				$html = $this->load->view('reportcardss/alltranscripts1', $this->data, true);
				$this->html2pdf->html($html);
				$this->html2pdf->create();
			} else {
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["exams"] = $this->exam_m->get_exam();
				$this->data["subview"] = "reportcardss/search";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}
	public function streamtranscripts() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$sectionID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$classID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			$examID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(5)));
			if((int)$classID && (int)$sectionID && (int)$examID) {
				$termss= $this->exam_m->get_term();
				$examss = $this->exam_m->get_exam_name($examID);
				foreach ($termss as $value) {
					$term=$value->term_name;
				}
				foreach ($examss as $value) {
					$exam=$value->exam;
				}
				$this->data["exam"]=$exam;
				$this->data["term"]=$term;
				$this->data['sectionID'] = $sectionID;
				$this->data['set'] = $classID;
				$this->data['classID'] = $classID;
				$this->data['examID'] = $examID;
				//$this->data["grades"] = $this->grade_m->get_grade();
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["classess"] = $this->student_m->get_class($classID);
				$this->data['subjects'] = $this->ranking_m->get_subject($classID);
				$this->data["exams"] = $this->exam_m->get_exam();
				$section=$this->ranking_m->get_section($sectionID);
				foreach ($section as $keyvalue) {
					$sectionname=$keyvalue->section;
				}
				if ($classID==13 || $classID==14) {
					$student=$this->ranking_m->gets_sstudentss($sectionID,$classID,$examID);
				}else{
				 $student= $this->ranking_m->gets_students_average($sectionID,$examID,$classID);
				}
				
			    // Generate PDF by saying hello to the world
			    foreach ($student as $key) {
			    	$this->pdf->AddPage();
			    	$this->pdf->Image(base_url('uploads/images/header.png'),10,10,190);
				    $this->pdf->SetFont('Arial','B',13);
				    $this->pdf->ln(20);
				    $this->pdf->Cell(0,6,"KIARENI E.L.C.K MIXED SECONDARY SCHOOL",0,1,'C');
				    $this->pdf->SetFont('Arial','B',12);
				    $this->pdf->Cell(0,6,"P.O. BOX 1467-40200, KISII",0,1,'C');
				    $this->pdf->Cell(0,6,"www.kiareni.sc.ke",0,1,'C');
				    $this->pdf->ln(7);
				    $this->pdf->Cell(0,6,"Official Transcript",0,1,'C');
				    $this->pdf->Cell(0,6,$term."/".$exam."/".date("Y"),0,1,'C');
				    $this->pdf->ln(10);
				    $this->pdf->Image(base_url('uploads/images/site.png'),10,30,30);
				    $this->pdf->Image(base_url('uploads/images/'.$key->photo),170,30,30);
				    $this->pdf->Cell(70,6,$key->name,0,0,'L');
				    $this->pdf->Cell(50,6,"CLass ".$sectionname,0,0,'C');
				    $this->pdf->Cell(0,6,"Adm No  ".$key->roll,0,1,'R');
				    $oposition=$this->ranking_m->get_overall_position($examID,$classID,$key->roll);
				    $cposition=$this->ranking_m->get_class_position($examID,$classID,$sectionID,$key->roll);
				    $transcriptss= $this->transcripts_m->get_order_by_transcripts_with_highest_mark($classID,$key->studentID,$examID);
					$header = array('Code', 'Subject Name', 'Score', 'Remarks','Initials');
					$this->sFancyTable($header,$student,$classID,$transcriptss,$cposition,$oposition);
					$this->pdf->ln(50);
					$this->pdf->SetFont('Arial','B',10);
				    $this->pdf->Cell(0,6,"Signature..................................................",0,0,'L');
				    $this->pdf->Cell(0,6,date("d/m/Y"),0,0,'R');
				    $this->pdf->ln(20);
					$this->pdf->SetFont('Arial','I',10);
				    $this->pdf->Cell(0,6,"*This is not final document certificate but summary of student score on specific Exam done.*",0,0,'C');
				    $this->pdf->Image(base_url('uploads/images/footer.png'),10,280,190);
			    }
			    
			    $this->pdf->Output();
				
			// More methods goes here
			} else {
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["exams"] = $this->exam_m->get_exam();
				$this->data["subview"] = "reportcardss/search";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}
	function sFancyTable($header,$student,$classID,$transcriptss,$cposition,$oposition)
				{
				    $this->pdf->SetFillColor(160,160,160);
				    $this->pdf->SetTextColor(255);
				    $this->pdf->SetDrawColor(192,192,192);
				    $this->pdf->SetLineWidth(.3);
				    $this->pdf->SetFont('','B');
				    // Header
				    $w = array(20, 55, 30, 60,25);
				    for($i=0;$i<count($header);$i++)
				        $this->pdf->Cell($w[$i],7,$header[$i],1,0,'L',true);
				    $this->pdf->Ln();
				    // Color and font restoration
				    $this->pdf->SetFillColor(224,235,255);
				    $this->pdf->SetTextColor(0);
				    $this->pdf->SetFont('');
				    // Data
				    $fill = false;
				    $ttmark=false;
				    $i=0;
				    $j=0;

					if ($classID==13 || $classID==14) {
						$totalstudents=count($student);
					      foreach ($transcriptss as $transcripts) {
					      	$this->pdf->Cell($w[0],6,$transcripts->sc,'LR',0,'L',$fill);
					        $this->pdf->Cell($w[1],6,$transcripts->subject,'LR',0,'L',$fill);
					      	$grades=$this->grade_m->get_grade(array("subjectID"=>$transcripts->subjectID));
					          if(count($grades)) {
					              foreach ($grades as $grade) {
					                  if($grade->gradefrom <= $transcripts->mark && $grade->gradeupto >= $transcripts->mark) {
					                  	$this->pdf->Cell($w[2],6,$transcripts->mark.' '.$grade->grade,'LR',0,'L',$fill);
					                  	$this->pdf->SetFont('','I');
					                      if ($transcripts->sc ==102) {
					                         $this->pdf->Cell($w[3],6,$this->lang->line('transcripts_'.$grade->note),'LR',0,'L',$fill);
					                      }else{
					                          $this->pdf->Cell($w[3],6,$grade->note,'LR',0,'L',$fill);
					                      }
					                  }
					              }
					          }
					        $this->pdf->Cell($w[4],6,$transcripts->initials,'LR',0,'L',$fill);
					        $this->pdf->SetFont('','B');
					        $this->pdf->Ln();
					        $fill = !$fill;
					      }
				           $kgrades = $this->grade_m->get_grade(array("classesID"=>1,"subjectID"=>1));
				              if(count($kgrades)) {
				                  foreach ($kgrades as $grade) {
				                      if($grade->point <= round($oposition['tp']/7,0) && $grade->point >= round($oposition['tp']/7,0)) {
				                              $ssgrade=$grade->grade;
				                      }
				                  }
				              }
				      	$this->pdf->ln(10);
				      	//$this->pdf->SetFont('Arial','',9);
						$this->pdf->Cell(40,6,"Total Points: ".$oposition['tp'],0,0,'L');
						$this->pdf->Cell(40,6,"Mean Grade: ".$ssgrade,0,0,'L');
						$this->pdf->Cell(60,6,"Overall Position: ".$oposition['position'].'/'.$oposition['totalstudents'],0,0,'L');
						$this->pdf->Cell(40,6,"Class Position: ".$cposition['position'].'/'.$cposition['totalstudents'],0,1,'L');
					  }else{
					  	$totalmarks=0;
					      foreach ($transcriptss as $transcripts) {
					      	$totalstudents=count($student);
					      	$this->pdf->Cell($w[0],6,$transcripts->sc,'LR',0,'L',$fill);
					        $this->pdf->Cell($w[1],6,$transcripts->subject,'LR',0,'L',$fill);
					      	$grades=$this->grade_m->get_grade(array("subjectID"=>$transcripts->subjectID));
					          if(count($grades)) {
					              foreach ($grades as $grade) {
					                  if($grade->gradefrom <= $transcripts->mark && $grade->gradeupto >= $transcripts->mark) {
					                  	$this->pdf->Cell($w[2],6,$transcripts->mark.' '.$grade->grade,'LR',0,'L',$fill);
					                  	$this->pdf->SetFont('','I');
					                      if ($transcripts->sc ==102) {
					                         $this->pdf->Cell($w[3],6,$this->lang->line('transcripts_'.$grade->note),'LR',0,'L',$fill);
					                      }else{
					                          $this->pdf->Cell($w[3],6,$grade->note,'LR',0,'L',$fill);
					                      }
					                  }
					              }
					          }
					        $this->pdf->Cell($w[4],6,$transcripts->initials,'LR',0,'L',$fill);
					        $this->pdf->SetFont('','B');
					        $this->pdf->Ln();
					        $fill = !$fill;
					        $totalmarks+=$transcripts->mark;
					      }
					      $avmarks=round(($totalmarks/11),0);
				           $kgrades = $this->grade_m->get_grade(array("classesID"=>1,"subjectID"=>1));
				              if(count($kgrades)) {
				                  foreach ($kgrades as $grade) {
				                      if($grade->gradefrom <= $avmarks && $grade->gradeupto >= $avmarks) {
				                              $ssgrade=$grade->grade;
				                      }
				                  }
				              }
				      	$this->pdf->ln(10);
				      	//$this->pdf->SetFont('Arial','',9);
						$this->pdf->Cell(40,6,"Total Marks: ".$totalmarks,0,0,'L');
						$this->pdf->Cell(40,6,"Mean Grade: ".$ssgrade,0,0,'L');
						$this->pdf->Cell(60,6,"Overall Position: ".$oposition['position'].'/'.$oposition['totalstudents'],0,0,'L');
						$this->pdf->Cell(40,6,"Class Position: ".$cposition['position'].'/'.$cposition['totalstudents'],0,1,'L');
					  }

				    // Closing line
				    $this->pdf->Cell(array_sum($w),0,'','T');
	}

	public function send_mail() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$id = $this->input->post('id');
			$url = $this->input->post('set');
			if ((int)$id && (int)$url) {
				$this->data["student"] = $this->student_m->get_student($id);
				$this->data["classes"] = $this->student_m->get_class($url);
				if($this->data["student"] && $this->data["classes"]) {

					$this->data['set'] = $url;
					$this->data["exams"] = $this->exam_m->get_exam();
					$this->data["grades"] = $this->grade_m->get_grade();
					$this->data["transcriptss"] = $this->transcripts_m->get_order_by_transcripts(array("studentID" =>$id, "classesID" => $url));
					$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);

				    $this->load->library('html2pdf');
				    $this->html2pdf->folder('uploads/report');
				    $this->html2pdf->filename('Report.pdf');
				    $this->html2pdf->paper('a4', 'portrait');
				    $this->data['panel_title'] = $this->lang->line('panel_title');
					$html = $this->load->view('reportcardss/print_preview', $this->data, true);
					$this->html2pdf->html($html);
					$this->html2pdf->create('save');

					if($path = $this->html2pdf->create('save')) {
					$this->load->library('email');
					$this->email->set_mailtype("html");
					$this->email->from($this->data["siteinfos"]->email, $this->data['siteinfos']->sname);
					$this->email->to($this->input->post('to'));
					$this->email->subject($this->input->post('subject'));
					$this->email->message($this->input->post('message'));
					$this->email->attach($path);
						if($this->email->send()) {
							$this->session->set_flashdata('success', $this->lang->line('mail_success'));
						} else {
							$this->session->set_flashdata('error', $this->lang->line('mail_error'));
						}
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

	function reportcardss_list() {
		$classID = $this->input->post('classesID');
		$termID = $this->input->post('termID');
		$year = $this->input->post('year');
		if((int)$classID && (int)$termID && (int)$year) {
			$string = base_url("reportcardss/index/$classID/$termID/$year");
			echo $string;
		} else {
			redirect(base_url("reportcardss/index"));
		}
	}

	function stream_list() {
		$sectionID = $this->input->post('sectionID');
		$classID = $this->input->post('classesID');
		$examID = $this->input->post('examID');
		if((int)$sectionID && (int)$classID && (int)$examID) {
			$string = base_url("reportcardss/stream/$sectionID/$classID/$examID");
			echo $string;
		} else {
			redirect(base_url("reportcardss/filter"));
		}
	}

	function student_list() {
		$studentID = $this->input->post('id');
		if((int)$studentID) {
			$string = base_url("reportcardss/index/$studentID");
			echo $string;
		} else {
			redirect(base_url("reportcardss/index"));
		}
	}

	function subjectcall() {
		$usertype = $this->session->userdata("usertype");
		$id = $this->input->post('id');
		if((int)$id) {
			if($usertype == "Admin") {
				$allsubject = $this->subject_m->get_order_by_subject(array("classesID" => $id));
				echo "<option value='0'>", $this->lang->line("transcripts_select_subject"),"</option>";
				foreach ($allsubject as $value) {
					echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
				}
			} elseif($usertype == "Teacher") {
				$username = $this->session->userdata("username");
				$teacher = $this->user_m->get_username_row("teacher", array("username" => $username));
				$allsubject = $this->subject_m->get_order_by_subject(array("classesID" => $id, "teacherID" => $teacher->teacherID));
				echo "<option value='0'>", $this->lang->line("transcripts_select_subject"),"</option>";
				foreach ($allsubject as $value) {
					echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
				}
			}
		}
	}

	function outoftranscriptscall() {
		$usertype = $this->session->userdata("usertype");
		$sid = $this->input->post('sid');
		$cid = $this->input->post('cid');
		if((int)$sid && (int)$cid) {
				$allsubject = $this->data['subject_data'] = $this->examoutofsettings_m->get_subject_data($cid,$sid);
				foreach ($allsubject as $allsubject) {
					echo '
						<input type="text" class="form-control" id="outof" name="outof" value=" '
							.$allsubject->subjecttranscripts.'">';
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
