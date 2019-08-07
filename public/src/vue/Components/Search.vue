<template>
    <div id="search">
        <input class="form-control" type="text" name="search" v-model.lazy="formdata.term">
        <button @click.prevent="search">Suchen</button>
        
        <div class="result-packages" v-if="Object.keys(packages).length">
            <div v-for="(composer_package) in packages" :key="composer_package.name" @click="addPackage">
                <span>{{composer_package.name}}</span><i>Downloads {{composer_package.downloads}}</i>
            </div>
        </div>
    </div>
</template>

<style>
    span {
        width: 300px;
        display: inline-block;
    }
    
    .result-packages > div:hover {
        background: #ccc
    }
    .result-packages > div {
        cursor: pointer;
        padding: 5px 20px;
    }
    .result-packages > div + div {
        border-top: 1px solid #444;
    }
</style>
<script>
export default {
    data() {
        return {
            formdata: {
                term: ''
            },
            packages: {}
        }
    },
    methods: {
        search() {
            this.$http.post(((location.href.indexOf('oxid.phar.php') !== -1 ? '/oxid.phar.php' : '')) + '/oxid/moduleinstaller/packages/' + this.formdata.term + '/')
                .then( response => {
                    this.packages = response.body.packages
                })
        },
        addPackage() {
            this.packages = {}
        }
    }
}
</script>