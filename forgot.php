<?php
	session_start();
  	if(isset($_SESSION['email'])||isset($_SESSION['db_key'])){
	    header('Location: ./dashboard/');
  	}

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/angular.min.js"></script>
	<style type="text/css">
		h2{
			text-align: center;
		}
		form{
			margin-top: 10px;
		}
		span{
			color: #f00;
		}
	</style>
</head>
<body class="container">
	<div class="col-xs-1 col-sm-2 col-md-3">
	</div>
	<form name="forgot" class="col-xs-10 col-sm-8 col-md-6 form-horizontal well" ng-app="forgot" ng-controller="forgotcntrlr">
		<?php
		if ($_POST["email"]) {
		?>
		<h2>Password reset</h2>
		<div class="form-group">	
			<label class="col-sm-4">Password</label>
			<div class="col-sm-8">
				<input type="password" class="form-control" name="password" ng-model="password" ng-minlength="8" ng-maxlength="20" ng-pattern="/(?=.*[0-9])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])/" placeholder="Enter you password here" required />
				<span ng-show="forgot.password.$error.required && forgot.password.$dirty">required</span>
    			<span ng-show="!forgot.password.$error.required && (forgot.password.$error.minlength || forgot.password.$error.maxlength) && forgot.password.$dirty">Passwords must be between 8 and 20 characters.</span>
    			<span ng-show="!forgot.password.$error.required && !forgot.password.$error.minlength && !forgot.password.$error.maxlength && forgot.password.$error.pattern && forgot.password.$dirty">Must contain one uppercase letter, one number and one symbol.</span>
    		</div>
    	</div>
    
		<div class="form-group">	
			<label class="col-sm-4">Confirm password</label>
			<div class="col-sm-8">
				<input type="password" class="form-control" name="rpassword" ng-model="rpassword" ng-minlength="8" ng-maxlength="20" ng-pattern="/(?=.*[0-9])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])/" placeholder="Enter you password here" required />
				<span ng-show="password !== rpassword">Password mismatch</span>
			</div>
		</div>

		<div class="form-group">        
      		<div class="col-sm-offset-4 col-sm-10">
				<input type="button" name="submitbtn"class="btn btn-default" ng-disabled="isDisabled ||forgot.password.$invalid||forgot.rpassword.$invalid" ng-click="submit()" value="Submit">
			</div>
		</div>
		<?php
		}
		else
		{
		?>

		<h2>Forgot Password</h2>

		<div class="form-group">	
			<label class="col-sm-4">Email id</label>
			<div class="col-sm-8">
				<input type="email" class="form-control" name="email" ng-model="email" ng-pattern="/^[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/" required>
				
				<span ng-show="forgot.email.$dirty && forgot.email.$invalid">
	    			<span ng-show="forgot.email.$error.required">Required</span>
    				<span ng-show="forgot.email.$invalid">Invalid Email</span>
				</span>	
			</div>
		</div>

		<div class="form-group">        
      		<div class="col-sm-offset-4 col-sm-10">
				<input type="button" name="submitbtn"class="btn btn-default" ng-disabled="isDisabled ||forgot.email.$invalid" ng-click="submit()" value="Submit">
			</div>
		</div>
		<?php
		}
		?>
	</form>
	</div>
	
</body>

	<script type="text/javascript">
	var app=angular.module("forgot",[]);
	app.controller("forgotcntrlr",function ($scope,$http) {
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
					<?php
					if($_POST['email']){
						echo 'mode:"password_reset",
						email:"'.$_POST['email'].'",
						password:$scope.password,
						rpassword:$scope.rpassword,
						key:"'.$_POST['key'].'"
						';
					}
					else{
						echo 'mode:"forgot",
					email:$scope.email';
					}

					?>
					
				},
				headers:{'Content-Type':'application/x-www-form-urlencoded'}

			});
			http.success(function(data){
				if ( !data.success) {
          			alert(data.error);
          			$scope.isDisabled=false;
        		} 
        		else {
					alert(data.message);
					window.location=window.location.protocol+"//" + document.location.hostname + loc + "/";
      			}
			}).error(function(error){
				alert("error in http");
			});
		}
	})
	</script>
</html>