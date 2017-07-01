<style scoped>
    .login {
        top: 10%;
        position: relative;
    }

    .form-input {
        margin-top: 10px;
    }

    .logo-card {
        margin-left: 30%;
        margin-right: 30%;
        margin-top: 10%;
    }

    .login-body {
        background: url('../images/login2.jpg') center 0 no-repeat;
        background-size: cover;
        height: 100%;
        width: 100%;
        background-color: rgba(230, 230, 230, 0.59)
    }

</style>
<template>
    <div class="login-body">
        <div class="login">
            <Card style="width:350px;background:rgba(247, 247, 247, 0.44); margin: 0 auto;">
                <div style="text-align:center">
                    <div class="logo-card">
                        <Card style="width:100px;height: 100px;margin: 0 auto;" padding="8">
                            <div style="text-align:center">
                                <img height="84px" width="84px" :src="adminInfoIcon">
                            </div>
                        </Card>
                    </div>
                    <div class="form-input">
                        <Form ref="formInline" inline>
                            <div style="margin-top: 30px;">
                                <Form-item prop="user">
                                    <Input type="text" v-model="loginInfo.username" placeholder="请输入账号"
                                           style="width: 200px">
                                    <Icon type="ios-person-outline" slot="prepend"></Icon>
                                    </Input>
                                </Form-item>
                            </div>
                            <div>
                                <Form-item prop="password">
                                    <Input type="password" v-model="loginInfo.password" placeholder="请输入密码"
                                           style="width: 200px;">
                                    <Icon type="ios-locked-outline" slot="prepend"></Icon>
                                    </Input>
                                </Form-item>
                            </div>
                            <div style="margin-top: 5px;">
                                <Form-item>
                                    <Button type="primary" shape="circle" @click="handleSubmit"
                                            style="width: 200px;" :loading="submitBtn">{{this.showBtnName}}
                                    </Button>
                                </Form-item>
                            </div>
                        </Form>
                    </div>
                </div>
            </Card>
        </div>
    </div>
</template>
<script>
    export default {
        created(){
            this.adminInfoIcon = window.localStorage.getItem('adminInfoIcon') ? window.localStorage.getItem('adminInfoIcon') : 'https://raw.githubusercontent.com/iview/iview/master/assets/logo.png';
        },
        destroyed() {

        },
        data () {
            return {
                submitBtn: false,
                showBtnName: '登录',
                login_body: {},
                loginInfo: {
                    username: '',
                    password: '',
                },
            }
        },
        methods: {
            handleSubmit() {
                let _self = this;
                this.submitBtn = true;
                this.showBtnName = '正在登录';
                if (this.loginInfo.username && this.loginInfo.password) {
                    this.postData(
                        serverUlr + apiUrl.adminLogin,
                        //this.loginInfo,
                        {username: this.loginInfo.username, password: md5(this.loginInfo.password + 'vue')},
                        function (result) {
                            window.localStorage.setItem('token', result.data.token);
                            window.localStorage.setItem('adminInfoIcon', result.data.icon);
                            _self.$router.push({path: '/home'});
                        }, function () {
                            _self.$Message.error('账号或密码错误!', 1.5, function (result) {
                                _self.resetSubmitBtn();
                            });
                        }, function () {
                            _self.$Message.error('服务器异常!', 1.5, function () {
                                _self.resetSubmitBtn();
                            });
                        });
                } else {
                    this.$Message.error('请先输入账号密码', 1, function () {
                        _self.resetSubmitBtn();
                    });
                }
            },
            resetSubmitBtn(){
                this.submitBtn = false;
                this.showBtnName = '登录';
            }
        }
    }

</script>