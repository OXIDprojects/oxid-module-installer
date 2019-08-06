<template>
    <div class="container">
        <app-header></app-header>
        <app-search></app-search>
        <hr>
        <app-packages></app-packages>
        <app-footer></app-footer>
    </div>
</template>

<script>
export default {
    computed: {
        user () {
            return this.$store.state.authentication.user;
        },
        users () {
            return this.$store.state.users.all;
        }
    },
    created () {
        this.$store.dispatch('users/getAll');
    }
};
</script>