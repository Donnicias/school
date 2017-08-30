
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-code-fork"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("reportforms/index")?>"><?=$this->lang->line('menu_reportforms_settings')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_reportforms_settings')?></li>
        </ol>
    </div><!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-8">
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
                                echo form_dropdown("classesID", $array, set_value("classesID", $set_classes), "id='classesID' class='form-control'");
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
                                    foreach ($terms as $term) {
                                        $array[$term->term_id] = $term->term_name;
                                    }
                                }
                                echo form_dropdown("term_id", $array, set_value("term_id", $set_term), "id='term_id' class='form-control'");
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
                        <div class="col-sm-6 col-xs-12">
                           <?php
                           if ($exams !=0){
                            $i=1;
                            foreach ($exams as $value) {
                            echo form_label($value->exam, "exam".$i)."&nbsp;";
                            echo form_checkbox("exam".$i, $value->examID, false)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
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
                            <textarea style="resize:none;" class="form-control" id="note" name="note"><?=set_value('note')?></textarea>
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
                            <input type="date" style="resize:none;" class="form-control" id="closing_date" name="closing_date"/>
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
                            <input type="date" style="resize:none;" class="form-control" id="opening_date" name="opening_date"/>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('opening_date'); ?>
                        </span>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_setting")?>" >
                        </div>
                    </div>
                </form>
            </div>    
        </div>
    </div>
</div>
<script type="text/javascript">
$("#classesID").change(function() {
var id = $(this).val();
if(parseInt(id)) {
    if(id === '0') {
        $('#subjectID').val(0);
    } else {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('mark/subjectcall')?>",
            data: {"id" : id},
            dataType: "html",
            success: function(data) {
               $('#subjectID').html(data);
            }
        });
    }
}
});
</script>