<?php

/*Создайте класс, описывающий жилой многоэтажный дом, состоящий из квартир. Используйте класс квартиры из предыдущего задания. В качестве примеров полей используйте номер дома, количество этажей, количество подъездов, квартиры, площади прилегающей территории и т.д. Реализуйте методы, которые:
- рассчитывает размер коммунальных платежей со всех квартир в этом доме;
- рассчитывает объем потребляемого электричества для освещения подъездов в зависимости от количества подъездов и этажей;
- рассчитывает размер налога на землю в зависимости от размера терртории, отведенной для дома;
- выводит информацию о доме.*/

class Building {
  private $building_num = 0;  //building number on street (int)
  private $num_of_floors = 0;  //number of floors in building (int)
  private $num_of_blocks = 0;  //number of blocks in building (int)
  private $apart_on_floor = 0;  //number on apartments per floor (int)
  private $num_of_apartments = 0;  //number of apartments in building (int)
  private $adjacent_territory_area = 0; //area of adjacent territory (int)
  private $has_elevator = false;  //has building an elevator (bool)
  private $db_id=null;
  private $apartments = [];
  
  /*constructor method. Needs to receive apartment number in house (int), 
  number of rooms in  apartment (int), area of apartment (int), 
  floor on which apartment is located (int), number of balconies (int) as parameters*/
  public function __construct($building_num, $num_of_floors, $apart_on_floor, $num_of_blocks, $adjacent_territory_area, $has_elevator) {
    $this->building_num = $building_num;
    $this->apart_on_floor = $apart_on_floor;
    $this->num_of_floors = $num_of_floors;
    $this->num_of_blocks = $num_of_blocks;
    $this->adjacent_territory_area = $adjacent_territory_area;
    $this->has_elevator = $has_elevator;
  }
  
  public function setDBId($id) {
    $this->db_id = $id;
  }

  public function setApartmentsNum($num) {
    $this->num_of_apartments = $num;
  }
  
  public function getBuildingData() {
    $response=[];
    $response['building_num'] = $this->building_num;
    $response['num_of_floors'] = $this->num_of_floors;
    $response['apart_on_floor'] = $this->apart_on_floor;
    $response['num_of_blocks'] = $this->num_of_blocks;
    $response['num_of_apartments'] = $this->num_of_apartments;
    $response['adjacent_territory_area'] = $this->adjacent_territory_area;
    $response['has_elevator'] = $this->has_elevator;
    $response['id'] = $this->db_id;
    $response['apartments'] = $this->apartments;
    return $response;
  }
  
  public function getApartments() {
    return $this->apartments;
  }
  
  public function setApartments(array $apartments) {
    $this->apartments = $apartments;
    $this->num_of_apartments = count($apartments);
    return true;
  }
  
  public function addApartment($apartment_num, $num_of_rooms, $apartment_area, $floor, $num_of_balconies) {
    $this->apartments[$apartment_num] = new Apartment($apartment_num, $num_of_rooms, $apartment_area, $floor, $num_of_balconies); 
    $this->num_of_apartments++;  
  }
  
  public function delApartment($apartment_num) {
    if(isset($this->apartments[$apartment_num])) {
      unset($this->apartments[$apartment_num]);
      $this->num_of_apartments--;  
    }
  }
  
  public function getApartment($apartment_num) {
    return $this->apartments[$apartment_num];
  }
  
  public function getUtilityCost(array $prices) {
    $summ = 0;
    foreach($this->apartments as $apartment) {
      $summ += $apartment->getUtilityCost($prices);
    }
    return $summ;
  }
  
  //average service electricity consumption amount
  public function getServiceElec() {
    $power = 0.06; //average power of lightbulb
    $hours = 12; // hours of lighting per day
    return $this->num_of_blocks*($this->num_of_floors+1)*$power*$hours;  
    
  }
  
  //Function returns cost of land fee calculated from area of adjanced territory. receive price of land fee per m2
  public function getLandFee($landTaxPrice) {
    return $this->adjacent_territory_area*$landTaxPrice;
  }
  
  public function info() {
    echo "<br>";
    echo "Дом №", $this->building_num,".<br>";
    echo "<div style='font-size: 16px'>";
    echo $this->num_of_floors, " этажей.<br>";
    echo $this->num_of_blocks, " подъездов.<br>";
    echo $this->num_of_apartments, " квартир.<br>";
    echo $this->apart_on_floor, " квартиры на этаже. <br>";
    echo "Площадь прилегающей территории: ", $this->adjacent_territory_area, " м2.<br>";
    echo "Лифт: ";
    if ($this->has_elevator) {
      echo "есть. <br>";
    } else {
      echo "нет. <br>";
    }
    echo "Количество жильцов: ";
    $tenants = 0;
    foreach($this->apartments as $apartment) {
      $tenants += $apartment->getApartmentData()['num_of_tenants'];
    }
    echo $tenants, ".<br>";
    echo "</div>";
  }
  
  public function getTenantsNum() {
    $tenants = 0;
    foreach($this->apartments as $apartment) {
      $tenants += $apartment->getTenantsNum();
    }
    return $tenants;
  }
}
?>