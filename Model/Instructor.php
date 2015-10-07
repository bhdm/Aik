<?php
namespace Model;

class Instructor extends Driver{

    protected $id;

    protected $lastName;

    protected $firstName;

    protected $surName;

    protected $phone;

    protected $groups; /* Массив ID */

    public function getTitle(){
        return $this->lastName.' '.$this->firstName.' '.$this->surName;
    }

    public function __construct($data = null){
        if ($data != null){
            $this->id = $data['ID'];
            $this->lastName = $data['LAST_NAME'];
            $this->firstName = $data['FIRST_NAME'];
            $this->surName = $data['FATHERS_NAME'];
            $this->phone = $data['PHONE'];

            $query = 'SELECT * FROM `groupp` g WHERE g.ID_INSTRUCTOR = '.$this->id.' ORDER BY g.ID ASC';
            try{
                $result = Mysqli::$mysqli->query($query);
            }catch (\Exception $e){
                $e->getCode().' / '.$e->getMessage();
            }
            while ($row = $result->fetch_assoc()){
                $this->groups[] = $row['ID'];
            }

        }else{
            $this->groups = array();
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
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getSurName()
    {
        return $this->surName;
    }

    /**
     * @param mixed $surName
     */
    public function setSurName($surName)
    {
        $this->surName = $surName;
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

    public function getGroups(){
        if ($this->groups == null){
            $query = 'SELECT * FROM `groupp` g WHERE g.ID_INSTRUCTOR = '.$this->id.' ORDER BY g.ID ASC';
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
