<template>
	<view class="page">
		<view class="uni-list">
			<view class="uni-list-cell" hover-class="uni-list-cell-hover" v-for="(value,key) in lists" :key="key" @click="goDetail(value)">
				<view class="uni-list-cell-navigate uni-navigate-right uni-media-list ">
					<view class="uni-media-list-logo">
						<image :src="value.cover_url"></image>
					</view>
					<view class="uni-media-list-body">
						<view class="uni-media-list-text-top">{{value.name}}</view>
						<view class="uni-media-list-text-bottom uni-ellipsis">{{value.describe}}</view>
					</view>
				</view>
			</view>
		</view>
	</view>
</template>
<script>
	
	import * as md5 from '../../static/js/md5.js';
	
	export default {
		data() {
			return {
				lists: [],
				fetchPageNum: 1,
			}
		},
		onLoad() {
			this.getData();
		},
		onReachBottom() {
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
					url: this.$serverUrl + '/api.php/video/playloglist.html?page=' + (this.refreshing ? 1 : this.fetchPageNum) 
					+ '&list_rows=' + this.$listRows + '&access_token=' + access_token + '&user_token=' + login_user_data.user_token,
					success: (ret) => {
						
							let list = [],
								lists = ret.data.data.data;
			
							if (this.refreshing) {
								this.refreshing = false;
								uni.stopPullDownRefresh()
								this.lists = lists;
								this.fetchPageNum = 2;
							} else {
								this.lists = this.lists.concat(lists);
								this.fetchPageNum += 1;
							}
					}
				});

			},
			goDetail(e) {
				uni.navigateTo({
					url:"../detail/detail?data=" + encodeURIComponent(JSON.stringify(e))
				})
			},
		}
	}
</script>

<style>
	@import '../../common/uni.css';
	
	.uni-media-list-text-bottom {
		
		width: 550upx;
	}
</style>
