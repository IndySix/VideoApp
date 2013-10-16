#!/usr/bin/python
from SerialRecordController import * 
import os 
 
# Camera 0 is the integrated web cam on my netbook
camera_port 	= 0

# Number of seconds filming
video_time 		= 5

# video file
video_save_path = "/opt/lampp/htdocs/webApp/movies/"

if(not os.access('video_save_path', os.W_OK)):
	print("Program does not have write permission to "+video_save_path)
	raise SystemExit(0)

#Serial port
serial_port 	= "/dev/ttyACM0"

# Run SerialRecordController
serController = SerialRecordController(serial_port, camera_port, video_save_path, video_time)
serController.run()
