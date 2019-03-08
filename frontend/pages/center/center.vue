<template>
	<view class="center">
		<view class="logo" @click="goLogin" :hover-class="!login ? 'logo-hover' : ''">
			<image class="logo-img" :src="login ? avatarUrl :avatarUrl"></image>
			<view class="logo-title">
				<text class="uer-name">Hi，{{login ? uerInfo.nickname : '您未登录'}}</text>
				<text class="go-login navigat-arrow" v-if="!login">&#xe65e;</text>
			</view>
		</view>
		<view class="center-list">
			<view class="center-list-item border-bottom" @click="goPlayLog">
				<text class="list-icon">&#xe60c;</text>
				<text class="list-text">观看历史</text>
				<text class="navigat-arrow">&#xe65e;</text>
			</view>

			<view class="center-list-item border-bottom" @click="goAbout">
				<text class="list-icon">&#xe603;</text>
				<text class="list-text">关于我们</text>
				<text class="navigat-arrow">&#xe65e;</text>
			</view>
			
			<view class="center-list-item  border-bottom" @click="goExitLogin">
				<text class="list-icon">&#xe609;</text>
				<text class="list-text">退出登录</text>
				<text class="navigat-arrow">&#xe65e;</text>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				login:false,
				avatarUrl:"../../static/logo.png",
				uerInfo:{}
			}
		},
		onLoad(e) {
			
			this.getData();
		},
		methods: {
			getData() {
				
				var login = null;
				var uerInfo = {};
				
				uni.getStorage({
					key: 'login_user_data',
					success: function (res) {
						
						login = true;
						uerInfo = res.data;
					}
				});
				
				
				this.login = login;
				this.uerInfo = uerInfo;
				this.avatarUrl = "../../static/logo.png";
			},
			goLogin() {
				if(!this.login){
					uni.navigateTo({
						url:"../login/login"
					})
				}
			},
			goPlayLog() {
				uni.navigateTo({
					url:"../center/playlog"
				})
			},
			goExitLogin() {
				
				uni.clearStorage();
				
				uni.reLaunch({
					url:"../login/login"
				});

			},
			goAbout() {
				// #ifdef APP-PLUS
				uni.navigateTo({
					url:'/platforms/app-plus/about/about'
				});
				// #endif
				// #ifdef H5
				uni.navigateTo({
					url:'/platforms/h5/about/about'
				});
				// #endif
			}
		}
	}
</script>

<style>
</style>
