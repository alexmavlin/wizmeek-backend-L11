<section class="popup" id="popup">
    <div class="popup__inner">
        <form action="" method="POST">
            @csrf
            @method('post')
            <span>Confirmation required!</span>
            <p>Are you sure You want to delete?</p>
            <p class="description"></p>
            <div class="popup__btn--row">
                <button class="cancelBtn" id="cancelBtn" role="button">Cancel</button>
                <button class="submitBtn" id="submitBtn" role="submit">Submit</button>
            </div>
        </form>
    </div>
</section>