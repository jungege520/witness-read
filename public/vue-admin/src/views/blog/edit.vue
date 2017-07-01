<style scoped>
    .amap-demo {
        height: 300px;
    }

    .search-box {
        position: absolute;
        top: 15px;
        left: 10px;
        opacity: 0.82;

    }

    .amap-page-container {
        position: relative;
        width: 500px;
    }
</style>
<template>
    <div>
        <Card style="width:532px;background:rgba(247, 247, 247, 0.44); margin: 0 auto;">
            <div class="amap-page-container">
                <el-amap-search-box class="search-box" :search-option="Amap.searchOption"
                                    :on-search-result="onSearchResult"></el-amap-search-box>
                <el-amap vid="amapDemo" :center="Amap.center" zoom="12" class="amap-demo" :events="Amap.events">
                    <el-amap-marker v-for="marker in Amap.markers" :position="marker"></el-amap-marker>
                </el-amap>
            </div>
        </Card>
        <vue-editor v-model="content" style="background-color: #ffffff;width: 500px;"></vue-editor>
    </div>
</template>
<script>
    import {VueEditor} from 'vue2-editor';
    export default {
        components: {
            VueEditor
        },
        created(){
            /*var storage = window.localStorage;
             if( storage.getItem('loginInfo') == null ) {
             storage.setItem('baidu', JSON.stringify(obj));//对象转字符串
             }*/
        },
        mounted(){

        },
        data() {
            let self = this;
            return {
                content: '<h1>Some initial content</h1>',
                Amap: {
                    modal: false,
                    markers: [],
                    center: [121.59996, 31.197646],
                    searchOption: {
                        city: '',
                        citylimit: false
                    },
                    events: {
                        click(e) {
                            let {lng, lat} = e.lnglat;
                            self.Amap.markers = [];
                            self.Amap.markers.push([lng, lat]);
                        }
                    },
                    lng: '',
                    lat: '',

                },
            }
        },
        methods: {
            onSearchResult(pois) {
                let latSum = 0;
                let lngSum = 0;
                if (pois.length > 0) {
                    pois.forEach(poi => {
                        let {lng, lat} = poi;
                        lngSum += lng;
                        latSum += lat;
                        this.Amap.markers.push([poi.lng, poi.lat]);
                    });
                    let center = {
                        lng: lngSum / pois.length,
                        lat: latSum / pois.length
                    };
                    this.Amap.center = [center.lng, center.lat];
                }
            }
        }
    };
</script>