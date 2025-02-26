import React from 'react';
import AddCar from './AddCar';
import './style.css'; // Подключение стилей

const AddCarPage = () => {
    return (
        <div className="add-car-page">
            <h1>Добавить Автомобиль</h1>
            <AddCar />
        </div>
    );
};

export default AddCarPage;
