<?php
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
namespace vod\Request\V20170321;

class SetAuditSecurityIpRequest extends \RpcAcsRequest
{
	function  __construct()
	{
		parent::__construct("vod", "2017-03-21", "SetAuditSecurityIp", "vod", "openAPI");
		$this->setMethod("POST");
	}

	private  $operateMode;

	private  $securityGroupName;

	private  $ips;

	public function getOperateMode() {
		return $this->operateMode;
	}

	public function setOperateMode($operateMode) {
		$this->operateMode = $operateMode;
		$this->queryParameters["OperateMode"]=$operateMode;
	}

	public function getSecurityGroupName() {
		return $this->securityGroupName;
	}

	public function setSecurityGroupName($securityGroupName) {
		$this->securityGroupName = $securityGroupName;
		$this->queryParameters["SecurityGroupName"]=$securityGroupName;
	}

	public function getIps() {
		return $this->ips;
	}

	public function setIps($ips) {
		$this->ips = $ips;
		$this->queryParameters["Ips"]=$ips;
	}
	
}