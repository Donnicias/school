
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-flask"></i> <?=$this->lang->line('panel_title')?></h3>
       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("examoutofsettings/index")?>"><?=$this->lang->line('menu_examoutofsettings')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_subjectmarkoutof')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="col-sm-6 col-sm-offset-3 list-group">
                    <div class="list-group-item list-group-item-warning">
                        <form class="form-horizontal" method="post" >
                            <?php
                                if(form_error('year')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="year" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('examoutofsettings_year')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("examoutofsettings_select_year"));
                                        if($years != 0) {
                                            foreach ($years as $yr) {
                                                $array[$yr->year] = $yr->year;
                                            }
                                        }
                                        echo form_dropdown("year", $array, set_value("year", $set_year), "id='year' class='form-control'");
                                    ?>
                                </div>
                            </div>

                            <?php 
                                if(form_error('term_id')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="examID" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('examoutofsettings_term')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("examoutofsettings_select_term"));
                                        foreach ($terms as $term) {
                                            $array[$term->term_id] = $term->term_name;
                                        }
                                        echo form_dropdown("term_id", $array, set_value("term_id", $set_term), "id='term_id' class='form-control'");
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
                                    <?=$this->lang->line('examoutofsettings_exam')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("examoutofsettings_select_exam"));
                                        foreach ($exams as $exam) {
                                            $array[$exam->examID] = $exam->exam;
                                        }
                                        echo form_dropdown("examID", $array, set_value("examID", $set_exam), "id='examID' class='form-control'");
                                    ?>
                                </div>
                            </div>


                            <?php 
                                if(form_error('classesID')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="classesID" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('examoutofsettings_classes')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("examoutofsettings_select_classes"));
                                        foreach ($classes as $classa) {
                                            $array[$classa->classesID] = $classa->classes;
                                        }
                                        echo form_dropdown("classesID", $array, set_value("classesID", $set_classes), "id='classesID' class='form-control'");
                                    ?>
                                </div>
                            </div>
                            <?php 
                                if(form_error('subjectID')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="subjectID" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('examoutofsettings_subject')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("examoutofsettings_select_subject"));
                                        if($subjects != 0) {
                                            foreach ($subjects as $subject) {
                                                $array[$subject->subjectID] = $subject->fullsubjectname;
                                            }
                                        }
                                        echo form_dropdown("subjectID", $array, set_value("subjectID", $set_subject), "id='subjectID' class='form-control'");
                                    ?>
                                </div>
                            </div>


                            <?php 
                                if(form_error('outof')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="outof" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('examoutofsettings_outof')?>
                                </label>
                                <div class="col-sm-6" id="out_of_mark">
                                    <input type="text" class="form-control" id="outof" name="outof" value="<?=set_value('outof',$set_out_of)?>" >
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-4 col-sm-8">
                                    <input type="submit" class="btn btn-success" style="margin-bottom:0px" value="<?=$this->lang->line("add_mark")?>" >
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="hide-table">
                <?php if(count($subjectsmarks)) { ?>
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('examoutofsettings_subject')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('examoutofsettings_term')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('examoutofsettings_year')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('examoutofsettings_examoutof')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($subjectsmarks)) {
                                $i = 1; foreach($subjectsmarks as $subject) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('examoutofsettings_subject')?>">
                                        <?php 
                                            echo $subject->subject; 
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('examoutofsettings_term')?>">
                                        <?php echo $subject->term_name; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('examoutofsettings_year')?>">
                                        <?php echo $subject->year; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('examoutofsettings_examoutof')?>">
                                        <?php echo $subject->subjectMark; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php
                                        if ($subject->mark !='') {
                                            ?>
                                            <label class="label label-info">Not Editable!</label>
                                            <?php
                                        }else{
                                            echo btn_edit('examoutofsettings/edit/'.$subject->classesID."/".$subject->subjectID."/".$examID."/".$term_id."/".$year, $this->lang->line('edit'));
                                        }
                                        
                                        echo btn_delete('examoutofsettings/delete/'.$subject->classesID."/".$subject->subjectID."/".$examID."/".$term_id."/".$year, $this->lang->line('delete'));
                                        ?>
                                    </td>
                                </tr>
                            <?php $i++; }} ?>
                            
                        </tbody>
                    </table>
                    <?php } ?>
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
        $('#subjectID').val(0);
    } else {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('examoutofsettings/subjectcall')?>",
            data: {"id" : id},
            dataType: "html",
            success: function(data) {
               $('#subjectID').html(data);
            }
        });
    }
}
});
$('#subjectID').change(function(){
    var sid=$(this).val();
    var cid=$("#classesID").val();
    var eid=$("#examID").val();
    var tid=$("#term_id").val();
    var year=$("#year").val();
if(parseInt(sid) && parseInt(cid) && parseInt(eid) && parseInt(tid) && parseInt(year))  {
    if(sid === '0' && cid==='0' && eid==='0' && tid==='0' && year==='0') {
        $('#out_of_mark').val(0);
    } else {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('examoutofsettings/outofmarkcall')?>",
            data: {"sid" : sid,"cid":cid,"eid":eid,"tid":tid,"year":year},
            dataType: "html",
            success: function(data) {
                if(data !=''){
                    $('#out_of_mark').html(data);
                }else{
                    $('#outof').val(0);
                }
            }
        });
    }
}
});
</script>