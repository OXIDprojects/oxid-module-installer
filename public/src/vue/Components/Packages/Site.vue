<template>
    <div id="packages-site">

        
        <h4>Packages 2 Update</h4>

        <div 
            class="package-container oxid-packages"
            v-for="(oxid_package) in showStack"
            :key="oxid_package.name"
            v-bind:class="{ delete: (oxid_package.installer.type === 'delete'), update: (oxid_package.installer.type === 'update') }"
        >
            <h4>{{oxid_package.name}}</h4>
            <div>
                Version: {{oxid_package.version}}
            </div>
            <button @click="removeFromStack({item: oxid_package, action: 'reset'})">Zurücksetzen</button>
        </div>
    </div>
</template>

<style>
#packages-site {
    top: 0;
    right: 0;
    bottom: 0;
    width: 400px;
    padding: 10px;
    position: fixed;
    background: #fff;
    border-left: 1px solid #ccc
}
.package-container {
    background: #f9f9f9;
    padding: 10px 20px;
}

.package-container + .package-container {
    margin-top: 20px;
}
.delete {
    background: #fcc
}
.update {
    background: #ccf
}
</style>

<script>
import { mapGetters, mapActions } from "vuex";
import { authHeader } from "../../_helpers";

export default {
    data() {
        return {
        };
    },
    methods: {
        ...mapActions('packages', [
            'removeFromStack'
        ])
    },
    computed: {
        ...mapGetters('packages', [
            'showStack'
        ])
    }
}
</script>