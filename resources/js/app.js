const logoutDialog = document.getElementById('logout-confirmation');

if (logoutDialog instanceof HTMLDialogElement) {
    let pendingLogoutForm = null;

    document.querySelectorAll('form[data-confirm-logout]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (form.dataset.logoutConfirmed === 'true') {
                delete form.dataset.logoutConfirmed;
                return;
            }

            event.preventDefault();
            pendingLogoutForm = form;
            logoutDialog.showModal();
        });
    });

    logoutDialog.querySelector('[data-logout-cancel]')?.addEventListener('click', () => {
        pendingLogoutForm = null;
        logoutDialog.close();
    });

    logoutDialog.querySelector('[data-logout-confirm]')?.addEventListener('click', () => {
        const form = pendingLogoutForm;
        pendingLogoutForm = null;

        if (!form) {
            return;
        }

        form.dataset.logoutConfirmed = 'true';
        logoutDialog.close();
        form.requestSubmit();
    });

    logoutDialog.addEventListener('close', () => {
        pendingLogoutForm = null;
    });
}
