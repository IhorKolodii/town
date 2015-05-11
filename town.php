<?php
/*
Создайте класс, описывающий населенный пункт. В качестве примеров полей используйте название населенного пункта, год основания, географические координаты и т.д. Реализуйте методы, которые:
- рассчитывает бюджет населенного пункта в зависимости от размера налога на землю, полученного со всех домов;
- рассчитывает количество населения, проживающего в населенном пункте;
- выводит информацию о населенном пункте.
*/

class Town {
  private $name = "";  //(str)
  private $year_of_foundation = 0;  // (int)
  private $coord=[]; // coordinates of town (x, y)
  private $type = ""; // type of settlement (string: "village", "city", "town", "township")
  private $has_river = false;  // town has a river (bool)
  private $num_of_streets = 0;  // (int)
  private $streets = [];
  private $db_id=null;
  
  /*constructor method. Needs to receive settlement name (str), year of foundation (int), geographical coordinates x, y(int,int), 
  type of settlement (str), availability of river (bool) as parameters*/
  public function __construct($name, $year_of_foundation, $coord_x, $coord_y, $type, $has_river) {
    $this->name = $name;
    $this->year_of_foundation = $year_of_foundation;
    $this->coord['x'] = $coord_x;
    $this->coord['y'] = $coord_y;
    $this->type = $type;
    $this->has_river = $has_river;
  }
  
  public function setDBId($id) {
    $this->db_id = $id;
  }

  public function getTownData() {
    $response=[];
    $response['name'] = $this->name;
    $response['year_of_foundation'] = $this->year_of_foundation;
    $response['coord'] = $this->coord;
    $response['type'] = $this->type;
    $response['has_river'] = $this->has_river;
    $response['num_of_streets'] = $this->num_of_streets;
    $response['id'] = $this->db_id;
    $response['streets'] = $this->streets;
    return $response;
  }
  
  public function getStreets() {
    return $this->streets;
  }
  
  public function setStreetsNum($num) {
    $this->num_of_streets = $num;
    return true;
  }
  
  public function setStreets(array $streets) {
    $this->streets = $streets;
    $this->num_of_streets = count($streets);
    return true;
  }
  
  public function addStreet($street_name, $street_length, $begin_coord_x, $begin_coord_y, $end_coord_x, $end_coord_y,  $traffic_provided, $road_surface_type, $lighting) {
    if(!isset($this->streets[$street_name])) {
      $this->streets[$street_name] = new Street($street_name, $street_length, $begin_coord_x, $begin_coord_y, $end_coord_x, $end_coord_y,  $traffic_provided, $road_surface_type, $lighting);
      $this->num_of_streets++;
    }
  }
  
  public function delStreet($street_name) {
    if(isset($this->streets[$street_name])) {
      unset($this->streets[$street_name]);
      $this->num_of_streets--;
    }
  }
  
  public function getStreet($street_name) {
    return $this->streets[$street_name];
  }
  
  public function info() {
    echo "<br>";
    echo "Населенный пункт ", $this->name,".<br>";
    echo "<div style='font-size: 16px'>";
    echo "Год основания: ", $this->year_of_foundation, ".<br>";
    echo "Тип населенного пункта: "; 
    switch ($this->type) {
      case "village": echo "Деревня"; break;
      case "city": echo "Мегаполис"; break;
      case "town": echo "Город"; break;
      case "township": echo "Поселок городского типа"; break;
      default: echo "нет информации"; break;
    }
    echo ".<br>";
    echo "Количество улиц: ", $this->num_of_streets, ".<br>";
    echo "Координаты ", $this->coord['x'], ", ", $this->coord['y'], " .<br>";
    echo "Река: ";
    if ($this->has_river) {
      echo "есть. <br></div>";
    } else {
      echo "нет. <br></div>";
    }
  }
  
  
  // budget of town based on every building land fee
  public function getBudget($landTaxPrice) {
    $summ = 0;
    foreach($this->streets as $street) {
      foreach($street->getBuildings() as $building) {
        $summ += $building->getLandFee($landTaxPrice);
      }
    }
    return $summ;
  }
  
  public function getTenantsNum() {
    $tenants = 0;
    foreach($this->streets as $street) {
      $tenants += $street->getTenantsNum();
    }
    return $tenants;
  }
}


?>