function initImageUpload(){
	if ($("#spanButtonPlaceholder").length > 0){
		swfu = new SWFUpload({
					upload_url: "/imagedb/upload/upload",	// Relative to the SWF file
					post_params: {},

					// File Upload Settings
					file_size_limit : "30720",	// 30MB
					file_types : "*.jpg;*.png;*.jpeg;*.gif",
					file_types_description : "Images",
					file_upload_limit : "0",

					// Event Handler Settings - these functions as defined in Handlers.js
					//  The handlers are not part of SWFUpload but are part of my website and control how
					//  my website reacts to the SWFUpload events.
					file_queue_error_handler : fileQueueError,
					file_dialog_complete_handler : fileDialogComplete,
					upload_progress_handler : uploadProgress,
					upload_error_handler : uploadError,
					upload_success_handler : uploadSuccess,
					upload_complete_handler : uploadComplete,

					// Button Settings
					button_placeholder_id : "spanButtonPlaceholder",
					button_width: 180,
					button_height: 18,
					button_text : '<span class="button">Select Images <span class="buttonSmall">(30 MB Max)</span></span>',
					button_text_style : '.button { font-family: Helvetica, Arial, sans-serif; font-size: 12pt; } .buttonSmall { font-size: 10pt; }',
					button_text_top_padding: 0,
					button_text_left_padding: 18,

					// Flash Settings
					flash_url : "/media/swfupload/swfupload.swf",	// Relative to this file

					custom_settings : {
						upload_target : "divFileProgressContainer"
					},

					// Debug Settings
					debug: false
				});
	}


}