<script>
    export default {
        onLaunch: function() {
            console.log('App Launch');
        },
        onShow: function() {
			
			
            console.log('App Show..')
			
			try {
				const login_user_data = uni.getStorageSync('login_user_data');
				if (!login_user_data) {
					
					uni.reLaunch({
						url: 'pages/login/login'
					});
				}
			} catch (e) {
				
				console.log('获取用户令牌异常');
			}
			
			
        },
        onHide: function() {
            console.log('App Hide')
        }
    }
</script>

<style>
	
    @import './common/common.css';

    page,
    view {
        display: flex;
    }

    page {
        display: flex;
        min-height: 100%;
        background-color: #EFEFEF;
    }

    template {
        display: flex;
        flex: 1;
    }
</style>
