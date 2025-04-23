const alertWindow = document.getElementById('error_alert');

if (alertWindow) {
    setTimeout(() => {
        alertWindow.classList.add('closed');
    }, 8000);
}