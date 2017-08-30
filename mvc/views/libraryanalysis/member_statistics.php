

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-lbooks"></i> <?=$this->lang->line('menu_library_analysis')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_member_statistics')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
                    $usertype = $this->session->userdata("usertype");
                    if($usertype == "Admin" || $usertype == "Librarian") {
                ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('libraryanalysis/general') ?>">
                            <i class="fa fa-book"></i> 
                            <?=$this->lang->line('view_book_statistics')?>
                        </a>
                    </h5>
                <?php } ?>


                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('libraryanalysis_stream')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('libraryanalysis_total_students')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('libraryanalysis_total_members')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('libraryanalysis_nonemembers')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(count($sections_data)) {$i = 1; foreach($sections_data as $data) { 
                                $membership_data=$this->student_m->get_registered_members_per_stream($data->stream);
                                ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('libraryanalysis_stream')?>">
                                        <?php echo $data->stream; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('libraryanalysis_total_students')?>">
                                        <?php echo $data->total_students; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('libraryanalysis_total_members')?>">
                                        <?php echo $membership_data->registered; ?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('libraryanalysis_nonemembers')?>">
                                        <?php echo ($data->total_students - $membership_data->registered); ?>
                                    </td>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <div class="row well" style="margin: 5px;">
            <div class="col-sm-12 col-xs-12">
                <!-- Chart code -->
               <h1 class="page-header text-center">Graph of students' library registration per stream</h1>
                <?php
                //require_once("book_statistics_data.php");
                    ?>
                <script>
                var chart = AmCharts.makeChart("chartdiv", {
                    "theme": "light",
                    "type": "serial",
                    "dataLoader": {
                        "url": "<?=base_url('libraryanalysis/member_statistics_graph_data')?>"
                      },
                    "valueAxes": [{
                        "position": "left",
                        "title": "Total Number of Students",
                    }],
                    "startDuration": 1,
                    "graphs": [{
                        "balloonText": "Total students in the class: <b>[[value]]</b>",
                        "fillAlphas": 0.9,
                        "lineAlpha": 0.2,
                        "type": "column",
                        "valueField": "totalStudents"
                    }, {
                        "balloonText": "Number of students registered: <b>[[value]]</b>",
                        "fillAlphas": 0.9,
                        "lineAlpha": 0.2,
                        "type": "column",
                        "clustered":false,
                        "columnWidth":0.5,
                        "valueField": "totalMembers"
                    }],
                    "plotAreaFillAlphas": 0.1,
                    "categoryField": "stream",
                    "categoryAxis": {
                        "gridPosition": "start"
                    },
                    "export": {
                        "enabled": true
                     }

                });
                </script>

                <!-- HTML -->
                <div id="chartdiv" style="width: 100%; height: 400px;"></div>       
            </div>
        </div> 
    </div>
</div>
