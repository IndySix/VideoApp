import serial

from RecordMovie import *  

class SerialRecordController:
	# Serial object
	serial 				= None
	# old value of serial
	oldSerialValue 		= None
	# camera port
	cameraPort 			= 0
	# String with full path to the save location dir with / on end
	videoSaveLocation 	= ""
	# video record time
	recordTime 			= 0
	# Video record start time
	startRecordTime 	= 0
	# Detect time
	detectTime			= 0
	# deriction
	direction			= 'R'

	def __init__(self, serialPort, cameraPort, videoSaveLocation, recordTime):
		self.serial 		= serial.Serial(serialPort, 9600, timeout=1)
		self.cameraPort 	= cameraPort
		self.videoSaveLocation = videoSaveLocation
		self.recordTime 	= recordTime
		self.oldSerialValue = self.serial.readline().split(':')

	def RecordCheck(self):
		new_value 		= self.serial.readline().split(':')
		left_old_dist 	= int(self.oldSerialValue[1])
		left_new_dist 	= int(new_value[1])
		right_old_dist 	= int(self.oldSerialValue[2])
		right_new_dist 	= int(new_value[2])
		time_dif 		= (int(new_value[0])) - self.startRecordTime
		detectTime_dif 	= (int(new_value[0])) - self.detectTime
		self.oldSerialValue = new_value

		if(time_dif > self.recordTime):
			if(self.direction == 'L'):
				if(left_new_dist < left_old_dist and left_old_dist-left_new_dist > 40 and detectTime_dif > 250):
					self.detectTime = int(new_value[0]);
				if(right_new_dist < right_old_dist and right_old_dist-right_new_dist > 40 and detectTime_dif < 250):
					print("Left")
					self.startRecordTime = int(new_value[1]);
					return True
			if(self.direction == 'R'):
				if(right_new_dist < right_old_dist and right_old_dist-right_new_dist > 40 and detectTime_dif > 250):
					self.detectTime = int(new_value[0]);
				if(left_new_dist < left_old_dist and left_old_dist-left_new_dist > 40 and detectTime_dif < 250):
					print("Right")
					self.startRecordTime = int(new_value[1]);
					return True
		return False

	def run(self):
		while True:
			if  self.RecordCheck():
		 		# Get Webcam
				camera = cv2.VideoCapture(self.cameraPort)

				# Capture the video
				recordMovie = RecordMovie(camera, self.videoSaveLocation)
				recordMovie.record(self.recordTime)

				# Release the camere and recordMvie
				del(camera)
				del(recordMovie)
