
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-share"></i> <?=$this->lang->line('menu_library_analysis')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><?php echo anchor('libraryanalysis/borrowing', '<i class="fa fa-sharre"></i><span>Search</span>'); ?></li>
            <li class="active"><?=$this->lang->line('menu_borrowing')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12 col-xs-12">

                <div class="col-sm-6 col-sm-offset-3 list-group">
                    <div class="list-group-item list-group-item-warning">
                        <form style="" class="form-horizontal" role="form" method="post">  
                            <div class="form-group">              
                                <label for="classesID" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line("libraryanalysis_classes")?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("lmember_select_class"));
                                        foreach ($classes as $classa) {
                                            $array[$classa->classesID] = $classa->classes;
                                        }
                                        echo form_dropdown("classesID", $array, set_value("classesID", $set), "id='classesID' class='form-control'");
                                    ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <section id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('libraryanalysis_photo')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('libraryanalysis_name')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('libraryanalysis_stream')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('libraryanalysis_total_borrowing')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('libraryanalysis_current_borrowing')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('libraryanalysis_total_lost')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('libraryanalysis_total_lost_but_found')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                            </tr>
                        </thead>
                        <tbody id="list">
                            <?php
                             if(count($students)) {$i = 1; foreach($students as $student) {
                                $total_borrowings=$this->libraryanalysis_m->get_students_total_borrowing($student->studentID);
                                $current_borrowings=$this->libraryanalysis_m->get_students_current_borrowing($student->studentID);
                                $total_lost=$this->libraryanalysis_m->get_students_total_lost($student->studentID);
                                $total_lost_bt_found=$this->libraryanalysis_m->get_students_total_lost_but_found($student->studentID);
                                //echo "total borrowing: ".$total_lost_bt_found->total_lost_bt_found."<br/>";
                              ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('libraryanalysis_photo')?>">
                                        <?php $array = array(
                                                "src" => base_url('uploads/images/'.$student->photo),
                                                'width' => '35px',
                                                'height' => '35px',
                                                'class' => 'img-rounded'

                                            );
                                            echo img($array); 
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('libraryanalysis_name')?>">
                                        <?php echo $student->name; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('libraryanalysis_stream')?>">
                                        <?php echo $student->ssection; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('libraryanalysis_total_borrowing')?>">
                                        <?php echo $total_borrowings->total_borrowing; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('libraryanalysis_current_borrowing')?>">
                                        <?php echo $current_borrowings->current_borrowing; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('libraryanalysis_total_lost')?>">
                                        <?php echo $total_lost->total_lost; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('libraryanalysis_total_lost')?>">
                                        <?php echo $total_lost_bt_found->total_lost_bt_found; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('action')?>">

                                        <?php
                                            echo btn_view('lmember/view/'.$student->studentID."/".$set, $this->lang->line('view')). " ";
                                            
                                        ?>
                                    </td>
                                </tr>
                            <?php $i++; }} ?>
                            
                        </tbody>
                    </table>
                </section>

            </div> <!-- col-sm-12 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<script type="text/javascript">
    $('#classesID').change(function() {
        var classesID = $(this).val();
        if(classesID == 0) {
            $('#hide-table').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('libraryanalysis/student_list')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>