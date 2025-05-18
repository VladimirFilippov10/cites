$(document).ready(function() {
    // Добавление нового фото
    $('#addPhoto').click(function() {
        $('#photoContainer').append('\
            <div class="photo-item mb-4">\
                <input type="file" name="car_photos[]" class="mb-2" required>\
                <button type="button" class="delete-photo bg-red-500 text-white p-1 rounded">Удалить</button>\
            </div>\
        ');
    });

    // Удаление фото
    $(document).on('click', '.delete-photo', function() {
        $(this).closest('.photo-item').remove();
    });
});
