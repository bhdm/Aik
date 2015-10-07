<?php
namespace Model;

class Driver extends Mysqli{

    static public function find($type, $searchId){
        $first = mb_substr($type,0,1);//первая буква
        $last = mb_substr($type,1);//все кроме первой буквы
        $first = strtoupper($first);
        $last = strtolower($last);
        $className = $first.$last;

        if ($type == 'group'){
            $tableName = 'groupp';
        }else{
            $tableName = $type;
        }
        $query = 'SELECT * FROM `'.$tableName.'` t1 WHERE t1.ID = '.$searchId.' ORDER BY t1.ID ASC';
        $result = Mysqli::$mysqli->query($query);
        $data = array();
        try {
            while ($row = $result->fetch_assoc()) {
                try {
                    switch($className){
                        case 'Instructor': $data[] = new Instructor($row); break;
                        case 'Room': $data[] = new Room($row); break;
                        case 'Group': $data[] = new Group($row); break;
                    }
                }catch (\Exception $e){
                    echo $e->getMessage();
                }
            }
        }catch (\Exception $e){
            echo $e->getMessage();
        }

        return $data;
    }

    static public function findAll($type){
        $first = mb_substr($type,0,1);//первая буква
        $last = mb_substr($type,1);//все кроме первой буквы
        $first = strtoupper($first);
        $last = strtolower($last);
        $className = $first.$last;

        if ($type == 'group'){
            $tableName = 'groupp';
        }else{
            $tableName = $type;
        }
        $query = 'SELECT * FROM `'.$tableName.'` t1 ORDER BY t1.ID DESC';
        $result = Mysqli::$mysqli->query($query);
        $data = array();
        try {
            while ($row = $result->fetch_assoc()) {
                try {
                    switch($className){
                        case 'Instructor': $data[] = new Instructor($row); break;
                        case 'Room': $data[] = new Room($row); break;
                        case 'Group': $data[] = new Group($row); break;
                    }
                }catch (\Exception $e){
                    echo $e->getMessage();
                }
            }
        }catch (\Exception $e){
            echo $e->getMessage();
        }

        return $data;
    }

    /**
     * @param $type String Instructor|Room|Group
     * @param $id
     * @param $date
     * @return array
     */
    static public function payment($type, $id, $date){
        $dateStart = $date->format('Y-m').'-01 00:00:00';
        $date->modify('+ 10 month');
        $dateEnd = $date->format('Y-m').'-01 00:00:00';
        switch($type){
            case 'Instructor': $where = 'g.ID_INSTRUCTOR = '.$id; break;
            case 'Room': $where = 'g.ID_ROOM = '.$id; break;
            case 'Group': $where = 'g.ID = '.$id; break;
        }
        $query =
            "
                SELECT p.YEAR pyear, p.MONTH pmonth, COUNT( p.ID ) amount, SUM( p.SUMMA ) sum
                FROM client c
                LEFT JOIN payment p ON p.ID_CLIENT = c.ID
                LEFT JOIN groupp g ON g.ID = c.ID_GROUP
                WHERE $where AND p.SUMMA > 0
                GROUP BY p.YEAR, p.MONTH
                ORDER BY p.YEAR DESC , p.MONTH DESC
                LIMIT 12
                ";
        $result = Mysqli::$mysqli->query($query);
        $data = array();
        while ($row = $result->fetch_assoc()){
            $key = $row['pyear'].'-'.$row['pmonth'];
            $data[$key] = $row;
        }

        return $data;
    }

    public static function userCount($groupId, $year = 2015)
    {
        $data = array();
        for ($month = 1; $month <= 12 ; $month ++ ){
            $date1 = $year.'-'.$month.'-01 00:00:00';
            $date2 = new \DateTime($date1);
            $date2->modify('+1 month');
            $date2 = $date2->format('Y-m').'-01 00:00:00';
            $query = "SELECT COUNT(c.ID) co FROM client c
                      WHERE c.ID_GROUP = $groupId AND
                      c.created < '$date2' AND
                      c.ENABLED = 1
                    ";
//                echo $query.'<br /><br />';
            $result = Mysqli::$mysqli->query($query);
            $lastData = null;
            $row = $result->fetch_assoc();
            $data[$month] = $row['co'];
        }
        return $data;
    }


    public static function userSales($groupId, $year = 2015)
    {
        $data = array();
        for ($month = 1; $month <= 12 ; $month ++ ){
            $date1 = $year.'-'.$month.'-01 00:00:00';
            $date2 = new \DateTime($date1);
            $date2->modify('+1 month');
            $date2 = $date2->format('Y-m').'-01 00:00:00';
            $query = "SELECT SUM(p.SUMMA) co FROM client c
                          LEFT JOIN payment p  ON p.ID_CLIENT = c.id
                          WHERE c.ID_GROUP = $groupId AND p.MONTH = $month AND p.YEAR = $year
                    ";
            $result = Mysqli::$mysqli->query($query);
            $lastData = null;
            $row = $result->fetch_assoc();
            $data[$month] = $row['co'];
        }
        return $data;
    }

    public static function roomRent($groupId, $year = 2015){
        $data = array();
        for ($month = 1; $month <= 12 ; $month ++ ){
            $count = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $countDay = $count;

            $days = array();
            for ($i = 1; $i<=$count; $i ++){
                $d = new \DateTime($year.'-'.$month.'-'.$i);
                $d = ($d->format('w') == 0 ? 7 : $d->format('w'));
                $days[] = $d;
            }

            $date1 = $year.'-'.$month.'-01 00:00:00';
            $date2 = new \DateTime($date1);
            $date2->modify('+1 month');
            $date2 = $date2->format('Y-m').'-01 00:00:00';

            $shArray = array();
            $rnArray = array();

            # По каждому залу получаем расписание за месяц
            $q = "SELECT group_schedule.* FROM group_schedule
                      LEFT JOIN groupp g ON g.ID_ROOM = group_schedule.room_id
                      WHERE g.ID = $groupId";
            $res_sh = Mysqli::$mysqli->query($q);
            if ($res_sh->num_rows > 0) {
                while ($sh = $res_sh->fetch_assoc()) {
                    $shArray[] = array('dayOfWeek' => $sh['dayOfWeek'], 'firstHour' => $sh['firstHour'], 'numberHour' => $sh['numberHour']);
                }
            }
//                }else{
//                        $shArray[] = array('dayOfWeek' => 0, 'firstHour' => 0, 'numberHour' => 0);
//                }

            # Получаем расценки
            $q = "SELECT * FROM room_rental
                      LEFT JOIN groupp g ON g.ID_ROOM = room_rental.room_id
                      WHERE g.ID = $groupId";
            $res_rn = Mysqli::$mysqli->query($q);
            while($rn = $res_rn->fetch_assoc()){
                $arrayPrice = array('minHour' => $rn['minHour'], 'maxHour' => $rn['maxHour'], 'price' => $rn['price']);
                $rnArray[$rn['dayType']][] = $arrayPrice;
            }
            if (isset($rnArray[0])){
                if (!isset($rnArray[1])){
                    $rnArray[1] = $rnArray[0];
                }
                if (!isset($rnArray[2])){
                    $rnArray[2] = $rnArray[0];
                }
            }else{
                $rnArray[1] = array();
                $rnArray[2] = array();
            }

            /*
             * Проходим по всем дням $shArray каждого зала
             * И пересчитываем все это. Тут Треш
             */
            $priceArray = 0;
            foreach ($days as $day){
                foreach ($shArray as $sh){
                    if (isset($sh['dayOfWeek']) && $sh['dayOfWeek'] == $day ){
                        if (!isset($priceArray)){
                            $priceArray = 0;
                        }
                        for ($i = 0 ; $i < $sh['numberHour'] ; $i ++){
                            $dayType = ( $sh['dayOfWeek']>=1 && $sh['dayOfWeek'] <=5 ? 0 : $sh['dayOfWeek']==6 ? 1 : 2);
                            if (isset($rnArray[$dayType])){
                                foreach ($rnArray[$dayType] as $val){
                                    if ( $sh['firstHour']+$i >= $val['minHour'] AND $sh['firstHour']+$i < $val['maxHour'] ){
                                        $priceArray +=$val['price'];
                                    }
                                }
                            }else{
                                $priceArray = 0;
                            }
                        }
                    }
                }


            }

            $data[$month] = $priceArray;
        }
        return $data;
    }

    public static function userSalary($groupId, $year = 2015){
        $data = array();
        for ($month = 1; $month <= 12 ; $month ++ ){
            $priceArray = 0;
            $sh = array();

            $date1 = $year.'-'.$month.'-01 00:00:00';
            $date2 = new \DateTime($date1);
            $date2->modify('+1 month');
            $date2 = $date2->format('Y-m').'-01 00:00:00';

            # находим все его группы и проходим по каждой
            $q = 'SELECT * FROM groupp WHERE ID ='.$groupId;
            $res_gr = Mysqli::$mysqli->query($q);
            $group = $res_gr->fetch_assoc();
            $sh = array();
            $query = 'SELECT * FROM group_schedule WHERE group_id = '.$groupId;
            $res_sh = Mysqli::$mysqli->query($query);
            # проходим дням группы
            while ($row = $res_sh->fetch_assoc()){
                $sh[$row['dayOfWeek']] = $row;
            }

            if (!isset($priceArray)) $priceArray = 0;

            $priceArray = $priceArray + $group['rate'];


            $data[$month]['plan'] =  $priceArray;

            # Отнимаем штрафы
            $query = 'SELECT * FROM fine WHERE `date`>="'.$date1.'" AND date<"'.$date2.'" AND instructor_id='.$group['ID_INSTRUCTOR'];
            $res_fine = Mysqli::$mysqli->query($query);
            while ($fine = $res_fine->fetch_assoc()){
                $priceArray -= $fine['summa'];
            }

            # Прибавляем замены
            $query = 'SELECT * FROM fine WHERE `date`>="'.$date1.'" AND date<"'.$date2.'" AND replace_id='.$group['ID_INSTRUCTOR'];
            $res_fine = Mysqli::$mysqli->query($query);
            while ($fine = $res_fine->fetch_assoc()){
                $priceArray += $fine['summa']/2;
            }

            $data[$month]['fact'] =  $priceArray;

        }
        return $data;
    }
}