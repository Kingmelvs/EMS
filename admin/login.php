<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
ob_start();
if(!isset($_SESSION['system'])){
	$system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
	foreach($system as $k => $v){
		$_SESSION['system'][$k] = $v;
	}
}
ob_end_flush();
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?php echo $_SESSION['system']['name'] ?></title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes glowing {
        0% { box-shadow: 0 0 10px #ff0000, 0 0 20px #ff0000; }
        50% { box-shadow: 0 0 20px #00ff00, 0 0 40px #00ff00; }
        100% { box-shadow: 0 0 10px #ff0000, 0 0 20px #ff0000; }
    }
    @keyframes shake {
        0% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        50% { transform: translateX(10px); }
        75% { transform: translateX(-10px); }
        100% { transform: translateX(0); }
    }

    body {
        height: 100vh;
        background: url(assets/uploads/<?php echo $_SESSION['system']['cover_img'] ?>) no-repeat center center fixed;
        background-size: cover;
        position: relative;
    }
    body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(10px);
    }
    .modal-dialog {
        max-width: 700px;
    }
    .modal-content {
        background: linear-gradient(135deg, #111111, #222222);
        border-radius: 15px;
        animation: fadeIn 0.5s ease-in-out, glowing 2s infinite alternate;
        padding: 40px;
        transform: scale(1.1);
        color: white;
        border: 2px solid #00ff00;
    }
    .modal-header {
        text-align: center;
    }
    .modal-body i {
        font-size: 60px;
        margin-bottom: 15px;
    }
    .modal-warning {
        animation: shake 0.5s ease-in-out;
    }
    .form-control {
        height: 50px;
        font-size: 18px;
        background-color: #111;
        color: #fff;
        border: 1px solid #00ff00;
    }
    .form-control:focus {
        border-color: #ff0000;
        box-shadow: 0 0 10px #ff0000;
    }
    button[type="submit"] {
        height: 50px;
        font-size: 20px;
        background-color: #00ff00;
        border: none;
        color: #000;
        font-weight: bold;
        transition: 0.3s;
    }
    button[type="submit"]:hover {
        background-color: #ff0000;
        color: #fff;
        transform: scale(1.05);
    }
  </style>
</head>
<body>
  <!-- Login Modal -->
  <div class="modal show" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true" style="display: block;">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">🔐 Login</h5>
        </div>
        <div class="modal-body">
          <form id="login-form">
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-block">Login</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Success Modal -->
  <div class="modal fade" id="successModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center">
          <i class="text-success fa fa-check-circle"></i>
          <h4>Login Successful!</h4>
          <p>Redirecting to Dashboard...</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Error Modal -->
  <div class="modal fade" id="errorModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content modal-warning">
        <div class="modal-body text-center">
          <i class="text-danger fa fa-exclamation-circle"></i>
          <h4>Wrong Password!</h4>
          <p>Please check your credentials.</p>
        </div>
      </div>
    </div>
  </div>

  <script>
    $('#login-form').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: 'ajax.php?action=login',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp){
                if(resp == 1){
                    $('#successModal').modal('show');
                    setTimeout(function(){
                        window.location.href = 'index.php?page=home';
                    }, 2000);
                } else if(resp == 2){
                    $('#successModal').modal('show');
                    setTimeout(function(){
                        window.location.href = 'voting.php';
                    }, 2000);
                } else {
                    $('#errorModal').modal('show');
                    $('.modal-content').addClass('modal-warning');
                }
            }
        });
    });
  </script>

  <!-- FontAwesome for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</body>
</html>
