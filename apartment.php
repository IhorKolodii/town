<?php
class Apartment {
  private $apartment_num = 0;  //apartment number in house (int)
  private $num_of_rooms = 0;  //number of rooms in apartment (int)
  private $apartment_area = 0;  //area of apartment (int)
  private $floor = 0; //floor on which apartment is located (int)
  private $num_of_tenants = 0;  //number of tenants cerrently lives in apartment (int)
  private $have_balcony = false;  //have apartment a balcony(ies) (bool)
  private $num_of_balconies = 0;  //number of balconies (int)
  private $electricity_consumed = 0;  //amount of consumed electricity in kwh (float)
  private $water_consumed = 0;  //amount of consumed water im m3 (float)
  private $gas_consumed = 0;  //amount of consumed gas in m3 (float)
  private $db_id=null;
  private $tenants = []; //info about tenants (array of arrays, more in add tenant function description)
  
  /*constructor method. Needs to receive apartment number in house (int), 
  number of rooms in  apartment (int), area of apartment (int), 
  floor on which apartment is located (int), number of balconies (int) as parameters*/
  public function __construct($apartment_num, $num_of_rooms, $apartment_area, $floor, $num_of_balconies) {
    $this->apartment_num = $apartment_num;
    $this->num_of_rooms = $num_of_rooms;
    $this->apartment_area = $apartment_area;
    $this->floor = $floor;
    $this->num_of_balconies = $num_of_balconies;
    if($num_of_balconies!=0) 
    {
      $this->have_balcony = true;
    } 
    else 
    {
      $this->have_balcony = false;
    }
  }
  
  public function setDBId($id) {
    $this->db_id = $id;
  }
  
  public function setTenantsNum($num) {
    $this->$num_of_tenants = $num;
  }
  
  //handlers for add/reset utilities amount
  public function add_electric($amount) {
    $this->electricity_consumed += $amount;
  }
  public function add_water($amount) {
    $this->water_consumed += $amount;
  }
  public function add_gas($amount) {
    $this->gas_consumed += $amount;
  }
  public function reset_electric() {
    $this->electricity_consumed = 0;
  }
  public function reset_water() {
    $this->water_consumed = 0;
  }
  public function reset_gas() {
    $this->gas_consumed = 0;
  }
  
  //tenant add and delete methods
  //birth date in "YYYY-MM-DD" format
  public function add_tenant($id, $first_name, $second_name, $birth_date, $gender) {
    if(is_numeric($id) && $id > 0 && $id < 9999999 && $id%1==0) {
      $new_tenant = [
      "id"=>$id,
      "first_name"=>$first_name,
      "second_name"=>$second_name,
      "birth_date"=>$birth_date, 
      "gender"=>$gender
      ];
      $this->tenants[$id] = $new_tenant;
      $this->num_of_tenants++;
    }
  }
  public function del_tenant($id) {
    if(isset($this->tenants[$id])) {
      unset($this->tenants[$id]);
      $this->num_of_tenants--;
    }
  }
  
  //show information about apartment
  public function info() {
    echo "<br>";
    echo "Квартира №", $this->apartment_num,".<br>";
    echo "<div style='font-size: 16px'>";
    echo $this->floor, " этаж. <br>";
    echo "Площадь ", $this->apartment_area, " м2.<br>";
    echo "Количество комнат: ", $this->num_of_rooms, ".<br>";
    echo "Количество балконов: ", $this->num_of_balconies, ".<br>";
    echo "Количество жильцов: ", $this->num_of_tenants, ".<br>";
    echo "<br>";
    echo "Жильцы:<br>";
    echo "</div>";
    echo "<div style='font-size: 14px'><br>";
    foreach ($this->tenants as $tenant) {
      echo $tenant["first_name"], " ", $tenant["second_name"], "<br>";
      echo "Пол: ", $tenant["gender"], "<br>";
      echo "Дата рождения: ", $tenant["birth_date"], "<br>";
      echo "ID: ", $tenant["id"], "<br><br>";
    }
    echo "</div>";
  }
  //handlers for utilities prices
  public function calculate_elec($price) {  //calculate price of electricity per month on apartment, price per kwh as parameter
    if($this->electricity_consumed!=0) {
      return $this->electricity_consumed*$price;
    } else {
      return $this->num_of_tenants*20*$price;
    }
  }
  public function calculate_water($price) {  //calculate price of water per month on apartment, price per m3 as parameter
    if($this->water_consumed!=0) {
      return $this->water_consumed*$price;
    } else {
      return $this->num_of_tenants*11.1*$price;
    }
  }
  public function calculate_gas($price) {  //calculate price of gas per month on apartment, price per m3 as parameter
    if($this->gas_consumed!=0) {
      return $this->gas_consumed*$price;
    } else {
      return $this->num_of_tenants*6*$price;
    }
  }
  public function calculate_canalisation($price) {  //calculate price of canalisation per month on apartment, price per m3 as parameter
    if($this->water_consumed!=0) {
      return $this->water_consumed*$price;
    } else {
      return $this->num_of_tenants*11.1*$price;
    }
  }
  public function calculate_fee($price) {  //calculate price of fee per month on apartment, price per m2 as parameter
    return $this->apartment_area*$price;
  }
  public function calculate_heating($price) {  //calculate price of heating per month on apartment, price per m2 as parameter
    return $this->apartment_area*$price;
  }
  
  //utility total for month
  public function getUtilityCost(array $prices) {  //calculate price of all utilities per apartment
    return $this->calculate_elec($prices['elec_price'])+$this->calculate_water($prices['water_price'])+$this->calculate_gas($prices['gas_price'])+$this->calculate_canalisation($prices['canaliz_price'])+$this->calculate_fee($prices['fee'])+$this->calculate_heating($prices['heat_price']);
  }
  
  public function getApartmentData() {
    $response = [];
    $response['apartment_num'] = $this->apartment_num;
    $response['num_of_rooms'] = $this->num_of_rooms;
    $response['apartment_area'] = $this->apartment_area;
    $response['floor'] = $this->floor;
    $response['num_of_tenants'] = $this->num_of_tenants;
    $response['have_balcony'] = $this->have_balcony;
    $response['num_of_balconies'] = $this->num_of_balconies;
    $response['electricity_consumed'] = $this->electricity_consumed;
    $response['water_consumed'] = $this->water_consumed;
    $response['gas_consumed'] = $this->gas_consumed;
    $response['id'] = $this->db_id;
    $response['tenants'] = $this->tenants;
    return $response;
  }
  
  public function setTenants(array $tenants) {
    $this->tenants=$tenants;
    $this->num_of_tenants = count($tenants);
  }
  public function getTenants() {
    return $this->tenants;
  }
  
  public function getTenantsNum() {
    return $this->num_of_tenants;
  }
}

?>
