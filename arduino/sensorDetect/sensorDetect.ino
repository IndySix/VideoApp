// # Connection:
// #       Pin 1 VCC (URM V3.2) -> VCC (Arduino)
// #       Pin 2 GND (URM V3.2) -> GND (Arduino)
// #       Pin 4 PWM (URM V3.2) -> Pin 3 (Arduino)
// #       Pin 6 COMP/TRIG (URM V3.2) -> Pin 5 (Arduino)
// # DistanceSensor sensor(<Pin 4 PWM>,<Pin 6 COMP>);
#include "DistanceSensor.h"
DistanceSensor sensorL(3,5); 	//Sensor L pin 3 en 5
DistanceSensor sensorR(10,12); 	// Sensor R pin 5 en 6

void setup(){   
  Serial.begin(9600);
}

void loop(){
  String runTimeMillis = String(millis());
  String distanceL     = String(sensorL.getDistance());
  String distanceR     = String(sensorR.getDistance());
  Serial.println( runTimeMillis+":"+distanceL+":"+distanceR );
  delay(20);
}
