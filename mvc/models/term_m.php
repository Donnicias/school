<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class term_m extends MY_Model {

	protected $_table_name = 'terms';
	protected $_primary_key = 'term_id';
	protected $_order_by = "term_name";

	function __construct() {
		parent::__construct();
	}

	function get_username($table, $data=NULL) {
		$query = $this->db->get_where($table, $data);
		return $query->result();
	}

	function get_term($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_active_term($term_id) {
		$query = $this->db->get_where('terms',$term_id);
		return $query->result();
	}
	function get_active_term1($term_id) {
		$query = $this->db->get_where('terms',$term_id);
		return $query->row_array();
	}

	function insert_term($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_term($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	function delete_term($id){
		parent::delete($id);
	}

	function hash($string) {
		return parent::hash($string);
	}	
}

/* End of file systemadmin_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/systemadmin_m.php */