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
import { authHeader } from '../../_helpers';
export default {
    data() {
        return {
            packages: {oxid:null,other:null}
        };
    },
    methods: {
        logout() {
            localStorage.removeItem('user');
        },
        fetchPackages() {
            
            const requestOptions = {
                method: 'GET',
                headers: authHeader()
            };

            var that = this;

            this.$http.get(((location.href.indexOf('oxid.phar.php') !== -1 ? '/oxid.phar.php' : '')) + '/oxid/moduleinstaller/packages/', requestOptions)
                .then( response => {
                    this.packages = response.body.packages
                }).catch(() => {
                    that.logout();
                    location.reload(true);
                })
        }
    },
    created() {
        this.fetchPackages();
    }
}
</script>