
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-file"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active">Mark Sheets</li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <div class="col-sm-6 col-sm-offset-3 list-group">
                    <div class="list-group-item list-group-item-warning">
                        <form class="form-horizontal">
                            <?php 
                                if(form_error('classesID')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="classesID" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('mark_classes')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("mark_select_classes"));
                                        foreach ($classes as $classa) {
                                            $array[$classa->classesID] = $classa->classes;
                                        }
                                        echo form_dropdown("classesID", $array, set_value("classesID", null), "id='classesID' class='form-control'");
                                    ?>
                                </div>
                            </div>
                            <?php 
                                if(form_error('sectionID')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="subjectID" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('mark_section')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("mark_select_section"));
                                        if($sections != 0) {
                                            foreach ($sections as $section) {
                                                $array[$section->sectionID] = $section->section;
                                            }
                                        }
                                        echo form_dropdown("sectionID", $array, set_value("sectionID", $set_section), "id='sectionID' class='form-control'");
                                    ?>
                                </div>
                            </div>

                            <?php 
                                if(form_error('examID')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="examID" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('mark_exam')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("mark_select_exam"));
                                        foreach ($exams as $exam) {
                                            $array[$exam->examID] = $exam->exam;
                                        }
                                        echo form_dropdown("examID", $array, set_value("examID", $set_exam), "id='examID' class='form-control'");
                                    ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$("#classesID").change(function() {
var id = $(this).val();
if(parseInt(id)) {
    if(id === '0') {
        $('#sectionID').val(0);
    } else {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('marksheets/sectioncall')?>",
            data: {"id" : id},
            dataType: "html",
            success: function(data) {
               $('#sectionID').html(data);
            }
        });
    }
}
});
$('#examID').change(function() {
    var examID = $(this).val();
    var classesID = $('#classesID').val();
    var sectionID = $('#sectionID').val();
    if(examID== 0 || classesID== 0) {
        alert("Hey? You must select exam and class");
    } else {
        if ( sectionID== 0) {
            sectionID ="all";
             $.ajax({
                type: 'POST',
                url: "<?=base_url('marksheets/marksheets_list')?>",
                data:{classesID: classesID,sectionID: sectionID,examID: examID},
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }else{
            $.ajax({
                type: 'POST',
                url: "<?=base_url('marksheets/marksheets_list')?>",
                data:{classesID: classesID,sectionID: sectionID,examID: examID},
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    }
});
</script>