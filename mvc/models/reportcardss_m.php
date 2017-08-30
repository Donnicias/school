<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reportcardss_m extends MY_Model {

	protected $_table_name = 'mark';
	protected $_primary_key = 'markID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "subject asc";

	function __construct() {
		parent::__construct();
	}

	function get_mark($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_terms(){
		$this->db->select("*")->from("terms");
		$this->db->order_by('term_id','asc');
		$query = $this->db->get();
		return $query->result();
	}

	function get_term_id($array_key){
		$this->db->select("term_id")->from("terms")->where($array_key);
		$query = $this->db->get();
		return $query->row();
	}

	function get_dates($array){
		$this->db->select("*")->from("report_form_settings")->where($array);
		$query = $this->db->get();
		return $query->row();
	}

	function get_exams($array){
		$this->db->select("examsID")->from("report_form_settings")->where($array);
		$query = $this->db->get();
		return $query->result();
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

	function update_transcripts_classes($array, $id) {
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

	function get_order_by_transcripts_with_subject($classes,$year) {
		$this->db->select('*');
		$this->db->from('subject');
		$this->db->join('mark', 'subject.subjectID = mark.subjectID', 'LEFT');
		$this->db->join('exam', 'exam.examID = mark.examID');
		$this->db->where('mark.classesID', $classes);
		$this->db->where('mark.year', $year);
		$query = $this->db->get();
		return $query->result();
	}

	function get_order_by_transcripts_with_highest_mark($classID,$studentID,$examID) {
		$this->db->select('S.subjectID as si,S.subject_code as sc,T.initials as initials,M.markID,M.examID, M.exam, M.subjectID, S.fullsubjectname as subject, M.studentID, M.classesID,  
			round((M.mark/out_of)*100) as mark, M.year, (
		SELECT Max((mark.mark/mark.out_of)*100)
		FROM mark
		WHERE mark.subjectID = M.subjectID
		AND mark.examID = M.examID AND mark.mark != "n"
		) highestmark');
		$this->db->from('exam E');
		$this->db->join('mark M', 'M.examID = E.examID', 'LEFT');
		$this->db->join('subject S', 'M.subjectID = S.subjectID');
		$this->db->join('teacher T', 'S.teacherID = T.teacherID');
		$this->db->where('M.classesID', $classID);
		$this->db->where('M.studentID', $studentID);
		$this->db->where('M.examID', $examID);
		$this->db->where('M.mark !="n"');
		$this->db->order_by('sc asc');
		$query = $this->db->get();
		return $query->result();
	}
	function get_order_by_transcripts_with_highest_mark_end_term($classID,$studentID,$examID,$term,$year) {
		$this->db->select('Ti.name as teacher,St.section as section,pm.term_name,pm.year,pm.studentID,S.subjectID as si,S.subject_code as sc,T.initials as initials,pm.scoreIndex as markID, pm.subjectID, S.fullsubjectname as subject, pm.studentID, pm.classesID,  
			avg(pm.score) as mark, pm.year');
		$this->db->from('processed_mark pm');
		$this->db->where(array('pm.classesID'=>$classID,'pm.term_name'=>$term,'pm.classesID'=>$classID,'pm.year'=>$year,'pm.score !='=>"n",'pm.studentID'=>$studentID));
		$this->db->where('pm.examID IN('.$examID.')');
		$this->db->join('subject S', 'pm.subjectID = S.subjectID', 'LEFT');
		$this->db->join('teacher T', 'S.teacherID = T.teacherID', 'LEFT');
		$this->db->join('student St', 'pm.studentID = St.studentID', 'LEFT');
		$this->db->join('section Sec', 'St.sectionID = Sec.sectionID', 'LEFT');
		$this->db->join('teacher Ti', 'Sec.teacherID = Ti.teacherID', 'LEFT');
		$this->db->order_by('sc asc');
		$this->db->group_by('sc');
		$query = $this->db->get();
		return $query->result();
	}
	function get_total_marks($classID,$studentID,$examID,$term,$year){
		$tmark=0;
		$resultset=$this->get_order_by_transcripts_with_highest_mark_end_term($classID,$studentID,$examID,$term,$year);
		foreach ($resultset as $value) {
			$tmark+=round($value->mark,0);
		}
		return $tmark;
	}
	function get_principal(){
		$this->db->select("*")->from("teacher")->where("designation","Principal");
		$query=$this->db->get();
		return $query->row();
	}
}

/* End of file transcripts_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/transcripts_m.php */
