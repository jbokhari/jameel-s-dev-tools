(function($, document, undefined){
	console.log("Test");
	var timer;
	var iframe;
	var count = 0;
	do_timer = function(){
		var timeout = setTimeout(function(){
			iframe = document.getElementById('content_ifr');
			count++;
			console.log("count is " + count);
			if ( ! iframe && count < 5 ){
				do_timer();
			} else if(iframe) {
				console.log("could not retreive Iframe");
				change_links();
			}	
		}, 100);
	}
	do_timer();
	change_links = function(){
		var iframe = document.getElementById('content_ifr');
		var innerDoc = iframe.contentDocument || iframe.contentWindow.document;
		var editor = innerDoc.getElementById('tinymce');
		var content = editor.innerHTML;
		console.log(content);
		var newcontent = content.replace(/\ssrc=\"\[homeurl\]/, 'src="' + JDT_Global.homeurl);
		console.log(newcontent);
		editor.innerHTML=newcontent;
	}
	$("a#content-tmce").on('click', function(){
		change_links();
	})
	// }, 3000);
})(jQuery, document);
