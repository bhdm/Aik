<?php
namespace Controller;
use Model\Driver;

/**
 * Class instructor
 * @package Controller
 * Тут будет генерация отчетов по инструкторам
 */
class instructor{

    protected $instructors;

    public function __construct(){
        # Получили всех инструкторов
        $this->instructors = Driver::findAll('instructor');

        # Теперь расчитываем доход помесячно

    }
}

