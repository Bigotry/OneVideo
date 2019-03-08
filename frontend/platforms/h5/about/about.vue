<template>
	<view class="about">
		<view class="content">
			<view class="desc">
				OneVideo是一款基于OneBase+UniApp开发的小视频播放移动应用，一次开发7端运行，项目已开源。
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				sourceLink: 'https://github.com/dcloudio/uni-template-picture'
			};
		},
		methods:{
			openLink() {
				window.open(this.sourceLink);
			}
		}
	}
</script>

<style>
	page,
	view {
		display: flex;
		-webkit-align-items:baseline;
		-webkit-justify-content:initial;
	}

	page {
		min-height: 100%;
		background-color: #FFFFFF;
	}

	.about {
		flex-direction: column;
		flex: 1;
	}

	.content {
		flex: 1;
		padding: 30upx;
		flex-direction: column;
		justify-content: center;
	}

	.desc {
		margin-top: 30upx;
		display: block;
	}


</style>
