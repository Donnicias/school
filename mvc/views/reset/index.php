
<div class="form-box" id="login-box">
    <div class="header" id="reset-header">Reset Password</div>
    <form role="form" method="post">
        <div class="body white-bg">

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

                if($this->session->flashdata('reset_send')) {
                    $message = $this->session->flashdata('reset_send');
                    echo "<div class=\"alert alert-success alert-dismissable\">
                        <i class=\"fa fa-ban\"></i>
                        <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>
                        $message
                    </div>";
                } else {
                    if($this->session->flashdata('reset_error')) {
                        $message = $this->session->flashdata('reset_error');
                        echo "<div class=\"alert alert-danger alert-dismissable\">
                            <i class=\"fa fa-ban\"></i>
                            <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>
                            $message
                        </div>";
                    }
                }
            ?>
            <div class="form-group">
                <div class="input-group">
                <span class="input-group-addon" id="addon-reset-pass"><i class="fa fa-user"></i></span>
                <input  class="form-control" placeholder="Email" name="email" type="text" id="addon-reset-pass">
                 </div>
            </div>

            <button id="btn-reset" type="submit" class="btn btn-lg  bg-olive "/>SEND &nbsp;&nbsp; <i class="fa fa-sign-in"></i></button
        </div>
    </form>
    <br/><br/>
</div>
