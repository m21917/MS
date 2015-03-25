$(document).ready(function(){

	$("#groupFormDiv").hide();
	var formType = 0;  //0 = 個人賽, 1 = 團體賽
	var nowStatus = $('#personBtn').attr('src');
	$("#personBtn").mouseover(function(){
		 $('#personBtn').attr('src','image/personBtnOn.png');
		 //$('#groupBtn').attr('src','image/groupBtnOff.png');
	}).mouseleave(function(){
          $('#personBtn').attr('src',nowStatus);
    }).mousedown(function(){
          $('#personBtn').attr('src','image/personBtnOn.png');
          $('#groupBtn').attr('src','image/groupBtnOff.png');
          nowStatus = "image/personBtnOn.png";
          nowStatus2 = "image/groupBtnOff.png";
          $("#personFormDiv").show();
          $("#groupFormDiv").hide();
          reset_GroupForm();
          formType = 0;
    });

    $("#submitBtn").click(function(){
    	if(formType == 0){
    		console.log('送出個人表單');
    		if(check_ReadRule()){  //驗證是否同意規定
    			var account = $("#personForm input[name='inputAccount']").val();
    			$.ajax({   //檢查是否重複報名個人組
							url: "AjaxCompetition.php",
							type: "POST",
							data: {personRegistCheck: true, account: account},
							success: function(data) {
								console.log(data);
								if(data == 'true'){
									$("#systemTip_content").html("您的帳號已經報名過了！");
									$(".systemTip").modal('show');
								} else if(data == 'false'){
									$("form[name='personForm']").submit();
								}
								
							},
							error: function(xresp) {
								$("#systemTip_content").html("資料有誤，請重新操作！");
								$(".systemTip").modal('show');
							}
				});

    		}
    		
    	} else if(formType == 1) {
    		console.log('送出團體表單');
    		//console.log($("#leaderAccount").attr('name'));
    		if(check_ReadRule()){  //驗證是否同意規定
    			$("form[name='groupForm']").submit();
    		}
    	}
    });


    var nowStatus2 = $('#groupBtn').attr('src');
	$("#groupBtn").mouseover(function(){
		 $('#groupBtn').attr('src','image/groupBtnOn.png');
		 //$('#personBtn').attr('src','image/personBtnOff.png');
	}).mouseleave(function(){
         $('#groupBtn').attr('src',nowStatus2);
    }).mousedown(function(){
         $('#groupBtn').attr('src','image/groupBtnOn.png');
		 $('#personBtn').attr('src','image/personBtnOff.png');
		 nowStatus = "image/personBtnOff.png";
		 nowStatus2 = "image/groupBtnOn.png";
		 $("#personFormDiv").hide();
         $("#groupFormDiv").show();
         reset_PersonForm();
         formType = 1;
    });

    var memberNum = 2;
    var newMemberForm1 = $(".memberinfo:last").clone();
    $("#addMember").click(function(){
    	console.log("新增成員");
    	if(memberNum >=2 && memberNum <= 3){
    		newMemberForm = newMemberForm1.clone();
    		newMemberForm.children().eq(1).children().eq(1).children().eq(0).val("").attr("name","memberAccount["+memberNum+"]").attr("id","memberAccount"+memberNum);	//帳號
    		newMemberForm.children().eq(2).children().eq(1).children().eq(0).val("").attr("name","memberName["+memberNum+"]");	//姓名
	    	newMemberForm.children().eq(3).children().eq(1).children().eq(0).children().eq(0).attr("name","memberSex["+memberNum+"]").prop('checked',true);		//性別:男
	    	newMemberForm.children().eq(3).children().eq(1).children().eq(1).children().eq(0).attr("name","memberSex["+memberNum+"]").prop('checked',false);	//性別:女
	    	newMemberForm.children().eq(4).children().eq(1).children().eq(0).val("").attr("name","memberTel["+memberNum+"]");	//聯絡電話
	    	newMemberForm.children().eq(5).children().eq(1).children().eq(0).val("").attr("name","memberEmail["+memberNum+"]");	//電子信箱
	    	newMemberForm.children().eq(6).children().eq(1).children().eq(0).children().eq(0).attr("name","memberKind["+memberNum+"]").prop('checked',true);	//身分別:教職員
	    	newMemberForm.children().eq(6).children().eq(1).children().eq(1).children().eq(0).attr("name","memberKind["+memberNum+"]").prop('checked',false);	//身分別:社會人士
	    	newMemberForm.children().eq(7).children().eq(1).children().eq(0).val("").attr("name","memberWorkPlace["+memberNum+"]");	//任職單位
	    	memberNum++;
	    	console.log(newMemberForm.children().eq(3).children().eq(1).children().eq(0).children().eq(0).attr("name"));
	    	newMemberForm.insertBefore("#memberBtnRow");
	    	$("input[name='memberAccount[1]'],input[name='memberAccount[2]'],input[name='memberAccount[3]']").unbind('change');
	    	$("input[name='memberAccount[1]'],input[name='memberAccount[2]'],input[name='memberAccount[3]']").on('change', function(){
		    	$("#someAccountTip").remove();
		    	console.log("remove");
			});

    	} else {
    		$("#systemTip_content").html("隊員人數最多4人!");
			$(".systemTip").modal('show');
    		
    	}
    	
    	
    	
    });

    $("#deleteMember").click(function(){
    	console.log("刪除成員");
    	if(memberNum == 2){
    		$("#systemTip_content").html("隊員人數最少2人!");
			$(".systemTip").modal('show');
    	} else {
    		//var newMemberForm = $(".memberinfo:last");
	    	$(".memberinfo:last").remove();
	    	memberNum--;
    	}
    	
    });
    $.validator.addMethod(
        "regex",
        function(value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        },
        "Please check your input."
	);

    var emptyErrorText = "欄位不得為空!";
    var accountErrorText = "該帳號不存在!";
    var telErrorText = "請輸入正確的手機格式!";
    var emailErrorText = "Email格式不正確!";
    $("#personForm").validate({ 
    	onkeyup: false,   
	    rules: {
	        inputName: {
	            required: true
	        },
	        inputTel: {
	        	required: true,
	        	minlength: 10,
	            maxlength: 10,
	            digits:true
	        },
	        inputEmail: {
	        	required: true,
	        	email:true
	        },
	        inputWorkPlace:{
	        	required: true
	        }
	        
	    },
	  messages: {
	   		inputName: {
	   			required:emptyErrorText
	   		},
	   		inputTel: {
	        	required: emptyErrorText,
	        	minlength: telErrorText,
	            maxlength: telErrorText,
	            digits:telErrorText
	        },
	        inputEmail:{
	        	required: emptyErrorText,
	        	email:emailErrorText
	        },
	        inputWorkPlace:emptyErrorText

	  },
	    highlight: function(element) {
	        //var id_attr = "#" + $( element ).attr("id") + "1";
	        $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
	        $(element).next().removeClass('glyphicon-ok').addClass('glyphicon-remove');         
	    },
	    unhighlight: function(element) {
	        //var id_attr = "#" + $( element ).attr("id") + "1";
	        $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
	        $(element).next().removeClass('glyphicon-remove').addClass('glyphicon-ok');         
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	        if(element.length) {
	           	element.parent().next().append(error);
	            //error.insertAfter(element.parent().next());
	        } else {
	            element.parent().next().append(error);
	            //error.insertAfter(element.parent().next());
	        }
	   	}
 	});

    $("#groupForm").validate({ 
    	onkeyup: false,   
	    rules: {
	        inputTeamName: {
	            required: true,
	            remote: {
				    url: "AjaxCompetition.php",     //後台處理程序
				    type: "POST",               //數據發送方式
				    data: {                     //要傳遞的數據
				        teamNameCheck:true, 
				        value:function() {
				            return $("#inputTeamName").val();
				        }
				    }
				},
				regex:"^[\u4e00-\u9fa5_a-zA-Z0-9]+$"
	        },
	        'memberAccount[1]':{
	        	required: true,
	        	remote: {
				    url: "AjaxCompetition.php",     //後台處理程序
				    type: "POST",               //數據發送方式
				    data: {                     //要傳遞的數據
				        accountCheck:true, 
				        value:function() {
				            return $("#memberAccount1").val();
				        }
				    }
				}
				
	        },
	       	'memberAccount[2]':{
	       		required: true,
	        	remote: {
				    url: "AjaxCompetition.php",     //後台處理程序
				    type: "POST",               //數據發送方式
				    data: {                     //要傳遞的數據
				        accountCheck:true, 
				        value:function() {
				            return $("#memberAccount2").val();
				        }
				    }
				},
				
	        },
	        'memberAccount[3]':{
	        	required: true,
	        	remote: {
				    url: "AjaxCompetition.php",     //後台處理程序
				    type: "POST",               //數據發送方式
				    data: {                     //要傳遞的數據
				        accountCheck:true, 
				        value:function() {
				            return $("#memberAccount3").val();
				        }
				    }
				},
				
	        },
	        'memberName[0]':{required: true},'memberName[1]':{required: true},'memberName[2]':{required: true},'memberName[3]':{required: true},
	        'memberTel[0]':{
	        	required: true,
	        	minlength: 10,
	            maxlength: 10,
	            digits:true
	        },
	        'memberTel[1]':{
	        	required: true,
	        	minlength: 10,
	            maxlength: 10,
	            digits:true
	        },
	        'memberTel[2]':{
	        	required: true,
	        	minlength: 10,
	            maxlength: 10,
	            digits:true
	        },
	        'memberTel[3]':{
	        	required: true,
	        	minlength: 10,
	            maxlength: 10,
	            digits:true
	        },
	        'memberEmail[0]':{required: true,email:true},'memberEmail[1]':{required: true,email:true},'memberEmail[2]':{required: true,email:true},'memberEmail[3]':{required: true,email:true},
	        'memberWorkPlace[0]':{required: true},'memberWorkPlace[1]':{required: true},'memberWorkPlace[2]':{required: true},'memberWorkPlace[3]':{required: true},
	    },
	  messages: {
	   		inputTeamName: {
	   			required:emptyErrorText,
	   			remote:"隊伍名稱已有人使用。",
	   			regex:"隊伍名稱內不得有標點符號。"
	   		},
	   		'memberAccount[1]':{
	   			remote:accountErrorText, required:emptyErrorText
	   		},
	   		'memberAccount[2]':{
	   			remote:accountErrorText, required:emptyErrorText
	   		},
	   		'memberAccount[3]':{
	   			remote:accountErrorText, required:emptyErrorText
	   		},
	   		'memberName[0]':emptyErrorText,'memberName[1]':emptyErrorText,'memberName[2]':emptyErrorText,'memberName[3]':emptyErrorText,
	   		'memberTel[0]':{
	   			minlength:telErrorText, maxlength:telErrorText, digits:telErrorText,required:emptyErrorText
	   		},
	   		'memberTel[1]':{
	   			minlength:telErrorText, maxlength:telErrorText, digits:telErrorText,required:emptyErrorText
	   		},
	   		'memberTel[2]':{
	   			minlength:telErrorText, maxlength:telErrorText, digits:telErrorText,required:emptyErrorText
	   		},
	   		'memberTel[3]':{
	   			minlength:telErrorText, maxlength:telErrorText, digits:telErrorText,required:emptyErrorText
	   		},
	   		'memberEmail[0]':{
	   			required:emptyErrorText, email:emailErrorText
	   		},
	   		'memberEmail[1]':{
	   			required:emptyErrorText, email:emailErrorText
	   		},
	   		'memberEmail[2]':{
	   			required:emptyErrorText, email:emailErrorText
	   		},
	   		'memberEmail[3]':{
	   			required:emptyErrorText, email:emailErrorText
	   		},
	   		'memberWorkPlace[0]':emptyErrorText,'memberWorkPlace[1]':emptyErrorText,'memberWorkPlace[2]':emptyErrorText,'memberWorkPlace[3]':emptyErrorText,
	  },
	    highlight: function(element) {
	        //var id_attr = "#" + $( element ).attr("id") + "1";
	        $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
	        $(element).next().removeClass('glyphicon-ok').addClass('glyphicon-remove');         
	    },
	    unhighlight: function(element) {
	        //var id_attr = "#" + $( element ).attr("id") + "1";
	        $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
	        $(element).next().removeClass('glyphicon-remove').addClass('glyphicon-ok'); 
	        $(element).parent().next().children().hide();  
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	        if(element.length) {
	           	element.parent().next().append(error);
	            //error.insertAfter(element.parent().next());
	        } else {
	            element.parent().next().append(error);
	            //error.insertAfter(element.parent().next());
	        }
	   	},
		submitHandler: function(form) {
		    // do other things for a valid form
		    $("#leaderAccount").attr('disabled', false);
		    if(checkAccountSome()){
		    	form.submit();
		    }
		    
		}
 	});

	function checkAccountSome(){
		var myarray = [];
		myarray[0] = $("#leaderAccount").val();
		myarray[1] = $("#memberAccount1").val();
		if(memberNum >= 3){
			myarray[2] = $("#memberAccount2").val();
		}
		if(memberNum==4){
			myarray[3] = $("#memberAccount3").val();
		}
		console.log("array.length="+myarray.length);
		var some = 0;
		var errorObj = '';
		for(var i=0;i < myarray.length;i++){
			for(var j=0;j < myarray.length;j++){
				if(i == j){
					continue;
				} else {
					console.log("array["+i+"]="+myarray[i]+",obj["+j+"]="+myarray[j]);
					if(myarray[i] == myarray[j]){
						some = 1;
						switch(j){
							case 1:
								errorObj = '#memberAccount1';
							break;
							case 2:
								errorObj = '#memberAccount2';
							break;
							case 3:
								errorObj = '#memberAccount3';
							break;
						}
						console.log("break");
						break;
					}
				}
			}
			if(some == 1){
				break;
			}
		}

		if(some == 0){
			return true;
		} else {
			
			$(errorObj).closest('.form-group').removeClass('has-success').addClass('has-error');
	        $(errorObj).next().removeClass('glyphicon-ok').addClass('glyphicon-remove');
	        var error = $("<span></span>").addClass('help-block').html("您輸入了相同的帳號!").attr('id','someAccountTip');
	        $(errorObj).parent().next().append(error);
	        $(errorObj).focus();

			return false;
		}
	}

	$("input[name='memberAccount[1]'],input[name='memberAccount[2]'],input[name='memberAccount[3]']").on('change', function(){
    	$("#someAccountTip").remove();
    	console.log("remove");
	});

	function reset_PersonForm(){
		$("#personForm input[type='text']").val('');
		$("#personForm input[type='text']").closest('.form-group').removeClass('has-success').removeClass('has-error');
		$("#personForm input[type='text']").next().removeClass('glyphicon-ok').removeClass('glyphicon-remove');
		
		$("#personForm input[type='radio'][value='1']").prop('checked',true);
		$("#personForm input[type='radio'][value='0']").prop('checked',false);
		$("#personForm input[type='text']").parent().next().children().hide();
		$("#inputReadRule").prop('checked',false);
		$(".inputReadRule_error").hide();
	}

	function reset_GroupForm(){
		$("#groupForm input[type='text']").val('');
		$("#groupForm input[type='text']").closest('.form-group').removeClass('has-success').removeClass('has-error');
		$("#groupForm input[type='text']").next().removeClass('glyphicon-ok').removeClass('glyphicon-remove');
		//console.log($("#groupForm input[type='radio'][value='1']").attr('name'));
		$("#groupForm input[type='radio'][value='1']").prop('checked',true);
		$("#groupForm input[type='radio'][value='0']").prop('checked',false);
		$("#groupForm input[type='text']").parent().next().children().hide();
		$("#inputReadRule").prop('checked',false);
		$(".inputReadRule_error").hide();
	}

	function check_ReadRule(){
		//console.log("readRule ="+$("#inputReadRule").prop('checked'));
		if($("#inputReadRule").prop('checked')){
			$(".inputReadRule_error").hide();
		} else {
			$("#inputReadRule").focus();
			$(".inputReadRule_error").show();
		}
		return $("#inputReadRule").prop('checked');
	}


});