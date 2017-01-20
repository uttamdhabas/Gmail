<?php
if (isset($this->session->userdata['logged_in'])) {

    header("location: inbox");
}
?>

<style>
    .form-signin
    {
        max-width: 330px;
        padding: 15px;
        margin: 0 auto;
    }
    .form-signin .form-signin-heading, .form-signin .checkbox
    {
        margin-bottom: 10px;
    }
    .form-signin .checkbox
    {
        font-weight: normal;
    }
    .form-signin .form-control
    {
        position: relative;
        font-size: 16px;
        height: auto;
        padding: 10px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .form-signin .form-control:focus
    {
        z-index: 2;
    }
    .form-signin input[type="text"]
    {
        margin-bottom: -1px;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }
    .form-signin input[type="password"]
    {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
    .account-wall
    {
        margin-top: 20px;
        padding: 40px 0px 20px 0px;
        background-color: #f7f7f7;
        -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
    }
    .login-title
    {
        color: #555;
        font-size: 18px;
        font-weight: 400;
        display: block;
    }
    .profile-img
    {
        width: 96px;
        height: 96px;
        margin: 0 auto 10px;
        display: block;
        -moz-border-radius: 50%;
        -webkit-border-radius: 50%;
        border-radius: 50%;
    }
    .need-help
    {
        margin-top: 10px;
    }
    .new-account
    {
        display: block;
        margin-top: 10px;
    }
</style>
<script>
    $('document').ready(function()
    {
        /* validation */
        checkSession();
        $("#login-form").validate({
            rules:
            {
                password: {
                    required: true,
                },
                username: {
                    required: true,

                },
            },
            messages:
            {
                password:{
                    required: "please enter your password"
                },
                username: "please enter your email address",
            },
            submitHandler: submitForm
        });
        /* validation */

        /* login submit */
        function checkSession() {
            $.ajax({

                type: 'POST',
                url: '/gmail/auth',
                success: function (response) {
                    if (response.auth == 1) {

                       //$("#btn-login").html('<img src="btn-ajax-loader.gif" /> &nbsp; Signing In ...');
                        window.location.href = "inbox";
                    }

                }
            });
        }
        function submitForm()
        {
            var data = $("#login-form").serialize();

            $.ajax({

                type : 'POST',
                url  : '/gmail/auth',
                data : data,
                beforeSend: function()
                {
                    $("#error").fadeOut();
                    $("#btn-login").html('<span class="glyphicon glyphicon-transfer"></span> &nbsp; signing ...');
                },
                success :  function(response)
                {
                    if(response.auth==1){


                         window.location.href = "inbox";
                    }
                    else{

                        $("#error").fadeIn(1, function(){
                            $("#error").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; '+response.error_message+' !</div>');
                            $("#btn-login").html('<span class="glyphicon glyphicon-log-in"></span> &nbsp; Sign In');
                        });
                    }
                }
            });
            return false;
        }
        /* login submit */
    });
</script>
<div class="container">
    <div class="signin-form">

        <div class="container">


            <form class="form-signin" method="post" id="login-form">

                <h2 class="form-signin-heading">Log In to WebApp.</h2><hr />

                <div id="error">
                    <!-- error will be shown here ! -->
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Email address" name="username" id="username" />
                    <span id="check-e"></span>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" name="password" id="password" />
                </div>

                <hr />

                <div class="form-group">
                    <button type="submit" class="btn btn-default" name="btn-login" id="btn-login">
                        <span class="glyphicon glyphicon-log-in"></span> &nbsp; Sign In
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>
