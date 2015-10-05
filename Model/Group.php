<?php
namespace Model;

class Group extends Driver
{

    protected $id;

    protected $title;

    protected $price;

    protected $rate;

    protected $set;

    protected $instructorId;

    protected $roomId;

    protected $room;

    protected $instructor;

    public function __construct($data = null)
    {
        if ($data != null) {
            $this->id = $data['ID'];
            $this->title = $data['NAME'];
            $this->price = $data['CENA'];
            $this->rate = $data['rate'];
            $this->set = $data['set'];

            $this->instructorId = $data['ID_INSTRUCTOR'];
            $this->roomId = $data['ID_ROOM'];
        }

        $this->room = null;
        $this->instructor = null;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return mixed
     */
    public function getSet()
    {
        return $this->set;
    }

    /**
     * @param mixed $set
     */
    public function setSet($set)
    {
        $this->set = $set;
    }

    /**
     * @return mixed
     */
    public function getInstructorId()
    {
        return $this->instructorId;
    }

    /**
     * @param mixed $instructorId
     */
    public function setInstructorId($instructorId)
    {
        $this->instructorId = $instructorId;
    }

    /**
     * @return mixed
     */
    public function getRoomId()
    {
        return $this->roomId;
    }

    /**
     * @param mixed $roomId
     */
    public function setRoomId($roomId)
    {
        $this->roomId = $roomId;
    }

    /**
     * @return null
     */
    public function getRoom()
    {
        if ($this->room == null && $this->roomId ){
            $query = 'SELECT * FROM `room` t1 WHERE t1.id = '.$this->roomId.' ORDER BY t1.ID ASC';
            try{
                $result = Mysqli::$mysqli->query($query);
            }catch (\Exception $e){
                $e->getCode().' / '.$e->getMessage();
            }
            $room = new Room($result->fetch_assoc());
            $this->room = $room;
        }
        return $this->room;
    }

    /**
     * @param null $room
     */
    public function setRoom($room)
    {
        $this->room = $room;
    }

    /**
     * @return null
     */
    public function getInstructor()
    {
        if ($this->instructor == null && $this->roomId){
            $query = 'SELECT * FROM `instructor` t1 WHERE t1.id = '.$this->instructorId.' ORDER BY t1.ID ASC LIMIT 1';
            $result = Mysqli::$mysqli->query($query);
            $instructor = new Instructor($result->fetch_assoc());
            $this->instructor = $instructor;
        }
        return $this->instructor;
    }

    /**
     * @param null $instructor
     */
    public function setInstructor($instructor)
    {
        $this->instructor = $instructor;
    }

}