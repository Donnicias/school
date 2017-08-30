

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-bar-chart-o"></i> <?=$this->lang->line('menu_library_analysis')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_lost_books')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <!-- Chart code -->
                <?php
                if (count($bookslost)) {
                ?>
               <h2 class="page-header text-center">GRAPHICAL SUMMARY OF LOST BOOKS PER DAY</h2>
                <script>
                    var chart = AmCharts.makeChart("chartdiv", {
                        "type": "serial",
                        "theme": "light",
                        "marginRight": 40,
                        "marginLeft": 40,
                        "autoMarginOffset": 20,
                        "mouseWheelZoomEnabled":true,
                        "dataDateFormat": "YYYY-MM-DD",
                        "valueAxes": [{
                            "id": "v1",
                            "axisAlpha": 0,
                            "position": "left",
                            "ignoreAxisWidth":true
                        }],
                        "balloon": {
                            "borderThickness": 1,
                            "shadowAlpha": 0
                        },
                        "graphs": [{
                            "id": "g1",
                            "balloon":{
                              "drop":true,
                              "adjustBorderColor":false,
                              "color":"#ffffff"
                            },
                            "bullet": "round",
                            "bulletBorderAlpha": 1,
                            "bulletColor": "#FFFFFF",
                            "bulletSize": 5,
                            "hideBulletsCount": 50,
                            "lineThickness": 2,
                            "title": "red line",
                            "useLineColorForBulletBorder": true,
                            "valueField": "total",
                            "balloonText": "<span style='font-size:18px;'>[[value]]</span>"
                        }],
                        "chartScrollbar": {
                            "graph": "g1",
                            "oppositeAxis":false,
                            "offset":30,
                            "scrollbarHeight": 80,
                            "backgroundAlpha": 0,
                            "selectedBackgroundAlpha": 0.1,
                            "selectedBackgroundColor": "#888888",
                            "graphFillAlpha": 0,
                            "graphLineAlpha": 0.5,
                            "selectedGraphFillAlpha": 0,
                            "selectedGraphLineAlpha": 1,
                            "autoGridCount":true,
                            "color":"#AAAAAA"
                        },
                        "chartCursor": {
                            "pan": true,
                            "valueLineEnabled": true,
                            "valueLineBalloonEnabled": true,
                            "cursorAlpha":1,
                            "cursorColor":"#258cbb",
                            "limitToGraph":"g1",
                            "valueLineAlpha":0.2,
                            "valueZoomable":true
                        },
                        "valueScrollbar":{
                          "oppositeAxis":false,
                          "offset":50,
                          "scrollbarHeight":10
                        },
                        "categoryField": "ldate",
                        "categoryAxis": {
                            "parseDates": true,
                            "dashLength": 1,
                            "minorGridEnabled": true
                        },
                        "export": {
                            "enabled": true
                        },
                        "dataLoader": {
                            "url": "<?=base_url('libraryanalysis/lost_books_graph_data')?>"
                          }
                    });

                    chart.addListener("rendered", zoomChart);

                    zoomChart();

                    function zoomChart() {
                        chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
                    }
                    </script>

                    <!-- HTML -->
                    <div id="chartdiv"  style="width: 100%; height: 400px;"></div>
                <?php
                    }else{
                        ?>
                            <div class="alert alert-info" role="alert"><i class="fa fa-info-circle fa-3x"></i><strong>There are no lost books so far...</strong></div>
                            <div class="jumbotron">
                              <p><a class="btn btn-default btn-lg" href="<?php echo base_url('libraryanalysis/general') ?>" role="button"><strong>Click to view detailed books statistics <i class="fa fa-hand-lizard-o"></i></strong></a></p>
                            </div>
                        <?php
                    }
                ?>
            </div>
        </div> 
    </div>
</div>
