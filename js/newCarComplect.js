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
        const complectationId = complectationItem.data('id'); // Предполагается, что ID элемента хранится в data-id

        if (complectationId) {
            $.ajax({
                type: 'POST',
                url: 'php/deleteComplectation.php',
                data: { complectationId: complectationId },
                success: function(response) {
                    alert(response);
                    complectationItem.remove(); // Удаляем элемент из интерфейса
                },
                error: function() {
                    alert('Ошибка при удалении элемента комплектации.');
                }
            });
        } else {
            complectationItem.remove(); // Удаляем элемент из интерфейса, если ID не задан
        }
    });

    // Обработка отправки формы
    $('form').on('submit', function(e) {
        e.preventDefault(); // Предотвращаем стандартное поведение формы

        const formData = $(this).serialize(); // Сериализуем данные формы

        $.ajax({
            type: 'POST',
            url: 'php/update.php',
            data: formData,
            success: function(response) {
                alert('Данные успешно сохранены!');
                window.location.href = 'viewAllCars.php'; // Перенаправление на страницу со списком автомобилей
            },
            error: function() {
                alert('Ошибка при сохранении данных.');
            }
        });
    });

});
