<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transcripts extends Admin_Controller {
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
		$this->load->model("transcripts_m");
		$this->load->model("term_m");
		$this->load->model("grade_m");
		$this->load->model("classes_m");
		$this->load->model("reportforms_m");
		$this->load->model("exam_m");
		$this->load->model("subject_m");
		$this->load->model("user_m");
		$this->load->model("section_m");
		$this->load->model("parentes_m");
		$this->load->model("ranking_m");
		$this->load->library('fpdf/pdf'); // Load library
		$language = $this->session->userdata('lang');
		$this->lang->load('transcripts', $language);
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
			$examID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			$year = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(5)));
			$termID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(6)));
			$this->data['set_year'] = 0;
			$this->data['set_term'] = 0;
			$this->data['years']=$this->ranking_m->get_years();
			$this->data['terms']=$this->term_m->get_term();
			
			if((int)$classID && (int)$examID && (int)$year && (int)$termID) {
				$term=$this->reportforms_m->get_term_name($termID);
				$this->data['termssID']=$termID;
				$this->data['term_name'] = $term->term_name;
				$this->data['year'] = $year;
				$this->data['set'] = $classID;
				$this->data['classID'] = $classID;
				$this->data['examID'] = $examID;
				//$this->data["grades"] = $this->grade_m->get_grade();
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["exams"] = $this->exam_m->get_exam();
				$class=$this->ranking_m->get_class($classID);
				foreach ($class as $keyvalue) {
					$this->data['class']=$keyvalue->classes;
				}
				if ($classID==13 || $classID==14) {
					$this->data['student']=$this->ranking_m->gets_sstudents($classID,$examID,$year,$this->data['term_name']);
				}else{
				 $this->data['student']= $this->ranking_m->get_students_average($examID,$classID,$year,$this->data['term_name']);
				}
				$this->data["subview"] = "transcripts/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["exams"] = $this->exam_m->get_exam();
				$this->data["subview"] = "transcripts/search";
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
				$this->data["subview"] = "transcripts/stream";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["exams"] = $this->exam_m->get_exam();
				$this->data["subview"] = "transcripts/filter";
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
								$in_student = $this->transcripts_m->get_order_by_transcripts(array("examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID, "studentID" => $studentID));
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
									$this->transcripts_m->insert_transcripts($array);
								}
							}
							$this->data['students'] = $students;
							$all_student = $this->transcripts_m->get_order_by_transcripts(array("examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID));
							$this->data['transcriptss'] = $all_student;
						}

					$this->data["subview"] = "transcripts/filter";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "transcripts/filter";
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
					$this->transcripts_m->update_transcripts_classes($array, array("examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID, "studentID" => $student->studentID));
					break;
				}
			}
			foreach ($ex_array_out_of as $key => $outof) {
				if($key == $student->studentID) {
					$array = array("out_of" => $outof);
					$this->transcripts_m->update_transcripts_classes($array, array("examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID, "studentID" => $student->studentID));
					break;
				}
			}
			foreach ($ex_array_percentage as $key => $percent) {
				if($key == $student->studentID) {
					$array = array("percentage" => $percent);
					$this->transcripts_m->update_transcripts_classes($array, array("examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID, "studentID" => $student->studentID));
					break;
				}
			}
		}
		echo $this->lang->line('transcripts_success');
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

			if ((int)$classID && (int)$examID && (int)$studentID && (int)$position && (int)$totalpoints && (int)$totalstudents) {

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
					$this->data["transcriptss"] = $this->transcripts_m->get_order_by_transcripts_with_highest_mark($classID,$studentID);
					$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);

					// dump($this->data["transcriptss"]);
					// die;


					$this->data["subview"] = "transcripts/view";
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
				$this->data["transcriptss"] = $this->transcripts_m->get_order_by_transcripts(array("studentID" => $student->studentID, "classesID" => $student->classesID));

				$this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);
				$this->data["subview"] = "transcripts/view";
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
			$year = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(9)));
			$termsID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(10)));
			
			if ((int)$classID && (int)$examID && (int)$studentID && (int)$position && (int)$year && (int)$termsID) {
				$term=$this->reportforms_m->get_term_name($termsID);
				$this->data['term_name'] = $term->term_name;
				$student = $this->student_m->get_student($studentID);
				$termss= $this->exam_m->get_term();
				$examss = $this->exam_m->get_exam_name($examID);
				foreach ($termss as $value) {
					$term=$value->term_name;
				}
				foreach ($examss as $value) {
					$exam=$value->exam;
				}
				$exam=$exam;
				$term=$term;
				$class=$this->ranking_m->get_class($classID);
				foreach ($class as $keyvalue) {
					$class=$keyvalue->classes;
				}
				if($student) {
					$examID= $examID;
					$classID= $classID;
					$studentID= $studentID;
					$position= $position;
					$totalpoints= $totalpoints;
					$totalstudents= $totalstudents;
					
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
				    $this->pdf->Image(base_url('uploads/images/'.$student->photo),170,30,30);
				    $this->pdf->Cell(70,6,ucwords(strtolower($student->name)),0,0,'L');
				    $this->pdf->Cell(50,6,"CLass ".$student->section,0,0,'C');
				    $this->pdf->Cell(0,6,"Adm No  ".$student->roll,0,1,'R');
				    $oposition=$this->ranking_m->get_overall_position($examID,$classID,$student->roll,$year,$this->data['term_name']);
				    $cposition=$this->ranking_m->get_class_position($examID,$classID,$student->sectionID,$student->roll,$year,$this->data['term_name']);
				    //print_r($cposition);
				    $transcriptss= $this->transcripts_m->get_order_by_transcripts_with_highest_mark($classID,$studentID,$examID,$year,$this->data['term_name']);
					$header = array('Code', 'Subject Name', 'Score', 'Remarks','Initials');
					$this->vFancyTable($header,$classID,$transcriptss,$cposition,$oposition);
					$this->pdf->ln(50);
					$this->pdf->SetFont('Arial','B',10);
				    $this->pdf->Cell(0,6,"Signature..................................................",0,0,'L');
				    $this->pdf->Cell(0,6,date("d/m/Y"),0,0,'R');
				    $this->pdf->ln(20);
					$this->pdf->SetFont('Arial','I',10);
				    $this->pdf->Cell(0,6,"*This is not final document certificate but summary of student score on specific Exam done.*",0,0,'C');
				    $this->pdf->Image(base_url('uploads/images/footer.png'),10,280,190);
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
	function vFancyTable($header,$classID,$transcriptss,$cposition,$oposition){
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
				           $kgrades = $this->grade_m->get_overal_performance_grading();
				              if(count($kgrades)) {
				                  foreach ($kgrades as $grade) {
				                      if($grade->points <= round($oposition['tp']/7,0) && $grade->points >= round($oposition['tp']/7,0)) {
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
					      $avmarks=round(($totalmarks/count($transcriptss)),0);
				           $kgrades = $this->grade_m->get_overal_performance_grading();
				              if(count($kgrades)) {
				                  foreach ($kgrades as $grade) {
				                      if($grade->gradeFrom <= $avmarks && $grade->gradeTo >= $avmarks) {
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
	public function alltranscripts() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$classID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$examID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			$year = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(5)));
			$termID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(6)));
			if((int)$classID && (int)$examID) {
				$termss= $this->exam_m->get_term();
				$examss = $this->exam_m->get_exam_name($examID);
				foreach ($termss as $value) {
					$term=$value->term_name;
				}
				foreach ($examss as $value) {
					$exam=$value->exam;
				}
				$termm=$this->reportforms_m->get_term_name($termID);
				$this->data['term_name'] = $termm->term_name;
				$this->data["exam"]=$exam;
				$this->data["term"]=$term;
				$this->data['set'] = $classID;
				$this->data['classID'] = $classID;
				$this->data['examID'] = $examID;
				//$this->data["grades"] = $this->grade_m->get_grade();
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["classess"] = $this->student_m->get_class($classID);
				$subjects = $this->ranking_m->get_subject($classID);
				$this->data["exams"] = $this->exam_m->get_exam();
				$class=$this->ranking_m->get_class($classID);
				foreach ($class as $keyvalue) {
					$class=$keyvalue->classes;
				}
				if ($classID==13 || $classID==14) {
					$student=$this->ranking_m->gets_sstudents($classID,$examID,$year,$this->data['term_name']);
				}else{
				 $student= $this->ranking_m->get_students_average($examID,$classID,$year,$this->data['term_name']);
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
				    $this->pdf->Cell(0,6,$this->data['term_name']."/".$exam."/".date("Y"),0,1,'C');
				    $this->pdf->ln(10);
				    $this->pdf->Image(base_url('uploads/images/site.png'),10,30,30);
				    $this->pdf->Image(base_url('uploads/images/'.$key->photo),170,30,30);
				    $this->pdf->Cell(70,6,$key->name,0,0,'L');
				    $this->pdf->Cell(50,6,"CLass ".$class,0,0,'C');
				    $this->pdf->Cell(0,6,"Adm No  ".$key->roll,0,1,'R');
				    $oposition=$this->ranking_m->get_overall_position($examID,$classID,$key->roll,$year,$this->data['term_name']);
				    $cposition=$this->ranking_m->get_class_position($examID,$classID,$key->sectionID,$key->roll,$year,$this->data['term_name']);
				    $transcriptss= $this->transcripts_m->get_order_by_transcripts_with_highest_mark($classID,$key->studentID,$examID,$year,$this->data['term_name']);
					$header = array('Code', 'Subject Name', 'Score', 'Remarks','Initials');
					$this->FancyTable($header,$student,$classID,$transcriptss,$cposition,$oposition);
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
				$this->data["subview"] = "transcripts/search";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}
	function FancyTable($header,$student,$classID,$transcriptss,$cposition,$oposition)
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
				           $kgrades = $this->grade_m->get_overal_performance_grading();
				              if(count($kgrades)) {
				                  foreach ($kgrades as $grade) {
				                      if($grade->points <= round($oposition['tp']/7,0) && $grade->points >= round($oposition['tp']/7,0)) {
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
					              	if (is_numeric($transcripts->mark)) {
					              		if($grade->gradefrom <= $transcripts->mark && $grade->gradeupto >= $transcripts->mark) {
						                  	$this->pdf->Cell($w[2],6,$transcripts->mark.' '.$grade->grade,'LR',0,'L',$fill);
						                  	$this->pdf->SetFont('','I');
						                      if ($transcripts->sc ==102) {
						                         $this->pdf->Cell($w[3],6,$this->lang->line('transcripts_'.$grade->note),'LR',0,'L',$fill);
						                      }else{
						                          $this->pdf->Cell($w[3],6,$grade->note,'LR',0,'L',$fill);
						                      }
						                  }
					              	}else{
					              		if ($transcripts->mark==="X") {
					              			$this->pdf->Cell($w[2],6,'X','LR',0,'L',$fill);
						                  	$this->pdf->SetFont('','I');
						                    $this->pdf->Cell($w[3],6,'Not Done','LR',0,'L',$fill);
					              		}else if($transcripts->mark==="x"){
					              			$this->pdf->Cell($w[2],6,'X','LR',0,'L',$fill);
						                  	$this->pdf->SetFont('','I');
						                    $this->pdf->Cell($w[3],6,'Not Done','LR',0,'L',$fill);
					              		}else if($transcripts->mark==="n"){
					              			$this->pdf->Cell($w[2],6,' ','LR',0,'L',$fill);
						                  	$this->pdf->SetFont('','I');
						                    $this->pdf->Cell($w[3],6,'Not Taking','LR',0,'L',$fill);
					              		}else if($transcripts->mark===""){
					              			$this->pdf->Cell($w[2],6,'-','LR',0,'L',$fill);
						                  	$this->pdf->SetFont('','I');
						                    $this->pdf->Cell($w[3],6,'Missing Mark','LR',0,'L',$fill);
					              		}else if($transcripts->mark==="N"){
					              			$this->pdf->Cell($w[2],6,'','LR',0,'L',$fill);
						                  	$this->pdf->SetFont('','I');
						                    $this->pdf->Cell($w[3],6,'Not Taking','LR',0,'L',$fill);
					              		}else{
					              			$this->pdf->Cell($w[2],6,$transcripts->mark,'LR',0,'L',$fill);
						                  	$this->pdf->SetFont('','I');
						                    $this->pdf->Cell($w[3],6,'Undefined','LR',0,'L',$fill);
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
					      $avmarks=round(($totalmarks/count($transcriptss)),0);
				           $kgrades = $this->grade_m->get_overal_performance_grading();
				              if(count($kgrades)) {
				                  foreach ($kgrades as $grade) {
				                      if($grade->gradeFrom <= $avmarks && $grade->gradeTo >= $avmarks) {
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
				$html = $this->load->view('transcripts/alltranscripts1', $this->data, true);
				$this->html2pdf->html($html);
				$this->html2pdf->create();
			} else {
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["exams"] = $this->exam_m->get_exam();
				$this->data["subview"] = "transcripts/search";
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
				$this->data["subview"] = "transcripts/search";
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
					$html = $this->load->view('transcripts/print_preview', $this->data, true);
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

	function transcripts_list() {
		$classID = $this->input->post('classesID');
		$examID = $this->input->post('examID');
		$year = $this->input->post('year');
		$termID = $this->input->post('termID');
		if((int)$classID && (int)$examID && (int)$year && (int)$termID) {
			$string = base_url("transcripts/index/$classID/$examID/$year/$termID");
			echo $string;
		} else {
			redirect(base_url("transcripts/index"));
		}
	}

	function stream_list() {
		$sectionID = $this->input->post('sectionID');
		$classID = $this->input->post('classesID');
		$examID = $this->input->post('examID');
		if((int)$sectionID && (int)$classID && (int)$examID) {
			$string = base_url("transcripts/stream/$sectionID/$classID/$examID");
			echo $string;
		} else {
			redirect(base_url("transcripts/filter"));
		}
	}

	function student_list() {
		$studentID = $this->input->post('id');
		if((int)$studentID) {
			$string = base_url("transcripts/index/$studentID");
			echo $string;
		} else {
			redirect(base_url("transcripts/index"));
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
