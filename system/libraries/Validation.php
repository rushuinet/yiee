<?php
/**
 * 验证类
 * @E-mail	rushui@qq.com
 * @author	Rushui
 */
class Validation {

	private $value = '';
	private $data;
	private $internalFunc = array('a','e','f','s','n','r','q','t','p','u');

	public function check($checkData,$data) {
		$this->data = $data;
		foreach ($checkData as $checkKey=>$checkRule) {
			//赋值
			$this->value = trim($data[$checkKey]);
			// |中可以有&，但&不可有|，必须先判断顺
			// 取出错误提示消息
			$errorPrompt = array_pop($checkRule);
			if (is_object($checkRule[0])) {
				$call = array();
				$call[] = array_shift($checkRule);//对像
				$call[] = array_shift($checkRule);//方法
				array_unshift($checkRule, $this->value);//值
				if (!call_user_func_array($call, $checkRule)) return $this->checkInfo(false,$checkKey, $errorPrompt);
			}elseif (stripos($checkRule[0],'|' ) !== false) {//或者，必须 要在&之间判断
				if (!$this->checkOr($checkRule[0])) return $this->checkInfo(false,$checkKey, $errorPrompt);
			}elseif (stripos($checkRule[0], '&') !== false) {//与
				if (!$this->checkAnd($checkRule[0])) return $this->checkInfo(false,$checkKey, $errorPrompt);
			}else{
				$call_back_type = substr($checkRule[0], 0,1);
				if (in_array($call_back_type, $this->internalFunc)) {
					if (!call_user_func_array(array($this,"_$call_back_type"), array(substr($checkRule[0], 1)))) return $this->checkInfo(false,$checkKey, $errorPrompt);
				}else {//普通函数
					$call = substr(array_shift($checkRule), 1);//函数
					array_unshift($checkRule, $this->value);//追加值
					if (!call_user_func_array($call, $checkRule)) return $this->checkInfo(false,$checkKey, $errorPrompt);
				}
			}
		}
		//添加状态码为true,验证通过，为了每次在外部，都先isset
		//$this->data[$this->status] = true;
		return $this->data;
	}
	
	//或者
	private function checkOr($checkRule) {
		$rule = explode('|', $checkRule);
		foreach ($rule as $ruleVal) {
			if (stripos($ruleVal,'&') !== false) {
				$result = $this->checkAnd($ruleVal);
			}else {
				//取得第一个字符判断表示什么，只要有一个为真，那么则返回true
				$result = call_user_func_array(array($this,'_'.substr($ruleVal, 0,1)), array(substr($ruleVal, 1)));
			}
			if ($result) break;
		}
		return $result;
	}
	//并且
	private function checkAnd($checkRule) {
		$rule = explode('&', $checkRule);
		foreach ($rule as $ruleVal) {
			$result = call_user_func_array(array($this,'_'.substr($ruleVal, 0,1)), array(substr($ruleVal, 1)));
			if (!$result) break;
		}
		return $result;
	}
	
	//验证字符串
	private function _s($rule) {
		if (!is_string($this->value)) return false;
		$valueLen = mb_strlen($this->value,'UTF-8');
		return $this->checkLen($valueLen, $rule);
	}
	
	//验证数字
	private function _n($rule) {
		if (!is_numeric($this->value)) return false;
		$valueLen = strlen($this->value);
		return $this->checkLen($valueLen, $rule);
	}
	
	//验证Email
	private function _e($rule) {
		return  filter_var($this->value,FILTER_VALIDATE_EMAIL);
	}

	//判断两值是否相等
	private function _f($rule) {
		return $this->value==trim($this->data[$rule]);
	}
	
	//验证允许为空
	private function _a($rule) {
		return $rule == 'n' ? !empty($this->value) : empty($this->value);
	}
	
	//正则表达式验证
	private function _r($rule) {
		return preg_match($rule, $this->value);
	}
	
	//验证qq
	private function _q($rule) {
		return preg_match('/^[1-9][\d]{4,10}$/',$this->value);
	}
	
	//验证手机
	private function _t() {
		return preg_match('/^[1-9][\d]{10}$/',$this->value);
	}
	
	//验证电话
	private function _p() {
		return preg_match('/^([\d]{3}\-[\d]{8})|([\d]{4}\-[\d]{7})$/',$this->value);
	}
	
	//验证url
	private function _u() {
		return preg_match('/^(http|https):\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"])*$/',$this->value);
	}
	
	//验证长度
	private function checkLen($valueLen,$rule) {
		if (stripos($rule, ',')) {//1,1个以上
			$ruleArr = explode(',', $rule);
			return $valueLen >= $ruleArr[0];
		}elseif (stripos($rule,'-')) {//1-10，1-10个之间
			$ruleArr = explode('-',$rule);
			return $valueLen >= $ruleArr[0] && $valueLen <= $ruleArr[1];
		}else {//10，只能是10个
			return $valueLen == $rule;
		}
	}
	
	// 	返回验证信息
	private function checkInfo($status,$checkKey,$error = '') {
		return array('status'=>$status,'key'=>$checkKey,'msg'=>$error);
	}
	

}
