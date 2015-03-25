<?php
	require_once("initPage.php");
	require_once("competitionUnitDBData.php");
	$data = new CompetitionUnitDbData;
	init_header("明日創作主題文章設計競賽");
?>
	<script type="text/javascript" src="js/jquery.validate.js"></script>
	<script type="text/javascript" src="js/registrationCompetition_js.js"></script>
	<?php
		if(isset($_POST['memberName']) && $_POST['memberName'] != ''){  //團體報名表
			//echo "帳號:".$_POST['memberName'][$i]."<br>";
			$partyArray = array();
			for($i = 0;$i < count($_POST['memberName']);$i++){
				
				$tmpArray['account'] = $_POST['memberAccount'][$i];
				$tmpArray['name'] = $_POST['memberName'][$i];
				$tmpArray['sex'] = $_POST['memberSex'][$i];
				$tmpArray['tel'] = $_POST['memberTel'][$i];
				$tmpArray['email'] = $_POST['memberEmail'][$i];
				$tmpArray['kind'] = $_POST['memberKind'][$i];
				$tmpArray['workplace'] = $_POST['memberWorkPlace'][$i];
				$partyArray[] = $tmpArray;
			}
			$resule = $data->groupFormSubmit($partyArray,$_POST['inputTeamName']);
			if($resule) {
					echo "報名成功!<br>";
					echo "<script>document.location.href='successPage.php?type=1';</script>";
					
			}
		}

		if(isset($_POST['inputAccount']) && $_POST['inputAccount'] != ''){	//個人報名表
				$resule = $data->personFormSubmit($_POST['inputAccount'],$_POST['inputName'],$_POST['sex'],$_POST['inputTel'],$_POST['inputEmail'],$_POST['kind'],$_POST['inputWorkPlace']);
				if($resule) {
					echo "報名成功!<br>";
					echo "<script>document.location.href='successPage.php?type=0';</script>";
					
				}
		}


		if(isset($_SESSION['uid'])){
			
	?>
<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
		<h1>報名比賽</h1>
		<hr>
	</div
</div>
<div class="row">
	<div class="col-lg-2 col-lg-offset-2 col-md-2 col-md-offset-2">
		<h2>步驟一</h2>
		<h3>選擇報名種類</h3>
	</div>
	<div class="col-lg-8 col-md-8">
		<div style="margin-top:15px">
			<span>
				<a href="#" data-toggle="" data-target=""><img src="image/personBtnOn.png" id="personBtn" /></a>
			</span>
			<span style="margin-left:50px">
				<a href="#" data-toggle="" data-target=""><img src="image/groupBtnOff.png" id="groupBtn" /></a>
			</span>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-8 col-lg-offset-2">
		<hr>
	</div
</div>
<div class="row">
	<div class="col-lg-1 col-lg-offset-2 col-md-1 col-md-offset-2">
		<h2>步驟二</h2>
		<h3>填寫報名表</h3>
	</div>
	<div class="col-lg-9 col-md-9">
		<div style="margin-top:15px" id="personFormDiv">
			<form class="form-horizontal" name="personForm" method="POST" action="registrationCompetition.php" id="personForm">
	            <div class="form-group">
	              <label for="inputAccount" class="col-sm-1 control-label">帳號</label>
	              <div class="col-sm-11" style="margin-top:5px">
	                <label><?php echo $data->getUserAccount($_SESSION['uid']);?></label>
	                <input type="hidden" name="inputAccount" value="<?php echo $data->getUserAccount($_SESSION['uid']);?>">
	              </div>
	            </div>
	         
	            <div class="form-group has-feedback">
	              <label for="inputName" class="col-sm-1 control-label">姓名</label>
	              <div class="col-sm-2">
	                <input type="text" class="form-control" id="inputName" name="inputName" placeholder="範例:王大明">
	                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
	              </div>
	              <div class="col-sm-9">
	              </div>
	            </div>
	            <div class="form-group">
		            <label for="inlineRadio1" class="col-sm-1 control-label">性別</label>
		            <div class="col-sm-11">
		            	<label class="radio-inline">
						  <input type="radio" name="sex" id="inlineRadio1" value="1" class="css-checkbox2" checked="checked"> 
						  <span for"inlineRadio1"="" class="css-label2"></span>
						 	男
						</label>
						<label class="radio-inline">
						  <input type="radio" name="sex" id="inlineRadio2" value="0" class="css-checkbox2"> 
						  <span for"inlineRadio2"="" class="css-label2"></span>
						 	女
						</label>

			            
		            </div>
	            </div>
	            <div class="form-group has-feedback">
	              <label for="inputTel" class="col-sm-1 control-label">行動電話</label>
	              <div class="col-sm-2">
	                <input type="text" class="form-control" id="inputTel" name="inputTel" placeholder="範例:0987654321">
	                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
	              </div>
	              <div class="col-sm-9">
	              </div>
	            </div>
	            <div class="form-group has-feedback">
	              <label for="inputEmail" class="col-sm-1 control-label">電子信箱</label>
	              <div class="col-sm-4">
	                <input type="text" class="form-control" id="inputEmail" name="inputEmail" placeholder="範例:example@yahoo.com.tw">
	                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
	              </div>
	              <div class="col-sm-7">
	              </div>
	            </div>
	            <div class="form-group">
		            <label for="inlineRadio1" class="col-sm-1 control-label">身分別</label>
		            <div class="col-sm-11">
		            	<label class="radio-inline">
						  <input type="radio" name="kind" id="kindRadio1" value="1" class="css-checkbox2" checked="checked"> 
						  <span for"kindRadio1"="" class="css-label2"></span>
						 	教職員
						</label>
						<label class="radio-inline">
						  <input type="radio" name="kind" id="kindRadio2" value="0" class="css-checkbox2"> 
						  <span for"kindRadio2"="" class="css-label2"></span>
						 	社會人士
						</label>

			            
		            </div>
	            </div>
	            <div class="form-group has-feedback">
	              <label for="inputTel" class="col-sm-1 control-label">任職單位</label>
	              <div class="col-sm-2">
	                <input type="text" class="form-control" id="inputWorkPlace" name="inputWorkPlace" placeholder="範例:中平國小">
	                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
	              </div>
	              <div class="col-sm-7">
	              </div>
	            </div>
	        </form>
		</div>

		<div style="margin-top:15px" id="groupFormDiv">
			<form class="form-horizontal" name="groupForm" method="POST" action="registrationCompetition.php" id="groupForm">
				<div class="col-sm-12 formSubtitle">
	            	<span>團隊資訊>></span>
	            </div>

				<div class="form-group has-feedback">
	              <label for="inputTeamName" class="col-sm-1 control-label">團隊名稱</label>
	              <div class="col-sm-2">
	                <input type="text" class="form-control" id="inputTeamName" placeholder="請輸入團隊名稱" name="inputTeamName">
	                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
	              </div>
	              <div class="col-sm-9">
	              	
	              </div>
	            </div>

	            <div class="col-sm-12 formSubtitle">
	            	<span>成員基本資料>></span>
	            </div>

	            <div class="memberinfo">
	            	<div class="form-group">
		              <label class="col-sm-1 control-label">職稱</label>
		              <div class="col-sm-11" style="margin-top:5px">
		                <label>隊員(主要聯絡人)</label>
		              </div>
		            </div>

	            	<div class="form-group">
		              <label for="memberAccount" class="col-sm-1 control-label">帳號</label>
		              <div class="col-sm-11" style="margin-top:5px">
		              	<input type="text" class="form-control" id="leaderAccount" name="memberAccount[0]" id="leaderAccount" placeholder="請輸入隊員帳號" style="width:200px" value="<?php echo $data->getUserAccount($_SESSION['uid']);?>" disabled>
		              </div>
		            </div>
		         	
		            <div class="form-group has-feedback">
		              <label for="memberName" class="col-sm-1 control-label">姓名</label>
		              <div class="col-sm-2">
		                <input type="text" class="form-control" name="memberName[0]" placeholder="範例:王大明">
		                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
		              </div>
		              <div class="col-sm-9">
		              	
		              </div>
		            </div>
		            <div class="form-group">
			            <label for="memberSex" class="col-sm-1 control-label">性別</label>
			            <div class="col-sm-11">
			            	<label class="radio-inline">
							  <input type="radio" name="memberSex[0]" value="1" class="css-checkbox2" checked="checked"> 
							  <span for"inlineRadio1"="" class="css-label2"></span>
							 	男
							</label>
							<label class="radio-inline">
							  <input type="radio" name="memberSex[0]" value="0" class="css-checkbox2"> 
							  <span for"inlineRadio2"="" class="css-label2"></span>
							 	女
							</label>

				            
			            </div>
		            </div>
		            <div class="form-group has-feedback">
		              <label for="memberTel" class="col-sm-1 control-label">行動電話</label>
		              <div class="col-sm-2">
		                <input type="text" class="form-control" name="memberTel[0]" placeholder="範例:0987654321">
		                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
		              </div>
		              <div class="col-sm-9"></div>
		            </div>
		            <div class="form-group has-feedback">
		              <label for="memberEmail" class="col-sm-1 control-label">電子信箱</label>
		              <div class="col-sm-4">
		                <input type="text" class="form-control" name="memberEmail[0]" placeholder="範例:example@yahoo.com.tw">
		              	<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
		              </div>
		              <div class="col-sm-7"></div>
		            </div>
		            <div class="form-group">
			            <label for="memberKind" class="col-sm-1 control-label">身分別</label>
			            <div class="col-sm-11">
			            	<label class="radio-inline">
							  <input type="radio" name="memberKind[0]" value="1" class="css-checkbox2" checked="checked"> 
							  <span for"kindRadio1"="" class="css-label2"></span>
							 	教職員
							</label>
							<label class="radio-inline">
							  <input type="radio" name="memberKind[0]" value="0" class="css-checkbox2"> 
							  <span for"kindRadio2"="" class="css-label2"></span>
							 	社會人士
							</label>
						</div>
		            </div>
		            <div class="form-group has-feedback">
		              <label for="memberWorkPlace" class="col-sm-1 control-label">任職單位</label>
		              <div class="col-sm-2">
		                <input type="text" class="form-control" name="memberWorkPlace[0]" placeholder="範例:中平國小">
		              	<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
		              </div>
		              <div class="col-sm-9"></div>
		            </div>
	            </div>

	            <div class="memberinfo">
	            	<div class="form-group">
		              <label class="col-sm-1 control-label">職稱</label>
		              <div class="col-sm-11" style="margin-top:5px">
		                <label>隊員</label>
		              </div>
		            </div>
	            	<div class="form-group has-feedback">
		              <label for="memberAccount" class="col-sm-1 control-label">帳號</label>
		              <div class="col-sm-2">
		                <input type="text" class="form-control" id="memberAccount1" name="memberAccount[1]" placeholder="請輸入隊員帳號" data-rule-required="true" minlength="2">
		                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
		                
		              </div>
		              <div class="col-sm-9">
		              	
		              </div>
		            </div>
		         	
		            <div class="form-group has-feedback">
		              <label for="memberName" class="col-sm-1 control-label">姓名</label>
		              <div class="col-sm-2">
		                <input type="text" class="form-control" name="memberName[1]" placeholder="範例:王大明">
		                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
		              </div>
		              <div class="col-sm-9">
		              	
		              </div>
		            </div>
		            <div class="form-group">
			            <label for="memberSex" class="col-sm-1 control-label">性別</label>
			            <div class="col-sm-11">
			            	<label class="radio-inline">
							  <input type="radio" name="memberSex[1]" value="1" class="css-checkbox2" checked="checked"> 
							  <span for"inlineRadio1"="" class="css-label2"></span>
							 	男
							</label>
							<label class="radio-inline">
							  <input type="radio" name="memberSex[1]" value="0" class="css-checkbox2"> 
							  <span for"inlineRadio2"="" class="css-label2"></span>
							 	女
							</label>

				            
			            </div>
		            </div>
		            <div class="form-group has-feedback">
		              <label for="memberTel" class="col-sm-1 control-label">行動電話</label>
		              <div class="col-sm-2">
		                <input type="text" class="form-control" name="memberTel[1]" placeholder="範例:0987654321">
		              	<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
		              </div>
		              <div class="col-sm-9"></div>
		            </div>
		            <div class="form-group has-feedback">
		              <label for="memberEmail" class="col-sm-1 control-label">電子信箱</label>
		              <div class="col-sm-4">
		                <input type="text" class="form-control" name="memberEmail[1]" placeholder="範例:example@yahoo.com.tw">
		              	<span class="glyphicon form-control-feedback" aria-hidden="true"></span>
		              </div>
		              <div class="col-sm-7"></div>
		            </div>
		            <div class="form-group">
			            <label for="memberKind" class="col-sm-1 control-label">身分別</label>
			            <div class="col-sm-11">
			            	<label class="radio-inline">
							  <input type="radio" name="memberKind[1]" value="1" class="css-checkbox2" checked="checked"> 
							  <span for"kindRadio1"="" class="css-label2"></span>
							 	教職員
							</label>
							<label class="radio-inline">
							  <input type="radio" name="memberKind[1]" value="0" class="css-checkbox2"> 
							  <span for"kindRadio2"="" class="css-label2"></span>
							 	社會人士
							</label>
						</div>
		            </div>
		            <div class="form-group has-feedback">
		              <label for="memberWorkPlace" class="col-sm-1 control-label">任職單位</label>
		              <div class="col-sm-2">
		                <input type="text" class="form-control" name="memberWorkPlace[1]" placeholder="範例:中平國小">
		                <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
		              </div>
		              <div class="col-sm-9"></div>
		            </div>
	            </div>
		        
		        <div class="col-sm-12" style="margin-top:15px" id="memberBtnRow">
		        	<button type="button" class="btn btn-success btn-lg" id="addMember">
					  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> 新增隊員
					</button>
					<button type="button" class="btn btn-danger btn-lg" id="deleteMember">
					  <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> 刪除隊員
					</button>
		        </div>
	        </form>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-8 col-lg-offset-2">
			<hr>
		</div
	</div>
	<div class="row">
		<div class="col-lg-1 col-lg-offset-2 col-md-1 col-md-offset-2">
			<h2>步驟三</h2>
			<h3>閱讀規定</h3>
		</div>
		<div class="col-lg-7 col-md-7">
			<div class="rule_box">
				<h3><strong>參加對象:</strong></h3>
				<p>
				任教於桃園市中小學教師或設籍於桃園市之社會人士，皆可以個人名義或團體名義(4人為限)方式報名參賽。(獲獎者須於領獎時檢附身分證明如在職證明或戶籍謄本，若經查不符參賽資格，將取消得獎資格)
				</p>
				<h3><strong>注意事項:</strong></h3>
				<p>一、	參賽者須同意參賽作品採「創用CC」(http://creativecommons.tw/explore)之相
			    關規範使用，並放置於明日學校教材管理系統，供明日學校教師教學使用。
				</p>
				<p>二、	本競賽乃屬非營利教育目的之活動，於本競賽獲獎文章將作為國中小教學公開使用，  建議參賽作品之著作權合法性須符合著作權法第65條規定，並註明出處來源。</p>
				<p><strong>著作權法第 65 條</strong></p>
				<p>著作之合理使用，不構成著作財產權之侵害。著作之利用是否合於第四十四條至第六十三條所定之合理範圍或其他合理使用之情形，應審酌一切情狀，尤應注意下列事項，以為判斷之基準：</p>
				<p>一、利用之目的及性質，包括係為商業目的或非營利教育目的。</p>
				<p>二、著作之性質。</p>
				<p>三、所利用之質量及其在整個著作所占之比例。</p>
				<p>四、利用結果對著作潛在市場與現在價值之影響。</p>
				<p>著作權人團體與利用人團體就著作之合理使用範圍達成協議者，得為前項判斷之參考。前項協議過程中，得諮詢著作權專責機關之意見。</p>
			</div>
			<div class="checkbox ruleCheckBox">
				<input type="checkbox" value="1" name="inputReadRule" id="inputReadRule">
				<label for="inputReadRule"><span></span>我已詳細閱讀本競賽各項規定與注意事項，並同意遵守相關規則。</label><br>
				<span class="inputReadRule_error">您必須同意本競賽各項規定與注意事項才可報名活動。</span>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-8 col-lg-offset-2">
			<hr>
		</div
	</div>
	<div class="row">
		<div class="col-lg-1 col-lg-offset-2 col-md-1 col-md-offset-2">
			<h2>步驟四</h2>
			<h3>送出表單</h3>
		</div>
		<div class="col-lg-9 col-md-9">
			<button type="button" class="btn btn-primary" id="submitBtn" style="margin-top:40px">送出申請</button>
		</div>
	</div>
</div>
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
<?php
	} else {  //未登入系統
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
				
			</div>
		</div>
		  
	</div>
</div>
<?php
	}
	init_footer();

?>