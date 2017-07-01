<style>
    .set-password-modal {
        margin-left: auto;
        margin-right: auto;
        margin-top: 20px;
    }
</style>
<template>
    <Modal v-model="setPassword.modal" title="修改密码" width="400" ok-text="提交" cancel-text="返回"
           @on-ok="submitSetPassword" :loading="setPassword.subLoading">
        <Form :label-width="60">
            <div class="set-password-modal">
                <Form-item label="原密码">
                    <Input type="password" v-model="setPassword.data.password" placeholder="请输入原密码"></Input>
                </Form-item>
            </div>
            <Form-item label="新密码">
                <Input type="password" v-model="setPassword.data.new_password" placeholder="请输入新密码"></Input>
            </Form-item>
            <Form-item label="再次输入">
                <Input type="password" v-model="setPassword.data.once_password" placeholder="请再次输入"></Input>
            </Form-item>
        </Form>
    </Modal>
</template>
<script>
    export default {
        data(){
            return {
                setPassword: {
                    modal: false,
                    subLoading: true,
                    data: {
                        password: '',
                        new_password: '',
                        once_password: '',
                    },
                },
            }
        },
        methods: {
            submitSetPassword(){
                let self = this;
                this.setPassword.subLoading = true;
                if (this.setPassword.data.new_password != this.setPassword.data.once_password) {
                    this.$Message.error('2次密码输入不一致，请重新输入', 2, function () {
                        self.resetLoadingBtn();
                    });
                    return false;
                }
                /** 修改管理员密码 */
                this.postData(
                    serverUlr + apiUrl.adminUpdatePassword,
                    {
                        password: md5(this.setPassword.data.password+'vue'),
                        new_password: md5(this.setPassword.data.new_password+'vue')
                    },
                    function (result) {
                        self.$Message.success('修改成功!', 1.5, function () {
                            self.setPassword.data.password = '';
                            self.setPassword.data.new_password = '';
                            self.setPassword.data.once_password = '';
                            self.$router.push({path: '/login'});
                            window.localStorage.setItem('token', null);
                        });
                        this.setPassword.modal = false;
                    }, function (result) {
                        self.$Message.error(result.message, 2, function () {
                            self.resetLoadingBtn();
                        });
                    }, function () {
                        //self.$Message.error('无法连接服务器!', 3);
                    }
                );
            },
            resetLoadingBtn(){
                this.setPassword.subLoading = false;
                let _self = this;
                setTimeout(function () {
                    _self.setPassword.subLoading = true;
                }, 100);
            }
        }
    }
</script>