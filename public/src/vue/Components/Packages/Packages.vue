<template>
    <div id="packages">

        <h2>Oxid Packages</h2>

        <div 
            class="package-container oxid-packages"
            v-for="(oxid_package) in packages.oxid"
            :key="oxid_package.name"
            v-bind:class="{ delete: (oxid_package.type === 'delete'), update: (oxid_package.type === 'update'), edit: (oxid_package.type === 'edit') }"
        >
            <h4>{{oxid_package.name}}</h4>
            <div>
                <div v-if="oxid_package.type !== undefined && oxid_package.type === 'edit'">
                    Version: <input type="text" v-model="oxid_package.version">
                    <button @click="update({item: oxid_package, type: 'update'})" class="btn btn-info">Update</button>
                    <button @click="addToStack({item: oxid_package, type: 'delete'})" class="btn btn-error">Delete</button>
                </div>
                <div v-else>
                    Version: {{oxid_package.version}}
                    <button @click="edit({item: oxid_package, type: 'edit'})" class="btn btn-info">Edit</button>
                    <button @click="addToStack({item: oxid_package, type: 'delete'})" class="btn btn-error">Delete</button>
                </div>
            </div>
        </div>

        <hr>

        <h2>Other Packages</h2>

        <div 
            class="package-container other-packages"
            v-for="(other_package) in packages.other"
            :key="other_package.name"
            v-bind:class="{ delete: (other_package.type === 'delete'), update: (other_package.type === 'update'), edit: (other_package.type === 'edit') }"
        >
            <h4>{{other_package.name}}</h4>
            <div>
                <div v-if="other_package.type !== undefined && other_package.type === 'edit'">
                    Version: <input type="text" v-model="other_package.version">
                    <button @click="update({item: other_package, type: 'update'})" class="btn btn-info">Update</button>
                    <button @click="addToStack({item: other_package, type: 'delete'})" class="btn btn-error">Delete</button>
                </div>
                <div v-else>
                    Version: {{other_package.version}}
                    <button @click="edit({item: other_package, type: 'update'})" class="btn btn-info">Edit</button>
                    <button @click="addToStack({item: other_package, type: 'delete'})" class="btn btn-error">Delete</button>
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
.delete {
    background: #fcc
}
.update {
    background: #ccf
}
.edit {
    background: #c9c9c9
}
</style>

<script>
import { mapActions } from "vuex";
import { authHeader } from "../../_helpers";

export default {
    data() {
        return {
        };
    },
    methods: {
        logout() {
            localStorage.removeItem('user');
        },
        ...mapActions('packages', [
            'addToStack',
            'edit',
            'update'
        ])
    },
    computed: {
        packages () {
            return this.$store.state.packages.all;
        }
    },
    created() {
        this.$store.dispatch('packages/getAll');
    }
}
</script>