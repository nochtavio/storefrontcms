<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Storefront CMS</title>
    <link href="<?php echo base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url() ?>assets/css/login.css" rel="stylesheet" type="text/css" />
  </head>
  <body>
    <div class="container">
      <form class="form-signin" role="form">
        <h2 class="form-signin-heading">Sign In</h2>
        <input type="text" id="txt_username" class="form-control" placeholder="Username" required autofocus>
        <input type="password" id="txt_password" class="form-control" placeholder="Password" required>
        <p id="error_container_message" style="color:red;"></p>
        <button id="btn_login" class="btn btn-lg btn-primary btn-block" type="button">Sign in</button>
      </form>
    </div>
  </body>

  <script src="<?php echo base_url() ?>assets/js/jquery.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url() ?>assets/js/jquery.easy-overlay.js" type="text/javascript"></script>
  <script>
    $(document).ready(function(){
      base_url = "<?php echo base_url() ?>";
      
      $(document).ajaxStart(function() {
        $(document.body).overlay();
      });

      $(document).ajaxStop(function() {
        $(document.body).overlayout();
      });
      
      login = function () {
        var username = $('#txt_username').val();
        var password = $('#txt_password').val();
        
        $.ajax({
          url: base_url + 'login/login',
          type: 'POST',
          data: {
            username: username,
            password: password
          },
          dataType: 'json',
          beforeSend: function () {
            $('#error_container_message').empty();
          },
          success: function (result) {
            if (result['result'] === 'r2') {
              $('#error_container_message').html(result['result_message']);
            }else{
              window.location = base_url+"dashboard/";
            }
          }
        });
      };
      
      $('#btn_login').click(function(){
        login();
      });
      
      $('.form-control').bind('keypress', function (e) {
        if (e.keyCode === 13) {
          login();
        }
      });
    });
  </script>
</html>
