<style scoped>
    .index {
        width: 100%;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        text-align: center;
    }

    .index h1 {
        height: 150px;
    }

    .index h1 img {
        height: 100%;
    }

    .index h2 {
        color: #666;
        margin-bottom: 200px;
    }

    .index h2 p {
        margin: 0 0 50px;
    }

    .index .ivu-row-flex {
        height: 100%;
    }
</style>
<template>
    <div class="index">
        <Row type="flex" justify="center" align="middle">
            <Col span="24">
            <h1>
                <img src="https://raw.githubusercontent.com/iview/iview/master/assets/logo.png">
            </h1>
            <h2>
                <p>Welcome to your iView app!</p>
                <Button type="ghost" @click="handleStart">Start iView</Button>
                <router-link to="/register">
                    <Button type="ghost">Register</Button>
                </router-link>
                <router-link to="/login">
                    <Button type="ghost">Login</Button>
                </router-link>
                <router-link to="/loading">
                    <Button type="ghost">Loading</Button>
                </router-link>
                <router-link to="/home">
                    <Button type="ghost">Home</Button>
                </router-link>
                <router-link to="/home/blog/edit">
                    <Button type="ghost">edit</Button>
                </router-link>
            </h2>
            </Col>
        </Row>
    </div>
</template>
<script>
    export default {
        created(){
            /*var storage = window.localStorage;
            if( storage.getItem('loginInfo') == null ) {
                storage.setItem('baidu', JSON.stringify(obj));//对象转字符串
            }*/
        },
        methods: {
            handleStart() {
                this.$Modal.info({
                    title: 'Bravo',
                    content: 'Now, enjoy the convenience of iView.'
                });
            },
            register2(){
                this.redirect('/register');
            },
            login2(){
                router.redirect('/login');
            },
            loading2(){
                router.redirect('/register');
            },
            home2(){
                router.redirect('/register');
            },
        }
    };
</script>