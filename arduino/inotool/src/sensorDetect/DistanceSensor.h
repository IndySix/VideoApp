#ifndef DistanceSensor_h
#define DistanceSensor_h

#include "Arduino.h"

class DistanceSensor
{
  public:
    DistanceSensor(int URPWM, int URTRIG);
    int getDistance();
  private:
    int _URPWM;
    int _URTRIG;
};

#endif