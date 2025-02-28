$(document).ready(function() {
    // Добавление нового элемента комплектации
    $('#addComplectation').click(function() {
        $('#complectationContainer').append(`
            <div class="complectation-item mb-4">
                <input type="text" name="complectation[]" class="w-3/4 p-2 border border-gray-300 rounded mb-2" placeholder="Элемент комплектации" required>
                <button type="button" class="delete-complectation bg-red-500 text-white p-1 rounded">Удалить</button>
            </div>
        `);
    });

    // Удаление элемента комплектации
    $(document).on('click', '.delete-complectation', function() {
        const complectationItem = $(this).closest('.complectation-item');
        complectationItem.remove(); // Удаляем элемент из интерфейса
    });

    // Обработка отправки формы
    $('form').on('submit', function(e) {
        e.preventDefault(); // Предотвращаем стандартное поведение формы

        const formData = new FormData(this); // Используем FormData для отправки данных формы, включая файлы

        console.log('Form data being sent:', formData); // Отладочное сообщение

        $.ajax({
            type: 'POST',
            url: 'php/update.php', // Отправка на update.php для редактирования автомобиля
            data: formData,
            processData: false, // Не обрабатываем данные
            contentType: false, // Не устанавливаем заголовок contentType
            success: function(response) {
                alert('Данные успешно сохранены!');
                console.log('Response from server:', response); // Отладочное сообщение
                window.location.href = 'viewAllCars.php'; // Перенаправление на viewAllCars.php после редактирования
            },
            error: function() {
                alert('Ошибка при сохранении данных.');
            }
        });
    });
});
