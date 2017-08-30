
<div class="form-box" id="login-box" style="background:url('uploads/images/gray.jpg');" >
    <div class="header bg-olive" id="login-header"><?=$this->lang->line('signin');?></div>

    <form method="post">

        <!-- style="margin-top:40px;" -->

        <div class="body bg-white">
        <?php
            if($form_validation == "No"){
            } else {
                if(count($form_validation)) {
                    echo "<div class=\"alert alert-danger alert-dismissable\">
                        <i class=\"fa fa-ban\"></i>
                        <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>
                        $form_validation
                    </div>";
                }
            }
            if($this->session->flashdata('reset_success')) {
                $message = $this->session->flashdata('reset_success');
                echo "<div class=\"alert alert-success alert-dismissable\">
                    <i class=\"fa fa-ban\"></i>
                    <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>
                    $message
                </div>";
            }
        ?>
            <div class="form-group">
                <div class="input-group">
                <span class="input-group-addon" id="addon-username-field"><i class="fa fa-user fa-fw"></i></span>
                <input class="form-control" placeholder="Username" name="username" type="text" id="addon-username-field">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                <span class="input-group-addon" id="addon-pass-field"><i class="fa fa-lock fa-fw"></i></span>
                <input class="form-control" placeholder="Password" name="password" type="password" id="addon-pass-field">
                </div>
            </div>


            <div class="form-group">
                <label>
                    <input id="btn-reset-login"  type="reset" class="btn refresh-btn btn-link" value="Reset form" name="reset_form">
                </label>
                <span>
                    <label class="pull-right">
                       <button id="btn-forgot-pass" class="btn btn-link"><a href="<?=base_url('reset/index')?>"> Forgot Password ?</a></button>
                    </label>
                </span>
            </div>
            <button id="btn-login" type="submit" class="btn btn-success btn-lg  bg-olive btn-block " value="SIGN IN" />LOGIN <i class="fa fa-sign-in"></i></button>
        </div>
    </form>
    <br/>
    <strong><p class="text-center"> &copy; <?php if(count($siteinfos)) { echo date("Y")."- ".$siteinfos->sname; } ?> <span> All rights reserved</span></p>
    <p class="text-center"><a href="http://www.niftecs.com">Powered by Niftecs</a></p></strong>
</div>
