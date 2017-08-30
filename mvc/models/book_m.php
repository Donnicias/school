<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Book_m extends MY_Model {

	protected $_table_name = 'book';
	protected $_primary_key = 'bookID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "bookID desc";

	function __construct() {
		parent::__construct();
	}

	function get_book($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_book($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_order_by_book_overal_analysis() {
		$this->db->select('*')->from("book")->group_by("book")->order_by("book asc");
		$query=$this->db->get();
		return $query->result();
	}

	function get_order_by_book_overal_analysis_pie_chart_data() {
		$this->db->select('(SELECT count(*) from book where status=0) as available,(select count(*) from book where status=1) as borrowed,(select count(*) from book where status=99) as lost,(select count(*) from book) as registered')->from("book");
		$query=$this->db->get();
		return $query->row();
	}

	function get_order_by_book_statistics($book) {
		$this->db->select('(SELECT count(*) from book where status=0 AND book="'.$book.'") as available,(select count(*) from book where status=1 AND book="'.$book.'") as borrowed,(select count(*) from book where status=99 AND book="'.$book.'") as lost,(select count(*) from book where book="'.$book.'") as registered')->from("book");
		$query=$this->db->get();
		return $query->row();
	}

	function lost_books($book) {
		$this->db->select('count(*) as no')->from("book")->where(array("status"=>99,"book"=>$book));
		$query=$this->db->get();
		return $query->row();
	}

	function registered_books($book) {
		$this->db->select('count(*) as no')->from("book")->where(array("book"=>$book));
		$query=$this->db->get();
		return $query->row();
	}

	function available_books($book) {
		$this->db->select('count(*) as no')->from("book")->where(array("status"=>0,"book"=>$book));
		$query=$this->db->get();
		return $query->row();
	}

	function borrowed_books($book) {
		$this->db->select('count(*) as no')->from("book")->where(array("status"=>1,"book"=>$book));
		$query=$this->db->get();
		return $query->row();
	}

	function get_single_book($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_book($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_book($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_book($id){
		parent::delete($id);
	}

	function allbook($book) {
		$query = $this->db->query("SELECT * FROM book WHERE book LIKE '$book%'");
		return $query->result();
	}

	function allauthor($author) {
		$query = $this->db->query("SELECT * FROM book WHERE author LIKE '$author%'");
		return $query->result();
	}
}

/* End of file book_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/book_m.php */