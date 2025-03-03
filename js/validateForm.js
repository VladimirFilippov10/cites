function validateForm() {
    // Проверка, что все обязательные поля заполнены
    const requiredFields = ['title', 'year', 'bodywork', 'generation', 'mileage', 'color', 'owners', 'engine_volume', 'power', 'transmission', 'car_drive', 'fuel_type', 'equipment_text'];
    for (let field of requiredFields) {
        if (!document.getElementById(field).value) {
            alert('Пожалуйста, заполните все обязательные поля.');
            return false;
        }
    }
    return true;
}

// Обработка отправки формы
$('form').submit(function(event) {
    event.preventDefault(); // Предотвращаем стандартное поведение формы
    if (validateForm()) {
        this.submit(); // Отправляем форму, если валидация прошла успешно
    }
});
