<style scoped>
    .Master-Menu {
        width: 100%;
    }

    .logo {
        float: left;
        position: relative;
        left: 15px;
        top: 5px;
    }

    .breadcrumb-show {
        float: left;
        position: relative;
        left: 30px;
        top: 5px;
    }

    .out-color {
        color: red;
    }

    .sub-menu {
        position: relative;
        left: 5px;
    }

    .user-detail-modal {
        margin-left: auto;
        margin-right: auto;
        margin-top: 10px;
    }
</style>
<template>
    <div style="height: 100%;background-color: rgba(230, 230, 230, 0.59)">
        <Spin size="large" v-show="Loading" fix></Spin>
        <!--菜单管理-->
        <template>
            <Menu mode="horizontal" :model="menu_list" :theme="theme1" :active-name="reset_active" class="Master-Menu">
                <img width="50px" class="logo"
                     src="https://raw.githubusercontent.com/iview/iview/master/assets/logo.png">
                <Breadcrumb class="breadcrumb-show">
                    <Breadcrumb-item :href="breadcrumbLists.url" v-for="breadcrumbLists in breadcrumbMenuList">
                        <Icon :type="breadcrumbLists.icon"></Icon>
                        {{breadcrumbLists.name}}
                    </Breadcrumb-item>
                </Breadcrumb>
                <template>
                    <Submenu name="1001" style="float: right">
                        <template slot="title">
                            <Icon type="person"></Icon>
                            我的账号
                        </template>
                        <div style="margin-top: 15px;">
                            <Menu-item name="1001-1" @click.native="showAdminInfoModal">
                                <Icon type="ios-person"></Icon>
                                <span class="sub-menu">个人信息</span>
                            </Menu-item>
                            <Menu-item name="1001-2" @click.native="showSetPasswordModal">
                                <Icon type="ios-unlocked"></Icon>
                                <span class="sub-menu">修改密码</span>
                            </Menu-item>
                            <Menu-item name="1001-3" @click.native="loginOut">
                                <Icon type="power"></Icon>
                                <span class="sub-menu out-color">退出登录</span>
                            </Menu-item>
                        </div>
                    </Submenu>
                </template>
                <!--<template>
                    <Submenu name="1002" style="float: right">
                        <template slot="title">
                            <Icon type="android-settings"></Icon>
                            系统设置
                        </template>
                        <div style="margin-top: 15px;">
                            <Menu-item name="1002-1" @click.native="redirectUrl('/home/admin/menu')">
                                <Icon type="android-menu"></Icon>
                                <span class="sub-menu">菜单列表</span>
                            </Menu-item>
                            <Menu-item name="1002-2" @click.native="redirectUrl('/home/admin/role')">
                                <Icon type="wrench"></Icon>
                                <span class="sub-menu">权限列表</span>
                            </Menu-item>
                            <Menu-item name="1002-3" @click.native="redirectUrl('/home/admin/user')">
                                <Icon type="person-stalker"></Icon>
                                <span class="sub-menu">管理员列表</span>
                            </Menu-item>
                            &lt;!&ndash;<Menu-item name="1002-4" @click.native="redirectUrl('/home/admin/log')">
                                <Icon type="clipboard"></Icon>
                                <span class="sub-menu">日志系统</span>
                            </Menu-item>&ndash;&gt;
                        </div>
                    </Submenu>
                </template>-->
                <template v-for="(menus,index) in menu_list">
                    <Submenu :name="menus.id" style="float: right" v-if="menus.sub_menu.length > 0">
                        <template slot="title">
                            <Icon :type="menus.icon"></Icon>
                            {{menus.name}}
                        </template>
                        <div style="margin-top: 15px;">
                            <Menu-item :name="subMenus.id" v-for="(subMenus,subIndex) in menus.sub_menu"
                                       @click.native="redirectUrl(subMenus.url,menus,subMenus)">
                                <Icon :type="subMenus.icon"></Icon>
                                <span class="sub-menu">{{subMenus.name}}</span>
                            </Menu-item>
                        </div>
                    </Submenu>
                    <Menu-item :name="menus.id" style="float: right"
                               @click.native="redirectUrl(menus.url,menus,[])" v-else>
                        <Icon :type="menus.icon"></Icon>
                        <span class="sub-menu">{{menus.name}}</span>
                    </Menu-item>
                </template>
            </Menu>
        </template>
        <router-view></router-view>
        <SetPassword ref="set_password"></SetPassword>
        <SetAdminInfo ref="set_admin_info"></SetAdminInfo>
    </div>
</template>
<script>
    import SetPassword from "./common/SetPassword";
    import SetAdminInfo from "./common/SetAdminInfo";
    export default {
        components: {
            SetPassword, SetAdminInfo,
        },
        created(){
            this.Loading = true;
            this.getMenuList();
        },
        mounted(){

        },
        data () {
            return {
                menu_list: [],
                breadcrumbMenuList: [],
                reset_active: '',
                Loading: false,
                theme1: 'light',
            }
        },
        methods: {
            redirectUrl(url){
                this.getMenuBread(this.menu_list, url);
                this.$router.push({path: url});
            },
            getMenuBread(menus, url){
                let self = this;
                for (var i = 0; i < menus.length; i++) {
                    if (menus[i].sub_menu.length > 0) {
                        for (var i2 = 0; i2 < menus[i].sub_menu.length; i2++) {
                            if (menus[i].sub_menu[i2].url == url) {
                                this.breadcrumbMenuList[0] = {url: menus[i].url, name: menus[i].name};
                                this.breadcrumbMenuList[1] = {
                                    url: menus[i].sub_menu[i2].url,
                                    name: menus[i].sub_menu[i2].name
                                };
                                /*setTimeout(function () {
                                    self.reset_active = menus[i].sub_menu[i2].id;
                                    console.log(self.reset_active);
                                },5000);*/
                            }
                        }
                    } else {
                        if (menus[i].url == url) {
                            this.breadcrumbMenuList[0] = {url: menus[i].url, name: menus[i].name};
                            this.breadcrumbMenuList[1] = {url: '#', name: '无'};
                            /*setTimeout(function () {
                                self.reset_active = menus[i].id;
                                console.log(self.reset_active);
                            },5000);*/
                        }
                    }
                }
            },
            loginOut(){
                let self = this;
                this.$Notice.warning({
                    title: '正在退出登录...',
                    desc: '',
                    duration: 2,
                    onClose: function () {
                        //window.localStorage.clear();
                        window.localStorage.setItem('token', null);
                        self.$store.state.adminInfo = [];
                        self.$router.push({path: '/'});
                    }
                });
            },
            getMenuList(){
                let self = this;
                if (this.$store.state.adminInfo.length == 0) {
                    this.postData(serverUlr + apiUrl.adminMyInfo, {},
                        function (result) {
                            self.$store.state.adminInfo = result.data;
                            self.menu_list = result.data.menuList;
                            self.$store.state.roleOperateArr = result.data.roleList;
                            self.Loading = false;
                            self.getMenuBread(self.menu_list, self.$route.path);
                        }, function (result) {
                            self.$Message.error(result.message, 2);
                        }, function () {
                            //self.$Message.error('服务器异常!', 1.5);
                            self.Loading = false;
                        }
                    );
                } else {
                    this.menu_list = this.$store.state.adminInfo.menuList;
                    this.getMenuBread(this.menu_list, this.$route.path);
                    this.Loading = false;
                }
            },
            showAdminInfoModal(){
                this.$refs.set_admin_info.adminInfo.modal = true;
                this.$refs.set_admin_info.adminInfo.data = this.$store.state.adminInfo;
                this.$refs.set_admin_info.adminInfo.imgUpload.uploadList[0] = {
                    url: this.$store.state.adminInfo.icon,
                    status: 'finished'
                };
            },
            showSetPasswordModal(){
                this.$refs.set_password.setPassword.modal = true;
            },
        }
    }
</script>