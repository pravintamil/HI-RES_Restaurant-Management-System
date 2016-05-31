<?php
session_start();
require './config.php';
require './facebook.php';
// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => $config['App_ID'],
  'secret' => $config['App_Secret'],
  'cookie' => true
));

if(isset($_GET['fbTrue']))
{
    if(!isset($_SESSION['token'])){
        
        $token_url = "https://graph.facebook.com/oauth/access_token?"
            . "client_id=".$config['App_ID']."&redirect_uri=" . urlencode($config['callback_url'])
            . "&client_secret=".$config['App_Secret']."&code=" . $_GET['code'];
        
        $response = file_get_contents($token_url);
        // print_r($response);
        $params = null;
        parse_str($response, $params);

        $graph_url = "https://graph.facebook.com/knonexcbe/feed?access_token=".$params['access_token'];
        
        $_SESSION['token'] = $params['access_token'];

    }
    else
    {
        $graph_url = "https://graph.facebook.com/knonexcbe/feed?access_token=".$_SESSION['token'];
    }
        // echo "$graph_url";
    // print_r($response);
    $user = json_decode(file_get_contents($graph_url));

    $content .='<body class="hold-transition skin-blue sidebar-mini">
    <section class="content-header">
      <h1>
        Timeline
        <small>Knonex</small>
      </h1>
    </section>
    			<section class="content" style="background-color: #ecf0f5;">
    				<div class="row">
        				<div class="col-md-12">
          					<ul class="timeline">';
    $date="";
    $len=sizeof($user->data);
    $colors=array("bg-red","bg-yellow","bg-aqua","bg-blue","bg-light-blue","bg-green","bg-navy","bg-teal","bg-olive","bg-lime","bg-orange","bg-fuchsia","bg-purple","bg-maroon","bg-black");
	$color=array_rand($colors,($len/2));
	$i=0;
    foreach($user->data as $data)
    {
     	$msg=$data->message;
     	$c_time=$data->created_time;
     	$startDate=date("d M Y", strtotime($c_time));
     	if($startDate!=$date){
     		$content.='<li class="time-label">
                		  <span class="'.$colors[$color[$i]].'">'.$startDate.'</span>
			            </li>';

            $i++;
    	}
     	$date=date("d M Y", strtotime($c_time));

     	$endDate=date ( 'Y-m-d H:i:s',strtotime ( date("Y-m-d H:i:s") ) );
        date_default_timezone_set('UTC');
        $startDate=date("Y-m-d H:i:s", strtotime($c_time));
        $a = new DateTime($startDate);
        $b = new DateTime($endDate);
        if ($a->diff($b)->format('%y')!=0) {
	        $ago=$a->diff($b)->format('%y')." year";
	    }
	    elseif ($a->diff($b)->format('%m')!=0) {
	        $ago=$a->diff($b)->format('%m month');
	    }
	    elseif ($a->diff($b)->format('%d')!=0) {
	        $ago=$a->diff($b)->format('%d days');
	    }
	    elseif ($a->diff($b)->format('%H')!=0) {
	        $ago=$a->diff($b)->format('%H hours');
	    }
	    elseif ($a->diff($b)->format('%i')!=0) {
	        $ago=$a->diff($b)->format('%i minutes');
	    }
     	$content.='<li>
              <i class="fa fa-envelope bg-blue"></i>

              <div class="timeline-item">
                <span class="time"><i class="fa fa-clock-o"></i> '.$ago.' ago</span>

                <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                <div class="timeline-body">'.$msg.'
                </div>
              </div>
            </li>';
    }
    $content .= '</ul>
        		</div>
      		</div>
		</section>

		</body>';
}
else
{
    $content = '<a href="https://www.facebook.com/dialog/oauth?client_id='.$config['App_ID'].'&redirect_uri='.$config['callback_url'].'&scope=email,user_videos,public_profile,user_posts,user_actions.news"><img src="../img/login-button.png" alt="Sign in with Facebook"/></a>';
    
}
echo '<!DOCTYPE html>
		<html>
			<head>
  				<title>AdminLTE 2 | Timeline</title>
  				<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  				<link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  				<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  				<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
			</head>
			';
echo $content;
echo "
</html>";
?>