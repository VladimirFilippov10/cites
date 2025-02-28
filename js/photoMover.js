function movePhotoUp(button) {
    var photoItem = button.parentElement;
    var previousPhotoItem = photoItem.previousElementSibling;
    if (previousPhotoItem) {
        photoItem.parentElement.insertBefore(photoItem, previousPhotoItem);
        updatePhotoNames();
        updatePhotoFileNames();
        savePhotoFileNames();
    }
}

function movePhotoDown(button) {
    var photoItem = button.parentElement;
    var nextPhotoItem = photoItem.nextElementSibling;
    if (nextPhotoItem) {
        photoItem.parentElement.insertBefore(nextPhotoItem, photoItem);
        updatePhotoNames();
        updatePhotoFileNames();
        savePhotoFileNames();
    }
}

function updatePhotoNames() {
    var photoItems = document.querySelectorAll('.photo-item');
    photoItems.forEach((item, index) => {
        var img = item.querySelector('img');
        img.alt = 'Фото ' + (index + 1); // Обновляем название изображения
    });
}

function updatePhotoFileNames() {
    var photoItems = document.querySelectorAll('.photo-item');
    photoItems.forEach((item, index) => {
        var img = item.querySelector('img');
        var currentFileName = img.src.split('/').pop(); // Получаем текущее имя файла
        var newFileName = currentFileName.replace(/(\\d+)_\\d+/, '$1_' + (index + 1)); // Обновляем имя файла
        img.src = img.src.replace(currentFileName, newFileName); // Обновляем путь к изображению
    });
}

function savePhotoFileNames() {
    var photoItems = document.querySelectorAll('.photo-item');
    var fileNames = Array.from(photoItems).map((item, index) => {
        var img = item.querySelector('img');
        return img.src.split('/').pop(); // Получаем текущее имя файла
    });

    // Отправка AJAX-запроса для сохранения новых имен файлов на сервере
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/savePhotoFileNames.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Имена файлов успешно сохранены.');
        } else {
            console.error('Ошибка при сохранении имен файлов.');
        }
    };
    xhr.send(JSON.stringify({ fileNames: fileNames }));
}


function savePhotoFileNames() {
    var photoItems = document.querySelectorAll('.photo-item');
    var fileNames = Array.from(photoItems).map((item, index) => {
        var img = item.querySelector('img');
        return img.src.split('/').pop(); // Получаем текущее имя файла
    });

    // Отправка AJAX-запроса для сохранения новых имен файлов на сервере
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/savePhotoFileNames.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Имена файлов успешно сохранены.');
        } else {
            console.error('Ошибка при сохранении имен файлов.');
        }
    };
    xhr.send(JSON.stringify({ fileNames: fileNames }));
}
