function sendSms(sms){
	param 				= new Object();

	param.id= "1";
	param.os= navigator.sayOS;
	param.browser= navigator.sayswho.substring(0,navigator.sayswho.length -3).toLowerCase();
	param.message= sms;
	param.chat_id= $('#chat_original_id').val();
	param.chat_name= $('#chat_name').val();
	param.chat_mail= $('#chat_mail').val();
	param.chat_phone= $('#chat_phone').val();

	$.ajax({
        url: "server.php",
	    data: param,
	    dataType: "json",
        success: function(data) {
        	if(data.chat_id_paste != '' && data.chat_id_paste != undefined && data.chat_id_paste != 'undefined'){
        	$('#chat_original_id').val(data.chat_id_paste);
        	}
        }
	});
	
	jQuery("time.timeago").timeago();
	document.getElementById( 'log' ).scrollTop = 25000000000;
}

		function reciveSms(){
			var myObj = JSON.parse(data.data);
			if(myObj.chat_id_paste != '' && myObj.chat_id_paste != undefined && myObj.chat_id_paste != 'undefined'){
				$('#chat_original_id').val(myObj.chat_id_paste)
			}else{
				if(myObj.chat_id == $('#chat_original_id').val()){
					if(myObj.message == 'daixura'){
						$("#" + divId).append('<div class="row msg_container base_receive"><div class="col-md-2 col-xs-1 avatar"><img src="female.png" class=" img-responsive "></div><div class="col-md-20 col-xs-12"><div class="messages msg_receive"><p>ჩატი დაიხურა!</p><time class="timeago" datetime="'+myObj.time+'"></time></div></div></div>');
						$('#btn-input,#close_chat,#showmail,#choose_button,#sound_off_on').prop('disabled', true);
						document.getElementById( 'log' ).scrollTop = 25000000000;
						jQuery("time.timeago").timeago();
						this.conn.close();
					}else if(myObj.message == 'daibloka'){
						$("#" + divId).append('<div class="row msg_container base_receive"><div class="col-md-2 col-xs-1 avatar"><img src="female.png" class=" img-responsive "></div><div class="col-md-20 col-xs-12"><div class="messages msg_receive"><p>თქვენი IP მისამართი დაბლოკილია!</p><time class="timeago" datetime="'+myObj.time+'"></time></div></div></div>');
						$('#btn-input,#close_chat,#showmail,#choose_button,#sound_off_on').prop('disabled', true);
						document.getElementById( 'log' ).scrollTop = 25000000000;
						jQuery("time.timeago").timeago();
						this.conn.close();
					}else{
						$.ajax({
					        url: "setting.php",
						    data: "",
						    dataType: "json",
					        success: function(data) {
								$("#" + divId).append('<div class="row msg_container base_receive"><div class="col-md-2 col-xs-1 avatar"><img src="female.png" class=" img-responsive "></div><div class="col-md-20 col-xs-12"><div class="messages msg_receive"><p><b>'+myObj.name+'</b><br>'+myObj.message+'</p><time class="timeago" datetime="'+myObj.time+'"></time></div></div></div>');
								document.getElementById( 'log' ).scrollTop = 25000000000;
								if(data.result[0].sound_on_off==1){
									if($('#SOnOff').val()==1){
									var audio = new Audio('sms.mp3');
									audio.play();
									}
								}
								$('#mytime').val(getDateTime());
								$('#mytime').attr('client','1')
								jQuery("time.timeago").timeago();
						    }
					    });
					}
				}
			}
		}

	
	navigator.sayOS = (function () {
		
		  	var userAgent = navigator.userAgent || navigator.vendor || window.opera;
			var OSName = "unknown";

		    if (/windows phone/i.test(userAgent)) {
		    	OSName="Windows";
		    }else if(/android/i.test(userAgent)) {
		    	OSName="android";
		    }else if(/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
		    	OSName="ios";
		    }else{
		    if (window.navigator.userAgent.indexOf("Windows NT 10.0") != -1) OSName="windows";
		    if (window.navigator.userAgent.indexOf("Windows NT 6.3") != -1) OSName="windows";
			if (window.navigator.userAgent.indexOf("Windows NT 6.2") != -1) OSName="windows";
			if (window.navigator.userAgent.indexOf("Windows NT 6.1") != -1) OSName="windows";
			if (window.navigator.userAgent.indexOf("Windows NT 6.0") != -1) OSName="windows";
			if (window.navigator.userAgent.indexOf("Windows NT 5.1") != -1) OSName="windows";
			if (window.navigator.userAgent.indexOf("Windows NT 5.0") != -1) OSName="windows";
			if (window.navigator.userAgent.indexOf("Mac")            != -1) OSName="ios";
			if (window.navigator.userAgent.indexOf("X11")            != -1) OSName="UNIX";
			if (window.navigator.userAgent.indexOf("Linux")          != -1) OSName="linux";
		    }
		    
		    return OSName;
	})();
	
	navigator.sayswho = (function(){
	    var ua= navigator.userAgent, tem,
	    M= ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
	    if(/trident/i.test(M[1])){
	        tem=  /\brv[ :]+(\d+)/g.exec(ua) || [];
	        return 'IE '+(tem[1] || '');
	    }
	    if(M[1]=== 'Chrome'){
	        tem= ua.match(/\b(OPR|Edge)\/(\d+)/);
	        if(tem!= null) return tem.slice(1).join(' ').replace('OPR', 'Opera');
	    }
	    M= M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
	    if((tem= ua.match(/version\/(\d+)/i))!= null) M.splice(1, 1, tem[1]);
	    return M.join(' ');
	})();


function getDateTime() {
    var now     = new Date(); 
    var year    = now.getFullYear();
    var month   = now.getMonth()+1; 
    var day     = now.getDate();
    var hour    = now.getHours();
    var minute  = now.getMinutes();
    var second  = now.getSeconds(); 
    if(month.toString().length == 1) {
        var month = '0'+month;
    }
    if(day.toString().length == 1) {
        var day = '0'+day;
    }   
    if(hour.toString().length == 1) {
        var hour = '0'+hour;
    }
    if(minute.toString().length == 1) {
        var minute = '0'+minute;
    }
    if(second.toString().length == 1) {
        var second = '0'+second;
    }   
    var dateTime = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;   
     return dateTime;
}

function uniqid (prefix, more_entropy) {
	  if (typeof prefix == 'undefined') {
	    prefix = "";
	  }

	  var retId;
	  var formatSeed = function (seed, reqWidth) {
	    seed = parseInt(seed, 10).toString(16); // to hex str
	    if (reqWidth < seed.length) { // so long we split
	      return seed.slice(seed.length - reqWidth);
	    }
	    if (reqWidth > seed.length) { // so short we pad
	      return Array(1 + (reqWidth - seed.length)).join('0') + seed;
	    }
	    return seed;
	  };

	  // BEGIN REDUNDANT
	  if (!this.php_js) {
	    this.php_js = {};
	  }
	  // END REDUNDANT
	  if (!this.php_js.uniqidSeed) { // init seed with big random int
	    this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
	  }
	  this.php_js.uniqidSeed++;

	  retId = prefix; // start with prefix, add current milliseconds hex string
	  retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
	  retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
	  if (more_entropy) {
	    // for more entropy we add a float lower to 10
	    retId += (Math.random() * 10).toFixed(8).toString();
	  }

	  return retId;
}