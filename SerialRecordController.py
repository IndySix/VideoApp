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

	def __init__(self, serialPort, cameraPort, videoSaveLocation, recordTime):
		self.serial 		= serial.Serial(serialPort, 9600, timeout=1)
		self.cameraPort 	= cameraPort
		self.videoSaveLocation = videoSaveLocation
		self.recordTime 	= recordTime
		self.oldSerialValue = self.serial.readline().split(':')

	def RecordCheck(self):
		new_value 	= self.serial.readline().split(':')
		old_dist 	= int(self.oldSerialValue[0])
		new_dist 	= int(new_value[0])
		time_dif 	= (int(new_value[1])) - self.startRecordTime
		self.oldSerialValue = new_value
		print(new_value)
		if(new_dist < old_dist and old_dist-new_dist > 40 and time_dif > self.recordTime):
			print("Start webcam old distance: "+str(old_dist)+"; new distance: "+str(new_dist))
			self.startRecordTime = int(new_value[1]);
			return True
		else:
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
