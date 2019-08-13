import { packagesService } from '../_services';

export const packages = {
    namespaced: true,
    state: {
        all: {},
        updateStack: []
    },
    getters: {
        showStack(state) {
            return state.updateStack
        }
    },
    actions: {
        getAll({ commit }) {
            commit('getAllRequest');

            packagesService.getAll()
                .then(
                    packages => commit('getAllSuccess', packages),
                    error => commit('getAllFailure', error)
                );
        },
        addToStack(context, payload) {
            context.commit('addToStack', payload)
        },
        edit(context, payload) {
            context.commit('edit', payload);
        },
        update(context, payload) {
            context.commit('update', payload);
        },
        removeFromStack(state, payload) {
            context.commit('removeFromStack', payload);
        },
    },
    mutations: {
        update(state, {item, type}) {
            for(var i in state.updateStack) {
                if(state.updateStack[i].name === item.name) {
                    state.updateStack[i] = item;
                    return;
                }
            }
            item.type = 'update';
            state.updateStack.push(item);
        },
        edit(state, {item, type}) {
            for(var packageType in state.all) {
                for(var i in state.all[packageType]) {
                    if(state.all[packageType][i].name === item.name) {
                        state.all[packageType][i].type = 'edit';
                        return;
                    }
                }
            }
        },
        addToStack(state, {item, type}) {
            
            for(var i in state.updateStack) {
                if(state.updateStack[i].name === item.name) {
                    state.updateStack[i].type = 'delete';
                    return;
                }
            }
            item.type = type;
            state.updateStack.push(item);
        },
        removeFromStack(state, payload) {
            
            for(var i in state.updateStack) {
                if(state.updateStack[i].name === item.name) {
                    state.updateStack[payload.name] = null;
                    delete state.updateStack[payload.name];
                    return;
                }
            }
        },
        getAllRequest(state) {
            state.all = { loading: true };
        },
        getAllSuccess(state, packages) {
            state.all = packages.packages;
        },
        getAllFailure(state, error) {
            state.all = { error };
        }
    }
}
