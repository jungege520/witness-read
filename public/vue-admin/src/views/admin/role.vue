<style>
    .add-admin {
        float: right;
        margin-right: 15px;
    }

    .table-card {
        width: 92%;
        height: 85%;
        margin-top: 2%;
        margin-left: auto;
        margin-right: auto;
    }

    .role-list {
        width: 95%;
        margin-top: 2%;
        margin-left: auto;
        margin-right: auto;
    }

</style>
<template>
    <Card class="table-card">
        <div>
            <div style="height: 60px;">
                <Button type="primary" shape="circle" class="add-admin" style="margin-top: 5px;"
                        @click="addRoleBtn" v-if="roleIn('add')">添加角色
                </Button>
            </div>

            <Table :data="roleData.data" :columns="columns1" :height="this.$store.state.tableHeight"
                   @on-sort-change="sortTable"></Table>

            <div style="margin: 10px;bottom:10px;position:absolute;right: 15px;">
                <div style="">
                    <Page :total="roleData.total" :current="1" @on-change="nextPageData" :page-size="15"></Page>
                </div>
            </div>

            <div>
                <Modal v-model="addRole.modal" title="添加角色" width="400" ok-text="保存" cancel-text="返回"
                       @on-ok="submitRole" :loading=true>
                    <div style="margin-top: 20px;">
                        <Form :label-width="60">
                            <Form-item label="名称">
                                <Input type="text" v-model="addRole.data.name" placeholder="请输入角色名称"></Input>
                            </Form-item>
                            <Form-item label="描述">
                                <Input type="text" v-model="addRole.data.desc" placeholder="请输入角色描述信息"></Input>
                            </Form-item>
                            <Form-item label="权限">
                                <Button type="ghost"
                                        :style="{'width':'308px','color':addRole.data.master_menu.length > 0?'#3399ff':''}"
                                        @click="RoleList.modal = true">
                                    {{addRole.data.master_menu.length > 0 ? '权限已设置' : '选择权限'}}
                                </Button>
                            </Form-item>
                        </Form>
                    </div>
                </Modal>
            </div>
            <div>
                <Modal v-model="RoleList.modal" title="选择权限" width="500" ok-text="确定" cancel-text="取消"
                       @on-ok="" @on-cancel="">
                    <div class="role-list" style="height: 400px;overflow: auto;">
                        <Card class="role-card"
                              style="width:90%;margin-left:auto;margin-right:auto;margin-top: 15px;background-color: rgba(246, 247, 249, 0.62)"
                              v-for="(roles,index) in RoleList.roles">
                            <div style="border-bottom: 1px solid #e9e9e9;padding-bottom:6px;margin-bottom:6px;">
                                <Checkbox :value="addRole.data.master_menu.indexOf(roles.id) !== -1"
                                          @on-change="onMasterCheckChange(roles.id,index)">{{roles.name}}
                                </Checkbox>
                            </div>
                            <Checkbox-group v-model="addRole.data.sub_menu" v-if="roles.sub_menu.length != 0">
                                <Checkbox :label="subRoles.id" v-for="(subRoles,subIndex) in roles.sub_menu"
                                          style="margin-left: 5px;margin-top: 10px;">{{subRoles.name}}
                                </Checkbox>
                            </Checkbox-group>
                        </Card>
                    </div>
                </Modal>
            </div>
        </div>
    </Card>
</template>
<script>
    export default {
        mounted(){
            //组件已经初始化开始的操作
            this.getMenuList();
        },
        created(){
            this.getRoleList(1, 15);
            console.log(this.$route.path);
        },
        data () {
            return {
                subGroupList: [],
                masterGroupList: [],
                page: 1,
                sort: {
                    key: '',
                    orderBy: '',
                },
                addRole: {
                    modal: false,
                    submitBtn: true,
                    data: {
                        id: '',
                        name: '',
                        desc: '',
                        role: [],
                        master_menu: [],
                        sub_menu: []
                    },
                },
                RoleList: {
                    modal: false,
                    showIndex: 1,
                    roles: [],
                },
                roleData: [],
                columns1: [
                    {
                        title: 'id',
                        key: 'id',
                        align: 'center',
                        sortable: 'custom',
                    }, {
                        title: '角色名称',
                        key: 'name',
                        align: 'center',
                    }, {
                        title: '描述',
                        key: 'desc',
                        align: 'center',
                    }, {
                        title: '更新时间',
                        key: 'update_time',
                        align: 'center',
                        sortable: 'custom',
                    }, {
                        title: '添加时间',
                        key: 'create_time',
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
                                        icon: 'edit',
                                    },
                                    style: {
                                        marginRight: '5px',
                                        display:this.roleIn('edit')?'':'none',
                                    },
                                    on: {
                                        click: () => {
                                            this.updateRoleShow(params.index)
                                        }
                                    }
                                }),
                                h('Button', {
                                    props: {
                                        type: 'ghost',
                                        size: 'small',
                                        shape: "circle",
                                        icon: 'android-delete',
                                    },
                                    style: {
                                        marginRight: '5px',
                                        display:this.roleIn('delete')?'':'none',
                                    },
                                    on: {
                                        click: () => {
                                            this.roleRemove(params.index)
                                        }
                                    }
                                })
                            ]);
                        }
                    }
                ],
            }
        },
        methods: {
            updateRoleShow(index){
                this.addRole.data = this.roleData.data[index];
                this.addRole.modal = true;
            }, /** 点击删除角色 */
            roleRemove (index) {
                this.$Modal.confirm({
                    title: '删除角色',
                    content: '删除的角色信息不可恢复，是否删除',
                    onOk: () => {
                        let self = this;
                        this.postData(
                            serverUlr + apiUrl.adminDeleteRole,
                            {id: this.roleData.data[index]['id']},
                            function (result) {
                                self.$Message.success('删除成功', 2);
                                self.roleData.data.splice(index, 1);
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
            }, /** 当主权限点击的时候做操作 */
            onMasterCheckChange(id, index){
                if (this.addRole.data.master_menu.indexOf(id) !== -1) {
                    this.addRole.data.master_menu = this.addRole.data.master_menu.filter(function (item) {
                        return item !== id;
                    });
                    var subMenu = this.RoleList.roles[index]['sub_menu'];
                    for (var i = 0; i < subMenu.length; i++) {
                        if (this.addRole.data.sub_menu.indexOf(subMenu[i].id) !== -1) {
                            this.addRole.data.sub_menu = this.addRole.data.sub_menu.filter(function (item) {
                                return item !== subMenu[i].id;
                            });
                        }
                    }
                } else {
                    this.addRole.data.master_menu.push(id);
                }
            },
            addRoleBtn(){
                this.addRole.data.master_menu = [];
                this.addRole.data.sub_menu = [];
                this.addRole.data.id = '';
                this.addRole.data.name = '';
                this.addRole.data.desc = '';
                this.addRole.modal = true;
            },
            cancelRoleList(){
                this.addRole.data.master_menu = [];
                this.addRole.data.sub_menu = [];
            },
            sortTable(column){
                this.sort.key = column.key;
                this.sort.orderBy = column.order;
                this.getRoleList(1, 15);
            },
            submitRole(){
                if (this.addRole.data.id) {
                    this.setRole(apiUrl.adminUpdateRole);
                } else {
                    this.setRole(apiUrl.adminAddRole);
                }
            },
            nextPageData(page){
                this.getRoleList(page, 15);
            },
            setRole(url){
                let self = this;
                this.postData(
                    serverUlr + url,
                    this.addRole.data,
                    function (result) {
                        self.$Message.success('操作成功', 1.5, function () {
                            self.getRoleList(1, 15);
                        });
                        self.addRole.modal = false;
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
            getRoleList(page, page_size){
                this.$Message.loading('正在拉取数据...', 0);

                let self = this;
                this.postData(
                    serverUlr + apiUrl.adminRoleList,
                    {page: page, page_size: page_size, sort: this.sort},
                    function (result) {
                        self.roleData = result.data;
                        self.$Message.destroy();
                    }, function (result) {
                        self.$Message.error(result.message, 2, function () {

                        });
                        self.$Message.destroy();
                    }, function () {
                        //self.$Message.error('无法连接服务器!', 3);
                        self.$Message.destroy();
                    }
                );

            },
            getMenuList(){
                let self = this;
                this.postData(
                    serverUlr + apiUrl.adminMenuRoleList, {},
                    function (result) {
                        self.RoleList.roles = result.data;
                    }, function (result) {
                        self.$Message.error(result.message, 2, function () {

                        });
                    }, function () {
                        //self.$Message.error('无法连接服务器!', 3);
                    }
                );
            },
            resetLoadingBtn(){
                this.addRole.btnModal = false;
                let _self = this;
                setTimeout(function () {
                    _self.addRole.btnModal = true;
                }, 100);
            }
        }
    }
</script>