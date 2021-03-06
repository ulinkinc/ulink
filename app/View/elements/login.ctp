<!--<script type="text/javascript" src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php"></script>
<script type="text/javascript">
    FB.init("cf0266cbd11fc7749378c8069b3543c3","/ulink/app/webroot/xd_receiver.htm");
</script> -->
<script language="javascript" type="text/javascript">
    $(document).ready(function() {
        $("#loginForm").submit(function() {
            $.post("<?php echo $this->Html->url(array('controller'=>'users','action'=>'login'),true) ?>",{remember_me:$('#remember_me').val(), username:$('#username').val(),password:$('#password').val(), rand:Math.random() },
                    function(data) {
                        switch(data) {
                            case 'yes':
                                $('#loginForm-container').addClass('success');

                                //start fading the message box
                                $("#loginForm-message").fadeTo(200,0.1,function() {
                                    //add message and change the class of the box and start fading
                                    $(this).html('Logging in.....').addClass('success').fadeTo(900,1,
                                    function() {
                                        // reload the current page
                                        window.location.reload();
                                    });
                                });
                                break;
                             case 'std':
                                $('#loginForm-container').addClass('error');

                                //start fading the message box
                                $("#loginForm-message").fadeTo(200,0.1,function() {
                                    //add message and change the class of the box and start fading
                                    $(this).html('Your account is inactive, please check your email to complete the activation process or contact help@theulink.com.').addClass('error').fadeTo(900,1);
                                });
                                break;
                             case 'auto':
                                   $('#loginForm-container').addClass('success');

                                   //start fading the message box
                                   $("#loginForm-message").fadeTo(200, 0.1, function () {
                                      //add message and change the class of the box and start fading
                                      $(this).html('Logging in.....').addClass('success').fadeTo(900, 1,
                                             function () {
                                                 // redirect to the change password page
                                                window.location = "<?php echo $this->Html->url(array('controller'=>'users','action'=>'password','1'),true) ?>";
                                             });
                                      });
                                   break;
                            default:
                                $('#loginForm-container').addClass('error');

                                //start fading the message box
                                $("#loginForm-message").fadeTo(200,0.1,function() {
                                    //add message and change the class of the box and start fading
                                    $(this).html('Invalid login, please try again.').addClass('error').fadeTo(900,1);
                                });
                        }
                    });
            // prevent normal form submission
            return false;
        });

        $("#btnLogin").click(function() {
            var valid = true;
            // first validate the form fields
            if ($('#username').val() == "") {
                $('#username').focus();
                valid = false;
            } else {
                $('#username').blur();
            }
            if ($('#password').val() == "") {
                $('#password').focus();
                valid = false;
            } else {
                $('#password').blur();
            }
            // if valid submit the form
            if(valid) {
                $("#loginForm").submit();
            } else {
                $('#loginForm-container').addClass('error');
                $('#loginForm-message').html('Please enter the required fields.');
                return valid;
            }
        });
    });
</script>
<div class="modal-wide hide fade" id="loginComponent">
   <!-- <div id="fb-root"></div>
    <script type="text/javascript" language="javascript">
    window.fbAsyncInit = function() {
        FB.init({
            appId  : '213400365339060',
            status : true, // check login status
            cookie : true, // enable cookies to allow the server to access the session
            xfbml  : true  // parse XFBML
        });
    };

    (function() {
        var e = document.createElement('script');
        e.src = document.location.protocol + '//connect.facebook.net/de_DE/all.js';
        e.async = true;
        document.getElementById('fb-root').appendChild(e);
    }());
    </script>   -->

    <div class="modal-header">
        <div class="row">
        <div class="login-header pull-left">Login with your uLink account</div>
        <div class="no-account-header pull-right"><a href="<?php echo($this->Html->url('/users/register')); ?>">No Account? Click Here!</a></div>
        </div>
    </div>  <!-- /modal-header -->
    <div class="modal-body">
        <?php echo $this->Form->create('User', array('action' => '#', 'id' => 'loginForm')); ?>
        <div id="loginForm-container" class="control-group">
            <div class="controls">
                <?php echo $this->Form->input('username', array('id' => 'username','label' => false, 'placeholder' => 'username','class' =>
                'input-xxlarge ulink-input-bigfont')); ?>
                <?php echo $this->Form->input('password', array('id' => 'password', 'label' => false,'placeholder' => 'password','class' =>'input-xxlarge ulink-input-bigfont')); ?>
                <span id="loginForm-message" class="help-inline"></span>
            </div>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>  <!-- /modal-body -->
    <div class="modal-footer">
        <div class="row">
            <div class="span2">
                <a id="btnLogin" class="btn btn-primary btn-large">Log In</a>
            </div>
      <!--      <div class="facebook-login pull-left">
                <span>or login with facebook</span>
                <?php
                if (isset($loggedInId)):
                if ($loggedInFacebookId > 0):
                    echo $this->Html->link('logout', 'javascript:void(0);', array('onclick' => 'FB.Connect.logout(function() {
                    document.location = hostname + \'users/logout/\'; }); return false;'));
                    else:
                    echo $this->Html->link('logout', array('controller' => 'members', 'action' => 'logout'));
                    endif;
                    else:
                    echo '<fb:login-button onlogin="window.location.reload();"></fb:login-button>';
                    endif;?>
            </div>   <!-- /facebookIconBox  -->
        </div>  <!-- /row -->
        <div class ="row">
            <div class="span1">&nbsp;</div>
            <div class="pull-left remember-forgot">
                <?php echo($this->Form->checkbox('remember_me')) ?>&nbsp;Remember Me&nbsp;|&nbsp;<a href="<?php echo($this->Html->url('/users/forgotpassword')); ?>">Forgot Password?</a>
            </div>
        </div>
    </div><!-- /modal-footer -->
</div>