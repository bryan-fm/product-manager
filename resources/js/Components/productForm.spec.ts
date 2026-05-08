import { mount } from '@vue/test-utils';
import ProductForm from './ProductForm.vue';

const makeForm = () => ({
    name: 'Produto A',
    description: 'Descrição',
    price: 12.5,
    stock: 5,
    errors: {},
    processing: false,
});

describe('ProductForm', () => {
    it('renders product name', () => {
        const wrapper = mount(ProductForm, {
            props: {
                form: makeForm(),
                submit: jest.fn(),
                buttonText: 'Salvar',
            },
        });

        const input = wrapper.find('input').element as HTMLInputElement;

        expect(input.value).toBe('Produto A');
    });

    it('renders in view mode with disabled fields', () => {
        const wrapper = mount(ProductForm, {
            props: {
                form: makeForm(),
                submit: jest.fn(),
                mode: 'view',
                buttonText: 'Salvar',
            },
        });

        expect(wrapper.find('button[type="submit"]').exists()).toBe(false);

        const input = wrapper.find('input').element as HTMLInputElement;

        expect(input.disabled).toBe(true);
    });
});