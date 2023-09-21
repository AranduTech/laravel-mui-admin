// eslint-disable-next-line import/named
import { configureStore, CombinedState } from '@reduxjs/toolkit';

import rootReducer from './reducers';

import { createLogger } from 'redux-logger';

const loggerMiddleware = createLogger({ /* ...options */ });

/**
 * Classe que representa o estado da aplicação.
 * Utiliza o Redux para gerenciar o estado global.
 */
class State {

    #store;

    /**
     * Criar um novo estado.
     *
     * @param {object} preloadedState - Estado pré-carregado.
     */
    constructor(preloadedState) {
        this.#store = configureStore({
            reducer: rootReducer,
            middleware: (getDefaultMiddleware) => getDefaultMiddleware().concat(loggerMiddleware),
            preloadedState,
        });
    }

    /**
     * Obter o redux store.
     *
     * @return {CombinedState} - O redux store.
     */
    get store() {
        return this.#store;
    }

    dispatch = (action) => this.#store.dispatch(action);

}

const state = new State(/* preloadedState */);

export default state;
