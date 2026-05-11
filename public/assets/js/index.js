export function modal() {
    const openModal = document.getElementById("open-modal");
    const closeModal = document.getElementById("close-modal");
    const modal = document.getElementById("vault-item");
    const backdrop = document.getElementById("modal-backdrop");

    if (openModal && close && modal && backdrop) {
        openModal.addEventListener("click", () => {
            modal.classList.remove("hidden");
            backdrop.classList.remove("hidden");
        });

        closeModal.addEventListener("click", () => {
            modal.classList.add("hidden");
            backdrop.classList.add("hidden");
        });

        backdrop.addEventListener("click", () => {
            modal.classList.add("hidden");
            backdrop.classList.add("hidden");
        });
    }
}

export function changeEntry() {
    document.addEventListener("click", (e) => {
        if (e.target.closest("#close-edit-modal")) {
            document.getElementById("pass-modal").classList.add("hidden");
            return;
        }

        if (e.target.closest("button")) return;

        const card = e.target.closest(".entryCard");
        if (!card) return;

        document.getElementById("edit-entry-id").value = card.dataset.id;
        document.getElementById("edit-site-name").value = card.dataset.site;
        document.getElementById("edit-username").value = card.dataset.username;
        document.getElementById("edit-password").value = card.dataset.password;
        document.getElementById('edit-category').value = card.dataset.category;
        document.getElementById("pass-modal").classList.remove("hidden");
    });
}

export function generatePass() {
    const buttons = document.querySelectorAll(".generatePass");

    buttons.forEach((button) => {
        button.addEventListener("click", () => {
            const input = button.previousElementSibling;

            const length = 16;
            const chars =
                "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
            const array = new Uint32Array(length);

            crypto.getRandomValues(array);

            let result = "";
            for (let i = 0; i < length; i++) {
                const index = array[i] % chars.length;
                result += chars[index];
            }

            input.value = result;
        });
    });
}

export function search() {
    const searchInput = document.getElementById("search");
    const cards = document.querySelectorAll(".entryCard");

    searchInput.addEventListener("input", () => {
        const query = searchInput.value.toLowerCase();

        cards.forEach((card) => {
            const site = card.dataset.site.toLowerCase();
            const username = card.dataset.username.toLowerCase();

            if (site.includes(query) || username.includes(query)) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });
    });
}

export function categoryFilter() {
    const buttons = document.querySelectorAll(".categoryBtn");
    if (!buttons.length) return;

    buttons.forEach((button) => {
        button.addEventListener("click", () => {
            buttons.forEach((btn) => {
                btn.classList.remove("bg-text-body", "text-white");
                btn.classList.add("text-gray-400");
            });
            button.classList.add("bg-text-body", "text-white");
            button.classList.remove("text-gray-400");

            const selected = button.dataset.category;
            document.querySelectorAll(".entryCard").forEach((card) => {
                if (selected === "all" || card.dataset.category === selected) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        });
    });
}


export function openModalWhenError() {
    const error = document.getElementById('validation-error');
    if (!error) return;

    if (error.dataset.form === 'new') {

        document.getElementById('site_name_new_entry').value = error.dataset.site;
        document.getElementById('generated-username').value = error.dataset.username;
        document.getElementById('generated-password').value = error.dataset.password;
        document.getElementById('new-category').value = error.dataset.category;

        document.getElementById('vault-item').classList.remove('hidden');
        document.getElementById('modal-backdrop').classList.remove('hidden');
    } else {
        document.getElementById('edit-entry-id').value = error.dataset.entryId;
        document.getElementById('edit-site-name').value = error.dataset.site;
        document.getElementById('edit-username').value = error.dataset.username;
        document.getElementById('edit-password').value = error.dataset.password;
        document.getElementById('edit-category').value = error.dataset.category;

        document.getElementById('pass-modal').classList.remove('hidden');
    }
}