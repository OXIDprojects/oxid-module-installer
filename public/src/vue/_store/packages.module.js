import Vue from 'vue';
import { packagesService } from '../_services';

export const packages = {
    namespaced: true,
    state: {
        all: {},
        updateStack: [],
        restorable: []
    },
    getters: {
        showStack(state) {
            return state.updateStack
        },
        hasStack(state) {
            return state.updateStack.length
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
        removeFromStack(context, payload) {
            context.commit('removeFromStack', payload);
        },
        updateAll(context) {
            packagesService.updateAll()
                // .then(
                //     packages => commit('getAllSuccess', packages),
                //     error => commit('getAllFailure', error)
                // );
        },
        updateSelected(context) {
            if(context.getters.hasStack) {
                packagesService.updateSelected(context.state.updateStack)
                // .then(
                //     packages => commit('getAllSuccess', packages),
                //     error => commit('getAllFailure', error)
                // );
            }
        }
    },
    mutations: {
        update(state, {item, action}) {
            for(var i in state.updateStack) {
                if(state.updateStack[i].name === item.name) {
                    state.updateStack[i] = item;
                    item.installer.type = action;
                    return;
                }
            }
            item.installer.type = action;
            state.updateStack.push(item);
        },
        edit(state, {item, action}) {
            for(var packageType in state.all) {
                for(var i in state.all[packageType]) {
                    if(state.all[packageType][i].name === item.name) {
                        state.restorable[item.name] = JSON.parse(JSON.stringify(state.all[packageType][i]));
                        state.all[packageType][i].installer.type = action;
                    }
                }
            }
            for(var i in state.updateStack) {
                if(state.updateStack[i].name === item.name) {
                    state.updateStack[i].installer.type = action
                }
            }
            item.installer.type = action;
        },
        addToStack(state, {item, action}) {
            for(var i in state.updateStack) {
                if(state.updateStack[i].name === item.name) {
                    state.updateStack[i].installer.type = action
                    return;
                }
            }
            item.installer.type = action;
            state.updateStack.push(item);
        },
        removeFromStack(state, {item, action}) {
            var stack = [];
            for(var i in state.updateStack) {
                if(state.updateStack[i].name === item.name) {
                    state.updateStack.splice(i, 1);
                }
            }
            
            for(var packageType in state.all) {
                for(var i in state.all[packageType]) {
                    if(state.all[packageType][i].name === item.name) {
                        state.all[packageType][i].installer.type = action
                        state.all[packageType][i] = Vue.observable(JSON.parse(JSON.stringify(state.restorable[item.name])));
                        state.all[packageType][i].installer.type = 'reset';
                    }
                }
            }
        },
        getAllRequest(state) {
            state.all = { loading: true };
        },
        getAllSuccess(state, packages) {
            for(var packageType in packages.packages) {
                for(var i in packages.packages[packageType]) {
                    packages.packages[packageType][i].installer = {
                        type: null
                    }
                }
            }
            state.all = packages.packages;
        },
        getAllFailure(state, error) {
            state.all = { error };
        }
    }
}
