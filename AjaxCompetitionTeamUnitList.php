<?php
	

	require_once("competitionUnitDBData.php");

	$data = new CompetitionUnitDbData();
	$array = $data->LoadTeamUnitList();
	$dataArray = array();
	$example = 4; //預設範文篇數
	foreach ($array as $object) {

		$_precent = ($object->example_Nums/$example*100) >= 100 ? 100 : $object->example_Nums/$example*100;  //範文完成比例
		$tmpArray = array();
		//所屬團隊
		$str = "<span class='myToolTip' data-toggle='tooltip' data-placement='bottom' title='";
		$nameArray = $data->getTeamMemberName($object->teamName);
		for($i = 0; $i < count($nameArray);$i++){
			if($i+1 != count($nameArray)){
				$str .= $nameArray[$i]." , ";
			} else {
				$str .= $nameArray[$i]."'>";
			}
		}
		$str .= $object->teamName;
		$str .= "</span>";
		$tmpArray[0] = $str;
		//主題名稱
		$str = $object->name;
		// if ($_precent == 100 & $object->guidance_Counts != 0) {
		// 	$str .= '<div class="menu-visable-on-select"><span class="small-link" kind="edit">修改</span> | <span class="small-link" kind="changeStage">變更主題狀態</span> | <span class="small-link" kind="delete">刪除</span></div>';
		// } else {
		// 	$str .= '<div class="menu-visable-on-select"><span class="small-link" kind="edit">修改</span> | <span class="small-link" kind="delete">刪除</span></div>';
		// }
		$str .= '<div class="menu-visable-on-select"><span class="small-link" kind="edit">修改</span> | <span class="small-link" kind="delete">刪除</span></div>';
		$tmpArray[1] = $str;
		//教材領域
		$str = '['.$object->field1.']<br><span style="color:#999;font-size:12px;">'.$object->field2.'</span>';
		$tmpArray[2]  = $str;
		//適用年級
		$str = $object->grade;
		$tmpArray[3]  = $str;
		//範例文章
		$str = '<div class="editExample">編輯</div><br>';
		$str .= $_precent == 100?'<div class="s_com"></div>':'<div class="example_percent_bar"><span style="width:'.$_precent.'%;background:'.($_precent == 100 ? "#8AC007" : "#fc4b1e").'"></span></div>';
		$tmpArray[4]  = $str;
		//啟發問題
		$str = '<div class="editGuidance">編輯</div><br>';
		$str .= $object->guidance_Counts != 0 ? '<div class="s_com"></div>' : '<div style="font-size:10px;color:#999;">未有資料</div>';
		$tmpArray[5]  = $str;
		//主題狀態
		// if ($object->status == 0) {
		// 	if ($_precent == 100 & $object->guidance_Counts != 0) {
		// 		$str = '<span><span class="gray-font italic">已關閉</span></span>';
		// 	}else {
		// 		$str = '<span><span class="red-font italic">未完成</span></span>';
		// 	}
		// } else {
		// 	$str = '<span><span class="green-font italic">已開啟</span></span>';
		// }
		// $tmpArray[6]  = $str;
		//建立時間
		$str = $object->time;
		$tmpArray[6]  = $str;
		//關鍵字
		$str = $object->keyWord;
		if($str == '')
			$str = '無';
		$tmpArray[7]  = $str;
		//投稿教材
		// if ($object->status == 0) {
		// 	if ($_precent == 100 & $object->guidance_Counts != 0) {
		// 		$str = '<span class="gray-font">請先開啟教材</span>';
		// 	}else {
		// 		$str = '<span class="red-font">教材未完成</span>';
		// 	}
		// } else {
		// 	if($object->isSend == 0){
		// 		$str = '<button class="btn btn-default sendArticle" aid="'.$object->id.'">投稿作品</button>';
		// 	}else {
		// 		$str = '<button class="btn btn-default cancelSendArticle" aid="'.$object->id.'">取消投稿</button>';
		// 	}
			
		// }
		if ($_precent == 100 & $object->guidance_Counts != 0) {
			if($object->isSend == 0){
				$str = '<button class="btn btn-default sendArticle" aid="'.$object->id.'">投稿作品</button>';
			}else {
				$str = '<span class="green-font" style="font-size:20px">投稿成功</span>';
				$str .= '<div style="font-size:12px;cursor: pointer;margin-top:2px"><span class="cancelSendArticle gray-font" aid="'.$object->id.'" >取消投稿</span></div>';
			}
		}else {
			$str = '<span class="red-font">教材未完成</span>';
		}
		
		$tmpArray[8]  = $str;
		//ID
		$str = $object->id;
		$tmpArray['id']  = $str;
		//isSend
		$str = $object->isSend;
		$tmpArray['isSend']  = $str;
		$dataArray[]= $tmpArray;
	}
	$articleArray['data'] = $dataArray;
	echo json_encode($articleArray);


?>