<?php

/**

 CHINESEVOCODE/vcode.php
 
 @usage create verifying code and display in GIF

 @author panguangyu
 
 @github panguangyu/chinesevcode
 
 */
	
	session_start();	//开启session保存相关变量的状态
	
	if($_GET['key']&&preg_match('/^[0-9]{6}$/',$_GET['key'])){	//根据获取的GET参数key判断是否为同一个浏览器的请求
		
			$sessionKey = "vcode".$_GET['key'];
			
			//查看对应的session是否存在
			
			if($_SESSION[$sessionKey]!=''){						//有拖动
				
				$randnumber = $_SESSION[$sessionKey];
				
			}else{												//首次拖动
				
				$_SESSION[$sessionKey] = rand(3,7);	
				
				$randnumber = $_SESSION[$sessionKey];
				
				$_SESSION['radical'] = "";						//对部首的状态记录要清空
				
					
				/*	数据内容 当放入其他文件时请注意字符编码的问题 */

				$char1 = array("亻","阝","冫","彳","犭","忄","饣","扌","讠","氵","纟");

				$char2 = array(
					
					array('青','主','中','至','吏','圭','为','木','壬','旦','扁','弗'),
					
					array('元','艮','完','且','井','东','龙','益','急','示','百','比'),
					
					array('京','先','令','水','兄','舌','妻','列','争','马','奏','疑'),
						
					array('余','艮','皮','羊','旬','寺','步','也','来','复','回'),
						
					array('苗','句','良','包','胃','师','侯','者','骨','甲','瓜'),
						
					array('青','兑','感','艮','赖','每','皇','灰','忽','真','俞','京'),
						
					array('欠','司','官','交','我','反','贵','昆','向'),
						
					array('丁','口','爪','是','令','那','曳','出','户','支'),
					
					array('炎','兑','仑','义','彦','荒','周','兼','延','普','每'),
						
					array('番','气','尼','吉','良','包','木','目','尧','由'),
						
					array('录','少','韦','条','周','充','秀','吉','文','卖','方','宗')
				);

				$totalLeft = count($char1);						//计算部首有多少个
				
				$randDataNumber = rand(0,$totalLeft-1);
				
				$totalRight = count($char2[$randDataNumber]);	//计算部首对应的字有多少个
				
				$randDataNumber2 = rand(0,$totalRight-1);	
				
				$_SESSION['character1'] = $char1[$randDataNumber];	
				
				$_SESSION['character2'] = $char2[$randDataNumber][$randDataNumber2];
				
				
				
					
				$co = imagecolorallocate($img,rand(0,255),rand(0,255),rand(0,255));
				
				$_SESSION['color'] = $co;
				
			}

	}else{

		exit();
	
	}
	
	//处理左边的偏旁部首
	
	if(ceil($_GET['number']/10)==$randnumber){
		
		/*	第一步：等于第一次就显示出部首 */
		
		$_SESSION['radical'] = 1;								//记录部首已经生成
		
		$leftWidth1 = 40;
		
		$leftHeight1 = 85;
		
		$size1 = 30;
		
		$pos1 = 0;

		setcookie("validate","",time()-3600,'/');				//利用cookie保存状态
		
	}else{
		
		if($_SESSION['radical']==1){
			
			$leftWidth1 = 40;
		
			$leftHeight1 = 85;
			
			$size1 = 30;
			
			$pos1 = 0;
			
			
		}else{
			
					
			$leftWidth1 = rand(120,400);
			
			$leftHeight1 = rand(0,200);
			
			$size1 = 30;

			$pos1 = rand(-20,20);
			
			setcookie("validate","",time()-3600,'/');
			
			
		}

	}
	
	
	//处理右边的字或部首
	
	if((ceil($_GET['number']/10)==$randnumber+3)&&($_SESSION['radical']==1)){
		
		//说明部首已经生成，现在生成后边的字
		
		$rightWidth2 = 65;
		
		$rightHeight2 = 85;
		
		$size2 = 30;
		
		$pos2 = 0;
				
		setcookie('validate','succ',0,'/');						//成功完成一个字的拼接，让js根据cookie判断是否成功
		
		
	}else{
		
		$rightWidth2 = rand(120,400);
		
		$rightHeight2 = rand(0,200);
		
		$size2 = 30;

		$pos2 = rand(-10,10);
		
		setcookie("validate","",time()-3600,'/');
		
	}

	/*	开始我们的GD画图	*/
	
	header("Content-type:image/gif");
	
	$width = 400;													//设置图片的宽高
	
	$height = 200;
	
	$img = imagecreatetruecolor($width,$height);					//创建画布
	
	$white = imagecolorallocate($img,255,255,255);					//建立颜色画笔，默认是白色画布
	
	imagefilledrectangle($img,0,0,400,200,$white);					//填充白颜色到画布
	
	$font = "font/font.ttf";										//设置字体的路径

	
	/* 把左右部首两个字写入画布*/
	
	imagettftext($img,$size1,$pos1,$leftWidth1,$leftHeight1,$_SESSION['color'],$font,$_SESSION['character1']);
	
	imagettftext($img,$size2,$pos2,$rightWidth2,$rightHeight2,$_SESSION['color'],$font,$_SESSION['character2']);
	
	
	/*	计算完成度	*/
	
	$percent = (($_GET['number']/10)/($randnumber+3))*100;
	
	if($percent<=100){
		
		imagettftext($img,10,0,25,140,$_SESSION['color'],$font,"完成度：".number_format($percent,2)."%");
		
	}else{
		
		imagettftext($img,10,0,25,140,$_SESSION['color'],$font,"请向左拖动滑块");
		
	}
	
	
	/*	把随机乱的部首展现在画布上，随机打乱排列 */

	$arr = array('亅','丿','乛','一','乙','乚','丶', '勹','匕', '冫', '卜', '厂', '刂' , '匚', '阝', '丷', '几', '卩' ,'冂', '力', '冖' ,'凵', '人', '亻', '入', '十', '厶', '亠', '讠', '廴', '又','彳','犭','彡','忄','饣','扌','氵' ,'纟');
		
	foreach($arr as $k=>$v){
		
			imagettftext($img,20,rand(-20,20),rand(120,400),rand(0,200),$_SESSION['color'],$font,$arr[$k]);
		
	}
	
	/*	画出正方形框 */
	
	imagettftext($img,100,0,10,120,$_SESSION['color'],$font,"□");
	
	imagettftext($img,10,0,25,190,$_SESSION['color'],$font,"github/panguangyu");
	

	//写字，其中“font/font.ttf”是文字所在的路径

	for($i=0;$i<=300;$i++){	
	
		imagesetpixel($img,rand(0,400),rand(0,200),$_SESSION['color']);   		 //随机画点
	}
	
	imageline($img,rand(0,400),rand(0,200),rand(0,400),rand(0,200),$_SESSION['color']);
	
	imageline($img,rand(0,400),rand(0,200),rand(0,400),rand(0,200),$_SESSION['color']);
	
	imagegif($img);												//输出图像
	
	imagedestroy($img);											//消除资源
	

?>

