<?php 

	require_once("competitionUnitDBData.php");
	$data = new CompetitionUnitDbData();
?>
<style>


</style>
<div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title" id="myModalLabel">範例文章編輯</h4>
	      </div>
	      <div class="modal-body">
	        <div id="example_box">

				<div><span class="h2_title">主題名稱</span><span id="subfieldinfo"></span>
				</div>
				<div id="tab_title">
					<ul class="tabs">
						<li><span value="1">範例文章 #1</span><span id="s1" class="statusImg"></span></li>
						<li><span value="2">範例文章 #2</span><span id="s2" class="statusImg"></span></li>
						<li><span value="3">範例文章 #3</span><span id="s3" class="statusImg"></span></li>
						<li><span value="4">範例文章 #4</span><span id="s4" class="statusImg"></span></li>
					</ul>
				</div>
				<div id="tab_container">
					<div id="tab1" class="tab_content">
						<div class="row formRow">
							<div class="col-lg-2">
								<span class="title_font">文章標題:</span>
							</div>
							<div class="col-lg-10">
								<input type="text" id="articleTitle" name="articleTitle" class="input_style" style="width:250px;" placeholder="輸入範例文章名稱">
								<span id="articleTitle_empty" class="error_tip">此為必填欄位，不得留空</span>
							</div>
						</div>
						<div class="row formRow">
							<div class="col-lg-2">
								<span class="title_font" style="margin-top:18px">文章內容:</span>
							</div>
							<div class="col-lg-10">
								<div class="wordCount">目前字數： <div id="wordCount" class="red-font">0</div> 字</div>
								<textarea id="exampleContent" name="exampleContent" class="input_style textarea_style" placeholder="輸入範例文章內容"></textarea>
								<span id="exampleContent_empty" class="error_tip">請輸入文章內容，不得留空</span>
							</div>
						</div>
						<div class="row formRow">
							<div class="col-lg-2">
								<span class="title_font">文章出處:</span>
							</div>
							<div class="col-lg-10">
								<select id="articleFromOption" class="input_style" style="float:left">
									<option value="0">請選擇…</option>
									<?
										$fromList = array("自行創作", "引用自", "修改自");
										foreach($fromList as $key => $value) {
											echo '<option value="'.($key+1).'" data-toggle="tooltip" data-placement="left" title="Tooltip on left">'.$value.'</option>';
										}
									?>
								</select>
								<!-- <input type="text" id="articleFrom" name="unitTitle" class="input_style" style="width:250px;" placeholder="輸入文章出處"> -->
								<textarea id="articleFrom" name="unitTitle" class="input_style textarea_style" style="width:400px;height:100px;margin-left:20px" placeholder="輸入文章出處"></textarea>
								<span id="articleFromOption_empty" class="error_tip">請註明文章出處</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-2">
								
							</div>
							<div class="col-lg-10">
								<div class="font12-g-info">書籍：作者（西元年）。文章名稱，《書名》。出版社。</div>
								<div class="font12-g-info">網站：作者（西元年） 。文章名稱，網站名（網站連結）。 </div>
								<div class="font12-g-info">學生作品：作者（西元年）。文章名稱，地點學校名，年級班級。</div>
								<div class="font12-g-info">報紙：作者（西元年月）。文章名稱，報紙名。</div>
							</div>
						</div>
					</div>
				</div><!-- end tab_container -->
			</div><!-- end example_box -->
	      </div>

	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel_addExample">取消編輯</button>
	        <button type="button" class="btn btn-primary" id="updateExample_Submit">更新範例文章</button>
	      </div>
</div>