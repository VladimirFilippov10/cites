document.addEventListener('DOMContentLoaded', function () {
    const images = document.querySelectorAll('#main-image ~ img');
    const thumbnails = document.querySelectorAll('.thumbnail img');
    let currentIndex = 0;

    function showImage(index) {
        images.forEach((img, i) => {
            img.classList.toggle('hidden', i !== index);
        });
    }

    document.getElementById('prev').addEventListener('click', function () {
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
        showImage(currentIndex);
    });

    document.getElementById('next').addEventListener('click', function () {
        currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
        showImage(currentIndex);
    });

    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('click', function () {
            currentIndex = index;
            showImage(currentIndex);
        });
    });

    showImage(currentIndex);
});
