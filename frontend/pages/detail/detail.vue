<template>
	<view class="index">
		
            <view>
                <video id="myVideo" :src="fileSrc"
                    controls autoplay="true"></video>
            </view>
			
			<view class="article-meta">
				<text>{{name}}</text>
			</view>
			<view class="article-content">
				<text decode="true">{{describe}}</text>
			</view>
		</view>	
</template>



<script>
	
	import * as md5 from '../../static/js/md5.js';
	
	export default {
		data() {
			return {
				screenHeight: 0,
				data: [],
				fileSrc:'',
				name:'',
				describe:'',
			}
		},
		onReachBottom() {
		},
		onLoad(e) {
	
			this.data = JSON.parse(decodeURIComponent(e.data));
			
			console.log(this.data)
			
			this.fileSrc 	= this.data.file_url;
			this.name 		= this.data.name;
			this.describe 	= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + this.data.describe;
			
			var access_token = md5.hexMD5('OneBase' + this.$ak);
			
			var login_user_data = null;
			
			uni.getStorage({
				key: 'login_user_data',
				success: function (res) {
					
					login_user_data = res.data;
				}
			});
			
			uni.request({
				url: this.$serverUrl + '/api.php/video/setPlayLog.html?&access_token=' + access_token + '&user_token=' + login_user_data.user_token + '&vid=' + this.data.id,
				success: (ret) => {
					
					console.log(ret);
				}
			});
			
			
		},
		onReady: function(res) {

		},
		methods: {
			
		}
	}
</script>

<style>

	.index {
		-webkit-align-items:baseline;
		-webkit-justify-content:initial;
		width: 750upx;
	}

	video {
		width: 750upx;
	}
	
	.article-meta {
		font-size: 40upx;
		font-weight: bold;
		margin-top: 30upx;
		margin-bottom: 30upx;
		width: 750upx;
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}
	
	.article-content {
		font-size: 30upx;
		margin: 10upx;
	}
	

</style>
