#include "Arduino.h"
#include "DistanceSensor.h"

DistanceSensor::DistanceSensor(int URPWM, int URTRIG)
{
  _URPWM  = URPWM;
  _URTRIG = URTRIG;
  uint8_t EnPwmCmd[4]={0x44,0x22,0xbb,0x01}; 
  
  pinMode(_URTRIG,OUTPUT);                     // A low pull on pin COMP/TRIG
  digitalWrite(_URTRIG,HIGH);                  // Set to HIGH
  pinMode(_URPWM, INPUT);                      // Sending Enable PWM mode command
}

int DistanceSensor::getDistance() {
  digitalWrite(_URTRIG, LOW);
  digitalWrite(_URTRIG, HIGH);               // reading Pin PWM will output pulses
 
  unsigned long DistanceMeasured=pulseIn(_URPWM,LOW);
  if(DistanceMeasured==50000 ){
    return -1;
  } else {
    return DistanceMeasured/50;
  }
}

