(() => {
    'use strict';

    const register = () => {
        window.Alpine.data('keyCombinationInput', ({ state, isDisabled }) => ({
            state,
            listening: false,
            isDisabled: Boolean(isDisabled),

            get displayValue() {
                if (!this.state) {
                    return '';
                }

                return String(this.state)
                    .toUpperCase()
                    .split('+')
                    .join(' + ');
            },

            normalizeKey(key) {
                if (!key) {
                    return '';
                }

                const lower = String(key).toLowerCase();

                if (lower === ' ') {
                    return 'space';
                }

                if (lower === 'esc') {
                    return 'escape';
                }

                return lower;
            },

            capture(event) {
                if (this.isDisabled) {
                    return;
                }

                event.preventDefault();
                event.stopPropagation();

                const parts = [];

                if (event.ctrlKey || event.metaKey) {
                    parts.push('ctrl');
                }

                if (event.shiftKey) {
                    parts.push('shift');
                }

                if (event.altKey) {
                    parts.push('alt');
                }

                const key = this.normalizeKey(event.key);

                if (['control', 'shift', 'alt', 'meta'].includes(key)) {
                    return;
                }

                parts.push(key);
                this.state = parts.join('+');
            },

            focusInput() {
                this.$refs.input?.focus();
            },
        }));
    };

    if (window.Alpine) {
        register();
    }

    document.addEventListener('alpine:init', register);
})();
