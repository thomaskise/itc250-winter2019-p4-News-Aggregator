<?php
/*Function  the left means to exclude the left side of the specified string, right means to exclude  to the right of the specified string */
function excludeString($replacedString, $originalString, $a){
    if(!$replacedString){
        return "The replaced string does not exist";
    }
    $replacedString = mb_convert_encoding($replacedString, 'GB2312', 'UTF-8');
    $originalString = mb_convert_encoding($originalString, 'GB2312', 'UTF-8');
    if($a == ""){
    $last = str_replace($replacedString, "", $originalString);
    }elseif($a == "right"){
        $last = preg_replace("/[" . $replacedString . "]+[\d\D\w\W\s\S]*/", "", $originalString);

        }elseif($a == "left"){
            $last = preg_replace("/[\d\D\w\W\s\S]*[". $replacedString . "]+/", "", $originalString);
            }
            $last =  mb_convert_encoding($last, 'UTF-8', 'GB2312'); 
    return $last;

}

// Strip the html tage
function stripHtmlTags($tags, $str, $content = true)
{
    $html = [];
    // Whether to retain the text characters in the tag
    if($content){
        foreach ($tags as $tag) {
            $html[] = '/(<' . $tag . '.*?>(.|\n)*?<\/' . $tag . '>)/is';
        }
    }else{
        foreach ($tags as $tag) {
            $html[] = "/(<(?:\/" . $tag . "|" . $tag . ")[^>]*>)/is";
        }
    }
    $data = preg_replace($html, '', $str);
    return $data;
}

//	Rrim the space 
function trimSpace($str){
    $trimResult = array("&nbsp;");
  	//	Replace all occurrences of the search string with the replacement string
    return str_replace($trimResult, '', $str);  
}

?>