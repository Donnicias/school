
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-code-fork"></i> <?=$this->lang->line('menu_reportforms_settings')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("reportforms/index/$classID")?>"><?=$this->lang->line('menu_reportforms_settings')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_reportforms_settings')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">

                <form class="form-horizontal" role="form" method="post">
                    <?php 
                        if(form_error('classesID')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="classesID" class="col-sm-2 control-label">
                            <?=$this->lang->line('reportforms_classes')?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array = array("0" => $this->lang->line("reportforms_select_classes"));
                                foreach ($classes as $classa) {
                                    $array[$classa->classesID] = $classa->classes;
                                }
                                echo form_dropdown("classesID", $array, set_value("classesID", $term->classesID), "id='classesID' class='form-control'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('term_id')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="term_id" class="col-sm-2 control-label">
                            <?=$this->lang->line('reportforms_term')?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array = array("0" => $this->lang->line("reportforms_select_term"));
                                if($terms != 0) {
                                    foreach ($terms as $termss) {
                                        $array[$termss->term_id] = $termss->term_name;
                                    }
                                }
                                echo form_dropdown("term_id", $array, set_value("term_id",$term->termID), "id='term_id' class='form-control'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('term_id'); ?>
                        </span>
                    </div>
                    
                    <div class='form-group'>

                        <label for="examID" class="col-sm-2 control-label">
                            <?=$this->lang->line("reportforms_select_exam")?>
                        </label>
                        <div class="col-sm-8">
                            <?php 
                            $examz=$this->reportforms_m->get_exams($term->examsID);
                            $no=count($examz);
                            $m=1;
                            echo "***Currently selected exams (";
                            foreach ($examz as $value) {
                                echo $value->exam;
                                if ($m<$no) {
                                    echo ",";
                                }
                                $m++;
                            }
                            echo ")***<br/>"; ?>
                           <?php
                           if ($exams !=0){
                            $i=1;
                            foreach ($exams as $value) {
                                echo form_label($value->exam, "exam".$i);
                                echo form_checkbox("exam".$i, $value->examID, false)."<br/>";
                                $i++;
                            }
                           }
                            
                           ?>
                        </div>
                    </div>

                    <?php 
                        if(form_error('note')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("reportforms_note")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea style="resize:none;" class="form-control" id="note" name="note"><?=set_value('note',$term->note)?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('note'); ?>
                        </span>
                    </div>
                    <?php 
                        if(form_error('closing_date')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="closing_date" class="col-sm-2 control-label">
                            <?=$this->lang->line("reportforms_closing_date")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="date" style="resize:none;" class="form-control" id="closing_date" name="closing_date" value="<?=set_value('closing_date',$term->closing_date)?>"/>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('closing_date'); ?>
                        </span>
                    </div>
                    <?php 
                        if(form_error('opening_date')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="opening_date" class="col-sm-2 control-label">
                            <?=$this->lang->line("reportforms_next_term_opening_date")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="date" style="resize:none;" class="form-control" id="opening_date" name="opening_date" value="<?=set_value('opening_date',$term->next_term_opening_date)?>"/>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('opening_date'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("edit_setting")?>" >
                        </div>
                    </div>
                </form>

            </div> <!-- col-sm-8 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<script type="text/javascript">
document.getElementById("uploadBtn").onchange = function() {
    document.getElementById("uploadFile").value = this.value;
};
$('#dob').datepicker({ startView: 2 });
$('#doa').datepicker({ startView: 2 });

$('#classesID').change(function(event) {
    var classesID = $(this).val();
    if(classesID === '0') {
        $('#classesID').val(0);
    } else {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('student/sectioncall')?>",
            data: "id=" + classesID,
            dataType: "html",
            success: function(data) {
               $('#sectionID').html(data);
            }
        });
    }
});


</script>
