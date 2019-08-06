<template>
    <div id="packages">
        PACKAGES
    </div>
</template>

<script>
export default {
    data() {
        return {

        };
    },
    methods: {
        fetchPackages() {
            this.$http.get('/oxid/moduleinstaller/packages/')
                .then( response => {
                    console.log(response)
                })
        }
    },
    created() {
        this.fetchPackages();
    }
}
</script>