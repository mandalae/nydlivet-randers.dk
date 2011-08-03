/* Demo Note:  This demo uses a FileProgress class that handles the UI for displaying the file name and percent complete.
The FileProgress class is not part of SWFUpload.
*/


/* **********************
   Event Handlers
   These are my custom event handlers to make my
   web application behave the way I went when SWFUpload
   completes different tasks.  These aren't part of the SWFUpload
   package.  They are part of my application.  Without these none
   of the actions SWFUpload makes will show up in my application.
   ********************** */
function fileQueued(file) {
	try {
		this.customSettings.tdFilesQueued.innerHTML = this.getStats().files_queued;
	} catch (ex) {
		this.debug(ex);
	}

}

function fileQueueError(file, errorCode, message) {
	try {
		alert(message);
	} catch (ex) {
		this.debug(ex);
	}

}

function fileDialogComplete(numFilesSelected, numFilesQueued) {
	try {
		if (numFilesQueued > 0) {
			this.startUpload();
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadStart(file) {
	try {
		updateDisplay.call(this, file);
	}
	catch (ex) {
		this.debug(ex);
	}	
}

function uploadProgress(file, bytesLoaded, bytesTotal) {
	try {
		updateDisplay.call(this, file);
	} catch (ex) {
		this.debug(ex);
	}
	
}

function uploadSuccess(file, serverData) {
	try {
		updateDisplay.call(this, file);
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadComplete(file) {
	try {
		/*  I want the next upload to continue automatically so I'll call startUpload here */
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		} else {
			this.customSettings.tdFilesQueued.innerHTML = this.getStats().files_queued;
			this.customSettings.tdFilesUploaded.innerHTML = this.getStats().successful_uploads;
			this.customSettings.tdErrors.innerHTML = this.getStats().upload_errors;
		}
	} catch (ex) {
		this.debug(ex);
	}
}

function uploadError(file, errorCode, message) {
	try {
		switch (errorCode) {
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			try {
				this.customSettings.tdFilesQueued.innerHTML = this.getStats().files_queued;
				this.customSettings.tdFilesUploaded.innerHTML = this.getStats().successful_uploads;
				this.customSettings.tdErrors.innerHTML = this.getStats().upload_errors;
			}
			catch (ex1) {
				this.debug(ex1);
			}
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			try {
				this.customSettings.tdFilesQueued.innerHTML = this.getStats().files_queued;
				this.customSettings.tdFilesUploaded.innerHTML = this.getStats().successful_uploads;
				this.customSettings.tdErrors.innerHTML = this.getStats().upload_errors;
			}
			catch (ex2) {
				this.debug(ex2);
			}
		default:
			break;
		}
		alert(message);
	} catch (ex3) {
		this.debug(ex3);
	}

}

function updateDisplay(file) {
	this.customSettings.tdCurrentSpeed.innerHTML = SWFUpload.speed.formatBPS(file.currentSpeed);
	this.customSettings.tdAverageSpeed.innerHTML = SWFUpload.speed.formatBPS(file.averageSpeed);
	this.customSettings.tdMovingAverageSpeed.innerHTML = SWFUpload.speed.formatBPS(file.movingAverageSpeed);
	this.customSettings.tdTimeRemaining.innerHTML = SWFUpload.speed.formatTime(file.timeRemaining);
	this.customSettings.tdTimeElapsed.innerHTML = SWFUpload.speed.formatTime(file.timeElapsed);
	this.customSettings.tdPercentUploaded.innerHTML = SWFUpload.speed.formatPercent(file.percentUploaded);
	this.customSettings.tdSizeUploaded.innerHTML =SWFUpload.speed.formatBytes(file.sizeUploaded);
}