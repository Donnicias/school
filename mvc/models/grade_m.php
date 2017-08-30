<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grade_m extends MY_Model {

	protected $_table_name = 'grade';
	protected $_primary_key = 'gradeID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "point desc";

	function __construct() {
		parent::__construct();
	}

	function get_grade($array) {
		$this->db->select('*')->from('grade')->where($array)->order_by('point desc');
		$query = $this->db->get();
		return $query->result();
	}

	function get_overal_performance_grading() {
		$this->db->select('*')->from('overal_performance_grading');
		$query = $this->db->get();
		return $query->result();
	}

	function get_order_by_grade($array) {
		$this->db->select('*')->from('grade')->where($array)->order_by('point desc');
		$this->db->join('subject','subject.subjectID=grade.subjectID');
		$this->db->join('classes','classes.classesID=grade.classesID');
		$query = $this->db->get();
		return $query->result();
	}

	function insert_grade($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_grade($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_grade($id){
		parent::delete($id);
	}
}

/* End of file grade_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/grade_m.php */