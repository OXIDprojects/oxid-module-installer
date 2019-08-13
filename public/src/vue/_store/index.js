import Vue from 'vue';
import Vuex from 'vuex';

import { alert } from './alert.module';
import { authentication } from './authentication.module';
import { users } from './users.module';
import { packages } from './packages.module';
import { repositories } from './repositories.module';

Vue.use(Vuex);

export const store = new Vuex.Store({
    modules: {
        alert,
        authentication,
        users,
        packages,
        repositories
    }
});
