/*
    --[Ev_v30_12] - Frame Class Utility

    Explanation: This is the basic code to create a frame with every
    input of Events Board

    Copyright (C) 2016 Libelium Comunicaciones Distribuidas S.L.
    http://www.libelium.com

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

    Version:           3.1
    Design:            David Gascón
    Implementation:    Carlos Bello
*/
#include <WaspSensorEvent_v30.h>
#include <WaspFrame.h>
#include <WaspXBee802.h>
// Variable to store the read value
char NumSerie[17];
int   pirValue;
float temp;
float humd;
float pres;
int pirCount=0;
int temperaturaCount=0;
int vlagaCount=0;
int tlakCount=0;
uint8_t error;
char RX_ADDRESS[] = "0013A20040F617E0";
//Instance objects

pirSensorClass pirSensor(SOCKET_1);


char node_ID[] = "Node_1";
//function declaration
void pirInit(); //waits for pir sensor stabilisation
void createMotionSensorFrame();
void createTHPFrame();
void sendDataToXbee();
void setup()
{
  USB.ON();
  USB.println(F("Frame Utility Example for Events Board 3.0"));

  // Turn on the sensor board
  Events.ON();

  // Set the Waspmote ID
  frame.setID(node_ID);
   xbee802.ON();
  pirInit();
  // Enable interruptions from the board
  Events.attachInt();

  Utils.readSerialID();
  snprintf(NumSerie, sizeof(NumSerie), "%02X%02X%02X%02X%02X%02X%02X%02X", 
    _serial_id[0], 
    _serial_id[1], 
    _serial_id[2], 
    _serial_id[3], 
    _serial_id[4], 
    _serial_id[5], 
    _serial_id[6], 
    _serial_id[7]); 
}


int kadCeTemperatura=0;
void loop()
{
  ///////////////////////////////////////
  // 1. Go to deep sleep mode
  ///////////////////////////////////////
  USB.println(F("enter deep sleep"));
  PWR.deepSleep("00:00:00:10", RTC_OFFSET, RTC_ALM1_MODE1, SENSOR_ON);

  USB.ON();
  USB.println(F("wake up\n"));


  ///////////////////////////////////////
  // 2. Check Interruption Flags
  ///////////////////////////////////////

  // Check interruption from RTC alarm
  if ( intFlag & RTC_INT )
  {
    USB.println(F("-----------------------------"));
    USB.println(F("RTC INT captured"));
    USB.println(F("-----------------------------"));

    // clear flag
    intFlag &= ~(RTC_INT);
  }
  
  // Check interruption from Sensor Board
  if (intFlag & SENS_INT)
  {
    
  interrupt_function();
  }
  kadCeTemperatura++;
  if(kadCeTemperatura%3==0){
     createTHPFrame();
  }
}



/**********************************************

   interrupt_function()

   Local function to treat the sensor interruption


 ***********************************************/
void interrupt_function()
{
  // Disable interruptions from the board
  Events.detachInt();
  // Load the interruption flag
  Events.loadInt();


  if (pirSensor.getInt())
  {
    USB.println(F("--------------------------------------"));
    USB.println(F("Interruption from PIR"));
    USB.println(F("--------------------------------------"));
    createMotionSensorFrame();
  }
 //  createMotionSensorFrame();




  // Read Input 4
 // pirValue = pirSensor.readPirSensor();
pirInit();

  ///////////////////////////////////////////
  // Create ASCII frame
  ///////////////////////////////////////////


  // Pir Sensor

}
void pirInit(){
 uint8_t  value=0;
    value = pirSensor.readPirSensor();
  while (value == 1)
  {
    USB.println(F("...wait for PIR stabilization"));
    delay(1000);
    value = pirSensor.readPirSensor();    
  }
}
void createMotionSensorFrame(){
  ///////////////////////////////////////////
  // Create ASCII frame
  ///////////////////////////////////////////


  // Pir Sensor
  pirCount++;
  frame.createFrame(ASCII);
  frame.addSensor(SENSOR_EVENETS_POC,"");
  frame.addSensor(SENSOR_EVENETS_BOARD_ID,NumSerie);
  frame.addSensor(SENSOR_EVENETS_MOTION_SENSOR,"");
  frame.addSensor(SENSOR_EVENETS_COUNT,pirCount);
  frame.addSensor(SENSOR_EVENTS_PIR, 1);
  frame.addSensor(SENSOR_EVENETS_KRAJ,"\n");
  frame.showFrame();
  sendDataToXbee();
}
void createTHPFrame(){
   ///////////////////////////////////////
  // 1. Read BME280 Values
  ///////////////////////////////////////
  //Temperature
  temp = Events.getTemperature();
  //Humidity
  humd = Events.getHumidity();
  //Pressure
  pres = Events.getPressure();

  
  ///////////////////////////////////////
  // 2. Print BME280 Values
  ///////////////////////////////////////
  USB.println("-----------------------------");
  USB.print("Temperature: ");
  USB.printFloat(temp, 2);
  USB.println(F(" Celsius"));
  USB.print("Humidity: ");
  USB.printFloat(humd, 1); 
  USB.println(F(" %")); 
  USB.print("Pressure: ");
  USB.printFloat(pres, 2); 
  USB.println(F(" Pa")); 
  USB.println("-----------------------------");  
  temperaturaCount++;
  frame.createFrame(ASCII);
  frame.addSensor(SENSOR_EVENETS_POC,"");
  frame.addSensor(SENSOR_EVENETS_BOARD_ID,NumSerie);
  frame.addSensor(SENSOR_EVENETS_TEMPERATURE_SENSOR,"");
  frame.addSensor(SENSOR_EVENETS_COUNT,temperaturaCount);
  frame.addSensor(SENSOR_EVENTS_TC,temp);
  //frame.addSensor(SENSOR_EVENTS_HUM,humd);
  frame.addSensor(SENSOR_EVENETS_KRAJ,"\n");
  frame.showFrame();
  sendDataToXbee();
  vlagaCount++;
    frame.createFrame(ASCII);
  frame.addSensor(SENSOR_EVENETS_POC,"");
  frame.addSensor(SENSOR_EVENETS_BOARD_ID,NumSerie);
  frame.addSensor(SENSOR_EVENETS_HUMIDITY_SENSOR,"");
  frame.addSensor(SENSOR_EVENETS_COUNT,vlagaCount);
  //frame.addSensor(SENSOR_EVENTS_TC,temp);
  frame.addSensor(SENSOR_EVENTS_HUM,humd);
frame.addSensor(SENSOR_EVENETS_KRAJ,"\n");
  frame.showFrame();
   sendDataToXbee();
   tlakCount++;
       frame.createFrame(ASCII);
  frame.addSensor(SENSOR_EVENETS_POC,"");
  frame.addSensor(SENSOR_EVENETS_BOARD_ID,NumSerie);
  frame.addSensor(SENSOR_EVENETS_PRESURE_SENSOR,"");
  frame.addSensor(SENSOR_EVENETS_COUNT,tlakCount);
  //frame.addSensor(SENSOR_EVENTS_TC,temp);
  frame.addSensor(SENSOR_EVENTS_PRES,pres);
  frame.addSensor(SENSOR_EVENETS_KRAJ,"\n");
  frame.showFrame();
  sendDataToXbee();
}
void sendDataToXbee(){
  // send XBee packet
  USB.ON();
  RTC.ON();
  xbee802.ON();
  error = xbee802.send( RX_ADDRESS, frame.buffer, frame.length );   
  
  // check TX flag
  if( error == 0 )
  {
    USB.println(F("send ok"));
    
    // blink green LED
    Utils.blinkGreenLED();
    
  }
  else 
  {
    USB.println(F("send error"));
    
    // blink red LED
    Utils.blinkRedLED();
  }

  // wait for five seconds
  delay(1000);
}


