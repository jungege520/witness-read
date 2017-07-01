/**
 * Created by Mr.Zhou on 17/6/14.
 */
exports.install = function (Vue, options) {
    Vue.prototype.postData = function (url,data,successCall,failCall,errorCall){

        var appKey='vue-admin@#!';
        var time = new Date().getTime();
        time = Math.floor(time/1000);
        function getSignStr(obj) {
            var str = "";
            var keys = [];
            for (var key in obj) {
                keys.push(key);
                if (typeof obj[key] == 'string') {
                    obj[key] = obj[key].replace(/\s+/g, "");
                }
            }
            keys.sort();
            $.each(keys, function (index, value) {
                var nn = obj[value];
                if(typeof nn == 'object'){
                    $.each(nn,function (k,v) {
                        str += value + "["+k+"]=" + v + "&";
                    })
                }else{
                    str += value + "=" + nn + "&";
                }
            });
            str += "appKey="+appKey+"&timestamp=" + time;
            return  md5(str).toUpperCase();
        };

        $sign = getSignStr(data);
        var postData = JSON.parse(JSON.stringify(data))
        postData.sign = $sign;
        postData.timestamp = time;

        this.$http({
            method: 'POST',
            url: url,
            params: postData,
            headers: {"Authorization": "Bearer " + window.localStorage.getItem('token')},
        }).then(function (response) {
            //console.log(response.data);
            if (response.data.status_code == 200) {
                successCall(response.data);
            } else {
                failCall(response.data);
            }
        },function (response) {
            errorCall();
        });
    };
};