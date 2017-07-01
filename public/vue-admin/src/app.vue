<style scoped>
    body, html {
        height: 100%;
    }
</style>
<template>
    <div style="width: 100%;height: 100%">
        <!--Loading加载图标-->
        <template>
            <Row type="flex" align="middle">
                <Spin fix size="large" v-if="requestLoading"></Spin>
            </Row>
        </template>
        <router-view></router-view>
    </div>
</template>
<script>
    export default {
        //监听路由检查登录
        watch: {
            "$route": 'checkLogin',
        },
        created(){
            //this.requestLoading = true;
            this.checkLogin();
        },
        data() {
            return {
                requestLoading: false,
            };
        },
        mounted() {

        },
        beforeDestroy() {

        },
        methods: {
            checkLogin(){
                this.checkRoute();
                var token = window.localStorage.getItem('token');
                if (token == null) {
                    this.$router.push({path: '/login'});
                } else {
                    let self = this;
                    /** 校验用户的token信息数据 */
                    this.postData(
                        serverUlr + apiUrl.adminAuthCheck,
                        {},
                        function(result){
                            if(self.$route.path == '/login'){
                                self.$router.push({path: '/home'});
                            }
                        },function (result) {
                            window.localStorage.setItem('token', null);
                            self.$Message.destroy();
                            self.$router.push({path: '/login'});
                        },function () {
                            //self.$Message.error('无法连接服务器!', 3);
                        }
                    );
                }
            },
            checkRoute(){
                let checkoutRoute = [
                    "/login",
                    "/home",
                    "/404",
                ];

                if($.inArray(this.$route.path,checkoutRoute) == -1){
                    let flag = this.$store.state.roleOperateArr instanceof Array;
                    if(!flag){
                        if(!this.$store.state.roleOperateArr.hasOwnProperty(this.$route.path)){
                            this.$router.push({path: '/404'});
                        }
                        return false;
                    }else{
                        let self = this;
                        setTimeout(function () {
                            self.checkRoute();
                        },1000);
                    }

                }

            }
        }
    };
</script>