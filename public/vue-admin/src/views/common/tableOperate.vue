<style>

</style>
<template>

</template>
<script>
    export default {
        methods: {
            tableUpdate(index,updateData,data){
                updateData = data[index];
                updateData.modal = true;
            }, /** 点击删除角色 */
            tableRemove (index,data,msg) {
                this.$Modal.confirm({
                    title: '危险操作',
                    content: '删除的 '+msg+' 不可恢复，是否删除',
                    onOk: () => {
                        this.$http({
                            method: 'POST',
                            url: serverUlr + apiUrl.adminDeleteRole,
                            params: {id:this.roleData.data[index]['id']},
                            headers: {"Authorization": "Bearer " + window.localStorage.getItem('token')},
                        }).then(response => {
                            if (response.data.status_code != 200) {
                                this.$Message.error(response.data.message, 2);
                            } else {
                                this.$Message.success('删除成功', 2);
                                this.roleData.data.splice(index, 1);
                            }
                        }, response => {
                            this.$Message.error('无法连接服务器!', 2);
                        });
                    },
                });
            }, /** 当主权限点击的时候做操作 */
            onMasterCheckChange(id, index){
                if (this.addRole.data.master_menu.indexOf(id) !== -1) {
                    this.addRole.data.master_menu = this.addRole.data.master_menu.filter(function (item) {
                        return item !== id;
                    });
                    var subMenu = this.RoleList.roles[index]['sub_menu'];
                    console.log(subMenu);
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
                this.getRoleList(1,15);
            },
            submitRole(){
                if(this.addRole.data.id){
                    this.setRole(apiUrl.adminUpdateRole);
                }else{
                    this.setRole(apiUrl.adminAddRole);
                }
            },
            nextPageData(page){
                this.getRoleList(page,15);
            },
            setRole(url){
                this.$http({
                    method: 'POST',
                    url: serverUlr + url,
                    params: this.addRole.data,
                    headers: {"Authorization": "Bearer " + window.localStorage.getItem('token')},
                }).then(response => {
                    if (response.data.status_code != 200) {
                        this.$Message.error(response.data.message, 2);
                    } else {
                        this.$Message.success('操作成功', 2);
                        this.getRoleList(1,15);
                    }
                    this.addRole.modal = false;
                }, response => {
                    this.$Message.error('无法连接服务器!', 2);
                });
            },
            getRoleList(page,page_size){
                this.$Message.loading('正在拉取数据...',0);

                this.$http({
                    method: 'POST',
                    url: serverUlr + apiUrl.adminRoleList,
                    params: {page:page,page_size:page_size,sort:this.sort},
                    headers: {"Authorization": "Bearer " + window.localStorage.getItem('token')},
                }).then(response => {
                    if (response.data.status_code != 200) {
                        this.$Message.error('获取角色列表失败', 2);
                    } else {
                        this.roleData = response.data.data;
                    }
                    this.$Message.destroy();
                }, response => {
                    this.$Message.error('无法连接服务器!', 2);
                    this.$Message.destroy()
                });
            },
            getMenuList(){
                /** 获取全部菜单权限 */
                this.$http({
                    method: 'POST',
                    url: serverUlr + apiUrl.adminMenuList,
                    params: {},
                    headers: {"Authorization": "Bearer " + window.localStorage.getItem('token')},
                }).then(response => {
                    if (response.data.status_code != 200) {
                        this.$Message.error('获取菜单列表失败', 2);
                    } else {
                        this.RoleList.roles = response.data.data;
                    }
                }, response => {
                    this.$Message.error('无法连接服务器!', 2);
                });
            },
        }
    }
</script>