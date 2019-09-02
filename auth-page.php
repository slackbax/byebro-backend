<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>SISGDOC</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="../dist/css/SISGDoc.css">
		<link rel="stylesheet" href="../dist/css/skins/skin-red.min.css">

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- jQuery 3 -->
		<script src="../bower_components/jquery/dist/jquery.min.js"></script>
		<!-- Google Font -->
		<link rel="stylesheet"
			  href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <?php extract($_GET) ?>
        <?php if (isset($_SESSION['bb_userid'])): ?>
        <?php $_login = true ?>
        <?php endif ?>
        <?php if (isset($_SESSION['bb_useradmin']) && $_SESSION['bb_useradmin']): ?>
        <?php $_admin = true ?>
        <?php endif ?>
    </head>

    <body>
        <div class="container">
            <form class="form-signin" id="formLogin" method="POST">
                <h2 class="form-signin-heading text-center">Documento de acceso restringido</h2>

                <?php if ($error): ?>
                <p class="bg-class bg-danger col-sm-6 col-sm-offset-3 text-center">Los datos ingresados no son correctos o no están autorizados para visualizar el documento.</p>
                <?php endif ?>

                <div class="form-group col-sm-4 col-sm-offset-4">
                    <input type="text" class="form-control" id="inputUser" name="usr" placeholder="Usuario" required>
                </div>
                
                <div class="form-group col-sm-4 col-sm-offset-4">
                    <input type="password" class="form-control" id="inputPassword" name="pass" placeholder="Contraseña" required>
                </div>
                
                <div class="form-group col-sm-4 col-sm-offset-4">
                    <button type="submit" class="btn btn-lg btn-primary btn-block">Ver Documento</button>
                </div>
            </form>
        </div>

		<!-- REQUIRED JS SCRIPTS -->
		<!-- Bootstrap 3.3.7 -->
		<script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<!-- jQueryForm -->
		<script src="../bower_components/jquery-form/dist/jquery.form.min.js"></script>
		<!-- FastClick -->
		<script src="../bower_components/fastclick/lib/fastclick.js"></script>
		<!-- SISGDoc App -->
		<script src="../dist/js/sisgdoc.min.js"></script>
		<script src="../dist/js/fn.js"></script>
    </body>
</html>