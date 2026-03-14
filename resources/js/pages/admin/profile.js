import { initForm } from '../../helpers/form.js';

initForm('#admin-profile-form', {
    method: 'patch',
    onSuccess: () => {
        const currentPassword = document.querySelector('#current_password');
        const password = document.querySelector('#password');
        const passwordConfirmation = document.querySelector('#password_confirmation');

        if (currentPassword) currentPassword.value = '';
        if (password) password.value = '';
        if (passwordConfirmation) passwordConfirmation.value = '';
    },
});
