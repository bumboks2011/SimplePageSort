<style>
    .pointed {
        cursor: pointer;
    }

    .block-20 {
        overflow: hidden;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        height: 275px;
        position: relative;
        display: block;
    }

    /*UI toast*/
    .toastify {
        display: flex;
        flex-direction: column-reverse;
        align-items: flex-end;
        position: fixed;
        right: 15px;
        bottom: 0;
        width: auto;
        height: auto;
        z-index: 9999;
    }

    .hiden {
        display: none !important;
    }

    .slide-fade-enter-active {
        transition: all .3s ease;
    }
    .slide-fade-leave-active {
        transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to {
        transform: translateX(10px);
        opacity: 0;
    }

</style>
<template>
    <div class="container">
        <div class="btn-group" aria-label="Basic example">
            <div class="pr-2 pb-2" v-on:click="sortDirection = !sortDirection; changeAttrs()">
                <button type="button" class="btn btn-outline-secondary"  v-if="sortDirection"><i class="fas fa-chevron-up"></i></button>
                <button type="button" class="btn btn-outline-secondary" v-else><i class="fas fa-chevron-down"></i></button>
            </div>
            <div class="pr-2 pb-2">
                <div v-on:click="sortName = !sortName; changeAttrs()">
                    <button type="button" class="btn btn-outline-secondary"  v-if="sortName"><i class="fas fa-font"></i></button>
                    <button type="button" class="btn btn-outline-secondary" v-else><i class="fas fa-dollar-sign"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="row col-sm-10">
                <div v-for="(item, index) in products" class="card col-md-6 col-lg-4 rounded-0">
                    <a class="block-20" v-bind:style="'background-image: url(https://sorty.ga/pic/' + item.picture + ')'"></a>
                    <div class="card-body">
                        <span class="font-weight-bold">{{ item.data.vendor + ' ' + item.name}}</span> <br> {{ item.price}} ₽
                    </div>
                </div>
            </div>
            <div class="col-sm-2 border-top">
                <div v-for="(item, index) in attributes">
                    <h4 class="pt-2 text-capitalize">{{index}}</h4>
                    <div v-for="(itemTwo, indexTwo) in item" class="custom-control custom-checkbox">
                        <div v-if="stripedProducts.includes(itemTwo) || includes.includes(index)">
                            <input type="checkbox" class="custom-control-input" v-bind:value="itemTwo" v-model="selected" v-on:click="changeAttrs()" v-bind:id="indexTwo + 'Unchecked'">
                            <label class="custom-control-label" v-bind:for="indexTwo + 'Unchecked'">{{indexTwo}}</label>
                        </div>
                        <div v-else>
                            <input type="checkbox" class="custom-control-input" v-bind:value="itemTwo" v-model="selected" v-on:click="changeAttrs()" v-bind:id="indexTwo + 'Unchecked'" disabled>
                            <label class="custom-control-label" v-bind:for="indexTwo + 'Unchecked'">{{indexTwo}}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <transition name="slide-fade">
            <div class="toastify" v-if="successTify">
                <div class="alert alert-success">
                    <i class="far fa-check-circle fa-4x"></i>
                </div>
            </div>
        </transition>
        <transition name="slide-fade">
            <div class="toastify" v-if="alertTify">
                <div class="alert alert-danger">
                    <i class="fas fa-times fa-4x"></i>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        /*components: {},*/
        props: [

        ],
        data: function () {
            return {
                products: [],
                attributes: [],
                selected: [],
                sortName: true,
                sortDirection: true,
                stripedProducts: [],
                includes: ['vendor'],

                /*UI toast*/
                successTify: false,
                alertTify: false,
            }
        },
        methods:{
            //UI
            alertNotify(type = true, delay = 1500){
                if (type) {
                    this.successTify = true;
                    setTimeout(() => {this.successTify = false},delay);
                } else {
                    this.alertTify = false;
                    setTimeout(() => {this.alertTify = false},delay);
                }
            },
            getAttributes(){
                axios
                    .get('api/attribute')
                    .then(response => {
                        this.attributes = response.data;
                        setTimeout(this.getSearch, 10);
                    })
                    .catch(error => console.log(error));
            },
            changeAttrs(){
                setTimeout(this.getSearch, 10)
            },
            getSearch(){
                let sorted = {};
                let atro = this.attributes;
                let sortName = this.sortName === true ? 'name' : 'price';
                let sortDirection = this.sortDirection === true ? 'asc' : 'desc';
                this.selected.forEach(function(item, i, arr) {
                    for (let key in atro) {
                        if (!sorted.hasOwnProperty(key)) {
                            sorted[key] = [];
                        }
                        for (let keyTwo in atro[key]) {
                            if (keyTwo === item) {
                                sorted[key][sorted[key].length] = item;
                            }
                        }
                    }
                });
                axios
                    .get('api/search', {params: {
                            options: sorted,
                            sort: [sortName,sortDirection]
                        }})
                    .then(response => {
                        this.products = response.data;
                        setTimeout(this.stripeProducts, 10);
                    })
                    .catch(error => console.log(error));
            },
            stripeProducts() {
                let products = this.products;
                let stripeProd = [];
                this.products.forEach(function(item, i, arr) {
                    for (let key in products[i]['data']) {
                        stripeProd = stripeProd.concat(products[i]['data'][key]);
                    }
                });
                this.stripedProducts = stripeProd;
            }
            /*,
            inProducts(index,name){
                if (this.mounted === false) {
                    return true;
                }
                this.products.forEach(function(item, i, arr) {
                    if (this.products[i]['data'][index] === name) {
                        return true;
                    }
                });
                return false;
            },
            onMounted() {
                this.notMounted = false;
            }*/
        },
        mounted(){
            this.getAttributes();
        },
    }
</script>
