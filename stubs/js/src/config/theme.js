/* eslint-disable max-len */
/* eslint-disable i18next/no-literal-string */

/**
 * As configurações do tema do Material UI.
 */
export default {
    /**
     * Sobreescreva o tema padrão do material UI aqui.
     *
     * Crie variantes de componentes, personalize as fontes e muito mais.
     *
     * Https://mui.com/material-ui/customization/default-theme/
     * https://mui.com/material-ui/customization/theme-components/.
     *
     * Https://m2.material.io/inline-tools/color/
     * https://zenoo.github.io/mui-theme-creator/.
     */

    components: {
        MuiPaper: {
            styleOverrides: {
                root: {
                    boxShadow: '0px 2px 1px -1px rgba(0,0,0,0.2), 0px 1px 1px 0px rgba(0,0,0,0.14), 0px 1px 3px 0px rgba(0,0,0,0.12)',
                    //
                },
            },
            variants: [
                {
                    props: { variant: 'table' },
                    style: {
                        borderRadius: 8,
                        //
                    },
                },
            ],
        },
        MuiSelect: {
            styleOverrides: {
                root: {
                    '&.rowsPerPage': {
                        width: 66,
                        height: 32,
                        marginLeft: 8,
                    },
                },
            },
        },
        MuiMenuItem: { styleOverrides: { root: { '&.active': { color: '#EB4432' } } } },
        MuiListItemButton: { styleOverrides: { root: { '&.active': { color: '#EB4432' } } } },
    },

    palette: {
        primary: { main: '#EB4432' },
        text: { primary: '#565454' },
        mode: 'light',
        secondary: { main: '#bd4910' },
        error: { main: '#eb4432' },
        warning: { main: '#f49d37' },
        info: { main: '#8338ec' },
    },
};
