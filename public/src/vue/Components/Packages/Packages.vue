<template>
    <div id="packages">
        <h2>Oxid Packages</h2>

        <div 
            class="package-container oxid-packages"
            v-for="(oxid_package) in packages.oxid"
            :key="oxid_package.name"
        >
            <h4>{{oxid_package.name}}</h4>
            <div>
                <div v-if="oxid_package.edit !== undefined && oxid_package.edit">
                    Version: <input type="text" v-model="oxid_package.version">
                    <button @click="edit(oxid_package)" class="btn btn-info">Edit</button>
                    <button @click="remove(oxid_package)" class="btn btn-error">Delete</button>
                </div>
                <div v-else>
                    Version: {{oxid_package.version}}
                    <button @click="edit(oxid_package)" class="btn btn-info">Edit</button>
                    <button @click="remove(oxid_package)" class="btn btn-error">Delete</button>
                </div>
            </div>
        </div>

        <hr>

        <h2>Other Packages</h2>

        <div 
            class="package-container other-packages"
            v-for="(other_package) in packages.other"
            :key="other_package.name"
        >
            <h4>{{other_package.name}}</h4>
            <div>
                <div v-if="other_package.edit !== undefined && other_package.edit">
                    Version: <input type="text" v-model="other_package.version">
                    <button @click="edit(other_package)" class="btn btn-info">Edit</button>
                    <button @click="remove(other_package)" class="btn btn-error">Delete</button>
                </div>
                <div v-else>
                    Version: {{other_package.version}}
                    <button @click="edit(other_package)" class="btn btn-info">Edit</button>
                    <button @click="remove(other_package)" class="btn btn-error">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.package-container {
    background: #f9f9f9;
    padding: 10px 20px;
}

.package-container + .package-container {
    margin-top: 20px;
}
</style>

<script>
import { authHeader } from "../../_helpers";
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
        },
        edit(composer_package) {
            composer_package.edit = true;
        },
        remove(composer_package) {
            const requestOptions = {
                method: "DELETE",
                headers: authHeader()
            };

            var that = this;

            this.$http
                .delete(
                    (location.href.indexOf("oxid.phar.php") !== -1
                    ? "/oxid.phar.php"
                    : "") + "/oxid/moduleinstaller/packages/",
                    {body: {composer_package: composer_package}, headers: authHeader()},
                    requestOptions
                )
                .then(response => {
                })
                .catch(() => {
                    that.logout();
                    location.reload(true);
                });
        }
    },
    created() {
        this.fetchPackages();
    }
}
</script>