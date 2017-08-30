<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Examoutofsettings_m extends MY_Model {

	protected $_table_name = 'mark';
	protected $_primary_key = 'markID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "subject asc";

	function __construct() {
		parent::__construct();
	}

	function get_mark($classesID,$examID,$term_name,$year) {
		$this->db->select('*')->from('mark')->where(array("term_name"=>$term_name,"year"=>$year,"classesID"=>$classesID,"examID"=>$examID))->group_by('subject');
		$query = $this->db->get();
		return $query->result();
	}

	function get_subject_data($classesID,$subjectID,$examID,$term_name,$year) {
		$this->db->select('classes.classes as class, mark.subject as subject, mark.subjectID as subjectID, mark.classesID as classesID,mark.out_of as subjectMark,mark.year as year,mark.term_name as term_name,mark.examID as examID,mark.mark as mark')->from('mark');
		$this->db->join("classes","classes.classesID=mark.classesID");
		$this->db->where("mark.classesID",$classesID);
		$this->db->where("mark.subjectID",$subjectID);
		$this->db->where("mark.examID",$examID);
		$this->db->where("mark.term_name",$term_name);
		$this->db->where("mark.year",$year);
		$this->db->group_by('mark.subject');
		$query = $this->db->get();
		return $query->result();
	}


	function update_out_of_mark($array,$subjectMark)
	{
		$this->db->update("mark",$subjectMark,$array);
		return true;
	}
	function delete_out_of_mark($array)
	{
		if($this->db->delete("mark",$array)){
		return true;}else{
			return false;
		}
	}
	function get_order_by_mark($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_mark($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_mark($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	function update_mark_classes($array, $id) {
		$this->db->update($this->_table_name, $array, $id);
		return $id;
	}

	public function delete_mark($id){
		parent::delete($id);
	}

	function sum_student_subject_mark($studentID, $classesID, $subjectID) {
		$array = array(
			"studentID" => $studentID,
			"classesID" => $classesID,
			"subjectID" => $subjectID
		);
		$this->db->select_sum('mark');
		$this->db->where($array);
		$query = $this->db->get('mark');
		return $query->row();
	}

	function count_subject_mark($studentID, $classesID, $subjectID) {
		$query = "SELECT COUNT(*) as 'total_semester' FROM mark WHERE studentID = $studentID && classesID = $classesID && subjectID = $subjectID && (mark != '' || mark <= 0 || mark >0)";
	    $query = $this->db->query($query);
	    $result = $query->row();
	    return $result;
	}

	function get_order_by_mark_with_subject($classes,$year) {
		$this->db->select('*');
		$this->db->from('subject');
		$this->db->join('mark', 'subject.subjectID = mark.subjectID', 'LEFT');
		$this->db->join('exam', 'exam.examID = mark.examID');
		$this->db->where('mark.classesID', $classes);
		$this->db->where('mark.year', $year);
		$query = $this->db->get();
		return $query->result();
	}

	function get_order_by_mark_with_highest_mark($classID,$studentID) {
		$this->db->select('M.markID,M.examID, M.exam, M.subjectID, M.subject, M.studentID, M.classesID,  M.mark, M.year, (
		SELECT Max( mark.mark )
		FROM mark
		WHERE mark.subjectID = M.subjectID
		AND mark.examID = M.examID
		) highestmark');
		$this->db->from('exam E');
		$this->db->join('mark M', 'M.examID = E.examID', 'LEFT');
		$this->db->join('subject S', 'M.subjectID = S.subjectID');
		$this->db->where('M.classesID', $classID);
		$this->db->where('M.studentID', $studentID);
		$query = $this->db->get();
		return $query->result();
	}
}

/* End of file mark_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/mark_m.php */
