<?php 

	require_once("competitionUnitDBData.php");
	$data = new CompetitionUnitDbData();
?>
<style>


</style>
<div class="modal-content">
<?php
	if(isset($_SESSION['uid']) && $_SESSION['uid']!=''){
		$exist = $data->RegistCheck($data->getUserAccount($_SESSION['uid']));
		if($exist){
			$info = $data->getInformation($_SESSION['uid']);
			?>
				  <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModalLabel">個人基本資料</h4>
			      </div>
			      <div class="modal-body" style="font-size:20px">
			        	<div class="row">
			        		<div class="col-md-3" style="text-align: right">姓名:</div>
		              		<div class="col-md-6"><?php echo $info->name;?></div>
			        	</div>
			        	<div class="row">
			        		<div class="col-md-3" style="text-align: right">性別:</div>
		              		<div class="col-md-6"><?php echo $info->sex;?></div>
			        	</div>
			        	<div class="row">
			        		<div class="col-md-3" style="text-align: right">電子信箱:</div>
		              		<div class="col-md-6"><?php echo $info->email;?></div>
			        	</div>
			        	<div class="row">
			        		<div class="col-md-3" style="text-align: right">行動電話:</div>
		              		<div class="col-md-6"><?php echo $info->tel;?></div>
			        	</div>
			        	<div class="row">
			        		<div class="col-md-3" style="text-align: right">身分別:</div>
		              		<div class="col-md-6"><?php echo $info->kind;?></div>
			        	</div>
			        	<div class="row">
			        		<div class="col-md-3" style="text-align: right">任職單位:</div>
		              		<div class="col-md-6"><?php echo $info->workPlace;?></div>
			        	</div>
			      </div>

			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>     
			      </div>
			<?php
		} else {
			?>
			<div class="modal-body" style="font-size:20px">
			<div style="text-align: center;color:red">您還沒報名參加比賽!</div>
			</div>
			<?php
		}
	}
	
?>
	      
</div>