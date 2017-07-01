<style>
    .amap-demo {
        height: 300px;
    }

    .search-box {
        position: absolute;
        top: 15px;
        left: 10px;
        opacity: 0.85;

    }

    .amap-page-container {
        position: relative;
        width: 500px;
    }
</style>
<template>
    <Modal v-model="Amap.modal" title="选择地标" width="500" ok-text="保存" cancel-text="返回"
           @on-ok="saveLngLat">
        <div class="amap-page-container">
            <el-amap-search-box class="search-box" :search-option="Amap.searchOption" :on-search-result="onSearchResult"></el-amap-search-box>
            <el-amap vid="amapDemo" :center="Amap.center" zoom="12" class="amap-demo" :events="Amap.events">
                <el-amap-marker v-for="marker in Amap.markers" :position="marker"></el-amap-marker>
            </el-amap>
        </div>
    </Modal>
</template>
<script>
    export default {
        data(){
            let self = this;
            return {
                Amap:{
                    modal:false,
                    markers: [],
                    center:[121.59996, 31.197646],
                    searchOption: {
                        city: '',
                        citylimit: false
                    },
                    events: {
                        click(e) {
                            let { lng, lat } = e.lnglat;
                            self.Amap.markers = [];
                            self.Amap.markers.push([lng,lat]);
                        }
                    },
                    lng:'',
                    lat:'',

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
            },
            saveLngLat(){
                if(this.Amap.markers){
                    this.Amap.lng = this.Amap.markers[0][0];
                    this.Amap.lat = this.Amap.markers[0][1];
                }
            },
        }
    }
</script>