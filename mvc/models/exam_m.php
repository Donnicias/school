<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exam_m extends MY_Model {

	protected $_table_name = 'exam';
	protected $_primary_key = 'examID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "exam asc";

	function __construct() {
		parent::__construct();
	}

	function get_exam($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_exam_name($examID) {
		$this->db->select("*")->from("exam")->where('`examID` = "'.$examID.'"');
		$query=$this->db->get();
		return $query->result();
	}

	function get_term() {
		$this->db->select("*")->from("terms")->where('`term_status` =1');
		$query=$this->db->get();
		return $query->result();
	}
	function get_single_exam($array) {
		$query = $this->db->get_where('exam', $array);
		return $query->row_array();
	}


	function get_order_by_exam($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_exam($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_exam($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_exam($id){
		parent::delete($id);
	}
}

/* End of file exam_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/exam_m.php */