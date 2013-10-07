import cv2
import time

class RecordMovie:
	# String with full path to the save location dir with / on end
	videoSaveLocation 	= ""
	# CV2 videoCapture object
	camera 				= None
	# String with video file name
	videoFileName 		= ""
	# String with screencap image file name
	pngFileName			= ""

	def __init__(self, camera, videoSaveLocation):
		self.camera = camera
		self.videoSaveLocation = videoSaveLocation

	# Record the video
	def record(self, recordTime):
		filename = time.strftime("%Y-%m-%d-%H-%M-%S", time.gmtime(time.time() ))
		self.videoFileName 	= filename+".ogg"
		self.pngFileName	= filename+".png"

		# Setup the videoWriter
		video = self.getvideoWriter()

		# Capture the video
		startTime = time.time()
		screencap = None
		while (time.time()-startTime < recordTime):
			frame = self.getCameraImage()
		 	video.write(frame)
		 	if screencap is None and time.time()-startTime > recordTime/2:
		 		screencap = frame

		# Write image screencap
		cv2.imwrite(self.videoSaveLocation+"screencap/"+self.pngFileName,screencap,) 
		# Release the video
		video.release()

	# Captures a single image from the camera and returns it in PIL format
	def getCameraImage(self):
		retval, im = self.camera.read()
		return im

	def getvideoWriter(self):
		height , width , layers =  self.getCameraImage().shape
		return cv2.VideoWriter(self.videoSaveLocation+self.videoFileName, cv2.cv.CV_FOURCC('t', 'h', 'e', 'o'),24,(width,height)) 