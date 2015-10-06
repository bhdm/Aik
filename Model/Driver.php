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
            $query = 'SELECT * FROM `'.$tableName.'` t1 ORDER BY t1.ID ASC';
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

    }