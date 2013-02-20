<?php
class VideoParser {
    static public function getEmbedCode($site, $id){
    	$w = variable_get('annotation_book_video_width', 400);
  		$h = variable_get('annotation_book_video_height', 300);

			$embed = array(
				'youtube' => 'http://www.youtube.com/embed/!vid',
				'vimeo' => 'http://player.vimeo.com/video/!vid',
				'youku' => 'http://player.youku.com/player.php/sid/!vid/v.swf',
				'tudou' => 'http://www.tudou.com/programs/view/!vid/',
			);
   		$link = str_replace('!vid', $id, $embed[$site]);
   		$code = '<iframe width="' . $w . '" height="' . $h . '" src="' . $link . '" frameborder="0" allowfullscreen></iframe>';
   		
    	return $code;
    }
    
    static public function parse($url=''){
    		$check_url_valid = "/(youtube\.com|vimeo\.com|youtu\.be|youku\.com|tudou\.com)/i";
    		
        preg_match($check_url_valid, $url, $matches);

        if(!$matches) return false;

        switch($matches[1]){
        case 'youtube.com':
        case 'youtu.be':
        		$data = self::_parseYoutube($url);
        		break;
        case 'youku.com':
            $data = self::_parseYouku($url);
            break;
        case 'tudou.com':
            $data = self::_parseTudou($url);
            break;
        case 'vimeo.com':
        		$data = self::_parseVimeo($url);
        		break;
        default:
            $data = false;
        }
        return $data;
    }
    
    private function _parseVimeo($url){
			preg_match('/(\d+)/', $url, $matches);
			
			if(!$matches)
			return false;

			$id = $matches[1];
  		$html = drupal_http_request("http://vimeo.com/api/v2/video/{$id}.php");
  		if($html->code == 200){
  			$info = @unserialize(trim($html->data));
	  		return array(
	  			'site' => 'vimeo',
	  			'id' => $id,
	  			'url' => $info[0]['url'],
	  			'title' => $info[0]['title'],
	  			'thumbnail' => $info[0]['thumbnail_medium'],	// thumbnail_small, thumbnail_large
	  			'embed' => self::getEmbedCode('vimeo', $id),
	  		);
			} else {
				return false;
			}
    }

    /**
     * Youtube
		 * http://www.youtube.com/watch?v=GiOzesXkKKM
		 * http://youtu.be/GiOzesXkKKM
		 * http://www.youtube.com/embed/GiOzesXkKKM
     */ 
    private function _parseYoutube($url){
 			$pattern = '#^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com(?:/embed/|/v/|/watch\?v=|/watch\?.+&v=))([\w-]{11})(?:.+)?$#x';
			preg_match($pattern, $url, $matches);
    	if(isset($matches[1])) 
	    	$id = $matches[1];
    	else
  	  	return false;		

			$html = drupal_http_request('http://youtube.com/get_video_info?video_id='.$id);
			
			if($html->code == 200) {
				parse_str($html->data, $info);

				return array(
					'site' => 'youtube',
					'id' => $id,
					'url' => "http://www.youtube.com/watch?v=$id",
					'title' => $info['title'],
					'thumbnail' => $info['thumbnail_url'],
					'embed' => self::getEmbedCode('youtube', $id),
				);
			} else {
				return false;
			}
    }
    
    /*
     * 优酷网 
     * http://v.youku.com/v_show/id_XMjI4MDM4NDc2.html
     * http://v.youku.com/v_playlist/f17146177o1p73.html
     */ 
    private function _parseYouku($url){
        preg_match("#id\_(\w+)#", $url, $matches);

        if ( empty($matches) ){
            preg_match("#v_playlist\/#", $url, $mat);
            if( !$mat )
            return false;
            
            $html = drupal_http_request($url);
            if( $html->code == 200 ){
	            preg_match("#videoId2\s*=\s*\'(\w+)\'#", $html->data, $matches);
            }
            
            if( !$matches )
            return false;
        }
        
				$id = $matches[1];
        $link = "http://v.youku.com/player/getPlayList/VideoIDS/{$id}/version/5/source/out?onData=%5Btype%20Function%5D&n=3";
				$html = drupal_http_request($link);
        if ($html->code == 200) {
            $info = json_decode($html->data, true);  
						return array(
							'site' => 'youku',
							'id' => $id,
							'url' => "http://v.youku.com/v_show/id_{$id}.html",
							'title' => $info['data'][0]['title'],
							'thumbnail' => $info['data'][0]['logo'],
							'embed' => self::getEmbedCode('youku', $id),
						);
        } else {
            return false;
        }
    }

    /**
     * 土豆网
     * http://www.tudou.com/programs/view/Wtt3FjiDxEE/
     * http://www.tudou.com/v/Wtt3FjiDxEE/v.swf
     * http://www.tudou.com/v/Wtt3FjiDxEE
     */
    private function _parseTudou($url){
        preg_match("#[v|programs/view]+/([-\w]+)/#", $url, $matches);
        if ( empty($matches) ) {
					//TODO: parse video id from listplay and albumplay
        	return false;
				}
				
				$id = $matches[1];
				
				$html = drupal_http_request("http://www.tudou.com/v/{$id}/v.swf");
        
        if ($html->code == 200) {
            parse_str(parse_url(urldecode($html->redirect_url), PHP_URL_QUERY));
            
						return array(
							'site' => 'tudou',
							'id' => $id,
							'url' => $url,
							'title' => $title,
							'thumbnail' => $snap_pic,
							'embed' => self::getEmbedCode('tudou', $id),
						);
        } else {
        	return false;
        }
		}
}

