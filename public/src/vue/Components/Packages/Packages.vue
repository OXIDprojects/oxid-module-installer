<template>
    <div id="packages">
        <h2>Oxid Packages</h2>

        <div 
            class="package-container oxid-packages"
            v-for="(oxid_package) in packages.oxid"
            :key="oxid_package.name"
        >
            <h4>{{oxid_package.name}}</h4>
            <p>
                Version: {{oxid_package.version}} 
                <button class="btn btn-info">Edit</button>
                <button class="btn btn-warning">Update</button>
                <button class="btn btn-error">Delete</button>
            </p>
        </div>

        <hr>

        <h2>Other Packages</h2>

        <div 
            class="package-container other-packages"
            v-for="(other_package) in packages.other"
            :key="other_package.name"
        >
            <h4>{{other_package.name}}</h4>
            <p>
                Version: {{other_package.version}}
                <button class="btn btn-info">Edit</button>
                <button class="btn btn-warning">Update</button>
                <button class="btn btn-error">Delete</button>
            </p>
        </div>
    </div>
</template>

<style>
.package-container {
    background: #f9f9f9;
    padding: 10px 20px 1px;
}

.package-container + .package-container {
    margin-top: 20px;
}
</style>

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