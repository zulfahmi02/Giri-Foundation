import './bootstrap';

const initializeTeamMemberDialogs = () => {
    const dialogElements = document.querySelectorAll('[data-team-member-dialog]');

    if (dialogElements.length === 0) {
        return;
    }

    const dialogMap = new Map(
        Array.from(dialogElements).map((dialogElement) => [dialogElement.dataset.teamMemberDialog, dialogElement]),
    );

    document.querySelectorAll('[data-team-member-dialog-trigger]').forEach((triggerElement) => {
        if (triggerElement.dataset.teamMemberDialogInitialized === 'true') {
            return;
        }

        triggerElement.dataset.teamMemberDialogInitialized = 'true';

        triggerElement.addEventListener('click', () => {
            dialogMap.get(triggerElement.dataset.teamMemberDialogTrigger)?.showModal();
        });
    });

    dialogElements.forEach((dialogElement) => {
        if (dialogElement.dataset.teamMemberDialogInitialized === 'true') {
            return;
        }

        dialogElement.dataset.teamMemberDialogInitialized = 'true';

        dialogElement.querySelectorAll('[data-team-member-dialog-close]').forEach((closeButton) => {
            closeButton.addEventListener('click', () => {
                dialogElement.close();
            });
        });

        dialogElement.addEventListener('click', (event) => {
            const dialogBounds = dialogElement.getBoundingClientRect();
            const clickedInsideDialog =
                dialogBounds.top <= event.clientY &&
                event.clientY <= dialogBounds.top + dialogBounds.height &&
                dialogBounds.left <= event.clientX &&
                event.clientX <= dialogBounds.left + dialogBounds.width;

            if (! clickedInsideDialog) {
                dialogElement.close();
            }
        });
    });
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeTeamMemberDialogs);
} else {
    initializeTeamMemberDialogs();
}
