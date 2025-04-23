document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.custom-multiselect').forEach(initMultiselect);
});

function initMultiselect(multiselect) {
    const selectedOptionsContainer = multiselect.querySelector('.selected-options');
    const dropdown = multiselect.querySelector('.dropdown');
    const searchInput = multiselect.querySelector('.search-input');
    const optionsList = multiselect.querySelector('.options-list');
    const hiddenSelect = multiselect.querySelector('.hidden-select');
    const selectedValues = new Map();

    // Initial load: check for pre-selected tags
    if (selectedOptionsContainer.children.length !== 0) {
        multiselect.classList.add('focused');
        const tags = selectedOptionsContainer.querySelectorAll('.tag');

        tags.forEach(tag => {
            const { id, label } = tag.dataset;
            selectedValues.set(id, label);
        });
    }

    // Handle open/close
    document.addEventListener('click', (e) => {
        if (multiselect.contains(e.target)) {
            multiselect.classList.add('open');
            multiselect.classList.add('focused');
            searchInput.focus();
        } else {
            multiselect.classList.remove('open');
            if (selectedOptionsContainer.children.length === 0) {
                multiselect.classList.remove('focused');
            }
        }
    });

    // Handle option click
    optionsList.addEventListener('click', (e) => {
        if (e.target.classList.contains('option')) {
            const id = e.target.dataset.id;
            const label = e.target.dataset.label;

            if (!selectedValues.has(id)) {
                selectedValues.set(id, label);
                addToHiddenSelect(id, label);
                renderSelectedOptions();
            }
        }
    });

    // Handle tag removal
    selectedOptionsContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove')) {
            const id = e.target.dataset.id;
            selectedValues.delete(id);
            removeFromHiddenSelect(id);
            renderSelectedOptions();
        }
    });

    // Handle search
    searchInput.addEventListener('input', (e) => {
        filterOptions(e.target.value.toLowerCase());
    });

    function renderSelectedOptions() {
        selectedOptionsContainer.innerHTML = '';
        selectedValues.forEach((label, id) => {
            const tag = document.createElement('div');
            tag.className = 'tag';
            tag.dataset.id = id;
            tag.dataset.label = label;
            tag.innerHTML = `
                ${label}
                <span class="remove" data-id="${id}">&times;</span>
            `;
            selectedOptionsContainer.appendChild(tag);
        });
    }

    function addToHiddenSelect(id, label) {
        const option = document.createElement('option');
        option.value = id;
        option.text = label;
        option.selected = true;
        console.log(hiddenSelect);
        hiddenSelect.appendChild(option);
    }

    function removeFromHiddenSelect(id) {
        const option = hiddenSelect.querySelector(`option[value="${id}"]`);
        if (option) {
            hiddenSelect.removeChild(option);
        }
    }

    function filterOptions(searchTerm) {
        const options = optionsList.querySelectorAll('.option');
        options.forEach((opt) => {
            const label = opt.dataset.label.toLowerCase();
            opt.style.display = label.includes(searchTerm) ? 'block' : 'none';
        });
    }
}
