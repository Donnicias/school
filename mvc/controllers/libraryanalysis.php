<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Libraryanalysis extends Admin_Controller {
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
		$this->load->model("lmember_m");
		$this->load->model("book_m");
		$this->load->model("section_m");
		$this->load->model("libraryanalysis_m");
		$this->load->model("data_books_statistics_m");
		$this->load->model("student_m");
		$this->load->model("parentes_m");
		
		$language = $this->session->userdata('lang');
		$this->lang->load('libraryanalysis', $language);	
	}

	public function general() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Librarian") {
			$this->data['books'] = $this->book_m->get_order_by_book_overal_analysis();
			$this->data["subview"] = "libraryanalysis/index";
			$this->load->view('_layout_main', $this->data);
		}else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}	
	}

	public function member_statistics() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Librarian") {
			$this->data['sections_data']=$this->student_m->get_member_details_per_stream();
			$this->data["subview"] = "libraryanalysis/member_statistics";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function member_statistics_graph_data() {
		$student_data=array();
		$data = $this->student_m->get_member_details_per_stream();
		if(count($data)){
            foreach($data as $datas) { 
                $membership_data=$this->student_m->get_registered_members_per_stream($datas->stream);
                $student_data[] = array(
				    'stream' => $datas->stream,
				    'totalStudents' => $datas->total_students,
				    'totalMembers' => $membership_data->registered
				  );
                
            }
             echo json_encode($student_data);
        }
	}

	function books_statistics_graph_data() {
		$book_data=array();
		$books = $this->book_m->get_order_by_book_overal_analysis();
		if(count($books)){
            foreach($books as $book) { 
                $statistics=$this->book_m->get_order_by_book_statistics($book->book);
                $book_data[] = array(
				    'book' => $book->book,
				    'Registered' => $statistics->registered,
				    'Left' => round($statistics->registered-$statistics->lost-$statistics->borrowed,0)
				  );
                
            }
             echo json_encode($book_data);
        }
	}

	function books_statistics_graph_data_pie_chart_data() {
		$book_data=array();
		$books = $this->book_m->get_order_by_book_overal_analysis_pie_chart_data();
		if(count($books)){
			foreach($books as $book => $value) { 
                $book_data[] = array(
				    'categoryy' => $book,
				    'valuee'=>$value
				  );
            }
            echo json_encode($books);
        }
	}

	function books_statistics_graph_data1() {
		$book_data=array();
		$books = $this->book_m->get_order_by_book_overal_analysis();
		if(count($books)){
			print_r($books);
            foreach($books as $book) { 
                $book_data[] = array(
				    'Registered' => $book->registered
				  );
                
            }
             echo json_encode($book_data);
        }
	}

	function lostbooks() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Librarian") {
			$this->data["bookslost"] = $this->data_books_statistics_m->get_lost_books_per_day();
			$this->data["subview"] = "libraryanalysis/lostbooks";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	function lost_books_graph_data() {
		$bookslost = $this->data_books_statistics_m->get_lost_books_per_day();
            echo json_encode($bookslost);
	}

	function lostandfound() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Librarian") {
			$this->data["bookslost"] = $this->data_books_statistics_m->get_lost_books_per_day();
			$this->data['booksfound'] =$this->data_books_statistics_m->get_lost_and_found_books_per_day();
			$this->data["totalbookslost"] = $this->data_books_statistics_m->get_total_lost_books();
			$this->data['totalbooksfound'] =$this->data_books_statistics_m->get__total_lost_and_found_books();
			$this->data["subview"] = "libraryanalysis/lostandfound";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	function lostandfoundbooks_graph_data() {
		$booksnotfound = $this->data_books_statistics_m->get_total_not_found_books();
		$booksfound =$this->data_books_statistics_m->get__total_lost_and_found_books();
		$bookslost =$this->data_books_statistics_m->get_total_lost_books();


		//$combined_data=array_merge_recursive($bookslost,$booksfound);
		
		 echo json_encode($booksfound)."<br/>";
		echo json_encode($bookslost)."<br/>";
		echo json_encode($booksnotfound)."<br/>";
	}

	public function borrowing() {
		$usertype = $this->session->userdata("usertype");
		if($usertype == "Admin" || $usertype == "Librarian") {
			$id = htmlentities(mysqli_real_escape_string($this->db->conn_id,$this->uri->segment(3)));
			if((int)$id) {
				$this->data['set'] = $id;
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data['students'] = array();
				$students = $this->student_m->get_order_by_roll(array('classesID' => $id));
				foreach ($students as $key => $student) {
					$section = $this->section_m->get_section($student->sectionID);
					if($section) {
						$this->data['students'][$key] = (object) array_merge( (array)$student, array('ssection' => $section->section));
					} else {
						$this->data['students'][$key] = (object) array_merge( (array)$student, array('ssection' => $student->section));
					}
				}
				$this->data["subview"] = "libraryanalysis/borrowing";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['classes'] = $this->student_m->get_classes();
				$this->data["subview"] = "libraryanalysis/search";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function student_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("libraryanalysis/borrowing/$classID");
			echo $string;
		} else {
			redirect(base_url("libraryanalysis/borrowing"));
		}
	}
}

/* End of file issue.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/issue.php */