import { repositoriesService } from '../_services';

export const repositories = {
    namespaced: true,
    state: {
        all: {},
        state: 0
    },
    actions: {
        getAll({ commit }) {
            commit('getAllRequest');

            repositoriesService.getAll()
                .then(
                    repositories => commit('getAllSuccess', repositories),
                    error => commit('getAllFailure', error)
                );
        }
    },
    mutations: {
        getAllRequest(state) {
            state.all = { loading: true };
        },
        getAllSuccess(state, repositories) {
            state.all = repositories.repositories;
            state.state = repositories.status;
        },
        getAllFailure(state, error) {
            state.all = { error };
        }
    }
}
