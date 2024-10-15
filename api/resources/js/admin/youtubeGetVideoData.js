document.addEventListener("DOMContentLoaded", function() {

    const originalLinkInput = document.getElementById('original_link');
    const artistNameInput = document.getElementById('artist_name');
    const songTitleInput = document.getElementById('title');
    const iframe = document.getElementById('iframe');

    function getVideoInfo(id) {
        
    }

    originalLinkInput.addEventListener("input", function(e) {
        const link = e.target.value; // Access the value from e.target
        const youTubeIdInputHidden = document.getElementById('youtube_id');
        const thumbnailInputHidden = document.getElementById('thumbnail');
        
        const linkParts = link.split('?v=');
        let vidId;
        if (linkParts.length > 1) {
            vidId = linkParts[1];
        } else {
            vidId = linkParts[0];
        }
        console.log(vidId);

        const iframeSrc = `https://www.youtube.com/embed/${vidId}?si=Z1QJTfr8TPTsrh0f&amp;controls=0`
        iframe.setAttribute('src', iframeSrc);

        const apiUrl = originalLinkInput.dataset.youtubeApi;
        const apiReqUrl = `${apiUrl}?id=${vidId}`;
        console.log(apiReqUrl);
        axios.get(apiReqUrl, {
            // Your data to send (if needed)
          })
          .then(response => {
            const videoData = response.data;
            console.log(videoData);
            artistNameInput.value = videoData.artist_name;
            songTitleInput.value = videoData.song_title;
            youTubeIdInputHidden.value = videoData.id;
            thumbnailInputHidden.value = videoData.thumbnail
          })
          .catch(error => {
            return {
                error: error
            }
          });
    });
    
});