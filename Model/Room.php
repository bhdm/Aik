<?php
namespace Model;

class Room extends Driver{

    protected $id;

    protected $title;

    protected $adrs;

    protected $phone;

    protected $admin;

    protected $groups;

    public function __construct($data = null){
        if ($data != null){
            $this->id = $data['ID'];
            $this->title = $data['NAME'];
            $this->adrs = $data['ADRES'];
            $this->phone = $data['PHONE'];
            $this->admin = $data['ADMIN'];
        }
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
    public function getAdrs()
    {
        return $this->adrs;
    }

    /**
     * @param mixed $adrs
     */
    public function setAdrs($adrs)
    {
        $this->adrs = $adrs;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * @param mixed $admin
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }


    public function getGroups(){
        if ($this->groups == null){
            $query = 'SELECT * FROM `groupp` g WHERE g.ID_ROOM = '.$this->id.' ORDER BY g.ID ASC';
            try{
                $result = Mysqli::$mysqli->query($query);
            }catch (\Exception $e){
                $e->getCode().' / '.$e->getMessage();
            }
            while ($row = $result->fetch_assoc()){
                $this->groups[] = $row['ID'];
            }
        }
        if ($this->groups == null){
            $this->groups = array();
        }
        return $this->groups;
    }
}
