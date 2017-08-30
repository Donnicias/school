<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Examanalysis_m extends MY_Model {

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

	function get_order_by_exam($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_class_performance($classesID,$examID) {
		$this->db->select_avg('mark')->from('mark')->where(array('examID'=>$examID,'classesID'=>$classesID));
		$query = $this->db->get();
		return $query->result();
	}
	function get_grade_count($minvalue,$maxvalue,$classesID,$examID){
		$this->db->select('*')->from('mark')->where("mark BETWEEN '$minvalue' AND '$maxvalue' AND classesID ='$classesID' AND examID='$examID'");
		$query=$this->db->get();
		return $query->result();
	}
}

/* End of file exam_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/exam_m.php */