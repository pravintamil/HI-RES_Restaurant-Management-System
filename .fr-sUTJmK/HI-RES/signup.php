<?php
session_start();
  if(!isset($_SESSION['email'])||!isset($_SESSION['db_key'])){
    header('Location: ./index.php');
  }
  ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="js/angular.min.js"></script>
	<script type="text/javascript" src="js/angular-messages.min.js"></script>
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
	<form name="signup" class="col-xs-10 col-sm-8 col-md-6 form-horizontal well" ng-app="signup" ng-controller="signupcntrlr">
		<h2>Client Signup form</h2>

		<div class="form-group">	
			<label class="col-sm-4">Restaurant Name</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="restaurant_name" ng-model="restaurant_name" ng-minlength="2" placeholder="Enter your restaurant name" required>
				
				<span ng-show="signup.restaurant_name.$invalid&&signup.restaurant_name.$dirty">
	    			<span ng-show="signup.restaurant_name.$error.required">Required</span>
    				<span ng-show="signup.restaurant_name.$error.minlength">Enter atleast 2 characters</span>
				</span>	
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4">Number of branches</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="branch" ng-model="branch" ng-pattern="/^[1-9][0-9]{0,1}$/" maxlength="2" placeholder="Enter the No. of Branches" required >
				<span ng-show="signup.branch.$dirty&& signup.branch.$invalid">
					<span ng-show="signup.branch.$error.required">Required</span>
					<span ng-show="signup.branch.$error.pattern">Enter the valid number</span>
				</span>
			</div>
		</div>
		<div class="form-group">	
			<label class="col-sm-4">Email</label>
			<div class="col-sm-8">
				<input type="email" class="form-control" name="email" ng-model="email" ng-pattern="/^[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/" placeholder="Enter your email address" required>
				
				<span ng-show="signup.email.$dirty && signup.email.$invalid">
	    			<span ng-show="signup.email.$error.required ">Required</span>
    				<span ng-show="signup.email.$error.pattern">Enter the valid Email</span>
				</span>	
			</div>
		</div>
		<div class="form-group">	
			<label class="col-sm-4">Password</label>
			<div class="col-sm-8">
				<input type="password" class="form-control" name="password" ng-model="password" ng-minlength="8" ng-maxlength="20" ng-pattern="/(?=.*[0-9])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])/" placeholder="Enter your password here" required />
				<span ng-show="signup.password.$error.required && signup.password.$dirty">required</span>
    			<span ng-show="!signup.password.$error.required && (signup.password.$error.minlength || signup.password.$error.maxlength) && signup.password.$dirty">Passwords must be between 8 and 20 characters.</span>
    			<span ng-show="!signup.password.$error.required && signup.password.$error.pattern && signup.password.$dirty">Must contain one uppercase letter, one number and one symbol.</span>
    		</div>
    	</div>
    
		<div class="form-group">	
			<label class="col-sm-4">Retype password</label>
			<div class="col-sm-8">
				<input type="password" class="form-control" name="repassword" ng-model="r_password" ng-minlength="8" ng-maxlength="20" ng-pattern="/(?=.*[0-9])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])/" placeholder="Retype your password" required>
				<span ng-show="password !== r_password">Password mismatch</span>
			</div>
		</div>
		<div class="form-group">
  			<label class="col-sm-4">Landline number</label>  
 			<div class="col-sm-8">
 				<input name="land" ng-pattern="/^([0]{0,1}[1-9]|[7-9])[0-9]{9}$/" ng-model="land" placeholder="Enter your Landline number" class="form-control input-md" type="text" maxlength="11" ng-minlength="10" required >
   				<span ng-show="signup.land.$error.minlength">Type 10 Numbers</span>
   				<span ng-show="signup.land.$error.pattern&&!form.land.$error.minlength">Invalid Landline number </span>
			</div>
		</div>
		<div class="form-group">
  			<label class="col-sm-4">Mobile number</label>  
 			<div class="col-sm-8">
 				<input name="mobile" ng-model="mobile" placeholder="Enter your mobile number" class="form-control input-md" type="text" ng-pattern="/^[7-9][0-9]{9}$/" maxlength="10" ng-minlength="10" ng-maxlength="10" required>
 				<span ng-show="signup.mobile.$error.required && signup.mobile.$dirty" >required</span>
				<span ng-show="!signup.mobile.$error.required && (signup.mobile.$error.minlength || signup.phone.$error.maxlength) ">phone number should be 10 digit number</span>
				<span ng-show="!signup.mobile.$error.required && !(signup.mobile.$error.minlength || signup.phone.$error.maxlength) && signup.mobile.$error.pattern" >phone number should not be start with less than 7</span>
 				
			</div>
		</div>
		<div class="form-group">	
			<label class="col-sm-4">Address</label>
			<div class="col-sm-8">
				<textarea name="address" class="form-control" ng-model="address" ng-pattern="/^[0-9a-zA-Z-,.\/\s]{10,}$/" required></textarea>
				<span ng-show="signup.address.$error.required && signup.address.$dirty" >required</span>
				<span ng-show="!signup.address.$error.required && signup.address.$error.pattern">Enter valid address</span>
			</div>
		</div>

		<div class="form-group">	
			<label class="col-sm-4">City</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="city" ng-model="city" ng-pattern="/^[a-zA-Z]{2,40}(\s{0,1}[a-zA-Z]{2,40})*$/" placeholder="Enter the city name" required>
				<span ng-show="signup.city.$error.required && signup.city.$dirty" >required</span>
				<span ng-show="!signup.city.$error.required && signup.city.$error.pattern">Character and space only allowed</span>
			</div>
		</div>

		<div class="form-group">	
			<label class="col-sm-4">State</label>
			<div class="col-sm-8">
				<input type="text" class="form-control" name="state" ng-model="state" ng-pattern="/^[a-zA-Z]{2,40}(\s{0,1}[a-zA-Z]{2,40})*$/" placeholder="Enter the state name"required>
				<span ng-show="signup.state.$error.required && signup.state.$dirty" >required</span>
				<span ng-show="!signup.state.$error.required && signup.state.$error.pattern">Character and space only allowed</span>
			</div>
		</div>
		<div class="form-group">
			<label class="col-md-4" >Pincode</label>
			<div class="col-md-8" >
				<input type="text" class="form-control" name="pincode" ng-model="pincode" ng-pattern="/^[1-9][0-9]{5}$/"
				placeholder="enter your pincode(postalcode)" maxlength="6" required>
				<span ng-show="signup.pincode.$error && signup.pincode.$dirty">
					<span ng-show="signup.pincode.$error.required">Required</span>
					<span ng-show="signup.pincode.$error.pattern">Enter a valid pincode</span>					
				</span>
			</div>
		</div>

		<div class="form-group">        
      		<div class="col-sm-offset-4 col-sm-10">
				<input type="reset" class="btn btn-default" ng-click="restaurant_name='';email='';" value="Reset">
				<input type="button" class="btn btn-primary" ng-disabled="isDisabled ||signup.$invalid" ng-click="submit()" value="Submit">	
			</div>
		</div>
	</form>
	</div>
	
</body>

	<script type="text/javascript">
	var app=angular.module("signup",[]);
	
	app.controller("signupcntrlr",function ($scope,$http) {
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
					mode:"signup",
					restaurant_name:$scope.restaurant_name,
					branch:$scope.branch,
					email:$scope.email,
					password:$scope.password,
					r_password:$scope.r_password,
					landline:$scope.land,
					mobile:$scope.mobile,
					address:$scope.address,
					city:$scope.city,
					state:$scope.state,
					pincode:$scope.pincode
				},
				headers:{'Content-Type':'application/x-www-form-urlencoded'}

			});
			http.success(function(data){
				if ( !data.success) {
          			alert(data.err);
          			$scope.isDisabled=false;
        		} 
        		else {
					alert(data.message);
					window.location=window.location.protocol+"//" + document.location.hostname + loc + "/";
      			}
			}).error(function(error){
				alert("error in http");
          			$scope.isDisabled=false;
			});
		}
	})
	</script>
</html>/