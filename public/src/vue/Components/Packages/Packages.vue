<template>
    <div id="packages">
        <h2>Oxid Packages</h2>

        <div 
            class="oxid-packages"
            v-for="(oxid_package) in packages.oxid"
            :key="oxid_package.name"
        >
            <h4>{{oxid_package.name}}</h4>
            <p>Version: {{oxid_package.version}}</p>
        </div>

        <hr>

        <h2>Other Packages</h2>

        <div 
            class="other-packages"
            v-for="(other_package) in packages.other"
            :key="other_package.name"
        >
            <h4>{{other_package.name}}</h4>
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