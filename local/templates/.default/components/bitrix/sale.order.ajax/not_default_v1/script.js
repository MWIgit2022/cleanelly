BX.saleOrderAjax = { // bad solution, actually, a singleton at the page

	BXCallAllowed: false,

	options: {},
	indexCache: {},
	controls: {},

	modes: {},
	properties: {},

	// called once, on component load
	init: function(options)
	{
		var ctx = this;
		this.options = options;

		window.submitFormProxy = BX.proxy(function(){
			ctx.submitFormProxy.apply(ctx, arguments);
		}, this);

		BX(function(){
			ctx.initDeferredControl();
		});
		BX(function(){
			ctx.BXCallAllowed = true; // unlock form refresher
		});

		this.controls.scope = BX('bx-soa-order');

		// user presses "add location" when he cannot find location in popup mode
		BX.bindDelegate(this.controls.scope, 'click', {className: '-bx-popup-set-mode-add-loc'}, function(){

			var input = BX.create('input', {
				attrs: {
					type: 'hidden',
					name: 'PERMANENT_MODE_STEPS',
					value: '1'
				}
			});

			BX.prepend(input, BX('bx-soa-order'));

			ctx.BXCallAllowed = false;
			BX.Sale.OrderAjaxComponent.sendRequest();
		});
	},

	cleanUp: function(){

		for(var k in this.properties)
		{
			if (this.properties.hasOwnProperty(k))
			{
				if(typeof this.properties[k].input != 'undefined')
				{
					BX.unbindAll(this.properties[k].input);
					this.properties[k].input = null;
				}

				if(typeof this.properties[k].control != 'undefined')
					BX.unbindAll(this.properties[k].control);
			}
		}

		this.properties = {};
	},

	addPropertyDesc: function(desc){
		this.properties[desc.id] = desc.attributes;
		this.properties[desc.id].id = desc.id;
	},

	// called each time form refreshes
	initDeferredControl: function()
	{
		var ctx = this,
			k,
			row,
			input,
			locPropId,
			m,
			control,
			code,
			townInputFlag,
			adapter;

		// first, init all controls
		if(typeof window.BX.locationsDeferred != 'undefined'){

			this.BXCallAllowed = false;

			for(k in window.BX.locationsDeferred){

				window.BX.locationsDeferred[k].call(this);
				window.BX.locationsDeferred[k] = null;
				delete(window.BX.locationsDeferred[k]);

				this.properties[k].control = window.BX.locationSelectors[k];
				delete(window.BX.locationSelectors[k]);
			}
		}

		for(k in this.properties){

			// zip input handling
			if(this.properties[k].isZip){
				row = this.controls.scope.querySelector('[data-property-id-row="'+k+'"]');
				if(BX.type.isElementNode(row)){

					input = row.querySelector('input[type="text"]');
					if(BX.type.isElementNode(input)){
						this.properties[k].input = input;

						// set value for the first "location" property met
						locPropId = false;
						for(m in this.properties){
							if(this.properties[m].type == 'LOCATION'){
								locPropId = m;
								break;
							}
						}

						if(locPropId !== false){
							BX.bindDebouncedChange(input, function(value){

								var zipChangedNode = BX('ZIP_PROPERTY_CHANGED');
								zipChangedNode && (zipChangedNode.value = 'Y');

								input = null;
								row = null;

								if(BX.type.isNotEmptyString(value) && /^\s*\d+\s*$/.test(value) && value.length > 3){

									ctx.getLocationsByZip(value, function(locationsData){
										ctx.properties[locPropId].control.setValueByLocationIds(locationsData);
									}, function(){
										try{
											// ctx.properties[locPropId].control.clearSelected();
										}catch(e){}
									});
								}
							});
						}
					}
				}
			}

			// location handling, town property, etc...
			if(this.properties[k].type == 'LOCATION')
			{

				if(typeof this.properties[k].control != 'undefined'){

					control = this.properties[k].control; // reference to sale.location.selector.*
					code = control.getSysCode();

					// we have town property (alternative location)
					if(typeof this.properties[k].altLocationPropId != 'undefined')
					{
						if(code == 'sls') // for sale.location.selector.search
						{
							// replace default boring "nothing found" label for popup with "-bx-popup-set-mode-add-loc" inside
							control.replaceTemplate('nothing-found', this.options.messages.notFoundPrompt);
						}

						if(code == 'slst')  // for sale.location.selector.steps
						{
							(function(k, control){

								// control can have "select other location" option
								control.setOption('pseudoValues', ['other']);

								// insert "other location" option to popup
								control.bindEvent('control-before-display-page', function(adapter){

									control = null;

									var parentValue = adapter.getParentValue();

									// you can choose "other" location only if parentNode is not root and is selectable
									if(parentValue == this.getOption('rootNodeValue') || !this.checkCanSelectItem(parentValue))
										return;

									var controlInApater = adapter.getControl();

									if(typeof controlInApater.vars.cache.nodes['other'] == 'undefined')
									{
										controlInApater.fillCache([{
											CODE:		'other', 
											DISPLAY:	ctx.options.messages.otherLocation, 
											IS_PARENT:	false,
											VALUE:		'other'
										}], {
											modifyOrigin:			true,
											modifyOriginPosition:	'prepend'
										});
									}
								});

								townInputFlag = BX('LOCATION_ALT_PROP_DISPLAY_MANUAL['+parseInt(k)+']');

								control.bindEvent('after-select-real-value', function(){

									// some location chosen
									if(BX.type.isDomNode(townInputFlag))
										townInputFlag.value = '0';
								});
								control.bindEvent('after-select-pseudo-value', function(){

									// option "other location" chosen
									if(BX.type.isDomNode(townInputFlag))
										townInputFlag.value = '1';
								});

								// when user click at default location or call .setValueByLocation*()
								control.bindEvent('before-set-value', function(){
									if(BX.type.isDomNode(townInputFlag))
										townInputFlag.value = '0';
								});

								// restore "other location" label on the last control
								if(BX.type.isDomNode(townInputFlag) && townInputFlag.value == '1'){

									// a little hack: set "other location" text display
									adapter = control.getAdapterAtPosition(control.getStackSize() - 1);

									if(typeof adapter != 'undefined' && adapter !== null)
										adapter.setValuePair('other', ctx.options.messages.otherLocation);
								}

							})(k, control);
						}
					}
				}
			}
		}

		this.BXCallAllowed = true;

		//set location initialized flag and refresh region & property actual content
		if (BX.Sale.OrderAjaxComponent)
			BX.Sale.OrderAjaxComponent.locationsCompletion();
	},

	checkMode: function(propId, mode){

		//if(typeof this.modes[propId] == 'undefined')
		//	this.modes[propId] = {};

		//if(typeof this.modes[propId] != 'undefined' && this.modes[propId][mode])
		//	return true;

		if(mode == 'altLocationChoosen'){

			if(this.checkAbility(propId, 'canHaveAltLocation')){

				var input = this.getInputByPropId(this.properties[propId].altLocationPropId);
				var altPropId = this.properties[propId].altLocationPropId;

				if(input !== false && input.value.length > 0 && !input.disabled && this.properties[altPropId].valueSource != 'default'){

					//this.modes[propId][mode] = true;
					return true;
				}
			}
		}

		return false;
	},

	checkAbility: function(propId, ability){

		if(typeof this.properties[propId] == 'undefined')
			this.properties[propId] = {};

		if(typeof this.properties[propId].abilities == 'undefined')
			this.properties[propId].abilities = {};

		if(typeof this.properties[propId].abilities != 'undefined' && this.properties[propId].abilities[ability])
			return true;

		if(ability == 'canHaveAltLocation'){

			if(this.properties[propId].type == 'LOCATION'){

				// try to find corresponding alternate location prop
				if(typeof this.properties[propId].altLocationPropId != 'undefined' && typeof this.properties[this.properties[propId].altLocationPropId]){

					var altLocPropId = this.properties[propId].altLocationPropId;

					if(typeof this.properties[propId].control != 'undefined' && this.properties[propId].control.getSysCode() == 'slst'){

						if(this.getInputByPropId(altLocPropId) !== false){
							this.properties[propId].abilities[ability] = true;
							return true;
						}
					}
				}
			}

		}

		return false;
	},

	getInputByPropId: function(propId){
		if(typeof this.properties[propId].input != 'undefined')
			return this.properties[propId].input;

		var row = this.getRowByPropId(propId);
		if(BX.type.isElementNode(row)){
			var input = row.querySelector('input[type="text"]');
			if(BX.type.isElementNode(input)){
				this.properties[propId].input = input;
				return input;
			}
		}

		return false;
	},

	getRowByPropId: function(propId){

		if(typeof this.properties[propId].row != 'undefined')
			return this.properties[propId].row;

		var row = this.controls.scope.querySelector('[data-property-id-row="'+propId+'"]');
		if(BX.type.isElementNode(row)){
			this.properties[propId].row = row;
			return row;
		}

		return false;
	},

	getAltLocPropByRealLocProp: function(propId){
		if(typeof this.properties[propId].altLocationPropId != 'undefined')
			return this.properties[this.properties[propId].altLocationPropId];

		return false;
	},

	toggleProperty: function(propId, way, dontModifyRow){

		var prop = this.properties[propId];

		if(typeof prop.row == 'undefined')
			prop.row = this.getRowByPropId(propId);

		if(typeof prop.input == 'undefined')
			prop.input = this.getInputByPropId(propId);

		if(!way){
			if(!dontModifyRow)
				BX.hide(prop.row);
			prop.input.disabled = true;
		}else{
			if(!dontModifyRow)
				BX.show(prop.row);
			prop.input.disabled = false;
		}
	},

	submitFormProxy: function(item, control)
	{
		var propId = false;
		for(var k in this.properties){
			if(typeof this.properties[k].control != 'undefined' && this.properties[k].control == control){
				propId = k;
				break;
			}
		}

		// turning LOCATION_ALT_PROP_DISPLAY_MANUAL on\off

		if(item != 'other'){

			if(this.BXCallAllowed){

				this.BXCallAllowed = false;
				setTimeout(function(){BX.Sale.OrderAjaxComponent.sendRequest()}, 20);
			}

		}
	},

	getPreviousAdapterSelectedNode: function(control, adapter){

		var index = adapter.getIndex();
		var prevAdapter = control.getAdapterAtPosition(index - 1);

		if(typeof prevAdapter !== 'undefined' && prevAdapter != null){
			var prevValue = prevAdapter.getControl().getValue();

			if(typeof prevValue != 'undefined'){
				var node = control.getNodeByValue(prevValue);

				if(typeof node != 'undefined')
					return node;

				return false;
			}
		}

		return false;
	},
	getLocationsByZip: function(value, successCallback, notFoundCallback)
	{
		if(typeof this.indexCache[value] != 'undefined')
		{
			successCallback.apply(this, [this.indexCache[value]]);
			return;
		}

		var ctx = this;

		BX.ajax({
			url: this.options.source,
			method: 'post',
			dataType: 'json',
			async: true,
			processData: true,
			emulateOnload: true,
			start: true,
			data: {'ACT': 'GET_LOCS_BY_ZIP', 'ZIP': value},
			//cache: true,
			onsuccess: function(result){
				if(result.result)
				{
					ctx.indexCache[value] = result.data;
					successCallback.apply(ctx, [result.data]);
				}
				else
				{
					notFoundCallback.call(ctx);
				}
			},
			onfailure: function(type, e){
				// on error do nothing
			}
		});
	}
};
/* $('body').ready(function(){
	if(!($.cookie('current_region')))
	{
		if(!($('select[name="PROFILE_ID"]').length))
		{
			setTimeout(function() {
				$('.bx-ui-sls-clear').click();
			}, 2000); 
		}
	}
});  */

$('body').on('change', 'select[name="PROFILE_ID"]', function() {
	var a = $('select[name="PROFILE_ID"]').val();
	if( a == 0 )
	{
		setTimeout(function() {
			$('.bx-ui-sls-clear').click();
		}, 2000); 
	}
});

$(document).ready(function(){
	$.fn.setCursorPosition = function(pos) {
		if (this.val()[3] != '_') return;
		if ($(this).get(0).setSelectionRange) {
		  $(this).get(0).setSelectionRange(pos, pos);
		} else if ($(this).get(0).createTextRange) {
		  var range = $(this).get(0).createTextRange();
		  range.collapse(true);
		  range.moveEnd('character', pos);
		  range.moveStart('character', pos);
		  range.select();
		}
		if(!$('.appendig_adress').length){
			$('div[data-property-id-row="7"]').append('<div class="appendig_adress"></div>');
		}
	  };
	 
	total_coupon = $('#bx-soa-total .bx-soa-cart-total .change_basket')[0].outerHTML;
	 BX.addCustomEvent('onAjaxSuccess', function(){
		 setTimeout(function(){
			if(!$('#bx-soa-total .bx-soa-cart-total .change_basket').length){
				$('#bx-soa-total .bx-soa-cart-total').prepend(window.total_coupon);
				$('#bx-soa-total-mobile .bx-soa-cart-total').prepend(window.total_coupon);
			} else {
				total_coupon = $('#bx-soa-total .bx-soa-cart-total .change_basket')[0].outerHTML;
			}
			if(!$('.appendig_adress').length){
				$('div[data-property-id-row="7"]').append('<div class="appendig_adress"></div>');
			}
		 }, 200);
		 $('#ID_DELIVERY_ID_53').closest('.bx-soa-pp-company').hide();
		  if(window.SDEK_CUSTOM){
			  IPOLSDEK_pvz.selectPVZ(window.SDEK_CUSTOM[1],window.SDEK_CUSTOM[0]); 
			 $('#ID_DELIVERY_ID_52').closest('.bx-soa-pp-company').addClass('bx-selected-fake');
			 window.SDEK_CUSTOM = false;
		  }
		  if( $('#ID_DELIVERY_ID_53').closest('.bx-soa-pp-company').hasClass('bx-selected')){
			 $('#ID_DELIVERY_ID_52').closest('.bx-soa-pp-company').addClass('bx-selected-fake'); 
		  }
     });
	 $('#ID_DELIVERY_ID_53').closest('.bx-soa-pp-company').hide();
	 if($('#ID_DELIVERY_ID_53').closest('.bx-soa-pp-company').hasClass('bx-selected')){
			 $('#ID_DELIVERY_ID_52').closest('.bx-soa-pp-company').addClass('bx-selected'); 
	 }
	 if(!$('.appendig_adress').length){
		$('div[data-property-id-row="7"]').append('<div class="appendig_adress"></div>');
	}
})

$(document).on('click', '.order-promocode-block-promocode-btn', function(){
	var coupon = $(this).parent().find('.promocode-input').val();
	BX.Sale.OrderAjaxComponent.sendRequest('enterCoupon', coupon);
})
function numberWithCommas(x) {return x.toFixed(0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ")} 

$(document).on('focus','[name="ORDER_PROP_7"]', function(){
	if($('div[data-property-id-row="5"] input').val()) {
		window.city = $('div[data-property-id-row="5"] input').val();
	}
								
})
$(document).on('focusout','[name="ORDER_PROP_7"]', function(){
	$th = $(this);
	setTimeout(function(){
		if(window.locations_dadata && $th.val() != window.location_found_dadata){
			if($th.parent().find('.dadata_loc_error').length>0){
				$th.parent().find('.dadata_loc_error').text('Пожалуйста, выбирете из списка!').css('display','block');
			} else {
				$th.parent().prepend('<span class="dadata_loc_error">Пожалуйста, выбирете из списка!</span>');
			}
			$th.css('color', 'red').css('border', '1px solid').trigger('keyup');
		}
	},200);
})
$(document).on('keyup', '[name=ORDER_PROP_7]', function () {
	var token = "e01ad93c1cc25a045b3aefcdfee5a8e8aef8070d"; //dadata
	let addressField = $(this);
	let url = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address";
	if(window.city){
		var city = window.city;
		let queryCity = $(this).val();
		var options = {
			method: "POST",
			mode: "cors",
			headers: {
				"Content-Type": "application/json",
				"Accept": "application/json",
				"Authorization": "Token " + token
			},
			body: JSON.stringify({
				"query": queryCity,
				"locations": [{
				"city": city
				}]
			})
		}

		fetch(url, options)
		.then(response => response.text())
		.then(result => {
			let res = JSON.parse(result);
			$('.appendig_adress').html('');
			if (res.suggestions.length != 0) {
				window.locations_dadata = true;
				res.suggestions.forEach(function(item, i, arr) {
					var house = item.data.house;
					if(item.data.block){
						house += ' стр. '+item.data.block;
					}
					$('.appendig_adress').append('<a href="javascript:void(0)" data-street="'+item.data.street_with_type+'" data-house="'+house+'"  data-flat="'+item.data.flat+'">'+ item.value +'</a>');
					$('.appendig_adress').show();
				});

				$(document).on('click', '.appendig_adress a', function(){
					$('[name=ORDER_PROP_7]').val($(this).text()).focus();
					$('[name=ORDER_PROP_7]').removeAttr('style');
					window.location_found_dadata = $(this).text();
					$('.dadata_loc_error').hide();
					$('#soa-property-25').val($(this).data('house'));
					$('#soa-property-24').val($(this).data('street'));
					$('#soa-property-26').val($(this).data('flat'));
					$('.address_variants').hide();
				})
				$(document).on('click', 'body', function(){
					$('.appendig_adress').hide();
				})
			} else {
				$('.appendig_adress').hide();
				window.locations_dadata = false;
			}
		})
		.catch(error => console.log("error", error));
	}
});

$(document).on('focus', '#soa-property-31', function(){
	$(this).inputmask("99.99.9999", {
		"placeholder": ' '
	});
})

$(document).on('focus', 'input[name=ORDER_PROP_3]', function(){
	$(this).inputmask("+7 999 999-9999");
})
$(document).on('keypress', 'input[name=ORDER_PROP_3]', function(){
	if (($('input[name=ORDER_PROP_3').val()[3] == '8' || $('input[name=ORDER_PROP_3').val()[3] == '7') && $('input[name=ORDER_PROP_3').val()[$('input[name=ORDER_PROP_3').val().length-1] != '_') {
		var newPhone = $('input[name=ORDER_PROP_3').val().substr(0,3) + $('input[name=ORDER_PROP_3').val().substr(4,$('input[name=ORDER_PROP_3').val().length);
		newPhone = newPhone.replace(/ |-/g, '');
		newPhone = newPhone.substr(0,2) + ' ' + newPhone.substr(2,3) + ' ' + newPhone.substr(5,3) + '-' + newPhone.substr(8,4);
		$('input[name=ORDER_PROP_3').val(newPhone);
		$('div[data-property-id-row=3]').removeClass('has-error')
		$('.tooltip-inner' ,'div[data-property-id-row=3]').hide();
	}					
})
$(document).on('change', 'input[name=ORDER_PROP_3]', function(){
	if ($('input[name=ORDER_PROP_3').val()[3]) {
		$('div[data-property-id-row=3]').removeClass('has-error')
		$('.tooltip-inner' ,'div[data-property-id-row=3]').hide();
	} else {
		$('div[data-property-id-row=3]').addClass('has-error')
		$('.tooltip-inner' ,'div[data-property-id-row=3]').show();						
	}
})