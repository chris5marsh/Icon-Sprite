$(function() {
	var iS = {};
	iS.init = function() {
		iS.children = $('li ul');
		iS.grandchildren = iS.children.children('li');
		iS.shown = {
			'width':'auto',
			'height':'auto',
			'padding-top':iS.grandchildren.css('padding-top'),
			'padding-right':iS.grandchildren.css('padding-right'),
			'padding-bottom':iS.grandchildren.css('padding-bottom'),
			'padding-left':iS.grandchildren.css('padding-left'),
			'border-top-width':iS.grandchildren.css('border-top-width'),
			'border-right-width':iS.grandchildren.css('border-right-width'),
			'border-bottom-width':iS.grandchildren.css('border-bottom-width'),
			'border-left-width':iS.grandchildren.css('border-left-width')
		};
		iS.hidden = {'width':0, 'height':0, 'padding':0, 'border-width':0};
		iS.parents = iS.children.parent();
		iS.grandchildren.css(iS.hidden).hide();
		// Set up checkboxes for parents and dropdowns
		iS.parents.each(function(i, v) {
			var li = $(this),
					label = li.children('label'),
					checkbox = $(document.createElement('input')).attr({'type':'checkbox'}).click(iS.selectChildren),
					dropdown = $(document.createElement('a')).addClass('dropdown').click(iS.toggleChild);
			li.addClass('parent');
			label.before(checkbox).after(dropdown);
		});
		// Set up 'Select all', 'Select none' and 'Select group' boxes
		var selectDiv = $(document.createElement('ul')).attr({'id':'select-buttons'}),
				selectAllInput = $(document.createElement('input')).attr({'type':'radio', 'name':'select-button', 'id':'select-button-all', 'value':'Select All'}).click(iS.selectAll),
				selectAllLabel = $(document.createElement('label')).attr('for','select-button-all').html('<img src="/img/silk/accept.png" alt="Select All" /> Select All'),
				selectNoneInput = $(document.createElement('input')).attr({'type':'radio', 'name':'select-button', 'id':'select-button-none', 'value':'Select None'}).click(iS.selectNone),
				selectNoneLabel = $(document.createElement('label')).attr('for','select-button-none').html('<img src="/img/silk/delete.png" alt="Select None" /> Select None'),
				selectSomeInput = $(document.createElement('input')).attr({'type':'radio', 'name':'select-button', 'id':'select-button-some', 'value':'Select Some'}).click(iS.selectSome),
				selectSomeLabel = $(document.createElement('label')).attr('for','select-button-some').html('<img src="/img/silk/help.png" alt="Select Some" /> Select Some'),
				selectAll = $(document.createElement('li')).append(selectAllInput, selectAllLabel),
				selectNone = $(document.createElement('li')).append(selectNoneInput, selectNoneLabel),
				selectSome = $(document.createElement('li')).append(selectSomeInput, selectSomeLabel);
				selectDiv.append(selectAll, selectNone, selectSome);
				$('#iconform').before(selectDiv);
		// Custom sets - suggestions: music, code, text editor,
		iS.iconset = {};
		iS.iconset[0] = ['accept', 'add', 'cancel', 'cross', 'delete', 'error', 'exclamation', 'help', 'information', 'stop', 'tick'];
		//iS.iconset['Web Development'] = ['accept', 'add', 'cancel', 'cross', 'delete', 'error', 'exclamation', 'help', 'information', 'stop', 'tick'];
		//iS.iconset['Music'] = ['accept', 'add', 'cancel', 'cross', 'delete', 'error', 'exclamation', 'help', 'information', 'stop', 'tick'];
		//iS.iconset['Txt Editor'] = ['accept', 'add', 'cancel', 'cross', 'delete', 'error', 'exclamation', 'help', 'information', 'stop', 'tick'];
	};
	iS.selectChildren = function(e) {
		$(this).siblings('ul').children('li').children('input').click();
	};
	iS.toggleChild = function(e) {
		var dropdown = $(this),
				children = dropdown.siblings('ul').children('li')
		if (dropdown.hasClass('open')) {
			dropdown.removeClass('open');
			children.css(iS.hidden).hide();
		} else {
			dropdown.addClass('open');
			children.show().css(iS.shown);
		}
	}
	iS.selectAll = function(e) {
		$('#iconform input[type=checkbox]').attr('checked','checked');
	};
	iS.selectNone = function(e) {
		$('#iconform input[type=checkbox]').removeAttr('checked');
	};
	iS.selectSome = function(e) {
		$.each(iS.iconset[0], function(i,v) {
			console.log(v);
			$('#iconform input[type=checkbox][name='+v+']').attr('checked','checked');
		});
	};
	iS.init();
});