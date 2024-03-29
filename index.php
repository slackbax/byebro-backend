<?php session_start() ?>
<?php $_login = false ?>
<?php $_staff = false ?>
<?php $_boss = false ?>
<?php $_admin = false ?>

<?php require 'class/classMyDBC.php' ?>
<?php require 'src/Random/random.php' ?>
<?php require 'src/sessionControl.php' ?>
<?php require 'src/fn.php' ?>

<?php extract($_GET) ?>
<?php if (isset($_SESSION['bb_userid'])): $_login = true; endif ?>
<?php if (isset($_SESSION['bb_useradmin']) and $_SESSION['bb_useradmin']): $_admin = true; endif ?>
<?php if (isset($_SESSION['bb_userstaff']) and $_SESSION['bb_userstaff']): $_staff = true; endif ?>
<?php if (isset($_SESSION['bb_usergroupboss']) and $_SESSION['bb_usergroupboss']): $_boss = true; endif ?>

<!DOCTYPE html>

<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ByeBro.com</title>

    <!-- Responsivness -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/datatables.net-buttons-bs/css/buttons.bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
    <!-- daterange picker -->
    <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- MultiFile -->
    <link rel="stylesheet" href="bower_components/multifile/jquery.MultiFile.css">
    <!-- SweetAlert -->
    <link rel="stylesheet" href="bower_components/sweetalert2/dist/sweetalert2.css">
    <!-- Noty -->
    <link rel="stylesheet" href="bower_components/noty/lib/noty.css">
    <!-- pincode-input -->
    <link rel="stylesheet" href="bower_components/bootstrap-pincode-input/css/bootstrap-pincode-input.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/all.css">
    <!-- jquery-pinlogin -->
    <link rel="stylesheet" href="node_modules/jquery-pinlogin/src/jquery.pinlogin.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/bb.css">
    <link rel="stylesheet" href="dist/css/skins/skin-red.min.css">

    <!-- jQuery 3 -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <!-- pincode-input -->
    <!--<script src="bower_components/bootstrap-pincode-input/js/bootstrap-pincode-input.js"></script>-->
    <!-- jQuery-pinlogin -->
    <script src="node_modules/jquery-pinlogin/src/jquery.pinlogin.js"></script>
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<?php if (isset($_SESSION['bb_userid'])): ?>
    <body class="hold-transition skin-red sidebar-mini fixed">
    <div class="wrapper">
        <header class="main-header">
            <a href="index.php" class="logo">
                <span class="logo-mini"><b>B</b>B</span>
                <span class="logo-lg">
					<b>Bye</b>Bro
				</span>
            </a>

            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown messages-menu">
                            <a href="#" id="btn-help" class="dropdown-toggle" data-toggle="dropdown">
                                Ayuda
                            </a>
                        </li>

                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="dist/img/<?php echo $_SESSION['bb_userpic'] ?>" class="user-image" alt="User Image">
                                <span class="hidden-xs"><?php echo $_SESSION['bb_userfname'] . ' ' . $_SESSION['bb_userlnamep'] ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <img src="dist/img/<?php echo $_SESSION['bb_userpic'] ?>" class="img-circle" alt="User Image">
                                    <p>
                                        <?php echo $_SESSION['bb_userfname'] . ' ' . $_SESSION['bb_userlnamep'] ?>
                                        <small><?php echo $_SESSION['bb_usergroup'] ?></small>
                                    </p>
                                </li>

                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="index.php?section=adminusers&sbs=editprofile" class="btn btn-default btn-flat"><i class="fa fa-user"></i> Ver perfil</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="index.php?section=adminusers&sbs=changepass" class="btn btn-default btn-flat"><i class="fa fa-key"></i> Cambiar contraseña</a>
                                    </div>
                                </li>

                                <li class="user-footer">
                                    <button type="button" id="btn-logout" class="btn btn-warning btn-flat btn-block">
                                        <i class="fa fa-power-off"></i> Salir
                                    </button>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <aside class="main-sidebar">
            <section class="sidebar">
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">PRINCIPAL</li>
                    <li<?php if (!isset($section) or $section == 'home' or $section == 'adminusers' or $section == 'forgotpass'): ?> class="active"<?php endif ?>>
                        <a href="index.php?section=home">
                            <i class="fa fa-home"></i> <span>Inicio</span>
                        </a>
                    </li>

                    <?php include 'class/classMenu.php'; ?>
                    <?php $m = new Menu(); ?>
                    <?php $men = $m->getByProfile($_SESSION['bb_userprofile']) ?>

                    <?php foreach ($men as $mn): ?>
                        <?php if ($mn->men_tipo == 1 and $mn->men_parent == ''): ?>
                            <li<?php if (isset($section) and $section == $mn->men_section): ?> class="active"<?php endif ?>>
                                <a href="index.php?section=<?php echo $mn->men_link ?>">
                                    <i class="fa <?php echo $mn->men_icon ?>"></i>
                                    <span><?php echo $mn->men_descripcion ?></span>
                                </a>
                            </li>

                        <?php elseif ($mn->men_tipo == 2): ?>
                            <li class="treeview<?php if (isset($section) and $section == $mn->men_section): ?>  active<?php endif ?>">
                                <a href="#">
                                    <i class="fa <?php echo $mn->men_icon; ?>"></i>
                                    <span><?php echo $mn->men_descripcion ?></span>
                                    <span class="pull-right-container">
                            		<i class="fa fa-angle-left pull-right"></i>
                        			</span>
                                </a>
                                <ul class="treeview-menu">
                                    <?php $subm = $m->getChildByProfile($mn->men_id, $_SESSION['bb_userprofile']) ?>
                                    <?php foreach ($subm as $smn): ?>
                                        <li<?php if (isset($sbs) and $sbs == $smn->men_link): ?> class="active"<?php endif ?>>
                                            <a href="index.php?section=<?php echo $mn->men_section ?>&sbs=<?php echo $smn->men_link ?>">
                                                <i class="fa <?php echo $smn->men_icon ?> text-red"></i><?php echo $smn->men_descripcion ?>
                                            </a>
                                        </li>
                                    <?php endforeach ?>
                                </ul>
                            </li>
                        <?php endif ?>
                    <?php endforeach ?>

                    <?php if ($_admin): ?>
                        <li class="header">ADMINISTRACIÓN</li>
                        <li class="treeview<?php if (isset($section) and $section == 'users'): ?> active<?php endif ?>">
                            <a href="#">
                                <i class="fa fa-user"></i>
                                <span>Usuarios</span>
                                <span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
                            </a>
                            <ul class="treeview-menu">
                                <li<?php if (isset($sbs) and $sbs == 'createuser'): ?> class="active"<?php endif ?>>
                                    <a href="index.php?section=users&sbs=createuser">
                                        <i class="fa fa-circle-o text-teal"></i>Nuevo usuario
                                    </a>
                                </li>
                                <li<?php if (isset($sbs) and $sbs == 'manageusers'): ?> class="active"<?php endif ?>>
                                    <a href="index.php?section=users&sbs=manageusers">
                                        <i class="fa fa-circle-o text-teal"></i>Usuarios creados
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="treeview<?php if (isset($section) and $section == 'personal'): ?> active<?php endif ?>">
                            <a href="#">
                                <i class="fa fa-users"></i>
                                <span>Personal</span>
                                <span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
                            </a>
                            <ul class="treeview-menu">
                                <li<?php if (isset($sbs) and $sbs == 'createposition'): ?> class="active"<?php endif ?>>
                                    <a href="index.php?section=personal&sbs=createposition">
                                        <i class="fa fa-circle-o text-teal"></i>Nuevo cargo
                                    </a>
                                </li>
                                <li<?php if (isset($sbs) and $sbs == 'createpersonal'): ?> class="active"<?php endif ?>>
                                    <a href="index.php?section=personal&sbs=createpersonal">
                                        <i class="fa fa-circle-o text-teal"></i>Nuevo personal
                                    </a>
                                </li>
                                <li<?php if (isset($sbs) and $sbs == 'managepositions'): ?> class="active"<?php endif ?>>
                                    <a href="index.php?section=personal&sbs=managepositions">
                                        <i class="fa fa-circle-o text-teal"></i>Cargos creados
                                    </a>
                                </li>
                                <li<?php if (isset($sbs) and $sbs == 'managepersonal'): ?> class="active"<?php endif ?>>
                                    <a href="index.php?section=personal&sbs=managepersonal">
                                        <i class="fa fa-circle-o text-teal"></i>Personal creado
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="treeview<?php if (isset($section) and $section == 'cities'): ?> active<?php endif ?>">
                            <a href="#">
                                <i class="fa fa-map-signs"></i>
                                <span>Ciudades</span>
                                <span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
                            </a>
                            <ul class="treeview-menu">
                                <li<?php if (isset($sbs) and $sbs == 'createocity'): ?> class="active"<?php endif ?>>
                                    <a href="index.php?section=cities&sbs=createocity">
                                        <i class="fa fa-circle-o text-teal"></i>Nueva ciudad de origen
                                    </a>
                                </li>
                                <li<?php if (isset($sbs) and $sbs == 'createdcity'): ?> class="active"<?php endif ?>>
                                    <a href="index.php?section=cities&sbs=createdcity">
                                        <i class="fa fa-circle-o text-teal"></i>Nueva ciudad de destino
                                    </a>
                                </li>
                                <li<?php if (isset($sbs) and $sbs == 'manageocities'): ?> class="active"<?php endif ?>>
                                    <a href="index.php?section=cities&sbs=manageocities">
                                        <i class="fa fa-circle-o text-teal"></i>Ciudades de origen creadas
                                    </a>
                                </li>
                                <li<?php if (isset($sbs) and $sbs == 'managedcities'): ?> class="active"<?php endif ?>>
                                    <a href="index.php?section=cities&sbs=managedcities">
                                        <i class="fa fa-circle-o text-teal"></i>Ciudades de destino creadas
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="treeview<?php if (isset($section) and $section == 'tripdetails'): ?> active<?php endif ?>">
                            <a href="#">
                                <i class="fa fa-paper-plane"></i>
                                <span>Detalles de viaje</span>
                                <span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
                            </a>
                            <ul class="treeview-menu">
                                <li<?php if (isset($sbs) and $sbs == 'createaccomodation'): ?> class="active"<?php endif ?>>
                                    <a href="index.php?section=tripdetails&sbs=createaccomodation">
                                        <i class="fa fa-circle-o text-teal"></i>Nuevo alojamiento
                                    </a>
                                </li>
                                <li<?php if (isset($sbs) and $sbs == 'createfood'): ?> class="active"<?php endif ?>>
                                    <a href="index.php?section=tripdetails&sbs=createfood">
                                        <i class="fa fa-circle-o text-teal"></i>Nuevo pack de comida
                                    </a>
                                </li>
                                <li<?php if (isset($sbs) and $sbs == 'createextra'): ?> class="active"<?php endif ?>>
                                    <a href="index.php?section=tripdetails&sbs=createextra">
                                        <i class="fa fa-circle-o text-teal"></i>Nuevo adicional
                                    </a>
                                </li>
                                <li<?php if (isset($sbs) and $sbs == 'manageaccomodations'): ?> class="active"<?php endif ?>>
                                    <a href="index.php?section=tripdetails&sbs=manageaccomodations">
                                        <i class="fa fa-circle-o text-teal"></i>Alojamientos creados
                                    </a>
                                </li>
                                <li<?php if (isset($sbs) and $sbs == 'managefood'): ?> class="active"<?php endif ?>>
                                    <a href="index.php?section=tripdetails&sbs=managefood">
                                        <i class="fa fa-circle-o text-teal"></i>Packs de comida creados
                                    </a>
                                </li>
                                <li<?php if (isset($sbs) and $sbs == 'manageextras'): ?> class="active"<?php endif ?>>
                                    <a href="index.php?section=tripdetails&sbs=manageextras">
                                        <i class="fa fa-circle-o text-teal"></i>Adicionales creados
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif ?>
                </ul>
            </section>
        </aside>

        <div class="content-wrapper main">
            <?php include 'src/routes.php'; ?>
        </div>
    </div>

    <!-- REQUIRED JS SCRIPTS -->
    <!-- Bootstrap 3.3.7 -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="bower_components/jszip/dist/jszip.min.js"></script>
    <script src="bower_components/pdfmake/build/pdfmake.min.js"></script>
    <script src="bower_components/pdfmake/build/vfs_fonts.js"></script>
    <script src="bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="bower_components/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <!-- jQueryForm -->
    <script src="bower_components/jquery-form/dist/jquery.form.min.js"></script>
    <!-- AutoComplete -->
    <script src="bower_components/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
    <!-- date-range-picker -->
    <script src="bower_components/moment/min/moment.min.js"></script>
    <script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap datepicker -->
    <script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js"></script>
    <!-- SweetAlert -->
    <script src="bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
    <!-- Noty -->
    <script src="bower_components/noty/lib/noty.js"></script>
    <!-- CKEditor -->
    <script src="bower_components/ckeditor/ckeditor.js"></script>
    <!-- Slimscroll -->
    <script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="bower_components/fastclick/lib/fastclick.js"></script>
    <!-- MultiFile -->
    <script src="bower_components/multifile/jquery.MultiFile.min.js"></script>
    <!-- NumberFormat -->
    <script src="bower_components/number-format.js/lib/format.min.js"></script>
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js"></script>
    <!-- ByeBro App -->
    <script src="dist/js/bb.min.js"></script>
    <script src="dist/js/jquery.Rut.min.js"></script>
    <script src="dist/js/fn.js"></script>

    <script src="dist/js/index.js"></script>
    </body>
<?php elseif (isset($section) and $section == 'forgotpass'): ?>
    <?php include 'admin/users/retrieve-password.php' ?>
<?php else: ?>
    <?php include 'src/login.php' ?>
<?php endif ?>
</html>
