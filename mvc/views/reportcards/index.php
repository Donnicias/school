
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="glyphicon glyphicon-list-alt"></i> <?=$this->lang->line('menu_reportforms_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_reportforms')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-info text-center" role="alert"> <strong>Select class, year and term to view combined exam results for the term, year and class.</strong></div>
                <div class="col-sm-6 col-sm-offset-3 list-group">
                    <div class="list-group-item list-group-item-warning">
                        <form class="form-horizontal" method="post" action="print_preview">
                            <?php 
                                if(form_error('classesID')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="classesID" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('reportcards_classes')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("reportcards_select_classes"));
                                        foreach ($classes as $classa) {
                                            $array[$classa->classesID] = $classa->classes;
                                        }
                                        echo form_dropdown("classesID", $array, set_value("classesID", null), "id='classesID' class='form-control'");
                                    ?>
                                </div>
                            </div>

                            <?php
                                if(form_error('year')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="year" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('reportcards_year')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("reportcards_select_year"));
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
                                if(form_error('termID')) 
                                    echo "<div class='form-group has-error' >";
                                else     
                                    echo "<div class='form-group' >";
                            ?>
                                <label for="termID" class="col-sm-2 col-sm-offset-2 control-label">
                                    <?=$this->lang->line('reportcards_term')?>
                                </label>
                                <div class="col-sm-6">
                                    <?php
                                        $array = array("0" => $this->lang->line("reportcards_select_term"));
                                        if($terms != 0) {
                                            foreach ($terms as $term) {
                                                $array[$term->term_id] = $term->term_name;
                                            }
                                        }
                                        echo form_dropdown("termID", $array, set_value("termID", $set_term), "id='termID' class='form-control'");
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
 $('#termID').change(function() {
        var termID = $(this).val();
        var classID = $('#classesID').val();
        var year = $('#year').val();
        if(classID == 0 || year== 0 || termID == 0) {
            $('#hide-table').hide();
            alert("Hey? You must select class and year first!");
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('reportcards/reportforms_list')?>",
                data:{year: year,classID: classID,termID:termID},
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>