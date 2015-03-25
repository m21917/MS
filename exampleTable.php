<dl>	
	<dt class="dt_tablestyle">文章標題：</dt>
	<dd class="dd_tablestyle">
		<input type="text" id="articleTitle" name="articleTitle" class="input_style" style="width:250px;" placeholder="輸入範例文章名稱">
		<span id="articleTitle_empty" class="error_tip">此為必填欄位，不得留空</span>
	</dd>
	<dt class="dt_tablestyle" style="padding-top:21px;">文章內容：</dt>
	<dd class="dd_tablestyle" style="padding-top:5px;height:auto;">
		<div class="wordCount">目前字數： <div id="wordCount" class="red-font">0</div> 字</div>
		<textarea id="exampleContent" name="exampleContent" class="input_style textarea_style" placeholder="輸入範例文章內容"></textarea>
		<span id="exampleContent_empty" class="error_tip" style="margin-left: 120px;">請輸入文章內容，不得留空</span>
	</dd>
	<dt class="dt_tablestyle" style="padding-top:7px;">文章出處：</dt>
	<dd class="dd_tablestyle" style="padding-top:8px;height:50px;">
		<div class="dd_ssdiv">
			<select id="articleFromOption" class="input_style">
				<option value="0">請選擇…</option>
				<?
					$fromList = array("自行創作", "引用自", "修改自");
					foreach($fromList as $key => $value) {
						echo '<option value="'.($key+1).'">'.$value.'</option>';
					}
				?>
			</select>
		</div>
		<div class="dd_ssdiv" style="margin-left:10px;">
			<input type="text" id="articleFrom" name="unitTitle" class="input_style" style="width:250px;" placeholder="輸入文章出處">
			<span id="articleFromOption_empty" class="error_tip">請註明文章出處</span>
		</div>
	</dd>
	<div class="row">
		<div class="col-lg-1">
		</div>
		<div class="col-lg-11">
			<div class="font12-g-info" style="padding-left:20px">書籍：作者（西元年）。文章名稱，《書名》。出版社。</div>
			<div class="font12-g-info" style="padding-left:20px">網站：作者（西元年） 。文章名稱，網站名（網站連結）。 </div>
			<div class="font12-g-info" style="padding-left:20px">學生作品：作者（西元年）。文章名稱，地點學校名，年級班級。</div>
			<div class="font12-g-info" style="padding-left:20px">報紙：作者（西元年月）。文章名稱，報紙名。</div>
		</div>
	</div>
	<dt class="dt_tablestyle"></dt>
	<dd class="dd_tablestyle">
		<div class="sub_exampleContent"><input type="submit" id="sub_article" value="新增範文" class="no_bg"></div>
		<div class="sub_exampleContent"><input type="submit" id="cal_article" value="回到主題清單" class="no_bg"></div>
		
	</dd>
</dl>