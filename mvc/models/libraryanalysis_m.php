<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class libraryanalysis_m extends MY_Model {

	protected $_table_name = 'issue';
	protected $_primary_key = 'issueID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "issueID asc";

	function __construct() {
		parent::__construct();
	}

	function get_issue($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_single_issue($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}


	function get_order_by_issue($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_issue($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_issue($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_issue($id){
		parent::delete($id);
	}

	function get_students_total_borrowing($studentID){
		$this->db->select("count(*) as total_borrowing")->from("issue")->where(array("lmember.studentID"=>$studentID))->join("lmember","issue.lID = lmember.lID");
		$query=$this->db->get();
		return $query->row();
	}

	function get_students_total_lost($studentID){
		$this->db->select("count(*) as total_lost")->from("lost_books_history")->where(array("lmember.studentID"=>$studentID))->join("lmember","lost_books_history.IID = lmember.lID");
		$query=$this->db->get();
		return $query->row();
	}

	function get_school_total_books(){
		$this->db->select("count(*) as total_books")->from("book");
		$query=$this->db->get();
		return $query->row();
	}

	function get_school_available_books(){
		$this->db->select("count(*) as available")->from("book")->where("status = 0");
		$query=$this->db->get();
		return $query->row();
	}

	function get_school_current_borrowing(){
		$this->db->select("count(*) as borrowed")->from("book")->where("status = 1");
		$query=$this->db->get();
		return $query->row();
	}

	function get_school_total_lost(){
		$this->db->select("count(*) as lost")->from("book")->where("status = 99");
		$query=$this->db->get();
		return $query->row();
	}

	function get_students_total_lost_but_found($studentID){
		$this->db->select("count(*) as total_lost_bt_found")->from("lost_books_history")->where(array("lmember.studentID"=>$studentID))->where("lost_books_history.found_status = 1")->join("lmember","lost_books_history.IID = lmember.lID");
		$query=$this->db->get();
		return $query->row();
	}

	function get_students_current_borrowing($studentID){
		$this->db->select("count(*) as current_borrowing")->from("issue")->where(array("lmember.studentID"=>$studentID))->where("issue.return_date IS NULL")->join("lmember","issue.lID = lmember.lID");
		$query=$this->db->get();
		return $query->row();
	}

	function fine($data) {
		$alldata = array();
		$r = array();
		$like = "";
		$temp_data = $this->db->query("SELECT * FROM issue");

		
		if($temp_data) {
			$db_data = $temp_data->result();

			foreach ($db_data as $value) {
				$alldata[] = $value->return_date;
				$likes = explode('-', $value->return_date);
			}
			return $alldata;
		}
	}
}

/* End of file libraryanalysis_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/libraryanalysis_m.php */