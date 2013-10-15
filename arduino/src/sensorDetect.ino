// # Connection:
// #       Pin 1 VCC (URM V3.2) -> VCC (Arduino)
// #       Pin 2 GND (URM V3.2) -> GND (Arduino)
// #       Pin 4 PWM (URM V3.2) -> Pin 3 (Arduino)
// #       Pin 6 COMP/TRIG (URM V3.2) -> Pin 5 (Arduino)
// #
//Sensor 1
#include "DistanceSensor.h"
DistanceSensor sensor(3,5);

void setup(){                                 // Serial initialization
  Serial.begin(9600);                         // Sets the baud rate to 9600

  uint8_t EnPwmCmd[4]={0x44,0x22,0xbb,0x01};    // distance measure command
  for(int i=0;i<4;i++){
      Serial.write(EnPwmCmd[i]);
   }
}

void loop(){
 Serial.println( String(millis())+":"+String(sensor.getDistance()) );
 delay(20);
}