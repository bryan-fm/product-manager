const inputFilters = {
    /**
     * Permite apenas números inteiros (0-9)
     */
    onlyNumbers(event: KeyboardEvent): void {
        const charCode = event.key;
        // Se não for um número de 0-9, bloqueia
        if (!/^\d$/.test(charCode)) {
            event.preventDefault();
        }
    },

    /**
     * Permite floats positivos com até 2 casas decimais
     */
    onlyFloat(event: KeyboardEvent): void {
        const char = event.key;
        const target = event.target as HTMLInputElement;
        const currentValue = target.value;
        if (event.ctrlKey || event.altKey || char.length > 1) return;

        if (!/[\d.]/.test(char)) {
            event.preventDefault();
            return;
        }

        if (char === '.' && currentValue.includes('.')) {
            event.preventDefault();
            return;
        }
        if (currentValue.includes('.')) {
            const parts = currentValue.split('.');
            const decimalPart = parts[1];

            if (decimalPart && decimalPart.length >= 2) {
                if (
                    target.selectionStart !== null &&
                    target.selectionStart <= currentValue.indexOf('.')
                ) {
                    return;
                }
                event.preventDefault();
            }
        }
    },
};

export default inputFilters;
