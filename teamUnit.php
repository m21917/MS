<?php 

	require_once("competitionUnitDBData.php");
	$data = new CompetitionUnitDbData();
?>
<div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">新增主題</h4>
	      </div>
	      <div class="modal-body">
	        <form class="form-horizontal">
	        	<div class="form-group">
	              <label for="teamName" class="col-sm-2 control-label">所屬團隊</label>
	              <div class="col-sm-10">
	                <select  class="form-control" style="width:150px" id="teamName" name="teamName">
		                <option value="0">請選擇...</option>
		                <?php 
										$teamName = $data->getTeamListByUid($_SESSION['uid']);
										foreach ($teamName as $value) {
											//if (isset($thisUnitInfo) && $thisUnitInfo->field1 == $key) { $checkMainField = "selected"; } else { $checkMainField = ""; }
											echo '<option value="'.$value.'">'.$value.'</option>';
										}
						?>
	              	</select>
	                <span id="teamName_ErrorMsg" class="error" style="display:none">必須選擇本主題所屬團隊。</span>
	              </div>
	            </div>
	            <div class="form-group">
	              <label for="unitName" class="col-sm-2 control-label">主題名稱</label>
	              <div class="col-sm-10">
	              	<input type="hidden" id="unitId" name="unitId" value="">
	                <input type="text" class="form-control" id="unitName" name="unitName" placeholder="輸入主題名稱" style="width:250px">
	                <span id="unitName_empty" class="error" style="display:none">主題名稱不得為空。</span>
	              </div>
	            </div>
	            <div class="form-group">
	            <label for="mainField" class="col-sm-2 control-label">主題領域</label>
	            <div class="col-sm-2">
	              <select  class="form-control" style="width:150px" id="mainField" name="mainField">
	                <option value="0">請選擇...</option>
	                <?php 
									$field = $data->LoadMainField();
									foreach ($field as $key => $value) {
										//if (isset($thisUnitInfo) && $thisUnitInfo->field1 == $key) { $checkMainField = "selected"; } else { $checkMainField = ""; }
										echo '<option value="'.$key.'">'.$value.'</option>';
									}
					?>
	              </select>
	              <span id="mainField_ErrorMsg" class="error" style="display:none">請選擇主領域。</span>
	            </div>
	            <label for="subField" class="col-sm-1 control-label subField_label">子領域</label>
	            <div class="col-sm-6">
	              <select  class="form-control subField_select" style="width:150px" id="subField" name="subField">
	                <option value="0">請選擇...</option>
	                <option value="1">語文學協</option>
	                <option value="2">文學作品</option>
	                <option value="3">故事</option>
	              </select>
	              <span id="subField_ErrorMsg" class="error" style="display:none">請選擇子領域。</span>
	            </div>
	            </div>

	            <div class="form-group">
	              <label class="col-sm-2 control-label">適用年級</label>
	              <div class="col-sm-10">
	                <label class="radio-inline">
					  <input type="radio" name="grade" id="grade1" value="1" class="css-checkbox2"> 
					  <span for"grade1" class="css-label2"></span>
					  低年級<span class="font12-g">(1~2年級)</span>
					</label>
					<label class="radio-inline">
					  <input type="radio" name="grade" id="grade2" value="2" class="css-checkbox2"> 
					  <span for"grade2" class="css-label2"></span>
					  中年級<span class="font12-g">(3~4)年級</span>
					</label>
					<label class="radio-inline">
					  <input type="radio" name="grade" id="grade3" value="3" class="css-checkbox2"> 
					  <span for"grade3" class="css-label2"></span>
					  高年級<span class="font12-g">(5~6)年級</span>
					</label>
					<label class="radio-inline">
					  <input type="radio" name="grade" id="grade4" value="4" class="css-checkbox2"> 
					  <span for"grade4" class="css-label2"></span>
					  國中<span class="font12-g">(7~9)年級</span>
					</label>
					<span id="grade_ErrorMsg" class="error" style="display:none">請選擇適用年級。</span>
	              </div>
	            </div>
	            <div class="form-group">
	              <label for="unitName" class="col-sm-2 control-label">關鍵字</label>
	              <div class="col-sm-10">
	                <input type="text" class="form-control" id="keyWord1" name="keyWord1" placeholder="請輸入關鍵字" style="width:150px;float:left;margin-right:10px">
	                <input type="text" class="form-control" id="keyWord2" name="keyWord2" placeholder="請輸入關鍵字" style="width:150px;float:left;margin-right:10px">
	                <input type="text" class="form-control" id="keyWord3" name="keyWord3" placeholder="請輸入關鍵字" style="width:150px;float:left;margin-right:10px">
	                <input type="text" class="form-control" id="keyWord4" name="keyWord4" placeholder="請輸入關鍵字" style="width:150px;float:left;margin-right:10px">
	                <input type="text" class="form-control" id="keyWord5" name="keyWord5" placeholder="請輸入關鍵字" style="width:150px;float:left">
	              	<span id="KeyWord_empty" class="error_tip">至少要有三組關鍵字!</span>
	              </div>
	            </div>
	        </form>
	      </div>

	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel_addUnit">取消新增</button>
	        <button type="button" class="btn btn-primary" id="addUnit_Submit">新增主題</button>
	      </div>
	    </div>