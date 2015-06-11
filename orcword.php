<?php
	$apikey=$_GET['apikey'];
	if(!isset($apikey) or empty($apikey)){
		echo "请输入百度apikey";die;
	}
    $ch = curl_init();
    $url = 'http://apis.baidu.com/apistore/idlocr/ocr';
    $header = array(
        'Content-Type:application/x-www-form-urlencoded',
        'apikey:'.$apikey,
    );
	$jpg=glob("*.jpg");
	foreach($jpg as $j){
		
		$img = file_get_contents($j);
		$img = base64_encode($img);
		//var_dump ($img);
		$img = urlencode($img);
		$data = "fromdevice=pc&clientip=10.10.10.0&detecttype=LocateRecognize&languagetype=CHN_ENG&imagetype=1&image=".$img;
		// 添加apikey到header
		curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
		// 添加参数
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 执行HTTP请求
		curl_setopt($ch , CURLOPT_URL , $url);
		$res = curl_exec($ch);
		
		$res = json_decode($res);
		$res=obj2arr($res);
		if($res['errMsg']!=='success'){
			echo "apikey不正确";die;
		}
		foreach($res['retData'] as $k=>$v){
			$v = obj2arr($v);
			echo $j."-------->";
			//var_dump($v['word']);
			echo $v['word']."<br>";
		}
	}
	//转数组
	function obj2arr($obj){
		$arr = is_object($obj) ? get_object_vars($obj) : $obj;
		return $arr;
	}
?>