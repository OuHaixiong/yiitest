<?php

/**
 * @desc 一些公共的自定义函数，工具类；包括删除文件夹、验证是否是邮件、验证是否是ip地址、验证是否为网址、获取网页内容、生成随机字符串等
 * @author bear
 * @version 1.0.3 2011-10-24 17:07
 * @copyright xiqiyanyan.com
 * @created 2011-10-11 16:15
 */
class Common_Tool
{
	private static $_startTime; // 脚本执行开始时间
	private static $_error; // 错误信息
	
	/**
	 * 设置脚本执行开始时间
	 */
	public static function executeStartTime() {
		self::$_startTime = microtime(true);		
	}

	/**
	 * 获取脚本执行时间，运行本函数之前必须先运行 executeStartTime
	 * @return number(integer) 本页脚本执行时间，单位 ： 秒
	 */
	public static function executeEndTime() {
		$endTime = microtime(true);
		return $endTime-self::$_startTime;
	}
	
	/**
	 * 设置错误信息
	 * @param string $e
	 */
	private static function setError($e) {
		self::$_error = $e;
	}
	
	/**
	 * 获取错误信息
	 * @return string
	 */
	public static function getError() {
		return self::$_error;
	}
	
	/**
	 * 获取网页的内容。实践证明比file_get_contents速度快, 并且比 file_get_contents 好很多（稳定）
	 * 如仅是读取本地文件，用原生的file_get_contents显然更合适
	 * @param string $url 网址
	 * @param integer $timeout 超时时间，单位是秒，默认为10s
	 * @return string
	 */
	public static function getWebContent($url, $timeout = 10) {
		if (!extension_loaded('curl')) {
			die('本页脚本不支持curl！');
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
//		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // 貌似有个地方是这样写的，也没有下面一行
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$contents = trim(curl_exec($ch));
		curl_close($ch);
		return $contents;
	}
	
	/**
	 * 获取一个网址的内容或获取文件的内容，和上面的函数是一个功能，不过如果程序不支持curl就必须用这个函数
	 * @param string $url
	 * @param integer $timeout 设置一个超时时间，单位为秒 
	 */
	public static function getFileContent($url, $timeout = 10) {
		$ctx = stream_context_create(array('http'=>array('timeout'=>$timeout)));
		return file_get_contents($url, 0, $ctx);
	}

	//替换页面上所有图片的src为本地图片（未测试，不能用）
	function ReplaceImageSrc($text){
		$Pattern = array(
			'/<img[^<>]*? src="http:\/\/.*\/(.*?\.(jpg|gif))"[^<>]*?>/i',
			'/<img src="http:\/\/.*\/(.*?\.(jpg|gif)) "[^<>]*?\/>/i',
			'/<img[^<>]*?src=http:\/\/[^<>]*?\/([^<>\/]*?\.(jpg|gif))[^<>]*?>/i'
		);
		$Replace = array(
			'<img src="download/xmls/news/$1"'.' />',
			'<img src="download/xmls/news/$1"'.' />',
			'<img src="download/xmls/news/$1"'.' />',
		);
		
		return preg_replace($Pattern, $Replace, $text);
	}
	

	/**
	 * 删除文件夹；递归删除给定路径（目录）的所有文件和文件夹 （这个是垃圾不能使用）
	 * @param  string $dir 需要删除的目录路径（最后带 / 和不带都是一样的）
	 * @return boolean flase：不是文件夹 ；true：删除成功
	 */
	public static function delDir($dir) { // 不要使用这个，使用下面那个
		if (!is_dir($dir)) return false;
		$dh = opendir($dir);
		$file = readdir($dh);
		while ($file) {
			if ($file!='.' && $file!='..') {
				$fullpath = $dir . '/' . $file;
				if (is_dir($fullpath)) {
					self::delDir($fullpath);
				} else {
					@unlink($fullpath);
				}
			}
		}
		closedir($dh);
		return @rmdir($dir); // false 删除空文件夹失败
	}
	
	/**
	 * 删除文件夹(目录)及此文件夹下的所有文件和文件夹；不能用来删除文件，删除文件建议用 unlink($dirnm)
	 * 注意： 此函数依赖于本脚本 dirIsEmpty 函数
	 * @param string $dir 需要删除的目录路径（最后带 / 和不带都是一样的）
	 * @return boolean flase：不是文件夹 ；true：删除成功
	 * @see www.smartwei.com 
	 * @author Ritesh Patel  patel.ritesh.mscit@gmail.com
	 */
	public static function removeDir($dir) { // 提倡使用这个，不要使用上面那个，上面的那个是垃圾
		if (!is_dir($dir)) {
			return false;
		}
		$d = dir($dir);
		while (false !== ($entry = $d->read())) {
			if ($entry=='.' || $entry=='..') continue;
			$currele = $d->path . '/' . $entry;
			if (is_dir($currele)) {
				if (self::dirIsEmpty($currele)) {
					@rmdir($currele);
				} else {
					self::removeDir($currele); // 递归函数 。
				}
			} else {
				@unlink($currele);
			}
		}
		$d->close();
		@rmdir($dir);
		return true;
	}

	/**
	 * 判断目录是否为空
	 * @param string $path 目录路径
	 * @return boolean
	 */
	public static function dirIsEmpty($path) {
		$handle = opendir($path);
		$i = 0;
		while(false !== ($file = readdir($handle))) {
			$i++;
		}
		closedir($handle);
		if ($i > 2) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 通过js 的 alert 提示一条信息
	 * @param string 
	 */
	public static function showMessage($msg) {
		echo ('<script language="javascript" type="text/javascript">alert("' . $msg . '");</script>');
	}

	/**
	 * 通过 js 显示提示一条信息，并跳转页面，如果不传 url 可以不用跳转页面
	 * @param string $message 要显示的信息
	 * @param string $url 要跳转的 url 地址
	 */
	public static function alertMessageAndSkipUrl($message, $url=null) {
		echo ('<script language="javascript" type="text/javascript">alert("' . $message .'");');
		if ($url !== null) {
			echo ('window.location.href="' . $url . '";');
		}
		echo ('</script>');
	}

	/**
	 * 检查变量存在并赋值（不能为空）。 注意 0 是为空的
	 * @param mixed $variable
	 * @return boolean
	 */
	public static function checkVariableIssetAndNotEnpty($variable) {
		if (isset($variable) && !empty($variable)) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 检查某个键是否存在某数组中，并且有值，且值不能为空或0（貌似不是很实用）
	 * @param string $key
	 * @param array $array
	 * @return boolean
	 */
	public static function keyExistsArray($key, $array) {
		if (array_key_exists($key, $array) && isset($array[$key]) && !empty($array[$key])) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 过滤用户输入字符串中的特殊字符; 检查sql注入,过滤 ', " 和sql注释：--, #, / *    * /	（貌似不是很实用，真正防注入还得写一个公共类比较好）
	 * @param string $str
	 * @return string
	 */
	public static function checkInput($str) {
		$str = trim($str);
		$str = htmlspecialchars($str); // htmlspecialchars() 过滤<,>,&,"和'；转化成实体
		$patterns = array('/#+/', '/(--)+/', '/(\/\*)+/', '/(\*\/)+/', '/"+/', '/(\')+/');
		$replacements = array(' ', '-&nbsp;-', '\&nbsp;*', '*&nbsp; /', '&quot;', '&#039;');
		return preg_replace($patterns, $replacements, $str);//
	}
	
	/**
	 * 获取document(该页面上)中的所有图片，并保存到指定的目录 (已测试通过)
	 * 该函数依赖 createdDirectory() 函数
	 * @param string $text 
	 * @param string $folderPath 需要保存到哪里，绝对路径，后面是否带 / 没有关系
	 * @param string $host 传进来的网页主机头； 暂时后面一定要带/
	 * 暂时没有返回值
	 */
	public static function getAllImages($text, $folderPath, $host = 'http://www.xiqiyanyan.com/') {
		$imgPattern = '/<img [^<>]*?src\s*\=\s*[\'|\"]([^<>\'\"]+?\.(jpg|jpeg|gif|png))[\'|\"][^>]*>/i';
		// 这里先这样写着，暂时能用，等遇到不能用时再改
		// $imgPattern = '/<img [^>]*src\s*\=\s*[\"\']?\s*([^>\"\'\s]*)\.(jpg|jpeg|gif|png)[\"\'][^>]*\/?这里无空格>/i';  // 这个也是可以的
		preg_match_all($imgPattern, $text, $imgMatches); 
// 		print_r($imgMatches);exit;
		$pattern = '/<img [^<>]*?src\s*\=\s*[\'\"](http:\/\/[^<>]*?\/[^<>]*?\.(jpg|jpeg|gif|png))[\'\"][^<>]*?>/i'; // 第一中情况就把有http的包含进去来，所以这里没有必要了
		preg_match_all($pattern, $text, $matches); 
// 		print_r($matches);exit;
		$imgUrlList = array_merge($imgMatches[1],$matches[1]); // print_r($imgUrlList);exit;
		foreach ($imgUrlList as $imgUrl) {
			$boolean = strpos($imgUrl, $host);
			if ($boolean === false) {
				if (substr($imgUrl, 0, 1) == '/') {
					$imgUrl = substr($imgUrl, 1);
				}
				$imgUrl = $host . $imgUrl;
			}
			$content = file_get_contents($imgUrl);
			$localImageUrl = ''; // 文件名
			$localImageUrl = explode('/', $imgUrl);
			$localImageUrl = $localImageUrl[sizeof($localImageUrl)-1]; // sizeof() 是 count() 的别名
			//deldir("download/xmls/news/".date("d",strtotime ("-2 day")) );
			if (!file_exists($folderPath)) {// 建img保存目录 。  注意了。此句不可缺少
//				@$boolean = mkdir($folderPath, 0700); // 貌似 0700 是可读写的意思吧； 默认的 mode 是 0777，意味着最大可能的访问权
//				if ($boolean == false) {
//					die('无法创建文件夹（目录）；可能是文件目录不存在，或没有权限');
//				}
				self::createdDirectory($folderPath);
			}
			if ($folderPath[strlen($folderPath)-1] !== '/') {
				$folderPath .= '/';
			}
			@$numberBit = file_put_contents($folderPath . $localImageUrl, $content); // 这一句当后面的这几行
			// file_put_contents()  将一个字符串写入文件。如果成功返回写入到文件内数据的字节数；如果失败返回false
			if ($numberBit === false) {// 貌似这里不需要
				die('写入文件失败！可能没有写的权限！');
			}
/*			if (false === ($fileOpen = fopen($folderPath . $localImageUrl, 'w'))) {
				die('open local image failed');
			}
			if (false === fwrite($fileOpen, $content)) {
				die('write local image failed');
			}
			if (!fclose($fileOpen)) die('close local image failed');*/
		}
	}
	
	/**
	 * 创建目录（文件夹）路径下的所有目录 , created all folder
	 * @param string $directoryPath 要创建的文件目录（文件夹）路径，这里只能是绝对路径，不能是相对路径，也不能是zend的重写路径（如 ‘/upload/img/’）或http://这样的路径 ( linux 除外，因为linux默认文件分割路径就是 /usr/dir )
	 * 在类中，路径最好是绝对路径比较好，如果是过程化的话也可以相对路径 
	 * @return false 有错误信息； true 成功
	 */
	public static function createdDirectory($directoryPath) { // created all folder
		$directoryPath = trim($directoryPath);
		if (!$directoryPath) {
			$errorMessage = '文件路径为空！';  // 提示错误信息
			self::setError($errorMessage);
			return false;
		}
		$directoryPath = str_replace('\\', '/', $directoryPath);
		if ($directoryPath[strlen($directoryPath)-1] == '/') {
			$directoryPath = substr($directoryPath, 0,-1);
		}
		$pattern = '/([a-zA-Z]+:\/)?/'; 
		preg_match($pattern, $directoryPath, $prefixFolder);
		$createdFolder = $prefixFolder[0];
		$directoryPath = str_replace($createdFolder, '', $directoryPath);
		$arrayFolder = explode('/', $directoryPath);
		foreach ($arrayFolder as $folder) {
			$folder = trim($folder);
			$parentFolder = $createdFolder;
			$createdFolder .= $folder . '/';
			if (!file_exists($createdFolder)) {
				if (!is_writeable($parentFolder)) {
					$errorMessage = "无法新建文件夹（目录）；请确认文件夹路径： $parentFolder 是否有写的权限！";
					self::setError($errorMessage);
					return false;
				}
				mkdir($createdFolder, 0777);
			}
		}
		return true;	
	}
	
	/**
	 * 除去 img 和 object 标签
	 * @param string $content
	 * @return string
	 */
	public static function clearImageAndObject($content) {
		$imgPattern = '/<img.*?>/is';//去除image
		$content = preg_replace($imgPattern, '', $content);
		$objectPattern = '/<object.*?>(.*?<\/object>)?/is';//去除object：视频，音频，flash等
		$content = preg_replace($objectPattern, '', $content);
		return $content;
	}

	/**
	 * 判断字符串是否是utf-8编码(一般来讲utf-8是针对中文字符来说的)
	 * @param string $string 需要判断的字符串
	 * @return number(integer 1:是； 0:否)
	 */
	public static function isUtf8($string) {
		return preg_match('%^(?:[\x09\x0A\x0D\x20-\x7E] # ASCII|[\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte|\xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte|\xED[\x80-\x9F][\x80-\xBF] # excluding surrogates|\xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3|[\xF1-\xF3][\x80-\xBF]{3} # planes 4-15|\xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16)*$%xs', $string);
	}

	/**
	 * 字符串根据指定长度自动换行
	 * @param string $string 需要转换的字符串
	 * @param integer $limit 指定长度
	 * @param string $encoding 字符串编码
	 * @return string 转换后的字符串
	 */
	static function cutString($string, $limit, $encoding) {
		$arrStr = '';
		$intTotal = mb_strlen($string, $encoding);
		$intNum = ceil($intTotal/$limit);
		if ($intNum==1) {
			return $string;
		} else {
			for ($i=0; $i<$intNum; $i++) {
				$startNum = $limit*$i;
				$arrStr .= mb_substr($string, $startNum, $limit, $encoding) . "\n";
			}
			return $arrStr;
		}
	}
	
	/**
	 * 判断属性是否存在对象中并且不能为null
	 * @param object $object
	 * @param string $property
	 * @return boolean
	 */
	public static function propertyExists($object, $property) {
		return isset($object->$property);
	}

	/**
	 * 判断一个属性是否存在对象中，如果存在就返回属性的值，否则返回false（此函数依赖 propertyExists() 函数）
	 * @param object $object 对象实例
	 * @param string $property 属性名称
	 * @return boolean | mixed 
	 */
	public static function getProperty($object, $property) {
		if (self::propertyExists($object, $property)) {
			return $object->$property;
		} else {
			return false;
		}
	}

	/**
	 * 数组转对象（Convert the array to objects）
	 * @param array $array 可以是二维数组，也可以数组中有对象、也有数组
	 * @return object stdClass
	 */
	public static function arrayToObject($array) {
		if (is_array($array)) {
			$object = new stdClass();
			foreach ($array as $key=>$value) {
				$object->$key = self::arrayToObject($value);
			}
			return $object;
		} else { 
			return $array;
		}
	}
	
	/**
	 * 显示客户端ip地址函数
	 * @return string
	 */
	public static function clientRealIp() {
		if (isset($_SERVER))
		{
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
			{
				$arr = explode(',', $_SERVER["HTTP_X_FORWARDED_FOR"]);/* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
				foreach ($arr AS $ip)
				{
					$ip = trim($ip);
					if ($ip != 'unknown')
					{$realip = $ip;break;}
				}
			}
			elseif (isset($_SERVER["HTTP_CLIENT_IP"]))
			{$realip = $_SERVER["HTTP_CLIENT_IP"];}
			else
			{$realip = $_SERVER["REMOTE_ADDR"];}
		}
		else
		{
			if (getenv('HTTP_X_FORWARDED_FOR'))
			{$realip = getenv('HTTP_X_FORWARDED_FOR');}
			elseif (getenv('HTTP_CLIENT_IP'))
			{$realip = getenv('HTTP_CLIENT_IP');}
			else
			{$realip = getenv('REMOTE_ADDR');}
		}
		return $realip;
	}
	
	/**
	 * 获取客户端(访问者)的IP（get the ip address）
	 * @return string | unknown 成功返回客户端的ip，失败返回 unknown
	 */
	public static function getIP() { // var_dump(getenv("HTTP_CLIENT_IP"));exit;
		//getenv($varname); 获取一个环境变量的值;如获取返回该变量值，否则返回 false。 如：$ip = getenv('REMOTE_ADDR');var_dump($ip); 获取ip
		if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
			$ip = getenv('HTTP_CLIENT_IP');
			// strcasecmp($str1, $str2)  二进制安全比较字符串（不区分大小写）;  如果 str1 小于 str2，返回负数；如果 str1 大于 str2，返回正数；二者相等则返回 0。 
		} elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
			$ip = getenv('REMOTE_ADDR');
		} elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
			$ip = $_SERVER['REMOTE_ADDR'];
		} else {
			$ip = 'unknown';
		}
		return $ip;	
	}
	
	/**
	 * 获取来源地址（get the http_referer url）;  即是哪个页面跳转过来的;上一页面的url；前一页的地址
	 * @return string | null 来源网址
	 */
	public static function getRefererUrl() {
		return isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER'] != '') ? $_SERVER['HTTP_REFERER'] : '';
	}
	
	/**
	 * 获取当前的网址（get the current url）; base URL
	 * @return string
	 */
	public static function getCurrentUrl() {
		return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		// $_SERVER['REQUEST_URI'] // 访问此页面所需的 URI。例如，“/index.html?s=123”。即当前执行脚本的路径，包括所得差数；
//		$currentPath = $_SERVER['PHP_SELF']; // 根目录到本脚本的路径，包括文件名。貌似在zend中无法正确返回，返回的是/index.php
//		print_r($currentPath);exit;
	}
	
	/**
	 * 判断上一个页面是否为 post 提交（check the request method is post）
	 * @return boolean
	 */
	public static function isPost() {
		return strtoupper($_SERVER['REQUEST_METHOD']) == 'POST';
//		$request = $this->getRequest(); // $this 指的是控制器
//		return $request->isPost();
	}

	/**
	 * 判断上一页面是否为 get 提交（check the request method is get）
	 * @return boolean
	 */
	public static function isGet() {
		return strtolower($_SERVER['REQUEST_METHOD']) == 'get';
	}
	
	/**
	 * 设置头文件（set the header the deafault is header('Content-type:text/html; charset=utf-8');）
	 * 注意：虽然通过判断能减少多写页面，但不要把危险的数据写到客户端才是正道。即如果数据库读出有危险的数据还是另起页面比较好
	 * @param string $contentType
	 * @param string $charSet
	 */
	public static function header($contentType = 'text/html', $charSet = 'utf-8') {
		header('Content-type:' . $contentType . '; charset=' . $charSet);
	}

	/**
	* 
	* 根据地址信息获取经纬度(待测)
	* 参数：$addressStr地址字符串
	* 返回值：
	* 正常获取：字符串："纬度||经度"
	* 异常：-2：请求频率过高；-1：获取失败
	* @param string $addressStr  
	*/
//	public static function getLocation($addressStr)
//	{
//		$returnStr = "";
//		$base_url = "http://maps.google.com/maps/geo?output=json";
//		$request_url = $base_url . "&q=" . urlencode($addressStr);
//		$returnStr =  P_Putils_Common::ucFopen( $request_url );
//		return $returnStr;
//	}
	
	
	/**
	 * 
	 * 概据经伟度去返回相应的地址（待测）
	 * @param unknown_type $pointx
	 * @param unknown_type $pointy
	 */
//	public static function getReLocation($pointx,$pointy)
//	{
//		$request_url = "http://maps.google.com/maps/api/geocode/json?latlng=$pointx,$pointy&sensor=true_or_false";
//		$request_url = "http://maps.google.com/maps/api/geocode/json?latlng=40.714224,-73.961452&sensor=true_or_false";
//	    $returnStr =  P_Putils_Common::ucFopen( $request_url );
//		return $returnStr;
//	}
	
	/**
	 * 
	 * get the urlContent（待测）
	 * @param string $url
	 */
	// ------------------------------------------------------------------------
	public static  function ucFopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
		$return = '';
		$matches = parse_url($url);
		!isset($matches['host']) && $matches['host'] = '';
		!isset($matches['path']) && $matches['path'] = '';
		!isset($matches['query']) && $matches['query'] = '';
		!isset($matches['port']) && $matches['port'] = '';
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;
		if($post) {
			$out = "POST $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			//$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= 'Content-Length: '.strlen($post)."\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cache-Control: no-cache\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
			$out .= $post;
		} else {
			$out = "GET $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			//$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}
	
		if(function_exists('fsockopen')) {
			$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		} elseif (function_exists('pfsockopen')) {
			$fp = @pfsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		} else {
			$fp = false;
		}
	
		if(!$fp) {
			return '';
		} else {
			stream_set_blocking($fp, $block);
			stream_set_timeout($fp, $timeout);
			@fwrite($fp, $out);
			$status = stream_get_meta_data($fp);
			if(!$status['timed_out']) {
				while (!feof($fp)) {
					if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
						break;
					}
				}
	
				$stop = false;
				while(!feof($fp) && !$stop) {
					$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
					$return .= $data;
					if($limit) {
						$limit -= strlen($data);
						$stop = $limit <= 0;
					}
				}
			}
			@fclose($fp);
			return $return;
		}
	}	

	
	/**
	 * 判断一个url地址是否为图片(check the string is a picture)
	 * @param string $url
	 * @return boolean
	 */
	public static function isPic($url) {
		$arrayPic = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
		$pathInfo = pathinfo($url); // pathinfo 返回文件路径的信息;实际就是通过 \ 或 / 分割字符串 （也可以返回文件目录部分）
		$extensionName = $pathInfo['extension'];
		$extensionName = strtolower($extensionName);
		return in_array($extensionName, $arrayPic);
	}

	/**
	 * 判断是否为 url 连接地址（check the string is a url string）
	 * @param string $url
	 * @return number(integer 0: false，不是; 1:true，是)
	 */
	public static function isUrl($url) {
		return preg_match('/^(http(s)?:\/\/)?([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?$/', $url);
	}

	/**
	 * 判断是否为手机号，check the string is a mobile number (为11位数字，以13|14|15|18开头)
	 * @param string $string
	 * @return boolean true:是；false:否
	 */
	public static function isMobile($string) {
		return (bool) preg_match('/^(13|14|15|18)\d{9}$/', $string);
	}

	/**
	 * 判断是否为IP地址 check the string is a ip address
	 * @param string $string
	 * @return number(integer 0: false，不是; 1:true，是)
	 */
	public static function isIP($string) {
		return preg_match('/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/', $string);
	}

	/**
	 * 判断是否为电子邮件（check the string is email rege）
	 * 此处不允许 -(貌似域名注册中不包括中划线)
	 * @param string $string
	 * @return integer 0:否; 1:是
	 */
	public static function isEmail($string) {
		// 	.plc.uk .net.cn 	.org.cn .travel(旅游)
//		$pattern = '/^\w+(\.\w+)*\@\w+(\.\w+)*(\.[a-zA-Z]{2,4})?\.[a-zA-Z]{2,6}$/';
	    // 网易的标准：6~18个字符，可使用字母、数字、下划线，需以字母开头
		$pattern = '/^[\w-]+(\.[\w-]+)*@\w+[\w-]*(\.[\w-]+)*(\.[a-zA-Z]{2,4})?\.[a-zA-Z]{2,6}$/'; // 标准格式。 有点像 zend 的验证. 貌似zend还可以+、=等
		return preg_match($pattern, $string);
	}
	
	/**
	 * 将Http下的图片文件下载到本地，并返回之
	 * @param array $arrayHttpImg 含有 http路径的数组
	 * @param string $folderPath  保存到哪里（文件夹路径）; 注意了，可以是相对路径或绝对路径，不能是 '/upload'这样的
	 * @return array | false 不是数组或数组为空时返回false， 如果保存不成功，返回null
	 */
	public static function httpImg2LocalFolder($arrayHttpImg, $folderPath) {
		if (is_array($arrayHttpImg) and sizeof($arrayHttpImg)) { // sizeof() 是 count() 的别名
			foreach ($arrayHttpImg as $key=>$value) {
				preg_match_all('/(http:\/\/(?!.*http.*)[^<]*\.(jpg|gif|jpeg|png|bmp))/iU', $value, $matches);
//				print_r($matches);
				if (sizeof($matches[0])) {
					$httpImgPath = $matches[0][0];
					$imgData = file_get_contents($httpImgPath);
//					$folderPath = realpath($folderPath); // realpath() 返回规范化的绝对路径名 ;如果无法找到（返回），则返回false；成功返回绝对路径 。  此处无法用
//					$folderPath = preg_replace('/\\\/', '/', $folderPath);  // 注意这里的写法
					$folderPath = str_replace('\\', '/', $folderPath);
					$folderPath = substr($folderPath, -1) == '/' ? $folderPath : $folderPath . '/';
					$imgPathInfo = pathinfo($httpImgPath);
					$savePath = $folderPath . md5(microtime(true)) . mt_rand() . '.' . $imgPathInfo['extension'];
					if (@file_put_contents($savePath, $imgData)) {
						$arrayHttpImg[$key] = $savePath;
					} else {
						$arrayHttpImg[$key] = null;
					}
				} else {
					$arrayHttpImg[$key] = null;
				}
			}
			return $arrayHttpImg;
		} else {
			return false;
		}
	}
	
	/**
	 * 生成随机的字符串
	 * @param integer $length 需要的字符串长度
	 * @return string
	 */
	public static function random($length) {
		$hash = '';
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		mt_srand((double)microtime()*1000000); // 播下一个更好的随机数发生器种子
		//自 PHP 4.2.0 起，不再需要用 srand() 或 mt_srand() 给随机数发生器播种 ，因为现在是由系统自动完成的。
		$len = strlen($chars)-1;
		for ($i=0; $i<$length; $i++) {
			$hash .= $chars[mt_rand(0, $len)];
		}
		return $hash;
	}
	
    /**
     * 获取随机字符串
     * @param integer $length
     * @param integer $mode 默认0：大小写字母和数字；1：数字；2：小写字母；3：大写字母；4：大小写字母；5：大写字母和数字；6：小写字母和数字
     * @return string
     */
    public static function getRandomString($length = 6, $mode = 0) {
        switch ($mode) {
        	case 1 : $str = '0123456789'; break;
        	case 2 : $str = 'abcdefghijklmnopqrstuvwxyz'; break;
        	case 3 : $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; break;
        	case 4 : $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; break;
        	case 5 : $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; break;
        	case 6 : $str = '0123456789abcdefghijklmnopqrstuvwxyz'; break;
        	default: $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz'; break;
        }
        $result = '';
        $l = strlen($str)-1;
        for ($i = 0; $i < $length; $i++) {
        	$result .= $str[mt_rand(0, $l)];
        }
        return $result; 
    }
	
	/**
	 * 跳转页面（TODO）
	 * @param unknown_type $message
	 * @param unknown_type $url
	 * @param unknown_type $title
	 */
	public static function gotoURL($message='', $url='', $title='', $second=3){ // TODO
	    $html  ="<html><head>";
    if(!empty($url))
     $html .="<meta http-equiv='refresh' content=\"1000;url='".$url."'\">";
    $html .="<link href='../templates/style.css' type=text/css rel=stylesheet>";
    $html .="</head><body><br><br><br><br>";
    $html .="<table cellspacing='0' cellpadding='0' border='1' width='450' align='center'>";
 $html .="<tr><td bgcolor='#ffffff'>";
 $html .="<table border='1' cellspacing='1' cellpadding='4' width='100%'>";
 $html .="<tr class='m_title'>";
 $html .="<td>".$title."</td></tr>";
 $html .="<tr class='line_1'><td align='center' height='60'>";
 $html .="<br>".$message."<br><br>";
    if (!empty($url))
     $html .="系统将在3秒后返回<br>如果您的浏览器不能自动返回,请点击[<a href=".$url." target=_self>这里</a>]进入";
    else
     $html .="[<a href='#' onclick='history.go(-1)'>返回</a>]";
    $html .="</td></tr></table></td></tr></table>";
 $html .="</body></html>";
 echo $html;
 exit;
	}

	/**
	 * 判断网络文件是否存在(已测试通过)
	 * （不完全准确，有些网站没有的时候跳页面或给友好提示还是有数据的，所以判断不准确）
	 * 测试速度 存在 3.845 不存在 0.1030 7.1203520298004
	 * @param string $url
	 * @return boolean
	 */
	public static function isExistFile($url) {
		$handle = @fopen($url, 'r');
		if ($handle) {
			return true;
		} else {
			return false;
		}
	}
	
    /**
     * 判断网络文件是否存在(已测试通过)
     * （不完全准确，有些网站没有的时候跳页面或给友好提示还是有数据的，所以判断不准确）
     * 测试速度 存在 0.0392  不存在 4.2085618972778 0.035804033279419
     * @param string $url
     * @return boolean
     */
    public static function imgExist($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, 1);// 不下载
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (curl_exec($ch) !== false) {
        	return true;
        } else {
        	return false;
        }
    }
    
    /**
     * 判断网络图片/文件是否存在 (已测试通过)
     * （不完全准确，有些网站没有的时候跳页面或给友好提示还是有数据的，所以判断不准确）
     * 测试速度 存在 0.9378 ， 不存在 0.05610203742981 0.05488109588623
     * @param string $url
     * @return boolean
     */
    public static function imageExists($url) {
        if (@file_get_contents($url, 0, null, 0, 1)) {
        	return true;
        } else {
        	return false;
        }
    }
    
    /**
     * 在图片src中插入静态域名 (已测试通过)
     * @param string $str
     * @param string $host
     * @return string
     */
    public static function replaceImg($str, $host = 'http://static.push.com') {
    	$pattern = '/(<img[^>]+src\s*\=\s*[\'"])([^\'">]+[\'"])([^>]*>)/is'; // i:不区分大小写；s：匹配多行
    	$replacement = '$1' . $host . '$2$3'; // 这里也可以写成 $replacement = '\\1' . $host . '\\2\\3';
    	$str = preg_replace($pattern, $replacement, $str);
    	return $str;
    }

    /**
     * 格式化数字成标准的Money格式，默认小数点后两位（四舍五入）(已测试通过)
     * @param float $number
     * @param integer $position
     * @return string
     */
    public static function formatMoney($number, $position = 2) {
    	return number_format($number, $position, '.', ',');
    }
    
    /**
     * 带pre打印变量
     * @param mixed $var 要打印的变量
     * @param boolean $isExist 是否要退出
     */
    public static function prePrint($var, $isExist = true) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        if ($isExist) {
            exit();
        }
    } 
   
    
    	

	




}
