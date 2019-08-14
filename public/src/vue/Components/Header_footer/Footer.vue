<template>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col">Copyright hinweise...</div>
                <div class="col-lg-4 action">
                    <button @click="updateSelected" v-if="hasStack" class="btn btn-warning">Pakete aktualisieren</button>
                    <button @click="updateAll" class="btn btn-warning">Alles aktualisieren</button>
                </div>
            </div>
        </div>
    </footer>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
export default {
    computed: {
        ...mapGetters('packages', [
            'hasStack'
        ])
    },
    methods: {
        ...mapActions('packages', [
            'updateAll',
            'updateSelected'
        ])
    }
}
</script>

<style>
    footer {
        left: 0;
        right: 0;
        bottom: 0;
        padding: 20px;
        color: #fff;
        position: fixed;
        background: #607D8B;
    }
</style>
