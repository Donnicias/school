<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marksheets extends Admin_Controller {
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
		$this->load->model("marksheets_m");
		$this->load->model("classes_m");
		$this->load->model("student_m");
		$this->load->model("mark_m");
		$this->load->model("term_m");
		$this->load->model("grade_m");
		$this->load->model("exam_m");
		$this->load->model("subject_m");
		$this->load->model("user_m");
		$this->load->model("section_m");
		$this->load->model("parentes_m");
		$this->load->library('fpdf/pdf'); // Load library
		$language = $this->session->userdata('lang');
		$this->lang->load('marksheets', $language);	
	}

	public function index() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$this->data['students'] = array();
			$this->data['set_exam'] = 0;
			$this->data['set_classes'] = 0;
			$this->data['set_subject'] = 0;
			$this->data['set_section'] = 0;
			$classesID = $this->input->post("classesID");
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
			$this->data['classes'] = $this->classes_m->get_classes();
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data["subview"] = "marksheets/index";
					$this->load->view('_layout_main', $this->data);
				} else {
					$examID = $this->input->post('examID');
					$classesID = $this->input->post('classesID');
					$subjectID = $this->input->post('subjectID');
					$sectionID = $this->input->post('sectionID');
					$this->data['set_exam'] = $examID;
					$this->data['set_classes'] = $classesID;
					$this->data['set_subject'] = $subjectID;
					$this->data['set_section'] = $sectionID;

					$exam = $this->exam_m->get_exam($examID);
					$subject = $this->subject_m->get_subject($subjectID);
					$year = date("Y");
					$students = $this->student_m->get_order_by_student(array("classesID" => $classesID));
					$sections=$this->section_m->get_allsection($classesID);

					$this->data["subview"] = "marksheets/index";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "marksheets/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("mark_exam"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_exam'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("mark_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_classes'
			),
			array(
				'field' => 'subjectID',
				'label' => $this->lang->line("mark_subject"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_subject'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("mark_section"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_section'
			)
		);
		return $rules;
	}
	function sectioncall(){
		$usertype = $this->session->userdata("usertype");
		$id = $this->input->post('id');
		if($usertype == "Admin") {
				$allsections =$this->section_m->get_allsection($id);
				echo "<option value='0'>", $this->lang->line("mark_select_section"),"</option>";
				foreach ($allsections as $value) {
					echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
				}
			} elseif($usertype == "Teacher") {
				$allsections =$this->section_m->get_allsection($id);
				echo "<option value='0'>", $this->lang->line("mark_select_section"),"</option>";
				foreach ($allsections as $value) {
					echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
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
	function check_section() {
		if($this->input->post('sectionID') == 0) {
			$this->form_validation->set_message("check_section", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}
	function print_preview() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Teacher") {
			$classesID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			$sectionID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(4)));
			$examID = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(5)));
			if ((int)$examID && (int)$classesID) {
				if (is_numeric($sectionID)) {
					$section= $this->section_m->get_single_section(array('sectionID'=>$sectionID));
					$students= $this->student_m->get_order_by_studen_with_section($classesID, $sectionID);
					$exams= $this->exam_m->get_single_exam(array('examID'=>$examID));
					$classes= $this->classes_m->get_single_class(array('classesID'=>$classesID));
					$subjects = $this->subject_m->get_single_subject(array('classesID'=>$classesID));
					$term=$this->term_m->get_active_term1(array('term_status' =>1));
					if($exams && $classes  && $subjects && $students && $section) {
						$this->pdf->AddPage();
						//$this->pdf->Image(base_url('uploads/images/header.png'),10,10,190);
					    $this->pdf->SetFont('Arial','B',10);
					    $this->pdf->ln(25);
					    $this->pdf->Cell(0,6,"KIARENI E.L.C.K MIXED SECONDARY SCHOOL",0,1,'C');
					    $this->pdf->SetFont('Arial','B',9);
					    $this->pdf->Cell(0,6,"P.O. BOX 1467-40200, KISII",0,1,'C');
					    $this->pdf->Cell(0,6,"Official Marksheet",0,1,'C');
					    $this->pdf->Cell(0,6,$section->section.'/'.$term['term_name']."/".$exams['exam']."/".date("Y"),0,1,'C');
					    $this->pdf->Image(base_url('uploads/images/site.png'),95,10,20);
					    //print_r($cposition);
						$header = array('#','Adm No', 'Student Name');
						foreach ($subjects as $key) {
							array_push($header, $key->subject);
						}
						$this->FancyTable($header,$students,$subjects);
						$this->pdf->Output();
					} else {
						$this->data["subview"] = "marksheets/index";
						$this->load->view('_layout_main', $this->data);
					}

				}else if ($sectionID==="all") {
					$exams= $this->exam_m->get_single_exam(array('examID'=>$examID));
					$classes = $this->classes_m->get_single_class(array('classesID'=>$classesID));
					$subjects= $this->subject_m->get_single_subject(array('classesID'=>$classesID));
					$term=$this->term_m->get_active_term1(array('term_status' =>1));
					$students= $this->student_m->get_order_by_studen_with_section_and_classes($classesID);
					if($exams && $classes  && $subjects && $students) {
						$this->pdf->AddPage();
						//$this->pdf->Image(base_url('uploads/images/header.png'),10,10,190);
					    $this->pdf->SetFont('Arial','B',10);
					    $this->pdf->ln(25);
					    $this->pdf->Cell(0,6,"KIARENI E.L.C.K MIXED SECONDARY SCHOOL",0,1,'C');
					    $this->pdf->SetFont('Arial','B',9);
					    $this->pdf->Cell(0,6,"P.O. BOX 1467-40200, KISII",0,1,'C');
					    $this->pdf->Cell(0,6,"Official Marksheet",0,1,'C');
					    $this->pdf->Cell(0,6,$classes['classes'].'/'.$term['term_name']."/".$exams['exam']."/".date("Y"),0,1,'C');
					    $this->pdf->Image(base_url('uploads/images/site.png'),95,10,20);
					    //print_r($cposition);
						$header = array('#','Adm No', 'Student Name');
						foreach ($subjects as $key) {
							array_push($header, $key->subject);
						}
						$this->FancyTable($header,$students,$subjects);

						$this->pdf->Output();
					} else {
						$this->data["subview"] = "marksheets/index";
						$this->load->view('_layout_main', $this->data);
					}
				}else{

					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
				
			}else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}
	function Footer()
		{
		    // Go to 1.5 cm from bottom
		    $this->pdf->SetY(-15);
		    // Select Arial italic 8
		    $this->pdf->SetFont('Arial','I',8);
		    // Print current and total page numbers
		    $this->pdf->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		}
	function FancyTable($header,$students,$subjects)
				{
				    $this->pdf->SetFillColor(160,160,160);
				    $this->pdf->SetTextColor(255);
				    $this->pdf->SetDrawColor(192,192,192);
				    $this->pdf->SetLineWidth(.3);
				    $this->pdf->SetFont('Arial','B',8);
				    // Header
				    $fill = false;
				    $w = array(7,15, 42);
				    foreach ($subjects as $key) {
				    	array_push($w, 11);
				    }
				    for($i=0;$i<count($header);$i++)
				        $this->pdf->Cell($w[$i],5,$header[$i],1,0,'L',true);
				    $this->pdf->Ln();
				    // Color and font restoration
				    $this->pdf->SetFillColor(224,235,255);
				    $this->pdf->SetTextColor(0);
				    $this->pdf->SetFont('');
				    // Data
				    $i=1;
				    $this->pdf->SetFont('Arial','',7);
					if ($students) {
					      foreach ($students as $student) {
					      	$this->pdf->Cell($w[0],4,$i,'LR',0,'L',$fill);
					      	$this->pdf->Cell($w[1],4,$student->roll,'LR',0,'L',$fill);
					        $this->pdf->Cell($w[2],4,$student->name,'LR',0,'L',$fill);
					          if(count($subjects)) {
					              foreach ($subjects as $subject) {
				                  	$this->pdf->Cell($w[3],4,"",'LR',0,'L',$fill);
					              }
					        	}
					        $this->pdf->SetFont('','B');
					        $this->pdf->Ln();
					        $fill = !$fill;
					        $i++;
					      }
					  }
				    // Closing line
				    $this->pdf->Cell(array_sum($w),0,'','T');
				}
	function marksheets_list() {
		$classID = $this->input->post('classesID');
		$sectionID = $this->input->post('sectionID');
		$examID = $this->input->post('examID');
		if((int)$classID && (int)$examID) {
			$string = base_url("marksheets/print_preview/$classID/$sectionID/$examID");
			echo $string;
		} else {
			redirect(base_url("transcripts/index"));
		}
	}

}