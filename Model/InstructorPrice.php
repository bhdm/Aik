<?php
namespace Model;

class InstructorPrices{
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
        return $this->days;
    }

    public function getPrice(){
        $priceArray = array();
        $sh = array();
        $q = 'SELECT * FROM instructor';
        $res = Mysqli::$mysqli->query($q);

        $date = $this->year.'-'.$this->month.'-1';
        $date2 = $this->year.'-'.$this->month.'-'.$this->countDay;
        # Проходим по всем инструкторам
        while ( $instructor = $res->fetch_assoc($res)){
            # находим все его группы и проходим по каждой
            $q = 'SELECT * FROM groupp WHERE ID_INSTRUCTOR='.$instructor['ID'];
            $res_gr = Mysqli::$mysqli->query($q);
            while ($groups = $res_gr->fetch_assoc($res_gr)){
                $sh = array();
                $query = 'SELECT * FROM group_schedule WHERE group_id = '.$groups['ID'];
                $res_sh = Mysqli::$mysqli->query($query);
                # проходим дням группы
                while ($row = $res_sh->fetch_assoc($res_sh)){
                    $sh[$row['dayOfWeek']] = $row;
                }

                if (!isset($priceArray[$instructor['ID']])) $priceArray[$instructor['ID']] = 0;
                $priceArray[$instructor['ID']]+=$groups['rate'];

            }

            # Отнимаем штрафы
            $query = 'SELECT * FROM fine WHERE `date`>"'.$date.'" AND date<"'.$date2.'" AND instructor_id='.$instructor['ID'];
            $res_fine = Mysqli::$mysqli->query($query);
            while ($fine = $res_fine->fetch_assoc($res_fine)){
                $priceArray[$instructor[ID]] -= $fine['summa'];
            }

            # Прибавляем замены
            $query = 'SELECT * FROM fine WHERE `date`>"'.$date.'" AND date<"'.$date2.'" AND replace_id='.$instructor['ID'];
            $res_fine = Mysqli::$mysqli->query($query);
            while ($fine = $res_fine->fetch_assoc($res_fine)){
                $priceArray[$instructor[ID]] += $fine['summa']/2;
            }

        }

        return $priceArray;
    }

    public function getWeekName($number){
        switch ($number){
            case 1: return 'Понедельник';
            case 2: return 'Вторник';
            case 3: return 'Среда';
            case 4: return 'Четверг';
            case 5: return 'Пятница';
            case 6: return 'Суббота';
            case 7: return 'Воскресенье';
        }
    }


}