<?php
/*
Создайте класс, описывающий улицу в населенном пункте. Используйте класс дом из предыдущего задания. В качестве примеров полей используйте название улицы, ее протяженность, координаты начала и конца, дома и т.д. Реализуйте методы, которые:
- рассчитывает количество дворников, которое необходимо для уборки прилегающих территорий всех домов по улице в зависимости от площади этих территорий;
- рассчитывает объем коммунальных платежей, которые будут получены со всех домов;
- выводит информацию об улице.
*/
class Street {
  private $street_name = "";  //(str)
  private $street_length = 0;  //length of street in meters (int)
  private $begin_coord=[]; // coordinates of the beginning of the street (x, y)
  private $end_coord=[]; // coordinates of the end of the street (x, y)
  private $traffic_provided = true;  // street available for transport (bool)
  private $road_surface_type = "грунт";  // type of road surface coverage (string: "грунт", "асфальт", "гравий", "песок", "бетон", "плиты")
  private $lighting = false;  //lighting awaivable on the street (bool)
  private $num_of_buildings = 0;
  private $buildings = [];
  private $db_id=null;
  
  /*constructor method. Needs to receive street name (str), street length in meters (int), beginning coordinates x, y(int,int), 
  end coordinates x, y(int,int) availability for transportas (bool), road surface type (str), availability of lighting (bool) as parameters*/
  public function __construct($street_name, $street_length, $begin_coord_x, $begin_coord_y, $end_coord_x, $end_coord_y,  $traffic_provided, $road_surface_type, $lighting) {
    $this->street_name = $street_name;
    $this->street_length = $street_length;
    $this->begin_coord['x'] = $begin_coord_x;
    $this->begin_coord['y'] = $begin_coord_y;
    $this->end_coord['x'] = $end_coord_x;
    $this->end_coord['y'] = $end_coord_y;
    $this->traffic_provided = $traffic_provided;
    $this->road_surface_type = $road_surface_type;
    $this->lighting = $lighting;
  }
  
  public function setDBId($id) {
    $this->db_id = $id;
  }

  public function getStreetData() {
    $response=[];
    $response['street_name'] = $this->street_name;
    $response['street_length'] = $this->street_length;
    $response['num_of_buildings'] = $this->num_of_buildings;
    $response['begin_coord'] = $this->begin_coord;
    $response['end_coord'] = $this->end_coord;
    $response['traffic_provided'] = $this->traffic_provided;
    $response['road_surface_type'] = $this->road_surface_type ;
    $response['lighting'] = $this->lighting ;
    $response['id'] = $this->db_id;
    $response['buildings'] = $this->buildings;
    return $response;
  }
  
  public function setBuildingsNum($num) {
    $this->num_of_buildings = $num;
    return true;
  }
  
  public function getBuildings() {
    return $this->buildings;
  }
  
  public function setBuildings(array $buildings) {
    $this->buildings = $buildings;
    $this->num_of_buildings = count($buildings);
    return true;
  }
  
  public function addBuilding ($building_num, $num_of_floors, $apart_on_floor, $num_of_blocks, $adjacent_territory_area, $has_elevator) {
    $this->buildings[$building_num] = new Building($building_num, $num_of_floors, $apart_on_floor, $num_of_blocks, $adjacent_territory_area, $has_elevator);
    $this->num_of_buildings++;
  }
  
  public function delBuilding($building_num) {
    if(isset($this->buildings[$building_num])) {
      unset($this->buildings[$building_num]);
      $this->num_of_buildings--;
    }
  }
  
  public function getBuilding($building_num) {
    return $this->buildings[$building_num];
  }
  
  public function info() {
    echo "<br>";
    echo "Улица ", $this->street_name,".<br>";
    echo "<div style='font-size: 16px'>";
    echo "Протяженность: ", $this->street_length, " метров.<br>";
    echo "Количество домов: ", $this->num_of_buildings, ".<br>";
    echo "Координаты начала ", $this->begin_coord['x'], ", ", $this->begin_coord['y'], " .<br>";
    echo "Координаты концы ", $this->end_coord['x'], ", ", $this->end_coord['y'], " .<br>";
    echo "Тип дорожного покрытия: ", $this->road_surface_type, ".<br>";
    echo "Движение транспорта: ";
    if ($this->traffic_provided) {
      echo "есть. <br>";
    } else {
      echo "нет. <br>";
    }
    echo "Освещение: ";
    if ($this->lighting) {
      echo "есть. <br></div>";
    } else {
      echo "нет. <br></div>";
    }
  }
  
  // number of janitors to clean the buildings adjacent area
  public function getJanitorsNum() {
    $square=0;
    foreach($this->buildings as $building) {
      $square += $building->getBuildingData()['adjacent_territory_area'];
    }
    return ceil($square/900+1);
  }
  
  //cost of utility of all buildings
  public function getUtilityCost(array $prices) {
    $summ = 0;
    foreach($this->buildings as $building) {
      $summ += $building->getUtilityCost($prices);
    }
    return $summ;
  }
  
  
  public function getTenantsNum() {
    $tenants = 0;
    foreach($this->buildings as $building) {
      $tenants += $building->getTenantsNum();
    }
    return $tenants;
  }
}
?>