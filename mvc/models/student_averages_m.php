<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student_averages_m extends MY_Model {

	protected $_table_name = 'form3and4averages';
	protected $_primary_key = 'index';
	protected $_order_by = "average desc";

	function __construct() {
		parent::__construct();
	}
	function save_student_average($array){
		$error = parent::insert($array);
		return TRUE;
	}

	function save_student_end_term_average($array){
		$error = parent::insert($array);
		return TRUE;
	}

	function get_student_average($array){
		$query = $this->db->select('*')->from('form3and4averages')->where($array);
		$query = $this->db->get();
		return $query->result();
	}

	function get_student_end_term_average($array){
		$query = $this->db->select('*')->from('form3and4endterm')->where($array);
		$query = $this->db->get();
		return $query->result();
	}

	function get_order_by_student_average($array) {
		$this->db->select('s.name as name,s.roll as admno, f.roll as roll,f.examID as examID,f.average as avg,f.total_points as tp,f.subject_entry as se,f.overall_grade as og')->from('form3and4averages f')->where($array)->order_by('tp desc');
		$this->db->join('student s','f.roll=s.studentID');
		$query=$this->db->get();
		return $query->result();
	}

	function get_order_by_student_averages($array) {
		$this->db->select('s.name as name,s.roll as admno, f.roll as roll,f.examID as examID,f.average as avg,f.total_points as tp,f.subject_entry as se,f.overall_grade as og')->from('form3and4averages f')->where($array)->order_by('tp desc');
		$this->db->join('student s','f.roll=s.studentID');
		$this->db->join('mark m','m.studentID=f.studentID');
		$query=$this->db->get();
		return $query->result();
	}

	function update_student_average($data, $array) {
		$this->db->where($array);
		$this->db->update('form3and4averages',$data);
		return true;
	}

	function update_student_end_term_average($data, $array) {
		$this->db->where($array);
		$this->db->update('form3and4endterm',$data);
		return true;
	}
	// function update_student_averages($avg,$tp,$se,$og,$cid,$sid,$eid,$term,$yr) {
	// 	$query = $this->db->update('form3and4averages')->set('average=$avg,total_points=$tp,subject_entry=$se,overall_grade=$og')->where('classesID=$cid AND roll=$sid AND examID=$examID AND term=$term AND year=$yr');
	// 	return $query;
	// }
}