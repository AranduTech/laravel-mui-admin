import React from 'react';

/**
 * Hook que retorna a altura da janela do navegador.
 *
 * @return {number} - A altura da janela do navegador.
 */
export default function useWindowHeight() {
    const [height, setHeight] = React.useState(window.innerHeight);

    React.useEffect(() => {
        /**
         * Sets `height` state to `window.innerHeight`.
         */
        const handleResize = () => {
            setHeight(window.innerHeight);
        };
        window.addEventListener('resize', handleResize);
        return () => {
            window.removeEventListener('resize', handleResize);
        };
    }, []);

    return height;
}
