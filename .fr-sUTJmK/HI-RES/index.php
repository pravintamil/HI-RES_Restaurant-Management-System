<?php
    	session_start();
  	if(isset($_SESSION['email'])||isset($_SESSION['db_key'])){
	    header('Location: ./dashboard/');
  	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>HI-RES</title>

	<link rel="stylesheet" type="text/css" href="./css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="./css/AdminLTE.min.css">
	<script type="text/javascript" src="./js/angular.min.js"></script>
	<script type="text/javascript" src="./js/jquery.min.js"></script>
	<script type="text/javascript" src="./js/bootstrap.min.js"></script>
	<style type="text/css">
		span{
			color: #f00;
		}
		body{
			width:100%;
			height:100%;
			background-color:#ff3;
		}
		.header{
			min-height:70px;
			background-color:rgb(242, 232, 32);
		}
		.content{
			width:100%;
		}
		img{
			height:auto;
		}
	</style>
</head>
<body>
	<div class="header">
		<div class="row">
			<div class="col-xs-10 col-lg-10">
				<h2> HI-RES Restaurant Management System</h2>
			</div>
			<div class="col-xs-2 col-lg-2" style="padding-top:5px;">
				<input type="button" class="btn btn-flat bg-navy	" value="Login" data-toggle="modal" data-target="#add_cat" style="float:right" >
			</div>
		</div>
	</div>
	<div class="content">
		<img src="./img/bg.png" class="col-xs-12 col-sm-12 col-md-12 col-lg-11">
	</div>
	
	<div class="modal" id="add_cat" >
        <div class="modal-dialog">
            <div class="modal-content"  ng-app="login" ng-controller="logincntrlr">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">HI-RES User Login</h4>
				</div>
				<div class="modal-body">
					<form name="login" >
						<div class="form-group">	
							<label class="col-sm-4">Email id</label>
							<div class="col-sm-8">
								<input type="email" class="form-control" name="email" ng-change="process()" ng-model="email" ng-pattern="/^[_a-zA-Z0-9]+(\.[_a-zA-Z0-9]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})$/" required>				
								<span ng-show="login.email.$dirty ">
									<span ng-show="login.email.$error.required">Required</span>
									<span ng-show="!login.email.$error.required&&login.email.$invalid">Invalid Email</span>
								</span>	
							</div>
						</div>

						<div class="form-group">	
							<label class="col-sm-4">Password</label>
							<div class="col-sm-8">
								<input type="password" class="form-control" name="password" ng-model="password" ng-minlength="8" ng-maxlength="20" ng-pattern="/(?=.*[0-9])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])/" required />
								<div style="clear:both"></div>
								<span ng-show="login.password.$error.required && login.password.$dirty">required</span>
								<span ng-show="!login.password.$error.required && (login.password.$error.minlength || login.password.$error.maxlength) && login.password.$dirty">Passwords must be between 8 and 20 characters.</span>
								<span ng-show="!login.password.$error.required && !login.password.$error.minlength && !login.password.$error.maxlength && login.password.$error.pattern && login.password.$dirty">Must contain one uppercase letter, one number and one symbol.</span>
							</div>
						</div>

						
						<a data-dismiss="modal">Forgotten your password?</a>

					</form>
				</div>
				<div class="modal-footer">
					<input type="reset" class="btn btn-flat bg-purple pull-left" ng-click="password='';email='';" value="Reset">
					<input type="button" class="btn btn-flat bg-navy" ng-disabled="isDisabled ||login.email.$invalid || login.password.$invalid" ng-click="submit()" value="Login">
				</div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
	<script type="text/javascript">
	var app=angular.module("login",[]);
	app.controller("logincntrlr",function ($scope,$http) {
		$scope.isDisabled=false;
		$scope.submit=function(){
			$scope.isDisabled=true;
			var loc = document.location.pathname;
			pos=loc.lastIndexOf('/');
			loc=loc.substr(0, pos);
			var http=$http({
				method:"post",
				url:window.location.protocol+"//" + document.location.hostname + loc + "/control.php",
				data:{
					mode:"login",
					email:$scope.email,
					password:$scope.password
				},
				headers:{'Content-Type':'application/x-www-form-urlencoded'}

			});
			http.success(function(data){
				console.log(data)
				if ( !data.success) {
          			alert(data.err);
          			$scope.isDisabled=false;
        		} 
        		else {
					// alert(data.message);
					window.location=window.location.protocol+"//" + document.location.hostname + loc + "/dashboard/";
      			}
			}).error(function(error){
				alert("error in http");
			});
		}
	})
	</script>
</body>
</html>