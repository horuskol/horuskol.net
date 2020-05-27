module.exports = {
    purge: [
        './source/**/*.blade.php',
        './source/**/*.md',
        './source/**/*.html'
    ],
    theme: {
        extend: {
            margin: {
                '-': '-0.25rem'
            },
            maxHeight: {
                '0': '0',
                '40': '40rem'
            }
        }
    },
    variants: {
        borderWidth: ['responsive', 'last'],
        textColor: ['visited', 'hover']
    }
};
