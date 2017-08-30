
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-student"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("student/index")?>"><?=$this->lang->line('menu_student')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-8">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    
                    <?php 
                        if(form_error('doa')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="dob" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_doa")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="doa" name="doa" value="<?=set_value('doa')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('doa'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('name')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="name_id" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_name")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="name_id" name="name" value="<?=set_value('name')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('name'); ?>
                        </span>
                    </div>


                    <?php 
                        if(form_error('guargianID')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="guargianID" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_guargian")?>
                        </label>
                        <div class="col-sm-6">
                            <div class="select2-wrapper">
                                <?php
                                    $array = array('' => '');
                                    foreach ($parents as $parent) {
                                        $array[$parent->parentID] = $parent->name." (" . $parent->email ." )";
                                    }
                                    echo form_dropdown("guargianID", $array, set_value("guargianID"), "id='guargianID' class='form-control guargianID'");
                                ?>
                            </div>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('guargianID'); ?>
                        </span>
                    </div>



                    <?php 
                        if(form_error('dob')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="dob" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_dob")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="dob" name="dob" value="<?=set_value('dob')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('dob'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('sex')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="sex" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_sex")?>
                        </label>
                        <div class="col-sm-6">
                            <?php 
                                echo form_dropdown("sex", array($this->lang->line('student_sex_male') => $this->lang->line('student_sex_male'), $this->lang->line('student_sex_female') => $this->lang->line('student_sex_female')), set_value("sex"), "id='sex' class='form-control'"); 
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('sex'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('religion')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="religion" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_religion")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="religion" name="religion" value="<?=set_value('religion')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('religion'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('email')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="email" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_email")?>
                        </label>
                        <div class="col-sm-6" id="emailf">
                            <input type="text" class="form-control" id="email" name="email" value="<?=set_value('email')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('email'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('phone')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="phone" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_phone")?>
                        </label>
                        <div class="col-sm-6" id="phonef">
                            <input type="text" class="form-control" id="phone" name="phone" value="<?=set_value('phone')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('phone'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('address')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="address" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_address")?>
                        </label>
                        <div class="col-sm-6" id="addressf">
                            <input type="text" class="form-control" id="address" name="address" value="<?=set_value('address')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('address'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('classesID')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="classesID" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_classes")?>
                        </label>
                        <div class="col-sm-6">
                           <?php
                                $array = array(0 => $this->lang->line("student_select_class"));
                                foreach ($classes as $classa) {
                                    $array[$classa->classesID] = $classa->classes;
                                }
                                echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('sectionID')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="sectionID" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_section")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array = array(0 => $this->lang->line("student_select_section"));
                                if($sections != "empty") {
                                    foreach ($sections as $section) {
                                        $array[$section->sectionID] = $section->section;
                                    }
                                }

                                $sID = 0;
                                if($sectionID == 0) {
                                    $sID = 0;
                                } else {
                                    $sID = $sectionID;
                                }
                                
                                echo form_dropdown("sectionID", $array, set_value("sectionID", $sID), "id='sectionID' class='form-control'");

                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('sectionID'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('roll')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="roll" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_roll")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="roll" name="roll" value="<?=set_value('roll')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('roll'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('former_primary')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="former_primary" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_former_primary")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="former_primary" name="former_primary" value="<?=set_value('former_primary')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('former_primary'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('kcpe_year')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="kcpe_year" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_kcpe_year")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="kcpe_year" name="kcpe_year" value="<?=set_value('kcpe_year')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('kcpe_year'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('kcpe_mark')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="kcpe_mark" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_kcpe_mark")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="kcpe_mark" name="kcpe_mark" value="<?=set_value('kcpe_mark')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('kcpe_mark'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('kcpe_index')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="kcpe_index" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_kcpe_index")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="kcpe_index" name="kcpe_index" value="<?=set_value('kcpe_index')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('kcpe_index'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('parent_status')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="parent_status" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_parent_status")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="parent_status" name="parent_status" value="<?=set_value('parent_status')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('parent_status'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('health')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="parent_status" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_health")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="health" name="health" value="<?=set_value('health')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('health'); ?>
                        </span>
                    </div>

                    <?php 
                        if(isset($image)) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="photo" class="col-sm-2 control-label col-xs-8 col-md-2">
                            <?=$this->lang->line("student_photo")?>
                        </label>
                        <div class="col-sm-4 col-xs-6 col-md-4">
                            <input class="form-control"  id="uploadFile" placeholder="Choose File" disabled />  
                        </div>

                        <div class="col-sm-2 col-xs-6 col-md-2">
                            <div class="fileUpload btn btn-success form-control">
                                <span class="fa fa-repeat"></span>
                                <span><?=$this->lang->line("upload")?></span>
                                <input id="uploadBtn" type="file" class="upload" name="image" />
                            </div>
                        </div>
                         <span class="col-sm-4 control-label col-xs-6 col-md-4">
                           
                            <?php if(isset($image)) echo $image; ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('username')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="username" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_username")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="username" name="username" value="<?=set_value('username')?>" >
                        </div>
                         <span class="col-sm-4 control-label">
                            <?php echo form_error('username'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('password')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="password" class="col-sm-2 control-label">
                            <?=$this->lang->line("student_password")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="password" class="form-control" id="password" name="password" value="<?=set_value('password')?>" >
                        </div>
                         <span class="col-sm-4 control-label">
                            <?php echo form_error('password'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_student")?>" >
                        </div>
                    </div>
                </form>
            </div> <!-- col-sm-8 -->
            
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<script type="text/javascript">


$('#dob').datepicker({ startView: 2 });
    document.getElementById("uploadBtn").onchange = function() {
    document.getElementById("uploadFile").value = this.value;
};

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

$('#doa').datepicker({ startView: 2 });

$('#guargianID').change(function(event) {
    var guargianID = $(this).val();
    if(guargianID === '0') {
        $('#addressf').val(0);
    } else {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('student/datacall')?>",
            data: "id=" + guargianID,
            dataType: "html",
            success: function(data) {
               $('#addressf').html(data);
            }
        });
    }
});
$('#guargianID').change(function(event) {
    var guargianID = $(this).val();
    if(guargianID === '0') {
        $('#phonef').val(0);
    } else {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('student/datacallphone')?>",
            data: "id=" + guargianID,
            dataType: "html",
            success: function(data) {
               $('#phonef').html(data);
            }
        });
    }
});
$('#guargianID').change(function(event) {
    var guargianID = $(this).val();
    if(guargianID === '0') {
        $('#emailf').val(0);
    } else {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('student/datacallemail')?>",
            data: "id=" + guargianID,
            dataType: "html",
            success: function(data) {
               $('#emailf').html(data);
            }
        });
    }
});


</script>
