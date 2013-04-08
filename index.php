<?php

$category = isset($_GET['cat']) ? $_GET['cat'] : 'php';

//define endpoin YQL
$endpoint = 'http://query.yahooapis.com/v1/public/yql?q=';

//define YQL statement
$yql = 'select * from html where url="http://ontwik.com/category/'.$category.'/" and xpath="//ul[@class=\'videos\']//h2" limit 12';

//collect and urlencoded and put the format json
$url = $endpoint . urlencode($yql). '&format=xml';

function getAndTrick($url) {

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);
    $data = curl_exec($ch);
    $data = preg_replace('/<\?.*\?>/','',$data);
    $data = preg_replace('/<\!--.*-->/','',$data);
    $data = preg_replace("/.*<results>|<\/results>.*/",'',$data);
    $data = preg_replace('/<\/results><\/query>/','',$data);
    $data = preg_replace('/<results>/','',$data);
    $data = preg_replace('/<h2 class="h2">/','',$data);
    $data = preg_replace('/<\/h2>/','',$data);
    $data = preg_replace('/\t|\n/','',$data);
    $data = preg_replace('/.jpg"\/>/','.jpg"/><span>',$data);
    $data = preg_replace('/<\/a>/','</span></a>',$data);
    $data = preg_replace('/<a href=/','<li><a href=',$data);
    $data = preg_replace('/<\/a>/','</a></li>',$data);
    curl_close($ch); 
    if(empty($data)) {return 'server timeout';}
                 else {return $data;}
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <title>Videos For Real Developers ontwik.com</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.8.0r4/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <link rel="stylesheet" href="http://bootswatch.com/amelia/bootstrap.min.css" />
<style type="text/css">
*{margin: 0;padding:0}
h1{color: #fff;font-size: 50px;
    background-color: #1A90DB;
    border-radius: 10px 10px 10px 10px;
    font-weight: bold;
    margin: 0 0 10px;
    padding: 5px 10px;
    text-align: center;
    width: 850px;
    margin-top:20px;
}
#ontwik ul {
    margin: 20px;
    padding: 0;   
}

#ontwik ul li {margin-top: 20px}

#ontwik {width: 900px;height:auto;}

#ontwik img.thumbnail{
    height: 125px;
    width: 220px;
}

#ontwik ul.media{
    float: left;
    list-style-type: none;
    margin-left: 5px;
    margin-bottom: 50px;
    text-align: left;    
}
#ontwik a:link,a:visited{color: #1A90DB;text-decoration: none}
#ontwik span {font-size: 40px;color: #FFF}
#ft {
    background-color: #1A90DB;
    border-radius: 10px 10px 10px 10px;
    font-weight: bold;
    margin: 0 0 10px;
    padding: 5px 10px;
    text-align: center;
    width: 850px;
    margin-top:20px;

}
select {margin-left: 40%}
#ft p a {color: #fff}
</style>
</head>
<body class="yui-skin-sam">
<div id="doc" class="yui-t7">
   <div id="hd" role="banner"><h1>Real Developers by ontwik.com</h1></div>
   <div id="bd" role="main">
<?php
     $arr = array("php","javascript","ruby","startup","python","html5-2","css","go","git-github","nodejs","haskell");
?>
<select tabindex="2" name="p_card_type" id="p_card_type" class="selecta">
        <option value="" disabled="disabled">Please select</option>
        <?php
            foreach($arr as $val) {
                   if($val == $category) {
                   echo"<option value='$val' selected>$val</option>";
                   } else {  
                   echo"<option value='$val'>$val</option>";
                   }
            } 
        ?>
</select>
<script type="text/javascript">
function addEvent(elem,evType,fn,useCapture) {
         if(elem.addEventListener) {
            return elem.addEventListener(evType,fn,useCapture); 
         } else if(elem.attachEvent) {
            return elem.attachEvent('on'+evType,fn);
         } else {
            elem['on'+evType] = fn;  
         }
}
addEvent(document.getElementById('p_card_type'),'change',function(){
         var cat = this.options[this.selectedIndex].value;
         window.location = 'index.php?cat='+ cat;
},false);
</script>
	<div class="yui-g">
<?php
echo"<div id='ontwik'>";
echo"<ul id='ontwik' class='media'>";
echo$output = getAndTrick($url);
echo"</ul>";
echo"</div>";
?>

	</div>
	</div>
   <div id="ft" role="contentinfo"><p>Created b @<a href="http://github.com/thinkphp">thinkphp</a> using YQL and PHP</p></div>
</div>




</body>
</html>