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
        $(this).closest('.complectation-item').remove();
    });
});