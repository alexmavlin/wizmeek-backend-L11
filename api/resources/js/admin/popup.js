document.addEventListener("DOMContentLoaded", function() {
    const popUpWindow = document.getElementById('popup');
    const popUpForm = popUpWindow.querySelector('form');
    const popUpParagraph = popUpForm.querySelector('p');
    const popUpParagraphDescription = popUpForm.querySelector('p.description');

    const cancelBtn = document.getElementById('cancelBtn');
    
    cancelBtn.addEventListener("click", function(e) {
        e.preventDefault();
        popUpWindow.classList.remove('active');
    });

    let popUpBtns = [];

    const deleteBtns = document.getElementsByClassName('deleteBtn');
    const restoreBtns = document.getElementsByClassName('restoreBtn');
    if (deleteBtns.length !== 0) {
        popUpBtns.push(...deleteBtns);
        popUpBtns.push(...restoreBtns);
    }

    for (const btn of popUpBtns) {
        btn.addEventListener("click", function() {
            const popUpFormAction = btn.dataset.formAction; // Use dot notation
            const popUpText = btn.dataset.text; // Use dot notation
            const popUpDescription = btn.dataset.description;

            popUpForm.setAttribute('action', popUpFormAction);
            popUpParagraph.innerText = popUpText;
            popUpParagraphDescription.innerText = popUpDescription;
            popUpWindow.classList.add('active');
        });
    }
});