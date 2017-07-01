<style>
    .menu-master {
        height: 92%;
    }

    .menu-list {
        float: left;
        width: 35%;
        height: 92%;
        margin-top: 2%;
        margin-left: 3%;
        margin-right: auto;
        overflow: auto;
    }

    .menu-on-sub {
        width: 28%;
    }

    .menu-list-sub {
        float: left;
        width: 24%;
        height: 92%;
        margin-top: 2%;
        margin-left: 1%;
        margin-right: auto;
        overflow: auto;
    }

    .add-menu {
        float: left;
        width: 54%;
        height: 92%;
        margin-top: 2%;
        margin-left: 3%;
        margin-right: auto;
        overflow: auto;
    }

    .on-sub {
        width: 39%;
    }

    .on-operate-sub {
        width: 30%;
    }

    .menu-operate {
        width: 20%;
    }

    .menu-btn {
        width: 90%;
        margin-left: auto;
        margin-right: auto;
        margin-top: 10px;
    }

    .btn-color {
        color: #3399ff;
    }

    .operate-color {
        color: #19be6b;
    }
</style>
<template>
    <div class="menu-master">
        <Card :class="['menu-list',menu_on_sub,operate_sub]">
            <draggable v-model="menuLists" @change="updateSort(1)">
                <transition-group>
                    <Card :class="{'menu-btn':true,'btn-color':m_menu_active===menus.id,'operate-color':m_operate_active===menus.id}"
                          v-for="(menus,index) in menuLists"
                          :key="menus.id">
                        <Icon :type="menus.icon"></Icon>
                        <span style="margin-left: 10px;">{{menus.name}}</span>
                        <span style="float: right;margin-right: 7px;" @click="removeMenu(1,index,menus.name)" v-if="roleIn('delete')">
                            <Tooltip content="删除" placement="top">
                                <Icon type="android-delete"></Icon>
                            </Tooltip>
                        </span>
                        <span style="float: right;margin-right: 15px;" @click="editMenu(menus)">
                            <Tooltip content="编辑" placement="top" v-if="roleIn('edit')">
                                <Icon type="edit"></Icon>
                            </Tooltip>
                        </span>
                        <span style="float: right;margin-right: 15px;" @click="openSubMenu(menus.id,menus.sub_menu)"
                              v-if="menus.sub_menu.length != 0">
                            <Tooltip content="查看子菜单" placement="top">
                                <Icon type="merge"></Icon>
                            </Tooltip>
                        </span>
                        <span style="float: right;margin-right: 15px;"
                              @click="openOperateMenu(menus.id,menus.sub_operate_menu,1)"
                              v-if="menus.sub_operate_menu.length != 0">
                            <Tooltip content="查看操作权限" placement="top">
                                <Icon type="ios-cog"></Icon>
                            </Tooltip>
                        </span>
                    </Card>
                </transition-group>
            </draggable>
        </Card>
        <Card :class="['menu-list-sub',operate_sub]" v-model="menuSubLists" v-if="menuSubListModal">
            <draggable v-model="menuSubLists" @change="updateSort(2)">
                <transition-group>
                    <Card :class="{'menu-btn':true,'btn-color':s_menu_active===sub_menus.id,'operate-color':s_operate_active===sub_menus.id}"
                          v-for="(sub_menus,index) in menuSubLists" :key="sub_menus.id">
                        <Icon :type="sub_menus.icon"></Icon>
                        <span style="margin-left: 10px;">{{sub_menus.name}}</span>
                        <span style="float: right;margin-right: 7px;" @click="removeMenu(2,index,sub_menus.name)" v-if="roleIn('delete')">
                            <Tooltip content="删除" placement="top">
                                <Icon type="android-delete"></Icon>
                            </Tooltip>
                        </span>
                        <span style="float: right;margin-right: 15px;" @click="editMenu(sub_menus)" v-if="roleIn('edit')">
                            <Tooltip content="编辑" placement="top">
                                <Icon type="edit"></Icon>
                            </Tooltip>
                        </span>
                        <span style="float: right;margin-right: 15px;"
                              @click="openOperateMenu(sub_menus.id,sub_menus.sub_operate_menu,2)"
                              v-if="sub_menus.sub_operate_menu && sub_menus.sub_operate_menu.length != 0">
                            <Tooltip content="查看操作权限" placement="top">
                                <Icon type="ios-cog"></Icon>
                            </Tooltip>
                        </span>
                    </Card>
                </transition-group>
            </draggable>
        </Card>

        <Card :class="['menu-list-sub',operate_sub]" v-model="menuOperateSubLists" v-if="menuOperateSubListModal">
            <div v-model="menuOperateSubLists">
                <div>
                    <Card class="menu-btn" v-for="(sub_operate_menus,index) in menuOperateSubLists"
                          :key="sub_operate_menus.id">
                        <Icon :type="sub_operate_menus.icon"></Icon>
                        <span style="margin-left: 10px;">{{sub_operate_menus.name}}</span>
                        <span style="float: right;margin-right: 7px;"
                              @click="removeMenu(2,index,sub_operate_menus.name)" v-if="roleIn('delete')">
                            <Tooltip content="删除" placement="top">
                                <Icon type="android-delete"></Icon>
                            </Tooltip>
                        </span>
                        <span style="float: right;margin-right: 15px;" @click="editMenu(sub_operate_menus)" v-if="roleIn('edit')">
                            <Tooltip content="编辑" placement="top">
                                <Icon type="edit"></Icon>
                            </Tooltip>
                        </span>
                    </Card>
                </div>
            </div>
        </Card>

        <Card :class="['add-menu',add_on_sub]" v-if="roleIn('add')">
            <div style="width: 80%;margin: 10% auto auto auto;">
                <Form :model="menuInfo" :label-width="80">
                    <Form-item label="菜单名">
                        <Input v-model="menuInfo.name" placeholder="请输入菜单名称"></Input>
                    </Form-item>
                    <!--<Form-item label="菜单等级">
                        <Select v-model="menuInfo.parent_id" placeholder="请选择">
                            <Option value="0" :selected="!menuInfo.parent_id">无</Option>
                            <Option :value="master_menus.id" :selected="master_menus.id === menuInfo.parent_id"
                                    v-for="(master_menus,index) in menuLists" v-if="menuInfo.id != master_menus.id">
                                {{master_menus.name}}
                            </Option>
                        </Select>
                    </Form-item>-->
                    <Form-item label="菜单等级">
                        <Cascader :data="cascadeData" v-model="choose_data" change-on-select
                                  @on-change="changeMenuParent"></Cascader>
                    </Form-item>
                    <Form-item label="图标">
                        <Input v-model="menuInfo.icon" placeholder="请选择菜单图标"></Input>
                    </Form-item>
                    <Form-item label="菜单权限">
                        <Input v-model="menuInfo.model" placeholder="请输入菜单权限名称"></Input>
                    </Form-item>
                    <Form-item label="链接地址">
                        <Input v-model="menuInfo.url" placeholder="请输入菜单链接"></Input>
                    </Form-item>
                    <Form-item label="操作菜单">
                        <i-switch v-model="menuInfo.type" @on-change=""></i-switch>
                    </Form-item>
                    <Form-item label="是否显示">
                        <i-switch v-model="menuInfo.status" @on-change=""></i-switch>
                    </Form-item>
                    <Form-item label="菜单描述">
                        <Input v-model="menuInfo.desc" type="textarea" :autosize="{minRows: 2,maxRows: 5}"
                               placeholder="请输入菜单描述"></Input>
                    </Form-item>
                    <Form-item>
                        <Button type="primary" style="width: 120px;" :loading="submitBtn" @click="submitMenu">
                            {{this.showBtnName}}
                        </Button>
                    </Form-item>
                </Form>
            </div>
        </Card>
    </div>
</template>
<script>
    import draggable from 'vuedraggable';
    export default {
        created(){
            this.getMenuList();
        },
        components: {
            draggable,
        },
        data () {
            return {
                submitBtn: false,//提交loading显示
                showBtnName: '提交',//提交按钮名称
                menuOperateSubListModal: false,
                menuOperateSubLists: [],
                menuSubListModal: false,//显示子菜单
                menuSubLists: [],//子菜单数组
                menuActiveIndex: -1,//主菜单点击查看子菜单样式
                menu_on_sub: '',//子菜单样式 影响左侧样式
                operate_sub: '',//操作菜单样式 影响菜单栏宽度
                add_on_sub: '',//子菜单样式 影响右侧样式
                m_menu_active: -1,//菜单选中样式
                m_operate_active: -1,//操作菜单选中样式
                s_menu_active: -1,//菜单选中样式
                s_operate_active: -1,//操作菜单选中样式
                menuLists: [],
                menuInfo: {
                    id: '',
                    name: '',
                    parent_id: 0,
                    icon: '',
                    model: '',
                    status: true,
                    url: '',
                    type: false,
                    desc: '',
                },
                choose_data: [],
                cascadeData: []
            }
        },
        methods: {
            openSubMenu(index, menus){
                if ((this.menuSubListModal && this.m_menu_active === index) || (this.menuOperateSubListModal && this.m_operate_active === index)) {
                    this.closeSubMenu();
                    return false;
                }
                this.m_menu_active = index;
                this.m_operate_active = -1;
                this.menu_on_sub = 'menu-on-sub';
                this.add_on_sub = 'on-sub';
                this.operate_sub = '';
                this.menuSubListModal = true;
                this.menuOperateSubListModal = false;
                this.menuOperateSubLists = false;
                this.menuSubLists = menus;
                //this.menuActiveIndex = index;
            },
            openOperateMenu(index, menus, type){

                if (type == 1) {
                    if ((this.menuSubListModal && this.m_menu_active === index) || (this.menuOperateSubListModal && this.m_operate_active === index)) {
                        this.closeSubMenu();
                        return false;
                    }
                    this.m_operate_active = index;
                    this.m_menu_active = -1;
                    this.menu_on_sub = 'menu-on-sub';
                    this.add_on_sub = 'on-sub';
                } else {
                    if (this.menuOperateSubListModal && this.s_operate_active === index) {
                        this.menuOperateSubListModal = false;
                        this.operate_sub = '';
                        this.menu_on_sub = 'menu-on-sub';
                        this.add_on_sub = 'on-sub';
                        this.menuOperateSubLists = [];
                        this.s_operate_active = -1;
                        return false;
                    }
                    this.s_operate_active = index;
                    this.s_menu_active = -1;
                    this.menu_on_sub = '';
                    this.operate_sub = 'menu-operate';
                    this.add_on_sub = 'on-operate-sub';
                }
                this.menuOperateSubListModal = true;
                this.menuOperateSubLists = menus;
                //this.menuSubListModal = true;
                //this.menuSubLists = menus;
                //this.menuActiveIndex = index;
            },
            closeSubMenu(){
                this.menu_on_sub = '';
                this.add_on_sub = '';
                this.operate_sub = '';
                this.menuSubListModal = false;
                this.menuOperateSubListModal = false;
                this.menuOperateSubLists = [];
                this.menuSubLists = [];
                this.m_menu_active = -1;
                this.m_operate_active = -1;
            },
            editMenu(menu){
                console.log(menu);
                this.menuInfo = menu;
                this.choose_data = this.getMenuSubLists(this.menuInfo.parent_id);
            },
            changeMenuParent(value, selectedData){
                this.menuInfo.parent_id = value.length > 0 ? value[value.length - 1] : 0;
            },
            removeMenu(type, index, name){
                this.$Modal.confirm({
                    title: '删除菜单',
                    content: '删除菜单 <strong style="color:#3399ff;font-size:14px;"> ' + name + '</strong> 不可恢复，是否删除',
                    onOk: () => {
                        let delete_id = type == 1 ? this.menuLists[index]['id'] : this.menuSubLists[index]['id'];

                        let self = this;
                        this.postData(
                            serverUlr + apiUrl.adminDeleteMenu,
                            {id: delete_id},
                            function (result) {
                                self.$Message.success('删除成功', 2);
                                if (type == 1) {
                                    if (self.menu_active === index) {
                                        self.closeSubMenu();
                                    }
                                    self.menuLists.splice(index, 1);
                                } else {
                                    self.menuSubLists.splice(index, 1);
                                    self.menuLists[self.menuSubListsIndex].splice(index, 1);
                                }
                                if (delete_id == self.menuInfo.id) {
                                    self.menuInfo = [];
                                }

                            }, function (result) {
                                self.$Message.error(result.message, 2, function () {
                                    self.resetSubmitBtn();
                                });
                            }, function () {
                                self.$Message.error('服务器异常!', 2, function () {

                                });
                            }
                        );
                    },
                });
            },
            updateSort(type){
                let arr = [];
                if (type == 1) {
                    for (let i = 0; i < this.menuLists.length; i++) {
                        arr.push(this.menuLists[i].id);
                    }
                } else {
                    for (let i = 0; i < this.menuSubLists.length; i++) {
                        arr.push(this.menuSubLists[i].id);
                    }
                }
                let self = this;
                this.postData(
                    serverUlr + apiUrl.adminUpdateMenuSort,
                    {menu_sort: arr},
                    function (result) {
                        self.roleData = result.data;
                        self.$Message.destroy();
                    }, function (result) {
                        self.$Message.error(result.message, 2, function () {

                        });
                    }, function () {
                        //self.$Message.error('无法连接服务器!', 3);
                    }
                );
            },
            showMenu(){

            },
            submitMenu(){
                this.submitBtn = true;
                this.showBtnName = '正在提交';
                console.log(this.menuInfo);
                if (!this.menuInfo.name || !this.menuInfo.icon || !this.menuInfo.model || !this.menuInfo.url || !this.menuInfo.desc) {
                    this.$Message.error('请先完善菜单信息！', 1.5);
                    this.resetSubmitBtn();
                    return false;
                }
                if (this.menuInfo.id) {
                    this.setMenu(apiUrl.adminUpdateMenu);
                } else {
                    this.setMenu(apiUrl.adminAddMenu);
                }
            },
            setMenu(url){
                if (this.menuInfo.status) {
                    this.menuInfo.status = 1;
                } else {
                    this.menuInfo.status = 0;
                }
                if (this.menuInfo.type) {
                    this.menuInfo.type = 1;
                } else {
                    this.menuInfo.type = 0;
                }

                var menuData = JSON.parse(JSON.stringify(this.menuInfo));
                delete menuData.sub_menu;
                delete menuData.sub_operate_menu;
                let self = this;
                this.postData(
                    serverUlr + url,
                    menuData,
                    function (result) {
                        self.$Message.success('操作成功', 1.5, function () {
                            self.getMenuList();
                            self.resetSubmitBtn();
                        });
                    }, function (result) {
                        self.$Message.error(result.message, 2, function () {
                            self.resetSubmitBtn();
                        });
                    }, function () {
                        self.$Message.error('服务器异常!', 2, function () {
                            self.resetSubmitBtn();
                        });
                    }
                );
            },
            resetSubmitBtn(){
                this.submitBtn = false;
                this.showBtnName = '提交';
            },
            getMenuSubLists(choose){
                let data = [{value:0,label:'无'}];
                for (let i = 0; i < this.menuLists.length; i++) {
                    if(typeof choose == 'number' && choose == this.menuLists[i].id){
                        return [this.menuLists[i].id];
                    }
                    let arr = [];
                    for (let i2 = 0; i2 < this.menuLists[i].sub_menu.length; i2++) {
                        if(typeof choose == 'number' && choose == this.menuLists[i].sub_menu[i2].id){
                            return [this.menuLists[i].id,this.menuLists[i].sub_menu[i2].id];
                        }
                        arr.push({
                            value: this.menuLists[i].sub_menu[i2].id,
                            label: this.menuLists[i].sub_menu[i2].name
                        });
                    }
                    data.push({value: this.menuLists[i].id, label: this.menuLists[i].name, children: arr});
                }
                this.cascadeData = data;
            },
            getMenuList(){
                this.$Message.loading('正在拉取数据...', 0);
                let self = this;
                this.postData(
                    serverUlr + apiUrl.adminMenuList,
                    {},
                    function (result) {
                        self.menuLists = result.data;
                        self.getMenuSubLists('');
                        self.$Message.destroy();
                    }, function (result) {
                        self.$Message.error(result.message, 2, function () {

                        });
                        self.$Message.destroy();
                    }, function () {
                        self.$Message.error('服务器异常!', 2, function () {

                        });
                        self.$Message.destroy();
                    }
                );

            }
        },
    }
</script>