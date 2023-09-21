const INITIAL_STATE = { example: null };

export default (state = INITIAL_STATE, action = {}) => {
    switch (action.type) {
        case 'EXAMPLE':
            return {
                ...state,
                example: action.payload,
            };
        default:
            return state;
    }
};

