<!DOCTYPE html5>
<html lang="en">
<head>
<title><?php echo $panel_title; ?></title>

<style type="text/css">
    #page-wrap {
        width: 700px;
        margin: 0 auto;
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
        border: 1px solid #282828  !important;
      }
    }
    table {
      max-width: 100%;
      background-color: transparent;
      font-size: 10px;
    }
    th {
      text-align: left;
    }
    .table {
      width: 100%;
      margin-bottom: 20px;
    }
    .table h4 {
      font-size: 10px;
      padding: 0px;
      margin: 0px;
    }
    .head {
       border-top: 0px solid #282828;
       border-bottom: 0px solid #282828;  
    }
    .table > thead > tr > th,
    .table > tbody > tr > th,
    .table > tfoot > tr > th,
    .table > thead > tr > td,
    .table > tbody > tr > td,
    .table > tfoot > tr > td {
      padding: 3px;
      line-height: 1.428571429;
      vertical-align: top;
      /*border-top: 1px solid #e2e7eb; */
    }
    /*ini edit default value : border top 1px to 0 px*/
    .table > thead > tr > th {
      font-size: 10px;
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
      border-top: 2px solid #383838;
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
      padding: 2px;
    }
    .table-bordered {
      border: 1px solid #383838;
    }
    .table-bordered > thead > tr > th,
    .table-bordered > tbody > tr > th,
    .table-bordered > tfoot > tr > th,
    .table-bordered > thead > tr > td,
    .table-bordered > tbody > tr > td,
    .table-bordered > tfoot > tr > td {
      border: 1px solid #383838;
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
  if($usertype == "Admin" || $usertype == "Teacher"){
?>
  <body>
    <div id="page-wrap">
      <table width="100%">
        <tr>
          <td>
            <h2 style="text-align:center">
              <?php
                if($siteinfos->photo) {
                    $array = array(
                        "src" => base_url('uploads/images/'.$siteinfos->photo),
                        'width' => '90px',
                        'height' => '90px',
                        "style" => "margin-right:0px;"
                    );
                    echo img($array)."<br>";
                } 
                echo $siteinfos->sname."<br/>";
                foreach ($section as $section) {
                  echo "Mark Sheet for ".$section->section."<br/>";
                }
                foreach ($term as $value) {
                   echo $value->term_name."/ ".date("Y")." <br/>";
                }
                foreach ($exams as $exam) {
                  echo $exam->exam."<br/>";
                }
                echo "<hr/>";
                ?>
            </h2>
          </td>
        </tr>
      </table>
      <table class="table table-bordered">
        <thead>
            <tr>
                <th colspan="6" style="border-top:0px;border-left:0px;"><b>Subject Code</b></th>
                <?php
                foreach ($subjects as $subject) {
                      ?>
                      <th colspan="2"><b><?php 
                      echo $subject->subject_code;
                      ?></b></th>
                      <?php
                    }
                ?>
              </tr>
            <tr>
            <th colspan="2"><h4><?=$this->lang->line("marksheets_no")?></h4></th>
            <th colspan="2"><h4><?=$this->lang->line("marksheets_roll")?></h4></th>
            <th colspan="2"><h4><?=$this->lang->line("marksheets_student_name")?></h4></th>
            <?php
            foreach ($subjects as $subject) {
                  ?>
                  <th colspan="2"><h4><?php echo $subject->subject;?></h4></th>
                  <?php
                }
            ?>
          </tr>
        </thead>
        <tbody>
        <?php
        $i=1;
          foreach ($students as $student) {
            ?>
            <tr>
              <td colspan="2"><?php echo $i.". ";?></td>
              <td colspan="2"><?php echo $student->roll;?></td>
              <td colspan="2"><?php echo $student->name;?></td>
              <?php
              foreach ($subjects as $subject) {
                    ?>
                    <td colspan="2"></td>
                    <?php
                  }
              ?>
            </tr>
            <?php
            $i++;
          }
        ?>
        </tbody> 
      </table>  
    </div>
  </body>
<?php } ?>
</html>