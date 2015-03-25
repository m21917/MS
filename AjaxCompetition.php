<?php
	require_once("competitionUnitDBData.php");

	if(isset($_POST['addUnit_submit'])){
		$data = new CompetitionUnitDbData();
		$json = $data->getSubjectNameList(); //取得科目清單
		echo json_encode($json);
	}

	if (isset($_POST['getSubField'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->getSubField($_POST['field']); //取得子領域項目
		echo json_encode($json);
	}

	if (isset($_POST['newUnit'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->newUnit(
			$_POST['id'], 
			$_POST['unit'], 
			$_POST['field1'], 
			$_POST['field2'], 
			$_POST['grade'],
			$_POST['keyWord']
		);

		echo json_encode($json);
	}

	if (isset($_POST['newTeamUnit'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->newTeamUnit(
			$_POST['teamName'],
			$_POST['id'], 
			$_POST['unit'], 
			$_POST['field1'], 
			$_POST['field2'], 
			$_POST['grade'],
			$_POST['keyWord']
		);

		echo json_encode($json);
	}

	if (isset($_POST['getUnitStatus'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->getUnitStatus($_POST['unit_id']);
		echo json_encode($json);
	}

	if (isset($_POST['getTeamUnitStatus'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->getTeamUnitStatus($_POST['unit_id']);
		echo json_encode($json);
	}

	if (isset($_POST['loadUnitInfo'])) {
		$data = new CompetitionUnitDbData();
		$json[] = $data->LoadUnitInfo($_POST['unit_id'],$_POST['type']);
		echo json_encode($json);
	}

	if (isset($_POST['loadTeamUnitInfo'])) {
		$data = new CompetitionUnitDbData();
		$json[] = $data->loadTeamUnitInfo($_POST['unit_id'],$_POST['type']);
		echo json_encode($json);
	}

	if (isset($_POST['getDeleteType'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->getDeleteType($_POST['unit_id']);
		echo json_encode($json);
	}

	if (isset($_POST['getTeamDeleteType'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->getTeamDeleteType($_POST['unit_id']);
		echo json_encode($json);
	}

	if (isset($_POST['deleteUnit'])) {
		$data = new CompetitionUnitDbData();
		$data->deleteUnit($_POST['unit_id']);
		$json = $data->LoadUnitInfo($_POST['unit_id'],0);
		echo json_encode($json);
	}

	if (isset($_POST['deleteTeamUnit'])) {
		$data = new CompetitionUnitDbData();
		$data->deleteTeamUnit($_POST['unit_id']);
		$json = $data->LoadTeamUnitInfo($_POST['unit_id'],0);
		echo json_encode($json);
	}

	if (isset($_POST['newExample'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->newExample(
			$_POST['id'],
			$_POST['exampleNums'],
			$_POST['article'],
			$_POST['content'],
			$_POST['fromOption'],
			$_POST['from']
		);
		echo json_encode($json);
	}

	if (isset($_POST['newTeamExample'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->newTeamExample(
			$_POST['id'],
			$_POST['exampleNums'],
			$_POST['article'],
			$_POST['content'],
			$_POST['fromOption'],
			$_POST['from']
		);
		echo json_encode($json);
	}

	if (isset($_POST['checkExample'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->CheckExample($_POST['id'], $_POST['exampleNums']);
		echo json_encode($json);
	}

	if (isset($_POST['checkTeamExample'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->checkTeamExample($_POST['id'], $_POST['exampleNums']);
		echo json_encode($json);
	}

	if (isset($_POST['checkExampleStatus'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->checkExampleStatus($_POST['id']);
		echo json_encode($json);
	}

	if (isset($_POST['checkTeamExampleStatus'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->checkTeamExampleStatus($_POST['id']);
		echo json_encode($json);
	}

	if (isset($_POST['getGuidance'])) {
		$data = new CompetitionUnitDbData();
		$json[] = $data->LoadUnitInfo($_POST['id'],1);
		$json[] = $data->getGuidanceByCMSUnitId($_POST['id']);
		echo json_encode($json);
	}

	if (isset($_POST['getTeamGuidance'])) {
		$data = new CompetitionUnitDbData();
		$json[] = $data->LoadTeamUnitInfo($_POST['id'],1);
		$json[] = $data->getTeamGuidanceByCMSUnitId($_POST['id']);
		echo json_encode($json);
	}

	if (isset($_POST['newGuidance'])) {
		$data = new CompetitionUnitDbData();
		
		$json = $data->newGuidance($_POST['cmsunit_id'],$_POST['gid'],$_POST['guidance']);
		
		//echo '{"status" : "newGuidance", "message" : "存檔成功", "result_id" : "'.$insertId.'"}';
		echo json_encode($json);
	}

	if (isset($_POST['newTeamGuidance'])) {
		$data = new CompetitionUnitDbData();
		
		$json = $data->newTeamGuidance($_POST['cmsunit_id'],$_POST['gid'],$_POST['guidance']);
		
		//echo '{"status" : "newGuidance", "message" : "存檔成功", "result_id" : "'.$insertId.'"}';
		echo json_encode($json);
	}

	if (isset($_POST['changeUintStatus'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->changeUintStatus($_POST['id'], $_POST['status']);
		echo json_encode($json);
	}

	if (isset($_POST['LoadTeamUnitListFromMSSC'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->LoadTeamUnitListFromMSSC($_POST['teamName']);
		echo json_encode($json);
	}

	if (isset($_POST['changeTeamUintStatus'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->changeTeamUintStatus($_POST['id'], $_POST['status']);
		echo json_encode($json);
	}

	if (isset($_POST['insertLoadUnit'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->insertLoadUnitCheckExist($_POST['id']);
		echo json_encode($json);
	}

	if (isset($_POST['insertLoadTeamUnit'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->insertLoadTeamUnitCheckExist($_POST['id'],$_POST['teamName']);
		echo json_encode($json);
	}

	if (isset($_POST['sendArticle'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->sendArticle($_POST['id']);
		echo json_encode($json);
	}

	if (isset($_POST['sendTeamArticle'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->sendTeamArticle($_POST['id']);
		echo json_encode($json);
	}

	if (isset($_POST['cancelSendArticle'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->cancelSendArticle($_POST['id']);
		echo json_encode($json);
	}

	if (isset($_POST['cancelSendTeamArticle'])) {
		$data = new CompetitionUnitDbData();
		$json = $data->cancelSendTeamArticle($_POST['id']);
		echo json_encode($json);
	}

	if (isset($_POST['teamNameCheck'])) {

		$data = new CompetitionUnitDbData();
		$json = $data->teamNameCheck($_POST['value']);
		echo json_encode($json);
		
	}

	if (isset($_POST['accountCheck'])) {

		$data = new CompetitionUnitDbData();
		$json = $data->accountCheck($_POST['value']);
		echo json_encode($json);
		
	}

	if (isset($_POST['personRegistCheck'])) {

		$data = new CompetitionUnitDbData();
		$json = $data->personRegistCheck($_POST['account']);
		echo json_encode($json);
		
	}


?>