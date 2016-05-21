<?php
  session_start();
  if(!isset($_SESSION['email'])||!isset($_SESSION['db_key'])){
    header('Location: ../index.php');
  }
  require_once("../role.php");
  include("../db.php");
  $db_key=$_SESSION['db_key'];
  $branch_id=$_SESSION['branch_id'];
  $restaurant_name=$_SESSION['restaurant_name'];
  $email=$_SESSION['email'];

  $row1=mysql_fetch_array(mysql_query("SELECT * FROM `rest_logo` WHERE `db_key` ='$db_key' ORDER BY `s.no` DESC "));
  if(empty($row1['image'])){
    $row1=mysql_fetch_array(mysql_query("SELECT * FROM `rest_logo` WHERE `s.no` =1"));
  }
  $logo_src=$row1['image'];
  $row2=mysql_fetch_array(mysql_query("SELECT * FROM `restaurant` WHERE `db_key` ='$db_key' "));
  $branches=$row2['branches'];
?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo "$restaurant_name";?> | Dashboard</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
  <link rel="stylesheet" href="plugins/fullcalendar/fullcalendar.min.css">
  <link rel="stylesheet" href="plugins/fullcalendar/fullcalendar.print.css" media="print">
  
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <link rel="stylesheet" type="text/css" href="../css/ng-img-crop.css">
  <link rel="stylesheet" type="text/css" href="../css/style.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
  <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
  <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
  <script src="../js/moment.js"></script>
  <script src="../js/angular.min.js"></script>
  <script src="../js/ng-bs-daterangepicker.js"></script>
  <script src="../js/ngDialog.min.js"></script>
  <script src="../js/angular-animate.min.js"></script>
  <script src="../js/angular-aria.min.js"></script>
  <script src="../js/angular-animate.js"></script>
  <script src="../js/angular-material.js"></script>
  <script src="../js/ng-img-crop.js"></script>
  <script src="../js/angular-animate.js"></script>
  <script src="../js/dirPagination.js"></script>
  <script src="../js/shortcut.js"></script>
  <script src="../js/pdf.js"></script>
  <script src="../js/ng-pdfviewer.js"></script>
  <script src="../js/ng-google-chart.js"></script>
  <script src="../js/lodash.underscore.min.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini" ng-app="dashapp" ng-controller="dashcntrl">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>LAZAT</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>LAZAT</b> inn</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
          </li>
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
          </li>
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img id="logo1" src="<?php echo "$logo_src";?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo "$email";?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <!-- <img src="<?php echo "$logo_src";?>" class="img-circle" alt="User Image"> -->
            <div id="logo" class="ch-item " style=" background:rgb(221, 221, 221)url('<?php echo $logo_src;?>');" ng-mouseover="hoverIn()" ng-mouseleave="hoverOut()">
                <div class="ch-info">
                  <h3>change logo</h3>
                </div>
              </div>
                <p>
                  <?php echo $row2['address'];?>
                  <small><?php $row2['city'];?></small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a data-toggle="modal" data-target="#change_pass" class="btn btn-default btn-flat">Change password</a>
                </div>
                <div class="pull-right">
                  <a href="./logout.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img id="logo2" src="<?php echo "$logo_src";?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo "$email";?></p>
        </div>
      </div>
      <ul class="sidebar-menu">
        <li id="1" class="active treeview"><a><i class="fa fa-dashboard"></i> <span>Dashboard</span> <i class="fa pull-right"></i></a></li>
        <li id="2"><a><i class="fa fa-users"></i><span>User management</span></a></li>
        <li id="3"><a><i class="fa fa-cutlery"></i> <span>Category management</span></a></li>
        <li id="4"><a><i class="fa fa-glass"></i> <span>Product management</span></a></li>
        <li id="5"><a ><i class="fa fa-files-o"></i> <span>Room details</span></a></li>
        <li id="6"><a ><i class="fa "><img src="../img/door.png" style="width:12px;opacity: 0.75;" ></i> <span>Table details</span></a></li>
        <li id="7"><a ><i class="fa fa-motorcycle"></i> <span>Driver details</span></a></li>
        <li id="8"><a ><i class="fa fa-print"></i> <span>Bill desk</span></a></li>
        <li id="14" class="treeview"><a><i class="fa fa fa-line-chart"></i> <span>Reports</span><i class="fa fa-angle-left pull-right"></i></a>
          <ul class="treeview-menu">
            <li id="9"><a><i class="fa fa-inr"></i> Sales report</a></li>
            <li id="10"><a><i class="fa fa-times-circle"></i> Cancellation report</a></li>
            <li id="11"><a><i class="fa fa-truck"></i> Delivery report</a></li>
            <li id="12"><a><i class="fa fa-user"></i> Customer report</a></li>
          </ul>
        </li>
        <li id="13"><a><i class="fa fa-calendar"></i><span>Calender</span></a></li>
      </ul>
    </section>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="min-height: 600px;"  >
    <div id="tabContent1" class="panel-collapse collapse in ">
      <section class="content-header">
        <h1>Dashboard <small>Control panel</small></h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Dashboard</li>
        </ol>
      </section>
      <section class="content">
        <div class="row">
          <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-purple">
            <div class="inner">
              <h3 ng-bind="order_count"></h3>

              <p>Today New Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
          </div>
          <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3 ng-bind="cat_count"></h3>

              <p>product categories</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-restaurant"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
          </div>
          <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
              <h3 ng-bind="prod_count"><sup style="font-size: 20px"></sup></h3>

              <p>products</p>
            </div>
            <div class="icon">
              <i class="ion ion-android-bar"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
          </div>
          <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-light-blue">
            <div class="inner">
              <h3 ng-bind="cus_count"></h3>

              <p>customer registrations</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-stalker"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
          </div>
        </div>
        <div class="row">
          <section class="col-lg-7 connectedSortable">
            <div class="nav-tabs-custom">
              <ul class="dashchart-nav nav nav-tabs pull-right">
                <!-- <li id="dashchart1" class="active"><a data-toggle="tab">line chart</a></li> -->
                <li id="dashchart2"><a data-toggle="tab">bar chart</a></li>
                <li id="dashchart3"><a data-toggle="tab">donut chart</a></li>
                <li class="pull-left header"><i class="fa fa-inbox"></i> Sales</li>
              </ul>
              <div class="tab-content no-padding">
                <!-- <div id="dashchart1-view" class="chart tab-pane" style="position: relative; height: 300px;" line-chart line-pre-units="'&#8377'"   line-data='report1' line-xkey='day' line-ykeys='["a"]' line-labels='["total sale "]' line-colors='["#605CA8", "#c7254e"]' line-resize="true">
              </div> -->

                <div id="dashchart2-view" class="chart tab-pane  active" style="position: relative; height: 300px;" bar-chart bar-data='report1' bar-pre-units="'&#8377'"  bar-x='day' bar-y='["a"]' bar-resize="1" bar-labels='["total sale"]' bar-colors='["#31C0BE", "#c7254e"]'>
                </div>
                <div id="dashchart3-view" class="chart tab-pane" style="position: relative; height: 300px;" donut-chart donut-data='report2' donut-colors='["#31C0BE", "#c7254e", "#F39C13", "#605CA8", "#222D32", "#F31313", "#960084", "#0F6B7F", "#0F7F12", "#FF8E01"]' donut-formatter="myFormatter" donut-resize="true">
                </div>

              </div>
            </div>
            <div class="box box-primary">
            <div class="box-header">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">To Do List</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              
              <ul class="todo-list">
                <li ng-repeat="todo in todo_data">
                  <!-- drag handle -->
                      <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                  <!-- checkbox -->
                  <!-- <input type="checkbox" value="" name=""> -->
                  <!-- todo text -->
                  <span class="text" ng-bind="todo.title"></span>
                  <!-- Emphasis label -->
                  <small class="label label-danger"><i class="fa fa-clock-o"></i> <span ng-bind="todo.diff"></span></small>
                  <!-- General tools such as edit or delete-->
                  <div class="tools">
                    <i class="fa fa-edit"></i>
                    <i class="fa fa-trash-o"></i>
                  </div>
                </li>
              
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix no-border">
              <button type="button" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Add item</button>
            </div>
            </div>
            <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Latest Orders</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Item</th>
                    <th>Status</th>
                    <th>Price</th>
                  </tr>
                  </thead>
                  <tbody>
                 
                  <tr ng-repeat="order in last_order">
                    <td><a ng-bind="order.order_id">OR11</a></td>
                    <td ng-bind="order.prod_name">pongal, idly(set of 3), chicken biriyani, mutton biriyani</td>
                    <td>
                      <span ng-show="order.status=='confirm'" class="label label-warning">Confirm</span>
                      <span ng-show="order.status=='cancel'" class="label label-danger">Canceled</span>
                      <span ng-show="order.status=='delivered'" class="label label-success">Delivered</span>
                      <span ng-show="order.status=='paid'" class="label label-danger">Paid</span>
                      <span ng-show="order.status=='preperation'" class="label label-primary">Preperation</span>
                    </td>
                    <td>
                      </span><i class="fa fa-inr"></i><span ng-bind="order.price"></span></span>
                    </td>
                  </tr>

                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix"> 
              <a class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
            </div>
            <!-- /.box-footer -->
          </div>
          </section>
          <section class="col-lg-5 connectedSortable">
            <div class="box box-solid bg-light-blue-gradient">
              <div class="box-header">
                <div class="pull-right box-tools">
                  <button type="button" class="btn btn-primary btn-sm daterange pull-right" data-toggle="tooltip" title="Date range"><i class="fa fa-calendar"></i></button>
                  <button type="button" class="btn btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
                </div>
                <i class="fa fa-map-marker"></i>
                <h3 class="box-title">Visitors</h3>
              </div>
              <div class="box-body">
                <div id="world-map" style="height: 250px; width: 100%;"></div>
              </div>
              <div class="box-footer no-border">
              <div class="row">
                <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                  <div id="sparkline-1"></div>
                  <div class="knob-label">Visitors</div>
                </div>
                <!-- ./col -->
                <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                  <div id="sparkline-2"></div>
                  <div class="knob-label">Online</div>
                </div>
                <!-- ./col -->
                <div class="col-xs-4 text-center">
                  <div id="sparkline-3"></div>
                  <div class="knob-label">Exists</div>
                </div>
                <!-- ./col -->
              </div>
              <!-- /.row -->
              </div>
            </div>
            <div class="box box-solid bg-navy">
              <div class="box-header">
                <i class="fa fa-calendar"></i>
                <h3 class="box-title">Calendar</h3>
                <!-- <div class="pull-right box-tools">
                  <div class="btn-group">
                    <button type="button" class="btn btn-inverse btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i></button>
                    <ul class="dropdown-menu pull-right" role="menu">
                      <li><a href="#">Add new event</a></li>
                      <li><a href="#">Clear events</a></li>
                      <li class="divider"></li>
                      <li><a href="#">View calendar</a></li>
                    </ul>
                  </div>
                </div> -->
              </div>
              <div class="box-body no-padding">
                <div id="calendar" style="width: 100%"></div>
              </div>
            </div>
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Recently Added Products</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                <li class="item" ng-repeat="product in last_product">
                  <div class="product-img">
                    <img src="dist/img/default-50x50.gif" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a class="product-title"><span ng-bind="product.item_name">Chicken Biriyani</span>
                     <span ng-show="product.price>=0 && product.price<=50 " class="label label-primary pull-right"><i class="fa fa-inr"></i><span ng-bind="product.price"></span></span>
                     <span ng-show="product.price>50 && product.price<=250 " class="label label-success pull-right"><i class="fa fa-inr"></i><span ng-bind="product.price"></span></span>
                     <span ng-show="product.price>250 && product.price<=500 " class="label label-warning pull-right"><i class="fa fa-inr"></i><span ng-bind="product.price"></span></span>
                     <span ng-show="product.price>500" class="label label-danger pull-right"><i class="fa fa-inr"></i><span ng-bind="product.price"></span></span>
                    </a>
                    <span class="product-description"  ng-bind="product.item_desc">Tasty</span>
                  </div>
                </li>
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a class="uppercase">View All Products</a>
            </div>
            <!-- /.box-footer -->
          </div>
          </section>
        </div>
      </section>
    </div>
    <div id="tabContent2" class="panel-collapse collapse content" ng-controller="user_cntrl">
      <div class="box box-primary content">
        <div class="row">
        <input type="button" class="btn btn-success" value="Add" data-toggle="modal" data-target="#add_usr" style="float:right" >
        <input type="button" class="btn btn-success btn-sm" ng-click=" get_user();" value="Refresh" style="" >
        <div class="modal fade" id="add_usr" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add new user</h4>
              </div>
              <div class="modal-body">
                <form name="add_usr">   
                  <div class="row form-group">
                    <label class="control-label col-sm-3" for="d_name">Email</label>
                    <div class="col-sm-9">
                      <input type="text" name="u_email" ng-model="u_email" class="form-control" ng-pattern="/^[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/"  placeholder="" required>
                      <span ng-show="add_usr.u_email.$error.pattern" style="color:#f44336;"> Please enter valid mail </span>
                    </div>  
                  </div>
                  <div class="row form-group">
                    <label class="control-label col-sm-3">Password</label>
                    <div class="col-sm-9">
                      <input type="password" class="form-control" name="password" ng-model="u_password" ng-minlength="8" ng-maxlength="20" ng-pattern="/(?=.*[0-9])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])/" placeholder="" required />
                      <span ng-show="add_user.password.$error.required && add_usr.password.$dirty">required</span>
                      <span ng-show="!add_usr.password.$error.required && (add_usr.password.$error.minlength || add_usr.password.$error.maxlength) && add_usr.password.$dirty">Passwords must be between 8 and 20 characters.</span>
                      <span ng-show="!add_usr.password.$error.required && add_usr.password.$error.pattern && add_usr.password.$dirty">Must contain one uppercase letter, one number and one symbol.</span>
                    </div>
                  </div>
                  <div class="row form-group">
                    <label class="control-label col-sm-3" for="mob">Employee role</label>
                    <div class="col-sm-9">          
                      <select class="form-control" name="r_id" ng-model="u_role_id" required>
                        <option ng-repeat="role in roles" ng-bind="role.role_name" value="{{role.role_id}}"></option>
                      </select>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" ng-disabled="add_usr.$invalid" ng-click="add_user()" class="btn btn-primary">Add new</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="edit_user" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Driver </h4>
              </div>
              <div class="modal-body">
                <form name="edit_usr">
                  <div class="row form-group">
                    <label class="control-label col-sm-3" for="d_name">Email</label>
                    <div class="col-sm-9">
                      <input type="text" name="u_email" ng-model="u_email" class="form-control" ng-pattern="/^[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/" placeholder="" required>
                      <span ng-show="edit_usr.u_email.$error.pattern" style="color:#f44336;"> Type characters only </span>
                    </div>  
                  </div>
                  <div class="row form-group">
                    <label class="control-label col-sm-3">Password</label>
                    <div class="col-sm-9">
                      <input type="password" class="form-control" name="password" ng-model="u_password" ng-minlength="8" ng-maxlength="20" ng-pattern="/(?=.*[0-9])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])/" placeholder="Leave empty to dont change password"/>
                      <span ng-show="edit_usr.password.$error.required && edit_usr.password.$dirty">required</span>
                      <span ng-show="!edit_usr.password.$error.required && (edit_usr.password.$error.minlength || edit_usr.password.$error.maxlength) && edit_usr.password.$dirty">Passwords must be between 8 and 20 characters.</span>
                      <span ng-show = "!edit_usr.password.$error.required && edit_usr.password.$error.pattern && edit_usr.password.$dirty">Must contain one uppercase letter, one number and one symbol.</span>
                    </div>
                  </div>
                  <div class="row form-group">
                    <label class="control-label col-sm-3" for="mob">Employee role</label>
                    <div class="col-sm-9">          
                      <select class="form-control" name="r_id" ng-model="u_role_id" required="">
                        <option ng-repeat="role in roles" ng-bind="role.role_name" value="{{role.role_id}}"></option>
                      </select>
                    </div>
                  </div>
                </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" ng-disabled="edit_usr.$invalid" ng-click="edit_user()" class="btn btn-primary">Update</button>
                </div>
              </div>
            </div>
        </div>
        <br>
        </div>
        <div class="row">
          <div class="col-xs-4">
            <label for="search">Search</label>
            <div class="input-group input-group-sm">
              <input ng-model="q" id="search" class="form-control" placeholder="Filter text">
              <div class="input-group-btn">
                <button class="btn btn-default"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </div>
          <div class="col-xs-3">
            <label for="search">items per page</label>
            <input type="number" min="1" max="50" class="form-control" ng-model="pageSize">
          </div>
          <div class="col-xs-5 text-center">
            <dir-pagination-controls boundary-links="true" template-url="../tmpl/dirPagination.tmpl.html" pagination-id="driver"></dir-pagination-controls>
          </div>
        </div>
        <hr></hr>
        <table class="table table-striped table-hover table-condensed table-responsive">
            <tr>
              <th ng-click="predicate = $index; reverse = !reverse;" >S.No</th>
              <th ng-click="predicate = 'name'; reverse = !reverse;" >Email</th>
              <th ng-click="predicate = 'phone'; reverse = !reverse;" >User Role</th>
              <th>Manage</th>
            </tr>
            <tr dir-paginate="user in users | filter:q | orderBy:predicate:reverse |itemsPerPage: pageSize" current-page="currentPage" pagination-id="driver">
              <td ng-bind="((currentPage-1)*pageSize)+($index+1)"></td>
              <td ng-bind="user.email"></td>
              <td ng-repeat="role in roles | filter:{role_id:user.role_id}" >
                <span ng-bind="role.role_name"></span>
              </td>
              <td ng-show="user.role_id!=1">
                <button type="button" ng-click="abc(user.employee_id,user.role_id,user.email)" data-toggle="modal" data-target="#edit_user" class="btn btn-warning btn-xs">Edit</button>
                <button type="button" ng-click="del_user(user.employee_id)" class="btn btn-danger btn-xs">Delete </button>
              </td>
            </tr>
        </table>
      </div>
    </div>
    <div id="tabContent3" class="panel-collapse collapse content" ng-controller="cat_cntrl">
      <div class="box box-primary content">
        <div class="row">
          <input type="button" class="btn btn-success" value="Add" data-toggle="modal" data-target="#add_cat" style="float:right" >
          <input type="button" class="btn btn-success btn-sm" ng-click="get_category();" value="Refresh" style="" >
          <div class="modal fade" id="add_cat" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add new category</h4>
              </div>
              <div class="modal-body">
                <form name="add_cat">
                  <div class="row form-group">
                    <label class="control-label col-sm-3" for="name">Category Name</label>
                    <div class="col-sm-8">
                      <input type="text" name="c_name" ng-model="c_name" ng-pattern="/^([a-zA-Z\s-_])+[^\W\d]$/" class="form-control" minlength="3" placeholder="" required>
                      <span ng-show="add_cat.c_name.$error.pattern && !add_cat.c_name.$error.minlength" style="color:#f44336;"> Type characters only </span>
                      <span ng-show="add_cat.c_name.$error.minlength" style="color:#f44336;"> Type atleast 3 characters </span>
                    </div>
                  </div>
                  <div class="row form-group">
                    <label class="control-label col-sm-3" for="descp">Description</label>
                    <div class="col-sm-8">
                      <textarea name="c_descp" ng-model="c_descp" rows="5" ng-pattern="/^([a-zA-z0-9\s_,./\&-])+$/" class="form-control" required></textarea>
                      <span ng-show="add_cat.c_descp.$error.pattern" style="color:#f44336;"> Type characters and numbers  </span>
                    </div>
                  </div>
                  <div class="row form-group">
                    <label class="control-label col-sm-3" for="pri">Priority</label>
                    <div class="col-sm-8">          
                      <select name="c_prior" ng-model="c_prior" class="form-control" placeholder="" required>
                        <option value="1">1 (one)</option>
                        <option value="2">2 (two)</option>
                        <option value="3">3 (three)</option>
                        <option value="4">4 (four)</option>
                        <option value="5">5 (five)</option>
                        <option value="6">6 (six)</option>
                        <option value="7">7 (seven)</option>
                        <option value="8">8 (eight)</option>
                        <option value="9">9 (nine)</option>
                        <option value="10">10 (ten)</option>
                      </select>
                      <span ng-show="!c_prior" style="color:#f44336;">select one</span>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" ng-disabled="add_cat.$invalid" data-dismiss="modal" ng-click="add_category()" class="btn btn-primary">Add new</button>
              </div>
            </div>
          </div>
          </div>
          <div class="modal fade" id="edit_cat" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit category</h4>
              </div>
              <div class="modal-body">
                <form name="edit_cat">
                  <div class="row form-group">
                    <label class="control-label col-sm-3" for="name">Category Name</label>
                    <div class="col-sm-8">
                      <input type="text" name="c_name" ng-model="c_name" ng-pattern="/^([a-zA-Z\s-_])+[^\W\d]$/" class="form-control" minlength="3" placeholder="" required>
                      <span ng-show="edit_cat.c_name.$error.pattern&&!edit_cat.c_name.$error.minlength" style="color:#f44336;"> Type characters only </span>
                      <span ng-show="edit_cat.c_name.$error.minlength" style="color:#f44336;"> Type atleast 3 characters </span>
                    </div>
                  </div>
                  <div class="row form-group">
                    <label class="control-label col-sm-3" for="descp">Description</label>
                    <div class="col-sm-8">
                      <textarea name="c_descp" ng-model="c_descp" rows="5" ng-pattern="/^([a-zA-z0-9\s_.,\&/-])+$/" class="form-control" required></textarea>
                      <span ng-show="edit_cat.c_descp.$error.pattern" style="color:#f44336;"> Type characters and numbers  </span>
                    </div>
                  </div>
                  <div class="row form-group">
                    <label class="control-label col-sm-3" for="pri">Priority</label>
                    <div class="col-sm-8">   
                      <select name="c_prior" ng-model="c_prior" class="form-control" placeholder="" required>
                        <option value="1">1 (one)</option>
                        <option value="2">2 (two)</option>
                        <option value="3">3 (three)</option>
                        <option value="4">4 (four)</option>
                        <option value="5">5 (five)</option>
                        <option value="6">6 (six)</option>
                        <option value="7">7 (seven)</option>
                        <option value="8">8 (eight)</option>
                        <option value="9">9 (nine)</option>
                        <option value="10">10 (ten)</option>
                      </select>
                      <span ng-show="!c_prior" style="color:#f44336;">select one</span>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" ng-disabled="edit_cat.$invalid" ng-click="edit_category()" class="btn btn-primary">Update</button>
              </div>
            </div>
          </div>
          </div>
          <br>
        </div>
        <div class="row">
        <div class="col-xs-4">
          <label for="search">Search</label>
          <input ng-model="q" id="search" class="form-control" placeholder="Filter text">
        </div>
        <div class="col-xs-3">
          <label for="search">items per page</label>
          <input type="number" min="1" max="50" class="form-control" ng-model="pageSize">
        </div>
        <div class="col-xs-5 text-center">
          <dir-pagination-controls boundary-links="true" template-url="../tmpl/dirPagination.tmpl.html" pagination-id="category"></dir-pagination-controls>
        </div>
        </div>
        <hr></hr>
        <table class="table table-striped table-hover table-condensed table-responsive">
        <tr>
          <th ng-click="predicate = $index; reverse = !reverse;" >S.No</th>
          <th ng-click="predicate = 'name'; reverse = !reverse;" >Name</th>
          <th ng-click="predicate = 'desc'; reverse = !reverse;" >Description</th>
          <th ng-click="predicate = 'priority'; reverse = !reverse;" >Priority</th>
          <th>Manage</th>
        </tr>
        <tr dir-paginate="cat in category | filter:q | orderBy:predicate:reverse | itemsPerPage: pageSize" current-page="currentPage" pagination-id="category">
          <td ng-bind="((currentPage-1)*pageSize)+($index+1)"></td>
          <td ng-bind="cat.name"></td>
          <td ng-bind="cat.desc"></td>
          <td ng-bind="cat.priority"></td>
          <td>
            <button type="button" ng-click="abc(cat.category_id,cat.name,cat.desc,cat.priority)" data-whatever="{{cat.category_id}}" data-toggle="modal" data-target="#edit_cat" class="btn btn-warning btn-xs">Edit</button>
            <button type="button" ng-click="del_category(cat.category_id)" class="btn btn-danger btn-xs">Delete </button>
          </td>
        </tr>
        </table>
        <br>
      </div>
    </div>
    <div id="tabContent4" class="panel-collapse collapse content" ng-controller="prod_cntrl">
      <div class="box box-primary content">
        <div class="row">
          <input type="button" class="btn btn-success" value="Add" data-toggle="modal" data-target="#add_prod" style="float:right" >
          <input type="button" class="btn btn-success btn-sm" ng-click=" get_product();" value="Refresh" style="" >
          <div class="modal fade" id="add_prod" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add new Product</h4>
              </div>
              <div class="modal-body">
                <form name="add_prod">
                  <div class="row form-group">
                    <label class="control-label col-sm-3" for="p_name">Product Name</label>
                    <div class="col-sm-9">
                      <input type="" name="p_name" ng-model="p_name" ng-pattern='/^[a-zA-Z0-9().:,_/-\s\"\&]{3,100}$/' class="form-control" minlength="3" placeholder="" required>
                      <span ng-show="add_prod.p_name.$error.pattern && !add_prod.p_name.$error.minlength" style="color:#f44336;"> Type characters only </span>
                      <span ng-show="add_prod.p_name.$error.minlength" style="color:#f44336;"> Type atleast 3 characters </span>
                    </div>
                  </div>
                  <div class="row form-group">
                    <label class="control-label col-sm-3" >Product category</label>
                    <div class="col-sm-9">
                      <select name="c_id" ng-model="c_id" class="form-control" required>
                        <option ng-repeat="cat in category" value="{{cat.category_id}}">{{cat.name}}</option>
                      </select>
                      <span ng-show="add_prod.c_id.$error.pattern" style="color:#f44336;"> Type characters only </span>
                    </div>
                  </div>
                  <div class="row form-group">
                    <label class="control-label col-sm-3" for="p_descp">Description</label>
                    <div class="col-sm-9">
                      <textarea name="p_descp" ng-model="p_descp" ng-pattern="/^([a-zA-z\s_,./-])+$/" minlength="3" class="form-control" rows="5" required></textarea>
                      <span ng-show="add_prod.p_descp.$error.pattern&& !add_prod.p_descp.$error.minlength" style="color:#f44336;"> Type characters and numbers </span>
                      <span ng-show="add_prod.p_descp.$error.minlength" style="color:#f44336;"> Type atleast 3 characters </span>
                    </div>
                  </div>
                  <div class="row form-group">
                    <label class="control-label col-sm-3" for="Food">Food type</label>
                    <div class="col-sm-9">          
                      <select name="f_type" ng-model="f_type" class="form-control" required>
                        <option value="veg">Veg</option>
                        <option value="non-veg">NonVeg</option>
                      </select>
                      <span ng-show="!f_type" style="color:#f44336;">select one</span>
                    </div>
                  </div>
                  <div class="row form-group">
                    <label class="control-label col-sm-3" for="meal">Meal type</label>
                    <div class="col-sm-9">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" class="" value="" ng-required="!m_type.break_fast&&!m_type.lunch&&!m_type.snacks&&!m_type.dinner" ng-model="m_type.break_fast" ng-change="myForm.onCheckBoxSelected()" />  
                            Break fast
                        </label>                      
                      </div>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" class="" value="" ng-required="!m_type.break_fast&&!m_type.lunch&&!m_type.snacks&&!m_type.dinner" ng-model="m_type.lunch" ng-change="myForm.onCheckBoxSelected()" />
                            Lunch
                        </label>                      
                      </div>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" class="" value="" ng-required="!m_type.break_fast&&!m_type.lunch&&!m_type.snacks&&!m_type.dinner" ng-model="m_type.snacks" ng-change="myForm.onCheckBoxSelected()" />
                            Snacks
                        </label>                      
                      </div>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" class="" value="" ng-required="!m_type.break_fast&&!m_type.lunch&&!m_type.snacks&&!m_type.dinner" ng-model="m_type.dinner" ng-change="myForm.onCheckBoxSelected()" />
                            Dinner
                        </label>                      
                      </div> 
                      <span style="color:red;" ng-show="!m_type.break_fast&&!m_type.lunch&&!m_type.snacks&&!m_type.dinner ">select any one of the above </span>
                      </div>
                  </div>
                  <div class="row form-group">
                      <label class="control-label col-sm-3" for="stat">Availability</label>
                      <div class="col-sm-9">          
                        <select name="p_avail" ng-model="p_avail" class="form-control" required>
                          <option value="avail">Avail</option>
                          <option value="medium">Medium</option>
                          <option value="notavail">Notavial</option>
                        </select>
                        <span ng-show="!p_avail" style="color:#f44336;">select one</span>
                      </div>
                  </div>
                  <div class="row form-group">
                      <label class="control-label col-sm-3" for="p_pri">Priority</label>
                      <div class="col-sm-9">
                        <select name="p_prior" ng-model="p_prior" class="form-control" placeholder="" required>
                          <option value="1">1 (one)</option>
                          <option value="2">2 (two)</option>
                          <option value="3">3 (three)</option>
                          <option value="4">4 (four)</option>
                          <option value="5">5 (five)</option>
                          <option value="6">6 (six)</option>
                          <option value="7">7 (seven)</option>
                          <option value="8">8 (eight)</option>
                          <option value="9">9 (nine)</option>
                          <option value="10">10 (ten)</option>
                        </select>
                        <span ng-show="!p_prior" style="color:#f44336;">select one</span>
                      </div>
                  </div>

                  <div class="row form-group">
                      <label class="control-label col-sm-3" for="cost">Price (Rs)</label>
                      <div class="col-sm-9">
                        <input type="text" name="p_price" ng-model="p_price" class="form-control" ng-pattern="/^[0-9]+[0-9]*(.[0-9]+)?$/" placeholder="Rs" required>
                        <span ng-show="add_prod.p_price.$error.pattern" style="color:#f44336;"> Type numbers only </span>
                      </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" ng-disabled="add_prod.$invalid||isdisabled" ng-click="add_product()" class="btn btn-primary">Add new</button>
              </div>
            </div>
          </div>
          </div>
          <div class="modal fade" id="edit_prod" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Product</h4>
              </div>
              <div class="modal-body">
                <form name="edit_prod">
                  <div class="row form-group">
                    <label class="control-label col-sm-3" for="p_name">Product Name</label>
                    <div class="col-sm-9">
                      <input type="" name="p_name" ng-model="p_name" ng-pattern='/^[a-zA-Z0-9().:,_\/\-\s\"\&]{3,100}$/' minlength="3" class="form-control" placeholder="" required>
                      <span ng-show="edit_prod.p_name.$error.pattern && !add_prod.p_name.$error.minlength" style="color:#f44336;"> Type characters only </span>
                      <span ng-show="edit_prod.p_name.$error.minlength" style="color:#f44336;"> Type atleast 3 characters </span>
                    </div>
                  </div>
                  <div class="row form-group">
                    <label class="control-label col-sm-3" >Product category</label>
                    <div class="col-sm-9">
                      <select name="c_id" ng-model="c_id" class="form-control" required>
                        <option ng-repeat="cat in category" value="{{cat.category_id}}">{{cat.name}}</option>
                      </select>
                      <span ng-show="edit_prod.c_id.$error.pattern" style="color:#f44336;"> Type characters only </span>
                    </div>
                  </div>
                  <div class="row form-group">
                    <label class="control-label col-sm-3" for="p_descp">Description</label>
                    <div class="col-sm-9">
                      <textarea name="p_descp" ng-model="p_descp" ng-pattern="/^([a-zA-z\s_,.-/])+$/" class="form-control" rows="5" required></textarea>
                      <span ng-show="edit_prod.p_descp.$error.pattern" style="color:#f44336;"> Type characters only </span>
                      </div>
                  </div>
                  <div class="row form-group">
                      <label class="control-label col-sm-3" for="Food">Food type</label>
                      <div class="col-sm-9">          
                        <select name="f_type" ng-model="f_type" class="form-control" required>
                          <option value="veg">Veg</option>
                          <option value="non-veg">NonVeg</option>
                        </select>
                        <span ng-show="!f_type" style="color:#f44336;">select one</span>
                      </div>
                  </div>
                  <div class="row form-group">
                      <label class="control-label col-sm-3" for="meal">Meal type</label>
                      <div class="col-sm-9">
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" class="" value="" ng-required="!m_type.break_fast&&!m_type.lunch&&!m_type.snacks&&!m_type.dinner" ng-model="m_type.break_fast" ng-change="myForm.onCheckBoxSelected()" />  
                            Break fast
                          </label>                      
                        </div>
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" class="" value="" ng-required="!m_type.break_fast&&!m_type.lunch&&!m_type.snacks&&!m_type.dinner" ng-model="m_type.lunch" ng-change="myForm.onCheckBoxSelected()" />
                            Lunch
                          </label>                      
                        </div>
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" class="" value="" ng-required="!m_type.break_fast&&!m_type.lunch&&!m_type.snacks&&!m_type.dinner" ng-model="m_type.snacks" ng-change="myForm.onCheckBoxSelected()" />
                            Snacks
                          </label>                      
                        </div>
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" class="" value="" ng-required="!m_type.break_fast&&!m_type.lunch&&!m_type.snacks&&!m_type.dinner" ng-model="m_type.dinner" ng-change="myForm.onCheckBoxSelected()" />
                            Dinner
                          </label>                      
                        </div>
                        <span style="color:red;" ng-show="!m_type.break_fast&&!m_type.lunch&&!m_type.snacks&&!m_type.dinner ">select any one of the above </span>
                      </div>
                  </div>
                  <div class="row form-group">
                      <label class="control-label col-sm-3" for="stat">Availability</label>
                      <div class="col-sm-9">          
                        <select name="p_avail" ng-model="p_avail" class="form-control" required>
                          <option value="avail">Avail</option>
                          <option value="medium">Medium</option>
                          <option value="notavail">Notavial</option>
                        </select>
                        <span ng-show="!p_avail" style="color:#f44336;">select one</span>
                      </div>
                  </div>
                  <div class="row form-group">
                      <label class="control-label col-sm-3" for="p_pri">Priority</label>
                      <div class="col-sm-9">
                        <select name="p_prior" ng-model="p_prior" class="form-control" placeholder="" required>
                          <option value="1">1 (one)</option>
                          <option value="2">2 (two)</option>
                          <option value="3">3 (three)</option>
                          <option value="4">4 (four)</option>
                          <option value="5">5 (five)</option>
                          <option value="6">6 (six)</option>
                          <option value="7">7 (seven)</option>
                          <option value="8">8 (eight)</option>
                          <option value="9">9 (nine)</option>
                          <option value="10">10 (ten)</option>
                        </select>
                        <span ng-show="!p_prior" style="color:#f44336;">select one</span>
                      </div>
                  </div>
                
                  <div class="row form-group">
                      <label class="control-label col-sm-3" for="cost">Price (Rs)</label>
                      <div class="col-sm-9">
                        <input type="text" name="p_price" ng-model="p_price" class="form-control" ng-pattern="/^[0-9]+[0-9]*(.[0-9]+)?$/" placeholder="Rs" required>
                        <span ng-show="edit_prod.p_price.$error.pattern" style="color:#f44336;"> Type numbers only </span>
                      </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" ng-disabled="edit_prod.$invalid" ng-click="edit_product()" class="btn btn-primary">Update</button>
              </div>
            </div>
          </div>
          </div>
          <br>
        </div>
        <div class="row">
        <div class="col-xs-4">
          <label for="search">Search</label>
          <input ng-model="q" id="search" class="form-control" placeholder="Filter text">
        </div>
        <div class="col-xs-3">
          <label for="search">items per page</label>
          <input type="number" min="1" max="50" class="form-control" ng-model="pageSize">
        </div>
        <div class="col-xs-5 text-center">
          <dir-pagination-controls boundary-links="true" template-url="../tmpl/dirPagination.tmpl.html" pagination-id="product"></dir-pagination-controls>
        </div>
        </div>
        <hr></hr>
        <table class="table table-striped table-hover table-condensed table-responsive">
        <tr>
          <th ng-click="predicate = $index; reverse = !reverse;" >S.No</th>
          <th ng-click="predicate = 'item_name'; reverse = !reverse;" class="cur_pnt" >Name</th>
          <th ng-click="predicate1 = ''; reverse = !reverse;" >Category</th>
          <th ng-click="predicate = 'item_desc'; reverse = !reverse;" >Description</th>
          <th ng-click="predicate = 'food_type'; reverse = !reverse;" >food type</th>
          <th ng-click="predicate = 'meal_type'; reverse = !reverse;" >meal type</th>
          <th ng-click="predicate = 'availability'; reverse = !reverse;" >Availability</th>
          <th ng-click="predicate = 'priority'; reverse = !reverse;" >Priority</th>
          <th ng-click="predicate = 'price'; reverse = !reverse;" >Price</th>
              <!-- <th class="cur_pnt">tax_id</th> -->
          <th>Manage</th>
        </tr>
        <tr dir-paginate="prod in product | filter:q | orderBy:predicate:reverse | itemsPerPage: pageSize" current-page="currentPage" pagination-id="product">
          <td ng-bind="((currentPage-1)*pageSize)+($index+1)"></td>
          <td ng-bind="prod.item_name"></td>
          <td>
            <span ng-repeat="cat in category | filter:{category_id:prod.category_id}">{{cat.name}}</span>
          </td>
          <td ng-bind="prod.item_desc"></td>
          <td ng-bind="prod.food_type"></td>
          <td ng-bind="prod.meal_type"></td>
          <td ng-bind="prod.availability>'1' ? 'medium' :(prod.availability>'0' ? 'avail' :'notavail')"></td>
          <td ng-bind="prod.priority"></td>
          <td ng-bind="prod.price|number:2" style="text-align: right;"></td>
          <td>
            <button type="button" ng-click="abc(prod.item_id,prod.item_name,prod.item_desc,prod.priority,prod.price,prod.category_id,prod.meal_type,prod.food_type,prod.availability)" data-toggle="modal" data-target="#edit_prod" class="btn btn-warning btn-xs">Edit</button>
            <button type="button" ng-click="del_product(prod.item_id)" class="btn btn-danger btn-xs">Delete </button>
          </td>
        </tr>
        </table>
        <br>
      </div>
    </div>
    <div id="tabContent5" class="panel-collapse collapse content " ng-controller="room_cntrl">
      <div class="box box-primary content">
        <div class="row">
          <input type="button" class="btn btn-success" value="Add" data-toggle="modal" data-target="#add_rom" style="float:right" >
          <input type="button" class="btn btn-success btn-sm" ng-click=" get_room();" value="Refresh" style="" >          
          <div class="modal fade" id="add_rom" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Add new room</h4>
                </div>
                <div class="modal-body">
                  <form name="add_rom">
                    <div class="row form-group">
                      <label class="control-label col-sm-3" for="r_name">Name</label>
                      <div class="col-sm-9">
                        <input type="text" name="r_name" ng-model="r_name" class="form-control" ng-pattern="/^([a-zA-Z\s-_])+[^\W\d]$/"  required>
                        <span ng-show="add_rom.r_name.$error.pattern" style="color:#f44336;"> Type characters only </span>
                      </div>
                    </div>
                    <div class="row form-group">
                      <label class="control-label col-sm-3" for="d_name">No of Tables</label>
                      <div class="col-sm-9">
                        <input type="text" name="t_num" ng-model="t_num" class="form-control" placeholder="" ng-pattern="/[0-9]/" maxlength="2" required>
                        <span ng-show="add_rom.t_num.$error.pattern" style="color:#f44336;"> Type numbers only </span>
                      </div>
                    </div> 
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" ng-disabled="add_rom.$invalid" ng-click="add_room()" class="btn btn-primary">Add new</button>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="edit_room" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Edit room </h4>
                </div>
                <div class="modal-body">
                  <form name="edit_rom">
                    <div class="row form-group">
                      <label class="control-label col-sm-3" for="r_name">Room name</label>
                      <div class="col-sm-9">
                        <input type="text" name="r_name" ng-model="r_name" class="form-control" ng-pattern="/^([a-zA-Z\s-_])+[^\W\d]$/"  required>
                        <span ng-show="edit_rom.r_name.$error.pattern" style="color:#f44336;"> Type characters only </span>
                      </div>
                    </div>
                    <div class="row form-group">
                      <label class="control-label col-sm-3" for="d_name">No of Tables</label>
                      <div class="col-sm-9">
                        <input type="text" name="t_num" ng-model="t_num" class="form-control" placeholder="" ng-pattern="/[0-9]/" maxlength="2" required>
                        <span ng-show="edit_rom.t_num.$error.pattern" style="color:#f44336;"> Type numbers only </span>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" ng-disabled="edit_rom.$invalid" ng-click="edit_room()" class="btn btn-primary">Update</button>
                </div>
              </div>
            </div>
          </div>
          <br>
        </div>
        <div class="row">
          <div class="col-xs-4">
            <label for="search">Search</label>
            <input ng-model="q" id="search" class="form-control" placeholder="Filter text">
          </div>
          <div class="col-xs-3">
            <label for="search">items per page</label>
            <input type="number" min="1" max="50" class="form-control" ng-model="pageSize">
          </div>
          <div class="col-xs-5 text-center">
            <dir-pagination-controls boundary-links="true" template-url="../tmpl/dirPagination.tmpl.html" pagination-id="room"></dir-pagination-controls>
          </div>

        </div>
        <hr></hr>
        <table class="table table-striped table-hover table-condensed table-responsive">
          <tr>
            <th ng-click="predicate = $index; reverse = !reverse;" >S.No</th>
            <th ng-click="predicate = 'table_category_name'; reverse = !reverse;" >Room name</th>
            <th ng-click="predicate = 'no_of_tables'; reverse = !reverse;" >No. of tables </th>
            <th>Manage</th>
          </tr>
          <tr dir-paginate="room1 in room | filter:q | orderBy:predicate:reverse | itemsPerPage: pageSize" current-page="currentPage" pagination-id="room">
              <td ng-bind="((currentPage-1)*pageSize)+($index+1)"></td>
              <td ng-bind="room1.table_category_name"></td>
              <td ng-bind="room1.no_of_tables"></td>
              <td>
                <button type="button" ng-click="abc(room1.table_category_id,room1.table_category_name,room1.no_of_tables)" data-toggle="modal" data-target="#edit_room" class="btn btn-warning btn-xs">Edit</button>
                <button type="button" ng-click="del_room(room1.table_category_id)" class="btn btn-danger btn-xs">Delete </button>
              </td>
          </tr>
        </table>
        <br>
      </div>
    </div>
    <div id="tabContent6" class="panel-collapse collapse content" ng-controller="table_cntrl">
      <div class="box box-primary content">
        <div class="row">
          <input type="button" class="btn btn-success" value="Add" data-toggle="modal" data-target="#add_tbl" style="float:right" >
          <input type="button" class="btn btn-success btn-sm" ng-click="get_table()" value="Refresh" style="" >
          <div class="modal fade" id="add_tbl" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Add new table</h4>
                </div>
                <div class="modal-body">
                  <form name="add_tbl">
                    <div class="row form-group">
                      <label class="control-label col-sm-3" for="t_name">Table Name</label>
                      <div class="col-sm-9">
                        <input type="text" name="t_name" ng-model="t_name" class="form-control" ng-pattern="/^[a-z0-9A-Z\s-_]+$/" maxlength="4" placeholder="" required>
                        <span ng-show="add_tbl.t_name.$error.pattern" style="color:#f44336;"> Type characters with (space,-,_ )</span>
                      </div>
                    </div>
                    <div class="row form-group">
                      <label class="control-label col-sm-3" for="rname">Room Name</label>
                      <div class="col-sm-9">   
                        <select name="r_id" ng-model="r_id" class="form-control" required>
                          <option ng-repeat="rom in room" value="{{rom.table_category_id}}">{{rom.table_category_name}}</option>
                        </select>
                      </div>
                    </div>
                    <div class="row form-group">
                      <label class="control-label col-sm-3" for="chair">Number of chairs available</label>
                      <div class="col-sm-9">
                        <input type="text" name="t_chairs" ng-model="t_chairs" class="form-control" ng-pattern="/[0-9]/" maxlength="2" required>
                        <span ng-show="add_tbl.t_chairs.$error.pattern" style="color:#f44336;"> Type numbers only </span>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" ng-disabled="add_tbl.$invalid" ng-click="add_table()" class="btn btn-primary">Add new</button>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="edit_table" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Edit room </h4>
                </div>
                <div class="modal-body">
                  <form name="edit_tbl">
                    <div class="row form-group">
                      <label class="control-label col-sm-3" for="t_name">Table Name</label>
                      <div class="col-sm-9">
                        <input type="text" name="t_name" ng-model="t_name" class="form-control" ng-pattern="/^[a-z0-9A-Z\s-_]+$/" maxlength="4" placeholder="Enter Your table Name" required>
                        <span ng-show="edit_tbl.t_name.$error.pattern" style="color:#f44336;"> Type characters with (space,-,_ )</span>
                      </div>
                    </div>
                    <div class="row form-group">
                      <label class="control-label col-sm-3" for="rname">Room Name</label>
                      <div class="col-sm-9">   
                        <select name="r_id" ng-model="r_id" class="form-control" required>
                          <option ng-repeat="rom in room" value="{{rom.table_category_id}}">{{rom.table_category_name}}</option>
                        </select>
                      </div>
                    </div>
                    <div class="row form-group">
                      <label class="control-label col-sm-3" for="chair">Number of chairs available</label>
                      <div class="col-sm-9">
                        <input type="text" name="t_chairs" ng-model="t_chairs" class="form-control" ng-pattern="/[0-9]/" maxlength="2" required>
                        <span ng-show="edit_tbl.t_chairs.$error.pattern" style="color:#f44336;"> Type numbers only </span>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" ng-disabled="edit_tbl.$invalid" ng-click="edit_table()" class="btn btn-primary">Update</button>
                </div>
              </div>
            </div>
          </div>
          <br>
        </div>
        <div class="row">
          <div class="col-xs-4">
            <label for="search">Search</label>
            <input ng-model="q" id="search" class="form-control" placeholder="Filter text">
          </div>
          <div class="col-xs-3">
            <label for="search">items per page</label>
            <input type="number" min="1" max="50" class="form-control" ng-model="pageSize">
          </div>
          <div class="col-xs-5 text-center">
            <dir-pagination-controls boundary-links="true" template-url="../tmpl/dirPagination.tmpl.html" pagination-id="table"></dir-pagination-controls>
          </div>

        </div>
        <hr></hr>
        <table class="table table-striped table-hover table-condensed table-responsive">
            <tr>
              <th ng-click="predicate = $index; reverse = !reverse;" >S.No</th>
              <th ng-click="predicate = 'table_name'; reverse = !reverse;" >Table name</th>
              <th ng-click="predicate = ''; reverse = !reverse;" >Room name </th>
              <th ng-click="predicate = 'capacity'; reverse = !reverse;" >Capacity</th>
              <th>Manage</th>
            </tr>
            <tr dir-paginate="tbl in table | filter:q | orderBy:predicate:reverse |itemsPerPage: pageSize" current-page="currentPage" pagination-id="table">
              <td ng-bind="((currentPage-1)*pageSize)+($index+1)"></td>
              <td ng-bind="tbl.table_name"></td>
              <td>
                <span ng-repeat="rom in room| filter:{table_category_id:tbl.table_category_id}">
                {{rom.table_category_name}}</span>
              </td>
              <td ng-bind="tbl.capacity"></td>
              <td>
                <button type="button" ng-click="abc(tbl.table_id,tbl.table_category_id,tbl.table_name,tbl.capacity)" data-toggle="modal" data-target="#edit_table" class="btn btn-warning btn-xs">Edit</button>
                <button type="button" ng-click="del_table(tbl.table_id)" class="btn btn-danger btn-xs">Delete </button>
              </td>
            </tr>
        </table>
        <br>
      </div>
    </div>
    <div id="tabContent7" class="panel-collapse collapse content" ng-controller="driv_cntrl">
      <div class="box box-primary content">
        <div class="row">
          <input type="button" class="btn btn-success" value="Add" data-toggle="modal" data-target="#add_driv" style="float:right" >
          <input type="button" class="btn btn-success btn-sm" ng-click=" get_driver();" value="Refresh" style="" >
          <div class="modal fade" id="add_driv" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Add new driver</h4>
                </div>
                <div class="modal-body">
                  <form name="add_driv">
                    
                    
              <div class="row form-group">
                <label class="control-label col-sm-3" for="d_name">Name</label>
                <div class="col-sm-9">
                  <input type="text" name="d_name" ng-model="d_name" class="form-control" ng-pattern="/^([a-zA-Z\s-_]{2,})+[^\W\d]$/" minlength="3" placeholder="" required>
                  <span ng-show="add_driv.d_name.$error.pattern && !add_driv.d_name.$error.minlength" style="color:#f44336;"> Type characters only </span>
                  <span ng-show="add_driv.d_name.$error.minlength" style="color:#f44336;"> Type atleast 3 characters </span>
                </div>
              </div>
              <div class="row form-group">
                <label class="control-label col-sm-3" for="mob">Mobile</label>
                <div class="col-sm-9">          
                  <input type="text" name="d_mobile" ng-model="d_mobile" ng-pattern="/^[7-9]+[0-9]*$/" maxlength="10" minlength="10"  class="form-control" placeholder="" required>
                  <span ng-show="add_driv.d_mobile.$error.pattern&& !add_driv.d_mobile.$error.minlength" style="color:#f44336;">The mobile number should not start with less than 7</span></br>
                  <span ng-show="add_driv.d_mobile.$error.minlength" style="color:#f44336;"> Type 10 numbers </span>
                </div>
              </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" ng-disabled="add_driv.$invalid" ng-click="add_driver()" class="btn btn-primary">Add new</button>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="edit_driv" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-md">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title">Edit Driver </h4>
                </div>
                <div class="modal-body">
                  <form name="edit_driv">
                    <div class="row form-group">
                      <label class="control-label col-sm-3" for="d_name">Name</label>
                      <div class="col-sm-9">
                        <input type="text" name="d_name" ng-model="d_name" class="form-control" ng-pattern="/^([a-zA-Z\s-_]{2,})+[^\W\d]$/" placeholder="" required>
                        <span ng-show="edit_drive.d_name.$error.pattern && !edit_drive.d_name.$error.minlength" style="color:#f44336;"> Type characters only </span>
                        <span ng-show="edit_drive.d_name.$error.minlength" style="color:#f44336;"> Type atleast 3 characters </span>
                      </div>
                    </div>
                    <div class="row form-group">
                      <label class="control-label col-sm-3" for="mob">Mobile</label>
                      <div class="col-sm-9">          
                        <input type="text" name="d_mobile" ng-model="d_mobile" ng-pattern="/^[7-9]+[0-9]*$/" maxlength="10" minlength="10"  class="form-control" placeholder="" required>
                        <span ng-show="edit_driv.d_mobile.$error.pattern && !edit_driv.d_mobile.$error.minlength" style="color:#f44336;">The mobile should not start with lesstan 7 </span></br>
                        <span ng-show="edit_driv.d_mobile.$error.minlength" style="color:#f44336;"> Type 10 numbers </span>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" ng-disabled="edit_driv.$invalid" ng-click="edit_driver()" class="btn btn-primary">Update</button>
                </div>
              </div>
            </div>
          </div>
          <br>
        </div>
        <div class="row">
          <div class="col-xs-4">
            <label for="search">Search</label>
            <input ng-model="q" id="search" class="form-control" placeholder="Filter text">
          </div>
          <div class="col-xs-3">
            <label for="search">items per page</label>
            <input type="number" min="1" max="50" class="form-control" ng-model="pageSize">
          </div>
          <div class="col-xs-5 text-center">
            <dir-pagination-controls boundary-links="true" template-url="../tmpl/dirPagination.tmpl.html" pagination-id="driver"></dir-pagination-controls>
          </div>
        </div>
        <hr></hr>
        <table class="table table-striped table-hover table-condensed table-responsive">
            <tr>
              <th ng-click="predicate = $index; reverse = !reverse;" >S.No</th>
              <th ng-click="predicate = 'name'; reverse = !reverse;" >Name</th>
              <th ng-click="predicate = 'phone'; reverse = !reverse;" >Phone</th>
              <th>Manage</th>
            </tr>
            <tr dir-paginate="driv in driver | filter:q | orderBy:predicate:reverse |itemsPerPage: pageSize" current-page="currentPage" pagination-id="driver">
              <td ng-bind="((currentPage-1)*pageSize)+($index+1)"></td>
              <td ng-bind="driv.name"></td>
              <td ng-bind="driv.phone"></td>
              <td>
                <button type="button" ng-click="abc(driv.driver_id,driv.name,driv.phone)" data-toggle="modal" data-target="#edit_driv" class="btn btn-warning btn-xs">Edit</button>
                <button type="button" ng-click="del_driver(driv.driver_id)" class="btn btn-danger btn-xs">Delete </button>
              </td>
            </tr>
        </table>
        <br>
      </div>
    </div>
    <div id="tabContent8" class="panel-collapse collapse content" ng-controller="order_cntrl">
     <!--  <div class="box box-primary content">
        <div class="row">
            <input type="button" class="btn btn-success btn-sm" ng-click=" get_orders();" value="Refresh" style="float: left;" >
        </div> -->
        <div class="col-lg-8 ">
          <div class="box box-primary content">
            <div class="row">
              <div class="col-xs-6">
                <label for="search">Search</label>
                <input ng-model="q" id="search" class="form-control" placeholder="Filter text">
              </div>
              <div class="col-xs-6">
                <label for="search">items per page</label>
                <input type="number" min="1" max="50" class="form-control" ng-model="pageSize">
              </div>
              <div class="col-xs-12 text-center">
                <dir-pagination-controls boundary-links="true" template-url="../tmpl/dirPagination.tmpl.html" pagination-id="order"></dir-pagination-controls>
              </div>
            </div>
            <hr>
            <table class="table table-striped table-hover table-condensed table-responsive">
                <tr>
                  <th ng-click="predicate = $index; reverse = !reverse;" >S.No</th>
                  <th ng-click="predicate = 'sale_order_id'; reverse = !reverse;">Order no</th>
                  <th ng-click="predicate = 'order_type'; reverse = !reverse;" class="cur_pnt" >Order type</th>
                  <th ng-click="predicate = 'order_date'; reverse = !reverse;" > date & time</th>
                  <th ng-click="predicate = 'driv_name'; reverse = !reverse;" > driver name</th>
                  <th ng-click="predicate = 'status'; reverse = !reverse;" >Status</th>
                  <th ng-click="predicate = 'bill_paid'; reverse = !reverse;" >bill </th>
                  <!-- <th>view bill</th> -->
                </tr>
                <tr dir-paginate="order in orders | filter:q | orderBy:predicate:reverse | itemsPerPage: pageSize" current-page="currentPage" pagination-id="order">
                  <td ng-bind="((currentPage-1)*pageSize)+($index+1)"></td>
                  <td ng-bind="order.sale_order_id"></td>
                  <td ng-bind="order.order_type"></td>
                  <td ng-bind="order.order_date"></td>
                  <td ng-bind="order.driver_name"></td>
                  <td ng-bind="order.status"></td>
                  <!-- <td ng-bind="prod.price|number:2" style="text-align: right;"></td> -->
                  <!-- <td ng-bind="prod.tax_id"></td> -->
                  <td>            
                    <button type="button" ng-show="order.bill_paid==0" ng-click="pay_order(order.sale_order_id)" class="btn btn-danger btn-xs">Pay </button>
                    <span ng-show="order.bill_paid!=0">paid</span>
                    <button type="button" ng-click="get_order_pdf(order.sale_order_id)" class="btn btn-warning btn-xs">View bill</button>
                  </td>
                </tr>
            </table>
          </div>
        </div>
        <div class="col-md-4">
          <div class="box box-success" style="position: fixed;width: 314px;">
            <div ng-controller="pdfcntrl" class="text-center " style="position: fixed;">
                <div class="row">
                  <span class="label" ng-show="totalPages">{{currentPage}}/{{totalPages}}</span>
                </div>
                <div class="row">
                  <div style="background-color: #ffffff; display: initial;" id="pdf_err">

                    <a style="position: absolute;color: #111; font-size: 12px;z-index: 1; height: 446px;
                    background-color:whitesmoke;width: 314px;padding-top: 200px">Bill not generated (or) Please click kitchen/Bill desk to view bill</a>
                  </div>
                  <pdfviewer src="{{pdfURL}}" on-page-load='pageLoaded(page,total)' id="viewer" load-progress='loadProgress(loaded, total, state)'>
                    
                  </pdfviewer>
                </div>
                <div class="row">
                  <div class="btn-group">
                    <button class="btn btn-danger btn-sm" ng-click="pdfURL=pdfkitch">Kitchen</button>
                    <button class="btn btn-danger btn-sm" ng-click="pdfURL=pdfbill">Bill desk</button>
                  </div>
                  <div class="btn-group">
                    <button class="btn btn-danger btn-sm" ng-click="gotoPage(1)">|&lt;</button>
                    <button class="btn btn-danger btn-sm" ng-click="prevPage()">&lt;</button>
                    <button class="btn btn-danger btn-sm" ng-click="nextPage()">&gt;</button>
                    <button class="btn btn-danger btn-sm" ng-click="gotoPage(totalPages)">&gt;|</button>
                  </div>
                </div>
            </div>  
          </div>
        </div>
    </div>
    <div id="tabContent9" class="panel-collapse collapse content" ng-controller="sales_report_cntrl">
          
          <div class="row">
            <div class="col-md-4">
              <label>Select Report</label>
              <select class="form-control" ng-model="report_type">
                <option value="sales_report_by_date" selected>Sales report in rupees</option>
                <option value="sales_product_report_by_date" >Product wise report</option>
                <option value="sales_area_report_by_date" >Area wise report</option>
                <option value="sales_type_report_by_date" >Order type wise report</option>
              </select>
            </div>
            <div class="col-md-4">
              <label>Date range</label>
              <input class="form-control" type="daterange" ng-model="dates" ranges="ranges"ng-change="get_data()"> 
            </div>
            <button class="btn btn-success btn-sm" style="float:right;margin-top:5px;" ng-click="get_data()" >Update Report </button>
          </div>
          <hr>
          <div class="col-lg-12">
            <div class="nav-tabs-custom">
              <ul class="chart-nav nav nav-tabs pull-right">
                <li id="chart1" class="active"><a data-toggle="tab">line chart</a></li>
                <li id="chart2"><a data-toggle="tab">bar chart</a></li>
                <li id="chart3"><a data-toggle="tab">donut chart</a></li>
                <li class="pull-left header"><i class="fa fa-inbox"></i> Sales</li>
              </ul>
              <div class="tab-content no-padding">
                <div id="chart1-view" class="chart tab-pane active" style="position: relative; height: 300px;" line-chart line-pre-units="'&#8377'"   line-data='report1' line-xkey='day' line-ykeys='["a"]' line-labels='["total sale "]' line-colors='["#605CA8", "#c7254e"]' line-resize="true">
  
                </div>

                <div id="chart2-view" class="chart tab-pane" style="position: relative; height: 300px;" bar-chart bar-data='report1' bar-pre-units="'&#8377'"  bar-x='day' bar-y='["a"]' bar-resize="1" bar-labels='["total sale"]' bar-colors='["#31C0BE", "#c7254e"]'>
                </div>
                <div id="chart3-view" class="chart tab-pane" style="position: relative; height: 300px;" donut-chart donut-data='report2' donut-colors='["#31C0BE", "#c7254e", "#F39C13", "#605CA8", "#222D32", "#F31313", "#960084", "#0F6B7F", "#0F7F12", "#FF8E01"]' donut-formatter="myFormatter" donut-resize="true">
                </div>

              </div>
            </div>
          </div>
    </div>
    <div id="tabContent10" class="panel-collapse collapse content" ng-controller="cancel_report_cntrl">
      <div class="row">
            <div class="col-md-4">

                <div>
                  <label>Date range</label>
                  <input class="form-control" type="daterange" ng-model="dates" ranges="ranges" ng-change="get_data()"> 
                </div>
                <button class="btn btn-success btn-sm" style="float:right;margin-top:5px;" ng-click="get_data()" >Update Report </button>
            </div>
      </div>

            
          <div class="col-lg-12">
            <div class="nav-tabs-custom">
              <ul class="chart-nav nav nav-tabs pull-right">
                <li id="chart4" class="active"><a data-toggle="tab">line chart</a></li>
                <li id="chart5"><a data-toggle="tab">bar chart</a></li>
                <li id="chart6"><a data-toggle="tab">donut chart</a></li>
                <li class="pull-left header"><i class="fa fa-inbox"></i> Cancellation</li>
              </ul>
              <div class="tab-content no-padding">
                <div id="chart4-view" class="chart tab-pane active" style="position: relative; height: 300px;" line-chart line-pre-units="''"   line-data='report1' line-xkey='email' line-ykeys='["count"]' line-labels='["orders "]' line-colors='["#31C0BE", "#c7254e"]' line-resize="true">
  
                </div>

                <div id="chart5-view" class="chart tab-pane" style="position: relative; height: 300px;" bar-chart bar-data='report1' bar-x='email' bar-y='["count"]' bar-resize="1" bar-labels='["orders  "]' bar-colors='["#31C0BE", "#c7254e"]'>
                </div>
                <div id="chart6-view" class="chart tab-pane" style="position: relative; height: 300px;" donut-chart donut-data='report2' donut-colors='["#31C0BE", "#c7254e", "#F39C13", "#605CA8", "#222D32", "#F31313", "#960084", "#0F6B7F", "#0F7F12", "#FF8E01"]' donut-formatter="myFormatter" donut-resize="true">
                </div>

              </div>
            </div>
          </div>
    </div>
    <div id="tabContent11" class="panel-collapse collapse content" ng-controller="delivery_report_cntrl">
          <div class="row">
            <div class="col-md-4">

                <div>
                  <label>Date range</label>
                  <input class="form-control" type="daterange" ng-model="dates" ranges="ranges" ng-change="get_data()"> 
                </div>
                <button class="btn btn-success btn-sm" style="float:right;margin-top:5px;" ng-click="get_data()" >Update Report </button>
            </div>
          </div>

            
          <div class="col-lg-12">
            <div class="nav-tabs-custom">
              <ul class="chart-nav nav nav-tabs pull-right">
                <li id="chart7" class="active"><a data-toggle="tab">line chart</a></li>
                <li id="chart8"><a data-toggle="tab">bar chart</a></li>
                <li id="chart9"><a data-toggle="tab">donut chart</a></li>
                <li class="pull-left header"><i class="fa fa-inbox"></i> Delivery</li>
              </ul>
              <div class="tab-content no-padding">
                <div id="chart7-view" class="chart tab-pane active" style="position: relative; height: 300px;" line-chart line-pre-units="''"   line-data='report1' line-xkey='name' line-ykeys='["count"]' line-labels='["orders "]' line-colors='["#31C0BE", "#c7254e"]' line-resize="true">
  
                </div>

                <div id="chart8-view" class="chart tab-pane" style="position: relative; height: 300px;" bar-chart bar-data='report1' bar-x='name' bar-y='["count"]' bar-resize="1" bar-labels='["orders  "]' bar-colors='["#31C0BE", "#c7254e"]'>
                </div>
                <div id="chart9-view" class="chart tab-pane" style="position: relative; height: 300px;" donut-chart donut-data='report2' donut-colors='["#31C0BE", "#c7254e", "#F39C13", "#605CA8", "#222D32", "#F31313", "#960084", "#0F6B7F", "#0F7F12", "#FF8E01"]' donut-formatter="myFormatter" donut-resize="true">
                </div>

              </div>
            </div>
          </div>
    </div>
    <div id="tabContent12" class="panel-collapse collapse content" ng-controller="customer_report_cntrl">
      
      <div class="row">
        <div class="col-md-4">
          <label>Customer name</label>
          <input class="form-control" ng-value="c_name" disabled>
        </div>
        <div class="col-md-4">
          <label>Date range</label>
            <input class="form-control" type="daterange" ng-model="dates" ranges="ranges" > 
        </div>
        <button class="btn btn-success btn-sm" style="float:right;margin-top:5px;"ng-click="get_data()" >Update Report </button>
      </div>

      <div class="col-lg-12">
            <div class="nav-tabs-custom">
              <ul class="chart-nav nav nav-tabs pull-right">
                <li id="chart10" class="active"><a data-toggle="tab">line chart</a></li>
                <li id="chart11"><a data-toggle="tab">bar chart</a></li>
                <li id="chart12"><a data-toggle="tab">donut chart</a></li>
                <li class="pull-left header"><i class="fa fa-inbox"></i> customer order</li>
              </ul>
              <div class="tab-content no-padding">
                <div id="chart10-view" class="chart tab-pane active" style="position: relative; height: 300px;" line-chart line-pre-units="''"   line-data='report1' line-xkey='day' line-ykeys='["count"]' line-labels='["orders "]' line-colors='["#31C0BE", "#c7254e"]' line-resize="true">
  
                </div>

                <div id="chart11-view" class="chart tab-pane" style="position: relative; height: 300px;" bar-chart bar-data='report1' bar-x='day' bar-y='["count"]' bar-resize="1" bar-labels='["orders  "]' bar-colors='["#31C0BE", "#c7254e"]'>
                </div>
                <div id="chart12-view" class="chart tab-pane" style="position: relative; height: 300px;" donut-chart donut-data='report2' donut-colors='["#31C0BE", "#c7254e", "#F39C13", "#605CA8", "#222D32", "#F31313", "#960084", "#0F6B7F", "#0F7F12", "#FF8E01"]' donut-formatter="myFormatter" donut-resize="true">
                </div>

              </div>
            </div>
      </div>
      <hr></hr>
      
      <div class="row">
          <div class="col-xs-4">
            <label for="search">Search</label>
            <input ng-model="q" id="search" class="form-control" placeholder="Filter text">
          </div>
          <div class="col-xs-3">
            <label for="search">items per page</label>
            <input type="number" min="1" max="50" class="form-control" ng-model="pageSize">
          </div>
          <div class="col-xs-5 text-center">
            <dir-pagination-controls boundary-links="true" template-url="../tmpl/dirPagination.tmpl.html" pagination-id="customer"></dir-pagination-controls>
          </div>
      </div>
      <table class="table table-striped table-hover table-condensed table-responsive">
            <tr>
              <th ng-click="predicate = $index; reverse = !reverse;" >S.No</th>
              <th ng-click="predicate = 'name'; reverse = !reverse;" >Customer name</th>
              <th ng-click="predicate = 'email'; reverse = !reverse;" >Email</th>
              <th ng-click="predicate = 'phone'; reverse = !reverse;" >Phone</th>
              <th ng-click="predicate = 'pincode'; reverse = !reverse;" >Pincode</th>
              <th class="cur_pnt">Select</th>
            </tr>
            <tr dir-paginate="cus in customer | filter:q | orderBy:predicate:reverse |itemsPerPage: pageSize" current-page="currentPage" pagination-id="customer">
              <td ng-bind="((currentPage-1)*pageSize)+($index+1)"></td>
              <td ng-bind="cus.name"></td>
              <td ng-bind="cus.email"></td>
              <td ng-bind="cus.phone"></td>
              <td ng-bind="cus.pincode"></td>
              <td>
                <button type="button" ng-click="$parent.c_id=cus.customer_id;$parent.c_name=cus.name" class="btn btn-warning btn-xs">Select</button>
              </td>
            </tr>
      </table>
    </div>
    <div id="tabContent13" class="panel-collapse collapse in" ng-controller="calendar_cntrl">
      <section class="content">
      <div class="row">
        <div class="col-md-3">
          <div class="box box-solid">
            <div class="box-header with-border">
              <h4 class="box-title">Draggable Events</h4>
            </div>
            <div class="box-body">
              <!-- the events -->
              <div id="external-events">
                <!-- <div class="external-event bg-green  ui-draggable ui-draggable-handle" data-id="1">kkjhh</div>
                <div class="external-event bg-green">Lunch</div>
                <div class="external-event bg-yellow">Go home</div>
                <div class="external-event bg-aqua">Do homework</div>
                <div class="external-event bg-light-blue">Work on UI design</div>
                <div class="external-event bg-red">Sleep tight</div>-->
                <div class="checkbox">
                  <label for="drop-remove">
                    <input type="checkbox" id="drop-remove">
                    remove after drop
                  </label>
                </div> 
              </div>
            </div>
          </div>
          <!-- /. box -->
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Create Event</h3>
            </div>
            <div class="box-body">
              <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
                <ul class="fc-color-picker" id="color-chooser">
                  <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
                </ul>
              </div>
              <!-- /btn-group -->
              <div class="input-group">
                <input id="new-event" type="text" class="form-control" placeholder="Event Title">

                <div class="input-group-btn">
                  <button id="add-new-event" type="button" class="btn btn-primary btn-flat">Add</button>
                </div>
                <!-- /btn-group -->
              </div>
              <!-- /input-group -->
            </div>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->
              <div id="fullcalendar"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>{{evnt_update_id}}
    </section>
    </div>

  </div>
    <footer class="main-footer">
      <div class="pull-right hidden-xs">
        <b>Version</b> 1.0.0
      </div>
      <strong>Copyright &copy; 2015-2016 <a href="http://knonex.com">knonex</a>.</strong> All rights reserved.
    </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <div class="tab-content">
      <div class="tab-pane" id="control-sidebar-home-tab">
  
      </div>
    </div>
  </aside>

  <div class="control-sidebar-bg"></div>
  </div>
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <div class="col-md-6">Select an image file
            <input type="file" id="fileInput" accept="image/png, image/jpeg, image/jpg " />

            <div class="cropArea">
              <img-crop image="myImage" result-image="myCroppedImage"></img-crop>
            </div>
          </div>
        </div>


        <div class="modal-footer">
          <button type="button" ng-click="upload()" class="btn btn-default" data-dismiss="modal">Submit
          </button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal -->
  </div>

  <div class="modal fade" id="change_pass" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Change password</h4>
        </div>
        <div class="modal-body">
          <form name="new_pass">                
            <div class="row form-group">
              <label class="control-label col-sm-3" for="name">Current Password</label>
              <div class="col-sm-8">
                <input type="password" class="form-control" name="pass" ng-model="pass" ng-minlength="8" ng-maxlength="20" ng-pattern="/(?=.*[0-9])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])/" placeholder="Enter your current password" required />      
                <span ng-show="new_pass.pass.$error.required && new_pass.pass.$dirty">required</span>
                <span ng-show="!new_pass.pass.$error.required && (new_pass.pass.$error.minlength || new_pass.pass.$error.maxlength) && new_pass.pass.$dirty">Passwords must be between 8 and 20 characters.</span>
                <span ng-show="!new_pass.pass.$error.required && new_pass.pass.$error.pattern && new_pass.pass.$dirty">Must contain one uppercase letter, one number and one symbol.</span>
              </div>
            </div>
            <div class="row form-group">
              <label class="control-label col-sm-3" for="descp">New password</label>
              <div class="col-sm-8">
                <input type="password" class="form-control" name="password" ng-model="password" ng-minlength="8" ng-maxlength="20" ng-pattern="/(?=.*[0-9])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])/" placeholder="Enter your new password" required />
                <span ng-show="new_pass.password.$error.required && new_pass.password.$dirty">required</span>
                <span ng-show="!new_pass.password.$error.required && (new_pass.password.$error.minlength || new_pass.password.$error.maxlength) && new_pass.password.$dirty">Passwords must be between 8 and 20 characters.</span>
                <span ng-show="!new_pass.password.$error.required && new_pass.password.$error.pattern && new_pass.password.$dirty">Must contain one uppercase letter, one number and one symbol.</span>
              </div>
            </div>
            <div class="row form-group">
              <label class="control-label col-sm-3" for="pri">Retype password</label>
              <div class="col-sm-8">
                <input type="password" class="form-control" name="repassword" ng-model="r_password" ng-minlength="8" ng-maxlength="20" ng-pattern="/(?=.*[0-9])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])/" placeholder="Retype your new password" required />
                <span ng-show="password !== r_password">Password mismatch</span>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" ng-disabled="new_pass.$invalid" ng-click="change_password()" class="btn btn-primary">Change password</button>
        </div>
      </div>
    </div>
  </div>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="plugins/morris/morris.min.js"></script>
<script src="plugins/sparkline/jquery.sparkline.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-in-mill.js"></script>
<script src="plugins/knob/jquery.knob.js"></script>
<script src="plugins/chartjs/Chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="plugins/fastclick/fastclick.js"></script>
<script src="plugins/fullcalendar/fullcalendar.min.js"></script>

<script src="dist/js/app.min.js"></script>
<script src="dist/js/pages/dashboard.js"></script>
<script src="dist/js/demo.js"></script>
<script src="../js/angular-morris-chart.js"></script>
<script type="text/javascript">
  var app=angular.module('dashapp',['ngBootstrap','ngDialog','ngImgCrop','googlechart','ngAnimate','ngMaterial','ngPDFViewer','angularUtils.directives.dirPagination','angular.morris-chart']);

  app.controller('dashcntrl', function ($scope,$rootScope,$http, ngDialog) {
    $rootScope.restaurant_name=<?php echo "'$restaurant_name';";?>
    $rootScope.branch_id=<?php echo "'$branch_id';";?>
    var handleFileSelect=function(evt) {
      var file=evt.currentTarget.files[0];
      var reader = new FileReader();
      reader.onload = function (evt) {
        $scope.$apply(function($scope){
          $scope.myImage=evt.target.result;
        });
      };
      reader.readAsDataURL(file);
    };
    angular.element(document.querySelector('#fileInput')).on('change',handleFileSelect);
    $scope.hoverIn = function(){
        this.hoverEdit = true;
        document.getElementById('logo').setAttribute("data-toggle","modal");
        document.getElementById('logo').setAttribute("data-target","#myModal");
    };
    $scope.myImage='';
    $scope.myCroppedImage='';
    $scope.hoverOut = function(){
        this.hoverEdit = false;
        document.getElementById('logo').removeAttribute("data-toggle");
        document.getElementById('logo').removeAttribute("data-target");
    };
    $scope.upload=function(){
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/upload_logo1.php",
          data:{
              image:$scope.myCroppedImage
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              // alert("up"+data.err);
              $rootScope.isdisabled=false;
            }
          else{       
            document.getElementById("logo").setAttribute("style","background: url('"+$scope.myCroppedImage+"')");
            document.getElementById("logo1").setAttribute("src",""+$scope.myCroppedImage+"");
            document.getElementById("logo2").setAttribute("src",""+$scope.myCroppedImage+"");
          }
        }).error(function(error){
          alert("error in connection");
        });
    }
    $scope.change_password= function(){
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"change_password",
              pass:$scope.pass,
              password:$scope.password,
              r_password:$scope.r_password
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
            alert(data.err);
          }
          else{
            alert(data.message);
            $scope.pass="";
            $scope.password="";
            $scope.r_password="";
            $('#change_pass').modal('hide');
            // window.location=window.location.protocol+"//" + document.location.hostname + loc + "/logout.php";
          }
        }).error(function(error){
          alert("error in connection");
        });
    }
    $scope.dashboard=function(){
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"dashboard-details"
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              alert("get"+data.err);
              $rootScope.isdisabled=false;
            }
          else{       
            console.log(data.todo_data);
            $scope.prod_count=data.prod_count;
            $scope.cat_count=data.cat_count;
            $scope.cus_count=data.cus_count;
            $scope.order_count=data.order_count;
            $scope.last_order=data.last_order;
            $scope.last_product=data.last_product;
            // $scope.sale_report=data.sale_report;
            $scope.report1=data.sale_report.data1;
            $scope.report2=data.sale_report.data2;
            $scope.todo_data=data.todo_data;
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.dashboard();
  $('.sidebar-menu li').click(function (e) {
    if(this.id!=14){
      // if(this.id!=1){
        $('#tabContent' + this.id).resize();
        $('#world-map').resize();
      // }
      for (var i = 1; i <= 13; i++) {
        document.getElementById(i).setAttribute("class","");
        $('#tabContent'+i).hide();
        if((i>=9)&&(i<13)){
          document.getElementById("chart"+(((i-8)*3)-2)).setAttribute("class","");
          document.getElementById('chart'+(((i-8)*3)-2)+'-view').setAttribute("class","chart tab-pane");
          document.getElementById("chart"+(((i-8)*3)-1)).setAttribute("class","");
          document.getElementById('chart'+(((i-8)*3)-1)+'-view').setAttribute("class","chart tab-pane");
          document.getElementById("chart"+(((i-8)*3)-0)).setAttribute("class","");
          document.getElementById('chart'+(((i-8)*3)-0)+'-view').setAttribute("class","chart tab-pane");
        }
      }
      document.getElementById(this.id).setAttribute("class","active");
      if((this.id>=9)&&(this.id<13)){
        document.getElementById("chart"+(((this.id-8)*3)-2)).setAttribute("class","active");
        document.getElementById('chart'+(((this.id-8)*3)-2)+'-view').setAttribute("class","chart tab-pane active");
        document.getElementById('chart'+(((this.id-8)*3)-2)+'-view').setAttribute("style","position: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);");
        
      }
      $('#tabContent' + this.id).show();
    }
  })
  $('.chart-nav li').click(function (e) {

    if(this.id!=13){
      $('#'+this.id +'-view').resize();
      for (var i = 1; i <= 12; i++) {
        document.getElementById("chart"+i).setAttribute("class","");
      document.getElementById("chart"+i +'-view').setAttribute("class","chart tab-pane");
        $('#'+ "chart"+i +'-view').hide();
      }
      document.getElementById(this.id).setAttribute("class","active");
      document.getElementById(this.id +'-view').setAttribute("class","chart tab-pane active");
      $('#'+ this.id +'-view').show();
    }
  })

  $('.dashchart-nav li').click(function (e) {

      $('#'+this.id +'-view').resize();
      for (var i = 2; i <= 3; i++) {
        document.getElementById("dashchart"+i).setAttribute("class","");
      document.getElementById("dashchart"+i +'-view').setAttribute("class","chart tab-pane");
        $('#'+ "dashchart"+i +'-view').hide();
      }
      document.getElementById(this.id).setAttribute("class","active");
      document.getElementById(this.id +'-view').setAttribute("class","chart tab-pane active");
      $('#'+ this.id +'-view').show();
  })
});
app.controller('user_cntrl', function ($scope,$http) {
  $scope.currentPage = 1;
  $scope.pageSize = 10;

  $scope.get_user=function(){
    $scope.u_email="";
    $scope.u_password="";
    $scope.u_role_id="";
    $scope.u_id="";
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"user_get_all_from_admin"
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              // alert("user_get"+data.err);
              $rootScope.isdisabled=false;
            }
          else{       
            $scope.users=data.users;
            $scope.roles=data.roles;
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.add_user=function () {
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"user_insert_from_admin",
              email:$scope.u_email,
              password:$scope.u_password,
              r_id:$scope.u_role_id
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              alert(""+data.err);
            }
          else{       
            $scope.get_user();
            $('#add_usr').modal('hide');
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.edit_user=function () {
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"user_update_from_admin",
              email:$scope.u_email,
              user_id:$scope.u_id,
              password:$scope.u_password,
              r_id:$scope.u_role_id
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              // alert(""+data.err);
            }
          else{       
            $scope.get_user();
            $('#edit_user').modal('hide');
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.del_user=function (e_id) {
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"user_delete_from_admin",
              e_id:e_id
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              // alert("user_del"+data.err);
            }
          else{       
            $scope.get_user();
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.abc=function(id,id2,email){
    $scope.u_email=email;
    $scope.u_role_id=id2;
    $scope.u_id=id;
  }
  $scope.get_user();
});
app.controller('cat_cntrl', function ($scope,$http) {
  $scope.currentPage = 1;
  $scope.pageSize = 10;
  $scope.get_category=function(){
      $scope.c_name="";
      $scope.c_descp="";
      $scope.c_prior="1";
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"category_get_all"
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              // alert("get_cat"+data.err);
              $rootScope.isdisabled=false;
            }
          else{       
            $scope.category=data.data;
            angular.forEach($scope.category,function (cat){
              cat.priority=parseFloat(cat.priority);
            })
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.add_category=function () {
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"category_insert",
              c_name:$scope.c_name,
              c_descp:$scope.c_descp,
              c_prior:$scope.c_prior
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              alert("cat_ins"+data.err);
            }
          else{       
            $scope.get_category();
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.edit_category=function () {
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"category_update",
              c_id:$scope.c_id,
              c_name:$scope.c_name,
              c_descp:$scope.c_descp,
              c_prior:$scope.c_prior
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              // alert("cat_up"+data.err);
            }
          else{       
            $scope.get_category();
            $('#edit_cat').modal('hide');
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.del_category=function (c_id) {
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"category_delete",
              c_id:c_id
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              // alert("cat_del"+data.err);
            }
          else{       
            $scope.get_category();
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.abc=function(id,name,descp,priority){
    $scope.c_id=id;
    $scope.c_name=name;
    $scope.c_descp=descp;
    $scope.c_prior=priority;
  }

  $scope.get_category();
});
app.controller('prod_cntrl', function ($scope,$http,$rootScope) {
  $scope.currentPage = 1;
  $scope.pageSize = 10;
  

  $scope.get_product=function(){
    $rootScope.isdisabled=false;
    $scope.p_name="";
    $scope.p_descp="";
    $scope.p_prior="1";
    $scope.p_price="";
    $scope.c_id="";
    $scope.m_type = {
      break_fast : true,
      lunch : false,
      snacks : false,
      dinner: false
    };
    $scope.f_type="veg";
    $scope.p_avail="avail";
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"product_cat_get_all"
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              // alert("p_get"+data.err);
              $rootScope.isdisabled=false;
            }
          else{       
            $scope.product=data.data;
            $scope.category=data.data1;
            angular.forEach($scope.product,function (prod){
              prod.priority=parseFloat(prod.priority);
              prod.price=parseFloat(prod.price);
            })
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.add_product=function () {
    $rootScope.isdisabled=true;
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"product_insert",
              p_name:$scope.p_name,
              p_descp:$scope.p_descp,
              p_prior:$scope.p_prior,
              p_price:$scope.p_price,
              c_id:$scope.c_id,
              m_type:$scope.m_type,
              f_type:$scope.f_type,
              p_avail:$scope.p_avail
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              // alert("pro_ins"+data.err);   
              $rootScope.isdisabled=false;
         }
          else{       
            $scope.get_product();
            $('#add_prod').modal('hide');
              $rootScope.isdisabled=false;

          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.edit_product=function () {
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"product_update",
              p_id:$scope.p_id,
              p_name:$scope.p_name,
              p_descp:$scope.p_descp,
              p_prior:$scope.p_prior,
              p_price:$scope.p_price,
              c_id:$scope.c_id,
              m_type:$scope.m_type,
              f_type:$scope.f_type,
              p_avail:$scope.p_avail
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              // alert("pro_up"+data.err);
            }
          else{       
            $scope.get_product();
            $('#edit_prod').modal('hide');
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.del_product=function (p_id) {
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"product_delete",
              p_id:p_id
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              // alert("pro_del"+data.err);
            }
          else{       
            $scope.get_product();
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.abc=function(p_id,name,desc,prior,price,id,meals,food,avail){
    $scope.p_id=p_id;
    $scope.p_name=name;
    $scope.p_descp=desc;
    $scope.p_prior=prior;
    $scope.p_price=price;
    $scope.c_id=id;
    $scope.m_type = {
      break_fast : false,
      lunch : false,
      snacks : false,
      dinner: false
    };
    meal=meals.split(", ");
    for (var i = 0; i < meal.length; i++) {
      if(meal[i]=="break_fast")$scope.m_type.break_fast=true;
      else if(meal[i]=="lunch")$scope.m_type.lunch=true;
      else if(meal[i]=="snacks")$scope.m_type.snacks=true;
      else if(meal[i]=="dinner")$scope.m_type.dinner=true;
    };
    $scope.f_type=food;
    avail=avail=='1' ? 'avail' :(avail=='2' ? 'medium' :'notavail');
    $scope.p_avail=avail;
  }

  $scope.get_product();
});
app.controller('room_cntrl', function ($scope, $http) {
  $scope.currentPage = 1;
  $scope.pageSize = 10;

  $scope.get_room=function (){
    $scope.r_name="";
    $scope.t_num="";
    $scope.r_id="";
    var loc = document.location.pathname;
    pos=loc.lastIndexOf('/');
    loc=loc.substr(0, pos);
    var http=$http({
      method:"post",
      url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
      data:{
        mode:"room_get_all"
      },
      headers:{'Content-Type':'application/x-www-form-urlencoded'}
    });
    http.success(function(data){
      if ( !data.success) {
        // alert("room_get"+data.err);
        $rootScope.isdisabled=false;
      }
      else{       
        $scope.room=data.data;
          angular.forEach($scope.room,function (rom){
          rom.no_of_tables=parseFloat(rom.no_of_tables);
        })
      }
    }).error(function(error){
      alert("error in connection");
    });
  }
  $scope.add_room=function (){
    var loc = document.location.pathname;
    pos=loc.lastIndexOf('/');
    loc=loc.substr(0, pos);
    var http=$http({
      method:"post",
      url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
      data:{
        mode:"room_insert",
        r_name:$scope.r_name,
        t_num:$scope.t_num
      },
      headers:{'Content-Type':'application/x-www-form-urlencoded'}
    });
    http.success(function(data){
      if ( !data.success) {
        alert(data.err);
        $rootScope.isdisabled=false;
      }
      else{       
        $scope.get_room();
        $('#add_rom').modal('hide');
      }
    }).error(function(error){
      alert("error in connection");
    });
  }
  $scope.edit_room=function (){
    var loc = document.location.pathname;
    pos=loc.lastIndexOf('/');
    loc=loc.substr(0, pos);
    var http=$http({
      method:"post",
      url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
      data:{
        mode:"room_update",
        r_id:$scope.r_id,
        r_name:$scope.r_name,
        t_num:$scope.t_num
      },
      headers:{'Content-Type':'application/x-www-form-urlencoded'}
    });
    http.success(function(data){
      if ( !data.success) {
        // alert("room_up"+data.err);
        $rootScope.isdisabled=false;
      }
      else{       
        $scope.get_room();
        $('#edit_room').modal('hide');
      }
    }).error(function(error){
      alert("error in connection");
    });
  }
  $scope.del_room=function (r_id){
    var loc = document.location.pathname;
    pos=loc.lastIndexOf('/');
    loc=loc.substr(0, pos);
    var http=$http({
      method:"post",
      url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
      data:{
        mode:"room_delete",
        r_id:r_id
      },
      headers:{'Content-Type':'application/x-www-form-urlencoded'}
    });
    http.success(function(data){
      if ( !data.success) {
        // alert("room_del"+data.err);
        $rootScope.isdisabled=false;
      }
      else{       
        $scope.get_room();
      }
    }).error(function(error){
      alert("error in connection");
    });
  }
  $scope.abc=function(id,name,num){
    $scope.r_id=id;
    $scope.r_name=name;
    $scope.t_num=num;
  }
  $scope.get_room();
})
app.controller('table_cntrl', function ($scope, $http) {
  $scope.currentPage = 1;
  $scope.pageSize = 10;

  $scope.get_table=function (){
    $scope.t_name="";
    $scope.t_num="";
    $scope.r_id="";
    var loc = document.location.pathname;
    pos=loc.lastIndexOf('/');
    loc=loc.substr(0, pos);
    var http=$http({
      method:"post",
      url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
      data:{
        mode:"table_get_all"
      },
      headers:{'Content-Type':'application/x-www-form-urlencoded'}
    });
    http.success(function(data){
      if ( !data.success) {
        // alert(data.err);
        $rootScope.isdisabled=false;
      }
      else{       
        $scope.table=data.data;
        $scope.room=data.data1;
        angular.forEach($scope.table,function (tbl){
          tbl.capacity=parseFloat(tbl.capacity);
        })
      }
    }).error(function(error){
      alert("error in connection");
    });
  }
  $scope.add_table=function (){
    var loc = document.location.pathname;
    pos=loc.lastIndexOf('/');
    loc=loc.substr(0, pos);
    var http=$http({
      method:"post",
      url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
      data:{
        mode:"table_insert",
        r_id:$scope.r_id,
        t_name:$scope.t_name,
        t_chairs:$scope.t_chairs
      },
      headers:{'Content-Type':'application/x-www-form-urlencoded'}
    });
    http.success(function(data){
      if ( !data.success) {
        alert(""+data.err);
        $rootScope.isdisabled=false;
      }
      else{       
        $scope.get_table();
        $('#add_tbl').modal('hide');
      }
    }).error(function(error){
      alert("error in connection");
    });
  }
  $scope.edit_table=function (){
    var loc = document.location.pathname;
    pos=loc.lastIndexOf('/');
    loc=loc.substr(0, pos);
    var http=$http({
      method:"post",
      url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
      data:{
        mode:"table_update",
        t_id:$scope.t_id,
        r_id:$scope.r_id,
        t_name:$scope.t_name,
        t_chairs:$scope.t_chairs
      },
      headers:{'Content-Type':'application/x-www-form-urlencoded'}
    });
    http.success(function(data){
      if ( !data.success) {
        // alert("table_up"+data.err);
        $rootScope.isdisabled=false;
      }
      else{       
        $scope.get_table();
        $('#edit_table').modal('hide');
      }
    }).error(function(error){
      alert("error in connection");
    });
  }
  $scope.del_table=function (t_id){
    var loc = document.location.pathname;
    pos=loc.lastIndexOf('/');
    loc=loc.substr(0, pos);
    var http=$http({
      method:"post",
      url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
      data:{
        mode:"table_delete",
        t_id:t_id
      },
      headers:{'Content-Type':'application/x-www-form-urlencoded'}
    });
    http.success(function(data){
      if ( !data.success) {
        // alert("table_del"+data.err);
        $rootScope.isdisabled=false;
      }
      else{       
        $scope.get_table();
      }
    }).error(function(error){
      alert("error in connection");
    });
  }
  $scope.abc=function(t_id,r_id,name,cap){
    $scope.t_id=t_id;
    $scope.r_id=r_id;
    $scope.t_name=name;
    $scope.t_chairs=cap;
  }
  $scope.get_table();
})
app.controller('driv_cntrl', function ($scope,$http) {
  $scope.currentPage = 1;
  $scope.pageSize = 10;

  $scope.get_driver=function(){
      $scope.d_id="";
      $scope.d_name="";
      $scope.d_mobile="";
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"driver_get_all"
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              // alert("driv_get"+data.err);
              $rootScope.isdisabled=false;
            }
          else{       
            $scope.driver=data.data;
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.add_driver=function () {
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"driver_insert",
              d_name:$scope.d_name,
              d_mobile:$scope.d_mobile
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              alert(""+data.err);
            }
          else{       
            $scope.get_driver();
            $('#add_driv').modal('hide');
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.edit_driver=function () {
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"driver_update",
              d_id:$scope.d_id,
              d_name:$scope.d_name,
              d_mobile:$scope.d_mobile
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              alert(""+data.err);
            }
          else{       
            $scope.get_driver();
            $('#edit_driv').modal('hide');
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.del_driver=function (d_id) {
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"driver_delete",
              d_id:d_id
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              // alert("driv_del"+data.err);
            }
          else{       
            $scope.get_driver();
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.abc=function(id,name,phone){
    $scope.d_id=id;
    $scope.d_name=name;
    $scope.d_mobile=phone;
  }
  $scope.get_driver();
});
app.controller('pdfcntrl', ['$rootScope', '$scope', 'PDFViewerService', function( $rootScope, $scope, pdf) {
  $rootScope.pdfURL="";

  $scope.instance = pdf.Instance("viewer");

  $scope.nextPage = function() {
    $scope.instance.nextPage();
  };

  $scope.prevPage = function() {
    $scope.instance.prevPage();
  };

  $scope.gotoPage = function(page) {
    $scope.instance.gotoPage(page);
  };

  $scope.pageLoaded = function(curPage, totalPages) {
    $scope.currentPage = curPage;
    $scope.totalPages = totalPages;
  };

  $scope.loadProgress = function(loaded, total, state) {
    console.log('loaded =', loaded, 'total =', total, 'state =', state);
  };
}]);
app.controller('order_cntrl', function ($scope,$http, $rootScope) {
  $scope.currentPage = 1;
  $scope.pageSize = 10;

  $scope.get_orders=function(){
      $scope.d_id="";
      $scope.d_name="";
      $scope.d_mobile="";
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"order_get_all"
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              // alert("order_get"+data.err);
              $rootScope.isdisabled=false;
            }
          else{       
            $scope.orders=data.data;
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.get_order_pdf=function(id){
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"order_get_pdf",
              order_id:id
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              alert("order_get"+data.err);
              $rootScope.isdisabled=false;
            }
          else{       
            var loc1 = document.location.pathname;
        pos1=loc1.lastIndexOf('/');
        loc1=loc1.substr(0, pos);
        pos1=loc1.lastIndexOf('/');
        loc1=loc1.substr(0, pos1);
            $rootScope.pdfbill=window.location.protocol+"//" + document.location.hostname + loc1 +"/bill/"+data.pdffile1;
            $rootScope.pdfkitch=window.location.protocol+"//" + document.location.hostname + loc1 +"/bill/"+data.pdffile2;
            $rootScope.pdfURL=window.location.protocol+"//" + document.location.hostname + loc1 +"/bill/"+data.pdffile2;
            // alert(data.pdfloc)
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.pay_order=function(id){
      var loc = document.location.pathname;
        pos=loc.lastIndexOf('/');
        loc=loc.substr(0, pos);
        var http=$http({
          method:"post",
          url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
          data:{
              mode:"order_pay",
              order_id:id
          },
          headers:{'Content-Type':'application/x-www-form-urlencoded'}

        });
        http.success(function(data){
          if ( !data.success) {
              alert("order_pay"+data.err);
              $rootScope.isdisabled=false;
            }
          else{       
            // $rootScope.pdfURL="../bill/"+data.pdffile;
            $scope.get_orders();
          }
        }).error(function(error){
          alert("error in connection");
        });
  }
  $scope.get_orders();
});
app.controller('sales_report_cntrl', function ($scope, $http) {
  $scope.currentPage = 1;
  $scope.pageSize = 10;
  $scope.chart_type="line";
  $scope.report_type="sales_report_by_date";
  $scope.data1 = [
    { day: "2006", a: 100, b: 90 },
    { day: "2007", a: 75,  b: 65 },
    { day: "2008", a: 50,  b: 40 },
    { day: "2009", a: 75,  b: 65 },
    { day: "2010", a: 50,  b: 40 },
    { day: "2011", a: 75,  b: 65 },
    { day: "2012", a: 100, b: 90 }
  ];
  $scope.data2=[
    {label: "Download Sales", value: 12},
    {label: "In-Store Sales",value: 30},
    {label: "Mail-Order Sales", value: 20}
    ];
  $scope.dates = { startDate: moment().subtract(30, 'day'), endDate: moment().subtract(0, 'day') };
    $scope.ranges = {
      'Today': [moment(), moment()],
      'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
      'Last 7 days': [moment().subtract('days', 6), moment()],
      'Last 30 days': [moment().subtract('days', 30), moment()],
      'This month': [moment().startOf('month'), moment()]
    };
  $scope.get_data=function (){
    var loc = document.location.pathname;
    pos=loc.lastIndexOf('/');
    loc=loc.substr(0, pos);
    // alert($scope.dates.startDate)
    var http=$http({
      method:"post",
      url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
      data:{
        mode:$scope.report_type,
        startDate:$scope.dates.startDate,
        endDate:$scope.dates.endDate
      },
      headers:{'Content-Type':'application/x-www-form-urlencoded'}
    });
    http.success(function(data){
      if ( !data.success) {
        // alert("date_get"+data.err);
        $rootScope.isdisabled=false;
      }
      else{       
        $scope.report1=data.report.data1;
        $scope.report2=data.report.data2;

      }
    }).error(function(error){
      alert("error in connection");
    });
  }
  $scope.get_data();
  $scope.myFormatter = function(input) {
      return '₹'+input;
    };
});
app.controller('cancel_report_cntrl', function ($scope, $http) {
  $scope.currentPage = 1;
  $scope.pageSize = 10;
  $scope.chart_type="pie";
  $scope.data1 = [
    { email: "Empty", count: 0}
  ];
  $scope.data2=[
    {label: "empty", value: 0}
    ];
  $scope.dates = { startDate: moment().subtract(30, 'day'), endDate: moment().subtract(0, 'day') };
    $scope.ranges = {
      'Today': [moment(), moment()],
      'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
      'Last 7 days': [moment().subtract('days', 6), moment()],
      'Last 30 days': [moment().subtract('days', 30), moment()],
      'This month': [moment().startOf('month'), moment()]
    };

  $scope.get_data=function (){
    var loc = document.location.pathname;
    pos=loc.lastIndexOf('/');
    loc=loc.substr(0, pos);
    // alert($scope.dates.startDate)
    var http=$http({
      method:"post",
      url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
      data:{
        mode:"cancel_report_by_date",
        startDate:$scope.dates.startDate,
        endDate:$scope.dates.endDate
      },
      headers:{'Content-Type':'application/x-www-form-urlencoded'}
    });
    http.success(function(data){
      if ( !data.success) {
        $rootScope.isdisabled=false;
      }
      else if(data.report.data1){       
        $scope.report1=data.report.data1;
        $scope.report2=data.report.data2;
      }
      else{       
        $scope.report1=$scope.data1;
        $scope.report2=$scope.data2;
      }
    }).error(function(error){
      alert("error in connection");
    });
  }
  $scope.get_data();
  $scope.myFormatter = function(input) {
      return input + ' order';
    };  
});
app.controller('delivery_report_cntrl', function ($scope, $http) {
  $scope.currentPage = 1;
  $scope.pageSize = 10;
  $scope.chart_type="pie";
  $scope.data1 = [
    { name: "no data", count: 0}
  ];
  $scope.data2=[
    {label: "empty", value: 0}
    ];
  $scope.dates = { startDate: moment().subtract(30, 'day'), endDate: moment().subtract(0, 'day') };
    $scope.ranges = {
      'Today': [moment(), moment()],
      'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
      'Last 7 days': [moment().subtract('days', 6), moment()],
      'Last 30 days': [moment().subtract('days', 30), moment()],
      'This month': [moment().startOf('month'), moment()]
    };

  $scope.get_data=function (){
    var loc = document.location.pathname;
    pos=loc.lastIndexOf('/');
    loc=loc.substr(0, pos);
    // alert($scope.dates.startDate)
    var http=$http({
      method:"post",
      url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
      data:{
        mode:"delivery_report_by_date",
        startDate:$scope.dates.startDate,
        endDate:$scope.dates.endDate
      },
      headers:{'Content-Type':'application/x-www-form-urlencoded'}
    });
    http.success(function(data){
      if ( !data.success) {
        // alert("date_get"+data.err);
        $rootScope.isdisabled=false;
      }
      else if(data.report.data1){       
        $scope.report1=data.report.data1;
        $scope.report2=data.report.data2;
      }
      else{       
        $scope.report1=$scope.data1;
        $scope.report2=$scope.data2;
      }
    }).error(function(error){
      alert("error in connection");
    });
  }
  $scope.get_data();
  $scope.myFormatter = function(input) {
      return input + ' order';
    };
});
app.controller('customer_report_cntrl', function ($scope, $http) {
  $scope.currentPage = 1;
  $scope.pageSize = 10;
  $scope.chart_type="pie";
  $scope.c_id=0;
  $scope.data1 = [
    { day: "no data", count: "0", b: 90 }
  ];
  $scope.data2=[
    {label: "empty", value: "0"}
    ];
  $scope.dates = { startDate: moment().subtract(30, 'day'), endDate: moment().subtract(0, 'day') };
    $scope.ranges = {
      'Today': [moment(), moment()],
      'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
      'Last 7 days': [moment().subtract('days', 6), moment()],
      'Last 30 days': [moment().subtract('days', 30), moment()],
      'This month': [moment().startOf('month'), moment()]
    };

  $scope.get_data=function (){
    var loc = document.location.pathname;
    pos=loc.lastIndexOf('/');
    loc=loc.substr(0, pos);
    // alert($scope.c_id)
    var http=$http({
      method:"post",
      url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
      data:{
        mode:"customer_report_by_date",
        startDate:$scope.dates.startDate,
        endDate:$scope.dates.endDate,
        id:$scope.c_id
      },
      headers:{'Content-Type':'application/x-www-form-urlencoded'}
    });
    http.success(function(data){
      if ( !data.success) {
        alert("date_get"+data.err);
        $rootScope.isdisabled=false;
      }
      else if(data.report.data1){ 
        console.log(data)
        $scope.report1=data.report.data1;
        $scope.report2=data.report.data2;
        $scope.customer=data.customer;
      }
      else{  
        $scope.report1=$scope.data1;
        $scope.report2=$scope.data2;
        $scope.customer=data.customer;     
      }
    }).error(function(error){
      alert("error in connection");
    });
  }
  $scope.get_data();
  $scope.myFormatter = function(input) {
      return input + ' order';
    };
}); 
app.controller('calendar_cntrl', function ($scope, $http) {
  var loc = document.location.pathname;
  var pos=loc.lastIndexOf('/');
  loc=loc.substr(0, pos);
  $scope.get_data=function (){
    var http=$http({
      method:"post",
      url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
      data:{
        mode:"calender_get_all_event_list"
      },
      headers:{'Content-Type':'application/x-www-form-urlencoded'}
    });
    http.success(function(data){
      if ( !data.success) {
        alert("date_get"+data.err);
        $rootScope.isdisabled=false;
      }
      else if(data.event_list){ 
        // console.log(data)
        for (var i = 0; i < data.event_list.length; i++) {
          var currColor=data.event_list[i].color;
          var ev_id=data.event_list[i].id;
          var val=data.event_list[i].title;
          var event = $("<div />");
          event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
          event.html(val);
          event.attr("data-id",ev_id);
          console.log("new event "+val+" created with id "+ev_id);
          $('#external-events').prepend(event);
          //Add draggable funtionality
          ini_events(event);
          

        }
      }
    }).error(function(error){
      alert("error in connection");
    });
  }
  function ini_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex: 1070,
          revert: true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        });

      });
  }
  $scope.get_data();
  $scope.ins_id=0;
  $scope.create_event=function (title,color) {
    console.log("cntrl "+title+" "+color);
    var http=$http({
      method:"post",
      url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
      data:{
        mode:"calendar_create_event",
        title:title,
        color:color
      },
      headers:{'Content-Type':'application/x-www-form-urlencoded'}
    }).success(function(data){
      if ( !data.success) {
        alert("event_cre"+data.err);
        $scope.ins_id = 0;
      }
      else if(data.id){ 
        // console.log(data);
        $scope.ins_id = data.id;
      }
    }).error(function(error){
      alert("error in connection");
      $scope.ins_id = -1;
    });
  }
  $scope.evnt_ins_id=0;
  $scope.calendar_insert_event=function (id,start,end) {

    $scope.evnt_ins_id=0;
    console.log("cntrl "+id+" "+start+" "+end);
    var http=$http({
      method:"post",
      url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
      data:{
        mode:"calendar_insert_event",
        id:id,
        start:start,
        end:end
      },
      headers:{'Content-Type':'application/x-www-form-urlencoded'}
    }).success(function(data){
      if ( !data.success) {
        alert("event_cre"+data.err);
        $scope.evnt_ins_id = 0;
      }
      else if(data.id){ 
        console.log(data);
        $scope.evnt_ins_id = data.id;
      }
    }).error(function(error){
      alert("error in connection");
      $scope.evnt_ins_id = -1;
    });
  }
  $scope.calendar_update_event=function (id,start,end) {
    console.log("cntrl "+id+" "+start+" "+end);
    var http=$http({
      method:"post",
      url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
      data:{
        mode:"calendar_update_event",
        id:id,
        start:start,
        end:end
      },
      headers:{'Content-Type':'application/x-www-form-urlencoded'}
    }).success(function(data){
      if ( !data.success) {
        alert("event_cre"+data.err);
        $scope.evnt_update_id = 0;
      }
      else if(data.rows){ 
        console.log(data);
        $scope.evnt_update_id = data.rows;
      }
    }).error(function(error){
      alert("error in connection");
      $scope.evnt_update_id = -1;
    });
  }
  $scope.calendar_deact_event=function (id) {
    console.log("deactivate "+id);
    var http=$http({
      method:"post",
      url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
      data:{
        mode:"calendar_deact_event",
        id:id
      },
      headers:{'Content-Type':'application/x-www-form-urlencoded'}
    }).success(function(data){
      if ( !data.success) {
        alert("event_cre"+data.err);
        $scope.evnt_deact_id = 0;
      }
      else if(data.rows){ 
        console.log(data);
        $scope.evnt_deact_id = data.rows;
      }
    }).error(function(error){
      alert("error in connection");
    });
  }
});
  </script>
</body>



<script>
  $(function () {
    var drop_evnt=1;
    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex: 1070,
          revert: true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        });

      });
    }

//         var scope = angular.element(document.getElementById("tabContent13")).scope();
// scope.$apply(function () {
//   scope.get_data();
    ini_events($('#external-events div.external-event'));
  // });

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date();
    var d = date.getDate(),
        m = date.getMonth(),
        y = date.getFullYear();
    var loc = document.location.pathname;
    var pos=loc.lastIndexOf('/');
    loc=loc.substr(0, pos);
    var url=window.location.protocol+"//" + document.location.hostname + loc + "/get_events.php";
        // console.log()
    $('#fullcalendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
      buttonText: {
        today: 'today',
        month: 'month',
        week: 'week',
        day: 'day'
      },
      events: {
        url: url
      },
      editable: true,
      droppable: true, // this allows things to be dropped onto the calendar !!!
      drop: function (date, allDay) { // this function is called when something is dropped

        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject');
        var event_id=$(this).data('id');
        var event_start=date;
        var event_end=new Date(new Date(date).getTime() + 60* 60000);
        // we need to copy it, so that multiple events don't have a reference to the same object
          
            var copiedEventObject = $.extend({}, originalEventObject);
        copiedEventObject.backgroundColor = $(this).css("background-color");
        copiedEventObject.borderColor = $(this).css("border-color");
        var scope = angular.element(document.getElementById("tabContent13")).scope();
        scope.$apply(function () {
          scope.calendar_insert_event(event_id,event_start,event_end);
        });
        scope.$watch('evnt_ins_id', function(newid, oldid) {
          console.log("new id"+newid);  
          console.log("old id"+oldid);  
          // render the event on the calendar
          // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
          if((newid>0)&&(!(newid!=oldid)||(drop_evnt==1))){
            drop_evnt++;
            console.log("if")
        // assign it the date that was reported
        copiedEventObject.start = event_start;
        copiedEventObject.end=event_end;
        copiedEventObject.allDay = false;
        
            copiedEventObject.id = newid;
            $('#fullcalendar').fullCalendar('renderEvent', copiedEventObject, true);
            console.log(originalEventObject.title+" was created on "+date+" ")    
          }
        })
        // is the "remove after drop" checkbox checked?
        if ($('#drop-remove').is(':checked')) {
          scope.$apply(function () {
            scope.calendar_deact_event(event_id);
          });
          scope.$watch('evnt_deact_id', function(newid, oldid) {
            if(newid==1){   
          // if so, remove the element from the "Draggable Events" list
              console.log(originalEventObject.title+" removed from list");
          $(this).remove();
            }
            else if(newid==0){
              revertFunc();
            }
          });
          scope.$apply(function () {
            scope.evnt_deact_id=-1;
          });
        }

      },

      eventResize: function(event, delta, revertFunc) {
        var scope = angular.element(document.getElementById("tabContent13")).scope();
        scope.$apply(function () {
          scope.calendar_update_event(event.id,event.start,event.end);
        });
        scope.$watch('evnt_update_id', function(newid, oldid) {
          if(newid==1){
            console.log(event.title + " end is now " + event.end);
            console.log(event.id)
          }
          else if(newid==0){
            revertFunc();
          }
        });
        scope.$apply(function () {
          scope.evnt_update_id=-1;
        });
        
    },
    eventDrop: function(event, delta, revertFunc) {
        
        var scope = angular.element(document.getElementById("tabContent13")).scope();
        scope.$apply(function () {
          scope.calendar_update_event(event.id,event.start,event.end);
        });
        scope.$watch('evnt_update_id', function(newid, oldid) {
          if(newid==1){
            console.log(event.id+" "+event.title + " was dropped from " + event.start+" on "+event.end);
            
          }
          else if(newid==0){
            revertFunc();
          }
        });
        scope.$apply(function () {
          scope.evnt_update_id=-1;
        });
      },eventClick: function(calEvent, jsEvent, view) {

        alert('Event: ' + calEvent.title);
        console.log(calEvent)
        // alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
        // alert('View: ' + view.name);

        // change the border color just for fun
        // $(this).css('border-color', 'red');

    }
    });

    /* ADDING EVENTS */
    var currColor = "#3c8dbc"; //Red by default
    //Color chooser button
    var colorChooser = $("#color-chooser-btn");
    $("#color-chooser > li > a").click(function (e) {
      e.preventDefault();
      //Save color
      currColor = $(this).css("color");
      //Add color effect to button
      $('#add-new-event').css({"background-color": currColor, "border-color": currColor});
    });
    $("#add-new-event").click(function (e) {
      e.preventDefault();
      //Get value and make sure it is not null
      var val = $("#new-event").val();
      if (val.length == 0) {
        return;
      }

      //Create events
      var event = $("<div />");
      event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
      event.html(val);

      var scope = angular.element(document.getElementById("tabContent13")).scope();
      scope.$apply(function () {
        scope.create_event(val,currColor);

      });
      scope.$watch('ins_id', function(newid4, oldid4) {
        if(newid4>0){
          event.attr("data-id",newid4);
          console.log("new event "+val+" created with id "+newid4);
          $('#external-events').prepend(event);
          //Add draggable funtionality
          ini_events(event);
          //Remove event from text input
          $("#new-event").val("");
        }
      })
    });
  });
</script>
</html>


  