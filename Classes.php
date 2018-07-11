<?php
class User{
    public $name;
    public $birth_date;
    public $phones = [];

    public function __construct($name,$birth_date,$phones = null){
        $this->name = $name;
        $this->birth_date = $birth_date;
        $this->phones = $phones;
    }
}

class Db
{
    protected $dbc;

    protected $result;

    function __construct()
    {

        $this->dbc = new mysqli(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);
        if ($this->dbc->connect_error) {
            die();
        }
    }

    public function mysqli_fetch_all_my($rows){

        $arr=[];

        while ($row = mysqli_fetch_assoc($rows)) {
            $arr[] = $row;
        }

        return $arr;
    }
    public function insert_id(){

        return $this->dbc->insert_id;
    }

    public function makeQuery($query){
        $result = $this->dbc->query($query);

        if (!$result) {
            var_dump($query);
            die();
        }

        if(is_bool($result))
            return $result;
        else{
            return $result->num_rows>1 ? $this->mysqli_fetch_all_my($result) : mysqli_fetch_assoc($result);
        }
    }

    function __destruct(){
        $this->dbc->close();
    }
}

class Factory{

    protected $dbc;

    public function __construct(){
        $this->dbc = new DB();
    }

    public function getUserById($id){
        $user = $this->dbc->makeQuery('SELECT name, DATE_FORMAT(birth_date, "%d-%m-%Y") as birth_date  FROM users WHERE id = '.$id);

        $user['phones']  = $this->dbc->makeQuery('SELECT * FROM phones WHERE user_id = '.$id);

        return new User($user['name'],$user['birth_date'],$user['phones']);
    }

    public function makeDeposit($country_code,$operator,$number,$amount){

        if($amount<=100&&$amount>=0) {
            $phone = $this->dbc->makeQuery('SELECT id,balance FROM phones WHERE country_code =' . $country_code . '  AND operator_code =' . $operator . ' AND phone_number = ' . $number);

            if($phone) {
                $phone['balance'] += $amount;

                return $this->dbc->makeQuery('UPDATE phones SET balance=' . $phone['balance'] . ' WHERE id =' . $phone['id']);
            }
            return false;

        }else{
            return false;
        }
    }

    public function createUser($name,$birth_day){
        $this->dbc->makeQuery('INSERT INTO users(name, birth_date) VALUES ("'.$name.'",STR_TO_DATE("'.$birth_day.'","%d-%m-%Y"))');
        return $this->dbc->insert_id();
    }

    public function addPhoneToUser($user_id,$country_code,$operator,$number){
        $phone = $this->dbc->makeQuery('SELECT id FROM phones WHERE country_code =' . $country_code . '  AND operator_code =' . $operator . ' AND phone_number = ' . $number);
        $user =  $this->dbc->makeQuery('SELECT * FROM users WHERE id = '.$user_id);

        if(!$phone&&$user)
            return $this->dbc->makeQuery('INSERT INTO phones( `country_code`, `operator_code`, `phone_number`, `balance`, `user_id`) VALUES ('.$country_code.','.$operator.','.$number.',0,'.$user_id.')');

        return false;
    }

    public function deleteUser($id){
        $this->dbc->makeQuery('DELETE FROM `phones` WHERE user_id = '.$id);

        return $this->dbc->makeQuery('DELETE FROM `users` WHERE id = '.$id);
    }


}