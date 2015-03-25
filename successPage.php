<?php
	require_once("initPage.php");
	require_once("competitionUnitDBData.php");
	$data = new CompetitionUnitDbData;
	init_header("報名成功!!");
?>
<div class="row" style="margin-top:20px;">

	<div class="jumbotron warningBlock">
		<div class="row">
			<div class="col-lg-3 col-sm-5 col-md-5">
				<img src="image/registSuccess-01.png"/>
			</div>
			<div class="col-lg-9 col-sm-7 col-md-7">
				<h1 style="color:rgb(130,191,42)">報名成功!</h1>
				<p>請點擊下方按鈕開始編輯教材。</p>
				<?php
					if(isset($_GET['type']) && $_GET['type'] == 0){
				?>
				<a class="btn btn-primary" href="competitionUnitList.php">前往編輯教材</a>
				<?php
					}
					else if(isset($_GET['type']) && $_GET['type'] == 1){
				?>
				<a class="btn btn-primary" href="competitionTeamUnitList.php">前往編輯教材</a>
				<?php
					}
				?>
			</div>
		</div>
		  
	</div>
</div>
<?php
	init_footer();

?>