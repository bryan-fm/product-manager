import { mount } from '@vue/test-utils';
import ConfirmationModal from './ConfirmationModal.vue';

describe('MyComponent.vue', () => {
    it('renders props.msg when passed', () => {
        const msg = 'new message';
        const wrapper = mount(ConfirmationModal, {
            props: {
                show: true,
                title: msg,
                confirmText: 'OK',
                cancelText: 'Cancel',
                variant: 'info',
            },
        });
        expect(wrapper.text()).toMatch(msg);
    });
});
