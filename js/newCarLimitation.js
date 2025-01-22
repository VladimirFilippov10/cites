$(document).ready(function() {
    $('#addLimitation').click(function() {
        $('#limitationContainer').append(`
            <div class="limitation-item mb-4">
                <input type="text" name="limitation[]" class="w-3/4 p-2 border border-gray-300 rounded mb-2" placeholder="Ограничение" required>
                <button type="button" class="delete-limitation bg-red-500 text-white p-1 rounded">Удалить</button>
            </div>
        `);
    });

    $(document).on('click', '.delete-limitation', function() {
        $(this).closest('.limitation-item').remove();
    });
});