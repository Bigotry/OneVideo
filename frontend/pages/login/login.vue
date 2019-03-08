<template>
	<form class='loginView' @submit="login">
		<view class="input-view">
			<view class="label-view">
				<text class="label">账号 </text>
			</view>
			<input class="input" type="text" placeholder="请输入用户名" name="username" value="test001" />
		</view>
		<view class="input-view">
			<view class="label-view">
				<text class="label">密码</text>
			</view>
			<input class="input" type="password" placeholder="请输入密码" name="password" value="123456"/>
		</view>
		<view class="button-view">
			<button type="default" class="login" hover-class="hover" formType="submit">登录</button>
		</view>
	</form>
</template>

<script>
	
	import * as md5 from '../../static/js/md5.js';
	
	export default {
		data() {
			return {};
		},
		methods: {
			login(e) {
				
				var access_token = md5.hexMD5('OneBase' + this.$ak);
				
				uni.request({
					url: this.$serverUrl + '/api.php/common/login.html',
					data: {
						username: e.detail.value.username,
						password: e.detail.value.password,
						access_token:access_token
					},
					success: (res) => {
						
						if (res.data.code == 0) {
						
							uni.setStorage({
								key: 'login_user_data',
								data: res.data.data,
								success: function () {
									uni.reLaunch({
										url: '/pages/new/new'
									});
								}
							});
					
						} else {
							uni.showToast({
								title: res.data.msg,
								duration: 2000,
								icon:'none',
							});
						}
					}
				});
				
			},
			register() {
				console.log("前往注册页面")
			}
		}
	}
</script>

<style>
	
</style>
