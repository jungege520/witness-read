<style>
    .search {
        float: left;
        margin-top: 24px;
        margin-left: 20px;
    }

    .date-time {
        width: 150px;
    }

    .input {
        width: 300px;
    }

    .search-btn {
        margin-left: 60px;
        width: 130px;
    }

    .add-admin {
        float: right;
        margin-top: 24px;
        margin-right: 15px;
    }

    .demo-upload-list {
        display: inline-block;
        width: 60px;
        height: 60px;
        text-align: center;
        line-height: 60px;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .2);
        margin-right: 4px;
    }

    .demo-upload-list2 {
        display: inline-block;
        width: 50px;
        height: 50px;
        text-align: center;
        line-height: 50px;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .2);
        margin-right: 4px;
    }

    .demo-upload-list img {
        width: 100%;
        height: 100%;
    }

    .demo-upload-list-cover {
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, .6);
    }

    .demo-upload-list:hover .demo-upload-list-cover {
        display: block;
    }

    .demo-upload-list2:hover .demo-upload-list-cover {
        display: block;
    }

    .demo-upload-list-cover i {
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }

    .table-card {
        width: 92%;
        height: 85%;
        margin-top: 2%;
        margin-left: auto;
        margin-right: auto;
    }
</style>
<template>
    <Card class="table-card">
        <div style="">
            <div style="height: 80px;">
                <!--<div class="search input">
                    <Input style="width: 300px">
                    <Select slot="prepend" style="width: 100px">
                        <Option :value="search_type.type" v-for="search_type in searchTypeList">{{search_type.name}}
                        </Option>
                    </Select>
                    </Input>
                </div>
                <Date-picker type="date" placeholder="开始日期" class="search date-time"></Date-picker>
                <Date-picker type="date" placeholder="结束日期" class="search date-time"></Date-picker>
                <Dropdown class="search" trigger="click">
                    <Button type="primary" :style="{background: '#fff','border-color':'#d7dde4',color: statusColor}">
                        {{this.searchStatusName}}
                        <Icon type="arrow-down-b"></Icon>
                    </Button>
                    <Dropdown-menu slot="list">
                        <Dropdown-item v-for="statusLists in searchStatusList">
                            <div style="width: 100%" @click="setStatus(statusLists.name,statusLists.id)">
                                {{statusLists.name}}
                            </div>
                        </Dropdown-item>
                    </Dropdown-menu>
                </Dropdown>
                <Button type="ghost" shape="circle" icon="ios-search" class="search search-btn">搜索</Button>-->
                <Button type="primary" shape="circle" class="add-admin" @click="showAddAdmin" v-if="roleIn('add')">添加管理</Button>
            </div>
            <Table :data="adminData.data" :columns="columns1" :height="this.$store.state.tableHeight"
                   @on-sort-change="sortTable"></Table>

            <div style="margin: 10px;bottom:10px;position:absolute;right: 15px;">
                <div style="">
                    <Page :total="adminData.total" :current="1" @on-change="nextPageData" :page-size="15"></Page>
                </div>
            </div>
            <div>
                <Modal v-model="addAdmin.modal" title="添加管理员" width="400" ok-text="提交" cancel-text="返回"
                       @on-ok="submitAdmin" :loading="addAdmin.btnModal">
                    <div>
                        <div class="demo-upload-list" style="margin-left: 150px;" v-for="item in imgUpload.uploadList">
                            <template v-if="item.status === 'finished'">
                                <img :src="item.url">
                                <div class="demo-upload-list-cover">
                                    <Icon type="ios-eye-outline" @click.native="handleView(item.url)"></Icon>
                                    <Icon type="ios-trash-outline" @click.native="handleRemove(item)"></Icon>
                                </div>
                            </template>
                            <template v-else>
                                <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
                            </template>
                        </div>
                        <Upload
                                v-show="imgUpload.uploadList.length ===0"
                                ref="upload"
                                :show-upload-list="false"
                                :default-file-list="imgUpload.imagesList"
                                :on-success="handleSuccess"
                                :format="['jpg','jpeg','png','gif']"
                                :max-size="2048"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                :before-upload="handleBeforeUpload"
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
                                <Input type="text" v-model="addAdmin.data.username" placeholder="请输入管理员账号"></Input>
                            </Form-item>
                            <Form-item label="昵称">
                                <Input type="text" v-model="addAdmin.data.nickname" placeholder="管理员昵称"></Input>
                            </Form-item>
                            <Form-item label="密码">
                                <Input type="password" v-model="addAdmin.data.password" placeholder="请输入新密码"></Input>
                            </Form-item>
                            <Form-item label="输入">
                                <Input type="password" v-model="addAdmin.data.once_pass" placeholder="请再次输入"></Input>
                            </Form-item>
                            <Form-item label="权限组">
                                <Select v-model="addAdmin.data.role" placeholder="请选择">
                                    <Option :value="roleName.id" v-for="(roleName,index) in roles">{{roleName.name}}
                                    </Option>
                                </Select>
                            </Form-item>
                        </Form>
                    </div>
                </Modal>
            </div>
            <div>
                <Modal title="查看图片" v-model="visible">
                    <img :src="imgUpload.url" v-if="visible"
                         style="width: 100%">
                </Modal>
            </div>
        </div>
    </Card>
</template>
<script>
    export default {
        created(){
            //组件已经初始化开始的操作
            this.getAdminList(1, 15);
        },
        data () {
            return {
                addAdmin: {
                    modal: false,
                    btnModal: true,
                    data: {
                        id: '',
                        icon: '',
                        username: '',
                        nickname: '',
                        password: '',
                        once_pass: '',
                        role: '',
                    },
                },
                adminData: [],
                roles: [],
                sort: {
                    key: '',
                    orderBy: '',
                },
                searchTypeList: [
                    {
                        type: 1,
                        name: '用户ID'
                    }, {
                        type: 2,
                        name: '用户昵称'
                    }, {
                        type: 3,
                        name: '用户手机'
                    }
                ],
                searchStatusList: [
                    {
                        id: 1,
                        name: '已完成'
                    }, {
                        id: 2,
                        name: '已拒绝'
                    }, {
                        id: 3,
                        name: '已通过'
                    }, {
                        id: 4,
                        name: '已注销'
                    }
                ],
                searchStatus: '-1',
                searchStatusName: '请选择',
                statusColor: '#d9dde4',
                columns1: [
                    {
                        title: 'id',
                        key: 'id',
                        align: 'center',
                        sortable: 'custom',
                    }, {
                        title: '昵称',
                        key: 'nickname',
                        align: 'center',
                    }, {
                        title: '账号',
                        key: 'username',
                        align: 'center',
                    }, {
                        title: '头像',
                        key: 'icon',
                        align: 'center',
                        render: (h, params) => {
                            return h('div', {
                                attrs: {
                                    class: 'demo-upload-list2',
                                },
                            }, [
                                h('img', {
                                    attrs: {
                                        src: params.row.icon,
                                    },
                                    style: {
                                        height: '50px'
                                    },
                                }),
                                h('div', {
                                    attrs: {
                                        class: 'demo-upload-list-cover',
                                    }
                                }, [
                                    h('a', {
                                        style: {
                                            height: '50px',
                                            width: '50px',
                                        },
                                        on: {
                                            click: () => {
                                                this.handleView(params.row.icon)
                                            }
                                        }
                                    }, '查看')
                                ])
                            ])
                        }
                    }, {
                        title: '权限',
                        key: 'role_name',
                        align: 'center',
                    }, {
                        title: '最近登录',
                        key: 'login_time',
                        align: 'center',
                        sortable: 'custom',
                    }, {
                        title: '操作',
                        key: 'action',
                        width: 150,
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'ghost',
                                        size: 'small',
                                        shape: "circle",
                                        icon: 'edit'
                                    },
                                    style: {
                                        marginRight: '5px',
                                        display:this.roleIn('edit')?'':'none',
                                    },
                                    on: {
                                        click: () => {
                                            this.tableDataUpdate(params.index)
                                        }
                                    }
                                }),
                                h('Button', {
                                    props: {
                                        type: 'ghost',
                                        size: 'small',
                                        shape: "circle",
                                        icon: 'android-delete'
                                    },
                                    style: {
                                        marginRight: '5px',
                                        display:this.roleIn('delete')?'':'none',
                                    },
                                    on: {
                                        click: () => {
                                            this.tableDataRemove(params.index)
                                        }
                                    }
                                })
                            ]);
                        }
                    }
                ],
                imgUpload: {
                    showUploadModal: true,
                    imgName: '',
                    url: '',
                    imagesList: [],
                    uploadList: [],
                },
                visible: false,
            }
        },
        methods: {
            tableDataUpdate(index){
                var data = this.adminData.data[index];
                this.addAdmin.data.id = data.id;
                this.addAdmin.data.icon = data.icon;
                this.addAdmin.data.nickname = data.nickname;
                this.addAdmin.data.role = data.role;
                this.addAdmin.data.username = data.username;
                this.imgUpload.uploadList[0] = {url: data.icon, status: 'finished'};
                this.addAdmin.modal = true;
            }, /** 点击删除角色 */
            tableDataRemove (index) {
                this.$Modal.confirm({
                    title: '删除管理员',
                    content: '删除的 管理员账号 不可恢复，是否删除',
                    onOk: () => {
                        let self = this;
                        this.postData(
                            serverUlr + apiUrl.adminDelete,
                            {id: this.adminData.data[index]['id']},
                            function (result) {
                                self.$Message.success('删除成功', 2);
                                self.adminData.data.splice(index, 1);
                            }, function (result) {
                                self.$Message.error(result.message, 2, function () {

                                });
                            }, function () {
                                self.$Message.error('无法连接服务器!', 2, function () {

                                });
                            }
                        );
                    },
                });
            },
            sortTable(column){
                this.sort.key = column.key;
                this.sort.orderBy = column.order;
                this.getAdminList(1, 15);
            },
            showAddAdmin(){
                this.addAdmin.data = {
                    id: '',
                    icon: '',
                    username: '',
                    nickname: '',
                    password: '',
                    once_pass: '',
                    role: '',
                };
                this.addAdmin.modal = true
            },
            nextPageData(page){
                this.getAdminList(page, 15);
            },
            setStatus(name, status){
                this.searchStatusName = name;
                this.searchStatus = status;
                this.statusColor = '#77818e';
            },
            submitAdmin(){
                let self = this;
                if (this.addAdmin.data.password != this.addAdmin.data.once_pass) {
                    this.$Message.error('2次输入的密码不一致，请重新输入!', 2, function () {
                        self.resetLoadingBtn();
                    });
                    return false;
                }

                if (this.addAdmin.data.id) {
                    this.setAdminInfo(apiUrl.adminUpdateAdminInfo);
                } else {
                    this.setAdminInfo(apiUrl.adminRegister);
                }
            },
            handleView (url) {
                this.imgUpload.url = url;
                this.visible = true;
            },
            handleRemove (file) {
                // 从 upload 实例删除数据
                this.imgUpload.showUploadModal = true;
                const fileList = this.$refs.upload.fileList;
                this.$refs.upload.fileList.splice(fileList.indexOf(file), 1);
                this.addAdmin.data.icon = '';
            },
            handleSuccess (res, file) {
                file.url = res.data.url;
                this.addAdmin.data.icon = res.data.url;
            },
            handleFormatError (file) {
                this.$Notice.warning({
                    title: '文件格式不正确',
                    desc: '文件 ' + file.name + ' 格式不正确，请上传 jpg 或 png 格式的图片。'
                });
            },
            handleMaxSize (file) {
                this.$Notice.warning({
                    title: '超出文件大小限制',
                    desc: '文件 ' + file.name + ' 太大，不能超过 2M。'
                });
            },
            handleBeforeUpload () {
                this.imgUpload.showUploadModal = false;
            },
            getAdminList(page, page_size){
                this.$Message.loading('正在拉取数据...', 0);

                let self = this;
                this.postData(
                    serverUlr + apiUrl.adminList,
                    {page: page, page_size: page_size, sort: this.sort},
                    function (result) {
                        self.adminData = result.data;
                        self.$Message.destroy();
                    }, function (result) {
                        self.$Message.error(result.message, 2, function () {

                        });
                        self.$Message.destroy();
                    }, function () {
                        self.$Message.error('无法连接服务器!', 2, function () {

                        });
                        self.$Message.destroy();
                    }
                );
            },
            getRoleList(){
                /** 获取全部菜单权限 */
                let self = this;
                this.postData(
                    serverUlr + apiUrl.adminRoleList,
                    {page: 1, page_size: 1000, sort: {key: 'id', orderBy: 'asc'}},
                    function (result) {
                        self.roles = result.data.data;
                    }, function (result) {
                        self.$Message.error(result.message, 2, function () {

                        });
                    }, function () {
                        self.$Message.error('无法连接服务器!', 2, function () {

                        });
                    }
                );
            },
            setAdminInfo(url){
                let self = this;
                this.postData(
                    serverUlr + url,
                    {
                        id: this.addAdmin.data.id,
                        icon: this.addAdmin.data.icon,
                        username: this.addAdmin.data.username,
                        nickname: this.addAdmin.data.nickname,
                        password: md5(this.addAdmin.data.password + 'vue'),
                        role: this.addAdmin.data.role,
                    },
                    function (result) {
                        self.$Message.success('操作成功', 1.5, function () {
                            self.getAdminList(1, 15);
                        });
                        self.addAdmin.modal = false;
                    }, function (result) {
                        self.$Message.error(result.message, 2, function () {
                            self.resetLoadingBtn();
                        });
                    }, function () {
                        self.$Message.error('无法连接服务器!', 2, function () {
                            self.resetLoadingBtn();
                        });
                    }
                );
            },
            resetLoadingBtn(){
                this.addAdmin.btnModal = false;
                let _self = this;
                setTimeout(function () {
                    _self.addAdmin.btnModal = true;
                }, 100);
            }
        },
        mounted () {
            this.imgUpload.uploadList = this.$refs.upload.fileList;
            this.getRoleList();
        }
    }
</script>