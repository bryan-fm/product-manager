import { mount } from '@vue/test-utils';
import ProductForm from './ProductForm.vue';

describe('ProductForm (Jest)', () => {
    const createForm = (overrides = {}) => ({
        name: 'Produto A',
        description: 'Descrição',
        price: 12.5,
        stock: 5,
        errors: {},
        processing: false,
        ...overrides,
    });

    it('renders initial values and submits the form', async () => {
        const form = createForm();
        const submitMock = jest.fn();

        const wrapper = mount(ProductForm, {
            props: {
                form: form,
                submit: submitMock,
                buttonText: 'Salvar',
            },
        });
        const nameInput = wrapper.find('input[placeholder="Nome"]');
        const priceInput = wrapper.find('input[type="number"][step="0.01"]');

        expect(nameInput.element.value).toBe('Produto A');

        // 2. Simular interação do usuário
        await nameInput.setValue('Produto Alterado');

        // 3. Simular o envio do formulário
        await wrapper.find('form').trigger('submit.prevent');

        // 4. Verificar se a função submit passada via prop foi chamada
        expect(submitMock).toHaveBeenCalled();
    });

    it('shows validation errors passed via props', () => {
        const formWithErrors = createForm({
            errors: {
                name: 'O nome é obrigatório',
                price: 'Preço inválido',
            },
        });

        const wrapper = mount(ProductForm, {
            props: {
                form: formWithErrors,
                buttonText: 'Salvar',
            },
        });

        expect(wrapper.text()).toContain('O nome é obrigatório');
        expect(wrapper.text()).toContain('Preço inválido');
    });

    it('disables fields when mode is view', () => {
        const wrapper = mount(ProductForm, {
            props: {
                form: createForm(),
                mode: 'view',
                buttonText: 'Salvar',
            },
        });

        const nameInput = wrapper.find('input[placeholder="Nome"]');
        expect(nameInput.attributes()).toHaveProperty('disabled');
    });
});
