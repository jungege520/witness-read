<template>
    <div>
        <div>
            <Modal v-model="adminInfo.modal" title="个人信息" class="user-detail-modal" width="400" ok-text="提交"
                   cancel-text="返回" :loading="adminInfo.subLoading"
                   @on-ok="updateAdminInfo">
                <div>
                    <div class="demo-upload-list" style="margin-left: 150px;"
                         v-for="item in adminInfo.imgUpload.uploadList">
                        <template v-if="item.status === 'finished'">
                            <img :src="item.url">
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-eye-outline" @click.native="handleAdminView(item.url)"></Icon>
                                <Icon type="ios-trash-outline" @click.native="handleAdminRemove(item)"></Icon>
                            </div>
                        </template>
                        <template v-else>
                            <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
                        </template>
                    </div>
                    <Upload
                            v-show="adminInfo.imgUpload.uploadList.length ===0"
                            ref="upload"
                            :show-upload-list="false"
                            :default-file-list="adminInfo.imgUpload.imagesList"
                            :on-success="handleAdminSuccess"
                            :format="['jpg','jpeg','png','gif']"
                            :max-size="2048"
                            :on-format-error="handleAdminFormatError"
                            :on-exceeded-size="handleAdminMaxSize"
                            :before-upload="handleAdminBeforeUpload"
                            multiple
                            type="drag"
                            action="https://admin.fastgoo.net/api/public/upload"
                            name="image"
                            style="display: inline-block;width:58px;margin-left: 150px;">
                        <div style="width: 58px;height:58px;line-height: 58px;">
                            <Icon type="camera" size="20"></Icon>
                        </div>
                    </Upload>
                </div>
                <div style="margin-top: 20px;">
                    <Form :label-width="60">
                        <Form-item label="账号">
                            <Input type="text" v-model="adminInfo.data.username" disabled
                                   placeholder="请输入管理员账号"></Input>
                        </Form-item>
                        <Form-item label="昵称">
                            <Input type="text" v-model="adminInfo.data.nickname" placeholder="管理员昵称"></Input>
                        </Form-item>
                        <Form-item label="权限组">
                            <Select v-model="adminInfo.data.role" disabled placeholder="请选择">
                                <Option :value="roleList.id" v-for="(roleList,roleIndex) in roles">{{roleList.name}}
                                </Option>
                            </Select>
                        </Form-item>
                    </Form>
                </div>
            </Modal>
        </div>
        <div>
            <Modal title="查看图片" v-model="adminInfo.visible">
                <img :src="adminInfo.imgUpload.url" v-if="adminInfo.visible"
                     style="width: 100%">
            </Modal>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'set_admin_info',
        created(){

        },
        data(){
            return {
                roles: [],
                adminInfo: {
                    modal: false,
                    visible: false,
                    subLoading: true,
                    data: {
                        id: '',
                        username: '',
                        nickname: '',
                        role: 0,
                        icon: '',
                    },
                    imgUpload: {
                        url: '',
                        showUploadModal: true,
                        imgName: '',
                        uploadList: [],
                        imagesList: [],
                    }
                }
            }
        },
        methods: {
            handleAdminView (url) {
                this.adminInfo.imgUpload.url = url;
                this.adminInfo.visible = true;
            },
            handleAdminRemove (file) {
                this.adminInfo.imgUpload.showUploadModal = true;
                const fileList = this.$refs.upload.fileList;
                this.$refs.upload.fileList.splice(fileList.indexOf(file), 1);
            },
            handleAdminSuccess (res, file) {
                file.url = res.data.url;
                this.adminInfo.data.icon = res.data.url;
            },
            handleAdminFormatError (file) {
                this.$Notice.warning({
                    title: '文件格式不正确',
                    desc: '文件 ' + file.name + ' 格式不正确，请上传 jpg 或 png 格式的图片。'
                });
            },
            handleAdminMaxSize (file) {
                this.$Notice.warning({
                    title: '超出文件大小限制',
                    desc: '文件 ' + file.name + ' 太大，不能超过 2M。'
                });
            },
            handleAdminBeforeUpload () {
                this.adminInfo.imgUpload.showUploadModal = false;
            },
            getRoleList(){
                let self = this;
                /** 获取全部菜单权限 */
                this.postData(
                    serverUlr + apiUrl.adminRoleList,
                    {page: 1, page_size: 1000, sort: {key: 'id', orderBy: 'asc'}},
                    function (result) {
                        self.roles = result.data.data;
                    }, function (result) {
                        self.$Message.error(result.message, 3);
                    }, function () {
                        //self.$Message.error('无法连接服务器!', 3);
                    }
                );
            },
            updateAdminInfo(){
                let self = this;
                /** 更新管理员的信息 */
                this.postData(
                    serverUlr + apiUrl.adminUpdateMySelfInfo,
                    {icon: this.adminInfo.data.icon, nickname: this.adminInfo.data.nickname},
                    function (result) {
                        self.$Message.success('修改成功!', 1.5);
                        self.adminInfo.modal = false;
                    }, function (result) {
                        self.$Message.error(result.msg, 2, function () {
                            self.resetLoadingBtn();
                        });
                    }, function () {
                        //self.$Message.error('无法连接服务器!', 3);
                    }
                );
            },
            resetLoadingBtn(){
                this.adminInfo.subLoading = false;
                let _self = this;
                setTimeout(function () {
                    _self.adminInfo.subLoading = true;
                }, 100);
            }
        },
        mounted () {
            this.adminInfo.imgUpload.uploadList = this.$refs.upload.fileList;
            this.getRoleList();
        }
    }
</script>