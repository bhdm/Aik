<?php
namespace Controller;

use Model\Driver;
use Model\InstructorPrices;
use Model\RoomPrices;

/**
 * Class instructor
 * @package Controller
 * Тут будет генерация отчетов по инструкторам
 */
class instructor{

    public function getData(){
        # Получили всех инструкторов
        $instructors = Driver::findAll('instructor');
        $payments = array();
        # Получить все патежи клиентов
        foreach ($instructors as $i){
            $payments[$i->getId()] = Driver::payment('Instructor', $i->getId(),new \DateTime());
        }
        # Получить стоимость аренды помещения
        $roomPrices = new RoomPrices();

        # Получить ЗП инструктора
        $instructorPrices = new InstructorPrices();

        return array(
            'instructors' => $instructors,
            'payments' => $payments,
            'roomPrices' => $roomPrices,
            'instructorPrices' => $instructorPrices,
        );
    }
}

