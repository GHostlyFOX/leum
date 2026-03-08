(function($) {
	"use strict";

	// For APP-SIDEBAR
	if (document.querySelector('.app-sidebar')) {
		new PerfectScrollbar('.app-sidebar', {
		  useBothWheelAxes:true,
		  suppressScrollX:true,
		});
	}

	// For Header Message dropdown
	if (document.querySelector('.message-menu')) {
		new PerfectScrollbar('.message-menu', {
			useBothWheelAxes:true,
			suppressScrollX:true,
		});
	}

	// For Header Notification dropdown
	if (document.querySelector('.notifications-menu')) {
		new PerfectScrollbar('.notifications-menu', {
			useBothWheelAxes:true,
			suppressScrollX:true,
		});
	}

	// For Header Cart dropdown
	if (document.querySelector('.cart-menu')) {
		new PerfectScrollbar('.cart-menu', {
			useBothWheelAxes:true,
			suppressScrollX:true,
		});
	}

})(jQuery);