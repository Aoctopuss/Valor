import {
    modal,
    changeEntry,
    search,
    generatePass,
    categoryFilter,
    openModalWhenError,
} from "./index.js";

function displayPasswords() {
    const buttons = document.querySelectorAll(".passBtn");

    buttons.forEach((button) => {
        let isVisible = false;

        button.addEventListener("click", () => {
            const input = button
                .closest(".passwordContainer")
                .querySelector(".passwordInput");

            if (!input) return;

            if (isVisible === false) {
                input.type = "text";
                button.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
            </svg>`;
                isVisible = true;
            } else {
                input.type = "password";
                button.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>`;
                isVisible = false;
            }
        });
    });
}

function copyPass() {
    document.querySelectorAll(".copyBtn").forEach((button) => {
        button.addEventListener("click", () => {
            const password = button.dataset.password;
            if (!password) return;

            const temp = document.createElement("input");
            temp.value = password;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand("copy");
            document.body.removeChild(temp);

            const notification = document.querySelector(".notification");
            if (!notification) return;
            notification.innerHTML = `
            <div class="bg-black mt-2 w-[120px] h-[40px] border rounded-md top-2 absolute flex items-center font-semibold px-4 text-center shadow-lg">
                <p class="text-white text-sm">Password copied!</p>
            </div>`;

            setTimeout(() => {
                notification.innerHTML = "";
            }, 2000);
        });
    });
}

generatePass();
modal();
changeEntry();
copyPass();
displayPasswords();
search();
categoryFilter();
openModalWhenError();