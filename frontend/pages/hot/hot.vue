<template>
	<view class="index">
		<block v-for="(list, index) in lists" :key="index">
			<view class="row">
				<view class="card card-list2" v-for="(item,key) in list" @click="goDetail(item)" :key="key">
					<image class="card-img card-list2-img" :src="item.cover_url"></image>
					<text class="card-num-view card-list2-num-view">{{item.category_name}}</text>
					<view class="card-bottm row">
						<view class="car-title-view row">
							<text class="card-title card-list2-title">{{item.name}}</text>
						</view>
					</view>
				</view>
			</view>
		</block>
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
					url: this.$serverUrl + '/api.php/video/recommendVideoList.html?page=' + (this.refreshing ? 1 : this.fetchPageNum) 
					+ '&list_rows=' + this.$listRows + '&access_token=' + access_token + '&user_token=' + login_user_data.user_token,
					success: (ret) => {
						
						console.log("data",ret);
						
							let list = [],
								lists = [],
								data = ret.data.data.data;
							for (let i = 0, length = data.length; i < length; i++) {
								let index = Math.floor(i / 2);
								list.push(data[i]);
								if (i % 2 == 1) {
									lists.push(list);
									list = [];
								}
							}
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
</style>
