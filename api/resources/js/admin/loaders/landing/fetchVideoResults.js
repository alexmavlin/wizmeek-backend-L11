import { URL } from "../../functions/endpoints";

document.addEventListener("DOMContentLoaded", function () {
    const formGroups = document.getElementsByClassName('form__group');

    for (const group of formGroups) {
        const input = group.querySelector('input[type="text"]');
        const loaderWindow = group.querySelector('.form__group--loader');
        const preloader = group.querySelector('#preloader');

        document.addEventListener('click', function(event) {
            // Check if the click is outside the loader window
            if (!loaderWindow.contains(event.target) && !group.contains(event.target)) {
                loaderWindow.classList.remove('active');
            }
        });

        if (!input || !preloader) {
            console.error('Input or preloader not found');
            continue;
        }

        let loaderOpen = false;

        const fetchData = async (query) => {
            loaderOpen = true;
            loaderWindow.classList.add('active');
            preloader.classList.remove('display-none');
            try {
                const response = await fetch(`${URL.HOST}${URL.VIDEO_SEARCH.FOR_LOADER}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ searchString: query }),
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Fetched data:', data);
                buildResultList(data, loaderWindow);
                // Process data as needed
            } catch (error) {
                console.error('Error fetching data:', error);
            } finally {
                // Stop preloader
                preloader.classList.add('display-none');
            }
        };

        const debouncedFetch = debounce((event) => {
            const query = event.target.value.trim();
            if (query.length > 0) {
                if (!loaderOpen) {
                }
                fetchData(query);
            }
        }, 800);

        // Attach debounced keyup listener
        input.addEventListener('keyup', debouncedFetch);
    }

    function debounce(func, delay) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    function buildResultList(data, loaderWindow) {
        const previousUl = loaderWindow.querySelector('ul');
        if (previousUl) {
            previousUl.remove();
        }
        const list = document.createElement('ul');
        
        for (const video of data) {
            const listItem = document.createElement('li');

            const videoThumbnail = document.createElement('img');
            videoThumbnail.setAttribute('src', video.thumbnail);
            videoThumbnail.setAttribute('width', '80px');
            listItem.appendChild(videoThumbnail);

            const songTitle = document.createElement('span');
            songTitle.innerText = `${video.artist} - ${video.title}`;
            listItem.appendChild(songTitle);

            listItem.addEventListener("click", () => {
                const hiddenInput = document.getElementById(loaderWindow.dataset.hiddenInputId);
                const visibleInput = loaderWindow.closest('.form__group').querySelector('input[type="text"]');
                hiddenInput.value = video.id;
                visibleInput.value = `${video.artist} - ${video.title}`;

                loaderWindow.classList.remove('active');
            })

            list.appendChild(listItem);
        }

        loaderWindow.appendChild(list);
    }
});
