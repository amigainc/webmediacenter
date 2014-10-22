<?php
class format{
	static function minify_files($format, $files, $saveFile='', $overwrite=true)
	{
		if(!$overwrite && is_file($saveFile))
			return $saveFile;
			
		$contenu='';
		switch($format)
		{
			case 'js':
				foreach($files as $file)
				{
					$contenu.=format::minify_js(url_get_contents($file));
				}
				break;
				
			case 'css':
				foreach($files as $file)
					$contenu.=format::minify_css(url_get_contents($file));
				
				break;
		}
		
		$h=fopen($saveFile,'w');
		fputs($h, $contenu);
		fclose($h);
		
		return $saveFile;
	}

    static function minify_css($text){
        $from   = array(
        //                  '%(#|;|(//)).*%',               // comments:  # or //
            '%/\*(?:(?!\*/).)*\*/%s',       // comments:  /*...*/
            '/\s{2,}/',                     // extra spaces
            "/\s*([;{}])[\r\n\t\s]/",       // new lines
            '/\\s*;\\s*/',                  // white space (ws) between ;
            '/\\s*{\\s*/',                  // remove ws around {
            '/;?\\s*}\\s*/',                // remove ws around } and last semicolon in declaration block
            //                  '/:first-l(etter|ine)\\{/',     // prevent triggering IE6 bug: http://www.crankygeek.com/ie6pebug/
        //                  '/((?:padding|margin|border|outline):\\d+(?:px|em)?) # 1 = prop : 1st numeric value\\s+/x',     // Use newline after 1st numeric value (to limit line lengths).
        //                  '/([^=])#([a-f\\d])\\2([a-f\\d])\\3([a-f\\d])\\4([\\s;\\}])/i',
        );
        $to     = array(
        //                  '',
            '',
            ' ',
            '$1',
            ';',
            '{',
            '}',
            //                  ':first-l$1 {',
        //                  "$1\n",
        //                  '$1#$2$3$4$5',
        );
        $text   = preg_replace($from,$to,$text);
        return $text;
    }
    static function minify_js($text){
        $file_cache     = strtolower(md5($text));
        $folder         = TMPPATH.'tmp_files'.DIRECTORY_SEPARATOR.substr($file_cache,0,2).DIRECTORY_SEPARATOR;
        if(!is_dir($folder))            @mkdir($folder, 0766, true);
        if(!is_dir($folder)){
            echo 'Impossible to create the cache folder:'.$folder;
            return 1;
        }
        $file_cache     = $folder.$file_cache.'_content.js';
        if(!file_exists($file_cache)){
            if(strlen($text)<=100){
                $contents = $text;
            } else {
                $contents = '';
                $post_text = http_build_query(array(
                                'js_code' => $text,
                                'output_info' => 'compiled_code',//($returnErrors ? 'errors' : 'compiled_code'),
                                'output_format' => 'text',
                                'compilation_level' => 'SIMPLE_OPTIMIZATIONS',//'ADVANCED_OPTIMIZATIONS',//'SIMPLE_OPTIMIZATIONS'
                            ), null, '&');
                $URL            = 'http://closure-compiler.appspot.com/compile';
                $allowUrlFopen  = preg_match('/1|yes|on|true/i', ini_get('allow_url_fopen'));
                if($allowUrlFopen){
                    $contents = file_get_contents($URL, false, stream_context_create(array(
                            'http'          => array(
                                'method'        => 'POST',
                                'header'        => 'Content-type: application/x-www-form-urlencoded',
                                'content'       => $post_text,
                                'max_redirects' => 0,
                                'timeout'       => 15,
                            )
                    )));
                }elseif(defined('CURLOPT_POST')) {
                    $ch = curl_init($URL);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded'));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_text);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
                    $contents = curl_exec($ch);
                    curl_close($ch);
                } else {
                    //"Could not make HTTP request: allow_url_open is false and cURL not available"
                    $contents = $text;
                }
                if($contents==false || (trim($contents)=='' && $text!='') || strtolower(substr(trim($contents),0,5))=='error' || strlen($contents)<=50){
                    //No HTTP response from server or empty response or error
                    $contents = $text;
                }
            }
            if(trim($contents)!=''){
                $contents = trim($contents);
                $f = fopen($file_cache, 'w');
                fwrite($f, $contents);
                fclose($f);
            }
        } else {
            touch($file_cache);     //in the future I will add a timetout to the cache
            $contents = file_get_contents($file_cache);
        }
        return $contents;
    }
    static function minify_html($text){
        if(isset($_GET['no_mini'])){
            return $text;
        }
        $file_cache     = strtolower(md5($text));
        $folder         = TMPPATH.'tmp_files'.DIRECTORY_SEPARATOR.substr($file_cache,0,2).DIRECTORY_SEPARATOR;
        if(!is_dir($folder))            @mkdir($folder, 0766, true);
        if(!is_dir($folder)){
            echo 'Impossible to create the cache folder:'.$folder;
            return 1;
        }
        $file_cache     = $folder.$file_cache.'_content.html';
        if(!file_exists($file_cache)){
            //get CSS and save it
            $search_css = '/<\s*style\b[^>]*>(.*?)<\s*\/style>/is';
            $ret = preg_match_all($search_css, $text, $tmps);
            $t_css = array();
            if($ret!==false && $ret>0){
                foreach($tmps as $k=>$v){
                    if($k>0){
                        foreach($v as $kk=>$vv){
                            $t_css[] = $vv;
                        }
                    }
                }
            }
            $css = format::minify_css(implode('\n', $t_css));

/*
            //get external JS and save it
            $search_js_ext = '/<\s*script\b.*?src=\s*[\'|"]([^\'|"]*)[^>]*>\s*<\s*\/script>/i';
            $ret = preg_match_all($search_js_ext, $text, $tmps);
            $t_js = array();
            if($ret!==false && $ret>0){
                foreach($tmps as $k=>$v){
                    if($k>0){
                        foreach($v as $kk=>$vv){
                            $t_js[] = $vv;
                        }
                    }
                }
            }
            $js_ext = $t_js;
*/
            //get inline JS and save it
            $search_js_ext  = '/<\s*script\b.*?src=\s*[\'|"]([^\'|"]*)[^>]*>\s*<\s*\/script>/i';
            $search_js      = '/<\s*script\b[^>]*>(.*?)<\s*\/script>/is';
            $ret            = preg_match_all($search_js, $text, $tmps);
            $t_js           = array();
            $js_ext         = array();
            if($ret!==false && $ret>0){
                foreach($tmps as $k=>$v){
                    if($k==0){
                        //let's check if we have a souce (src="")
                        foreach($v as $kk=>$vv){
                            if($vv!=''){
                                $ret = preg_match_all($search_js_ext, $vv, $ttmps);
                                if($ret!==false && $ret>0){
                                    foreach($ttmps[1] as $kkk=>$vvv){
                                        $js_ext[] = $vvv;
                                    }
                                }
                            }
                        }
                    } else {
                        foreach($v as $kk=>$vv){
                            if($vv!=''){
                                $t_js[] = $vv;
                            }
                        }
                    }
                }
            }
            $js = format::minify_js(implode('\n', $t_js));

            //get inline noscript and save it
            $search_no_js = '/<\s*noscript\b[^>]*>(.*?)<\s*\/noscript>/is';
            $ret = preg_match_all($search_no_js, $text, $tmps);
            $t_js = array();
            if($ret!==false && $ret>0){
                foreach($tmps as $k=>$v){
                    if($k>0){
                        foreach($v as $kk=>$vv){
                            $t_js[] = $vv;
                        }
                    }
                }
            }
            $no_js = implode('\n', $t_js);

            //remove CSS and JS
            $search = array(
                $search_js_ext,
                $search_css,
                $search_js,
                $search_no_js,
                '/\>[^\S ]+/s', //strip whitespaces after tags, except space
                '/[^\S ]+\</s', //strip whitespaces before tags, except space
                '/(\s)+/s',  // shorten multiple whitespace sequences
            );
            $replace = array(
                '',
                '',
                '',
                '',
                '>',
                '<',
                '\\1',
            );
            $buffer = preg_replace($search, $replace, $text);

            $append = '';
            //add CSS and JS at the bottom
            if(is_array($js_ext) && count($js_ext)>0){
                foreach($js_ext as $k=>$v){
                    $append .= '<script type="text/javascript" language="javascript" src="'.$v.'" ></script>';
                }
            }
            if($css!='')        $append .= '<style>'.$css.'</style>';
            if($js!=''){
                //remove weird '\n' strings
                $js = preg_replace('/[\s]*\\\n/', "\n", $js);
                $append .= '<script>'.$js.'</script>';
            }
            if($no_js!='')      $append .= '<noscript>'.$no_js.'</noscript>';
            $buffer = preg_replace('/(.*)(<\s*\/\s*body\s*>)(.*)/','\\1'.$append.'\\2\\3', $buffer);
            if(trim($buffer)!=''){
                $f = fopen($file_cache, 'w');
                fwrite($f, trim($buffer));
                fclose($f);
            }
        } else {
            touch($file_cache);     //in the future I will add a timetout to the cache
            $buffer = file_get_contents($file_cache);
        }

        return $buffer;
    }

}