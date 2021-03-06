;(function()
{
	if(BX.ImConferenceController)
	{
		return;
	}

	BX.ImConferenceController = function(config)
	{
		this.callEngine = BX.Call.Engine.getInstance();

		this.callFields = JSON.parse(config.callFields);
		this.callUsers = JSON.parse(config.callUsers);

		this.publicIds = JSON.parse(config.publicIds);

		this.currentCall = this.callEngine.getCall(this.callFields, this.callUsers);

		this.callView = new BX.Call.View({
			container: config.viewContainer,
			users: this.callUsers,
			userStates: this.currentCall.getUsers()
		});

		this.init();

		this.callView.show();
	};

	BX.ImConferenceController.prototype.init = function()
	{
		this.currentCall.addEventListener(BX.Call.Event.onDestroy, this._onCallDestroy.bind(this));
		this.currentCall.addEventListener(BX.Call.Event.onUserStateChanged, this._onCallUserStateChanged.bind(this));
		this.currentCall.addEventListener(BX.Call.Event.onLocalMediaReceived, this._onCallLocalMediaReceived.bind(this));
		this.currentCall.addEventListener(BX.Call.Event.onStreamReceived, this._onCallUserStreamReceived.bind(this));
		this.currentCall.addEventListener(BX.Call.Event.onUserVoiceStarted, this._onCallUserVoiceStarted.bind(this));
		this.currentCall.addEventListener(BX.Call.Event.onUserVoiceStopped, this._onCallUserVoiceStopped.bind(this));
		this.currentCall.addEventListener(BX.Call.Event.onDeviceListUpdated, this._onCallDeviceListUpdated.bind(this));

		this.callView.setCallback('onClose', this._onCallViewClose.bind(this));
		this.callView.setCallback('onDestroy', this._onCallViewDestroy.bind(this));
		this.callView.setCallback('onButtonClick', this._onCallViewButtonClick.bind(this));
		this.callView.setCallback('onReplaceCamera', this._onCallViewReplaceCamera.bind(this));
		this.callView.setCallback('onReplaceMicrophone', this._onCallViewReplaceMicrophone.bind(this));

		this.currentCall.setVideoEnabled(true);
		this.currentCall.startMediaCapture();
	};


	BX.ImConferenceController.prototype._onCallDestroy = function(e)
	{
		this.currentCall = null;

		if(this.callView)
		{
			this.callView.close();
		}

		// todo
		alert('The call is finished');
	};

	BX.ImConferenceController.prototype._onCallUserStateChanged = function(e)
	{
		if(this.callView)
		{
			this.callView.setUserState(e.userId, e.state);
		}
	};

	BX.ImConferenceController.prototype._onCallLocalMediaReceived = function(e)
	{
		if(e.tag == 'main')
		{
			this.callView.setLocalStream(e.stream);
		}
	};

	BX.ImConferenceController.prototype._onCallUserStreamReceived = function(e)
	{
		if(this.callView)
		{
			this.callView.setStream(e.userId, e.stream);
		}
	};

	BX.ImConferenceController.prototype._onCallUserVoiceStarted = function(e)
	{
		if(this.callView)
		{
			this.callView.setUserTalking(e.userId, true);
		}
	};

	BX.ImConferenceController.prototype._onCallUserVoiceStopped = function(e)
	{
		if(this.callView)
		{
			this.callView.setUserTalking(e.userId, false);
		}
	};

	BX.ImConferenceController.prototype._onCallDeviceListUpdated = function(e)
	{
		if(this.callView)
		{
			this.callView.setDeviceList(e.deviceList);
		}
	};

	BX.ImConferenceController.prototype._onCallViewClose = function(e)
	{
		this.callView.destroy();
	};

	BX.ImConferenceController.prototype._onCallViewDestroy = function(e)
	{
		this.callView = null;
	};

	BX.ImConferenceController.prototype._onCallViewButtonClick = function(e)
	{
		var buttonName = e.buttonName;

		var handlers = {
			toggleMute: this._onCallViewToggleMuteButtonClick.bind(this),
			toggleVideo: this._onCallViewToggleVideoButtonClick.bind(this),
		};

		if(BX.type.isFunction(handlers[buttonName]))
		{
			handlers[buttonName].call(this, e);
		}
	};

	BX.ImConferenceController.prototype._onCallViewReplaceCamera = function(e)
	{
		if(this.currentCall)
		{
			this.currentCall.setCameraId(e.deviceId);
		}
	};

	BX.ImConferenceController.prototype._onCallViewReplaceMicrophone = function(e)
	{
		if(this.currentCall)
		{
			this.currentCall.setMicrophoneId(e.deviceId)
		}
	};

	BX.ImConferenceController.prototype._onCallViewToggleMuteButtonClick = function(e)
	{
		this.currentCall.setMuted(e.muted);
	};

	BX.ImConferenceController.prototype._onCallViewToggleVideoButtonClick = function(e)
	{
		this.currentCall.setVideoEnabled(e.video);
	};

})();