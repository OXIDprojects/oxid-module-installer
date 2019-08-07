<template>
    <div id="repositories">
        <h2>Repositories</h2>

        <div 
            class="repositories"
            v-for="(repository) in repositories"
            :key="repository.url"
        >
            <h4>{{repository.url}}</h4>
            <p>Type: {{repository.type}}</p>
            <div>
                <button class="btn btn-info">Edit</button>
                <button class="btn btn-warning">Update</button>
                <button class="btn btn-error">Delete</button>
            </div>
        </div>

        <button class="btn btn-primary">Add REPO</button>
    </div>
</template>

<script>
import { authHeader } from '../../_helpers';
export default {
    data() {
        return {
            repositories: {}
        };
    },
    methods: {
        logout() {
            localStorage.removeItem('user');
        },
        fetchRepositories() {
            
            const requestOptions = {
                method: 'GET',
                headers: authHeader()
            };

            var that = this;

            this.$http.get(((location.href.indexOf('oxid.phar.php') !== -1 ? '/oxid.phar.php' : '')) + '/oxid/moduleinstaller/repositories/', requestOptions)
                .then( response => {
                    this.repositories = response.body.repositories
                }).catch(() => {
                    that.logout();
                    location.reload(true);
                })
        }
    },
    created() {
        this.fetchRepositories();
    }
}
</script>