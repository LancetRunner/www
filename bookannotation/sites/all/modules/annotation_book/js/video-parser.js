function parseVideoURL(url){
	var width = Drupal.settings.videoFrame.width;
	var height = Drupal.settings.videoFrame.height;
		
	var _parseYoutube = function(url){
		var matches = url.match("youtu(?:\.be|be\.com)/(?:.*v(?:/|=)|(?:.*/)?)([a-zA-Z0-9-_]+)");
		if(matches){
			var id = matches[1];
			var embed = '<iframe width="' + width + '" height="' + height + '" src="http://www.youtube.com/embed/' + id + '" frameborder="0" allowfullscreen></iframe>';
			return {site : 'youtube', id : id, embed : embed};
		} else {
			return false;
		}
	}
	
	var _parseYouku = function(url){
		// tested: http://v.youku.com/v_show/id_XMzAzMzUzMzcy.html
		// http://player.youku.com/player.php/sid/XMzAzMzUzMzcy
		var matches = url.match("v\.youku\.com/v_show/id_([a-zA-Z0-9-_]+)\.html");
		if(!matches)
		matches = url.match("player.youku.com/player.php/sid/([a-zA-Z0-9-_]+)");
		
		if(matches){
			var id = matches[1];
			var embed = '<embed src="http://player.youku.com/player.php/sid/' + id + '/v.swf" allowFullScreen="true" quality="high" width="' + width + '" height="' + height + '" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>';
			return {site : 'youku', id : id, embed : embed};
		} else {
			return false;
		}
	}	
	
	var _parseTudou = function(){
		// tested: http://www.tudou.com/programs/view/_TN5UzGHJ4Y/
		// http://www.tudou.com/v/_TN5UzGHJ4Y
		var matches = url.match("tudou\.com/(?:v|programs/view)/([a-zA-Z0-9-_]+)");
		if(matches){
			var id = matches[1];
			var embed = '<embed src="http://www.tudou.com/v/' + id + '/v.swf" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="opaque" width="' + width + '" height="' + height + '"></embed';
			return {site : 'tudou', id : id, embed : embed};
		} else {
			return false;
		}	
	}
	
	var ret = null;
	var sites = /(youtube\.com|youtu\.be|youku\.com|tudou\.com|ku6\.com|56\.com|letv\.com|video\.sina\.com\.cn|(my\.)?tv\.sohu\.com|v\.qq\.com)/i;
	var matches = url.match(sites);
	if( matches ){
		switch(matches[0]){
		case 'youtube.com':
		case 'youtu.be':
			ret = _parseYoutube(url);
			break;
		case 'tudou.com':
			ret = _parseTudou(url);
			break;			
		case 'youku.com':
			ret = _parseYouku(url);
			break;
		}
	}	
	return ret;
}
