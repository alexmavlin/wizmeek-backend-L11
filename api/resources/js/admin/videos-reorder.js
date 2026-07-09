document.addEventListener("DOMContentLoaded", function () {
    const list = document.getElementById("videosSortable");

    if (!list || typeof Sortable === "undefined") {
        return;
    }

    const statusEl = document.getElementById("reorderStatus");
    const reorderUrl = list.dataset.reorderUrl;
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

    let statusTimeout = null;

    const setStatus = function (message, state) {
        if (!statusEl) return;
        statusEl.textContent = message;
        statusEl.classList.remove("is-saving", "is-success", "is-error");
        if (state) {
            statusEl.classList.add(state);
        }

        if (statusTimeout) {
            clearTimeout(statusTimeout);
        }
        if (state === "is-success") {
            statusTimeout = setTimeout(function () {
                statusEl.textContent = "";
                statusEl.classList.remove("is-success");
            }, 2500);
        }
    };

    const saveOrder = function () {
        const ids = Array.from(list.querySelectorAll(".artists__list--row"))
            .map((row) => parseInt(row.dataset.videoId, 10))
            .filter((id) => !Number.isNaN(id));

        setStatus("Saving order...", "is-saving");

        axios
            .post(
                reorderUrl,
                { ids: ids },
                { headers: { "X-CSRF-TOKEN": csrfToken } }
            )
            .then(function () {
                setStatus("Order saved.", "is-success");
            })
            .catch(function () {
                setStatus("Failed to save order. Please try again.", "is-error");
            });
    };

    Sortable.create(list, {
        handle: ".drag-handle",
        animation: 150,
        ghostClass: "sortable-ghost",
        chosenClass: "sortable-chosen",
        onEnd: saveOrder,
    });
});
