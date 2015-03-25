<?php
	require_once("initPage.php");
	require_once("competitionUnitDBData.php");
	$dbObject = new CompetitionUnitDbData();
	
	init_header("團體賽-教材編輯");
?>
	<link rel="stylesheet" type="text/css" href="js/DataTables-1.10.4/media/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="js/DataTables-1.10.4/extensions/TableTools/css/dataTables.tableTools.css">
	<script type="text/javascript" language="javascript" src="js/DataTables-1.10.4/media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" language="javascript" src="js/DataTables-1.10.4/extensions/TableTools/js/dataTables.tableTools.js"></script>
	<script type="text/javascript" src="js/competitionTeamUnitList_js.js"></script>
<?php


	if(!isset($_SESSION['uid'])){
		
		//Header("Location: index.php"); 
?>
<div class="row" style="margin-top:20px;">

	<div class="jumbotron warningBlock">
		<div class="row">
			<div class="col-lg-3 col-sm-5 col-md-5">
				<img src="image/noEnter2.png"/>
			</div>
			<div class="col-lg-9 col-sm-7 col-md-7">
				<h1>驗證身分失敗!</h1>
				<p>尚未登入的訪客不得使用此功能，請先登入系統才可使用此功能。</p>
				<a class="ac_for_login_iframe cboxElement btn btn-primary" href="/sha/school_member/mod/m_user_register_verify/view/login">登入系統</a>
			</div>
		</div>
		  
	</div>
</div>
	
<?php
	} else {	//已登入系統...

	$account = $dbObject->getUserAccount($_SESSION['uid']);
	if($dbObject->groupRegistCheck($account)){	//如果有報名團體賽
?>
	<div class="row" style="margin-top:20px;">
		<div class="col-md-10 col-md-offset-1">
		<h2 style="float:left;margin-bottom:10px">我的主題列表</h2>
		<div style="margin-top:17px;padding-left:20px;float:left">
		<button class="btn btn-success" data-toggle="modal" data-target="#myModal" id="callAddForm">新增主題</button>
		<button class="btn btn-success" data-toggle="modal" data-target="#loadUnitModal" id="callLoadUnit">由教材管理系統匯入主題</button>
		</div>
		<div style="margin-top:17px;float:right">
			<a class="btn btn-primary" href="teaching.php" target="_blank">系統使用說明</a>
		</div>
		<hr style="clear:both;">
			<table id="unitListTable" class="" cellspacing="0" width="100%">
				        <thead>
				            <tr>
				            	<th>所屬團隊</th>
				                <th>主題名稱</th>
				                <th>教材領域</th>
				                <th>適用年級</th>
				                <th>範例文章</th>
				                <th>啟發問題</th>
				                <!-- <th>主題狀態</th> -->
				                <th>建立時間</th>
				                <th>關鍵字</th>
				                <th>投稿狀態</th>
				            </tr>
				        </thead>
				</table>
		</div>
	</div>
	<!-- 新增/更新主題清單 -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog" style="width:1000px">
	    <?php require_once("teamUnit.php");?>
	  </div>
	</div>
	<!-- 新增/更新主題清單 -->

	<!-- 編輯範文清單 -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModal" aria-hidden="true">
	  <div class="modal-dialog" style="width:1000px">
	    <?php require_once("example.php");?>
	  </div>
	</div>
	<!-- 編輯範文清單 -->

	<!-- 編輯啟發問題清單 -->
	<div class="modal fade" id="guidanceModal" tabindex="-1" role="dialog" aria-labelledby="guidanceModal" aria-hidden="true">
	  <div class="modal-dialog" style="width:1000px">
	    <?php require_once("guidance.php");?>
	  </div>
	</div>
	<!-- 編輯啟發問題清單 -->

	<!-- 由教材庫匯入主題 -->
	<div class="modal fade" id="loadUnitModal" tabindex="-1" role="dialog" aria-labelledby="loadUnitModal" aria-hidden="true">
	  <div class="modal-dialog" style="width:1000px">
	    <?php require_once("loadTeamUnit.php");?>
	  </div>
	</div>
	<!-- 由教材庫匯入主題 -->

	<!-- 變更主題狀態 -->
	<div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" aria-labelledby="changeStatusModal" aria-hidden="true">
	  <div class="modal-dialog" style="width:400px">
	  	<div class="modal-content">
		  	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="myModalLabel">變更主題狀態</h4>
		    </div>
		    
		     <div class="modal-body">
		      	<p style="text-align: center">您決定要變更主題狀態?(目前為<span id="nowStatus"></span>)</p>
		      	<p><span class="green-font">已開啟:</span>已完成主題的編輯，開啟後才能投稿主題。</p>
		      	<p><span class="gray-font">已關閉:</span>尚未完成主題的編輯。</p>
		     </div>

		    <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
		        <button type="button" class="btn btn-primary" id="changeStatus_Submit">變更</button>
		    </div>
		 </div>
	  </div>
	</div>
	<!-- 變更主題狀態 -->

	<!-- 系統訊息 -->
	<div class="modal fade systemTip" tabindex="-1" role="dialog" aria-labelledby="systemTip" aria-hidden="true">
	  <div class="modal-dialog modal-sm">
	    <div class="modal-content">
	      <div class="modal-body">
	      	<div style="text-align:center;font-size:24px;margin:20px 0;" id="systemTip_content">
	        	
	        </div>
	        <div style="text-align:center">
	        	<button type="button" class="btn btn-primary" data-dismiss="modal">確定</button>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- 系統訊息 -->

	<!-- 刪除教材 -->
	<div class="modal fade deleteAlert" tabindex="-1" role="dialog" aria-labelledby="deleteAlert" aria-hidden="true">
	  <div class="modal-dialog modal-sm">
	    <div class="modal-content" style="width:420px">
	      <div class="modal-body">
	      	<div style="text-align:center;font-size:24px;margin:20px 0;" id="deleteAlert_content">

	        </div>
	        <div style="text-align:center">
	        	<input type="hidden" name="deleteID" id="deleteID" value="">
	        	<input type="hidden" name="deleteType" id="deleteType" value="0">
	        	<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
	        	<button type="button" class="btn btn-primary" id="deleteUnitSubmit">確定</button>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- 系統訊息 -->
<?php
		}	//如果有報名團體賽...
		else {		//	沒有報名團體賽
			?>
			<div class="row" style="margin-top:20px;">

				<div class="jumbotron warningBlock">
					<div class="row">
						<div class="col-lg-3 col-sm-5 col-md-5">
							<img src="image/noEnter2.png"/>
						</div>
						<div class="col-lg-9 col-sm-7 col-md-7">
							<h1>您尚未報名團體賽!</h1>
							<p>您還沒有完成團體賽報名的動作，請先完成報名程序。</p>
							<a class="btn btn-primary" href="registrationCompetition.php">前往報名</a>
						</div>
					</div>
					  
				</div>
			</div>
			<?php
		}
	}	//已登入系統...
	init_footer();

?>