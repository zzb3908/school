<?php

/**
 * 通用化API接口数据输出
 * @param int $status 业务状态码
 * @param string $message 信息提示
 * @param array $data  数据
 * @param int $httpCode http状态码
 * @return mixed
 */
function show($status, $message, $data=[], $httpCode=200) {

    $data = [
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ];

    return json($data, $httpCode);
}

function module_name($id){
	foreach (config('module_type') as $key => $value) {
		if ($id == $value['id']) {
			return $value['name'];
		}
	}
	return false;
}

//模块类型
function module_type($pid=0){
	if (empty($pid)) {
		return config('module_type');
	}

	$data = [];
	foreach (config('module_type') as $key => $value) {
		if ($value['pid']==$pid) {
			$data[] = $value;
		}
	}
	return $data;
}

function tags_name($id){
	foreach (config('tags_type') as $key => $value) {
		if ($id == $value['id']) {
			return $value['name'];
		}
	}
	return '';
}

function pinyin($zh) {
	$ret = "";
	$s1 = iconv("UTF-8", "gb2312", $zh);
	$s2 = iconv("gb2312", "UTF-8", $s1);
	if ($s2 == $zh) {$zh = $s1;}
	for ($i = 0; $i < strlen($zh); $i++) {
		$s1 = substr($zh, $i, 1);
		$p = ord($s1);
		if ($p > 160) {
			$s2 = substr($zh, $i++, 2);
			$ret .= getfirstchar($s2);
		} else {
			$ret .= $s1;
		}
	}
	return $ret;
}

/**
 * @name php获取中文字符拼音首字母
 * @param $str
 * @return null|string
 */
function getfirstchar($s0) {
	$fchar = ord($s0{0});
	if ($fchar >= ord("A") and $fchar <= ord("z")) {
		return strtoupper($s0{0});
	}

	$s1 = $s0;
	$s2 = $s1;
	//$s1 = iconv("UTF-8","gb2312", $s0);
	//$s2 = iconv("gb2312","UTF-8", $s1);
	if ($s2 == $s0) {$s = $s1;} else { $s = $s0;}
	$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
	if ($asc >= -20319 and $asc <= -20284) {
		return "A";
	}

	if ($asc >= -20283 and $asc <= -19776) {
		return "B";
	}

	if ($asc >= -19775 and $asc <= -19219) {
		return "C";
	}

	if ($asc >= -19218 and $asc <= -18711) {
		return "D";
	}

	if ($asc >= -18710 and $asc <= -18527) {
		return "E";
	}

	if ($asc >= -18526 and $asc <= -18240) {
		return "F";
	}

	if ($asc >= -18239 and $asc <= -17923) {
		return "G";
	}

	if ($asc >= -17922 and $asc <= -17418) {
		return "H";
	}

	if ($asc >= -17417 and $asc <= -16475) {
		return "J";
	}

	if ($asc >= -16474 and $asc <= -16213) {
		return "K";
	}

	if ($asc >= -16212 and $asc <= -15641) {
		return "L";
	}

	if ($asc >= -15640 and $asc <= -15166) {
		return "M";
	}

	if ($asc >= -15165 and $asc <= -14923) {
		return "N";
	}

	if ($asc >= -14922 and $asc <= -14915) {
		return "O";
	}

	if ($asc >= -14914 and $asc <= -14631) {
		return "P";
	}

	if ($asc >= -14630 and $asc <= -14150) {
		return "Q";
	}

	if ($asc >= -14149 and $asc <= -14091) {
		return "R";
	}

	if ($asc >= -14090 and $asc <= -13319) {
		return "S";
	}

	if ($asc >= -13318 and $asc <= -12839) {
		return "T";
	}

	if ($asc >= -12838 and $asc <= -12557) {
		return "W";
	}

	if ($asc >= -12556 and $asc <= -11848) {
		return "X";
	}

	if ($asc >= -11847 and $asc <= -11056) {
		return "Y";
	}

	if ($asc >= -11055 and $asc <= -10247) {
		return "Z";
	}

	return null;
}

function img2base64($file) {
	$base64_data = '';
	if (file_exists($file)) {
		$base64_data = base64_encode(file_get_contents($file));
	}
	return $base64_data;
}

/**
 * 计算过去或未来的时间维度
 * @access       public
 * @param        $type string 时间类型，可选：year,season,month,week,day
 * @param        $num int 维度数：0表示当前、负值表示过去、正值表示未来，举例：$type=day... $num=1(明天...)、$num=-1(昨天...)
 * @return       array 开始时间和结束时间
 * @author       iclubs <iclubs@126.com>
 */
function time_dimension($type, $num = 0) {
	if (!in_array($type, array('year', 'season', 'month', 'week', 'day'))) {
		return false;
	}
	$num = (int) $num;
	$dateArr = array();
	if ($type == 'year') {
		//年
		$dateArr['start_time'] = mktime(0, 0, 0, 1, 1, date("Y") + 1 * $num);
		$dateArr['end_time'] = mktime(23, 59, 59, 12, 31, date("Y", $dateArr['start_time']));
		$dateArr['start_date'] = date("Y-m-d H:i:s", $dateArr['start_time']);
		$dateArr['end_date'] = date("Y-m-d H:i:s", $dateArr['end_time']);
	} elseif ($type == 'season') {
		//季
		$season = ceil((date('n')) / 3) + 1 * $num; //当前第几季度
		$dateArr['start_time'] = mktime(0, 0, 0, $season * 3 - 3 + 1, 1, date('Y'));
		$dateArr['end_time'] = mktime(23, 59, 59, $season * 3, date('t', mktime(0, 0, 0, $season * 3, 1, date("Y"))), date('Y'));
		$dateArr['start_date'] = date("Y-m-d H:i:s", $dateArr['start_time']);
		$dateArr['end_date'] = date("Y-m-d H:i:s", $dateArr['end_time']);
	} elseif ($type == 'month') {
		//月
		$dateArr['start_time'] = mktime(0, 0, 0, date("m") + 1 * $num, 1, date("Y"));
		$dateArr['end_time'] = mktime(23, 59, 59, date("m") + 1 * $num, date("t", $dateArr['start_time']), date("Y"));
		$dateArr['start_date'] = date("Y-m-d H:i:s", $dateArr['start_time']);
		$dateArr['end_date'] = date("Y-m-d H:i:s", $dateArr['end_time']);
	} elseif ($type == 'week') {
		//周
		$dateArr['start_time'] = mktime(0, 0, 0, date("m"), date("d") - date("N") + 1 + 7 * $num, date("Y"));
		$dateArr['end_time'] = mktime(23, 59, 59, date("m"), date("d") - date("N") + 7 + 7 * $num, date("Y"));
		$dateArr['start_date'] = date("Y-m-d H:i:s", $dateArr['start_time']);
		$dateArr['end_date'] = date("Y-m-d H:i:s", $dateArr['end_time']);
	} elseif ($type == 'day') {
		//日
		$dateArr['start_time'] = mktime(0, 0, 0, date("m"), date("d") + 1 * $num, date("Y"));
		$dateArr['end_time'] = mktime(23, 59, 59, date("m"), date("d") + 1 * $num, date("Y"));
		$dateArr['start_date'] = date("Y-m-d H:i:s", $dateArr['start_time']);
		$dateArr['end_date'] = date("Y-m-d H:i:s", $dateArr['end_time']);
	}
	return $dateArr;
}

/**
 * pdf2png
 * @param $pdf  待处理的PDF文件
 * @param $path 待保存的图片路径
 * @param $filenmae 待保存的文件名
 * @param $page 待导出的页面 -1为全部 0为第一页 1为第二页
 * @return $file 保存好的图片路径和文件名
 */
function pdf2png($pdf, $path, $filenmae = '', $page = -1) {
	if (!extension_loaded('imagick')) {
		return false;
	}

	if (!file_exists($pdf)) {
		return false;
	}

	$im = new Imagick();
	$im->setResolution(120, 120);
	$im->setCompressionQuality(100);
	if ($page == -1) {
		$im->readImage($pdf);
	} else {
		$im->readImage($pdf . "[" . $page . "]");
	}

	$files = array();
	$tmp = md5(time());
	foreach ($im as $key => $value) {
		$value->setImageFormat('png');
		if ($filenmae) {
			$filename = $path . "/" . $filenmae . '-' . $key . '.png';
		} else {
			$filename = $path . "/" . $tmp . '-' . $key . '.png';
		}
		if ($value->writeImage($filename) == true) {
			$files[] = $filename;
		}
	}
	return $files;
}

//过虑产品名中的 年利率6% 字样
function filter_product_name($string) {
	return preg_replace('/年(利率|费率|化)[0-9]([\.][0-9]*)*\%/i', '', $string);
}

/**
 * 输出指定日期范围
 * @access      public
 * @param       $type日期类型 year,month,day
 * @param       $num输出的数量
 * @return      返回类型 array
 * @author      iclubs <iclubs@126.com>
 */
function mydate_format($type = '', $num = 5) {
	$arr = array();
	if ($type == 'year') {
		for ($i = date('Y'); $i >= date('Y') - $num; $i--) {
			$arr[] = (string) $i;
		}
	} elseif ($type == 'month') {
		for ($i = 1; $i <= 12; $i++) {
			$arr[] = $i < 10 ? '0' . $i : (string) $i;
		}
	} elseif ($type == 'day') {
		for ($i = 1; $i <= 31; $i++) {
			$arr[] = $i < 10 ? '0' . $i : (string) $i;
		}
	}
	return $arr;
}

//格式化千位符，去尾0
function format_money($money) {
	$money = floatval($money);
	if (stripos($money, '.') !== false) {
		$format_len = strlen($money) - (stripos($money, '.') + 1);
		$money = number_format($money, $format_len);
	} else {
		$money = number_format($money);
	}
	return $money ? $money : 0;
}

// 自定义身份证号验证规则
function getIdCard($idcard) {
	$vCity = array(
		'11', '12', '13', '14', '15', '21', '22',
		'23', '31', '32', '33', '34', '35', '36',
		'37', '41', '42', '43', '44', '45', '46',
		'50', '51', '52', '53', '54', '61', '62',
		'63', '64', '65', '71', '81', '82', '91',
	);
	if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $idcard)) {
		return false;
	}

	if (!in_array(substr($idcard, 0, 2), $vCity)) {
		return false;
	}

	$idcard = preg_replace('/[xX]$/i', 'a', $idcard);
	$vLength = strlen($idcard);
	if ($vLength == 18) {
		$year = substr($idcard, 6, 4);
		$vBirthday = $year . '-' . substr($idcard, 10, 2) . '-' . substr($idcard, 12, 2);
		$idcard18 = $idcard;
	} else {
		$year = '19' . substr($idcard, 6, 2);
		$vBirthday = $year . '-' . substr($idcard, 8, 2) . '-' . substr($idcard, 10, 2);
		$idcard18 = substr($idcard, 0, 6) . '19' . substr($idcard, 6);
	}
	if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) {
		return false;
	}

	if ($vLength == 18) {
		$vSum = 0;
		for ($i = 17; $i >= 0; $i--) {
			$vSubStr = substr($idcard, 17 - $i, 1);
			$vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr, 11));
		}
		if ($vSum % 11 != 1) {
			return false;
		}

	}
	if (strlen($idcard18) == 17) {
		$idcard18 .= getIdCardVerifyBit($idcard18);
	}
	//处理性别: 18位倒数第二位 单数=男，复数=女，这里重新定义 女=2，男=1
	$gender = (substr($idcard18, -2, 1) % 2 == 0) ? 2 : 1;
	return array('idcard' => $idcard18, 'birthday' => $vBirthday, 'age' => (date('Y') - $year), 'gender' => $gender);
}
// 计算身份证第18位校验码，根据国家标准GB 11643-1999
function getIdCardVerifyBit($idcard_base) {
	if (strlen($idcard_base) != 17) {
		return false;
	}
	//加权因子
	$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
	//校验码对应值
	$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
	$checksum = 0;
	for ($i = 0; $i < strlen($idcard_base); $i++) {
		$checksum += substr($idcard_base, $i, 1) * $factor[$i];
	}
	$mod = $checksum % 11;
	return $verify_number_list[$mod];
}

//时间格式化
function format_time($timestamp, $type = 'A') {
	if (empty($timestamp)) {
		return '';
	}
	
	$date = date('Y-m-d H:i', $timestamp);
	if (strtoupper($type) == 'B') {
		if (date('Y') == date('Y', $timestamp)) {
			$date = date('m-d H:i', $timestamp);
		}
		return $date;
	}

	$now = time();
	$today = strtotime(date('Y-m-d', $now));
	$year = strtotime(date('Y', $now) . '-01-01');
	$diff = $now - $timestamp;
	if ($timestamp >= $today) {
		if ($diff == 0) {
			$date = '1秒前';
		} elseif ($diff < 60) {
			$date = $diff . '秒前';
		} elseif ($diff < 3600) {
			$date = floor($diff / 60) . '分钟前';
		} else {
			$date = floor($diff / 3600) . '小时前';
		}
	} elseif ($timestamp >= $today-86400*1) {
		$date = '昨天';
	} elseif ($timestamp >= $today-86400*2) {
		$date = '前天';
	} elseif ($timestamp >= $today-86400*3) {
		$date = '3天前';
	} elseif ($timestamp >= $today-86400*4) {
		$date = '4天前';
	} elseif ($timestamp >= $today-86400*5) {
		$date = '5天前';
	} elseif ($timestamp >= $year) {
		$date = date('m.d', $timestamp);
	} else {
		$date = date('Y.m.d', $timestamp);
	}
	return $date;
}

//校验日期格式是否正确
function check_date($date, $formats = array("Y-m-d", "Y/m/d")) {
	$unixTime = strtotime($date);
	if (!$unixTime) {
		return false;
	}

	//校验日期的有效性，只要满足其中一个格式就OK
	foreach ($formats as $format) {
		if (date($format, $unixTime) == $date) {
			return true;
		}
	}
	return false;
}

function page_ajax($count, $page, $page_size, $pages, $url, $view_page = 5, $extend_param = '') {
	$offset = 2;
	// $url .= strpos($url, '?') ? '&' : '?';
	$pageHtml = '<li><span>共' . $count . '条，' . $pages . '页</span></li>';
	if ($count > $page_size) {
		if ($view_page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $page - $offset;
			$to = $from + $view_page - 1;
			if ($from < 1) {
				$to = $page + 1 - $from;
				$from = 1;
				if ($to - $from < $view_page) {
					$to = $view_page;
				}
			} elseif ($to > $pages) {
				$from = $pages - $view_page + 1;
				$to = $pages;
			}
		}
		$pageHtml .= '<li><a href="javascript:;" onclick="tablecontent(1)">首页</a></li>';
		if ($page > 1) {
			$pageHtml .= '<li class="previous"><a href="javascript:;" onclick="tablecontent(' . ($page - 1) . ')">上一页</a></li>';
		} else {
			$pageHtml .= '<li class="previous disabled"><a href="javascript:;">上一页</a></li>';
		}

		for ($i = $from; $i <= $to; $i++) {
			if ($i == $page) {
				$pageHtml .= '<li class="active"><a href="javascript:;">' . $i . '</a></li>';
			} else {
				$pageHtml .= '<li><a href="javascript:;" onclick="tablecontent(' . $i . ')">' . $i . '</a></li>';
			}
		}
		if ($page < $pages) {
			$pageHtml .= '<li class="next"><a href="javascript:;" onclick="tablecontent(' . ($page + 1) . ')">下一页</a></li>';
		} else {
			$pageHtml .= '<li class="next disabled"><a href="javascript:;">下一页</a></li>';
		}
		$pageHtml .= '<li><a href="javascript:;" onclick="tablecontent(' . $pages . ')">末页</a></li>';

		$pageHtml .= '<li><input type="text" id="jumpPage" style="width:45px;" value="' . $page . '" onchange="goJump(this);"></li>
        <li><a href="javascript:;">跳转</a></li>';
		$pageHtml .= '
            <script type="text/javascript">
                function tablecontent(pageIndex){
                    var param = "page="+pageIndex;
                    $.ajax({type:"get",url:"' . $url . '",data: param,dataType:"json",success:
                    function(msg){$("#tableData").html(msg.table);$("#pageData").html(msg.pagination);}});
                };
                function goJump(o){
                    var page=parseInt($("#jumpPage").val());
                    if(page<=0 || isNaN(page)){return false;};
                    $.ajax({type:"get",url:"' . $url . '",data: "page="+page,dataType:"json",success:
                    function(msg){$("#tableData").html(msg.table);$("#pageData").html(msg.pagination);}});
                };
            </script>';
	}
	return $pageHtml;
}

//获取登录客户信息
function getLoginInfo() {
	$code = my_encrypt(cookie(config('login_mark')), 'decode', config('login_key'));
	parse_str($code, $info);
	if (empty($info)) {
		return false;
	}
	return $info;
}

function data_encrypt($string, $operation = 'decode') {
	if (empty($string)) {
		return $string;
	}

	if ($operation == 'decode') {
		return base64_decode($string);
	} elseif ($operation == 'encode') {
		return base64_encode($string);
	} else {
		return $string;
	}
}

// 格式化指定字符串为input
function format_input($string, $targetData = array()) {
	if ($targetData) {
		//将input替换为目标数据
		preg_match_all('/<input.*?>/is', $string, $m);
		if ($m[0]) {
			foreach ($m[0] as $key => $value) {
				$string = str_replace_limit($value, '<u>' . $targetData[$key] . '</u>', $string, 1);
			}
		}
	} else {
		//将{...}替换为input数据
		preg_match_all('/\{\d+\}/i', $string, $m);
		if ($m[0]) {
			foreach ($m[0] as $key => $value) {
				$num = preg_replace('/\{(\d*)\}/i', '$1', $value);
				if ($num > 0) {
					$string = str_replace_limit($value, '<input type="text" name="content[]" style="width:' . ($num * 5) . 'px">', $string, 1);
				}
			}
		}
	}
	return $string;
}

/**
 * 对字符串执行指定次数替换
 * @param  Mixed $search   查找目标值
 * @param  Mixed $replace  替换值
 * @param  Mixed $subject  执行替换的字符串／数组
 * @param  Int   $limit    允许替换的次数，默认为-1，不限次数
 * @return Mixed
 */
function str_replace_limit($search, $replace, $subject, $limit = -1) {
	if (is_array($search)) {
		foreach ($search as $k => $v) {
			$search[$k] = '`' . preg_quote($search[$k], '`') . '`';
		}
	} else {
		$search = '`' . preg_quote($search, '`') . '`';
	}
	return preg_replace($search, $replace, $subject, $limit);
}

/**
 * 日志记录函数，用于调试时使用
 * @access      public
 * @param       $content 请求的数据
 * @param       $logDir 保存的目录
 * @param       $extData 返回的数据
 * @return      Boolean
 * @author      iclubs <iclubs@126.com>
 */
function act_log($content, $logDir = 'debug', $extData = array()) {
	$logDir = RUNTIME_PATH . rtrim($logDir, '/') . '/';
	if (!is_dir($logDir)) {
		mkdir($logDir, 0755, true);
	}
	if (is_array($content)) {
		$content = http_build_query($content);
	}

	$fp = fopen($logDir . date('Ymd') . '.log', "a+");
	$content = "操作页面: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "\n提交方式: " . $_SERVER["REQUEST_METHOD"] . "\n提交数据: " . $content;
	if ($extData) {
		if (is_array($extData)) {
			$extContent = http_build_query($extData);
		}

		$extContent = "\r\n返回数据: " . $extContent;
	} else {
		$extContent = "";
	}
	$string = $content . $extContent . "\n操作IP: " . realip() . "\n操作时间: " . strftime("%Y-%m-%d %H:%M:%S") . "\n\n";
	fputs($fp, $string);
	fclose($fp);
	return true;
}

function sendsms($phone, $type = 'reg', $message = '', $param = array()) {
	if (empty($phone)) {
		return false;
	}
	$smsClass = new \app\common\tool\Sms();
	return $smsClass->sendSms($phone, $type, $message, $param);
}
function verifysms($phone, $code) {
	if (!is_phone($phone)) {
		return false;
	}
	$smsClass = model('Sms');
	$sms_info = $smsClass->fetchSmsInfo(array('phone' => $phone));
	if (empty($sms_info) || $code != $sms_info['ValidateCode']) {
		return false;
	}
	return true;
}

//随机生成唯一性的13位数字id，全局表主键ID使用此方法
function uniqueid($prefix = '8') {
	return $prefix . substr(time(), -8) . substr(microtime(), 2, 4) . sprintf('%02d', rand(0, 99));
	// return date('Y').substr(time(),-4).substr(microtime(),2,3).sprintf('%02d',rand(0,99));
}

function is_mobile($phone) {
	$search = '/^(1[3456789])[0-9]{9}$/';
	if (preg_match($search, $phone)) {
		return true;
	} else {
		return false;
	}
}

/**
 * 上传通用函数
 * @param     $files 文件源
 * @param     $saveDir 保存的目录
 * @param     $type 上传的文件类型，不同类型调用不同方法，默认图片
 * @param     $thumb 缩略图 格式 宽x高 如：300x200，空不生成缩略图
 * @param     $water 水印 false 不加水印，true 加水印
 * @param     $server 第三方储存服务，默认local(本地储存)、qiniu(七牛云)、upyun(又拍云)、aliyun(阿里云)
 * @return    array 成功后的文件信息
 */
function upload($files, $saveDir = '', $type = 'images', $thumb = '', $water = false, $server = 'local') {
	$uploadClass = new \tool\Upload(UPLOAD_PATH); //上传类
	if ($type == 'images') {
		$fileinfo = $uploadClass->saveImg($files, '', $saveDir); //上传到本地服务器
	} else {
		$fileinfo = $uploadClass->saveFile($files, '', $saveDir); //上传到本地服务器
	}

	if ($type == 'images') {
		//缩略图，尺寸缩放
		$thumbArr = explode('x', $thumb);
		if (count($thumbArr) == 2) {
			$thumb_img = $uploadClass->makeThumbGD($fileinfo['fileroot'], $thumbArr[0], $thumbArr[1], 'center');
		}
		//水印
		if ($water === true) {
			$waterinfo = $uploadClass->setWater($fileinfo['fileroot'], UPLOAD_PATH . 'images/water.jpg', 9, 'img', '');
		}
	}

	$result = false;
	if ($server == 'qiniu') {
		$qiniu = [];
		//上传到七牛云
		$qiniuClass = new \tool\Qiniu();
		if (isset($fileinfo['fileroot']) && $fileinfo['fileroot']) {
			$qiniu['original'] = $qiniuClass->saveFile($fileinfo['fileroot'], $fileinfo['fileurl']);
			$qiniu['original']['screenshot'] = $type=='video' ? $qiniuClass->videoScreenshot($qiniu['original']['key']) : '';
			@unlink($fileinfo['fileroot']);
		}
		if (isset($thumb_img['fileroot']) && $thumb_img['fileroot']) {
			$qiniu['thumb'] = $qiniuClass->saveFile($thumb_img['fileroot'], $thumb_img['fileurl']);
			@unlink($thumb_img['fileroot']);
		}
		if($qiniu) {
			$result = $fileinfo;
			$result['qiniu'] = $qiniu;
		}
	} else {
		$result = $fileinfo;
	}

	if ($result) {
		return array('status' => 1, 'msg' => '上传成功', 'data' => $result);
	} else {
		return array('status' => 0, 'msg' => $uploadClass->sys_msg['msg'], 'data' => '');
	}
}

/**
 * 多语言平台加解密函数 php .net javascript java
 * str 要加密的内容，pwd 加解密用到的key
 * @return       String
 * @author       iclubs <iclubs@126.com>
 * @copyright    Copyright (c) Date, Openver.com
 * @link         http://www.openver.com
 */
function str_encrypt($str, $pwd) {
	if (empty($pwd) || strlen($pwd) <= 0) {
		// die("请输入用于加密信息的密码。");
		return false;
	}
	$str = (string) $str;
	$prand = "";
	for ($i = 0; $i < strlen($pwd); $i++) {
		$prand .= ord($pwd[$i]);
	}
	$sPos = (int) floor(strlen($prand) / 5);
	$mult = intval($prand[$sPos] . $prand[$sPos * 2] . $prand[$sPos * 3] . $prand[$sPos * 4] . (strlen($prand) > $sPos * 5 ? $prand[$sPos * 5] : ''));
	$incr = ceil(strlen($pwd) / 2);
	$modu = pow(2, 31) - 1;
	if ($mult < 2) {
		// die("算法找不到合适的哈希值，请选择一个更复杂或更长的密码。");
		return false;
	}
	$salt = round(random(0, 1) * 1000000000) % 100000000;
	$prand .= $salt;
	$k = strlen($prand) - 11;
	while (strlen($prand) > 10) {
		if (strlen($prand) <= $k) {
			$k = 10;
		}

		$prand = substr($prand, 0, 10) + substr($prand, $k, strlen($prand) - 10);
	}
	$prand = (int) fmod(($mult * $prand + $incr), $modu);
	$enc_chr = "";
	$enc_str = "";
	for ($i = 0; $i < strlen($str); $i++) {
		$enc_chr = intval(ord($str[$i]) ^ floor(($prand / $modu) * 255));
		if ($enc_chr < 16) {
			$enc_str .= "0" . dechex($enc_chr);
		} else {
			$enc_str .= dechex($enc_chr);
		}
		$prand = (int) fmod(($mult * $prand + $incr), $modu);
	}
	$salt = dechex($salt);
	while (strlen($salt) < 8) {
		$salt = "0" . $salt;
	}

	return $enc_str . $salt;
}
function str_decrypt($str, $pwd) {
	if (empty($str) || strlen($str) < 8) {
		// die("解密失败！加密消息长度太短无法提取salt值。");
		return false;
	}
	if (empty($pwd) || strlen($pwd) <= 0) {
		// die("解密失败！请输入用于加密信息的密码。");
		return false;
	}
	$prand = "";
	for ($i = 0; $i < strlen($pwd); $i++) {
		$prand .= ord($pwd[$i]);
	}
	$sPos = (int) floor(strlen($prand) / 5);
	$mult = intval($prand[$sPos] . $prand[$sPos * 2] . $prand[$sPos * 3] . $prand[$sPos * 4] . (strlen($prand) > $sPos * 5 ? $prand[$sPos * 5] : ''));
	$incr = ceil(strlen($pwd) / 2);
	$modu = pow(2, 31) - 1;
	$tmp_len = strlen($str) - 8;
	$salt = intval(substr($str, $tmp_len, strlen($str) - $tmp_len), 16);
	$str = substr($str, 0, $tmp_len);
	$prand .= $salt;
	$k = strlen($prand) - 11;
	while (strlen($prand) > 10) {
		if (strlen($prand) <= $k) {
			$k = 10;
		}

		$prand = substr($prand, 0, 10) + substr($prand, $k, strlen($prand) - 10);
	}
	$prand = (int) fmod(($mult * $prand + $incr), $modu);
	$enc_chr = "";
	$enc_str = "";
	for ($i = 0; $i < strlen($str); $i += 2) {
		$enc_chr = intval(intval(substr($str, $i, 2), 16) ^ floor(($prand / $modu) * 255));
		$enc_str .= fromCharCode($enc_chr);
		$prand = (int) fmod(($mult * $prand + $incr), $modu);
	}
	return $enc_str;
}
//实现 js random()
function random($min = 0, $max = 1) {
	return $min + mt_rand() / mt_getrandmax() * ($max - $min);
}
//实现 js fromCharCode()
function fromCharCode($codes) {
	if (is_scalar($codes)) {
		$codes = func_get_args();
	}

	$str = '';
	foreach ($codes as $code) {
		$str .= chr($code);
	}

	return $str;
}

/**
 * 转换当前模型对象为数组
 * @param     object
 * @return    array
 */
function obj2arr($res) {
	if (empty($res) || is_scalar($res)) {
		return $res;
	} elseif (is_object($res)) {
		return $res->toArray();
	}
	foreach ($res as $key => $value) {
		if (is_object($value)) {
			$res[$key] = $value->toArray();
		} else {
			$res[$key] = $value;
		}
	}
	return $res;
}
function array2object($array) {
	if (is_array($array)) {
		$obj = new StdClass();
		foreach ($array as $key => $val) {
			$obj->$key = $val;
		}
	} else {
		$obj = $array;
	}
	return $obj;
}
function object2array($object) {
	$array = array();
	if (is_object($object)) {
		foreach ($object as $key => $value) {
			$array[$key] = $value;
		}
	} else {
		$array = $object;
	}
	return $array;
}
/**
 * 数据安全转义
 * @param string $value Raw string
 * @return string       Quoted string
 */
function safe_quote($value) {
	if (is_int($value)) {
		return $value;
	} elseif (is_float($value)) {
		return sprintf('%F', $value);
	}
	return "'" . addcslashes($value, "\000\n\r\\'\"\032") . "'";
}
function request_url($url, $param = array(), $method = 'post', $type = 'json', $header = false, $timeout = 20) {
	if (!isset($param['access_token'])) {
		$param['access_token'] = my_encrypt('access_time=' . time() . '&app_id=' . config('api_appid'), 'encode', config('api_key')); //加密
	}
	$url = strpos($url, '?') ? $url . '&' : $url . '?';
	$method = strtolower($method);
	$ch = curl_init();
	if (stripos($url, "https://") !== false) {
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	}
	if ($header === false) {
		curl_setopt($ch, CURLOPT_HEADER, 0);
	} else {
		curl_setopt($ch, CURLOPT_HEADER, 1);
	}
	if ($method == 'get') {
		curl_setopt($ch, CURLOPT_URL, $url . http_build_query($param));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	} else {
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		if ($type == 'json') {
			$param = empty($param) ? json_encode(new stdClass()) : json_encode($param);
			$headers = array(
				"Content-type: application/json;charset='utf-8'",
				"Accept: application/json",
				"Content-Length: " . strlen($param),
				"Cache-Control: no-cache",
				"Pragma: no-cache",
			);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		} else {
			$param = http_build_query($param);
		}
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	}
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	$output = curl_exec($ch);
	// curl_error($ch);
	curl_close($ch);
	return $output;
}
function my_encrypt($string, $operation = 'decode', $key = '', $expiry = 0) {
	$operation = strtolower($operation);
	$string = $operation == 'decode' ? str_replace('|', '+', $string) : $string;
	$ckey_length = 20;
	$key = md5($key ? $key : '7JdedocQ1wb3r78sba53h8dK3oai4wrgwrfE0jdgPjqn7r2h9d8y2Y4ow35z');
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'decode' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
	$cryptkey = $keya . md5($keya . $keyc);
	$key_length = strlen($cryptkey);
	$string = $operation == 'decode' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for ($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	for ($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for ($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	if ($operation == 'decode') {
		if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		$string = $keyc . str_replace('=', '', base64_encode($result));
		return str_replace('+', '|', $string);
	}
}

function realip() {
	$realip = '';
	if (isset($_SERVER)) {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			foreach ($arr AS $ip) {
				$ip = trim($ip);
				if ($ip != 'unknown') {
					$realip = $ip;
					break;
				}
			}
		} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$realip = $_SERVER['HTTP_CLIENT_IP'];
		} else {
			if (isset($_SERVER['REMOTE_ADDR'])) {
				$realip = $_SERVER['REMOTE_ADDR'];
			} else {
				$realip = '0.0.0.0';
			}
		}
	} else {
		if (getenv('HTTP_X_FORWARDED_FOR')) {
			$realip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif (getenv('HTTP_CLIENT_IP')) {
			$realip = getenv('HTTP_CLIENT_IP');
		} else {
			$realip = getenv('REMOTE_ADDR');
		}
	}
	preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
	$realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
	return $realip;
}
/**
 * js escape php 实现方法
 * @param $string
 * @param $in_encoding
 * @param $out_encoding
 */
function escape($string, $in_encoding = 'UTF-8', $out_encoding = 'UCS-2') {
	$return = '';
	if (function_exists('mb_get_info')) {
		for ($x = 0; $x < mb_strlen($string, $in_encoding); $x++) {
			$str = mb_substr($string, $x, 1, $in_encoding);
			if (strlen($str) > 1) {
				// 多字节字符
				$return .= '%u' . strtoupper(bin2hex(mb_convert_encoding($str, $out_encoding, $in_encoding)));
			} else {
				$return .= '%' . strtoupper(bin2hex($str));
			}
		}
	}
	return $return;
}
function unescape($str) {
	$ret = '';
	$len = strlen($str);
	for ($i = 0; $i < $len; $i++) {
		if ($str[$i] == '%' && $str[$i + 1] == 'u') {
			$val = hexdec(substr($str, $i + 2, 4));
			if ($val < 0x7f) {
				$ret .= chr($val);
			} else
			if ($val < 0x800) {
				$ret .= chr(0xc0 | ($val >> 6)) .
				chr(0x80 | ($val & 0x3f));
			} else {
				$ret .= chr(0xe0 | ($val >> 12)) .
				chr(0x80 | (($val >> 6) & 0x3f)) .
				chr(0x80 | ($val & 0x3f));
			}

			$i += 5;
		} else
		if ($str[$i] == '%') {
			$ret .= urldecode(substr($str, $i, 3));
			$i += 2;
		} else {
			$ret .= $str[$i];
		}

	}
	return $ret;
}

//检查密码有效性
function check_password($password) {
	if (mb_strlen($password) < 6 || mb_strlen($password) > 16) {
		return false;
	}
	return true;
}
function password($inputpwd, $encrypt = '') {
	if (empty($encrypt)) {
		$encrypt = substr(uniqid(rand()), -6);
	}
	$password = md5(md5($inputpwd) . $encrypt);
	return array('password' => $password, 'encrypt' => $encrypt);
}

function get_file_mime($suffix) {
	$arr = array(
		'acx' => 'application/internet-property-stream',
		'ai' => 'application/postscript',
		'aif' => 'audio/x-aiff',
		'aifc' => 'audio/x-aiff',
		'aiff' => 'audio/x-aiff',
		'asp' => 'text/plain',
		'aspx' => 'text/plain',
		'asf' => 'video/x-ms-asf',
		'asr' => 'video/x-ms-asf',
		'asx' => 'video/x-ms-asf',
		'au' => 'audio/basic',
		'avi' => 'video/x-msvideo',
		'axs' => 'application/olescript',
		'bas' => 'text/plain',
		'bcpio' => 'application/x-bcpio',
		'bin' => 'application/octet-stream',
		'bmp' => 'image/bmp',
		'c' => 'text/plain',
		'cat' => 'application/vnd.ms-pkiseccat',
		'cdf' => 'application/x-cdf',
		'cer' => 'application/x-x509-ca-cert',
		'class' => 'application/octet-stream',
		'clp' => 'application/x-msclip',
		'cmx' => 'image/x-cmx',
		'cod' => 'image/cis-cod',
		'cpio' => 'application/x-cpio',
		'crd' => 'application/x-mscardfile',
		'crl' => 'application/pkix-crl',
		'crt' => 'application/x-x509-ca-cert',
		'csh' => 'application/x-csh',
		'css' => 'text/css',
		'dcr' => 'application/x-director',
		'der' => 'application/x-x509-ca-cert',
		'dir' => 'application/x-director',
		'dll' => 'application/x-msdownload',
		'dms' => 'application/octet-stream',
		'doc' => 'application/msword',
		'dot' => 'application/msword',
		'dvi' => 'application/x-dvi',
		'dxr' => 'application/x-director',
		'eps' => 'application/postscript',
		'etx' => 'text/x-setext',
		'evy' => 'application/envoy',
		'exe' => 'application/octet-stream',
		'fif' => 'application/fractals',
		'flr' => 'x-world/x-vrml',
		'flv' => 'video/x-flv',
		'gif' => 'image/gif',
		'gtar' => 'application/x-gtar',
		'gz' => 'application/x-gzip',
		'h' => 'text/plain',
		'hdf' => 'application/x-hdf',
		'hlp' => 'application/winhlp',
		'hqx' => 'application/mac-binhex40',
		'hta' => 'application/hta',
		'htc' => 'text/x-component',
		'htm' => 'text/html',
		'html' => 'text/html',
		'htt' => 'text/webviewhtml',
		'ico' => 'image/x-icon',
		'ief' => 'image/ief',
		'iii' => 'application/x-iphone',
		'ins' => 'application/x-internet-signup',
		'isp' => 'application/x-internet-signup',
		'jfif' => 'image/pipeg',
		'jpe' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpg' => 'image/jpeg',
		'js' => 'application/x-javascript',
		'latex' => 'application/x-latex',
		'lha' => 'application/octet-stream',
		'lsf' => 'video/x-la-asf',
		'lsx' => 'video/x-la-asf',
		'lzh' => 'application/octet-stream',
		'm13' => 'application/x-msmediaview',
		'm14' => 'application/x-msmediaview',
		'm3u' => 'audio/x-mpegurl',
		'man' => 'application/x-troff-man',
		'mdb' => 'application/x-msaccess',
		'me' => 'application/x-troff-me',
		'mht' => 'message/rfc822',
		'mhtml' => 'message/rfc822',
		'mid' => 'audio/mid',
		'mny' => 'application/x-msmoney',
		'mov' => 'video/quicktime',
		'movie' => 'video/x-sgi-movie',
		'mp2' => 'video/mpeg',
		'mp3' => 'audio/mpeg',
		'mpa' => 'video/mpeg',
		'mpe' => 'video/mpeg',
		'mpeg' => 'video/mpeg',
		'mpg' => 'video/mpeg',
		'mpp' => 'application/vnd.ms-project',
		'mpv2' => 'video/mpeg',
		'ms' => 'application/x-troff-ms',
		'mvb' => 'application/x-msmediaview',
		'nws' => 'message/rfc822',
		'oda' => 'application/oda',
		'p10' => 'application/pkcs10',
		'p12' => 'application/x-pkcs12',
		'p7b' => 'application/x-pkcs7-certificates',
		'p7c' => 'application/x-pkcs7-mime',
		'p7m' => 'application/x-pkcs7-mime',
		'p7r' => 'application/x-pkcs7-certreqresp',
		'p7s' => 'application/x-pkcs7-signature',
		'pbm' => 'image/x-portable-bitmap',
		'pdf' => 'application/pdf',
		'pfx' => 'application/x-pkcs12',
		'pgm' => 'image/x-portable-graymap',
		'php' => 'text/plain',
		'pko' => 'application/ynd.ms-pkipko',
		'pma' => 'application/x-perfmon',
		'pmc' => 'application/x-perfmon',
		'pml' => 'application/x-perfmon',
		'pmr' => 'application/x-perfmon',
		'pmw' => 'application/x-perfmon',
		'png' => 'image/png',
		'pnm' => 'image/x-portable-anymap',
		'pot,' => 'application/vnd.ms-powerpoint',
		'ppm' => 'image/x-portable-pixmap',
		'pps' => 'application/vnd.ms-powerpoint',
		'ppt' => 'application/vnd.ms-powerpoint',
		'prf' => 'application/pics-rules',
		'ps' => 'application/postscript',
		'pub' => 'application/x-mspublisher',
		'qt' => 'video/quicktime',
		'ra' => 'audio/x-pn-realaudio',
		'ram' => 'audio/x-pn-realaudio',
		'ras' => 'image/x-cmu-raster',
		'rgb' => 'image/x-rgb',
		'rmi' => 'audio/mid',
		'roff' => 'application/x-troff',
		'rtf' => 'application/rtf',
		'rtx' => 'text/richtext',
		'scd' => 'application/x-msschedule',
		'sct' => 'text/scriptlet',
		'setpay' => 'application/set-payment-initiation',
		'setreg' => 'application/set-registration-initiation',
		'sh' => 'application/x-sh',
		'shar' => 'application/x-shar',
		'sit' => 'application/x-stuffit',
		'snd' => 'audio/basic',
		'spc' => 'application/x-pkcs7-certificates',
		'spl' => 'application/futuresplash',
		'src' => 'application/x-wais-source',
		'sst' => 'application/vnd.ms-pkicertstore',
		'stl' => 'application/vnd.ms-pkistl',
		'stm' => 'text/html',
		'svg' => 'image/svg+xml',
		'sv4cpio' => 'application/x-sv4cpio',
		'sv4crc' => 'application/x-sv4crc',
		'swf' => 'application/x-shockwave-flash',
		't' => 'application/x-troff',
		'tar' => 'application/x-tar',
		'tcl' => 'application/x-tcl',
		'tex' => 'application/x-tex',
		'texi' => 'application/x-texinfo',
		'texinfo' => 'application/x-texinfo',
		'tgz' => 'application/x-compressed',
		'tif' => 'image/tiff',
		'tiff' => 'image/tiff',
		'tr' => 'application/x-troff',
		'trm' => 'application/x-msterminal',
		'tsv' => 'text/tab-separated-values',
		'txt' => 'text/plain',
		'uls' => 'text/iuls',
		'ustar' => 'application/x-ustar',
		'vcf' => 'text/x-vcard',
		'vrml' => 'x-world/x-vrml',
		'wav' => 'audio/x-wav',
		'wcm' => 'application/vnd.ms-works',
		'wdb' => 'application/vnd.ms-works',
		'wks' => 'application/vnd.ms-works',
		'wmf' => 'application/x-msmetafile',
		'wmv' => 'video/x-ms-wmv',
		'wps' => 'application/vnd.ms-works',
		'wri' => 'application/x-mswrite',
		'wrl' => 'x-world/x-vrml',
		'wrz' => 'x-world/x-vrml',
		'xaf' => 'x-world/x-vrml',
		'xbm' => 'image/x-xbitmap',
		'xla' => 'application/vnd.ms-excel',
		'xlc' => 'application/vnd.ms-excel',
		'xlm' => 'application/vnd.ms-excel',
		'xls' => 'application/vnd.ms-excel',
		'xlt' => 'application/vnd.ms-excel',
		'xlw' => 'application/vnd.ms-excel',
		'xof' => 'x-world/x-vrml',
		'xpm' => 'image/x-xpixmap',
		'xwd' => 'image/x-xwindowdump',
		'z' => 'application/x-compress',
		'zip' => 'application/zip',
		'avi' => 'video/avi',
		'mp4' => 'video/mp4',
		'mp3' => 'audio/mp3',
		'amr' => 'audio/amr',
		'mpeg' => 'audio/mpeg',
		'doc' => 'application/msword',
	);
	return $arr[$suffix];
}