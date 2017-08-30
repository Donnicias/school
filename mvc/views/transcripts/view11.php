<?php
require('/assets/fpdf/fpdf.php');

class PDF extends FPDF
{
// Load data
function LoadData()
{
    // Read file lines
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
    return $data;
}



// Colored table
function FancyTable($header)
{
    $trans=new Transcripts();
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    $w = array(40, 35, 40, 45);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Data
    $fill = false;




$classID = $trans->view1()->classID;
            $examID = $trans->view1()->examID;
            $studentID = $trans->view1()->studentID;
            $position = $trans->view1()->position;
            $totalpoints = $trans->view1()->totalpoints;
            //$meangrade = htmlentities(mysql_real_escape_string($this->uri->segment(8)));
            $totalstudents = $trans->totalstudents;

            if ((int)$classID && (int)$examID && (int)$studentID && (int)$position) {

                $student= $this->student_m->get_student($studentID);
                $termss= $this->exam_m->get_term();
                $examss = $this->exam_m->get_exam_name($examID);
                foreach ($termss as $value) {
                    $term=$value->term_name;
                }
                foreach ($examss as $value) {
                    $exam=$value->exam;
                }
                

                $classes= $this->student_m->get_class($classID);
                // if($student && $classes) {

                //     $this->data['set'] = $examID;
                //     $this->data['examID'] = $examID;
                //     $this->data['classID'] = $classID;
                //     $this->data['studentID'] = $studentID;
                //     $this->data['position'] = $position;
                //     $this->data['totalpoints'] = $totalpoints;
                //     //$this->data['meangrade'] = $meangrade;
                //     $this->data['totalstudents'] = $totalstudents;

                //     $this->data["exams"] = $this->exam_m->get_exam();
                //     $this->data["grades"] = $this->grade_m->get_grade();
                //     $this->data["transcriptss"] = $this->transcripts_m->get_order_by_transcripts_with_highest_mark($classID,$studentID);
                //     $this->data["section"] = $this->section_m->get_section($this->data['student']->sectionID);

                //     // dump($this->data["transcriptss"]);
                //     // die;


                //     $this->data["subview"] = "transcripts/view1";
                //     $this->load->view('_layout_main', $this->data);
                // } else {
                //     $this->data["subview"] = "error";
                //     $this->load->view('_layout_main', $this->data);
                // }
        $this->Cell($w[0],6,$student->roll,'LR',0,'L',$fill);
        $this->Cell($w[1],6,$student->name,'LR',0,'L',$fill);
        $this->Cell($w[2],6,$examID,'LR',0,'R',$fill);
        $this->Cell($w[3],6,$classesID,'LR',0,'R',$fill);
        $this->Ln();
        $fill = !$fill;

            }




    
        
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}
}

$pdf = new PDF();
// Column headings
$header = array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)');
// Data loading
//$data = $pdf->LoadData('countries.txt');
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->FancyTable($header);
$pdf->Output();
?>