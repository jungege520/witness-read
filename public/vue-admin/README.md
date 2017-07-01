iview-admin 项目介绍
================
基于iview + vuejs2.0 构建的管理后台基础模板，使用的前后端分离的模式开发的，后端用的lumen5.4 + jwt + dingo开发的。[线上地址](https://admin.fastgoo.net/vue/dist/index.html)  账号 admin  密码 123456

示例图片
================
![admin.fastgoo.net](https://github.com/jungege520/iview-admin/blob/master/example/1.jpeg)
![admin.fastgoo.net](https://github.com/jungege520/iview-admin/blob/master/example/2.jpeg)
![admin.fastgoo.net](https://github.com/jungege520/iview-admin/blob/master/example/3.jpeg)


使用组件
================
1、[UI组件 iview](https://github.com/iview/iview)<br>
2、[通讯组件vue-resource](https://github.com/pagekit/vue-resource)<br> 
3、[高德地图组件 vue-amap](https://github.com/ElemeFE/vue-amap)<br> 
4、[富文本编辑器 vue2-editor](https://github.com/davidroyer/vue2-editor)<br> 
5、[菜单拖拽排序 Vue.Draggable](https://github.com/SortableJS/Vue.Draggable)<br>
6、[jquery 插件](#)<br> 
7、[MD5加密](#)<br>


安装说明
================
###
	git clone https://github.com/jungege520/iview-admin.git iview-admin
	npm install  //安装依赖
	npm run dev  //本机运行
	npm run build  //打包线上文件


项目文件介绍
================
###
	1、config---api_url   请求接口的地址集合
	2、plugins---commonAction.js   公共使用方法，全局注册使用的
	3、plugins---makeSign.js   生成签名方法，请求接口方法集成在一起
	4、views.admin---log.vue   log日志功能模块，用于统计管理员的操作记录以及访问记录
	5、views.admin---menu.vue   菜单管理功能模块，菜单添加编辑删除，排序修改权限菜单设置等
	6、views.admin---role.vue   权限管理模块，添加、修改、删除权限角色。权限细化到按钮控制
	7、views.admin---user.vue   管理员模块，添加、修改、删除管理员。管理员可设置权限角色、头像、昵称、账号、密码
	
功能介绍
================
###
	1、管理员登录
	2、管理员修改自己登录密码、基本信息
	3、管理员可退出登录、清除登录信息
	4、显示菜单为动态获取、同时控制不可访问自己权限之外的菜单，如果通过URL访问就会报404错误
	5、菜单管理分为二级菜单以及操作类菜单，操作菜单是控制当前页面的按钮权限，二级菜单是二级显示，二级菜单也有操作类菜单，模式跟以及菜单是一致的
	6、权限管理的可配置多权限角色，所有的菜单（一级菜单、二级菜单、操作菜单）权限都可编辑控制。目前只做了前端的按钮控制并未做后端的强路由限制
	7、管理员管理管理所有的管理员的信息（账号、密码、权限角色、头像、昵称），可编辑删除
	8、在blog---edit.vue文件里面写了符文本编辑、高德地图的demo。需要集成的可以自己去集成
	9、所有的接口都有签名验证规则，保证数据不被篡改
	
服务端地址
===============
[iview-admin-lumen](https://github.com/SortableJS/Vue.Draggable)
	
