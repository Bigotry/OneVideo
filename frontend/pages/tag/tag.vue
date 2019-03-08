<template>
	<view class="index">
		<view class="tags">
			<block v-for="(value, index) in data" :key="index">
				<view class="tag" @tap="goList(value)">
					<image class="tag-img" :src="value.cover_url"></image>
					<text class="tag-text">{{value.name}}</text>
				</view>
			</block>
		</view>
	</view>
</template>

<script>
	import * as md5 from '../../static/js/md5.js';
	export default {
		data() {
			return {
				data: []
			}
		},
		onLoad() {
			this.getData();
		},
		methods: {
			
			getData() {
				
				var access_token = md5.hexMD5('OneBase' + this.$ak);
				
				var login_user_data = null;
				
				uni.getStorage({
					key: 'login_user_data',
					success: function (res) {
						
						login_user_data = res.data;
					}
				});
				
				
				uni.request({
					url: this.$serverUrl + '/api.php/video/categorylist.html?&access_token=' + access_token + '&user_token=' + login_user_data.user_token,
					success: (ret) => {
						
						this.data = ret.data.data;
					}
				});
			},
			goList(value) {
				uni.navigateTo({
					url:'../list/list?cid=' + value.id
				})
			}
		}
	}
</script>

<style>
	
</style>
