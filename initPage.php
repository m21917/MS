<?php

function init_header($title){
	?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
	  <title><?php echo $title;?></title>
	  <meta charset="UTF-8">
	  <link href="css/bootstrap.min.css" rel="stylesheet">
	  <link href="css/bootstrap-theme.min.css" rel="stylesheet">
	  <link href="js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
	  <link href="css/bootstrap-tokenfield.min.css" rel="stylesheet">
	  <link href="css/ms_css.css" rel="stylesheet">
	  <script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
	  <script type="text/javascript" src="js/jquery-ui/jquery-ui.min.js"></script>
	  <script type="text/javascript" src="js/bootstrap.min.js"></script>
	  <script type="text/javascript" src="js/bootstrap-tokenfield.min.js"></script>
	  <script type="text/javascript" src="js/jquery.slimscroll.min.js"></script>
	  
	  
	  <script>
	  $(document).ready(function(){
	  	$('#login_info_box').hide();
	  	var times = 0;
	      var lightFont = setInterval(animateFont, 700);
	      //var start_to_say_hi = setInterval("console.log('h1')",1000); 
	      function animateFont() {
	      	//console.log("閃爍文字");
	        if(times == 0){
	          $("#registLink_btn>a").animate({color:"#FFF"}, 700);
	          times = 1;
	        } else {
	          $("#registLink_btn>a").animate({color:"#FFEE05"}, 700);
	          times = 0;
	        }
	      
	      }
	  });
	  </script>
	  </head>
		<body>
		<!--========================導航列頂===============================-->
		<div class="row">
		  <div class="col-lg-12 col-sm-12 col-xs-12" id="navbar" style="background-color:#428bca;z-index:1;">
		      <!-- <span style="float:left;"><img src="image/SchoolofTomorrow.png"></span> -->
		       
		      <ul id="nav-item" class="nav navbar-nav">
		      <li class="dropdown" id="index_btn">
		       <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">競賽說明 <span class="caret"></span></a>
		       <ul class="dropdown-menu" role="menu">
		       		<li><a href="index.php?page=1" value="section1">活動宗旨</a></li>
					<li><a href="index.php?page=2" value="section2">參加對象</a></li>
					<li><a href="index.php?page=3" value="section3">活動時程與辦法</a></li>
					<!-- <li><a href="index.php?page=4" value="section4">參加辦法</a></li> -->
					<li><a href="index.php?page=5" value="section5">作品說明及範例</a></li>
					<li><a href="index.php?page=6" value="section6">系統使用說明</a></li>
					<li><a href="index.php?page=7" value="section7">評選與獎勵</a></li>
					<li><a href="index.php?page=8" value="section8">注意事項</a></li>
					<li><a href="index.php?page=9" value="section9">聯絡資訊</a></li>
		       </ul>
		      </li>
		      <!-- <li id="index_btn"><a href="index.php">競賽說明</a></li> -->
		      <li id="unitList_btn"><a href="competitionUnitList.php">個人賽-教材編輯</a></li>
		      <li id="teamUnitList_btn"><a href="competitionTeamUnitList.php">團體賽-教材編輯</a></li>
		      <li id="registLink_btn"><a href="registrationCompetition.php">報名比賽</a></li>
		      </ul>
		      <ul class="nav navbar-nav" id="nav-bottom">
		      <?php
		      	if(!isset($_SESSION)){
			      	session_start();
			    }
		      	if(isset($_SESSION['name']) && $_SESSION['name']!=''){
		      		echo '<li><a class="pull-right user-info" data-toggle="modal" data-target="#myInformation">使用者:'.$_SESSION['name'].'</a></li>';
		      	}
			    
			    if(isset($_SESSION['name']) && $_SESSION['name']!=''){
		      		echo '<li><a href="#" onclick="logout()">登出系統</a></li>';
		      	} else {
		      		echo '<li><a class="ac_for_login_iframe cboxElement" href="/sha/school_member/mod/m_user_register_verify/view/login/">登入系統</a></li>';
		      	}

		      	

		      ?>
		      </ul>
		</div>
		<?php //require_once($_SERVER['DOCUMENT_ROOT']."/sha/school_member/mod/m_user_register_verify/view/api/login_api.php"); ?>
		<!--========================導航列底===============================-->
			<?php
}


function init_footer(){
	?>
	<!-- 載入個人資訊 -->
	<div class="modal fade" id="myInformation" tabindex="-1" role="dialog" aria-labelledby="myInformation" aria-hidden="true">
	  <div class="modal-dialog" style="width:700px">
	    <?php require_once("information.php");?>
	  </div>
	</div>
	<!-- 載入個人資訊 -->

	<!--========================content底===============================-->
	<div class="navbar-fixed-bottom" style="background-color:#428bca;z-index:1;" id="footer">
	  <p>國立中央大學 版權所有 © 2008-2015 National Central University All Rights Reserved.</p>
	</div>

	</body>
	</html>
	<?php
}
?>