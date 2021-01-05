<?php

include 'libraries/PHPExcel.php';
include 'libraries/smarty.php';
include  'common.php';

$objPHPExcel = new PHPExcel();
$smarty  = new ATU_Smarty;
$smarty->smarty_dir  = 'theme';
$smarty->compile_dir   = 'theme/temp';
$smarty->force_compile = false;

function show($theme, $data = array())
{
	if (sizeof($data) > 0) {
		foreach ($data as $k => $v) {
			$GLOBALS["smarty"]->assign($k, $v);
		}
	}

	$GLOBALS["smarty"]->display($theme);
}

$act=S_get("act");
$dbf = "country.jdb";
$d = file_get_contents($dbf);
$jdt= json_decode(file_get_contents("country.jdt"), true);
$dbt = $jdt["t"];
$dbs = $jdt["s"];

$d1=array();
foreach($dbt as $k=>$v){
	if($dbs[$k]){
		$d1[$k]=$v;
	}
}
$dbt2=$d1;
//$d=json_decode($d,true);

//$dbt = array("id" => "序号", "zm2" => "二字母", "zm2s" => "二字母2", "zm3" => "三字母", "num" => "数字", "tel" => "电话区号", "time" => "时区", "time2" => "夏时制", "en" => "简称", "en2" => "全称", "cn" => "中文", "cn3" => "全称", "cn2" => "别名", "belong" =>  "所属", "continent"=>"洲", "capital" =>  "首都", "flag" => "国旗", "flag" => "国旗", "area" => "面积(平方公里)", "population" => "人口", "eun" => "组织", "un" => "联合国成员国", "untime" => "入联时间", "birthtime" => "建国日", "readme" => "备注");
/*
$dbt3 = array("id" => "序号", "zm2" => "二字母", "zm2s" => "二字母2", "zm3" => "三字母", "num" => "数字", "tel" => "电话区号", "time" => "时区", "time2" => "夏时制", "en" => "简称", "en2" => "全称", "cn" => "中文", "cn3" => "全称", "cn2" => "别名", "belong" =>  "所属", "continent" => "洲", "capital" =>  "首都", "flag" => "国旗", "flag" => "国旗", "area" => "面积(平方公里)", "population" => "人口", "eun" => "组织", "un" => "联合国成员国", "untime" => "入联时间", "birthtime" => "建国日", "readme" => "备注");
$d4=array();
foreach(json_decode($d,true) as $row){
	$d4[]=array("id" => $row["id"], "zm2" => $row["zm2"], "en" => $row["en"], "cn" => $row["cn"], "belong" => $row["belong"],  "capital" => empty($row["capital"])?"": $row["capital"], "area" => $row["area"], "population" => $row["population"], "un" =>  empty($row["un"]) ? "" : $row["un"]);
}
file_put_contents("c.json",json_encode($d4));
*/
if($act== "getField"){
	echo json_encode($jdt);
}
if($act== "saveField"){
	
	$k= $_GET["k"];
	$v = $_GET["v"];
	$s = $_GET["s"];

	$d1=array();
	$d2 = array();
	for($i=0;$i<sizeof($k);$i++){

		if($k[$i]!=""){
			$d1[$k[$i]]= $v[$i];
			$d2[$k[$i]] = $s[$i];
		}
	}
	$d=array("t"=> $d1,"s"=> $d2);
	file_put_contents("country.jdt",json_encode($d));
	echo '{"result":1}';
}

if($act=="xls2json"){
    $f= "country.xlsx";
  
	$objPHPExcel = PHPExcel_IOFactory::load($f);//获取sheet表格数目
	$sheetCount = $objPHPExcel->getSheetCount();//默认选中sheet0表
	$sheetSelected = 0;$objPHPExcel->setActiveSheetIndex($sheetSelected);//获取表格行数
	$rowCount = $objPHPExcel->getActiveSheet()->getHighestRow();//获取表格列数
	$columnCount = $objPHPExcel->getActiveSheet()->getHighestColumn();
	//echo "<div>Sheet Count : ".$sheetCount."　　行数： ".$rowCount."　　列数：".$columnCount."</div>";
	$dataArr = array();
	/* 循环读取每个单元格的数据 */
	//行数循环
	for ($row = 2; $row <= $rowCount; $row++){
	//列数循环 , 列数是以A列开始
		$d2=array();
		for ($column = 'A'; $column <= $columnCount; $column++) {
			$cell= $objPHPExcel->getActiveSheet()->getCell($column.$row)->getValue();

			if (is_object($cell)) {
				$cell = $cell->__toString();
			}
			$d2[]= $cell;
		}
		$dataArr[]=array("zm2" => $d2[0], "zm2s" => $d2[1], "zm3" =>  $d2[2], "num" =>  $d2[3], "tel" =>  $d2[4], "en" =>  $d2[5], "en2" =>  $d2[6], "cn" =>  $d2[7], "cn2" =>  $d2[8], "belong" =>  $d2[9], "flag" =>  $d2[10], "readme" =>  $d2[11]);
		
		//echo "<br/>消耗的内存为：".(memory_get_peak_usage(true) / 1024 / 1024)."M";
		//$endTime = time();
		//echo "<div>解析完后，当前的时间为：".date("Y-m-d H:i:s")."总共消耗的时间为：".(($endTime - $startTime))."秒</div>";
	}
	//var_dump($dataArr);
    echo json_encode($dataArr);
	file_put_contents($dbf, json_encode($dataArr));

	$dataArr = NULL;
	exit;
}
if($act== "info"){
	$data=array();
	$d=json_decode($d,true);
	$id=S_get("id");
	foreach($d as $row){
		if($row["id"]== $id){
			
			$data= iniRow($row);
			break;
		}
	}
	echo json_encode($data);
	exit;
}
function iniRow($row){
	foreach ($row as $k => $v) {
		$row[$k] = str_replace("\&quot;", "'", $v);
	}
	return $row;
}
function iniData($d)
{	
	$nd=array();
	foreach ($d as $row) {
		$nd[] = iniRow($row);
	}
	return $nd;
}
if ($act == "ini") {
	$data = json_decode($d, true);
	/*
	
	$data1=array();
	$id=1;
	foreach ($data as $row) {
		$row["id"]= $id;
		$data1[] = $row;
		$id++;
		
	}
	//加入id
	*/
	/*
	//加入联合国信息
	
	$country="|";
	foreach ($data as $row) {
		$country.= $row["cn"]."|";
	}
	$str=file_get_contents("un.txt");
	$str = str_replace("\r", "|", $str);
	$str = str_replace(" ", "", $str);
	
	$strArr=explode("|", $str);
	array_pop($strArr);
	
	$unCountry=array();
	foreach($strArr as $k){
		$strAr2 = explode(",", $k);
		//echo $strAr2[0].":".strpos($country, $strAr2[0])."<br/>";
		$c=trim($strAr2[0]);
		if(str_replace("|". $c. "|","", $country)!= $country){
			$unCountry[$c]= $strAr2[1];
			
		}else{
			echo $c . "<br/>";
		}
	}
	$data1=array();
	foreach($data as $row){
		if(!empty($unCountry[$row["cn"]])){
			$row["un"]=1;
			$row["untime"] = $unCountry[$row["cn"]];
		}
		$data1[]=$row;
	}
	*/

	$data1 = array();
	foreach ($data as $row) {
		$row["area"]=str_replace("平方公里","", $row["area"]);
		$row["population"] = str_replace("年", "", $row["population"]);
		$data1[] = $row;
	}


	dbSave($data, $data1);
	exit;
	
}
function dbSave($oldData,$newData){
	//$dbt=$GLOBALS["dbt"];
	$dbf = $GLOBALS["dbf"];
	file_put_contents("bk/country." . time() . ".jdb",  json_encode( $oldData));

	file_put_contents($dbf,  json_encode($newData));
}
function getRow($data,$id){

	foreach ($data as $row) {
		if ($row["id"] == $id) {
		
			return  $row;
		} 
	}
	return "";
}
if ($act == "save") {
	$data1 = array();
	$data = json_decode($d, true);
	$id = S_get("id");
	

	
	$nd= getRow($data,$id);
	foreach($dbt2 as $k=>$v){
		$nd[$k]=S_get($k);
	}
	if (strpos($nd["flag"], "://")>0){
		//处理图片
		$url = "flag/" . $nd["zm2"] . ".jpg";
		file_put_contents($url, file_get_contents($nd["flag"]));
		$nd["flag"]=$url;
		
	}
	$isNew = 1;
	foreach ($data as $row) {
		if ($row["id"] == $id) {
			$data1[] = $nd;
			$isNew=0;
		}else{
			$data1[]=$row;
		}
	}
	
	if($isNew){
		$data1[] = $nd;
		echo '{"result":2}';
	}else{
		echo '{"result":1}';
	}

	dbSave($data, $data1);
	
	exit;
}
function baiduUrl($cn){
	$url = 'https://baike.baidu.com/item/' . $cn;
	if ($cn == "阿鲁巴") {
		$url = "https://baike.baidu.com/item/%E9%98%BF%E9%B2%81%E5%B7%B4/36524";
	}
	if ($cn == "孟加拉") {
		$url = "https://baike.baidu.com/item/%E5%AD%9F%E5%8A%A0%E6%8B%89%E5%9B%BD";
	}
	if ($cn == "百慕大") {
		$url = "https://baike.baidu.com/item/%E7%99%BE%E6%85%95%E5%A4%A7/73567";
	}
	if ($cn == "多哥") {
		$url = "https://baike.baidu.com/item/%E5%A4%9A%E5%93%A5/423205";
	}
	if ($cn == "希腊") {
		$url = "https://baike.baidu.com/item/%E5%B8%8C%E8%85%8A/197766";
	}
	if ($cn == "黑山") {
		$url = "https://baike.baidu.com/item/%E9%BB%91%E5%B1%B1/14112#viewPageContent";
	}
	if ($cn == "利比里亚") {
		$url = "https://baike.baidu.com/item/%E5%88%A9%E6%AF%94%E9%87%8C%E4%BA%9A%E5%85%B1%E5%92%8C%E5%9B%BD/1908819";
	}
	if ($cn == "荷属圣马丁岛") {
		$url = "https://baike.baidu.com/item/%E8%8D%B7%E5%B1%9E%E5%9C%A3%E9%A9%AC%E4%B8%81/4185280?fr=aladdin";
	}
	if ($cn == "亚美尼亚") {
		$url = "https://baike.baidu.com/item/%E4%BA%9A%E7%BE%8E%E5%B0%BC%E4%BA%9A/129520";
	}
	if ($cn == "阿尔及利亚") {
		$url = "https://baike.baidu.com/item/%E9%98%BF%E5%B0%94%E5%8F%8A%E5%88%A9%E4%BA%9A/269730";
	}
	if ($cn == "格鲁吉亚") {
		$url = "https://baike.baidu.com/item/%E6%A0%BC%E9%B2%81%E5%90%89%E4%BA%9A/129742";
	}
	if ($cn == "伊拉克") {
		$url = "https://baike.baidu.com/item/%E4%BC%8A%E6%8B%89%E5%85%8B/213585";
	}
	if ($cn == "秘鲁") {
		$url = "https://baike.baidu.com/item/%E7%A7%98%E9%B2%81/258354";
	}
	if ($cn == "巴布亚新几内亚") {
		$url = "https://baike.baidu.com/item/%E5%B7%B4%E5%B8%83%E4%BA%9A%E6%96%B0%E5%87%A0%E5%86%85%E4%BA%9A/374306";
	}
	if ($cn == "塞内加尔") {
		$url = "https://baike.baidu.com/item/%E5%A1%9E%E5%86%85%E5%8A%A0%E5%B0%94/422461";
	}

	
	
	return $url;
}
if ($act == "getBaidu") {
	$cn = S_get("cn");
	echo '{"url":"'. baiduUrl($cn).'"}';
}

if($act== "getFlag"){
	$cn = S_get("cn");
	$zm2 = S_get("zm2");

	$url= baiduUrl($cn);
	
	//echo 'https://baike.baidu.com/item/' . $cn  ;
	$con = file_get_contents($url);
	$con = str_replace(array("\r\n", "\r", "\n"), "", $con);
	
	preg_match_all('@<div class="summary-pic">(.*?)</div>([^<>]+)@is', $con, $matches);
	//echo $matches[0][0];
	
	if(!empty($matches[0][0])){
		preg_match_all('@<img src="(.*?)"([^<>]+)@is',  $matches[0][0], $matches);
		$url= $matches[1][0];
		$urlArr=explode("?",$url);
		//echo $urlArr[0];
	}
	$tel =  "";
	$time =  "";
	$area =  "";
	$poplation =  "";
	preg_match_all('@<dt class="basicInfo-item name">人口数量</dt><dd class="basicInfo-item value">(.*?)</dd>@is', $con, $matches);
	if(empty($matches[1][0])){
	
		preg_match_all('@<dt class="basicInfo-item name">人&nbsp;&nbsp;&nbsp;&nbsp;口</dt><dd class="basicInfo-item value">(.*?)</dd>@is', $con, $matches);
		
	}
	if (!empty($matches[1][0])) {
	$str = preg_replace("<sup.*?/sup>", "", $matches[1][0]);
	$str = preg_replace("<a.*?/a>", "", $str);
	$str = str_replace(array("<>", "&nbsp;"), "", $str);
	$poplation = $str;
	}

	preg_match_all('@<dt class="basicInfo-item name">国土面积</dt><dd class="basicInfo-item value">(.*?)</dd>@is', $con, $matches);
	if (empty($matches[1][0])) {

		preg_match_all('@<dt class="basicInfo-item name">面&nbsp;&nbsp;&nbsp;&nbsp;积</dt><dd class="basicInfo-item value">(.*?)</dd>@is', $con, $matches);
	}

	if (!empty($matches[1][0])) {
	$str = preg_replace("<sup.*?/sup>", "", $matches[1][0]);
	$str = preg_replace("<a.*?/a>", "", $str);
	$str = str_replace(array("<>", "&nbsp;"), "", $str);

	$area= $str;
	}

	preg_match_all('@<dt class="basicInfo-item name">国际电话区号</dt><dd class="basicInfo-item value">(.*?)</dd>@is', $con, $matches);
	if (empty($matches[1][0])) {

		preg_match_all('@<dt class="basicInfo-item name">电话区号</dt><dd class="basicInfo-item value">(.*?)</dd>@is', $con, $matches);
	}
	if (!empty($matches[1][0])) {

	
	$str = preg_replace("<sup.*?/sup>", "", $matches[1][0]);
	$str = preg_replace("<a.*?/a>", "", $str);
	$str = str_replace(array("<>", "&nbsp;"), "", $str);

	$tel =  $str;
	}

	preg_match_all('@<dt class="basicInfo-item name">时&nbsp;&nbsp;&nbsp;&nbsp;区</dt><dd class="basicInfo-item value">(.*?)</dd>@is', $con, $matches);
	if (!empty($matches[1][0])) {
		

	$str = preg_replace("<sup.*?/sup>", "", $matches[1][0]);
	$str = preg_replace("<a.*?/a>", "", $str);
	$str = str_replace(array("<>", "&nbsp;"), "", $str);

	$time =  $str;
	}
	
	if(empty($urlArr[0])){
		$url="";
	}else{
		$fileType="jpg";
		if(strpos($urlArr[0],".png")>0){
			$fileType = "png";
		}
		if (strpos($urlArr[0], ".gif") > 0) {
			$fileType = "gif";
		}
		$url = "flag/" . $zm2 . ".". $fileType;
		file_put_contents($url, file_get_contents($urlArr[0]));
	}
	
	echo '{"flag":"'. $url.'","area":"'. $area. '","population":"' . $poplation . '","time":"' . $time . '","tel":"' . $tel . '"}';
	
	exit;
}
if($act=="getBirth"){
	$cn = S_get("cn");
	$zm2 = S_get("zm2");
	$url = baiduUrl($cn);
	$con = file_get_contents($url);
	$con = str_replace(array("\r\n", "\r", "\n"), "", $con);
	$birthtime="";
	$capital="";

	preg_match_all('@<dt class="basicInfo-item name">国庆日</dt><dd class="basicInfo-item value">(.*?)</dd>@is', $con, $matches);
	if (empty($matches[1][0])) {

		//preg_match_all('@<dt class="basicInfo-item name">电话区号</dt><dd class="basicInfo-item value">(.*?)</dd>@is', $con, $matches);
	}
	if (!empty($matches[1][0])) {


		$str = preg_replace("<sup.*?/sup>", "", $matches[1][0]);
		$str = strip_tags($str);
		$str = str_replace(array("<>", "&nbsp;"), "", $str);

		$birthtime = str_replace(array("年","月"),".", $str) ;
		$birthtime = str_replace("日", "", $birthtime);
	}

	preg_match_all('@<dt class="basicInfo-item name">首&nbsp;&nbsp;&nbsp;&nbsp;都</dt><dd class="basicInfo-item value">(.*?)</dd>@is', $con, $matches);
	if (empty($matches[1][0])) {

		//preg_match_all('@<dt class="basicInfo-item name">电话区号</dt><dd class="basicInfo-item value">(.*?)</dd>@is', $con, $matches);
	}
	if (!empty($matches[1][0])) {


		$str = preg_replace("<sup.*?/sup>", "", $matches[1][0]);
		$str = strip_tags($str);
		$str = str_replace(array("<>", "&nbsp;"), "", $str);

		$capital = $str;
	}
	
	echo '{"birthtime":"' . $birthtime . '","capital":"' . $capital . '"}';

	
	
}
if($act=="test"){
	echo md5('AlexTu2020');
}
if($act){}else{
show("index.html", array("d" => $d,"t"=>json_encode($dbt2)));
}