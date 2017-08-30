<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $panel_title; ?></title>

<style type="text/css">
    body{
      font-family: verdana;
      font-size: 12px;
    }
    #page-wrap {
        width: 700px;
        margin: 0 auto;
        height:1000px;
    }
    .center-justified {
        text-align: justify;
        margin: 0 auto;
        width: 30em;
    }
    /*ini starts here*/
    .list-group {
      padding-left: 0;
      margin-bottom: 15px;
      width: auto;
    }
    .list-group-item {
      position: relative;
      display: block;
      padding: 7.5px 10px;
      margin-bottom: -1px;
      background-color: #fff;
      border: 1px solid #ddd;
      /*margin: 2px;*/
    }
    table {
      border-spacing: 0;
      border-collapse: collapse;
      font-size: 12px;
    }
    td,
    th {
      padding: 0;
    }
    @media print {
      * {
        color: #000 !important;
        text-shadow: none !important;
        background: transparent !important;
        box-shadow: none !important;
      }
      a,
      a:visited {
        text-decoration: underline;
      }
      a[href]:after {
        content: " (" attr(href) ")";
      }
      abbr[title]:after {
        content: " (" attr(title) ")";
      }
      a[href^="javascript:"]:after,
      a[href^="#"]:after {
        content: "";
      }
      pre,
      blockquote {
        border: 1px solid #999;

        page-break-inside: avoid;
      }
      thead {
        display: table-header-group;
      }
      tr,
      img {
        page-break-inside: avoid;
      }
      img {
        max-width: 100% !important;
      }
      p,
      h2,
      h3 {
        orphans: 3;
        widows: 3;
      }
      h2,
      h3 {
        page-break-after: avoid;
      }
      select {
        background: #fff !important;
      }
      .navbar {
        display: none;
      }
      .table td,
      .table th {
        background-color: #fff !important;
      }
      .btn > .caret,
      .dropup > .btn > .caret {
        border-top-color: #000 !important;
      }
      .label {
        border: 1px solid #000;
      }
      .table {
        border-collapse: collapse !important;
      }
      .table-bordered th,
      .table-bordered td {
        border: 1px solid #ddd !important;
      }
    }
    table {
      max-width: 100%;
      background-color: transparent;
      font-size: 12px;
    }
    th {
      text-align: left;
    }
    .table {
      width: 100%;
      margin-bottom: 20px;
    }
    .table h4 {
      font-size: 15px;
      padding: 0px;
      margin: 0px;
    }
    .head {
       border-top: 0px solid #e2e7eb;
       border-bottom: 0px solid #e2e7eb;  
    }
    .table > thead > tr > th,
    .table > tbody > tr > th,
    .table > tfoot > tr > th,
    .table > thead > tr > td,
    .table > tbody > tr > td,
    .table > tfoot > tr > td {
      padding: 8px;
      line-height: 1.428571429;
      vertical-align: top;
      /*border-top: 1px solid #e2e7eb; */
    }
    /*ini edit default value : border top 1px to 0 px*/
    .table > thead > tr > th {
      font-size: 15px;
      font-weight: 500;
      vertical-align: bottom;
      /*border-bottom: 2px solid #e2e7eb;*/
      color: #242a30;
     
      
    }
    
    .table > caption + thead > tr:first-child > th,
    .table > colgroup + thead > tr:first-child > th,
    .table > thead:first-child > tr:first-child > th,
    .table > caption + thead > tr:first-child > td,
    .table > colgroup + thead > tr:first-child > td,
    .table > thead:first-child > tr:first-child > td {
      border-top: 0;
    }
    .table > tbody + tbody {
      border-top: 2px solid #e2e7eb;
    }
    .table .table {
      background-color: #fff;
    }
    .table-condensed > thead > tr > th,
    .table-condensed > tbody > tr > th,
    .table-condensed > tfoot > tr > th,
    .table-condensed > thead > tr > td,
    .table-condensed > tbody > tr > td,
    .table-condensed > tfoot > tr > td {
      padding: 5px;
    }
    .table-bordered {
      border: 1px solid #e2e7eb;
    }
    .table-bordered > thead > tr > th,
    .table-bordered > tbody > tr > th,
    .table-bordered > tfoot > tr > th,
    .table-bordered > thead > tr > td,
    .table-bordered > tbody > tr > td,
    .table-bordered > tfoot > tr > td {
      border: 1px solid #e2e7eb;
    }
    .table-bordered > thead > tr > th,
    .table-bordered > thead > tr > td {
      border-bottom-width: 2px;
    }
    .table-striped > tbody > tr:nth-child(odd) > td,
    .table-striped > tbody > tr:nth-child(odd) > th {
      background-color: #f0f3f5;
    }
    .panel-title {
      margin-top: 0;
      margin-bottom: 0;
      font-size: 20px;
      color: #fff;
      padding: 0;
    }
    .panel-title > a {
      color: #707478;
      text-decoration: none;
    }
    a {
      background: transparent;
      color: #707478;
      text-decoration: none;
    }
    strong {
        color: #707478;
    }
</style>
</head>
<?php 
  $usertype = $this->session->userdata("usertype");
  if($usertype){
?>
  <body >

<div id="page-wrap" style="border-bottom:2px solid green;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px; border-top:5px solid green;border-top-right-radius: 5px;border-top-left-radius: 5px;">
    <br/><br/><br/><br/>





<?php
    if(count($student)) {
        $usertype = $this->session->userdata("usertype");
    $i = 0; 
    $j = 0; 
    $ttmark=false;
    $totalstudents=count($student);
    foreach($student as $student) {
		
        
                            if ($classID==13 || $classID==14) {
								if($student->se >0){
								?>
								<table width="100%" >
        <tr>
          <td>
          <span class="text-left " style="line-height: 8px;">
                <h2>KIARENI ELCK SECONDARY SCHOOL</h2>
                <h3>Official Transcript</h3>
                <h3><?php echo $term.'/'.$exam.'/'.date("Y");?></h3>
            </span>
            <h2 style="text-align:center;font-family: arial;">
                <a >
                    
                    <?php
                    $array = array(
                        "src" => base_url('uploads/images/'.$student->photo),
                        'width' => '100px',
                        'height' => '100px',
                        "style" => "margin-right:0px;"
                    );
                    echo img($array);
                    ?>
                </a>
                <table style="text-align:center;" width="100%">
                  <tr>
                    <td>
                        <h2 style="margin:0px;"> <b><?php  echo $student->name; ?></b></h2>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <h3 style="margin:0px;"> <b><?php  echo $this->lang->line("transcripts_classes")." ".$classes->classes; ?> </b>
                      </h3>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <h3 style="margin:0px;"> <b><?php  echo $this->lang->line("transcripts_roll")." ".$student->roll; ?></b>
                      </h3> 
                    </td>
                  </tr>
                </table>
            </h2>
          </td>
        </tr>
      </table>
      <br/><br/><br/><br/><br/>
      <?php
      echo "<table class=\"table  table-bordered table-condensed\">";
        echo "<tbody>";

          echo "<tr>";
              echo "<th><b>";
                  echo $this->lang->line("transcripts_subject_code");
              echo "</b></th>";
              echo "<th><b/>";
                  echo $this->lang->line("transcripts_subject_name");
              echo "</b></th>";
              if(count($grades)) {
                  echo "<th><b>";
                      echo $this->lang->line("transcripts_score");
                  echo "</b></th>";
                  echo "<th><b>";
                      echo $this->lang->line("transcripts_remarks");
                  echo "</b></th>";
                  echo "<th><b>";
                      echo $this->lang->line("transcripts_initials");
                  echo "</b></th>";
              }
          echo "</tr>";
        echo "</tbody>";
    echo "<tbody>";
    if ($classID==13 || $classID==14) {
      foreach ($transcriptss as $transcripts) {
      echo "<tr>";
          echo "<td data-title='".$this->lang->line('transcripts_subject_code')."'>";
              echo $transcripts->sc;
          echo "</td>";
          echo "<td data-title='".$this->lang->line('transcripts_subject')."'>";
              echo $transcripts->subject;
          echo "</td>";
          if(count($grades)) {
              foreach ($grades as $grade) {
                  if($grade->gradefrom <= $transcripts->mark && $grade->gradeupto >= $transcripts->mark) {
                      echo "<td data-title='".$this->lang->line('transcripts_grade')."'>";
                          echo $transcripts->mark.' '.$grade->grade;
                      echo "</td>";
                      if ($transcripts->sc ==102) {
                         echo "<td data-title='".$this->lang->line('transcripts_'.$transcripts->sc)."'>";
                              echo $this->lang->line('transcripts_'.$grade->note);
                          echo "</td>"; 
                      }else{
                          echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'>";
                          echo $grade->note;
                      echo "</td>";
                      }
                      break;
                  }
              }
          }
          echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'><i>";
              echo $transcripts->initials;
          echo "</i></td>";
          echo "</tr>";
      }
      echo "<tr>";
          echo "<td colspan=\"2\" class=\"text-right\">";
              echo "&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;<b>Exam summary (".$term.'/'.$exam.")</b>";
          echo "</td>";
           $avgpoints=round(($totalpoints/7),0);
              if(count($grades)) {
                  foreach ($grades as $grade) {
                      if($grade->point <= $avgpoints && $grade->point >= $avgpoints) {
                              $sgrade=$grade->grade;
                      }
                  }
              }
          echo "<td colspan=\"3\" >";
              echo "<b>Total Points:</b> ".$totalpoints."  <b> &nbsp; &nbsp; &nbsp;  Mean Grade:</b> ".$sgrade." <b>  &nbsp; &nbsp; &nbsp; Overall Position:</b> ".$position.'/'.$totalstudents;
          echo "</td>";
      echo "</tr>";
  }else{
      foreach ($transcriptss as $transcripts) {
              echo "<tr>";
                  echo "<td data-title='".$this->lang->line('transcripts_subject_code')."'>";
                      echo $transcripts->sc;
                  echo "</td>";
                  echo "<td data-title='".$this->lang->line('transcripts_subject')."'>";
                      echo $transcripts->subject;
                  echo "</td>";
                  if(count($grades)) {
                      foreach ($grades as $grade) {
                          if($grade->gradefrom <= $transcripts->mark && $grade->gradeupto >= $transcripts->mark) {
                              echo "<td data-title='".$this->lang->line('transcripts_grade')."'>";
                                  echo $transcripts->mark.' '.$grade->grade;
                              echo "</td>";
                              if ($transcripts->sc ==102) {
                                 echo "<td data-title='".$this->lang->line('transcripts_'.$transcripts->sc)."'>";
                                      echo $this->lang->line('transcripts_'.$grade->note);
                                  echo "</td>"; 
                              }else{
                                  echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'>";
                                  echo $grade->note;
                              echo "</td>";
                              }
                              
                              break;
                          }
                      }
                  }
                  echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'><i>";
                      echo $transcripts->initials;
                  echo "</i></td>";
              echo "</tr>";
      }
       echo "<tr>";
          echo "<td colspan=\"2\" class=\"text-right\">";
              echo "<b>Exam summary (".$term.'/'.$exam.")</b>";
          echo "</td>";
           $avgpoints=round(($totalpoints/11),0);
              if(count($grades)) {
                  foreach ($grades as $grade) {
                      if($grade->gradefrom <= $avgpoints && $grade->gradeupto >= $avgpoints) {
                              $ssgrade=$grade->grade;
                      }
                  }
              }
          echo "<td colspan=\"3\" >";
              echo "<span><b>Total Marks: ".$totalpoints."/1100</b></span> <span><b>Mean Grade: ".$ssgrade."</b></span> <span>  <b>Overall Position: ".$position.'/'.$totalstudents;
          echo "</b></span></td>";
      echo "</tr>";
  }
echo "</tbody>";
echo "</table>";
      ?>
      <b>
      <br/><br/><br/><br/><br/><br/>
      <p><b>Signature ----------------------------------------------------------</b> &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;
      <b><?php echo date("D M Y");?></b></p><br/>
      <br/><br/><br/><br/></b>
      <p style="text-align:center;"><i>*this is not final document certificate but summary of student score on specific Exam done.</i></p><br/><br/>
								<?php }
                            }else{?>
							<table width="100%" >
        <tr>
          <td>
          <span class="text-left " style="line-height: 8px;">
                <h2>KIARENI ELCK SECONDARY SCHOOL</h2>
                <h3>Official Transcript</h3>
                <h3><?php echo $term.'/'.$exam.'/'.date("Y");?></h3>
            </span>
            <h2 style="text-align:center;font-family: arial;">
                <a >
                    
                    <?php
                    $array = array(
                        "src" => base_url('uploads/images/'.$student->photo),
                        'width' => '100px',
                        'height' => '100px',
                        "style" => "margin-right:0px;"
                    );
                    echo img($array);
                    ?>
                </a>
                <table style="text-align:center;" width="100%">
                  <tr>
                    <td>
                        <h2 style="margin:0px;"> <b><?php  echo $student->name; ?></b></h2>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <h3 style="margin:0px;"> <b><?php  echo $this->lang->line("transcripts_classes")." ".$classes->classes; ?> </b>
                      </h3>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <h3 style="margin:0px;"> <b><?php  echo $this->lang->line("transcripts_roll")." ".$student->roll; ?></b>
                      </h3> 
                    </td>
                  </tr>
                </table>
            </h2>
          </td>
        </tr>
      </table>
      <br/><br/><br/><br/><br/>
      <?php
      echo "<table class=\"table  table-bordered table-condensed\">";
        echo "<tbody>";

          echo "<tr>";
              echo "<th><b>";
                  echo $this->lang->line("transcripts_subject_code");
              echo "</b></th>";
              echo "<th><b/>";
                  echo $this->lang->line("transcripts_subject_name");
              echo "</b></th>";
              if(count($grades)) {
                  echo "<th><b>";
                      echo $this->lang->line("transcripts_score");
                  echo "</b></th>";
                  echo "<th><b>";
                      echo $this->lang->line("transcripts_remarks");
                  echo "</b></th>";
                  echo "<th><b>";
                      echo $this->lang->line("transcripts_initials");
                  echo "</b></th>";
              }
          echo "</tr>";
        echo "</tbody>";
    echo "<tbody>";
    if ($classID==13 || $classID==14) {
      foreach ($transcriptss as $transcripts) {
      echo "<tr>";
          echo "<td data-title='".$this->lang->line('transcripts_subject_code')."'>";
              echo $transcripts->sc;
          echo "</td>";
          echo "<td data-title='".$this->lang->line('transcripts_subject')."'>";
              echo $transcripts->subject;
          echo "</td>";
          if(count($grades)) {
              foreach ($grades as $grade) {
                  if($grade->gradefrom <= $transcripts->mark && $grade->gradeupto >= $transcripts->mark) {
                      echo "<td data-title='".$this->lang->line('transcripts_grade')."'>";
                          echo $transcripts->mark.' '.$grade->grade;
                      echo "</td>";
                      if ($transcripts->sc ==102) {
                         echo "<td data-title='".$this->lang->line('transcripts_'.$transcripts->sc)."'>";
                              echo $this->lang->line('transcripts_'.$grade->note);
                          echo "</td>"; 
                      }else{
                          echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'>";
                          echo $grade->note;
                      echo "</td>";
                      }
                      break;
                  }
              }
          }
          echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'><i>";
              echo $transcripts->initials;
          echo "</i></td>";
          echo "</tr>";
      }
      echo "<tr>";
          echo "<td colspan=\"2\" class=\"text-right\">";
              echo "&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;<b>Exam summary (".$term.'/'.$exam.")</b>";
          echo "</td>";
           $avgpoints=round(($totalpoints/7),0);
              if(count($grades)) {
                  foreach ($grades as $grade) {
                      if($grade->point <= $avgpoints && $grade->point >= $avgpoints) {
                              $sgrade=$grade->grade;
                      }
                  }
              }
          echo "<td colspan=\"3\" >";
              echo "<b>Total Points:</b> ".$totalpoints."  <b> &nbsp; &nbsp; &nbsp;  Mean Grade:</b> ".$sgrade." <b>  &nbsp; &nbsp; &nbsp; Overall Position:</b> ".$position.'/'.$totalstudents;
          echo "</td>";
      echo "</tr>";
  }else{
      foreach ($transcriptss as $transcripts) {
              echo "<tr>";
                  echo "<td data-title='".$this->lang->line('transcripts_subject_code')."'>";
                      echo $transcripts->sc;
                  echo "</td>";
                  echo "<td data-title='".$this->lang->line('transcripts_subject')."'>";
                      echo $transcripts->subject;
                  echo "</td>";
                  if(count($grades)) {
                      foreach ($grades as $grade) {
                          if($grade->gradefrom <= $transcripts->mark && $grade->gradeupto >= $transcripts->mark) {
                              echo "<td data-title='".$this->lang->line('transcripts_grade')."'>";
                                  echo $transcripts->mark.' '.$grade->grade;
                              echo "</td>";
                              if ($transcripts->sc ==102) {
                                 echo "<td data-title='".$this->lang->line('transcripts_'.$transcripts->sc)."'>";
                                      echo $this->lang->line('transcripts_'.$grade->note);
                                  echo "</td>"; 
                              }else{
                                  echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'>";
                                  echo $grade->note;
                              echo "</td>";
                              }
                              
                              break;
                          }
                      }
                  }
                  echo "<td data-title='".$this->lang->line('transcripts_highest_mark')."'><i>";
                      echo $transcripts->initials;
                  echo "</i></td>";
              echo "</tr>";
      }
       echo "<tr>";
          echo "<td colspan=\"2\" class=\"text-right\">";
              echo "<b>Exam summary (".$term.'/'.$exam.")</b>";
          echo "</td>";
           $avgpoints=round(($totalpoints/11),0);
              if(count($grades)) {
                  foreach ($grades as $grade) {
                      if($grade->gradefrom <= $avgpoints && $grade->gradeupto >= $avgpoints) {
                              $ssgrade=$grade->grade;
                      }
                  }
              }
          echo "<td colspan=\"3\" >";
              echo "<span><b>Total Marks: ".$totalpoints."/1100</b></span> <span><b>Mean Grade: ".$ssgrade."</b></span> <span>  <b>Overall Position: ".$position.'/'.$totalstudents;
          echo "</b></span></td>";
      echo "</tr>";
  }
echo "</tbody>";
echo "</table>";
      ?>
      <b>
      <br/><br/><br/><br/><br/><br/>
      <p><b>Signature ----------------------------------------------------------</b> &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;
      <b><?php echo date("D M Y");?></b></p><br/>
      <br/><br/><br/><br/></b>
      <p style="text-align:center;"><i>*this is not final document certificate but summary of student score on specific Exam done.</i></p><br/><br/><?php
                            }
	
}
    ?>
</div>
<?php

} ?>

</div>
  </body>
<?php } ?>
</html>