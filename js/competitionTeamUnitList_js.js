
$(document).ready(function(){
	//console.log($(".systemTip").attr('role'));
	
 	


	var table = $('#unitListTable').dataTable( {
        "ajax": "AjaxCompetitionTeamUnitList.php",
        "info": true,
        "order": [],
        "columnDefs": [ {
      		"targets": [ 1 ],
      		className: "listUnitTitle", 
    	}],
    	"pageLength": 10,
        "lengthMenu": [ [10, 20, 30, -1], [10, 20, 30, "全部顯示"] ],
        "pagingType": "simple_numbers",
        "language": {
          "loadingRecords": "載入資料中，請稍後...",
        	"lengthMenu": " 每頁顯示 _MENU_ 資料",
        	"processing": "搜尋中請稍後...",
        	"zeroRecords": "沒有任何資料",
        	"search": "",
        	"info": "第 _PAGE_ 頁，共 _PAGES_ 頁，共有_TOTAL_筆資料" ,
        	"infoEmpty": "目前尚無資料",
        	"infoFiltered": "(所有資料共有_MAX_筆)",
    		"paginate": {
      			"next": "下一頁",
      			"previous":"上一頁",
      			"first":"第一頁",
      			"last":"最末頁"
    		}
  		},
  		"sDom": '<"toolbar"f>lrti<"mypagination"p>',
  		"fnCreatedRow": function( nRow, aData, iDataIndex ) {
        	$(nRow).attr('id', 'c'+aData['id']).attr('value',aData['id']);
        	if(aData['isSend']==1){
        		$(nRow).addClass('isSendTr');
        	}
    	},
    	"fnInitComplete": function (oSettings, json) {
             $('[data-toggle="tooltip"]').tooltip();
        }
    } );

    $("div.dataTables_filter").prepend('<label for="inputSearch" style="font-size:16px">搜尋教材:</label>');
	$("div.dataTables_filter").children().children().attr('placeholder','請輸入關鍵字');
	$("div.dataTables_filter").children().children().attr('id','inputSearch');
	$("div.dataTables_filter").children().children().addClass('form-control');
	var table = $('#unitListTable').DataTable();
	$('#unitListTable tbody').on( 'click', 'tr', function () {
		var data = table.row(this).data();
	    
	} );

	
	

	$("#teamUnitList_btn").addClass("active");
	// $(".insertUnit .glyphicon").hide();
	// $(".insertUnit").click(function(){
	// 	var taget = $(this);
	// 	var id = $(this).attr('value');
	// 	var teamName = checkSelect('joinUnitTeamName');
	// 	console.log(id+","+teamName);
	// 	if(id && teamName){
	// 		$.ajax({
	// 				url: "AjaxCompetition.php",
	// 				type: "POST",
	// 				data: {insertLoadTeamUnit: true, id: id, teamName:teamName},
	// 				success: function(data) {
	// 					console.log(data);
	// 					if(data)
	// 					taget.addClass('list-group-item-success').removeClass('insertUnit');
	// 					taget.find('span').show();
	// 					$("#systemTip_content").html("匯入教材成功！");
	// 					$(".systemTip").modal('show');
	// 					table.ajax.reload();
	// 				},
	// 				error: function(xresp) {
	// 					$("#systemTip_content").html("資料有誤，請重新操作！");
	// 					$(".systemTip").modal('show');
	// 				}
	// 		});
	// 	}
		
	// });

	initAddUnitForm();
	function initAddUnitForm(){
		$(".subField_label,.subField_select").hide();
		$("#teamName_ErrorMsg,#unitName_empty,#mainField_ErrorMsg,#subField_ErrorMsg,#grade_ErrorMsg,#KeyWord_empty").hide();
		$("#unitId").val("");
		$("#teamName option").first().attr('selected','selected');
		$("#unitName").val("");
		$("#mainField option").first().attr('selected','selected');
		$("#subField option").first().attr('selected','selected');
		$("input[name='grade']:checked").attr('checked',false);
		$("#keyWord1").val('');
		$("#keyWord2").val('');
		$("#keyWord3").val('');
		$("#keyWord4").val('');
		$("#keyWord5").val('');
		$('#myModalLabel').html('新增主題');
		$("#addUnit_Submit").html('新增主題');
		$('#cancel_addUnit').html('取消新增');
		
	}

	function checkRequired(objID) {
		
		if ($('#'+objID).val() == '') {
			$('span#'+objID+'_ErrorMsg').html("主題名稱不得為空!");
			$('span#'+objID+'_ErrorMsg').show();
			return false;
		}else {
			$('span#'+objID+'_ErrorMsg').hide();
			return $('#'+objID).val();
		}
	}

	function checkLength(objID) {
		if ($('#'+objID).val().length > 20) {
			$('span#'+objID+'_ErrorMsg').html("標題長度不得超過20字!");
			$('span#'+objID+'_ErrorMsg').show();
			return false;
		}else if($('#'+objID).val().length > 1){
			$('span#'+objID+'_ErrorMsg').hide();
			return true;
		}
	}

	function checkSelect(objID) {
		var value = $("#"+objID).val();
		if(value == 0) {
			$('span#'+objID+'_ErrorMsg').show();
			return false;
		} else {
			$('span#'+objID+'_ErrorMsg').hide();
			return value;
		}
	}

	function checkRadio(nameID) {

		if ($('input[name='+nameID+']:checked').length == '0') {
			$('span#'+nameID+'_ErrorMsg').show();
			return false;
		}else {
			$('span#'+nameID+'_ErrorMsg').hide();
			return $('input[name='+nameID+']:checked').val();
		}
	}


	$('#unitName').on('blur', function(){
		var show = $(this).attr('id');
		checkRequired(show);
		checkLength(show);
	});


	$('#joinUnitTeamName,#teamName,#mainField,#subField').on('blur', function(){
		var show = $(this).attr('id');
		checkSelect(show);
	});

	$('#teamName,#mainField,#subField').on('change', function(){
		var show = $(this).attr('id');
		checkSelect(show);
	});

	
	$('#myModal').on('hidden.bs.modal', function (e) {  //當新增/更新主題視窗結束時...
  		// do something...
  		initAddUnitForm();
	});

	$('#joinUnitTeamName').on('change', function(){
		var show = $(this).attr('id');
		var teamName = checkSelect(show);
		if(teamName != ''){
			$.ajax({
				url: "AjaxCompetition.php",
				type: "POST",
				data: {LoadTeamUnitListFromMSSC: true,teamName:teamName},
				success: function(data) {
					//console.log(data);
					var jsonData = $.parseJSON(data);
					$("#insertUnitList").empty();
					//console.log("length="+jsonData.length);
					for (var i = 0; i < jsonData.length; i++) {
						var a = $('<a>'+jsonData[i].name+'</a>');
						a.attr('value',jsonData[i].id).addClass('list-group-item').attr('id','loadUnit'+jsonData[i].id);
						var span = $('<span>'+jsonData[i].field1+">>"+jsonData[i].field2+'</span>');
						span.addClass('gray-font').css('margin-left','10px');
						a.append(span);
						var span2 = $('<span class="glyphicon glyphicon-ok pull-right" aria-hidden="true"></span>');
						span2.hide();
						a.append(span2);
						if(jsonData[i].exist && jsonData[i].exist.enable != 0){
							a.addClass('list-group-item-success');
							span2.show();
						} else {
							a.addClass('insertUnit');
						}
						$("#insertUnitList").append(a);
						
					}
					$(".insertUnit").click(function(){
							var taget = $(this);
							var id = $(this).attr('value');
							var teamName = checkSelect('joinUnitTeamName');

							//console.log(id+","+teamName);
							if(id && teamName){
								$.ajax({
										url: "AjaxCompetition.php",
										type: "POST",
										data: {insertLoadTeamUnit: true, id: id, teamName:teamName},
										success: function(data) {
											//console.log(data);
											if(data)
											taget.addClass('list-group-item-success').removeClass('insertUnit');
											taget.find('span').show();
											// $("#systemTip_content").html("匯入教材成功！");
											// $(".systemTip").modal('show');
											alert("匯入教材成功！");
											table.ajax.reload();
										},
										error: function(xresp) {
											$("#systemTip_content").html("資料有誤，請重新操作！");
											$(".systemTip").modal('show');
										}
								});
							}
							
						});
					// console.log("height="+$("#insertUnitList").height());
					var h = $("#loadUnitModal .modal-content").height()+100;
					h = h < 1100?1100:h;
					console.log("height="+h);
					$(".modal-backdrop").css("height",h);
				},
				error: function(xresp) {
						
				}
			});
		} else {
			$("#insertUnitList").empty();
		} 
		
		
	});


	$('#exampleModal').on('hidden.bs.modal', function (e) {  //當更新範例文章視窗結束時...
  		// do something...
  		initAddUnitForm();
  		$('ul.tabs li').removeClass('selected');
  		$('span#articleTitle_empty').hide();
  		$('span#exampleContent_empty').hide();
  		$('span#articleFromOption_empty').hide();
  		table.ajax.reload();
	});
	
	$('#guidanceModal').on('hidden.bs.modal', function (e) {  //當新增/更新啟發問題視窗結束時...
  		// do something...
  		initAddUnitForm();
  		$("#guidContent").val('');
  		$('span#guidContent_empty').hide();
  		$('.guidance-wordCount div').html(0);
  		table.ajax.reload();
	});

	$('#changeStatusModal').on('hidden.bs.modal', function (e) {  //當更變主題狀態視窗結束時...
  		// do something...
  		initAddUnitForm();
	});

	$('#loadUnitModal').on('hidden.bs.modal', function (e) {  //當引用教材庫主題清單視窗結束時...
  		// do something...
  		$('#joinUnitTeamName option').first().attr('selected','selected');
  		$("#insertUnitList").empty();
	});

	$(document).on('click', '.editExample', function(){
		
		var unit_id = $(this).parents('tr').attr('value');
		//console.log("unit_id"+unit_id);
		//console.log("editExample checked");
		$.ajax({
					url: "AjaxCompetition.php",
					type: "POST",
					data: {loadTeamUnitInfo: true, unit_id: unit_id,type:1},
					success: function(data) {
						//console.log(data);
						var jsonData = $.parseJSON(data);
						$("#unitId").val(jsonData[0].id);
						$(".h2_title").html(jsonData[0].name);
						$("#subfieldinfo").html(jsonData[0].field1+">>"+jsonData[0].field2);
						var _showTab = 0;
						var _defaultTab = $('ul.tabs li').eq(_showTab).addClass('selected');
						$(_defaultTab.find('span').attr('value')).siblings().hide();


						var _clickTab = 1;  //正在編輯的範文
						var _unit = jsonData[0].id;

						checkExampleStatus(_unit);  //檢查四篇範文存在狀態
						LoadArticleExample(_unit, _clickTab);  //檢查是否有第一篇範文(預設第一篇)
						$("#exampleModal").modal('show');
					},
					error: function(xresp) {
						$("#systemTip_content").html("資料有誤，請重新操作！");
						$(".systemTip").modal('show');
					}
		});
		
	});

	$(document).on('click', '.editGuidance', function(){
		
		var unit_id = $(this).parents('tr').attr('value');
		// console.log("unit_id"+unit_id);
		// console.log("editGuidance checked");
		$.ajax({
					url: "AjaxCompetition.php",
					type: "POST",
					data: {getTeamGuidance: true, id: unit_id},
					success: function(data) {
						//console.log(data);
						var jsonData = $.parseJSON(data);
						

						$("#unitId").val(unit_id);
						$(".guidance_title").html(jsonData[0].name);
						$(".guidance_subfieldinfo").html(jsonData[0].field1+">>"+jsonData[0].field2);
						$("#updateGuidance_Submit").html('新增啟發問題');
						if(jsonData[1].gid){
							//console.log(jsonData[1].gid);
							$("#guidContent").attr('gid',jsonData[1].gid).val(jsonData[1].content);
							$('.guidance-wordCount div').html(jsonData[1].content.length);
							$("#updateGuidance_Submit").html('更新啟發問題');
							
						}
						$("#guidanceModal").modal('show');
					},
					error: function(xresp) {
						$("#systemTip_content").html("資料有誤，請重新操作！");
						$(".systemTip").modal('show');
					}
		});
		
	});
	
	$(document).on('click', '#updateGuidance_Submit', updateGuidance);

	function updateGuidance(){
		var uid = $("#unitId").val();
		var gid = $("#guidContent").attr('gid')?$("#guidContent").attr('gid'):0;
		var content = checkRequired('guidContent');
		// console.log("uid="+uid);
		// console.log("gid="+gid);
		// console.log("content="+content);
		if (uid && content){
			$.ajax({
					url: "AjaxCompetition.php",
					type: "POST",
					data: {newTeamGuidance: true, cmsunit_id: uid,gid:gid,guidance:content},
					success: function(data) {
						//console.log(data);
						var jsonData = $.parseJSON(data);
						$("#guidContent").attr('gid',jsonData.gid).val(jsonData.content);
						$('.guidance-wordCount div').html(jsonData.content.length);
						$("#guidanceModal").modal('hide');
						$("#systemTip_content").html("啟發問題編輯完成！");
						$(".systemTip").modal('show');
						//$("#guidanceModal").modal('show');
					},
					error: function(xresp) {
						$("#systemTip_content").html("資料有誤，請重新操作！");
						$(".systemTip").modal('show');
					}
			});
		}
	}

	$("#addUnit_Submit").on("click",function(){		//新增主題送出表單
		var id = $('#unitId').val();
		var teamName = checkSelect('teamName');
		var unit = checkRequired('unitName');
		var unit_length = checkLength('unitName');
		var field1 = checkSelect('mainField');
		var field2 = checkSelect('subField');
		var grade = checkRadio('grade');
		var keywordArray = [$("#keyWord1").val(),$("#keyWord2").val(),$("#keyWord3").val(),$("#keyWord4").val(),$("#keyWord5").val()];
		var keyWord = '';
		var checkNum = 0; //檢查關鍵字數量
		$.each(keywordArray,function(key, value){
			if(value != ''){
				keyWord += value +",";
				checkNum++;
			}
		});
		if(checkNum < 3){
			$('span#KeyWord_empty').show();
		}else {
			$('span#KeyWord_empty').hide();
		}
		keyWord = keyWord.substr(0,keyWord.length-1);
		//console.log("KeyWord:"+keyWord);
		if (teamName && unit && unit_length && field1 && field2 && grade && checkNum >= 3) {
			$.ajax({
				url: "AjaxCompetition.php",
				type: "POST",
				data: {newTeamUnit:true, teamName:teamName, id:id, unit:unit, field1:field1, field2:field2, grade:grade,keyWord:keyWord},
				success: function(data) {
					//console.log(data);
					var jsonData = $.parseJSON(data);
					if (jsonData) {
						//console.log('jsonData:'+jsonData);
						table.ajax.reload();
						$("#myModal").modal('hide');
						if(id != ''){
							$("#systemTip_content").html("更新主題成功");
						} else {
							$("#systemTip_content").html("新增主題成功");
						}
						
						$(".systemTip").modal('show');
					} else {
						//console.log('jsonData:'+jsonData);
						$("#systemTip_content").html("資料有誤，請重新操作！");
						$(".systemTip").modal('show');

					}
				},
				error: function(xresp) {
					$("#systemTip_content").html("資料有誤，請重新操作！");
					$(".systemTip").modal('show');
				}
			});
		}
		
	});

	

	$("#mainField").on("change",function(){
		var mainField = $(this).val();
		loadSubFieldList(mainField,0);
	});

	function loadSubFieldList(mainIndex,subIndex){

		var sub = $('#subField');
		$.ajax({
			url: "AjaxCompetition.php",
			type: "POST",
			data: {getSubField: true, field: mainIndex},
			success: function(data) {
				//console.log(data);
				var jsonData = $.parseJSON(data);
				sub.empty().append($('<option></option>').attr('value', '0').text('請選擇...'));
				// console.dir(jsonData);
				$.each(jsonData, function(key, value) {
					if(subIndex !=0){
						if(subIndex == key)
							sub.append($('<option></option>').attr('value', key).text(value).prop('selected','selected'));
						else
							sub.append($('<option></option>').attr('value', key).text(value));
					} else {
						sub.append($('<option></option>').attr('value', key).text(value));
					}
					
				});
				$(".subField_label,.subField_select").show();
			},
			error: function(xresp) {
				alert("資料有誤，請重新操作！");
			}
		});

	}

	//顯示選單
	$(document).on('mouseenter', 'table.dataTable tbody tr', function(){  
			var _char = $(this).attr('id');
			$('#'+_char+' .menu-visable-on-select').css('visibility', 'visible');
	});
	$(document).on('mouseleave', 'table.dataTable tbody tr', function(){  
		var _char = $(this).attr('id');
			$('#'+_char+' .menu-visable-on-select').css('visibility', 'hidden');
	});

	$(document).on('click', 'span.small-link', function(){
		var action = $(this).attr('kind');
		var unit_id = $(this).parents('tr').attr('value');

		//console.log(action+','+unit_id);
		switch(action) {
			case 'edit':
				$.ajax({
					url: "AjaxCompetition.php",
					type: "POST",
					data: {loadTeamUnitInfo: true, unit_id: unit_id,type:0},
					success: function(data) {
						//console.log(data);
						var jsonData = $.parseJSON(data);
						$("#teamName option[value="+jsonData[0].teamName+"]").attr('selected','selected');
						$("#unitId").val(jsonData[0].id);
						$("#unitName").val(jsonData[0].name);
						$("#mainField option[value="+jsonData[0].field1+"]").attr('selected','selected');
						loadSubFieldList(jsonData[0].field1,jsonData[0].field2);
						//$("input[name='grade']").attr('checked',false);
						$.each($('input[name="grade"]'),function(){
							var inputValue = $(this).prop('value');
							if(inputValue == jsonData[0].grade){
								$(this).prop('checked',true);
							}
							
						});
						$("#keyWord1").val(jsonData[0].keyWord1);
						$("#keyWord2").val(jsonData[0].keyWord2);
						$("#keyWord3").val(jsonData[0].keyWord3);
						$("#keyWord4").val(jsonData[0].keyWord4);
						$("#keyWord5").val(jsonData[0].keyWord5);
						$('#myModalLabel').html('更新主題');
						$("#addUnit_Submit").html('更新主題');
						$('#cancel_addUnit').html('取消更新');
						$("#myModal").modal('show');
					},
					error: function(xresp) {
						$("#systemTip_content").html("資料有誤，請重新操作！");
						$(".systemTip").modal('show');
					}
				});

			break;
			case 'changeStage':
				var unit_id = $(this).parents('tr').attr('value');
				$("#unitId").val(unit_id);
				$.ajax({
							url: "AjaxCompetition.php",
							type: "POST",
							data: {getTeamUnitStatus: true, unit_id: unit_id},
							success: function(data) {
								//console.log(data);
								var jsonData = $.parseJSON(data);
								if(jsonData == 0){
									$('span#nowStatus').html('已關閉').removeClass('green-font').addClass('gray-font');
								} else {
									$('span#nowStatus').html('已開啟').removeClass('gray-font').addClass('green-font');
								}
								$('span#nowStatus').attr('value',jsonData);
								$("#changeStatusModal").modal('show');
							},
							error: function(xresp) {
								$("#systemTip_content").html("資料有誤，請重新操作！");
								$(".systemTip").modal('show');
							}
				});
				
			break;
			case 'delete':
				$.ajax({
							url: "AjaxCompetition.php",
							type: "POST",
							data: {getTeamDeleteType: true, unit_id: unit_id},
							success: function(data) {
								//console.log("DeleteType="+data);
								$("#deleteID").val(unit_id);
								if(data == "true"){	//若該主題已投稿至儲文所
									$("#deleteType").val(0);
									$("#deleteAlert_content").html("當您刪除該主題時，也會同時取消該主題的投稿行為，您確定要這麼做?");
									$(".deleteAlert").modal('show');
								} else {	//若該主題尚未投稿
									$("#deleteType").val(1);
									$("#deleteAlert_content").html("您確定要刪除該主題?");
									$(".deleteAlert").modal('show');
								}
							},
							error: function(xresp) {
								$("#systemTip_content").html("資料有誤，請重新操作！");
								$(".systemTip").modal('show');
							}
				});
				
			break;
		}
	});

	$("#deleteUnitSubmit").on("click",function(){
		$(".deleteAlert").modal('hide');
		var deleteType = $("#deleteType").val();
		var unit_id = $("#deleteID").val();
		// console.log("deleteType="+deleteType);
		// console.log("deleteID="+unit_id);
		$.ajax({
					url: "AjaxCompetition.php",
					type: "POST",
					data: {deleteTeamUnit: true, unit_id: unit_id},
					success: function(data) {
						
						var jsonData = $.parseJSON(data);
						var a = $("a#loadUnit"+jsonData.CMSUnit_id);
						a.removeClass('list-group-item-success').addClass('insertUnit');
						$("a#loadUnit"+jsonData.CMSUnit_id+" .glyphicon").hide();
						table.ajax.reload();
						$("#systemTip_content").html("資料刪除成功!");
						$(".systemTip").modal('show');
					},
					error: function(xresp) {
						$("#systemTip_content").html("資料有誤，請重新操作！");
						$(".systemTip").modal('show');
					}
		});
		if(deleteType == 0){
			$.ajax({
				url: "AjaxCompetition.php",
				type: "POST",
				data: {cancelSendTeamArticle:true, id:unit_id},
					success: function(data) {
						var jsonData = $.parseJSON(data);
						//console.log("log="+jsonData);
						if (jsonData) {
							table.ajax.reload();
							
						} else {
							
						}
					},
					error: function(xresp) {
						
					}
			});
			
		}
	});
	
	$(document).on('click', '#changeStatus_Submit', changeStatus);

	function changeStatus(){
		var unit_id = $("#unitId").val();
		var status = $('span#nowStatus').attr('value');
		//console.log("unit_id="+unit_id+",status="+status);
		$.ajax({
					url: "AjaxCompetition.php",
					type: "POST",
					data: {changeTeamUintStatus: true, id: unit_id,status:status},
					success: function(data) {
						table.ajax.reload();
						$("#changeStatusModal").modal('hide');
						$("#systemTip_content").html("主題狀態變更成功!");
						$(".systemTip").modal('show');
					},
					error: function(xresp) {
						$("#systemTip_content").html("資料有誤，請重新操作！");
						$(".systemTip").modal('show');
					}
		});
		
	}


	function checkRequired(objID) {
		
		if ($('#'+objID).val() == '') {
			$('span#'+objID+'_empty').show();
			return false;
		}else {
			$('span#'+objID+'_empty').hide();
			return $('#'+objID).val();
		}
	}

	function checkLength(objID) {
		if ($('#'+objID).val().length > 20) {
			$('span#'+objID+'_overload').show();
			return false;
		}else {
			$('span#'+objID+'_overload').hide();
			return true;
		}
	}

	function checkSelected(objID) {

		if ($('#'+objID).val() == '0') {  //required
			$('span#'+objID+'_empty').show();
			return false;
		}else {
			$('span#'+objID+'_empty').hide();
			return $('#'+objID).val();
		}
	}

	$(document).on('blur', '#articleTitle, #exampleContent,#guidContent', function(){

		var show = $(this).attr('id');
		checkRequired(show);
	});
	$(document).on('change', '#articleFromOption', function(){

		if ($(this).val() == 1) {
			$('#articleFrom').val('無');
			$('#articleFromOption_empty').hide();
		}else {
			$('#articleFrom').val('');
		}
	});
	$(document).on('blur', '#articleFrom', function(){
		var showId = $(this).attr('id');
		if ($('#articleFromOption').val() == '1') { $(this).val('無'); }
		checkFromRequired(showId);
	});

	function checkFromRequired(objID) {

		if ($('#'+objID).val() == '') {
			$('span#articleFromOption_empty').show();
			return false;
		}else {
			if ($('#articleFromOption').val() == '0') {
				$('span#articleFromOption_empty').show();
			}else {
				$('span#articleFromOption_empty').hide();
			}
			return $('#'+objID).val();
		}
	}
	// 當 li 頁籤被點擊時...
	$('ul.tabs li').on('click', function() {
		var $this = $(this);
		//console.log("this.value="+$this.find('span').attr('value'));
		var _unit = $('#unitId').val();
		var _clickTab = $this.find('span').attr('value');
		$('span#articleTitle_empty').hide();
  		$('span#exampleContent_empty').hide();
  		$('span#articleFromOption_empty').hide();
		$this.addClass('selected').siblings('.selected').removeClass('selected');
		LoadArticleExample(_unit, _clickTab);
	});

	$(document).on('click', '#updateExample_Submit', tabSaveVerify);
	function tabSaveVerify() {
		//表單驗證
		var id = $('#unitId').val();
		var	clickTab = $("ul.tabs li.selected").find('span').attr('value');
		var article = checkRequired('articleTitle');
		var content = checkRequired('exampleContent');
		var fromOption = checkSelected('articleFromOption');
		var from = checkFromRequired('articleFrom');
		//console.dir(id+', '+clickTab+', '+article+', '+content+', '+fromOption+', '+from);

		if (id && clickTab && article && content && fromOption && from) {
			$.ajax({
				url: "AjaxCompetition.php",
				type: "POST",
				data: {newTeamExample:true, id:id, exampleNums:clickTab, article:article, content:content, fromOption:fromOption, from:from},
				success: function(data) {
					var jsonData = $.parseJSON(data);
					if (jsonData) {
						$('span#s'+clickTab).css('background-image', 'url(image/onebit.png)');
						alert('更新範文成功！');
						// $("#systemTip_content").html('更新範文成功！');
						// $(".systemTip").modal('show');
					} else {
						$("#systemTip_content").html('資料有誤，請重新操作！');
						$(".systemTip").modal('show');
					}
				},
				error: function(xresp) {
					$("#systemTip_content").html('資料有誤，請重新操作！');
					$(".systemTip").modal('show');
				}
			});
		}
	}

	

	function checkExampleStatus(unit) {
		//console.log(unit);
		$.ajax({
			url: "AjaxCompetition.php",
			type: "POST",
			data: {checkTeamExampleStatus:true, id:unit},
			success: function(data) {
				var jsonData = $.parseJSON(data);

				// console.dir(jsonData);
				for (var i=1; i<=4; i++) {
					//console.log($.inArray(i.toString(), jsonData));
					if ($.inArray(i.toString(), jsonData) != -1) {
						$('span#s'+i).css('background-image', 'url(image/onebit.png)');
					} else {
						$('span#s'+i).css('background-image', 'url(image/onebit_36.png)');
					}
				}
			},
			error: function(xresp) {
				alert("操作錯誤，請確認網路狀態！");
			}
		});
	}

	function LoadArticleExample(unit, tab) {  //取得範文內容

		$.ajax({
			url: "AjaxCompetition.php",
			type: "POST",
			data: {checkTeamExample:true, id:unit, exampleNums:tab},
			success: function(data) {
				var jsonData = $.parseJSON(data);
				// console.dir(jsonData.article);
				if (jsonData.article != null) {
					$('#articleTitle').val(jsonData.article);
					$('#exampleContent').val(jsonData.content);
					$('div#wordCount').empty().append(jsonData.content.length);  //字數統計
					$('#articleFromOption').val(jsonData.resource_type);
					$('#articleFrom').val(jsonData.resource);

					$('span#s'+tab).css('background-image', 'url(image/onebit.png)');
					$('#updateExample_Submit').html('更新範例文章');
				} else {
					$('#articleTitle').val('');
					$('#exampleContent').val('');
					$('div#wordCount').empty().append(0);  //字數統計
					$('#articleFromOption').val(0);
					$('#articleFrom').val('');
					$('span#s'+tab).css('background-image', 'url(image/onebit_36.png)');
					$('#updateExample_Submit').html('新增範例文章');
				}
			},
			error: function(xresp) {
				alert("操作錯誤，請確認網路狀態！");
			}
		});
	}

	$(document).on('input', '#exampleContent,#guidContent', function(){

		var areaText = $(this).val();
		var str = areaText.charAt(areaText.length-1);
		var myReg = new RegExp('[ㄅㄆㄇㄈㄉㄊㄋㄌㄍㄎㄏㄐㄑㄒㄓㄔㄕㄖㄗㄘㄙㄚㄛㄜㄝㄞㄟㄠㄡㄢㄣㄤㄥㄦㄧㄨㄩＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺ‧〔〕`U1234567890]', 'i');
		if (!str.match(myReg)) {
			// console.dir(areaText+', '+areaText.length);
			$('div#wordCount').empty().append(areaText.length);
		}
		
	});

	$(document).on('click', '.sendArticle', function(){

		var btn = $(this);
		var unit_id = btn.attr('aid');
		//console.log("id="+unit_id);
		$.ajax({
				url: "AjaxCompetition.php",
				type: "POST",
				data: {sendTeamArticle:true, id:unit_id},
				success: function(data) {
					var jsonData = $.parseJSON(data);
					//console.log("log="+jsonData);
					if (jsonData) {
						table.ajax.reload();
						$("#systemTip_content").html(jsonData);
						$(".systemTip").modal('show');
					} else {
						$("#systemTip_content").html(jsonData);
						$(".systemTip").modal('show');
					}
				},
				error: function(xresp) {
					$("#systemTip_content").html('資料有誤，請重新操作！');
					$(".systemTip").modal('show');
				}
		});
	});

	$(document).on('click', '.cancelSendArticle', function(){

		var btn = $(this);
		var unit_id = btn.attr('aid');
		//console.log("id="+unit_id);
		$.ajax({
				url: "AjaxCompetition.php",
				type: "POST",
				data: {cancelSendTeamArticle:true, id:unit_id},
				success: function(data) {
					var jsonData = $.parseJSON(data);
					//console.log("log="+jsonData);
					if (jsonData) {
						table.ajax.reload();
						$("#systemTip_content").html("文章投稿已取消");
						$(".systemTip").modal('show');
					} else {
						$("#systemTip_content").html('資料有誤，請重新操作！');
						$(".systemTip").modal('show');
					}
				},
				error: function(xresp) {
					$("#systemTip_content").html('資料有誤，請重新操作！');
					$(".systemTip").modal('show');
				}
		});
	});

});