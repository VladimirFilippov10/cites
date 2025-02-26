import React, { useState } from 'react';

const AddCar = () => {
    const [formData, setFormData] = useState({
        title: '',
        model_id: '',
        brand: '',
        year: '',
        generation: '',
        car_state_number: '',
        car_link_specifications: '',
        car_link_to_report: '',
        bodywork: '',
        for_sale: false,
        price: '',
        mileage: '',
        color: '',
        owners: '',
        engine_volume: '',
        power: '',
        drive: '',
        transmission: '',
        fuel_type: '',
        description: '',
        equipment_text: '',
        car_photos: [],
        complectation: ['']
    });

    const handleChange = (e) => {
        const { name, value, type, checked } = e.target;
        setFormData({
            ...formData,
            [name]: type === 'checkbox' ? checked : value
        });
    };

    const handleComplectationChange = (index, value) => {
        const newComplectation = [...formData.complectation];
        newComplectation[index] = value;
        setFormData({ ...formData, complectation: newComplectation });
    };

    const handlePhotoChange = (e) => {
        setFormData({ ...formData, car_photos: [...e.target.files] });
    };

const handleSubmit = async (e) => {
    e.preventDefault();
    const formDataToSend = new FormData();
    
    // Добавление всех полей в FormData
    for (const key in formData) {
        if (Array.isArray(formData[key])) {
            formData[key].forEach((item) => formDataToSend.append(key, item));
        } else {
            formDataToSend.append(key, formData[key]);
        }
    }

    try {
        const response = await fetch('php/submit.php', {
            method: 'POST',
            body: formDataToSend,
        });

        const result = await response.json();
        if (result.success) {
            alert('Автомобиль успешно добавлен!');
            // Очистка формы или перенаправление
        } else {
            alert('Ошибка при добавлении автомобиля.');
        }
    } catch (error) {
        console.error('Ошибка:', error);
        alert('Произошла ошибка при отправке данных.');
    }
};


    return (
        <form onSubmit={handleSubmit}>
            <div>
                <label>WinCod</label>
                <input type="text" name="title" value={formData.title} onChange={handleChange} required />
            </div>
            <div>
                <label>Марка автомобиля</label>
                <select name="brand" value={formData.brand} onChange={handleChange} required>
                    <option value="">Выберите марку</option>
                    {/* Здесь будет логика для загрузки марок */}
                </select>
            </div>
            <div>
                <label>Модель автомобиля</label>
                <select name="model_id" value={formData.model_id} onChange={handleChange} required>
                    <option value="">Выберите модель</option>
                    {/* Здесь будет логика для загрузки моделей */}
                </select>
            </div>
            <div>
                <label>Год производства</label>
                <select name="year" value={formData.year} onChange={handleChange} required>
                    {/* Здесь будет логика для загрузки годов */}
                </select>
            </div>
            <div>
                <label>Госномер</label>
                <input type="text" name="car_state_number" value={formData.car_state_number} onChange={handleChange} required />
            </div>
            <div>
                <label>Ссылка на характеристики</label>
                <input type="text" name="car_link_specifications" value={formData.car_link_specifications} onChange={handleChange} required />
            </div>
            <div>
                <label>Ссылка на отчет</label>
                <input type="text" name="car_link_to_report" value={formData.car_link_to_report} onChange={handleChange} required />
            </div>
            <div>
                <label>Тип кузова</label>
                <select name="bodywork" value={formData.bodywork} onChange={handleChange} required>
                    <option value="">Выберите тип кузова</option>
                    <option value="Седан">Седан</option>
                    <option value="Хэтчбек">Хэтчбек</option>
                    <option value="Универсал">Универсал</option>
                    <option value="Кроссовер">Кроссовер</option>
                    <option value="Внедорожник">Внедорожник</option>
                    <option value="Купе">Купе</option>
                    <option value="Кабриолет">Кабриолет</option>
                </select>
            </div>
            <div>
                <label>На продажу</label>
                <input type="checkbox" name="for_sale" checked={formData.for_sale} onChange={handleChange} />
            </div>
            {formData.for_sale && (
                <div>
                    <label>Цена (₽)</label>
                    <input type="number" name="price" value={formData.price} onChange={handleChange} />
                </div>
            )}
            <div>
                <label>Пробег (км)</label>
                <input type="number" name="mileage" value={formData.mileage} onChange={handleChange} required />
            </div>
            <div>
                <label>Цвет</label>
                <input type="text" name="color" value={formData.color} onChange={handleChange} required />
            </div>
            <div>
                <label>Количество владельцев</label>
                <input type="number" name="owners" value={formData.owners} onChange={handleChange} required />
            </div>
            <div>
                <label>Объем двигателя (л)</label>
                <input type="number" step="0.1" name="engine_volume" value={formData.engine_volume} onChange={handleChange} required />
            </div>
            <div>
                <label>Мощность (л.с.)</label>
                <input type="number" name="power" value={formData.power} onChange={handleChange} required />
            </div>
            <div>
                <label>Привод</label>
                <select name="drive" value={formData.drive} onChange={handleChange} required>
                    <option value="">Выберите привод</option>
                    <option value="передний">Передний</option>
                    <option value="задний">Задний</option>
                    <option value="полный">Полный</option>
                </select>
            </div>
            <div>
                <label>Коробка передач</label>
                <select name="transmission" value={formData.transmission} onChange={handleChange} required>
                    <option value="">Выберите коробку передач</option>
                    <option value="Механическая">Механическая</option>
                    <option value="Автоматическая">Автоматическая</option>
                    <option value="Роботизированная">Роботизированная</option>
                </select>
            </div>
            <div>
                <label>Тип топлива</label>
                <select name="fuel_type" value={formData.fuel_type} onChange={handleChange} required>
                    <option value="">Выберите тип топлива</option>
                    <option value="Бензин">Бензин</option>
                    <option value="Дизель">Дизель</option>
                    <option value="Электричество">Электричество</option>
                    <option value="Гибрид">Гибрид</option>
                </select>
            </div>
            <div>
                <label>Описание автомобиля</label>
                <textarea name="description" value={formData.description} onChange={handleChange} required></textarea>
            </div>
            <div>
                <label>Описание комплектации</label>
                <textarea name="equipment_text" value={formData.equipment_text} onChange={handleChange} required></textarea>
            </div>
            <div>
                <label>Фотографии автомобиля</label>
                <input type="file" name="car_photos" multiple onChange={handlePhotoChange} required />
            </div>
            <div>
                <h3>Комплектация</h3>
                {formData.complectation.map((item, index) => (
                    <div key={index}>
                        <input
                            type="text"
                            value={item}
                            onChange={(e) => handleComplectationChange(index, e.target.value)}
                            required
                        />
                    </div>
                ))}
                <button type="button" onClick={() => setFormData({ ...formData, complectation: [...formData.complectation, ''] })}>
                    Добавить элемент комплектации
                </button>
            </div>
            <button type="submit">Сохранить</button>
        </form>
    );
};

export default AddCar;
