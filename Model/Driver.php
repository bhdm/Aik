<?php
namespace Model;

    class Driver extends Mysqli{

        public function find($type, $searchId){
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

        public function findAll($type){
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
    }