
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-code-fork"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("reportforms/index/$classID")?>"><?=$this->lang->line('menu_reportforms_settings')?></a></li>
            <li class="active"><?=$class?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php 
                    $usertype = $this->session->userdata("usertype");
                    if($usertype == "Admin" || $usertype == "Teacher") {
                ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('reportforms/add') ?>">
                            <i class="fa fa-plus-circle"></i> 
                            <?=$this->lang->line('filter_title')?>
                        </a>
                    </h5>
                <?php } ?>

                <div class="col-sm-6 col-sm-offset-3 list-group text-center">
                    <div class="list-group-item list-group-item-warning">
                        <form style="" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">  

                            <?php 
                                if(form_error('name')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="name_id" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line("reportforms_classes")?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("reportforms_select_classes"));
                                        foreach ($classes as $classa) {
                                            $array[$classa->classesID] = $classa->classes;
                                        }
                                        echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control'");
                                    ?>
                                </div>
                            </div>

                            <?php 
                                if(form_error('examID')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="year" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line("reportforms_year")?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("reportforms_select_year"));
                                        echo form_dropdown("year", $array, set_value("year"), "id='year' class='form-control'");
                                    ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> <!-- col-sm-6 --> 
                
                <div class="col-sm-12">
                    <div id="hide-table">
                    <strong>
                        <h2 class="text-center">Current Class: <?php echo $class;?></h2>
                    </strong>
                
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('reportforms_number')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('reportforms_term')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('reportforms_exams')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('reportforms_year')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('reportforms_closing_date')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('reportforms_opening_date')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('reportforms_note')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($reportforms) {
                                $i=1;
                                $j=1;

                             foreach($reportforms as $setting) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('reportforms_number')?>">
                                        <span class="badge"><?php echo $j; ?></span>
                                    </td>
                                    <td data-title="<?=$this->lang->line('reportforms_term')?>">
                                        <?php echo $setting->term_name; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('reportforms_exams')?>">
                                        <?php 
                                        $exams=$this->reportforms_m->get_exams($setting->examsID);
                                        $no=count($exams);
                                        $i=1;
                                        echo "(";
                                        foreach ($exams as $value) {
                                            echo $value->exam;
                                            if ($i<$no) {
                                                echo ",";
                                            }
                                            $i++;
                                        }
                                        echo ")"; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('reportforms_year')?>">
                                        <?php echo $setting->year; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('reportforms_year')?>">
                                        <?php echo $setting->closing_date; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('reportforms_year')?>">
                                        <?php echo $setting->next_term_opening_date; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('reportforms_note')?>">
                                        <?php echo $setting->note; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php 
                                            if($usertype == "Admin" || $usertype == "Teacher") {
                                                echo btn_edit('reportforms/edit/'.$setting->rf_ID.'/'.$classID, $this->lang->line('edit'));
                                                echo btn_delete('reportforms/delete/'.$setting->rf_ID.'/'.$classID, $this->lang->line('delete'));
                                            }

                                        ?>
                                    </td>
                                </tr>
                            <?php  $j++;}
                        } ?>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$("#classesID").change(function() {
var classID = $(this).val();
if(parseInt(classID)) {
    if(classID === '0') {
        $('#year').val(0);
    } else {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('reportforms/year_call')?>",
            data: {"classID" : classID},
            dataType: "html",
            success: function(data) {
               $('#year').html(data);
            }
        });
    }
}
});
    $('#year').change(function() {
        var year = $(this).val();
        var classID = $('#classesID').val();
        if(classID == 0 || year== 0) {
            $('#hide-table').hide();
            alert("Hey? You must select class first!");
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('reportforms/reportforms_list')?>",
                data:{year: year,classID: classID},
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });

</script>