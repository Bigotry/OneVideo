<?php

namespace app\common\service\pay\driver\wxpay;

class  SDKRuntimeException extends \Exception {
	public function errorMessage()
	{
		return $this->getMessage();
	}

}

?>