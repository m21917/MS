$(document).ready(function(){

      function getUrlParam(name) {
          var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
          var r = window.location.search.substr(1).match(reg);  
          if (r != null)
              return unescape(r[2]);
          return null; 
      }


      // var times = 0;
      // var lightFont = setInterval(animateFont, 1000);
      // //var start_to_say_hi = setInterval("console.log('h1')",1000); 
      // function animateFont() {
      //   if(times == 0){
      //     $("#registLink").animate({color:"#FFF"}, 1000);
      //     times = 1;
      //   } else {
      //     $("#registLink").animate({color:'red'}, 500);
      //     times = 0;
      //   }
      
      // }

      for(var i = 1;i < 9;i++){
        $("#section"+i).hide();
      }
      
      function showTab(id) {
        for(var i = 1;i < 10;i++){
          $("#section"+i).hide();
        }
         $("#section"+id).show();
         $('#mycontent').slimscroll({destroy: true});
         setbodyHeight();
         //$("#"+id)
      }

      var page = getUrlParam('page');
      if(page == null) page = 1;
      
      showTab(page);
      $('#mycontent').slimscroll({destroy: true});
      setbodyHeight();
      function setbodyHeight(){
          var documentHeight = document.documentElement.clientHeight;
          var navbarHeight = $('#navbar').outerHeight(true);
          var headerHeight = $('#header').outerHeight(true);
          var footerHeight = $('#footer').outerHeight(true);
          // console.log("documentHeight" + documentHeight);
          // console.log("navbarHeight" + navbarHeight);
          // console.log("headerHeight" + headerHeight);
          // console.log("footerHeight" + footerHeight);
        
          var bodyHeight = documentHeight - navbarHeight - headerHeight - footerHeight - 25;
          //bodyHeight += 200;
          $('#mycontent').slimscroll({
            height:bodyHeight,
            allowPageScroll: true,
            scrollTo: 0
          });


      }
        
      $(window).resize(function() {
          
          $('#mycontent').slimscroll({destroy: true});
          setbodyHeight();

      });
      //   //$('#mycontent').scrollspy({ target: '#navbar-meun' });
        
      setbodyHeight();
      // function getPosition(){
      //    // console.log("#section1:"+$("#section1").position().top);
      //    // console.log("#section2:"+$("#section2").position().top);
      //    // console.log("#section3:"+$("#section3").position().top);
      //    // console.log("#section4:"+$("#section4").position().top);
      //    // console.log("#section5:"+$("#section5").position().top);
      //    // console.log("#section6:"+$("#section6").position().top);
      //    // console.log("#section7:"+$("#section7").position().top);
      //    // console.log("#section8:"+$("#section8").position().top);
      //    // console.log("#section9:"+$("#section9").position().top);
      //    // console.log("#section10:"+$("#section10").position().top);
      //    var position = [0];
      //    for(var i=0;i < 10;i++){
      //       var x= i+1;
      //       position[x] = $("#section"+x).position().top;
      //    }
      //    return position;
      // }
      
      // var positionArray = getPosition();

      // $('.dropdown-menu a').click(function(){

      //     console.log(location.href);
      //     var id = $(this).attr('value');
      //     showTab(id);
          
      //     $('.dropdown-menu li').removeClass('active');
      //     $(this).parent().addClass('active');

      // });

      $('#joinBtn').mouseover(function(){
          $('#joinBtn').attr('src','image/joinBtn_click.png');
      }).mouseleave(function(){
          $('#joinBtn').attr('src','image/joinBtn_link.png');
      }).mousedown(function(){
          $('#joinBtn').attr('src','image/joinBtn_hover.png');
      }).mouseup(function(){
          $('#joinBtn').attr('src','image/joinBtn_link.png');
      })

      $("#index_btn").addClass("active");
      //$("#index_btn").css("background-color","#5bc0de");

      
    
  });       
