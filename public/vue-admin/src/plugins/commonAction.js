/**
 * Created by Mr.Zhou on 17/6/14.
 */

exports.install = function (Vue, options) {

    // 获取权限角色的数据
    Vue.prototype.roleIn = function (roleName){
        if($.inArray(roleName,this.$store.state.roleOperateArr[this.$route.path]) > -1){
            return true;
        }else{
            return false;
        }
    };

    //获取管理员个人信息
    Vue.prototype.adminInfoData = function (){
        var resultData = [];
        this.postData(serverUlr + apiUrl.adminMyInfo, {},
            function (result) {
                alert(1);
                resultData = result;
            }, function (result) {
                resultData = result;
            }, function () {

            }
        );
        return resultData;
    };
};