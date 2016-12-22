<?php	
// +----------------------------------------------------------------------+
// | SSQEIMS  version V0.1   	 		          		  |
// +----------------------------------------------------------------------+
// | Copyright (c) .2016 sousouqi.com                                     |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// | Authors: OuYang LingYun <582194733@qq.com>                      	  |
// +----------------------------------------------------------------------+
require("class.phpmailer.php");	
class sendmail
 {	
	function __construct($config){				
		 //加载邮箱配置文件信息			 
		 if(APP_NAME=='Apps'){			
				if(date('i')%2==1){				
					require "./Conf/e_for_temp_user.php";				
				}else{			
					require "./Conf/email.php";
					}			  
		}else{			
			  require "../Conf/email.php";		
		}		
		$this->econfig = $array;
		//dump($array);
		if($_SESSION['role_id']==1){
			$this->Port=$this->econfig['EMAIL_PORT'];
			$this->Host=$this->econfig['EMAIL_SERVER'];	
			$this->Username=date('H')%2==0?$this->econfig['EMAIL_USER1']:$this->econfig['EMAIL_USER'];
			$this->Password=date('H')%2==0?$this->econfig['EMAIL_PWD1']:$this->econfig['EMAIL_PWD'];
			$this->From=date('H')%2==0?$this->econfig['EMAIL_FORM1']:$this->econfig['EMAIL_FORM'];		
		}else{
			$this->Port=$this->econfig['EMAIL_PORT'];
			$this->Host=$this->econfig['EMAIL_SERVER'];	
			$this->Username=date('H')%2==0?$this->econfig['EMAIL_USER1']:$this->econfig['EMAIL_USER'];
			$this->Password=date('H')%2==0?$this->econfig['EMAIL_PWD1']:$this->econfig['EMAIL_PWD'];
			$this->From=date('H')%2==0?$this->econfig['EMAIL_FORM1']:$this->econfig['EMAIL_FORM'];		
		}	
		$this->FromName=$this->econfig['EMAIL_USERNAME']; //自己取		
		$this->SMTPAuth=true;		
		$this->SMTPSecure='ssl';
		$this->IsHTML=true;		
		$this->REG_EMAIL_TITLE=$this->econfig['REG_EMAIL_TITLE'];//激活邮件标题
		$this->REG_EMAIL_CONTENT=$this->econfig['REG_EMAIL_CONTENT'];//激活邮件内容
		$this->PWD_EMAIL_TITLE=$this->econfig['PWD_EMAIL_TITLE'];//密码找回邮件标题
		$this->PWD_EMAIL_CONTENT=$this->econfig['PWD_EMAIL_CONTENT'];//密码找回邮件内容
		$this->CHANGE_EMAIL_TITLE=$this->econfig['CHANGE_EMAIL_TITLE'];//修改邮箱邮件标题
		$this->CHANGE_EMAIL_CONTENT=$this->econfig['CHANGE_EMAIL_CONTENT'];//修改邮箱邮件内容
		$this->AUTH_STATUS_TITLE=$this->econfig['AUTH_STATUS_TITLE'];//企业认证邮件标题
		$this->AUTH_STATUS_CONTENT=$this->econfig['AUTH_STATUS_CONTENT'];//企业认证邮件内容		
	}

	/**
	* 发送找回密码验证邮件	
	* @address  收件人地址
	* @username  收件用户名
	* @code     激活链接
	*/
	function RePwdSend($address,$username,$code){
		$subject=$this->PWD_EMAIL_TITLE;	
		$subject=$subject;
		$fetchcontent =$this->PWD_EMAIL_CONTENT;			
		$result=$this->SendMails($subject,$fetchcontent,$code,$address,$username);	
		return $result;	
	}
	/**
	* 发送注册激活邮件	
	* @address  收件人地址
	* @username   收件用户名	
	* @code     激活链接
	*/
	function toRegSend($address,$username,$code){	
		$subject=$this->REG_EMAIL_TITLE;
		$fetchcontent =$this->REG_EMAIL_CONTENT;	
		$result=$this->SendMails($subject,$fetchcontent,$code,$address,$username);	
		return $result;
	}
	/**
	* 发送更换邮箱邮件	
	* @address  收件人地址
	* @username   收件用户名	
	* @code     激活链接
	*/
	function toChangeSend($address,$username,$code){	
		$subject=$this->CHANGE_EMAIL_TITLE;
		$fetchcontent =$this->CHANGE_EMAIL_CONTENT;				
		$result=$this->SendMails($subject,$fetchcontent,$code,$address,$username);	
		return $result;
	}
		/**
	* 发送企业认证通知邮件
	* @address  收件人地址
	* @username   收件用户名	
	* @$qyname   企业名称
	* @$status   审核状态	
	*/
	function toAuthSend($address,$username,$qyname,$status){	
		$subject=$this->AUTH_STATUS_TITLE;
		$fetchcontent =$this->AUTH_STATUS_CONTENT;
		if($status==1){
			$status='成功';
			$checkstatus='<span style="color:red;">审核通过！</span>';
			$contentstatus='<p style="font-weight: bold;font-size:18px;color:green;">'.$qyname.' <span> 认证成功！</span></p>';
		}else{
			$status='失败';
			$checkstatus='<span style="color:red;">审核不通过！</span>';
			$contentstatus='<p style="font-size:18px;color:gray;">'.$qyname.' <span style="font-weight: bold;"> 认证失败！</span></p>';	
			$contentstatus=$contentstatus.'<p>因您上传的 {qyname} 的企业营业执照包含不仅限于以下原因：</p>';
			$contentstatus=$contentstatus.'<p>1.上传的营业执照模糊无法辨识;</p>';
			$contentstatus=$contentstatus.'<p>2.上传的营业执照与认领企业不一致！</p>';
		}		
		$qyname='<span style="color: blue;">'.$qyname.'</span>';
		$subject=str_replace('{status}',$status,$subject);
		$fetchcontent=str_replace('{status}',$contentstatus,$fetchcontent);
		$fetchcontent=str_replace('{checkstatus}',$checkstatus,$fetchcontent);
		$fetchcontent=str_replace('{qyname}',$qyname,$fetchcontent);
		$fetchcontent=$fetchcontent.'<p>感谢您对搜搜企的信任与支持！</p>';		
		$code=null;
		$result=$this->SendMails($subject,$fetchcontent,$code,$address,$username);		
		return $result;
	}
	
	
		/**
	* 发送邮件
	* @subject   邮件标题
	* @bodys     邮件内容
	* @address  收件人地址
	* @username   收件用户名
	*   example：
	*			Vendor('phpmailer.sendmail');	
	*			$mail=new sendmail();
	*			$result=$mail->toSend($subject,$bodys,$address,$username);		
	*			if($result['errorcode'] == '1'){
	*				$this->success('邮件发送成功！');
	*			}else{			
	*				$this->error("邮件发送失败: " . $result['errormsg']);	
	*			}	
	*/
	function toSend($subject,$bodys,$address,$username){		
		$mail = new PHPMailer();		
		$mail->IsSMTP(); // 使用SMTP方式发送
		$mail->SMTPSecure=$this->SMTPSecure;
		$mail->Host = $this->Host; // 您的企业邮局域名
		$mail->SMTPAuth = true; // 启用SMTP验证功能
		$mail->Username =$this->Username; // 邮局用户名(请填写完整的email地址)
		$mail->Password =$this->Password; // 邮局密码
		$mail->Port=$this->Port;
		$mail->From = $this->From; //邮件发送者email地址
		$mail->FromName = $this->FromName;
		$mail->AddAddress($address, $username);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件用户名")
		//$mail->AddReplyTo("", "");
		//$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
		$mail->IsHTML($this->IsHTML); // set email format to HTML //是否使用HTML格式
		$mail->Subject = $subject; //邮件标题
		$footer='<br/><div style="color: #999;"><p>发件时间：'.date('Y年m月d日 H:i:s',$_SERVER['REQUEST_TIME']).' <br/>此邮件为搜搜企系统自动发送，请勿直接回复。<br/>如需帮助,可 <a href="http://www.sousouqi.com/article-question.html">查看帮助</a> 或 联系搜搜企客服!</p></div>';		
		$mail->Body =$bodys.$footer; //邮件内容
		//$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
		if(!$mail->Send())
		{			
			return array('errorcode'=>'0','errormsg'=>$mail->ErrorInfo);
			exit;
		}else{			
			return array('errorcode'=>'1');
		}
	}
	/**
	* 发送系统设置模板邮件	
	* @address  收件人地址
	* @username  收件用户名
	* @code     激活链接
	*/
	public function SendMails($subject,$fetchcontent,$code,$address,$username){			
		$mail = new PHPMailer();
		$mail->IsSMTP(); // 使用SMTP方式发送
		$mail->SMTPSecure=$this->SMTPSecure;
		$mail->SMTPKeepAlive=true;		
		$mail->Host = $this->Host; // 您的企业邮局域名
		$mail->SMTPAuth = true; // 启用SMTP验证功能
		$mail->Username =$this->Username; // 邮局用户名(请填写完整的email地址)
		$mail->Password =$this->Password; // 邮局密码
		$mail->Port=$this->Port;
		$mail->From = $this->From; //邮件发送者email地址
		$mail->FromName = $this->FromName;
		$mail->AddAddress($address, $username);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件用户名")
		//$mail->AddReplyTo("", "");
		//$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
		$href = "<a href=" . $code . ">" . $code . "</a>";	
		$footer='<br/><div style="color: #999;"><p>发件时间：'.date('Y年m月d日 H:i:s',$_SERVER['REQUEST_TIME']).' <br/>此邮件为搜搜企系统自动发送，请勿直接回复。<br/>如需帮助,可 <a href="http://www.sousouqi.com/article-question.html">查看帮助</a> 或 联系搜搜企客服!</p></div>';
		$fetchcontent = str_replace('{username}',$username,$fetchcontent);		
		$fetchcontent = str_replace('{time}',date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']),$fetchcontent);
		$fetchcontent = str_replace('{br}',"<br/>",$fetchcontent);
		$fetchcontent = str_replace('{code}',$href,$fetchcontent);		
		$bodys=$fetchcontent;
		$subject = str_replace('{username}',$username,$subject);
		$mail->IsHTML($this->IsHTML); // set email format to HTML //是否使用HTML格式	
		$mail->Subject = $subject; //邮件标题
		$mail->Body =$bodys.$footer; //邮件内容
		//$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
		if(!$mail->Send())
		{		
			$mail->SmtpClose();	
			return array('errorcode'=>'0','errormsg'=>$mail->ErrorInfo);			
			exit;
		}else{
			$mail->SmtpClose();
			return array('errorcode'=>'1');
		}
	}	
	
	
 }
