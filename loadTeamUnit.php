<?php 

	require_once("competitionUnitDBData.php");
	$data = new CompetitionUnitDbData();
?>
<style>


</style>
<div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">由教材管理系統匯入主題:我的教材清單</h4>
	      </div>
	      <div class="modal-body">
	      	<div class="list-group">
		      	<span class="control-label" style="float:left;margin-top:5px">所屬團隊:</span>
		      	<select  class="form-control" style="width:150px;float:left;margin-bottom:10px;margin-left:10px;margin-right:10px" id="joinUnitTeamName" name="joinUnitTeamName">
			                <option value="0">請選擇...</option>
			                <?php 
											$teamName = $data->getTeamListByUid($_SESSION['uid']);
											foreach ($teamName as $value) {
												//if (isset($thisUnitInfo) && $thisUnitInfo->field1 == $key) { $checkMainField = "selected"; } else { $checkMainField = ""; }
												echo '<option value="'.$value.'">'.$value.'</option>';
											}
							?>
		        </select>
		        <span id="joinUnitTeamName_ErrorMsg" class="error" style="display:none">必須選擇本主題所屬團隊。</span>
		        <div style="clear:both"></div>
	      	</div>
	        
	      	<div class="red-font" style="text-align:center">注意!教材管理系統之主題狀態要設為開啟，才能看到自己的主題喔。</div>
	      	
	      	<div class="list-group" id="insertUnitList">
	      		
	      		<?php
	      			// $dataArray = $data->LoadUnitListFromMSSC();
	      			// foreach ($dataArray as $object) {
	      			// 	$result = $data->checkExistInTeamCompetitionList($object->id,);
	      			// 	if($result && $result->enable == 1) {
	      			// 		echo '<a href="#" class="list-group-item list-group-item-success" value="'.$object->id.'" id="loadUnit'.$object->id.'">'.$object->name.'<span class="gray-font" style="margin-left:10px">'.$object->field1.'>>'.$object->field2.'</span><span class="glyphicon glyphicon-ok pull-right" aria-hidden="true"></span></a>';
	      			// 	} else {
	      			// 		echo '<a href="#" class="list-group-item insertUnit" value="'.$object->id.'" id="loadUnit'.$object->id.'">'.$object->name.'<span class="gray-font" style="margin-left:10px">'.$object->field1.'>>'.$object->field2.'</span><span class="glyphicon glyphicon-ok pull-right" aria-hidden="true"></span></a>';
	      			// 	}
	      				
	      			// }
	      		?>
			  <!-- <a href="#" class="list-group-item list-group-item-success">Dapibus ac facilisis in<span class="glyphicon glyphicon-ok pull-right" aria-hidden="true"></span></a>
			  <a href="#" class="list-group-item">Morbi leo risus</a>
			  <a href="#" class="list-group-item">Porta ac consectetur ac</a>
			  <a href="#" class="list-group-item">Vestibulum at eros</a> -->
			</div>
	      </div>

	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel_loadUnit">關閉視窗</button>
	      </div>
</div>