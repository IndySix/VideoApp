#!/usr/bin/python
from SerialRecordController import *  
 
# Camera 0 is the integrated web cam on my netbook
camera_port 	= 0

# Number of seconds filming
video_time 		= 5

# video file
video_save_path = "/home/maikel/Documents/movies/"

#Serial port
serial_port 	= "/dev/ttyACM0"

# Run SerialRecordController
serController = SerialRecordController(serial_port, camera_port, video_save_path, video_time)
serController.run()