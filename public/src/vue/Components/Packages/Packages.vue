<template>
    <div id="packages">
        <h2>Oxid Packages</h2>

        <div 
            class="oxid-packages"
            v-for="(oxid_package,index) in packages.oxid"
            :key="index"
        >
            <h3>{{oxid_package.name}}</h3>
            <p>Version: {{oxid_package.version}}</p>
        </div>

        <h2>Other Packages</h2>

        <div 
            class="oxid-packages"
            v-for="(other_package,other) in packages.other"
            :key="other"
        >
            <h3>{{other_package.name}}</h3>
            <p>Version: {{other_package.version}}</p>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            packages: {oxid:null,other:null}
        };
    },
    methods: {
        fetchPackages() {
            this.$http.get('/oxid/moduleinstaller/packages/')
                .then( response => {
                    this.packages = response.body.packages
                })
        }
    },
    created() {
        this.fetchPackages();
    }
}
</script>