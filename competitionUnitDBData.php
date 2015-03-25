<?php

require_once("DbConnection.php");

  if(!isset($_SESSION)){
      session_start();
  }
  $testVersion = 1; // 測試版本: 0 = 關閉; 1 = 開啟

  if($testVersion) {
    $_SESSION['permission'] = '41362201410151309141';
    $_SESSION['uid'] = '41362';
    $_SESSION['name'] = '中大團隊';
    $_SESSION['school'] = '中央大學';
    $_SESSION['grade'] = '1';
    $_SESSION['school_code'] = 'ncu';
    $_SESSION['sem_year'] = '2014';
    $_SESSION['sem_term'] = '1';
    $_SESSION['ta_class'] = '1';
    $_SESSION['class_code'] = 'ncu_2014_1_1_1';
    $_SESSION['identity'] = 'SA';
    $_SESSION['permission_id'] = '0';

    // $_SESSION['permission'] = 'super';
    // $_SESSION['uid'] = '52039';
    // $_SESSION['name'] = 'diego';
    // $_SESSION['school'] = '中大團隊';
    // $_SESSION['grade'] = '';
    // $_SESSION['school_code'] = '';
    // $_SESSION['sem_year'] = '';
    // $_SESSION['sem_term'] = '';
    // $_SESSION['ta_class'] = '';
    // $_SESSION['class_code'] = '';
    // $_SESSION['identity'] = 'A';
    // $_SESSION['permission_id'] = '0';
  }

  class CompetitionUnitDbData {
         /*
        功能描述：檢查投稿比賽清單是否已有該主題(loadUnit.php) - new
        參數：CMSUnit_id
        建立日期：2015-02-10(Diego)
        修改日期：無
        */
        function insertLoadUnitCheckExist($id){
          $result = $this->checkExistInCompetitionList($id);
          if($result && $result->enable == 0){
            $this->updateUnitEnableStatus($result->id);
            return '教材已重新匯入!';
          } else if($result && $result->enable == 1){
            return '教材已存在';
          } else{
            $this->insertUnitToCompetition($id);
            return '教材匯入成功';
          }
        }

        /*
        功能描述：檢查投稿比賽團體清單是否已有該主題(loadUnit.php) - new
        參數：CMSUnit_id
        建立日期：2015-03-12(Diego)
        修改日期：無
        */
        function insertLoadTeamUnitCheckExist($id,$teamName){
          $result = $this->checkTeamExistInCompetitionList($id,$teamName);
          if($result && $result->enable == 0){
            $this->updateTeamUnitEnableStatus($result->id);
            return $result;
          } else if($result && $result->enable == 1){
            return '教材已存在';
          } else{
            $this->insertTeamUnitToCompetition($id,$teamName);
            return $result;
          }
        }

         /*
        功能描述：還原已新增至投稿比賽主題(loadUnit.php) - new
        參數：CompetitionUnit_id
        建立日期：2015-02-10(Diego)
        修改日期：無
        */
        function updateUnitEnableStatus($id){
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "UPDATE `Competition_CMSUnit` SET `enable`='1' WHERE `id`=:id";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $result = $sth->execute();
          $DbConnection->Close();
        }

        /*
        功能描述：還原已新增至團隊投稿比賽主題(loadTeamUnit.php) - new
        參數：CompetitionUnit_id
        建立日期：2015-03-12(Diego)
        修改日期：無
        */
        function updateTeamUnitEnableStatus($id){
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "UPDATE `Competition_CMSTeamUnit` SET `enable`='1' WHERE `id`=:id";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $result = $sth->execute();
          $DbConnection->Close();
        }
        /*
        功能描述：由教材庫匯入教材(loadUnit.php) - new
        參數：CMSUnit_id
        建立日期：2015-02-09(Diego)
        修改日期：無
        */
        function insertUnitToCompetition($id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "INSERT INTO `Competition_CMSUnit` (`CMSUnit_id`, `name`, `uid`, `CMSMainField_id`, `CMSSubField_id`, `grade`,`status`,`keyWord`) SELECT `id`, `name`, `uid`, `CMSMainField_id`, `CMSSubField_id`, `grade`,`status`,`keyWord` FROM mssc.`Cisland_CMSUnit` WHERE `id` = :id;"; 
          $sql .= "SELECT `id` FROM Competition_CMSUnit WHERE `CMSUnit_id` = :id"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $result = $sth->execute();

          $row = $sth->fetch(PDO::FETCH_ASSOC);
          $this->insertUnitExampleAndGudance($this->getIDFromCompetition($id),$id);

          if($result){
            return $this->getIDFromCompetition($id);
          } else {
            return false;
          }
          
          $DbConnection->Close();

        }

        /*
        功能描述：由教材庫匯入團隊教材(loadTeamUnit.php) - new
        參數：CMSUnit_id
        建立日期：2015-03-12(Diego)
        修改日期：無
        */
        function insertTeamUnitToCompetition($id,$teamName) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "INSERT INTO `Competition_CMSTeamUnit` (`CMSUnit_id`,`belongTeam`, `name`, `uid`, `CMSMainField_id`, `CMSSubField_id`, `grade`,`status`,`keyWord`) SELECT `id`,:teamName, `name`, `uid`, `CMSMainField_id`, `CMSSubField_id`, `grade`,`status`,`keyWord` FROM mssc.`Cisland_CMSUnit` WHERE `id` = :id;"; 
          $sql .= "SELECT `id` FROM Competition_CMSTeamUnit WHERE `CMSUnit_id` = :id"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $sth->bindParam(':teamName', $teamName);
          $result = $sth->execute();

          $row = $sth->fetch(PDO::FETCH_ASSOC);
          $this->insertTeamUnitExampleAndGudance($this->getTeamIDFromCompetition($id,$teamName),$id);

          if($result){
            return $this->getTeamIDFromCompetition($id,$teamName);
          } else {
            return false;
          }
          
          $DbConnection->Close();

        }

        /*
        功能描述：取得投稿比賽文章ID(loadUnit.php) - new
        參數：CompetitionUnit_id
        建立日期：2015-02-09(Diego)
        修改日期：無
        */
        function getIDFromCompetition($id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "SELECT `id` FROM Competition_CMSUnit WHERE `CMSUnit_id` = :id"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);
          if($result){
            return $row['id'];
          } else {
            return false;
          }
          
          $DbConnection->Close();


        }
        /*
        功能描述：取得投稿比賽團隊文章ID(loadTeamUnit.php) - new
        參數：CompetitionUnit_id
        建立日期：2015-03-12(Diego)
        修改日期：無
        */
        function getTeamIDFromCompetition($id,$teamName) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "SELECT `id` FROM Competition_CMSTeamUnit WHERE `CMSUnit_id` = :id AND `belongTeam`=:teamName"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $sth->bindParam(':teamName', $teamName);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);
          if($result){
            return $row['id'];
          } else {
            return false;
          }
          
          $DbConnection->Close();


        }

        /*
        功能描述：由教材庫匯入範文(loadUnit.php) - new
        參數：$id
        建立日期：2015-02-09(Diego)
        修改日期：無
        */
        function insertUnitExampleAndGudance($id,$CMSUnit_id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "INSERT INTO `Competition_CMSExample` (`CMSUnit_id`, `example_id`, `title`, `content`, `resource_type`, `resource`, `create_time`) SELECT $id, `example_id`, `title`, `content`, `resource_type`, `resource`, `create_time` FROM mssc.`Cisland_CMSExample` WHERE `CMSUnit_id` = :id;"; 
          $sql .= "INSERT INTO `Competition_CMSGuidance`(`CMSUnit_id`, `guidContent`, `uid`, `usedOnUnit`, `time`) SELECT $id, `guidContent`, `uid`, `usedOnUnit`, `time` FROM mssc.`Cisland_CMSGuidance` WHERE `CMSUnit_id`=:id AND usedOnUnit='1';"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $CMSUnit_id);
          $result = $sth->execute();

          if($result){
            return true;
          } else {
            return false;
          }
          $DbConnection->Close();

        }

        /*
        功能描述：由教材庫匯入範文至團隊比賽(loadTeamUnit.php) - new
        參數：$id
        建立日期：2015-03-12(Diego)
        修改日期：無
        */
        function insertTeamUnitExampleAndGudance($id,$CMSUnit_id,$teamName) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "INSERT INTO `Competition_CMSTeamExample` (`CMSUnit_id`, `example_id`, `title`, `content`, `resource_type`, `resource`, `create_time`) SELECT $id, `example_id`, `title`, `content`, `resource_type`, `resource`, `create_time` FROM mssc.`Cisland_CMSExample` WHERE `CMSUnit_id` = :id;"; 
          $sql .= "INSERT INTO `Competition_CMSTeamGuidance`(`CMSUnit_id`, `guidContent`, `uid`, `usedOnUnit`, `time`) SELECT $id, `guidContent`, `uid`, `usedOnUnit`, `time` FROM mssc.`Cisland_CMSGuidance` WHERE `CMSUnit_id`=:id AND usedOnUnit='1';"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $CMSUnit_id);
          $result = $sth->execute();

          if($result){
            return true;
          } else {
            return false;
          }
          $DbConnection->Close();

        }


        /*
        功能描述：取得我的主題教材庫列表(loadUnit.php) - new
        參數：$_SESSION['uid']
        建立日期：2015-02-09(Diego)
        修改日期：無
        */
        function LoadUnitListFromMSSC() {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `id`, `name`, `CMSMainField_id`, `CMSSubField_id`, `grade`, `status` FROM mssc.`Cisland_CMSUnit` WHERE `uid` = :uid AND `enable` = '1' AND `status` = '1' ORDER BY `id` DESC";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':uid', $_SESSION['uid']);
          $result = $sth->execute();

          $data = array();

          while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $unit = new stdClass();
            $unit->id = $row['id'];
            $unit->name = $row['name'];
            $unit->field1 = $this->getMainFieldName($row['CMSMainField_id']);
            $unit->field2 = $this->getSubFieldName($row['CMSSubField_id']);
            $unit->grade = $this->getGradeName($row['grade']);
            $unit->status = $row['status'];

            $data[] = $unit;
          }

          if ($result) {
            return $data;
          }else {
            return '資料有誤，請重新查詢';
          }

          $DbConnection->Close();
        }

        /*
        功能描述：取得我的主題教材庫列表-團體比賽(loadUnit.php) - new
        參數：$_SESSION['uid']
        建立日期：2015-02-09(Diego)
        修改日期：無
        */
        function LoadTeamUnitListFromMSSC($teamName) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `id`, `name`, `CMSMainField_id`, `CMSSubField_id`, `grade`, `status` FROM mssc.`Cisland_CMSUnit` WHERE `uid` = :uid AND `enable` = '1' AND `status` = '1' ORDER BY `id` DESC";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':uid', $_SESSION['uid']);
          $result = $sth->execute();

          $data = array();

          while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $unit = new stdClass();
            $unit->id = $row['id'];
            $unit->name = $row['name'];
            $unit->field1 = $this->getMainFieldName($row['CMSMainField_id']);
            $unit->field2 = $this->getSubFieldName($row['CMSSubField_id']);
            $unit->grade = $this->getGradeName($row['grade']);
            $unit->status = $row['status'];
            $unit->exist = $this->checkExistInTeamCompetitionList($row['id'],$teamName);
            
            $data[] = $unit;
          }

          if ($result) {
            return $data;
          }else {
            return '資料有誤，請重新查詢';
          }

          $DbConnection->Close();
        }
        /*
        功能描述：檢查文章是否已存在於投稿比賽(loadUnit.php) - new
        參數：$_SESSION['uid'],$CMSUnit_id
        建立日期：2015-02-09(Diego)
        修改日期：無
        */
        function checkExistInCompetitionList($id){
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "SELECT `id`,`enable` FROM `Competition_CMSUnit` WHERE `uid` = :uid AND `CMSUnit_id` = :id";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':uid', $_SESSION['uid']);
          $sth->bindParam(':id', $id);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);
          $unit = new stdClass();
          $unit->id = $row['id'];
          $unit->enable = $row['enable'];
          $data = $unit;
          if ($sth->rowCount() > 0) {
            return $data;
          }else {
            return FALSE;
          }
        }

        /*
        功能描述：檢查文章是否已存在於團隊投稿比賽(loadUnit.php) - new
        參數：$_SESSION['uid'],$CMSUnit_id
        建立日期：2015-03-12(Diego)
        修改日期：無
        */
        function checkTeamExistInCompetitionList($id,$teamName){
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "SELECT `id`,`enable` FROM `Competition_CMSTeamUnit` WHERE `uid` = :uid AND `CMSUnit_id` = :id AND `belongTeam` = :teamName";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':uid', $_SESSION['uid']);
          $sth->bindParam(':id', $id);
          $sth->bindParam(':teamName', $teamName);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);
          $unit = new stdClass();
          $unit->id = $row['id'];
          $unit->enable = $row['enable'];
          $data = $unit;
          if ($sth->rowCount() > 0) {
            return $data;
          }else {
            return FALSE;
          }
        }

        /*
        功能描述：檢查文章是否已存在於投稿比賽-團體組(loadUnit.php) - new
        參數：$_SESSION['uid'],$CMSUnit_id
        建立日期：2015-03-12(Diego)
        修改日期：無
        */
        function checkExistInTeamCompetitionList($id,$teamName){
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "SELECT `id`,`enable` FROM `Competition_CMSTeamUnit` WHERE `uid` = :uid AND `CMSUnit_id` = :id AND `belongTeam` = :teamName";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':uid', $_SESSION['uid']);
          $sth->bindParam(':id', $id);
          $sth->bindParam(':teamName', $teamName);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);
          $unit = new stdClass();
          $unit->id = $row['id'];
          $unit->enable = $row['enable'];
          $data = $unit;
          if ($sth->rowCount() > 0) {
            return $data;
          }else {
            return FALSE;
          }
        }

        /*
        功能描述：取得我的主題列表(AjaxCompetitionUnitList.php) - new
        參數：$_SESSION['uid']
        建立日期：2015-01-28(Diego)
        修改日期：無
        */
        function LoadUnitList() {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `id`, `name`, `CMSMainField_id`, `CMSSubField_id`, `grade`, `status`, DATE(`time`) AS `time`,`keyWord`,`isSend` FROM `Competition_CMSUnit` WHERE `uid` = :uid AND `enable` = '1' ORDER BY `id` DESC";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':uid', $_SESSION['uid']);
          $result = $sth->execute();
          
          $data = array();

          while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $unit = new stdClass();
            $unit->id = $row['id'];
            $unit->name = $row['name'];
            $unit->field1 = $this->getMainFieldName($row['CMSMainField_id']);
            $unit->field2 = $this->getSubFieldName($row['CMSSubField_id']);
            $unit->grade = $this->getGradeName($row['grade']);
            $unit->status = $row['status'];
            $unit->keyWord = $row['keyWord'];
            $unit->time = $row['time'];
            $unit->isSend = $row['isSend'];
            $unit->example_Nums = $this->DetectExampleInfo($row['id'], true);             //已完成範文篇數
            $unit->guidance_Counts = $this->detectGuidanceCountByCMSUnitId($row['id']);   //啟發問題筆數( != 0：已完成)

            $data[] = $unit;
          }

          if ($result) {
            return $data;
          }else {
            return '資料有誤，請重新查詢';
          }

          $DbConnection->Close();
        }

        /*
        功能描述：取得我的團隊主題列表(AjaxCompetitionTeamUnitList.php) - new
        參數：$_SESSION['uid']
        建立日期：2015-01-28(Diego)
        修改日期：無
        */
        function LoadTeamUnitList() {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $teamArray = $this->getTeamListByUid($_SESSION['uid']);
          $teamStr = '';
          for ($i=0; $i < count($teamArray); $i++) { 
            $teamStr .="'".$teamArray[$i]."'";
            if($i != count($teamArray)-1){
              $teamStr .= ',';
            }
          }
          if($teamStr == '') $teamStr = "''";
          $sql = "SELECT `id`, `name`,`belongTeam`, `CMSMainField_id`, `CMSSubField_id`, `grade`, `status`, DATE(`time`) AS `time`,`keyWord`,`isSend` FROM `Competition_CMSTeamUnit` WHERE `belongTeam` in (".$teamStr.") AND `enable` = '1' ORDER BY `id` DESC";
          $sth = $dbh->prepare($sql);
          $result = $sth->execute();
          
          $data = array();

          while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $unit = new stdClass();
            $unit->id = $row['id'];
            $unit->name = $row['name'];
            $unit->teamName = $row['belongTeam'];
            $unit->field1 = $this->getMainFieldName($row['CMSMainField_id']);
            $unit->field2 = $this->getSubFieldName($row['CMSSubField_id']);
            $unit->grade = $this->getGradeName($row['grade']);
            $unit->status = $row['status'];
            $unit->keyWord = $row['keyWord'];
            $unit->time = $row['time'];
            $unit->isSend = $row['isSend'];
            $unit->example_Nums = $this->DetectTeamExampleInfo($row['id'], true);             //已完成範文篇數
            $unit->guidance_Counts = $this->detectTeamGuidanceCountByCMSUnitId($row['id']);   //啟發問題筆數( != 0：已完成)

            $data[] = $unit;
          }

          if ($result) {
            return $data;
          }else {
            return '資料有誤，請重新查詢';
          }

          $DbConnection->Close();
        }

        /*
        功能描述：變更主題狀態(unit-js.js)
        參數：id(unit_id), status
        建立日期：2014-09-04(Ellen)
        修改日期：無
        */
        function changeUintStatus($id, $status) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $status = ($status==0)?'1':'0';

          $today = date("Y-m-d H:i:s");
          $sql = "UPDATE `Competition_CMSUnit` SET `status` = :status, `updateTime` = :updateTime WHERE `id` = :id";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $sth->bindParam(':status', $status);
          $sth->bindParam(':updateTime', $today);
          $result = $sth->execute();

          if ($result) {
            return TRUE;
          }else {
            return FALSE;
          }

          $DbConnection->Close();
        }

        /*
        功能描述：變更團隊主題狀態(unit-js.js)
        參數：id(unit_id), status
        建立日期：2015-03-11(Diego)
        修改日期：無
        */
        function changeTeamUintStatus($id, $status) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $status = ($status==0)?'1':'0';

          $today = date("Y-m-d H:i:s");
          $sql = "UPDATE `Competition_CMSTeamUnit` SET `status` = :status, `updateTime` = :updateTime WHERE `id` = :id";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $sth->bindParam(':status', $status);
          $sth->bindParam(':updateTime', $today);
          $result = $sth->execute();

          if ($result) {
            return TRUE;
          }else {
            return FALSE;
          }

          $DbConnection->Close();
        }

        /*
        功能描述：範文完成篇數(AjaxCompetitionUnitList.php)
        參數：unit_id, getStatus(true:取得現有篇數 / false:取得範文內容)
        建立日期：2015-01-28(Diego)
        修改日期：無
        */
        function DetectExampleInfo($unit_id, $getStatus) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `CMSUnit_id`, `example_id`, `title`, `content`,`resource_type`, `resource` FROM `Competition_CMSExample` WHERE `CMSUnit_id` = :unitId ORDER BY `example_id`";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':unitId', $unit_id);
          $result = $sth->execute();


          if ($getStatus) {
            $data = $sth->rowCount();
          }else {
            $data = array();
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
              $ex = new stdClass();
              $ex->example_id = $row['example_id'];
              $ex->title = $row['title'];
              $ex->content = $row['content'];
              switch ($row['resource_type']) {
                case 1:
                  $r_type = '自行創作';
                  break;
                
                case 2:
                  $r_type = '引用自';
                  break;

                case 3:
                  $r_type = '修改自';
                  break;
              }
              $ex->resource_type = $r_type;
              $ex->resource = $row['resource'];
              $data[] = $ex;
            }
          }

          return $data;
          $DbConnection->Close();
        }

        /*
        功能描述：團隊範文完成篇數(AjaxCompetitionUnitList.php)
        參數：unit_id, getStatus(true:取得現有篇數 / false:取得範文內容)
        建立日期：2015-01-28(Diego)
        修改日期：無
        */
        function DetectTeamExampleInfo($unit_id, $getStatus) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `CMSUnit_id`, `example_id`, `title`, `content`,`resource_type`, `resource` FROM `Competition_CMSTeamExample` WHERE `CMSUnit_id` = :unitId ORDER BY `example_id`";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':unitId', $unit_id);
          $result = $sth->execute();


          if ($getStatus) {
            $data = $sth->rowCount();
          }else {
            $data = array();
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
              $ex = new stdClass();
              $ex->example_id = $row['example_id'];
              $ex->title = $row['title'];
              $ex->content = $row['content'];
              switch ($row['resource_type']) {
                case 1:
                  $r_type = '自行創作';
                  break;
                
                case 2:
                  $r_type = '引用自';
                  break;

                case 3:
                  $r_type = '修改自';
                  break;
              }
              $ex->resource_type = $r_type;
              $ex->resource = $row['resource'];
              $data[] = $ex;
            }
          }

          return $data;
          $DbConnection->Close();
        }
        /*
        功能描述：主題啟發問題是否存在
        參數：CMSUnit_id
        建立日期：2015-01-28(Diego)
        修改日期：無
        */
        function detectGuidanceCountByCMSUnitId($CMSUnit_id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `id` FROM `Competition_CMSGuidance` WHERE `CMSUnit_id` = :CMSUnit_id";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':CMSUnit_id', $CMSUnit_id);
          $result = $sth->execute();

          if ($result) {
            return $sth->rowCount();
          }else {
            return FALSE;
          }
          $DbConnection->Close();
        }

         /*
        功能描述：團隊主題啟發問題是否存在
        參數：CMSUnit_id
        建立日期：2015-01-28(Diego)
        修改日期：無
        */
        function detectTeamGuidanceCountByCMSUnitId($CMSUnit_id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `id` FROM `Competition_CMSTeamGuidance` WHERE `CMSUnit_id` = :CMSUnit_id";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':CMSUnit_id', $CMSUnit_id);
          $result = $sth->execute();

          if ($result) {
            return $sth->rowCount();
          }else {
            return FALSE;
          }
          $DbConnection->Close();
        }

        function getMainFieldName($field_id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `name` FROM mssc.`Cisland_CMSMainField` WHERE `id` = :fieldId";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':fieldId', $field_id);
          $result = $sth->execute();

          $row = $sth->fetch(PDO::FETCH_ASSOC);
          $data = $row['name'];

          return $data;
          $DbConnection->Close();
        }

        function getUnitStatus($id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `status` FROM `Competition_CMSUnit` WHERE `id` = :id";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $result = $sth->execute();

          $row = $sth->fetch(PDO::FETCH_ASSOC);
          $data = $row['status'];

          return $data;
          $DbConnection->Close();
        }

        function getTeamUnitStatus($id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `status` FROM `Competition_CMSTeamUnit` WHERE `id` = :id";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $result = $sth->execute();

          $row = $sth->fetch(PDO::FETCH_ASSOC);
          $data = $row['status'];

          return $data;
          $DbConnection->Close();
        }

        function getSubFieldName($field_id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `name` FROM mssc.`Cisland_CMSSubField` WHERE `id` = :fieldId";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':fieldId', $field_id);
          $result = $sth->execute();

          $row = $sth->fetch(PDO::FETCH_ASSOC);
          $data = $row['name'];

          return $data;
          $DbConnection->Close();
        }

        function getGradeName($grade) {

          switch($grade) {

              case 1:
                return '低年級';
                break;
              case 2:
                return '中年級';
                break;
              case 3:
                return '高年級';
                break;
              case 4:
                return '國中';
                break;
            }
        }

        /*
        功能描述：取得主領域項目(unit.php)
        參數：none
        建立日期：2014-08-28(Ellen)
        修改日期：無
        */
        function LoadMainField() {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `id`, `name` FROM mssc.`Cisland_CMSMainField` ORDER BY `id`";
          $sth = $dbh->prepare($sql);
          $sth->execute() or exit(var_dump($sth->errorInfo()));

          $data = array();
      
          while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $data[$row['id']] = $row['name'];
          }

          $DbConnection->Close();
          return $data;
        }

        /*
        功能描述：取得子領域項目(unit-js.js)
        參數：主領域ID
        建立日期：2014-08-28(Ellen)
        修改日期：無
        */
        function getSubField($filedId) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `id`, `name` FROM mssc.`Cisland_CMSSubField` WHERE `CMSMainField_id` = :filedId ORDER BY `id`";
          $sth = $dbh->prepare($sql);
          $sth->execute(array(":filedId" => $filedId)) or exit(var_dump($sth->errorInfo()));

          $data = array();

          while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $data[$row['id']] = $row['name'];
          }

          $DbConnection->Close();
          return $data;
        }

        /*
        功能描述：取得啟發問題：主題編輯(usGuidance.php)
        參數：CMSUnit_id, uid(author)
        建立日期：2014-09-22(Ellen)
        修改日期：無
        */
        function getGuidanceByCMSUnitId($CMSUnit_id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `id`,  `guidContent` FROM `Competition_CMSGuidance` WHERE `CMSUnit_id` = :CMSUnit_id";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':CMSUnit_id', $CMSUnit_id);
          $result = $sth->execute();

          $row = $sth->fetch(PDO::FETCH_ASSOC);
          $guid = new stdClass();
          $guid->gid = $row['id'];
          $guid->content = $row['guidContent'];

          if ($result) {
            return $guid;
          }else {
            return FALSE;
          }
          $DbConnection->Close();
        }

        /*
        功能描述：取得團隊啟發問題：主題編輯(usGuidance.php)
        參數：CMSUnit_id, uid(author)
        建立日期：2015-03-11(Diego)
        修改日期：無
        */
        function getTeamGuidanceByCMSUnitId($CMSUnit_id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `id`,  `guidContent` FROM `Competition_CMSTeamGuidance` WHERE `CMSUnit_id` = :CMSUnit_id";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':CMSUnit_id', $CMSUnit_id);
          $result = $sth->execute();

          $row = $sth->fetch(PDO::FETCH_ASSOC);
          $guid = new stdClass();
          $guid->gid = $row['id'];
          $guid->content = $row['guidContent'];

          if ($result) {
            return $guid;
          }else {
            return FALSE;
          }
          $DbConnection->Close();
        }

         /*
        功能描述：新增主題資訊(competitionUnitList_js.js)
        參數：none
        建立日期：2015-01-29(Diego)
        修改日期：無
        */
        function newUnit($id, $unit, $field1, $field2, $grade,$keyWord) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $today = date("Y-m-d H:i:s");

          $sql = "INSERT INTO `Competition_CMSUnit` (`id`, `name`, `uid`, `CMSMainField_id`, `CMSSubField_id`, `grade`,`status`,`keyWord`) VALUES (:id, :unit, :uid, :field1, :field2, :grade,'0',:keyWord) ON DUPLICATE KEY UPDATE `name` = :unit, `CMSMainField_id` = :field1, `CMSSubField_id` = :field2, `grade` = :grade,`keyWord`=:keyWord, `updateTime` = :updateTime";
          if($id == '')
          $sql = "INSERT INTO `Competition_CMSUnit` ( `name`, `uid`, `CMSMainField_id`, `CMSSubField_id`, `grade`,`status`,`keyWord`) VALUES ( :unit, :uid, :field1, :field2, :grade,'0',:keyWord) ON DUPLICATE KEY UPDATE `name` = :unit, `CMSMainField_id` = :field1, `CMSSubField_id` = :field2, `grade` = :grade,`keyWord`=:keyWord, `updateTime` = :updateTime";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $sth->bindParam(':unit', $unit);
          $sth->bindParam(':uid', $_SESSION['uid']);
          $sth->bindParam(':field1', $field1);
          $sth->bindParam(':field2', $field2);
          $sth->bindParam(':grade', $grade);
          $sth->bindParam(':keyWord', $keyWord);
          $sth->bindParam(':updateTime', $today);

          $result = $sth->execute();

          if ($result) {
            return TRUE;
          }else {
            return FALSE;
          }

          $DbConnection->Close();

        }

         /*
        功能描述：新增團隊主題資訊(competitionUnitList_js.js)
        參數：none
        建立日期：2015-01-29(Diego)
        修改日期：無
        */
        function newTeamUnit($teamName, $id, $unit, $field1, $field2, $grade,$keyWord) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $today = date("Y-m-d H:i:s");

          $sql = "INSERT INTO `Competition_CMSTeamUnit` (`id`, `belongTeam`, `name`, `uid`, `CMSMainField_id`, `CMSSubField_id`, `grade`,`status`,`keyWord`) VALUES (:id, :teamName, :unit, :uid, :field1, :field2, :grade,'0',:keyWord) ON DUPLICATE KEY UPDATE `belongTeam`=:teamName,`name` = :unit, `CMSMainField_id` = :field1, `CMSSubField_id` = :field2, `grade` = :grade,`keyWord`=:keyWord, `updateTime` = :updateTime";
          if($id == '')
          $sql = "INSERT INTO `Competition_CMSTeamUnit` ( `belongTeam`,`name`, `uid`, `CMSMainField_id`, `CMSSubField_id`, `grade`,`status`,`keyWord`) VALUES ( :teamName,:unit, :uid, :field1, :field2, :grade,'0',:keyWord) ON DUPLICATE KEY UPDATE `belongTeam`=:teamName,`name` = :unit, `CMSMainField_id` = :field1, `CMSSubField_id` = :field2, `grade` = :grade,`keyWord`=:keyWord, `updateTime` = :updateTime";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $sth->bindParam(':teamName', $teamName);
          $sth->bindParam(':unit', $unit);
          $sth->bindParam(':uid', $_SESSION['uid']);
          $sth->bindParam(':field1', $field1);
          $sth->bindParam(':field2', $field2);
          $sth->bindParam(':grade', $grade);
          $sth->bindParam(':keyWord', $keyWord);
          $sth->bindParam(':updateTime', $today);

          $result = $sth->execute();

          if ($result) {
            return TRUE;
          }else {
            return $sql;
          }

          $DbConnection->Close();

        }

        /*
        功能描述：新增啟發問題資訊(unitSetting-js.js)
        參數：cmsunit_id, guid, owner(1:主題預設/0:自行新增), count(是否已存在此啟發問題)
        建立日期：2014-09-18(Ellen)
        修改日期：2014-09-22(Ellen)：table結構修改
        */
        function newGuidance($cms_unit_id, $gid,$guid) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
         
          if ($gid == 0) {  //insert into
            $sth = $dbh->prepare("INSERT INTO `Competition_CMSGuidance` (`CMSUnit_id`, `guidContent`, `uid`, `usedOnUnit`) VALUES (:id, :guid, :uid, '0')");
            $sth->bindParam(':id', $cms_unit_id);
            $sth->bindParam(':guid', $guid);
            $sth->bindParam(':uid', $_SESSION['uid']);
          } else {  //update
            $sth = $dbh->prepare("UPDATE `Competition_CMSGuidance` SET `guidContent` = :guid  WHERE `CMSUnit_id` = :id AND `id` = :gid");
            $sth->bindParam(':id', $cms_unit_id);
            $sth->bindParam(':gid', $gid);
            $sth->bindParam(':guid', $guid);
          }

          $result = $sth->execute();

          $data = $this->getGuidanceByCMSUnitId($cms_unit_id);
          if ($result) {
            return $data;
          }else {
            return FALSE;
          }
          $DbConnection->Close();
        }

        /*
        功能描述：新增團隊啟發問題資訊(unitSetting-js.js)
        參數：cmsunit_id, guid, owner(1:主題預設/0:自行新增), count(是否已存在此啟發問題)
        建立日期：2015-03-11(Diego)
        
        */
        function newTeamGuidance($cms_unit_id, $gid,$guid) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
         
          if ($gid == 0) {  //insert into
            $sth = $dbh->prepare("INSERT INTO `Competition_CMSTeamGuidance` (`CMSUnit_id`, `guidContent`, `uid`, `usedOnUnit`) VALUES (:id, :guid, :uid, '0')");
            $sth->bindParam(':id', $cms_unit_id);
            $sth->bindParam(':guid', $guid);
            $sth->bindParam(':uid', $_SESSION['uid']);
          } else {  //update
            $sth = $dbh->prepare("UPDATE `Competition_CMSTeamGuidance` SET `guidContent` = :guid  WHERE `CMSUnit_id` = :id AND `id` = :gid");
            $sth->bindParam(':id', $cms_unit_id);
            $sth->bindParam(':gid', $gid);
            $sth->bindParam(':guid', $guid);
          }

          $result = $sth->execute();

          $data = $this->getTeamGuidanceByCMSUnitId($cms_unit_id);
          if ($result) {
            return $data;
          }else {
            return FALSE;
          }
          $DbConnection->Close();
        }

        /*
        功能描述：取得主題資訊(competitionUnitList.php)
        參數：unit_id
        建立日期：2015-01-25(Ellen)
        修改日期：無
        */
        function LoadUnitInfo($unit_id, $code) {  //$code => 1:output名稱 / 0:output代碼

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `id`,`CMSUnit_id`, `name`, `CMSMainField_id`, `CMSSubField_id`, `grade`,`keyWord`,DATE(`time`) AS `time` FROM `Competition_CMSUnit` WHERE `id` = :unitId";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':unitId', $unit_id);
          $result = $sth->execute();

          $row = $sth->fetch(PDO::FETCH_ASSOC);
          
          $unit = new stdClass();
          $unit->id = $row['id'];
          $unit->CMSUnit_id = $row['CMSUnit_id'];
          $unit->name = $row['name'];
          $unit->field1 = ($code==0)?$row['CMSMainField_id']:$this->getMainFieldName($row['CMSMainField_id']);
          $unit->field2 = ($code==0)?$row['CMSSubField_id']:$this->getSubFieldName($row['CMSSubField_id']);
          $unit->grade = ($code==0)?$row['grade']:$this->getGradeName($row['grade']);
          $unit->time = $row['time'];
          $unit->keyWord1 = ''; 
          $unit->keyWord2 = '';
          $unit->keyWord3 = '';
          $unit->keyWord4 = '';
          $unit->keyWord5 = '';
          if($row['keyWord'] != '') {
              $keyWordArray = explode( ',', $row['keyWord'] );
              switch (count($keyWordArray)) {
                case '1':
                  $unit->keyWord1 = $keyWordArray[0]; 
                  break;
                case '2':
                  $unit->keyWord1 = $keyWordArray[0]; 
                  $unit->keyWord2 = $keyWordArray[1]; 
                  break;
                case '3':
                  $unit->keyWord1 = $keyWordArray[0]; 
                  $unit->keyWord2 = $keyWordArray[1];
                  $unit->keyWord3 = $keyWordArray[2];
                  break;
                case '4':
                  $unit->keyWord1 = $keyWordArray[0]; 
                  $unit->keyWord2 = $keyWordArray[1];
                  $unit->keyWord3 = $keyWordArray[2];
                  $unit->keyWord4 = $keyWordArray[3];
                  break;
                case '5':
                  $unit->keyWord1 = $keyWordArray[0]; 
                  $unit->keyWord2 = $keyWordArray[1];
                  $unit->keyWord3 = $keyWordArray[2];
                  $unit->keyWord4 = $keyWordArray[3];
                  $unit->keyWord5 = $keyWordArray[4];
                  break;
              }
          }
          

          if ($result) {
            return $unit;
          }else {
            return '資料有誤，請重新查詢！';
          }
        }

        /*
        功能描述：取得團隊主題資訊(competitionUnitList.php)
        參數：unit_id
        建立日期：2015-03-11(Diego)
        修改日期：無
        */
        function LoadTeamUnitInfo($unit_id, $code) {  //$code => 1:output名稱 / 0:output代碼

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `id`,`CMSUnit_id`,`belongTeam`, `name`, `CMSMainField_id`, `CMSSubField_id`, `grade`,`keyWord`,DATE(`time`) AS `time` FROM `Competition_CMSTeamUnit` WHERE `id` = :unitId";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':unitId', $unit_id);
          $result = $sth->execute();

          $row = $sth->fetch(PDO::FETCH_ASSOC);
          
          $unit = new stdClass();
          $unit->id = $row['id'];
          $unit->CMSUnit_id = $row['CMSUnit_id'];
          $unit->teamName = $row['belongTeam'];
          $unit->name = $row['name'];
          $unit->field1 = ($code==0)?$row['CMSMainField_id']:$this->getMainFieldName($row['CMSMainField_id']);
          $unit->field2 = ($code==0)?$row['CMSSubField_id']:$this->getSubFieldName($row['CMSSubField_id']);
          $unit->grade = ($code==0)?$row['grade']:$this->getGradeName($row['grade']);
          $unit->time = $row['time'];
          $unit->keyWord1 = ''; 
          $unit->keyWord2 = '';
          $unit->keyWord3 = '';
          $unit->keyWord4 = '';
          $unit->keyWord5 = '';
          if($row['keyWord'] != '') {
              $keyWordArray = explode( ',', $row['keyWord'] );
              switch (count($keyWordArray)) {
                case '1':
                  $unit->keyWord1 = $keyWordArray[0]; 
                  break;
                case '2':
                  $unit->keyWord1 = $keyWordArray[0]; 
                  $unit->keyWord2 = $keyWordArray[1]; 
                  break;
                case '3':
                  $unit->keyWord1 = $keyWordArray[0]; 
                  $unit->keyWord2 = $keyWordArray[1];
                  $unit->keyWord3 = $keyWordArray[2];
                  break;
                case '4':
                  $unit->keyWord1 = $keyWordArray[0]; 
                  $unit->keyWord2 = $keyWordArray[1];
                  $unit->keyWord3 = $keyWordArray[2];
                  $unit->keyWord4 = $keyWordArray[3];
                  break;
                case '5':
                  $unit->keyWord1 = $keyWordArray[0]; 
                  $unit->keyWord2 = $keyWordArray[1];
                  $unit->keyWord3 = $keyWordArray[2];
                  $unit->keyWord4 = $keyWordArray[3];
                  $unit->keyWord5 = $keyWordArray[4];
                  break;
              }
          }
          

          if ($result) {
            return $unit;
          }else {
            return '資料有誤，請重新查詢！';
          }
        }

        /*
        功能描述：新增範文資訊(unit-js.js) - 當資料已存在時，更新
        參數：none
        建立日期：2014-09-03(Ellen)
        修改日期：無
        */
        function newExample($id, $exampleNums, $article, $content, $fromOption, $from) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "INSERT INTO `Competition_CMSExample` (`CMSUnit_id`, `example_id`, `title`, `content`, `resource_type`, `resource`) VALUES (:id, :exampleNums, :article, :content, :fromOption, :fromx) ON DUPLICATE KEY UPDATE `title` = :article, `content` = :content, `resource_type` = :fromOption, `resource` = :fromx";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $sth->bindParam(':exampleNums', $exampleNums);
          $sth->bindParam(':article', $article);
          $sth->bindParam(':content', $content);
          $sth->bindParam(':fromOption', $fromOption);
          $sth->bindParam(':fromx', $from);

          $result = $sth->execute();

          if ($result) {
            return TRUE;
          }else {
            return FALSE;
          }

          $DbConnection->Close();
        }

        /*
        功能描述：新增團隊範文資訊(unit-js.js) - 當資料已存在時，更新
        參數：none
        建立日期：2015-03-11(Diego)
        修改日期：無
        */
        function newTeamExample($id, $exampleNums, $article, $content, $fromOption, $from) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "INSERT INTO `Competition_CMSTeamExample` (`CMSUnit_id`, `example_id`, `title`, `content`, `resource_type`, `resource`) VALUES (:id, :exampleNums, :article, :content, :fromOption, :fromx) ON DUPLICATE KEY UPDATE `title` = :article, `content` = :content, `resource_type` = :fromOption, `resource` = :fromx";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $sth->bindParam(':exampleNums', $exampleNums);
          $sth->bindParam(':article', $article);
          $sth->bindParam(':content', $content);
          $sth->bindParam(':fromOption', $fromOption);
          $sth->bindParam(':fromx', $from);

          $result = $sth->execute();

          if ($result) {
            return TRUE;
          }else {
            return FALSE;
          }

          $DbConnection->Close();
        }

        /*
        功能描述：檢查閱讀範文內容(readingUnit-js.js)
        參數：id(unit_id), tab(exampleNums)
        建立日期：2015-01-22(Diego)
        修改日期：無
        */
        function CheckExample($id, $tab) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `title`, `content`, `resource_type`, `resource` FROM `Competition_CMSExample` WHERE `CMSUnit_id` = :id AND `example_id` = :tab";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $sth->bindParam(':tab', $tab);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);

          $data = new stdClass();
          $data->article = $row['title'];
          $data->content = $row['content'];
          $data->resource_type = $row['resource_type'];
          $data->resource = $row['resource'];

          return $data;
          $DbConnection->Close();
        }

        /*
        功能描述：檢查團隊閱讀範文內容(readingUnit-js.js)
        參數：id(unit_id), tab(exampleNums)
        建立日期：2015-03-11(Diego)
        修改日期：無
        */
        function CheckTeamExample($id, $tab) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `title`, `content`, `resource_type`, `resource` FROM `Competition_CMSTeamExample` WHERE `CMSUnit_id` = :id AND `example_id` = :tab";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $sth->bindParam(':tab', $tab);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);

          $data = new stdClass();
          $data->article = $row['title'];
          $data->content = $row['content'];
          $data->resource_type = $row['resource_type'];
          $data->resource = $row['resource'];

          return $data;
          $DbConnection->Close();
        }

        /*
        功能描述：檢查範文存在狀態(unit-js.js)
        參數：id(unit_id)
        建立日期：2014-09-03(Ellen)
        修改日期：無
        */
        function checkExampleStatus($id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `example_id` FROM `Competition_CMSExample` WHERE `CMSUnit_id` = :id ORDER BY `CMSUnit_id`";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $result = $sth->execute();

          $data = array();

          while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row['example_id']);
          }

          return $data;
          $DbConnection->Close();
        }

        /*
        功能描述：檢查團隊範文存在狀態(unit-js.js)
        參數：id(unit_id)
        建立日期：2015-03-11(Diego)
        修改日期：無
        */
        function checkTeamExampleStatus($id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `example_id` FROM `Competition_CMSTeamExample` WHERE `CMSUnit_id` = :id ORDER BY `CMSUnit_id`";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $result = $sth->execute();

          $data = array();

          while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row['example_id']);
          }

          return $data;
          $DbConnection->Close();
        }

        /*
        功能描述：取得刪除類型(competitionUnitList.php)
        參數：id(unit_id)
        建立日期：2015-03-13(Diego)
        修改日期：無
        */
        function getDeleteType($id) {
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "SELECT count(`publish_id`) as num FROM ms_list WHERE `article_id` = :id AND sendFrom='1' AND origin='3' AND enable='0'"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);
          if($row['num'] != 0){
            return true;
          } else {
            return false;
          }
          
          $DbConnection->Close();

        }

        /*
        功能描述：取得團隊文章刪除類型(competitionTeamUnitList.php)
        參數：id(unit_id)
        建立日期：2015-03-13(Diego)
        修改日期：無
        */
        function getTeamDeleteType($id) {
          $teamName = $this->getTeamNameById($id);
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "SELECT count(ms_list.publish_id) as num FROM ms_list,ms_unitBelongTeam WHERE ms_list.publish_id = ms_unitBelongTeam.publish_id AND ms_unitBelongTeam.teamName = :teamName AND  ms_list.article_id = :id AND ms_list.sendFrom='1' AND ms_list.origin='4' AND ms_list.enable='0'"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $sth->bindParam(':teamName', $teamName);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);
          if($row['num'] != 0){
            return true;
          } else {
            return false;
          }
          
          $DbConnection->Close();

        }

        /*
        功能描述：取得文章所屬團隊(competitionTeamUnitList.php)
        參數：id(unit_id)
        建立日期：2015-03-13(Diego)
        修改日期：無
        */
        function getTeamNameById($id) {
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "SELECT `belongTeam` FROM Competition_CMSTeamUnit WHERE `id` = :id"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);
          
          return $row['belongTeam'];
          
          
          $DbConnection->Close();

        }

        /*
        功能描述：刪除主題(competitionUnitList.php)
        參數：id(unit_id)
        建立日期：2014-09-05(Ellen)
        修改日期：無
        */
        function deleteUnit($id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "UPDATE `Competition_CMSUnit` SET `enable` = 0 WHERE `id` = :id";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $result = $sth->execute();

          if ($result) {
            return TRUE;
          }else {
            return FALSE;
          }

          $DbConnection->Close();
        }

         /*
        功能描述：刪除團隊主題(competitionUnitList.php)
        參數：id(unit_id)
        建立日期：2015-03-11(Diego)
        修改日期：無
        */
        function deleteTeamUnit($id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "UPDATE `Competition_CMSTeamUnit` SET `enable` = 0 WHERE `id` = :id";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $result = $sth->execute();

          if ($result) {
            return TRUE;
          }else {
            return FALSE;
          }

          $DbConnection->Close();
        }
        /*
        功能描述：投稿團隊文章(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-03-12(Diego)
        修改日期：無
        */
        function sendTeamArticle($id) {

          $class_code ='';
          if(isset($_SESSION['class_code'])){
            $class_code =$_SESSION['class_code'];
          }
          $checkExist = $this->getTeamIDFromMSList($id);

            if($checkExist != ''){  //曾經投稿過
              $this->reSendTeamArticle($id);
              $this->updateTeamArticleToMSExample($checkExist,$id);
              $this->updateTeamArticleToMSGuidance($id);
              $this->updateTeamNameToMSUnitBelong($checkExist,$id);
              return "重新投稿教材成功!";



            } else {    //尚未投稿過
              $articleInfo = $this->LoadTeamUnitInfo($id, 0);
              $addArticle = $this->addTeamArticleToMSList($id,$class_code,$articleInfo->time);
              if($addArticle){
                  $publish_id = $this->getTeamIDFromMSList($id);
                  $this->addTeamArticleToMSExample($publish_id,$id);
                  $this->addTeamArticleToMSGuidance($id);
                  $this->addTeamNameToMSUnitBelong($publish_id,$articleInfo->teamName);
                  $DbConnection = new DbConnection();
                  $dbh = $DbConnection->Open();

                  $sql = "UPDATE `Competition_CMSTeamUnit` SET `isSend` = '1' WHERE `id` = :id";
                  $sth = $dbh->prepare($sql);
                  $sth->bindParam(':id', $id);
                  $result = $sth->execute();

                  if ($result) {
                    return "投稿教材成功!";
                  }else {
                    return "投稿教材時發生錯誤!";
                  }

                  $DbConnection->Close();
              } else {
                return "投稿教材時發生錯誤!";
              }
            }
            
        }

        /*
        功能描述：投稿文章(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-02-12(Diego)
        修改日期：無
        */
        function sendArticle($id) {

          $class_code ='';
          if(isset($_SESSION['class_code'])){
            $class_code =$_SESSION['class_code'];
          }
          $checkExist = $this->getIDFromMSList($id);

            if($checkExist != ''){  //曾經投稿過
              $this->reSendArticle($id);
              $this->updateArticleToMSExample($checkExist,$id);
              $this->updateArticleToMSGuidance($id);
              
              return "重新投稿教材成功!";



            } else {    //尚未投稿過
              $articleInfo = $this->LoadUnitInfo($id, 0);
              $addArticle = $this->addArticleToMSList($id,$class_code,$articleInfo->time);
              if($addArticle){
                  $publish_id = $this->getIDFromMSList($id);
                  $this->addArticleToMSExample($publish_id,$id);
                  $this->addArticleToMSGuidance($id);
                  $DbConnection = new DbConnection();
                  $dbh = $DbConnection->Open();

                  $sql = "UPDATE `Competition_CMSUnit` SET `isSend` = '1' WHERE `id` = :id";
                  $sth = $dbh->prepare($sql);
                  $sth->bindParam(':id', $id);
                  $result = $sth->execute();

                  if ($result) {
                    return "投稿教材成功!";
                  }else {
                    return "投稿教材時發生錯誤!";
                  }

                  $DbConnection->Close();
              } else {
                return "投稿教材時發生錯誤!";
              }
            }
            
        }
         /*
        功能描述：取消投稿文章(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-02-12(Diego)
        修改日期：無
        */
        function cancelSendArticle($id) {

          $publish_id = $this->getIDFromMSList($id);
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "UPDATE `ms_list` SET enable='1' WHERE publish_id = :pid;"; 
          $sql .= "UPDATE `Competition_CMSUnit` SET isSend='0' WHERE id = '".$id."';"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':pid', $publish_id);
          $result = $sth->execute();
 
          if($result){
            return true;
          } else {
            return false;
          }
          $DbConnection->Close();

        }

         /*
        功能描述：取消團隊投稿文章(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-03-12(Diego)
        修改日期：無
        */
        function cancelSendTeamArticle($id) {

          $publish_id = $this->getTeamIDFromMSList($id);
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "UPDATE `ms_list` SET enable='1' WHERE publish_id = :pid;"; 
          $sql .= "UPDATE `Competition_CMSTeamUnit` SET isSend='0' WHERE id = '".$id."';"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':pid', $publish_id);
          $result = $sth->execute();
 
          if($result){
            return true;
          } else {
            return false;
          }
          $DbConnection->Close();

        }

        /*
        功能描述：重新投稿文章(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-02-12(Diego)
        修改日期：無
        */
        function reSendArticle($id) {

          $publish_id = $this->getIDFromMSList($id);
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "UPDATE `ms_list` SET enable='0',sTime=NOW() WHERE publish_id = :pid AND origin='3';"; 
          $sql .= "UPDATE `Competition_CMSUnit` SET isSend='1' WHERE id = '".$id."';"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':pid', $publish_id);
          $result = $sth->execute();

          if($result){
            return true;
          } else {
            return false;
          }
          $DbConnection->Close();

        }

        /*
        功能描述：重新投稿團隊文章(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-02-12(Diego)
        修改日期：無
        */
        function reSendTeamArticle($id) {

          $publish_id = $this->getTeamIDFromMSList($id);
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "UPDATE `ms_list` SET enable='0',sTime=NOW() WHERE publish_id = :pid AND origin='4';"; 
          $sql .= "UPDATE `Competition_CMSTeamUnit` SET isSend='1' WHERE id = '".$id."';"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':pid', $publish_id);
          $result = $sth->execute();

          if($result){
            return true;
          } else {
            return false;
          }
          $DbConnection->Close();

        }

        /*
        功能描述：新增團隊文章所屬隊伍(competitionUnitList.php) - new
        參數：$publish_id,$teamName
        建立日期：2015-03-12(Diego)
        修改日期：無
        */
        function addTeamNameToMSUnitBelong($publish_id,$teamName) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "INSERT INTO `ms_unitBelongTeam`(`publish_id`, `teamName`) VALUES (:pid,:teamName)"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':pid', $publish_id);
          $sth->bindParam(':teamName', $teamName);
          $result = $sth->execute();

          if($result){
            return true;
          } else {
            return false;
          }
          $DbConnection->Close();

        }

        /*
        功能描述：更新團隊文章所屬隊伍(competitionUnitList.php) - new
        參數：$publish_id,$teamName
        建立日期：2015-03-12(Diego)
        修改日期：無
        */
        function updateTeamNameToMSUnitBelong($publish_id,$id) {
          $articleInfo = $this->LoadTeamUnitInfo($id, 0);
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "UPDATE `ms_unitBelongTeam` SET `teamName`=:teamName WHERE `publish_id`=:pid"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':pid', $publish_id);
          $sth->bindParam(':teamName', $articleInfo->teamName);
          $result = $sth->execute();

          if($result){
            return true;
          } else {
            return false;
          }
          $DbConnection->Close();

        }

        /*
        功能描述：新增投稿文章(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-02-12(Diego)
        修改日期：無
        */
        function addArticleToMSList($id,$class_code,$aTime) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "INSERT INTO `ms_list`(`UID`, `sendFrom`, `class_code`, `article_id`, `origin`, `aTime`, `status`, `rUID`, `published`) VALUES (:uid,'1',:class_code,:id,'3',:aTime,'0',:uid,'0')"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':uid', $_SESSION['uid']);
          $sth->bindParam(':class_code', $class_code);
          $sth->bindParam(':id', $id);
          $sth->bindParam(':aTime', $aTime);
          $result = $sth->execute();

          if($result){
            return true;
          } else {
            return false;
          }
          $DbConnection->Close();

        }

        /*
        功能描述：新增團隊投稿文章(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-03-12(Diego)
        修改日期：無
        */
        function addTeamArticleToMSList($id,$class_code,$aTime) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "INSERT INTO `ms_list`(`UID`, `sendFrom`, `class_code`, `article_id`, `origin`, `aTime`, `status`, `rUID`, `published`) VALUES (:uid,'1',:class_code,:id,'4',:aTime,'0',:uid,'0')"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':uid', $_SESSION['uid']);
          $sth->bindParam(':class_code', $class_code);
          $sth->bindParam(':id', $id);
          $sth->bindParam(':aTime', $aTime);
          $result = $sth->execute();

          if($result){
            return true;
          } else {
            return false;
          }
          $DbConnection->Close();

        }

        /*
        功能描述：新增投稿文章範文(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-02-12(Diego)
        修改日期：無
        */
        function addArticleToMSExample($pid,$id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "INSERT INTO `ms_CMSExample`(`publish_id`,`sendFrom`, `unit_id`, `example_id`, `title`, `content`, `create_time`, `resource_type`, `resource`, `lock`, `enable`) SELECT :pid,'1',`CMSUnit_id`, `example_id`, `title`, `content`,`create_time`, `resource_type`, `resource`,'0','0'  FROM `Competition_CMSExample` WHERE `CMSUnit_id`=:aid;"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':pid', $pid);
          $sth->bindParam(':aid', $id);
    
          $result = $sth->execute();

          if($result){
            return true;
          } else {
            return false;
          }
          $DbConnection->Close();

        }
        /*
        功能描述：新增團隊投稿文章範文(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-03-12(Diego)
        修改日期：無
        */
        function addTeamArticleToMSExample($pid,$id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "INSERT INTO `ms_CMSExample`(`publish_id`,`sendFrom`, `unit_id`, `example_id`, `title`, `content`, `create_time`, `resource_type`, `resource`, `lock`, `enable`) SELECT :pid,'1',`CMSUnit_id`, `example_id`, `title`, `content`,`create_time`, `resource_type`, `resource`,'0','0'  FROM `Competition_CMSTeamExample` WHERE `CMSUnit_id`=:aid;"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':pid', $pid);
          $sth->bindParam(':aid', $id);
    
          $result = $sth->execute();

          if($result){
            return true;
          } else {
            return false;
          }
          $DbConnection->Close();

        }

        /*
        功能描述：更新投稿文章範文(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-02-12(Diego)
        修改日期：無
        */
        function updateArticleToMSExample($pid,$id) {

          $exArray = $this->getExampleInfo($id);
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = '';
          for ($i = 0;$i < count($exArray);$i++){
            $sql .= "UPDATE `ms_CMSExample` SET `title`='".$exArray[$i]->title."', `content`='".$exArray[$i]->content."', `create_time`='".$exArray[$i]->create_time."', `resource_type`='".$exArray[$i]->resource_type."', `resource`='".$exArray[$i]->resource."' WHERE `publish_id`='".$pid."' AND `example_id`='".$exArray[$i]->example_id."';";
          }

           
          $sth = $dbh->prepare($sql);
          $result = $sth->execute();

          if($result){
            return TRUE;
          } else {
            return false;
          }
          
          
          $DbConnection->Close();
          

        }

         /*
        功能描述：更新團隊投稿文章範文(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-03-12(Diego)
        修改日期：無
        */
        function updateTeamArticleToMSExample($pid,$id) {

          $exArray = $this->getTeamExampleInfo($id);
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = '';
          for ($i = 0;$i < count($exArray);$i++){
            $sql .= "UPDATE `ms_CMSExample` SET `title`='".$exArray[$i]->title."', `content`='".$exArray[$i]->content."', `create_time`='".$exArray[$i]->create_time."', `resource_type`='".$exArray[$i]->resource_type."', `resource`='".$exArray[$i]->resource."' WHERE `publish_id`='".$pid."' AND `example_id`='".$exArray[$i]->example_id."';";
          }

           
          $sth = $dbh->prepare($sql);
          $result = $sth->execute();

          if($result){
            return TRUE;
          } else {
            return false;
          }
          
          
          $DbConnection->Close();
          

        }

        /*
        功能描述：新增投稿文章啟發問題(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-02-12(Diego)
        修改日期：無
        */
        function addArticleToMSGuidance($id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "INSERT INTO `ms_CMSGuidance`(`sendFrom`,`CMSUnit_id`, `guidContent`, `uid`, `time`) SELECT '1',`CMSUnit_id`, `guidContent`, `uid`, `time`  FROM `Competition_CMSGuidance` WHERE `CMSUnit_id`=:aid;"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':aid', $id);
    
          $result = $sth->execute();

          if($result){
            return true;
          } else {
            return false;
          }
          $DbConnection->Close();

        }

        /*
        功能描述：新增團隊投稿文章啟發問題(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-03-12(Diego)
        修改日期：無
        */
        function addTeamArticleToMSGuidance($id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "INSERT INTO `ms_CMSGuidance`(`sendFrom`,`CMSUnit_id`, `guidContent`, `uid`, `time`) SELECT '1',`CMSUnit_id`, `guidContent`, `uid`, `time`  FROM `Competition_CMSTeamGuidance` WHERE `CMSUnit_id`=:aid;"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':aid', $id);
    
          $result = $sth->execute();

          if($result){
            return true;
          } else {
            return false;
          }
          $DbConnection->Close();

        }

        /*
        功能描述：更新投稿文章啟發問題(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-02-12(Diego)
        修改日期：無
        */
        function updateArticleToMSGuidance($id) {

          $content = $this->getGuidContent($id);
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "UPDATE `ms_CMSGuidance` SET `guidContent`='".$content."' WHERE `sendFrom`='1' AND `CMSUnit_id`='".$id."'"; 
          $sth = $dbh->prepare($sql);
    
          $result = $sth->execute();

          if($result){
            return true;
          } else {
            return false;
          }
          $DbConnection->Close();


        }

        /*
        功能描述：更新團隊投稿文章啟發問題(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-03-12(Diego)
        修改日期：無
        */
        function updateTeamArticleToMSGuidance($id) {

          $content = $this->getTeamGuidContent($id);
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "UPDATE `ms_CMSGuidance` SET `guidContent`='".$content."' WHERE `sendFrom`='1' AND `CMSUnit_id`='".$id."'"; 
          $sth = $dbh->prepare($sql);
    
          $result = $sth->execute();

          if($result){
            return true;
          } else {
            return false;
          }
          $DbConnection->Close();


        }

        /*
        功能描述：取得個人賽儲文所ID(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-02-12(Diego)
        修改日期：無
        */
        function getIDFromMSList($id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "SELECT `publish_id` FROM ms_list WHERE `article_id` = :id AND sendFrom='1' AND origin='3'"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);
          if($result){
            return $row['publish_id'];
          } else {
            return false;
          }
          
          $DbConnection->Close();


        }

        /*
        功能描述：取得團隊賽儲文所ID(competitionUnitList.php) - new
        參數：$id
        建立日期：2015-02-12(Diego)
        修改日期：無
        */
        function getTeamIDFromMSList($id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "SELECT `publish_id` FROM ms_list WHERE `article_id` = :id AND sendFrom='1' AND origin='4'"; 
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':id', $id);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);
          if($result){
            return $row['publish_id'];
          } else {
            return false;
          }
          
          $DbConnection->Close();


        }

        /*
        功能描述：取得範文內容
        參數：unit_id
        建立日期：2015-02-13(Diego)
        修改日期：無
        */
        function getExampleInfo($unit_id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `CMSUnit_id`, `example_id`, `title`, `content`,`resource_type`, `resource`,`create_time` FROM `Competition_CMSExample` WHERE `CMSUnit_id` = :unitId ORDER BY `example_id`";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':unitId', $unit_id);
          $result = $sth->execute();


            $data = array();
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
              $ex = new stdClass();
              $ex->example_id = $row['example_id'];
              $ex->title = $row['title'];
              $ex->content = $row['content'];
              $ex->resource_type = $row['resource_type'];
              $ex->resource = $row['resource'];
              $ex->create_time = $row['create_time'];
              $data[] = $ex;
            }
          

          return $data;
          $DbConnection->Close();
        }

        /*
        功能描述：取得團隊範文內容
        參數：unit_id
        建立日期：2015-03-12(Diego)
        修改日期：無
        */
        function getTeamExampleInfo($unit_id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `CMSUnit_id`, `example_id`, `title`, `content`,`resource_type`, `resource`,`create_time` FROM `Competition_CMSTeamExample` WHERE `CMSUnit_id` = :unitId ORDER BY `example_id`";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':unitId', $unit_id);
          $result = $sth->execute();


            $data = array();
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
              $ex = new stdClass();
              $ex->example_id = $row['example_id'];
              $ex->title = $row['title'];
              $ex->content = $row['content'];
              $ex->resource_type = $row['resource_type'];
              $ex->resource = $row['resource'];
              $ex->create_time = $row['create_time'];
              $data[] = $ex;
            }
          

          return $data;
          $DbConnection->Close();
        }

        /*
        功能描述：取得範文內容
        參數：unit_id
        建立日期：2015-02-13(Diego)
        修改日期：無
        */
        function getGuidContent($unit_id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `guidContent` FROM `Competition_CMSGuidance` WHERE `CMSUnit_id` = :unitId";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':unitId', $unit_id);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);

          return $row['guidContent'];
          $DbConnection->Close();

        }

        /*
        功能描述：取得團隊範文內容
        參數：unit_id
        建立日期：2015-03-13(Diego)
        修改日期：無
        */
        function getTeamGuidContent($unit_id) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT `guidContent` FROM `Competition_CMSTeamGuidance` WHERE `CMSUnit_id` = :unitId";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':unitId', $unit_id);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);

          return $row['guidContent'];
          $DbConnection->Close();

        }

        /*
        功能描述：取得帳號
        參數：uid
        建立日期：2015-02-13(Diego)
        修改日期：無
        */
        function getUserAccount($uid) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT account FROM user.`member` WHERE `uid` = :uid";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':uid', $uid);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);

          return $row['account'];
          $DbConnection->Close();

        }

        /*
        功能描述：取得uid
        參數：account
        建立日期：2015-03-09(Diego)
        修改日期：無
        */
        function getUserUid($account) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT uid FROM user.`member` WHERE `account` = :account";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':account', $account);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);

          return $row['uid'];
          $DbConnection->Close();

        }

        /*
        功能描述：檢查隊伍名稱是否存在
        參數：uid
        建立日期：2015-02-13(Diego)
        修改日期：無
        */
        function teamNameCheck($value) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT count(team_uid) as num FROM MS.`Competition_TeamUserList` WHERE `team_name` = :val";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':val', $value);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);

          if($row['num'] == 0){        
            return true;
            
          } else {
            return false;
          }
          
          $DbConnection->Close();

        }

        /*
        功能描述：檢查帳號是否存在
        參數：uid
        建立日期：2015-02-13(Diego)
        修改日期：無
        */
        function accountCheck($value) {

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT count(account) as num FROM user.`member` WHERE `account` = :val";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':val', $value);
          $result = $sth->execute();
          $row = $sth->fetch(PDO::FETCH_ASSOC);

          if($row['num'] == 0){
            return false;
          } else {
            return true;
          }
          
          $DbConnection->Close();

        }

        /*
        功能描述：教材編輯競賽個人組報名
        參數：account,name,sex,tel,email,kind,workpalce
        建立日期：2015-03-09(Diego)
        修改日期：無
        */
        function personFormSubmit($account,$name,$sex,$tel,$email,$kind,$workpalce){
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();
          $sql = "INSERT INTO MS.`Competition_UserList`(`uid`, `name`, `sex`, `email`, `tel`, `kind`, `workPlace`, `personTeam`, `groupTeam`) VALUES (:uid, :name, :sex, :email, :tel, :kind, '$workpalce','1','0')";
          //$sql = "INSERT INTO `Competition_UserList`(`uid`, `name`, `sex`, `email`, `tel`, `kind`, `workPlace`, `personTeam`, `groupTeam`) VALUES ('".$this->getUserUid($account)."','$name','$sex','$email','$tel','$kind','$workpalce','1','0')";
          $sth = $dbh->prepare($sql);
          $uid = $this->getUserUid($account);
          $sth->bindParam(':uid', $uid);
          $sth->bindParam(':name', $name);
          $sth->bindParam(':sex', $sex);
          $sth->bindParam(':email', $email);
          $sth->bindParam(':tel', $tel);
          $sth->bindParam(':kind', $kind);
          //$sth->bindParam(':workplace', $workplace);
          //echo "INSERT INTO `Competition_UserList`(`uid`, `name`, `sex`, `email`, `tel`, `kind`, `workPlace`, `personTeam`, `groupTeam`) VALUES ('".$this->getUserUid($account)."','$name','$sex','$email','$tel','$kind','$workpalce','1','0')";
          $result = $sth->execute();
          echo "resule =".$result;
          $row = $sth->fetch(PDO::FETCH_ASSOC);
          if($result) {
            return true;
          } else {
            return false;
          }

          $DbConnection->Close();
        }


        /*
        功能描述：檢查個人組報名是否重覆
        參數：account
        建立日期：2015-03-09(Diego)
        修改日期：無
        */
        function personRegistCheck($account){
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT count(uid) as num FROM MS.`Competition_UserList` WHERE `uid` = :uid AND `personTeam`='1'";
          $sth = $dbh->prepare($sql);
          $uid = $this->getUserUid($account);
          $sth->bindParam(':uid', $uid);
          $result = $sth->execute();

          $row = $sth->fetch(PDO::FETCH_ASSOC);

          if($row['num'] == 0){
            return false;
          } else {
            return true;
          }
          
          $DbConnection->Close();
        }

        /*
        功能描述：檢查是否報名團體組
        參數：account
        建立日期：2015-03-09(Diego)
        修改日期：無
        */
        function groupRegistCheck($account){
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT count(uid) as num FROM MS.`Competition_UserList` WHERE `uid` = :uid AND `groupTeam`='1'";
          $sth = $dbh->prepare($sql);
          $uid = $this->getUserUid($account);
          $sth->bindParam(':uid', $uid);
          $result = $sth->execute();

          $row = $sth->fetch(PDO::FETCH_ASSOC);

          if($row['num'] == 0){
            return false;
          } else {
            return true;
          }
          
          $DbConnection->Close();
        }


        /*
        功能描述：教材編輯競賽團體組報名
        參數：$value_array
        建立日期：2015-03-09(Diego)
        修改日期：無
        */
        function groupFormSubmit($value_array,$teamName){

          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $success = 1;
          for($i = 0;$i < count($value_array);$i++){
            $uid = $this->getUserUid($value_array[$i]['account']);
            $name = $value_array[$i]['name'];
            $sex = $value_array[$i]['sex'];
            $email = $value_array[$i]['email'];
            $tel = $value_array[$i]['tel'];
            $kind = $value_array[$i]['kind'];
            $workpalce = $value_array[$i]['workplace'];
            $leader = 0;
            if($i == 0) $leader = 1;
            if($this->RegistCheck($value_array[$i]['account'])){   //如果報名表有他，不更新他原有資料
              $sql = "UPDATE `Competition_UserList` SET `groupTeam`='1' WHERE `uid`='$uid';";
            } else {
              $sql = "INSERT INTO MS.`Competition_UserList`(`uid`, `name`, `sex`, `email`, `tel`, `kind`, `workPlace`, `personTeam`, `groupTeam`) VALUES ('$uid', '$name', '$sex', '$email', '$tel', '$kind', '$workpalce','0','1');";
            }
            $sql .= "INSERT INTO MS.`Competition_TeamUserList`(`team_uid`, `team_name`, `team_leader`) VALUES ('$uid','$teamName','$leader')";

            $sth = $dbh->prepare($sql);
            $result = $sth->execute();
            if(!$result) $success = 0;
          }

          if($success) {
            return true;
          } else {
            return false;
          }

          $DbConnection->Close();
        }

         /*
        功能描述：檢查是否在報名清單
        參數：account
        建立日期：2015-03-09(Diego)
        修改日期：無
        */
        function RegistCheck($account){
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT count(uid) as num FROM MS.`Competition_UserList` WHERE `uid` = :uid";
          $sth = $dbh->prepare($sql);
          $uid = $this->getUserUid($account);
          $sth->bindParam(':uid', $uid);
          $result = $sth->execute();

          $row = $sth->fetch(PDO::FETCH_ASSOC);

          if($row['num'] == 0){
            return false;
          } else {
            return true;
          }
          
          $DbConnection->Close();
        }

         /*
        功能描述：取得報名表基本資訊
        參數：uid
        建立日期：2015-03-20(Diego)
        修改日期：無
        */
        function getInformation($uid){
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT * FROM MS.`Competition_UserList` WHERE `uid` = :uid";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':uid', $uid);
          $result = $sth->execute();

          $row = $sth->fetch(PDO::FETCH_ASSOC);

          $info = new stdClass();
          $info->name = $row['name'];
          $info->sex = $row['sex']==1?'男':'女';
          $info->email = $row['email'];
          $info->tel = $row['tel'];
          $info->kind = $row['kind']==0?'教職員':'社會人士';
          $info->workPlace = $row['workPlace'];
          
          return $info;
          
          
          $DbConnection->Close();
        }

         /*
        功能描述：取得所屬隊伍列表
        參數：account
        建立日期：2015-03-10(Diego)
        修改日期：無
        */
        function getTeamListByUid($uid){
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT team_name FROM MS.`Competition_TeamUserList` WHERE `team_uid` = :uid group by team_name";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':uid', $uid);
          $result = $sth->execute();
          $teamArray = array();
          while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $teamArray[] = $row['team_name'];
          }

          
          return $teamArray;
          
        
          $DbConnection->Close();
        }

        /*
        功能描述：取得隊員清單
        參數：teamName
        建立日期：2015-03-18(Diego)
        修改日期：無
        */
        function getTeamMemberName($teamName){
          $DbConnection = new DbConnection();
          $dbh = $DbConnection->Open();

          $sql = "SELECT a.name FROM MS.`Competition_UserList` a,MS.`Competition_TeamUserList` b WHERE b.`team_name` = :teamName AND a.uid = b.team_uid";
          $sth = $dbh->prepare($sql);
          $sth->bindParam(':teamName', $teamName);
          $result = $sth->execute();
          $teamArray = array();
          while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $NameArray[] = $row['name'];
          }

          
          return $NameArray;
          
        
          $DbConnection->Close();
        }
  }
  
?>