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

    .demo-upload-list-cover i {
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }
    .table-card{
        width:92%;height:85%;margin-top: 2%;margin-left: auto;margin-right: auto;
    }
</style>
<template>
    <Card class="table-card">
        <div style="">
            <div style="height: 80px;">
                <div class="search input">
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
                            <div style="width: 100%" @click="setStatus(statusLists.name,statusLists.id)">{{statusLists.name}}</div>
                        </Dropdown-item>
                    </Dropdown-menu>
                </Dropdown>
                <Button type="ghost" shape="circle" icon="ios-search" class="search search-btn">搜索</Button>
                <Button type="primary" shape="circle" class="add-admin" @click="addAdmin.modal = true">添加管理</Button>
            </div>
            <div>
                <Table :data="data1" :columns="columns1"></Table>
                <div style="margin: 10px;overflow: hidden">
                    <div style="float: right;">
                        <Page :total="100" :current="1" @on-change=""></Page>
                    </div>
                </div>
            </div>
            <div>
                <Modal v-model="addAdmin.modal" title="添加管理员" width="400" ok-text="提交" cancel-text="返回"
                       @on-ok="">
                    <div>
                        <div class="demo-upload-list" style="margin-left: 150px;" v-for="item in imgUpload.uploadList">
                            <template v-if="item.status === 'finished'">
                                <img :src="item.url">
                                <div class="demo-upload-list-cover">
                                    <Icon type="ios-eye-outline" @click.native="handleView(item.name)"></Icon>
                                    <Icon type="ios-trash-outline" @click.native="handleRemove(item)"></Icon>
                                </div>
                            </template>
                            <template v-else>
                                <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
                            </template>
                        </div>
                        <Upload
                                v-show="imgUpload.showUploadModal"
                                ref="upload"
                                :show-upload-list="false"
                                :default-file-list="imgUpload.imagesList"
                                :on-success="handleSuccess"
                                :format="['jpg','jpeg','png']"
                                :max-size="2048"
                                :on-format-error="handleFormatError"
                                :on-exceeded-size="handleMaxSize"
                                :before-upload="handleBeforeUpload"
                                multiple
                                type="drag"
                                action="//jsonplaceholder.typicode.com/posts/"
                                style="display: inline-block;width:58px;margin-left: 150px;">
                            <div style="width: 58px;height:58px;line-height: 58px;">
                                <Icon type="camera" size="20"></Icon>
                            </div>
                        </Upload>
                    </div>
                    <div style="margin-top: 20px;">
                        <Form :label-width="60">
                            <Form-item label="账号">
                                <Input type="text" v-model="addAdmin.username" placeholder="请输入管理员账号"></Input>
                            </Form-item>
                            <Form-item label="昵称">
                                <Input type="text" v-model="addAdmin.nickname" placeholder="管理员昵称"></Input>
                            </Form-item>
                            <Form-item label="密码">
                                <Input type="password" v-model="addAdmin.new_pass" placeholder="请输入新密码"></Input>
                            </Form-item>
                            <Form-item label="输入">
                                <Input type="password" v-model="addAdmin.once_pass" placeholder="请再次输入"></Input>
                            </Form-item>
                            <Form-item label="权限组">
                                <Select v-model="addAdmin.role" placeholder="请选择">
                                    <Option value="beijing">超级管理员</Option>
                                    <Option value="shanghai">管理员</Option>
                                    <Option value="shenzhen">运营人员</Option>
                                </Select>
                            </Form-item>
                        </Form>
                    </div>
                </Modal>
            </div>
            <div>
                <Modal title="查看图片" v-model="visible">
                    <img :src="'https://o5wwk8baw.qnssl.com/' + imgUpload.imgName + '/large'" v-if="visible" style="width: 100%">
                </Modal>
            </div>
        </div>
    </Card>
</template>
<script>
    export default {
        data () {
            return {
                addAdmin: {
                    modal: false,
                    username: '',
                    nickname: '',
                    new_pass: '',
                    once_pass: '',
                    role:'',
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
                        id:1,
                        name:'已完成'
                    },{
                        id:2,
                        name:'已拒绝'
                    },{
                        id:3,
                        name:'已通过'
                    },{
                        id:4,
                        name:'已注销'
                    }
                ],
                searchStatus: '-1',
                searchStatusName: '请选择',
                statusColor: '#d9dde4',
                columns1: [
                    {
                        title: 'id',
                        key: 'id',
                    }, {
                        title: '昵称',
                        key: 'nickname',
                    }, {
                        title: '头像',
                        key: 'img',
                    }, {
                        title: '权限',
                        key: 'role',
                    }, {
                        title: '最近登录',
                        key: 'login_time',
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
                                        shape:"circle",
                                        icon:'edit'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.show(params.index)
                                        }
                                    }
                                }),
                                h('Button', {
                                    props: {
                                        type: 'ghost',
                                        size: 'small',
                                        shape:"circle",
                                        icon:'android-delete'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.tableRemove(params.index)
                                        }
                                    }
                                }),
                                h('Button', {
                                    props: {
                                        type: 'ghost',
                                        size: 'small',
                                        shape:"circle",
                                        icon:'gear-b'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            this.remove(params.index)
                                        }
                                    }
                                })
                            ]);
                        }
                    }
                ],
                data1: this.getTableData(),
                imgUpload:{
                    showUploadModal:true,
                    imgName:'',
                    imagesList:[],
                    uploadList:[],
                },
                visible: false,
            }
        },
        methods: {
            getTableData(){
                var table_data = [];
                for (var i = 0; i < 10; i++) {
                    table_data[i] = {
                        id: '1',
                        nickname: '周先生',
                        image: '11111',
                        role: '超级管理员',
                        login_time: '2017-05-22 23:30:34',
                        create_time: '2017-05-21 19:27:16'
                    }
                }
                return table_data;
            },
            setStatus(name,status){
                this.searchStatusName = name;
                this.searchStatus = status;
                this.statusColor = '#77818e';
            },
            tableRemove (index) {
                this.$Modal.confirm({
                    title: '删除管理员',
                    content: '删除的信息不可恢复，是否删除',
                    onOk: () => {
                        this.data1.splice(this.data1, 1);
                    },
                });
            },
            handleView (name) {
                this.imgUpload.imgName = name;
                this.visible = true;
            },
            handleRemove (file) {
                // 从 upload 实例删除数据
                this.imgUpload.showUploadModal = true;
                const fileList = this.$refs.upload.fileList;
                this.$refs.upload.fileList.splice(fileList.indexOf(file), 1);
            },
            handleSuccess (res, file) {
                // 因为上传过程为实例，这里模拟添加 url
                file.url = 'https://o5wwk8baw.qnssl.com/7eb99afb9d5f317c912f08b5212fd69a/avatar';
                file.name = '7eb99afb9d5f317c912f08b5212fd69a';
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
            }
        },
        mounted () {
            this.imgUpload.uploadList = this.$refs.upload.fileList;
        }
    }
</script>