import React from 'react';

import Typography from '@mui/material/Typography';

import { addFilter, t } from '@arandu/laravel-mui-admin';

export default () => {
    /** ROLE. */
    addFilter('repository_index_item_data', (value, item, column) => {
        if (item.className === 'role' && column.key === 'abilities') {
            const models = Object.keys(value);
            const temp = {};

            models.forEach((model) => {
                temp[model] = [];

                Object.entries(value[model]).forEach(([ability, val]) => {
                    if (val) {
                        temp[model].push(t(`abilities.${ability}`));
                    }
                });

                if (temp[model].length === Object.keys(value[model]).length) {
                    temp[model] = [t('common.all')];
                }
                if (temp[model].length <= 0) {
                    temp[model] = [t('common.none')];
                }
            });

            return Object.entries(temp).map(([model, abilities]) => (
                <Typography key={model}>
                    <em>{t(`models.${model}.singular`)}: </em>
                    {abilities.map((ability) => `${ability}`).join(', ')}
                </Typography>
            ));
        }

        return value;
    });
};
