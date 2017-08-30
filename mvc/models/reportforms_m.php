<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reportforms_m extends MY_Model {

	protected $_table_name = 'report_form_settings';
	protected $_primary_key = 'rf_ID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "rf_ID asc";

	function __construct() {
		parent::__construct();
	}

	function get_settings($classesID) {
		$this->db->select('*')->from('report_form_settings');
		$this->db->join('terms','terms.term_id=report_form_settings.termID');
		$this->db->where('report_form_settings.classesID',$classesID);
		$query = $this->db->get();
		return $query->result();
	}

	function get_term_name($termID) {
		$this->db->select('*')->from('terms');
		$this->db->where('term_id',$termID);
		$query = $this->db->get();
		return $query->row();
	}

	function get_unique_term($rf_ID) {
		$this->db->select('*')->from('report_form_settings');
		$this->db->join('terms','terms.term_id=report_form_settings.termID');
		$this->db->where('report_form_settings.rf_ID',$rf_ID);
		$query = $this->db->get();
		return $query->row();
	}

	function get_years($classesID) {
		$this->db->select('*')->from('report_form_settings')->where(array('classesID'=>$classesID));
		$query = $this->db->get();
		return $query->result();
	}

	function get_examsID($classesID,$year) {
		$this->db->select('examsID')->from('report_form_settings')->where(array('classesID'=>$classesID,'year'=>$year));
		$query = $this->db->get();
		return $query->row();
	}

	function get_unique_term_id($classesID,$year,$term_id) {
		$this->db->select('*')->from('report_form_settings')->where(array('classesID'=>$classesID,'termID'=>$term_id,'year'=>$year));
		$query = $this->db->get();
		return $query->result();
	}

	function get_exams($examIDs) {
		$this->db->select('*')->from('exam')->where('examID IN ('.$examIDs.')');
		$query = $this->db->get();
		return $query->result();
	}

	function insert_setting($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_setting($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_setting($id){
		parent::delete($id);
	}
}

/* End of file transcripts_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/transcripts_m.php */