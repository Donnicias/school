<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ranking_m extends MY_Model {

	protected $_table_name = 'examschedule';
	protected $_primary_key = 'examscheduleID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "classesID asc";

	function __construct() {
		parent::__construct();
	}

	function get_order_by_mark($examID,$classesID,$subjectID,$studentID) {
		$this->db->select('*')->from('mark')->where(array('examID'=>$examID,'classesID'=>$classesID,'studentID'=>$studentID,'subjectID'=>$subjectID))->order_by('mark asc');
		$query = $this->db->get();
		return $query;
	}
	function get_classes() {
		$this->db->select('*')->from('classes')->order_by('classes_numeric asc');
		$query = $this->db->get();
		return $query->result();
	}
	function getSectionDetails($array){
		// $query = $this->db->get('CALL `classranking`($classesID, $sectionID)');
		// return $query->result();

		// $result = $this->db->query( "CALL `classranking`(?" . str_repeat(",?", count($array)-1) . ")", $array)or die("<br/><br/>".mysql_error());
		// return $result->result();

		// $this->db->select("f.roll as roll, f.average as average,f.overall_grade as grade,f.subject_entry as se,
		// 	f.total_points as tp, @curRank := IF(@prevVal=f.total_points, @curRank, @studentNumber) AS rank, 
		// 	@studentNumber := @studentNumber + 1 as studentNumber, 
		// 	@prevVal:=f.total_points as preVal, s.name as name")->from("ssystem.form3and4averages f")->join('ssystem.student s',"s.studentID= f.roll
		// 				, (
		// 				SELECT @curRank :=0, @prevVal:=null, @studentNumber:=1, @percentile:=100
		// 				) r")->where("f.classesID='14' AND s.sectionID='9'")->order_by("BY tp DESC ");
		// 	$query=$this->db->get();
		// 	return $query;
	}
	function get_exam() {
		$this->db->select('*')->from('exam')->order_by('exam asc');
		$query = $this->db->get();
		return $query->result();
	}
	function get_student($classesID) {
		$query = $this->db->get_where('student', array('classesID' => $classesID));
		return $query->result();
	}
	function get_sstudent($classesID,$sectionID,$examID,$year,$term_name) {
		$this->db->select('student.kcpe_mark,student.name,student.roll, student.studentID, avg(score) as avg, sum(score) as studenttotal')->from('processed_mark')->where(array('processed_mark.examID'=>$examID,'processed_mark.year'=>$year,'processed_mark.term_name'=>$term_name,'processed_mark.classesID'=>$classesID,'student.sectionID'=>$sectionID))->group_by('processed_mark.studentID')->order_by('studenttotal desc');
		$this->db->join('student', 'student.studentID = processed_mark.studentID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_sstudent_end_term($classesID,$sectionID,$examID,$year,$term) {
		$this->db->select('student.kcpe_mark,student.name,student.roll, student.studentID, avg(pm.score) as avg, sum(pm.score) as studenttotal')->from('processed_mark pm');
		$this->db->where(array('pm.classesID'=>$classesID,'student.sectionID'=>$sectionID,'pm.year'=>$year,'pm.term_name'=>$term));
		$this->db->where('pm.examID IN ('.$examID.')');
		$this->db->group_by('pm.studentID');
		$this->db->order_by('avg desc');
		$this->db->join('student', 'student.studentID = pm.studentID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_sstudents($classesID,$sectionID,$examID,$year,$term_name) {
		$this->db->select('student.studentID,student.kcpe_mark,student.name,student.roll, student.studentID, f.total_points as tp,f.overall_grade as grade, f.subject_entry as se')->from('mark')->where(array('mark.examID'=>$examID,'mark.year'=>$year,'mark.term_name'=>$term_name,'mark.classesID'=>$classesID,'student.sectionID'=>$sectionID,'f.examID'=>$examID,'f.year'=>$year,'f.term'=>$term_name))->group_by('mark.studentID');
		$this->db->join('student', 'student.studentID = mark.studentID', 'LEFT');
		$this->db->join('form3and4averages f', 'f.roll = mark.studentID', 'LEFT');
		$this->db->order_by('tp desc');
		$query = $this->db->get();
		return $query->result();
	}

	function get_sstudents_end_term($classesID,$sectionID,$examID,$year,$term) {
		$this->db->select('student.studentID,student.kcpe_mark,student.name,student.roll, student.studentID,f.total_points as tp,f.overall_grade as grade, f.subject_entry as se')->from('mark');
		$this->db->where(array('mark.classesID'=>$classesID,'student.sectionID'=>$sectionID,'mark.year'=>$year,'mark.term_name'=>$term,'f.term_name'=>$term,'mark.year'=>$year,'f.year'=>$year));
		$this->db->where('mark.examID IN ('.$examID.')');
		$this->db->group_by('mark.studentID');
		$this->db->join('student', 'student.studentID = mark.studentID', 'LEFT');
		$this->db->join('form3and4endterm f', 'f.studentID = mark.studentID', 'LEFT');
		$this->db->order_by('tp desc');
		$query = $this->db->get();
		return $query->result();
	}

	function get_section($sectionID){
		$this->db->select('*')->from('section')->where(array('sectionID'=>$sectionID));
		$query = $this->db->get();
		return $query->result();
	}

	function get_class($classesID){
		$this->db->select('*')->from('classes')->where(array('classesID'=>$classesID));
		$query = $this->db->get();
		return $query->result();
	}

	function gets_sstudents($classesID,$examID,$year,$term_name) {
		$this->db->select('student.sectionID,student.kcpe_mark,student.photo,student.name,student.roll, student.studentID, f.total_points as tp,f.overall_grade as grade, f.subject_entry as se')->from('mark')->where(array('mark.examID'=>$examID,'mark.year'=>$year,'mark.term_name'=>$term_name,'mark.classesID'=>$classesID,'f.examID'=>$examID,'f.term'=>$term_name,'f.year'=>$year))->group_by('mark.studentID');
		$this->db->join('student', 'student.studentID = mark.studentID', 'LEFT');
		$this->db->join('form3and4averages f', 'f.roll = mark.studentID', 'LEFT');
		$this->db->order_by('tp desc');
		$query = $this->db->get();
		return $query->result();
	}

	function gets_sstudents_end_term($classesID,$examID,$year,$term) {
		$this->db->select('student.sectionID,student.kcpe_mark,student.photo,student.name,student.roll, student.studentID,f.total_points as tp,f.overall_grade as grade, f.subject_entry as se')->from('processed_mark pm');
		$this->db->where(array('pm.classesID'=>$classesID,'pm.year'=>$year,'pm.term_name'=>$term));
		$this->db->where('pm.examID IN ('.$examID.')');
		$this->db->where('f.subject_entry !=""');
		$this->db->group_by('pm.studentID');
		$this->db->join('student', 'student.studentID = pm.studentID', 'LEFT');
		$this->db->join('form3and4endterm f', 'f.studentID = pm.studentID', 'LEFT');
		$this->db->order_by('tp desc');
		$query = $this->db->get();
		return $query->result();
	}

	function gets_sstudentss($sectionID,$classesID,$examID) {
		$this->db->select('section.sectionID as section,student.photo,student.name,student.roll, student.studentID, f.total_points as tp,f.overall_grade as grade, f.subject_entry as se')->from('mark')->where(array('mark.examID'=>$examID,'section.sectionID'=>$sectionID,'mark.classesID'=>$classesID,'f.examID'=>$examID))->group_by('mark.studentID');
		$this->db->join('student', 'student.studentID = mark.studentID', 'LEFT');
		$this->db->join('section', 'section.sectionID = student.sectionID', 'LEFT');
		$this->db->join('form3and4averages f', 'f.roll = mark.studentID', 'LEFT');
		$this->db->order_by('tp desc');
		$query = $this->db->get();
		return $query->result();
	}

	function sciences_count($studentID,$examID,$classesID){
		$this->db->select('count(*) as total_s,subject.subjectID as subjectID')->from('subject')->where("`subject.classesID` ='$classesID' AND subject.subject_code IN(232,231,233) AND mark.studentID='$studentID' AND mark.examID='$examID' AND mark.mark !='n'");
		$this->db->join('mark','subject.subjectID=mark.subjectID');
		$query = $this->db->get();
		return $query->result();
	}

	function sciences_count_end_term($studentID,$examsID,$classesID){
		$this->db->select('*')->from('subject')->where("`subject.classesID` ='$classesID' AND subject.subject_code IN(232,231,233) AND mark.studentID='$studentID' AND mark.mark !='n'");
		$this->db->group_by('subject.subjectID');
		$this->db->join('mark','subject.subjectID=mark.subjectID');
		$this->db->where('mark.examID IN ('.$examsID.')');
		$query = $this->db->get();
		return $query->result();
	}

	function sciences_rcount($studentID,$examID,$classesID){
		$this->db->select('count(*) as total_s,subject.subjectID as subjectID')->from('subject')->where("`subject.classesID` ='$classesID' AND subject.subject_code IN(232,231,233) AND mark.studentID='$studentID' AND mark.examID='$examID' AND mark.mark !='n'");
		$this->db->join('mark','subject.subjectID=mark.subjectID');
		$query = $this->db->get();
		return $query->result();
	}

	function humanities_count($studentID,$examID,$classesID){
		$this->db->select('count(*) as total_h')->from('subject')->where("`subject.classesID` ='$classesID' AND subject.subject_code IN(311,313,312) AND mark.studentID='$studentID' AND mark.examID='$examID' AND mark.mark !='n'");
		$this->db->join('mark','subject.subjectID=mark.subjectID');
		$query = $this->db->get();
		return $query->result();
	}

	function humanities_count_end_term($studentID,$examsID,$classesID){
		$this->db->select('*')->from('subject')->where("`subject.classesID` ='$classesID' AND subject.subject_code IN(311,313,312) AND pm.studentID='$studentID' AND pm.score !='n'")->group_by('subject.subjectID');
		$this->db->join('processed_mark pm','subject.subjectID=pm.subjectID');
		$this->db->where('pm.examID IN ('.$examsID.')');
		$query = $this->db->get();
		return $query->result();
	}

	function humanities_rcount($studentID,$examID,$classesID){
		$this->db->select('count(*) as total_h')->from('subject')->where("`subject.classesID` ='$classesID' AND subject.subject_code IN(311,313,312) AND mark.studentID='$studentID' AND mark.examID='$examID' AND mark.mark !='n'");
		$this->db->join('mark','subject.subjectID=mark.subjectID');
		$query = $this->db->get();
		return $query->result();
	}

	function humanities_IDs($studentID,$examID,$classesID){
		$this->db->select('subject.subjectID as subjectID')->from('subject')->where("`subject.classesID` ='$classesID' AND subject.subject_code IN(311,313,312) AND mark.studentID='$studentID' AND mark.examID='$examID' AND mark.mark !='n'");
		$this->db->join('mark','subject.subjectID=mark.subjectID')->group_by('subjectID')->order_by('(mark.mark/mark.out_of)*100 asc')->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	function humanities_IDs_end_term($studentID,$examsID,$classesID,$year,$term_name){
		$this->db->select('subject.subjectID as subjectID,avg(pm.score) as mark')->from('subject')->where("`subject.classesID` ='$classesID' AND subject.subject_code IN(311,313,312) AND pm.studentID='$studentID' AND pm.year='$year' AND pm.term_name='$term_name' AND pm.score !='n'");
		$this->db->join('processed_mark pm','subject.subjectID=pm.subjectID')->group_by('subjectID')->order_by('mark asc')->limit(1);
		$this->db->where('pm.examID IN ('.$examsID.')');
		$query = $this->db->get();
		return $query->result();
	}

function humanities_rIDs($studentID,$examID,$classesID){
		$this->db->select('subject.subjectID as subjectID')->from('subject')->where("`subject.classesID` ='$classesID' AND subject.subject_code IN(311,313,312) AND mark.studentID='$studentID' AND mark.examID='$examID' AND mark.mark !='n'");
		$this->db->join('mark','subject.subjectID=mark.subjectID')->order_by('(mark.mark/mark.out_of)*100 asc')->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	function sciences_IDs($studentID,$examID,$classesID){
		$this->db->select('subject.subjectID as subjectID')->from('subject')->where("`subject.classesID` ='$classesID' AND subject.subject_code IN(232,231,233) AND mark.studentID='$studentID' AND mark.examID='$examID' AND mark.mark !='n'");
		$this->db->join('mark','subject.subjectID=mark.subjectID')->order_by('(mark.mark/mark.out_of)*100 asc')->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	function sciences_IDs_end_term($studentID,$examsID,$classesID){
		$this->db->select('subject.subjectID as subjectID')->from('subject')->where("`subject.classesID` ='$classesID' AND subject.subject_code IN(232,231,233) AND pm.studentID='$studentID' AND pm.examID IN ($examsID) AND pm.score !='n'");
		$this->db->join('processed_mark pm','subject.subjectID=pm.subjectID')->order_by('pm.score asc')->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

function sciences_rIDs($studentID,$examID,$classesID){
		$this->db->select('subject.subjectID as subjectID')->from('subject')->where("`subject.classesID` ='$classesID' AND subject.subject_code IN(232,231,233) AND mark.studentID='$studentID' AND mark.examID='$examID' AND mark.mark !='n'");
		$this->db->join('mark','subject.subjectID=mark.subjectID')->order_by('(mark.mark/mark.out_of)*100 asc')->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	function get_subject_mark($studentID,$examID,$subjectID,$year,$term_name) {
		$query = $this->db->get_where('processed_mark', array('term_name' => $term_name,'year' => $year,'studentID' => $studentID,'examID' => $examID,'year' => $year,'term_name' => $term_name,'subjectID' => $subjectID));
		return $query->result();
	}

	function get_subject_mark_end_term($studentID,$examID,$subjectID,$year,$term) {
		$this->db->select("avg(mark) as mark,avg(out_of) as out_of")->from("mark");
		$this->db->where(array('studentID' => $studentID,'subjectID' => $subjectID,'year'=>$year,'term_name'=>$term,'mark !='=>'n'));
		$this->db->where('examID IN ('.$examID.')');
		$query=$this->db->get();
		return $query->row();
	}
	function get_subject_mark_end_terms($studentID,$examID,$subjectID,$year,$term) {
		$this->db->select("avg(score) as score")->from("processed_mark");
		$this->db->where(array('studentID' => $studentID,'subjectID' => $subjectID,'year'=>$year,'term_name'=>$term,'score !='=>'n'));
		$this->db->where('examID IN ('.$examID.')');
		$query=$this->db->get();
		return $query->result();
	}

	function get_subjects_marks($studentID,$examID,$subjectID) {
		$query = $this->db->get_where('mark', array('studentID' => $studentID,'examID' => $examID,'subjectID' => $subjectID,'mark !=' => 'n'));
		return $query->result();
	}

	function get_subjects_marks_end_term($studentID,$examID,$subjectID,$year,$term) {
		$this->db->select('score as mark')->from("processed_mark");
		$this->db->where(array('studentID' => $studentID,'subjectID' => $subjectID,'score !=' => 'n','year'=>$year,'term_name'=>$term,'examID'=>$examID));
		$query = $this->db->get();
		return $query->result();
	}

	function get_total_subject_count($studentID,$examID,$classesID,$year,$term_name) {
		$this->db->select('count(*) as sno')->from('student')->where(array('student.roll'=>$studentID,'mark.examID'=>$examID,'mark.year'=>$year,'mark.term_name'=>$term_name,'mark.classesID'=>$classesID,'mark.mark !='=>'n'));
		$this->db->join('mark','student.studentID=mark.studentID');
		$query=$this->db->get();
		return $query->result();
	}

	function get_total_subjects_count_end_term($studentID,$classesID,$year,$term_name) {
		$this->db->select('count(*) as sno')->from('student')->where(array('student.roll'=>$studentID,'mark.year'=>$year,'mark.term_name'=>$term_name,'mark.classesID'=>$classesID,'mark.mark !='=>'n','mark.term_name'=>$term_name,'mark.year'=>$year,'mark.examID'=>3));
		$this->db->join('mark','student.studentID=mark.studentID');
		$query=$this->db->get();
		return $query->result();
	}

	function get_total_subject_count_end_termm($studentID,$examID,$classesID,$year,$term) {
		$this->db->select('count(*) as sno')->from('student');
		$this->db->where(array('student.roll'=>$studentID,'mark.classesID'=>$classesID,'mark.mark !='=>'n','mark.year'=>$year,'mark.term_name'=>$term));
		$this->db->where('mark.examID IN ('.$examID.')');
		$this->db->join('mark','student.studentID=mark.studentID');
		$query=$this->db->get();
		return $query->result();
	}

	function get_total_subject_count_end_term($studentID,$examID,$classesID,$year) {
		$this->db->select('count(*) as sno')->from('student');
		$this->db->where(array('student.roll'=>$studentID,'mark.classesID'=>$classesID,'mark.mark !='=>'n','mark.year'=>$year));
		$this->db->where('mark.examID IN ('.$examID.')');
		$this->db->join('mark','student.studentID=mark.studentID');
		$query=$this->db->get();
		return $query->result();
	}

	function get_student_total($studentID,$examID,$classesID,$year,$term_name) {
		$this->db->select_sum('score')->from('processed_mark')->where(array('examID'=>$examID,'classesID'=>$classesID,'studentID'=>$studentID,'year'=>$year,'term_name'=>$term_name));
		$query = $this->db->get();
		return $query->result();
	}

	function get_subject_total($examID,$subjectID,$classesID,$year,$term_name) {
		$query=$this->db->select('sum(score) as mark')->from('processed_mark')->where(array('examID'=>$examID,'classesID'=>$classesID,'subjectID'=>$subjectID,'year'=>$year,'term_name'=>$term_name,'score !='=>'n'));
		$query = $this->db->get();
		return $query->result();
	}

	function get_subject_total_end_term($examID,$subjectID,$classesID,$year) {
		$this->db->select('sum((mark/out_of)*100) as mark')->from('mark');
		$this->db->where(array('classesID'=>$classesID,'subjectID'=>$subjectID,'mark !='=>'n','year'=>$year));
		$this->db->where('examID IN ('.$examID.')');
		$query = $this->db->get();
		return $query->result();
	}

	function get_overall_total($examID,$classesID) {
		$this->db->select('sum((mark/out_of)*100) as mark')->from('mark')->where(array('examID'=>$examID,'classesID'=>$classesID,'mark !='=>'n'));
		$query = $this->db->get();
		return $query->result();
	}

	function get_overall_total_end_term($examID,$classesID,$year) {
		$this->db->select('sum((mark/out_of)*100) as mark')->from('mark');
		$this->db->where(array('classesID'=>$classesID,'mark !='=>'n','year'=>$year));
		$this->db->where('examID IN ('.$examID.')');
		$query = $this->db->get();
		return $query->result();
	}

	function get_section_total($examID,$classesID,$sectionID) {
		$this->db->select('sum((mark.mark/mark.out_of)*100) as mark')->from('mark')->where(array("student.sectionID"=>$sectionID,'mark.examID'=>$examID,'mark.classesID'=>$classesID,'mark.mark !='=>'n'));
		$this->db->join('student', 'student.studentID = mark.studentID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_section_total_end_term($examID,$classesID,$sectionID,$year,$term) {
		$this->db->select('sum((mark.mark/mark.out_of)*100) as mark')->from('mark');
		$this->db->where(array("student.sectionID"=>$sectionID,'mark.classesID'=>$classesID,'mark.mark !='=>'n','mark.term_name'=>$term,'mark.year'=>$year));
		$this->db->where('mark.examID IN ('.$examID.')');
		$this->db->join('student', 'student.studentID = mark.studentID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_mean($examID,$classesID) {
		$this->db->select('avg(total_points)/7 as mean')->from('form3and4averages')->where(array('examID'=>$examID,'classesID'=>$classesID));
		$query = $this->db->get();
		return $query->result();
	}

	function get_mean_end_term($examID,$classesID,$year,$term) {
		$this->db->select('avg(total_points)/7 as mean')->from('form3and4averages');
		$this->db->where(array('classesID'=>$classesID,'year'=>$year,'term'=>$term));
		$this->db->where('examID IN ('.$examID.')');
		$query = $this->db->get();
		return $query->result();
	}

	function get_section_mean($examID,$classesID,$sectionID) {
		$this->db->select('avg(form3and4averages.total_points)/7 as mean')->from('form3and4averages')->where(array('form3and4averages.examID'=>$examID,'form3and4averages.classesID'=>$classesID,'student.sectionID'=>$sectionID));
		$this->db->join('student', 'student.studentID = form3and4averages.roll', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_section_mean_end_term($examID,$classesID,$sectionID,$term,$year) {
		$this->db->select('avg(form3and4averages.total_points)/7 as mean')->from('form3and4averages');
		$this->db->where(array('form3and4averages.classesID'=>$classesID,'student.sectionID'=>$sectionID,'form3and4averages.term'=>$term,'form3and4averages.year'=>$year));
		$this->db->where('form3and4averages.examID IN ('.$examID.')');
		$this->db->join('student', 'student.studentID = form3and4averages.roll', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_ssubject_total($examID,$subjectID,$classesID,$sectionID,$year,$term) {
		$this->db->select('sum(pm.score) as mark')->from('processed_mark pm');
		$this->db->join('student', 'student.studentID = pm.studentID', 'LEFT');
		$this->db->where(array("student.sectionID"=>$sectionID,"pm.examID"=>$examID,"pm.classesID"=>$classesID,"pm.subjectID"=>$subjectID,"pm.year"=>$year,"pm.term_name"=>$term,"pm.score !="=>"n"));
		$query=$this->db->get();
		return $query->result();
	}

	function get_ssubject_total_end_term($examID,$subjectID,$classesID,$sectionID,$term,$year) {
		$this->db->select('sum(pm.score) as mark')->from('processed_mark pm');
		$this->db->join('student', 'student.studentID = pm.studentID', 'LEFT');
		$this->db->where(array("student.sectionID"=>$sectionID,"pm.classesID"=>$classesID,"pm.subjectID"=>$subjectID,"pm.score !="=>"n",'pm.term_name'=>$term,'pm.year'=>$year));
		$this->db->where("pm.examID IN (".$examID.')');
		$query=$this->db->get();
		return $query->result();
	}

	function get_subject_average($examID,$subjectID,$classesID) {
		$this->db->select('avg((mark/out_of)*100) as mark')->from('mark')->where(array('examID'=>$examID,'classesID'=>$classesID,'subjectID'=>$subjectID,'mark !='=>'n'));
		$query = $this->db->get();
		return $query->result();
	}

	function get_subject_average_end_term($examID,$subjectID,$classesID,$year,$term) {
		$this->db->select('avg(score) as mark')->from('processed_mark');
		$this->db->where(array('classesID'=>$classesID,'subjectID'=>$subjectID,'score !='=>'n','year'=>$year,'term_name'=>$term));
		$this->db->where('examID IN ('.$examID.')');
		$query = $this->db->get();
		return $query->result();
	}

	function get_overall_average($examID,$classesID) {
		$this->db->select('avg((mark/out_of)*100) as mark')->from('mark')->where(array('examID'=>$examID,'classesID'=>$classesID,'mark !='=>'n'));
		$query = $this->db->get();
		return $query->result();
	}

	function get_overall_average_end_term($examID,$classesID,$year) {
		$this->db->select('avg((mark/out_of)*100) as mark')->from('mark');
		$this->db->where(array('classesID'=>$classesID,'mark !='=>'n','year'=>$year));
		$this->db->where('examID IN ('.$examID.')');
		$query = $this->db->get();
		return $query->result();
	}

	function get_section_average($examID,$classesID,$sectionID) {
		$this->db->select('avg((mark.mark/mark.out_of)*100) as mark')->from('mark')->where(array("student.sectionID"=>$sectionID,'mark.examID'=>$examID,'mark.classesID'=>$classesID,'mark.mark !='=>'n'));
		$this->db->join('student', 'student.studentID = mark.studentID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_section_average_end_term($examID,$classesID,$sectionID,$term,$year) {
		$this->db->select('avg((mark.mark/mark.out_of)*100) as mark')->from('mark');
		$this->db->where(array("student.sectionID"=>$sectionID,'mark.classesID'=>$classesID,'mark.mark !='=>'n','mark.term_name'=>$term,'mark.year'=>$year));
		$this->db->where('mark.examID IN ('.$examID.')');
		$this->db->join('student', 'student.studentID = mark.studentID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_ssubject_average($examID,$subjectID,$classesID,$sectionID,$year,$term_name) {
		$this->db->select('avg(pm.score) as mark')->from('processed_mark pm')->where(array("pm.examID"=>$examID,"pm.classesID"=>$classesID,"pm.subjectID"=>$subjectID,"pm.year"=>$year,"pm.term_name"=>$term_name,"student.sectionID"=>$sectionID,"pm.score !="=>"n"));
		$this->db->join('student', 'student.studentID = pm.studentID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_ssubject_average_end_term($examID,$subjectID,$classesID,$sectionID,$term,$year) {
		$this->db->select('avg(pm.score) as mark')->from('processed_mark pm');
		$this->db->where(array("pm.classesID"=>$classesID,"pm.subjectID"=>$subjectID,"student.sectionID"=>$sectionID,"pm.score !="=>"n",'pm.term_name'=>$term,'pm.year'=>$year));
		$this->db->where("pm.examID IN (".$examID.')');
		$this->db->join('student', 'student.studentID = pm.studentID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_student_average($studentID,$examID,$classesID,$year,$term_name) {
		$query=$this->db->select('avg(score) as avg')->from('processed_mark')->where(array('examID'=>$examID,'classesID'=>$classesID,'studentID'=>$studentID,'year'=>$year,'term_name'=>$term_name,'score !='=>'n'));
		$query = $this->db->get();
		return $query->result();
	}
	function get_students_average($examID,$classesID,$year,$term_name) {
		$query=$this->db->select('student.kcpe_mark,student.sectionID,student.photo,student.name,student.roll, student.studentID, avg(score) as avg, sum(score) as stotal')->from('processed_mark')->where(array('processed_mark.examID'=>$examID,'processed_mark.classesID'=>$classesID,'processed_mark.year'=>$year,'processed_mark.term_name'=>$term_name,'processed_mark.score !='=>'n'))->group_by('processed_mark.studentID')->order_by('stotal desc');
		$this->db->join('student', 'student.studentID = processed_mark.studentID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_students_average_end_term($examID,$classesID,$year,$term) {
		$query=$this->db->select('student.kcpe_mark,student.sectionID,student.photo,student.name,student.roll, student.studentID, avg(pm.score) as avg,sum(pm.score) as stotal')->from('processed_mark pm');
		$this->db->where(array('pm.classesID'=>$classesID,'pm.score !='=>'n','pm.year'=>$year,'pm.term_name'=>$term));
		$this->db->where('pm.examID IN ('.$examID.')');
		$this->db->group_by('pm.studentID');
		$this->db->order_by('avg desc');
		$this->db->join('student', 'student.studentID = pm.studentID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function gets_students_average($sectionID,$examID,$classesID) {
		$query=$this->db->select('student.photo,student.name,student.roll, student.studentID, avg((mark.mark/out_of)*100) as avg, sum((mark.mark/out_of)*100) as stotal')->from('mark')->where(array('mark.examID'=>$examID,'mark.classesID'=>$classesID,'section.sectionID'=>$sectionID,'mark.mark !='=>'n'))->group_by('mark.studentID')->order_by('stotal desc');
		$this->db->join('student', 'student.studentID = mark.studentID', 'LEFT');
		$this->db->join('section', 'section.sectionID = student.sectionID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_studentss_average($examID,$classesID,$year,$term_name) {
		$this->db->select('student.name,student.roll, student.studentID, sum(pm.score) as stotal,pm.term_name as term')->from('processed_mark pm')->where(array('pm.examID'=>$examID,'pm.classesID'=>$classesID,'pm.year'=>$year,'pm.term_name'=>$term_name,'pm.score !='=>'n'))->group_by('pm.studentID');
		$this->db->join('student','student.studentID = pm.studentID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_studentss_average_end_term($examID,$classesID,$year,$term) {
		$query=$this->db->select('student.name,student.roll, student.studentID, sum(pm.score) as stotal,pm.term_name as term')->from('processed_mark pm');
		$query=$this->db->where(array('pm.classesID'=>$classesID,'pm.score !='=>'n','pm.year'=>$year,'pm.term_name'=>$term));
		$query=$this->db->where('pm.examID IN ('.$examID.')');
		$query=$this->db->group_by('pm.studentID');
		$this->db->join('student', 'student.studentID = pm.studentID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}


	function get_subject($id) {
		$this->db->select('*')->from('subject')->where(array('subject.classesID' => $id))->order_by('subject_code asc');
		$this->db->join('teacher T','subject.teacherID=T.teacherID');
		$query = $this->db->get();
		return $query->result();
	}
	function get_subjects($studentID,$examID,$classesID,$year,$term_name) {
		$this->db->select('subjectID,score as mark')->from('processed_mark')->where(array('score !='=>'n','studentID'=>$studentID,'examID'=>$examID,'classesID'=>$classesID,'year'=>$year,'term_name'=>$term_name));
		$query = $this->db->get();
		return $query->result();
	}

	function get_subjects_end_term($studentID,$examsID,$classesID,$year,$term_name) {
		$this->db->select('subject,subjectID,avg(score) as mark')->from('processed_mark')->where(array('score !='=>'n','studentID'=>$studentID,'classesID'=>$classesID,'year'=>$year,'term_name'=>$term_name))->group_by('subjectID');
		$this->db->where('examID IN ('.$examsID.')');
		$query = $this->db->get();
		return $query->result();
	}

	function get_years() {
		$this->db->select("year")->from("processed_mark")->group_by("year");
		$query = $this->db->get();
		return $query->result();
	}

	function get_rsubjects($studentID,$examID,$classesID) {
		$this->db->select('subjectID,round((mark/out_of)*100) as mark,avg((mark/out_of)*100) as avg')->from('mark')->where(array('mark.mark !='=>'n','mark.studentID'=>$studentID,'mark.examID'=>$examID,'classesID'=>$classesID));
		$query = $this->db->get();
		return $query->result();
	}

	function get_option1_subjects($studentID,$examID,$classesID,$subjecth,$year,$term_name) {
		$this->db->select('subjectID,score as mark')->from('processed_mark')->where(array('score !='=>'n','studentID'=>$studentID,'examID'=>$examID,'classesID'=>$classesID,'subjectID !='=>$subjecth,'year'=>$year,'term_name'=>$term_name));
		$query = $this->db->get();
		return $query->result();
	}

	function get_option1_subjects_end_term($studentID,$examsID,$classesID,$subjecth,$year,$term_name) {
		$this->db->select('subjectID,avg(score) as mark')->from('processed_mark')->where(array('score !='=>'n','studentID'=>$studentID,'classesID'=>$classesID,'subjectID !='=>$subjecth,'year'=>$year,'term_name'=>$term_name))->group_by('subjectID');
		$this->db->where('examID IN ('.$examsID.')');
		$query = $this->db->get();
		return $query->result();
	}

	function get_option1_rsubjects($studentID,$examID,$classesID,$subjecth) {
		$this->db->select('subjectID,round((mark/out_of)*100) as mark,avg((mark/out_of)*100) as avg')->from('mark')->where(array('mark.mark !='=>'n','mark.studentID'=>$studentID,'mark.examID'=>$examID,'classesID'=>$classesID,'subjectID !='=>$subjecth));
		$query = $this->db->get();
		return $query->result();
	}

	function get_option2_subjects($studentID,$examID,$classesID,$subject_s,$year,$term_name) {
		$this->db->select('subjectID,score as mark')->from('processed_mark')->where(array('score !='=>'n','studentID'=>$studentID,'examID'=>$examID,'classesID'=>$classesID,'subjectID !='=>$subject_s,'year'=>$year,'term_name'=>$term_name));
		$query = $this->db->get();
		return $query->result();
	}

	function get_option2_subjects_end_term($studentID,$examID,$classesID,$subject_s,$year,$term_name) {
		$this->db->select('subjectID,avg(score) as mark')->from('processed_mark')->where(array('score !='=>'n','studentID'=>$studentID,'classesID'=>$classesID,'subjectID !='=>$subject_s,'year'=>$year,'term_name'=>$term_name));
		$this->db->where('examID IN ('.$examID.')');
		$this->db->group_by('subjectID');
		$query = $this->db->get();
		return $query->result();
	}

	function get_option2_rsubjects($studentID,$examID,$classesID,$subject_s) {
		$this->db->select('subjectID,round((mark/out_of)*100) as mark,avg((mark/out_of)*100) as avg')->from('mark')->where(array('mark.mark !='=>'n','mark.studentID'=>$studentID,'mark.examID'=>$examID,'classesID'=>$classesID,'subjectID !='=>$subject_s));
		$query = $this->db->get();
		return $query->result();
	}

	function get_subject_count($classesID){
		$query = $this->db->select('*')->from('subject')->where(array('classesID' => $classesID));
		$query = $this->db->get();
		return $query->result();
	}

	function get_join_all_end_term($classesID,$examID,$termID,$year,$term) {
		$this->db->select('*');
		$this->db->from('mark');
		$this->db->where(array('mark.classesID' => $classesID, 'mark.year >=' => $year,'mark.term_name'=>$term));
		$this->db->where('mark.examID IN ('.$examID.')');
		$this->db->join('student', 'student.studentID = mark.examID', 'LEFT');
		$this->db->join('subject', 'subject.subjectID = mark.subjectID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_join_all($id, $examID) {
		$date = date("Y");
		$this->db->select('*');
		$this->db->from('mark');
		$this->db->where(array('mark.classesID' => $id, 'mark.year >=' => $date,'mark.examID' => $examID));
		$this->db->join('student', 'student.studentID = mark.examID', 'LEFT');
		$this->db->join('subject', 'subject.subjectID = mark.subjectID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_join_all_wsection($id, $sectionID) {
		$date = date("Y-m-d");
		$this->db->select('*');
		$this->db->from('examschedule');
		$this->db->where(array('examschedule.classesID' => $id, 'examschedule.sectionID' => $sectionID, 'examschedule.edate >=' => $date));
		$this->db->join('exam', 'exam.examID = examschedule.examID', 'LEFT');
		$this->db->join('classes', 'classes.classesID = examschedule.classesID', 'LEFT');
		$this->db->join('section', 'section.sectionID = examschedule.sectionID', 'LEFT');
		$this->db->join('subject', 'subject.subjectID = examschedule.subjectID', 'LEFT');
		$query = $this->db->get();
		return $query->result();
	}

	function get_examschedule($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_examschedule($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_examschedule($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_examschedule($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_examschedule($id){
		parent::delete($id);
	}	
	public function get_overall_position($examID,$classesID,$sadmno,$year,$term_name){
		$i = 0; 
        $j = 0; 
        $ttmark=false;
        $rankingdetails=array();
		if ($classesID !=13 && $classesID !=14) {
			$rakingr=$this->ranking_m->get_students_average($examID,$classesID,$year,$term_name);
			$totalstudents=count($rakingr);
			foreach ($rakingr as $keyvalue) {
				if ($ttmark != round($keyvalue->stotal,0)) {
		            $ttmark=round($keyvalue->stotal,0);
		            $i++;
		            if ($j>0) {
		                $i+=$j;
		                $j=0;
		            }
		        }else{
		            $j++;
		        }
		        if ($keyvalue->roll === $sadmno) {
		        	$rankingdetails['position']=$i;
		        	$rankingdetails['totalstudents']=$totalstudents;
		        	return $rankingdetails;
		        }
			}
		}else{
			$studentsw_datas=$this->ranking_m->gets_sstudents($classesID,$examID,$year,$term_name);
			$totalstudents=count($studentsw_datas);
			foreach($studentsw_datas as $sdatar){
				if ($ttmark != $sdatar->tp) {
	                $ttmark=$sdatar->tp;
	                $i++;
	                if ($j>0) {
	                    $i+=$j;
	                    $j=0;
	                }
	            }else{
	                $j++;
	            }
	            if ($sdatar->roll === $sadmno) {
		        	$rankingdetails['position']=$i;
		        	$rankingdetails['tp']=$sdatar->tp;
		        	$rankingdetails['totalstudents']=$totalstudents;
		        	return $rankingdetails;
		        }
	        }
		}
	}

	public function get_overall_position_end_term($examID,$classesID,$sadmno,$year,$term){
		$i = 0; 
        $j = 0; 
        $ttmark=false;
        $rankingdetails=array();
		if ($classesID !=13 && $classesID !=14) {
			$rakingr=$this->ranking_m->get_students_average_end_term($examID,$classesID,$year,$term);
			$totalstudents=count($rakingr);
			foreach ($rakingr as $keyvalue) {
				if ($ttmark != round($keyvalue->stotal,0)) {
		            $ttmark=round($keyvalue->stotal,0);
		            $i++;
		            if ($j>0) {
		                $i+=$j;
		                $j=0;
		            }
		        }else{
		            $j++;
		        }
		        if ($keyvalue->roll === $sadmno) {
		        	$rankingdetails['position']=$i;
		        	$rankingdetails['totalmarks']=$keyvalue->stotal;
		        	$rankingdetails['average']=round($keyvalue->avg);
		        	$rankingdetails['totalstudents']=$totalstudents;
		        	return $rankingdetails;
		        }
			}
		}else{
			$studentsw_datas=$this->ranking_m->gets_sstudents_end_term($classesID,$examID,$year,$term);
			$totalstudents=count($studentsw_datas);
			foreach($studentsw_datas as $sdatar){
				if ($ttmark != round($sdatar->tp,0)) {
	                $ttmark=$sdatar->tp;
	                $i++;
	                if ($j>0) {
	                    $i+=$j;
	                    $j=0;
	                }
	            }else{
	                $j++;
	            }
	            if ($sdatar->roll === $sadmno) {
		        	$rankingdetails['position']=$i;
		        	$rankingdetails['tp']=round($sdatar->tp,0);
		        	$rankingdetails['totalstudents']=$totalstudents;
		        	$rankingdetails['se']=$sdatar->se;
		        	$rankingdetails['avg_points']=round($sdatar->tp/$sdatar->se,0);
		        	return $rankingdetails;
		        }
	        }
		}
	}
	public function get_class_position($examID,$classesID,$sectionID,$sadmno,$year,$term_name){
		$i = 0; 
        $j = 0; 
        $ttmark=false;
        $rankingdetails=array();
		if ($classesID !=13 && $classesID !=14) {
			$rakingr=$this->ranking_m->get_sstudent($classesID,$sectionID,$examID,$year,$term_name);
			$totalstudents=count($rakingr);
			foreach ($rakingr as $keyvalue) {
				if ($ttmark != round($keyvalue->studenttotal,0)) {
		            $ttmark=round($keyvalue->studenttotal,0);
		            $i++;
		            if ($j>0) {
		                $i+=$j;
		                $j=0;
		            }
		        }else{
		            $j++;
		        }
		        if ($keyvalue->roll === $sadmno) {
		        	$rankingdetails['totalmarks']=$keyvalue->studenttotal;
		        	$rankingdetails['position']=$i;
		        	$rankingdetails['totalstudents']=$totalstudents;
		        	return $rankingdetails;
		        	break;
		        }
			}
		}else{
			$studentsw_datas=$this->ranking_m->get_sstudents($classesID,$sectionID,$examID,$year,$term_name);
			$totalstudents=count($studentsw_datas);
			foreach($studentsw_datas as $sdatar){
				if ($ttmark != $sdatar->tp) {
	                $ttmark=$sdatar->tp;
	                $i++;
	                if ($j>0) {
	                    $i+=$j;
	                    $j=0;
	                }
	            }else{
	                $j++;
	            }
	            if ($sdatar->roll === $sadmno) {
	            	$rankingdetails['tp']=$sdatar->tp;
		        	$rankingdetails['position']=$i;
		        	$rankingdetails['totalstudents']=$totalstudents;
		        	return $rankingdetails;
		        	break;
		        }
	        }
		}
	}

	public function get_class_position_end_term($examID,$classesID,$sectionID,$sadmno,$year,$term){
		$i = 0; 
        $j = 0; 
        $ttmark=false;
        $rankingdetails=array();
		if ($classesID !=13 && $classesID !=14) {
			$rakingr=$this->ranking_m->get_sstudent_end_term($classesID,$sectionID,$examID,$year,$term);
			$totalstudents=count($rakingr);
			foreach ($rakingr as $keyvalue) {
				if ($ttmark != round($keyvalue->studenttotal,0)) {
		            $ttmark=round($keyvalue->studenttotal,0);
		            $i++;
		            if ($j>0) {
		                $i+=$j;
		                $j=0;
		            }
		        }else{
		            $j++;
		        }
		        if ($keyvalue->roll === $sadmno) {
		        	$rankingdetails['totalmarks']=$keyvalue->studenttotal;
		        	$rankingdetails['position']=$i;
		        	$rankingdetails['totalstudents']=$totalstudents;
		        	return $rankingdetails;
		        	break;
		        }
			}
		}else{
			$studentsw_datas=$this->ranking_m->get_sstudents_end_term($classesID,$sectionID,$examID,$year,$term);
			$totalstudents=count($studentsw_datas);
			foreach($studentsw_datas as $sdatar){
				if ($ttmark != round($sdatar->tp,0)) {
	                $ttmark=$sdatar->tp;
	                $i++;
	                if ($j>0) {
	                    $i+=$j;
	                    $j=0;
	                }
	            }else{
	                $j++;
	            }
	            if ($sdatar->roll === $sadmno) {
	            	$rankingdetails['tp']=$sdatar->tp;
		        	$rankingdetails['position']=$i;
		        	$rankingdetails['se']=$sdatar->se;
		        	$rankingdetails['avg_points']=round($sdatar->tp/$sdatar->se,0);
		        	$rankingdetails['totalstudents']=$totalstudents;
		        	return $rankingdetails;
		        	break;
		        }
	        }
		}
	}
}

/* End of file examschedule_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/examschedule_m.php */