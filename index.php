<?php
    	session_start();
  	if(isset($_SESSION['email'])||isset($_SESSION['db_key'])){
	    header('Location: ./dashboard/');
  	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>HotApp</title>

	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">

	<script type="text/javascript" src="js/angular.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<style type="text/css">
		span{
			color: #f00;
		}
	</style>
</head>
<body>
	<div class="col-xs-1 col-sm-2 col-md-3">
	</div>
	
	<form name="login" class=" col-xs-10 col-sm-8 col-md-6 form-horizontal well" ng-app="login" ng-controller="logincntrlr">
		<h2>
			Client login form
		</h2>

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

		
		<a href="forgot.php">Forgotten your password?</a>

   		<div class="form-group">        
      		<div class="col-sm-offset-4 col-sm-10">
				<input type="reset" class="btn btn-default" ng-click="password='';email='';" value="Reset">
				<input type="button" class="btn btn-primary" ng-disabled="isDisabled ||login.email.$invalid || login.password.$invalid" ng-click="submit()" value="Submit">

				
			</div>
		</div>
	</form>
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