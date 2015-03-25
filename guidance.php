<?php 

	require_once("competitionUnitDBData.php");
	$data = new CompetitionUnitDbData();
?>
<style>


</style>
<div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">啟發問題編輯</h4>
	      </div>
	      <div class="modal-body">
	      	<div><span class="h2_title guidance_title">主題名稱</span><span class="guidance_subfieldinfo"></span></div>
	      	<div class="row formRow">
	      		<div class="col-lg-2">
	      			<span class="title_font" style="margin-top:18px">啟發問題:</span>
	      		</div>
	      		<div class="col-lg-10">
	      			<div class="guidance-wordCount">目前字數： <div id="wordCount" class="red-font">0</div> 字</div>
					<textarea id="guidContent" name="guidContent" class="input_style textarea_style" placeholder="輸入啟發問題內容"></textarea>
					<span id="guidContent_empty" class="error_tip">請輸入啟發問題，不得留空</span>
	      		</div>
	      	</div>
	      	
	      </div>

	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel_guidance">取消編輯</button>
	        <button type="button" class="btn btn-primary" id="updateGuidance_Submit">編輯完成</button>
	      </div>
</div>