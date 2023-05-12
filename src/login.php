<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo text-red">
    <a class="btn-block" href="index.php">
      <img src="dist/img/logo.png" style="width: 330px">
    </a>
  </div>

  <div class="login-box-body text-center">
    <p class="login-box-msg"><b>Bienvenido!</b><br>Ingresa tus datos para iniciar sesión:</p>

    <form id="form-login">
      <div class="form-group has-feedback">
        <i class="fa fa-user form-control-feedback"></i>
        <input type="text" class="form-control" id="inputUser" placeholder="Nombre de usuario" name="name" required>
      </div>
      <div class="form-group">
        <i class="fa fa-lock"></i>
        <div id="inputPincode"></div>
        <input type="hidden" id="inputPin" name="pswpincode">
      </div>
      <div class="row">
        <div class="col-xs-12">
          <button type="button" id="submitBtn" class="btn btn-primary btn-block btn-lg">
            Ingresar
            <i class="fa fa-gear fa-spin loginLoader" id="submitLoader"></i>
          </button>
        </div>
      </div>
    </form>

    <a href="index.php?section=forgotpass">Olvidé mi contraseña</a><br>
  </div>

  <?php if (isset($timeout) and $timeout == 1): ?>
    <div class="col-sm-12 bg-class bg-danger text-center" style="display: block;" id="login-error">Se ha cerrado tu sesión por inactividad.<br>Por favor, ingresa nuevamente.</div>
  <?php else: ?>
    <div class="col-sm-12 bg-class bg-danger text-center" id="login-error"></div>
  <?php endif ?>
</div>

<script>
  $(document).ready(function () {
    $('#submitLoader').css('display', 'none');
    const pinlogin = $("#inputPincode").pinlogin({
      fields: 4, reset: false,
      complete: function (pin) {
        $('#inputPin').val(pin)
      }
    });

    $('#submitBtn').click(function (e) {
      $('#submitLoader').css('display', 'inline-block');
      $('#login-error').css('display', 'none');

      $.ajax({
        type: "POST",
        url: "src/session.php",
        data: {user: $('#inputUser').val(), passwd: $('#inputPin').val()}
      }).done(function (msg) {
        if (msg === 'true') {
          $('#login-error').html('');
          window.location.replace('index.php');
        } else {
          $('#submitLoader').css('display', 'none');
          const message = '<strong>¡Error!</strong> ' + msg;
          $('#login-error').html(message).css('display', 'block');
          $('#inputUser').val('');
          pinlogin.reset();
        }
      });
    });
  });
</script>
</body>