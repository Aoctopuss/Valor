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
    const buttons = document.querySelectorAll('.categoryBtn');
    if (!buttons.length) return;

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            buttons.forEach(btn => {
                btn.classList.remove('bg-text-body', 'text-white');
                btn.classList.add('text-gray-400');
            });
            button.classList.add('bg-text-body', 'text-white');
            button.classList.remove('text-gray-400');

            const selected = button.dataset.category;
            document.querySelectorAll('.entryCard').forEach(card => {
                if (selected === 'all' || card.dataset.category === selected) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
}

