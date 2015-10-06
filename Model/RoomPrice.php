<?php
namespace Model;

class RoomPrices{
    public $days; /** Дни месяца */

    public $month;

    public $year;

    public $countDay;

    public function __construct($month = null, $year = null){



        if ($year == null){
            $year = new \DateTime('now');
            $year = $year->format('Y');
            $this->year = $year;
        }

        if ($month == null){
            $month = new \DateTime('now');
            $month = $month->format('m');
            $this->month = $month;
        }

        $count = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $this->countDay = $count;

        $this->days = array();
        for ($i = 1; $i<=$count; $i ++){
            $d = new \DateTime($year.'-'.$month.'-'.$i);
            $d = ($d->format('w') == 0 ? 7 : $d->format('w'));
            $this->days[] = $d;
        }

//        return $this->days;
    }


    public function getDays(){
        $month = $this->month;
        $year = $this->year;
        $count = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $this->countDay = $count;

        $this->days = array();
        for ($i = 1; $i<=$count; $i ++){
            $d = new \DateTime($year.'-'.$month.'-'.$i);
            $d = ($d->format('w') == 0 ? 7 : $d->format('w'));
            $this->days[] = $d;
        }
        return $this->days;
    }

    public function getPrice(){


        $shArray = array();
        $rnArray = array();
        $priceArray = array();
        $q = 'SELECT * FROM room';
        $result = Mysqli::$mysqli->query($q);
        # Получаем все залы
        while ($room = $result->fetch_assoc()){
            $shArray = array();
            $rnArray = array();
            # По каждому залу получаем расписание за месяц
            $q = 'SELECT * FROM group_schedule WHERE room_id='.$room['ID'];
            $res_sh = Mysqli::$mysqli->query($q);
            while($sh = $res_sh->fetch_assoc($res_sh)){
                $shArray[$room['ID']][] = array('dayOfWeek' => $sh['dayOfWeek'], 'firstHour' => $sh['firstHour'], 'numberHour' => $sh['numberHour']);
            }

            # Получаем расценки
            $q = 'SELECT * FROM room_rental WHERE room_id='.$room['ID'];
            $res_rn = Mysqli::$mysqli->query($q);
            while($rn = $res_rn->fetch_assoc($res_rn)){
                $arrayPrice = array('minHour' => $rn['minHour'], 'maxHour' => $rn['maxHour'], 'price' => $rn['price']);
                $rnArray[$room['ID']][$rn['dayType']][] = $arrayPrice;
            }
            if (!isset($rnArray[$room['ID']][1])){
                $rnArray[$room['ID']][1] = $rnArray[$room['ID']][0];
            }
            if (!isset($rnArray[$room['ID']][2])){
                $rnArray[$room['ID']][2] = $rnArray[$room['ID']][0];
            }

            /*
             * Проходим по всем дням $shArray каждого зала
             * И пересчитываем все это. Тут Треш
             */
            foreach ($this->days as $days){
                foreach ($shArray[$room['ID']] as $sh){
                    if ($sh['dayOfWeek'] == $days ){
                        if (!isset($priceArray[$room['ID']])){
                            $priceArray[$room['ID']] = 0;
                        }
                        for ($i = 0 ; $i < $sh['numberHour'] ; $i ++){
                            $dayType = ( $sh['dayOfWeek']>=1 && $sh['dayOfWeek'] <=5 ? 0 : $sh['dayOfWeek']==6 ? 1 : 2);
                            foreach ($rnArray[$room['ID']][$dayType] as $val){
                                if ( $sh['firstHour']+$i >= $val['minHour'] AND $sh['firstHour']+$i < $val['maxHour'] ){
                                    $priceArray[$room['ID']] +=$val['price'];
                                }
                            }
                        }
                    }
                }
            }


        }

        return $priceArray;
    }


}