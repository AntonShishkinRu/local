BX.namespace('BX.Sale.OrderAjaxComponentExt');

(function() {
	'use strict';
	
	var initParent = BX.Sale.OrderAjaxComponent.init,
        getBlockFooterParent = BX.Sale.OrderAjaxComponent.getBlockFooter,
        editOrderParent = BX.Sale.OrderAjaxComponent.editOrder
        ;
	
	
    BX.namespace('BX.Sale.OrderAjaxComponentExt');    
    BX.Sale.OrderAjaxComponentExt = BX.Sale.OrderAjaxComponent;
	
	// разворачиваем все секции навсегда
	BX.Sale.OrderAjaxComponentExt.editSection = function(section)
	{
		if (!section || !section.id)
			return;

		if (this.result.SHOW_AUTH && section.id != this.authBlockNode.id && section.id != this.basketBlockNode.id)
			section.style.display = 'none';
		else if (section.id != this.pickUpBlockNode.id)
			section.style.display = '';

	//	var active = section.id == this.activeSectionId,
		var active = true,
			titleNode = section.querySelector('.bx-soa-section-title-container'),
			editButton, errorContainer;
	/*
		BX.unbindAll(titleNode);
		if (this.result.SHOW_AUTH)
		{
			BX.bind(titleNode, 'click', BX.delegate(function(){
				this.animateScrollTo(this.authBlockNode);
				this.addAnimationEffect(this.authBlockNode, 'bx-step-good');
			}, this));
		}
		else
		{
			BX.bind(titleNode, 'click', BX.proxy(this.showByClick, this));
			editButton = titleNode.querySelector('.bx-soa-editstep');
			editButton && BX.bind(editButton, 'click', BX.proxy(this.showByClick, this));
		}
*/
		errorContainer = section.querySelector('.alert.alert-danger');
		this.hasErrorSection[section.id] = errorContainer && errorContainer.style.display != 'none';

		switch (section.id)
		{
			case this.authBlockNode.id:
				this.editAuthBlock();
				break;
			case this.basketBlockNode.id:
				this.editBasketBlock(active);
				break;
			case this.regionBlockNode.id:
				this.editRegionBlock(active);
				break;
			case this.paySystemBlockNode.id:
				this.editPaySystemBlock(active);
				break;
			case this.deliveryBlockNode.id:
				this.editDeliveryBlock(active);
				break;
			case this.pickUpBlockNode.id:
				this.editPickUpBlock(active);
				break;
			case this.propsBlockNode.id:
				this.editPropsBlock(active);
				break;
		}

		if (active)
			section.setAttribute('data-visited', 'true');
	};
	
	
	
	
	
	
	
	BX.Sale.OrderAjaxComponentExt.locationsCompletion = function()
	{
		var i, locationNode, clearButton, inputStep, inputSearch,
			arProperty, data, section;

		this.locationsInitialized = true;
		this.fixLocationsStyle(this.regionBlockNode, this.regionHiddenBlockNode);
		this.fixLocationsStyle(this.propsBlockNode, this.propsHiddenBlockNode);

		for (i in this.locations)
		{
			if (!this.locations.hasOwnProperty(i))
				continue;

			locationNode = this.orderBlockNode.querySelector('div[data-property-id-row="' + i + '"]');
			if (!locationNode)
				continue;

			clearButton = locationNode.querySelector('div.bx-ui-sls-clear');
			inputStep = locationNode.querySelector('div.bx-ui-slst-pool');
			inputSearch = locationNode.querySelector('input.bx-ui-sls-fake[type=text]');

			locationNode.removeAttribute('style');
			this.bindValidation(i, locationNode);
			if (clearButton)
			{
				BX.bind(clearButton, 'click', function(e){
					var target = e.target || e.srcElement,
						parent = BX.findParent(target, {tagName: 'DIV', className: 'form-group'}),
						locationInput;

					if (parent)
						locationInput = parent.querySelector('input.bx-ui-sls-fake[type=text]');

					if (locationInput)
						BX.fireEvent(locationInput, 'keyup');
				});
			}

			if (!this.firstLoad && this.options.propertyValidation)
			{
				if (inputStep)
				{
					arProperty = this.validation.properties[i];
					data = this.getValidationData(arProperty, locationNode);
					section = BX.findParent(locationNode, {className: 'bx-soa-section'});

					if (section && section.getAttribute('data-visited') == 'true')
						this.isValidProperty(data);
				}

				if (inputSearch)
					BX.fireEvent(inputSearch, 'keyup');
			}
		}

		if (this.firstLoad && this.result.IS_AUTHORIZED && typeof this.result.LAST_ORDER_DATA.FAIL === 'undefined')
		{
			this.showActualBlock();
		}
		else if (!this.result.SHOW_AUTH)
		{
			this.changeVisibleContent();
		}

		this.checkNotifications();
/*
		if (this.activeSectionId !== this.regionBlockNode.id)
			this.editFadeRegionContent(this.regionBlockNode.querySelector('.bx-soa-section-content'));

		if (this.activeSectionId != this.propsBlockNode.id)
			this.editFadePropsContent(this.propsBlockNode.querySelector('.bx-soa-section-content'));*/
	};
	
	
	
	BX.Sale.OrderAjaxComponentExt.getDeliveryLocationInput = function(node)
	{
		var currentProperty, locationId, altId, location, k, altProperty,
			labelHtml, currentLocation, insertedLoc,
			labelTextHtml, label, input, altNode;

		for (k in this.result.ORDER_PROP.properties)
		{
			if (this.result.ORDER_PROP.properties.hasOwnProperty(k))
			{
				currentProperty = this.result.ORDER_PROP.properties[k];
				if (currentProperty.IS_LOCATION == 'Y')
				{
					locationId = currentProperty.ID;
					altId = parseInt(currentProperty.INPUT_FIELD_LOCATION);
					break;
				}
			}
		}

		location = this.locations[locationId];
		if (location && location[0] && location[0].output)
		{
			this.regionBlockNotEmpty = true;

			labelHtml = '';
			/*
			labelHtml = '<label class="bx-soa-custom-label" for="soa-property-' + parseInt(locationId) + '">'
				+ (currentProperty.REQUIRED == 'Y' ? '<span class="bx-authform-starrequired">*</span> ' : '')
				+ BX.util.htmlspecialchars(currentProperty.NAME)
				+ (currentProperty.DESCRIPTION.length ? ' <small>(' + BX.util.htmlspecialchars(currentProperty.DESCRIPTION) + ')</small>' : '')
				+ '</label>';
			*/
			
			
			currentLocation = location[0].output;
			insertedLoc = BX.create('DIV', {
				attrs: {'data-property-id-row': locationId},
				props: {className: 'form-group bx-soa-location-input-container'},
				style: {visibility: 'hidden'},
				html:  labelHtml + currentLocation.HTML
			});
			
			node.appendChild(insertedLoc);
			node.appendChild(BX.create('INPUT', {
				props: {
					type: 'hidden',
					name: 'RECENT_DELIVERY_VALUE',
					value: location[0].lastValue
				}
			}));

			for (k in currentLocation.SCRIPT)
				if (currentLocation.SCRIPT.hasOwnProperty(k))
					BX.evalGlobal(currentLocation.SCRIPT[k].JS);
		}

		if (location && location[0] && location[0].showAlt && altId > 0)
		{
			for (k in this.result.ORDER_PROP.properties)
			{
				if (parseInt(this.result.ORDER_PROP.properties[k].ID) == altId)
				{
					altProperty = this.result.ORDER_PROP.properties[k];
					break;
				}
			}
		}

		if (altProperty)
		{
			altNode = BX.create('DIV', {
				attrs: {'data-property-id-row': altProperty.ID},
				props: {className: "form-group bx-soa-location-input-container"}
			});

			labelTextHtml = altProperty.REQUIRED == 'Y' ? '<span class="bx-authform-starrequired">*</span> ' : '';
			labelTextHtml += BX.util.htmlspecialchars(altProperty.NAME);

			label = BX.create('LABEL', {
				attrs: {for: 'altProperty'},
				props: {className: 'bx-soa-custom-label'},
				html: labelTextHtml
			});

			input = BX.create('INPUT', {
				props: {
					id: 'altProperty',
					type: 'text',
					placeholder: altProperty.DESCRIPTION,
					autocomplete: 'city',
					className: 'form-control bx-soa-customer-input bx-ios-fix',
					name: 'ORDER_PROP_' + altProperty.ID,
					value: altProperty.VALUE
				}
			});

			altNode.appendChild(label);
			altNode.appendChild(input);
			node.appendChild(altNode);

			this.bindValidation(altProperty.ID, altNode);
		}

		this.getZipLocationInput(node);
/*
		if (location && location[0])
		{
			node.appendChild(
				BX.create('DIV', {
					props: {className: 'bx-soa-reference'},
					html: this.params.MESS_REGION_REFERENCE
				})
			);
		} */
	};
	
	
	
	BX.Sale.OrderAjaxComponentExt.createDeliveryItemList = function(item)
	{
		var checked = item.CHECKED == 'Y',
			deliveryId = parseInt(item.ID),
			labelNodes = [
				BX.create('INPUT', {
					props: {
						id: 'ID_DELIVERY_ID_' + deliveryId,
						name: 'DELIVERY_ID',
						type: 'checkbox',
						className: 'bx-soa-pp-company-checkbox',
						value: deliveryId,
						checked: checked
					}
				})
			],
			deliveryCached = this.deliveryCachedInfo[deliveryId],
			logotype, label, title, itemNode, logoNode;

		logoNode = BX.create('DIV', {props: {className: 'bx-soa-pp-company-image'}});
		logotype = this.getImageSources(item, 'LOGOTIP');
		if (logotype && logotype.src_2x)
		{
			logoNode.setAttribute('style',
				'background-image: url("' + logotype.src_1x + '");' +
				'background-image: -webkit-image-set(url("' + logotype.src_1x + '") 1x, url("' + logotype.src_2x + '") 2x)'
			);
		}
		else
		{
			logotype = logotype && logotype.src_1x || this.defaultDeliveryLogo;
			logoNode.setAttribute('style', 'background-image: url("' + logotype + '");');
		}
		labelNodes.push(logoNode);

		if (item.PRICE >= 0 || typeof item.DELIVERY_DISCOUNT_PRICE !== 'undefined')
		{
			labelNodes.push(
				BX.create('DIV', {
					props: {className: 'bx-soa-pp-delivery-cost'},
					html: typeof item.DELIVERY_DISCOUNT_PRICE !== 'undefined'
						? item.DELIVERY_DISCOUNT_PRICE_FORMATED
						: item.PRICE_FORMATED})
			);
		}
		else if (deliveryCached && (deliveryCached.PRICE >= 0 || typeof deliveryCached.DELIVERY_DISCOUNT_PRICE !== 'undefined'))
		{
			labelNodes.push(
				BX.create('DIV', {
					props: {className: 'bx-soa-pp-delivery-cost'},
					html: typeof deliveryCached.DELIVERY_DISCOUNT_PRICE !== 'undefined'
						? deliveryCached.DELIVERY_DISCOUNT_PRICE_FORMATED
						: deliveryCached.PRICE_FORMATED})
			);
		}

		label = BX.create('DIV', {
			props: {
				className: 'bx-soa-pp-company-graf-container'
				+ (item.CALCULATE_ERRORS || deliveryCached && deliveryCached.CALCULATE_ERRORS ? ' bx-bd-waring' : '')},
			children: labelNodes
		});

		if (this.params.SHOW_DELIVERY_LIST_NAMES == 'Y')
		{
			title = BX.create('DIV', {
				props: {className: 'bx-soa-pp-company-smalltitle'},
				text: this.params.SHOW_DELIVERY_PARENT_NAMES != 'N' ? item.NAME : item.OWN_NAME
			});
		}










		itemNode = BX.create('OPTION', {
			props: {
				selected: checked,
				value: deliveryId
			},
			text: this.params.SHOW_DELIVERY_PARENT_NAMES != 'N' ? item.NAME : item.OWN_NAME
		});
		//checked && BX.addClass(itemNode, 'bx-selected');


		


		


		if (checked && this.result.LAST_ORDER_DATA.PICK_UP)
			this.lastSelectedDelivery = deliveryId;

		return itemNode;
	};
	
	BX.Sale.OrderAjaxComponentExt.getSelectedDelivery = function()
	{
		var deliveryOption = this.deliveryBlockNode.querySelector('select[name=DELIVERY_ID] option:checked'),
			currentDelivery = false,
			deliveryId, i;

		if (deliveryOption)
		{
			var currentDelivery, i;
			var deliveryId = this.deliveryBlockNode.querySelector('select[name=DELIVERY_ID]').value;

			for (i in this.result.DELIVERY)
			{
				if (this.result.DELIVERY[i].ID == deliveryId)
				{
					currentDelivery = this.result.DELIVERY[i];
					break;
				}
			}
		}

		return currentDelivery;
	};
		
		
		
	BX.Sale.OrderAjaxComponentExt.editDeliveryInfo = function(deliveryNode)
	{
		if (!this.result.DELIVERY)
			return;

		var deliveryInfoContainer = BX.create('DIV', {props: {className: 'bx-soa-pp-desc-container'}}),
			currentDelivery, logotype, name, logoNode,
			subTitle, label, title, price, period,
			clear, infoList, extraServices, extraServicesNode;

		BX.cleanNode(deliveryInfoContainer);
		currentDelivery = this.getSelectedDelivery();

		logoNode = BX.create('DIV', {props: {className: 'bx-soa-pp-company-image'}});
		logotype = this.getImageSources(currentDelivery, 'LOGOTIP');
		if (logotype && logotype.src_2x)
		{
			logoNode.setAttribute('style',
				'background-image: url("' + logotype.src_1x + '");' +
				'background-image: -webkit-image-set(url("' + logotype.src_1x + '") 1x, url("' + logotype.src_2x + '") 2x)'
			);
		}
		else
		{
			logotype = logotype && logotype.src_1x || this.defaultDeliveryLogo;
			logoNode.setAttribute('style', 'background-image: url("' + logotype + '");');
		}

		name = this.params.SHOW_DELIVERY_PARENT_NAMES != 'N' ? currentDelivery.NAME : currentDelivery.OWN_NAME;

		if (this.params.SHOW_DELIVERY_INFO_NAME == 'Y')
			subTitle = BX.create('DIV', {props: {className: 'bx-soa-pp-company-subTitle'}, text: name});

		label = BX.create('DIV', {
			props: {className: 'bx-soa-pp-company-logo'},
			children: [
				BX.create('DIV', {
					props: {className: 'bx-soa-pp-company-graf-container'},
					children: [logoNode]
				})
			]
		});
		title = BX.create('DIV', {
			props: {className: 'bx-soa-pp-company-block'},
			children: [
				BX.create('DIV', {props: {className: 'bx-soa-pp-company-desc exp'}, html: currentDelivery.DESCRIPTION}),
				currentDelivery.CALCULATE_DESCRIPTION
					? BX.create('DIV', {props: {className: 'bx-soa-pp-company-desc  exp'}, html: currentDelivery.CALCULATE_DESCRIPTION})
					: null
			]
		});

		if (currentDelivery.PRICE >= 0)
		{
			price = BX.create('DIV', {
				children: [
					BX.create('DIV', {
						props: {className: 'bx-soa-pp-list-description'},
						children: this.params.MESS_PRICE + ': ' + this.getDeliveryPriceNodes(currentDelivery)
					})
				]
			});
		}

		if (currentDelivery.PERIOD_TEXT && currentDelivery.PERIOD_TEXT.length)
		{
			period = BX.create('DIV', {
				children: [
					BX.create('DIV', {props: {className: 'bx-soa-pp-list-description'}, html: this.params.MESS_PERIOD + ': ' + currentDelivery.PERIOD_TEXT})
				]
			});
		}

		clear = BX.create('DIV', {style: {clear: 'both'}});
		infoList = BX.create('DIV', {props: {className: 'bx-soa-pp-list exp'}, children: [price, period]});
		extraServices = this.getDeliveryExtraServices(currentDelivery);

		if (extraServices.length)
		{
			extraServicesNode = BX.create('DIV', {
				props: {className: 'bx-soa-pp-company-block'},
				children: extraServices
			});
		}

		deliveryInfoContainer.appendChild(
			BX.create('DIV', {
				props: {className: 'bx-soa-pp-company'},
				children: [subTitle, label, title, clear, extraServicesNode, infoList]
			})
		);
		deliveryNode.appendChild(deliveryInfoContainer);

		if (this.params.DELIVERY_NO_AJAX != 'Y')
			this.deliveryCachedInfo[currentDelivery.ID] = currentDelivery;
	};
	
	
	BX.Sale.OrderAjaxComponentExt.editActiveDeliveryBlock = function(activeNodeMode) {
		var node = activeNodeMode ? this.deliveryBlockNode : this.deliveryHiddenBlockNode,
			deliveryContent, deliveryNode;

		if (this.initialized.delivery)
		{
			BX.remove(BX.lastChild(node));
			node.appendChild(BX.firstChild(this.deliveryHiddenBlockNode));
		}
		else
		{
			deliveryContent = node.querySelector('.bx-soa-section-content');
			if (!deliveryContent)
			{
				deliveryContent = this.getNewContainer();
				node.appendChild(deliveryContent);
			}
			else
				BX.cleanNode(deliveryContent);

			this.getErrorContainer(deliveryContent);

			deliveryNode = BX.create('DIV', {props: {className: 'bx-soa-pp field'}});
			this.editDeliveryItems(deliveryNode);
			deliveryContent.appendChild(deliveryNode);
			
			
			
			
			
			
			
			
			
			// пользовательские поля
			var propsItemsContainer = BX.create('DIV', {props: {className: 'bx-soa-customer columns  delivery_address'}}),
			group, property, groupIterator = this.propertyCollection.getGroupIterator(), propsIterator;

			//if (!propsItemsContainer)
			//	propsItemsContainer = this.propsBlockNode.querySelector('.col-sm-12.bx-soa-customer');
			
			// улица
			while (group = groupIterator())
			{
				propsIterator =  group.getIterator();
				while (property = propsIterator())
				{
					if (property.getId() >= 7 && property.getId() <= 9) {
						this.getPropertyRowNode(property, propsItemsContainer, false);
					}
				}
			}
			deliveryContent.appendChild(propsItemsContainer);
			
			
			
			
			
			
			this.editDeliveryInfo(deliveryNode);

			if (this.params.SHOW_COUPONS_DELIVERY == 'Y')
				this.editCoupons(deliveryContent);

			this.getBlockFooter(deliveryContent);
		}
	};
	
	
	BX.Sale.OrderAjaxComponentExt.editDeliveryItems = function(deliveryNode)
	{
		if (!this.result.DELIVERY || this.result.DELIVERY.length <= 0)
			return;

		var deliveryItemsContainer = BX.create('SELECT', {
				props: {
					className: 'form-control bx-soa-pp-item-container',
					name: 'DELIVERY_ID',
					
				},
				events: {change: BX.proxy(this.selectDelivery, this)}
			}),
			deliveryItemNode, k;

		for (k = 0; k < this.deliveryPagination.currentPage.length; k++)
		{
			deliveryItemNode = this.createDeliveryItemList(this.deliveryPagination.currentPage[k]);
			deliveryItemsContainer.appendChild(deliveryItemNode);
		}

		if (this.deliveryPagination.show)
			this.showPagination('delivery', deliveryItemsContainer);

		deliveryNode.appendChild(deliveryItemsContainer);

		deliveryNode.appendChild(BX.create('SPAN', {
			html:'<svg><use xlink:href="/local/images/sprite.svg#ic_arr_hor"></use></svg>',
			props: {
				className: 'arr'
			}
		}));

	};
		
		
		
	BX.Sale.OrderAjaxComponentExt.editActivePropsBlock = function(activeNodeMode)
	{
		var node = activeNodeMode ? this.propsBlockNode : this.propsHiddenBlockNode,
			propsContent, propsNode, selectedDelivery, showPropMap = false, i, validationErrors;

		if (this.initialized.props)
		{
			BX.remove(BX.lastChild(node));
			node.appendChild(BX.firstChild(this.propsHiddenBlockNode));
			this.maps && setTimeout(BX.proxy(this.maps.propsMapFocusWaiter, this.maps), 200);
		}
		else
		{
			propsContent = node.querySelector('.bx-soa-section-content');
			if (!propsContent)
			{
				propsContent = this.getNewContainer();
				node.appendChild(propsContent);
			}
			else
				BX.cleanNode(propsContent);

			this.getErrorContainer(propsContent);

			propsNode = BX.create('DIV');
			selectedDelivery = this.getSelectedDelivery();

			if (
				selectedDelivery && this.params.SHOW_MAP_IN_PROPS === 'Y'
				&& this.params.SHOW_MAP_FOR_DELIVERIES && this.params.SHOW_MAP_FOR_DELIVERIES.length
			)
			{
				for (i = 0; i < this.params.SHOW_MAP_FOR_DELIVERIES.length; i++)
				{
					if (parseInt(selectedDelivery.ID) === parseInt(this.params.SHOW_MAP_FOR_DELIVERIES[i]))
					{
						showPropMap = true;
						break;
					}
				}
			}

			this.editPropsItems(propsNode);
			
			if (this.params.HIDE_ORDER_DESCRIPTION !== 'Y')
			{
				this.editPropsComment(propsNode);
			}
			
		
			showPropMap && this.editPropsMap(propsNode);
/*
			if (this.params.HIDE_ORDER_DESCRIPTION !== 'Y')
			{
				this.editPropsComment(propsNode);
			}
*/
			propsContent.appendChild(propsNode);
			this.getBlockFooter(propsContent);

			if (this.propsBlockNode.getAttribute('data-visited') === 'true')
			{
				validationErrors = this.isValidPropertiesBlock(true);
				if (validationErrors.length)
					BX.addClass(this.propsBlockNode, 'bx-step-error');
				else
					BX.removeClass(this.propsBlockNode, 'bx-step-error');
			}
		}
	};
	
	
	BX.Sale.OrderAjaxComponentExt.editPropsItems = function(propsNode)
	{
		if (!this.result.ORDER_PROP || !this.propertyCollection)
			return;

		var propsItemsContainer = BX.create('DIV', {props: {className: 'columns bx-soa-customer'}}),
			group, property, groupIterator = this.propertyCollection.getGroupIterator(), propsIterator;

		if (!propsItemsContainer)
			propsItemsContainer = this.propsBlockNode.querySelector('.columns.bx-soa-customer');

		while (group = groupIterator())
		{
			propsIterator =  group.getIterator();
			while (property = propsIterator())
			{
				if (
					this.deliveryLocationInfo.loc == property.getId()
					|| this.deliveryLocationInfo.zip == property.getId()
					|| this.deliveryLocationInfo.city == property.getId()
				)
					continue;
				if (property.getId() < 7 || property.getId() > 9)
					this.getPropertyRowNode(property, propsItemsContainer, false);
			}
		}

		propsNode.appendChild(propsItemsContainer);
		
		
		
		
		
		$(function(){
			const phoneInputs = document.querySelectorAll('input[name="ORDER_PROP_4"]')

			if (phoneInputs) {
				phoneInputs.forEach(el => {
					IMask(el, {
						mask: '+{7} (000) 000-00-00',
						lazy: true
					})
				})
			}
			
		})
	};
	
	
	BX.Sale.OrderAjaxComponentExt.getPropertyRowNode = function(property, propsItemsContainer, disabled)
	{
		var propsItemNode = BX.create('DIV'),
			textHtml = '',
			propertyType = property.getType() || '',
			propertyDesc = property.getDescription() || '',
			label;

		if (disabled)
		{
			propsItemNode.innerHTML = '<strong>' + BX.util.htmlspecialchars(property.getName()) + ':</strong> ';
		}
		else
		{
			BX.addClass(propsItemNode, "form-group bx-soa-customer-field   line width1of3");
			/*		<div class="field">
													<input type="text" name="" value="" class="input" placeholder="ФАМИЛИЯ">
												</div>
				*/								
												
												
												
												
			if (property.isRequired())
				textHtml += '<span class="bx-authform-starrequired">*</span> ';

			textHtml += BX.util.htmlspecialchars(property.getName());
			if (propertyDesc.length && propertyType != 'STRING' && propertyType != 'NUMBER' && propertyType != 'DATE')
				textHtml += ' <small>(' + BX.util.htmlspecialchars(propertyDesc) + ')</small>';

		/*	label = BX.create('LABEL', {
				attrs: {'for': 'soa-property-' + property.getId()},
				props: {className: 'bx-soa-custom-label'},
				html: textHtml
			}); */
			propsItemNode.setAttribute('data-property-id-row', property.getId());
		//	propsItemNode.appendChild(label);
		}

		switch (propertyType)
		{
			case 'LOCATION':
				this.insertLocationProperty(property, propsItemNode, disabled);
				break;
			case 'DATE':
				this.insertDateProperty(property, propsItemNode, disabled);
				break;
			case 'FILE':
				this.insertFileProperty(property, propsItemNode, disabled);
				break;
			case 'STRING':
				this.insertStringProperty(property, propsItemNode, disabled);
				break;
			case 'ENUM':
				this.insertEnumProperty(property, propsItemNode, disabled);
				break;
			case 'Y/N':
				this.insertYNProperty(property, propsItemNode, disabled);
				break;
			case 'NUMBER':
				this.insertNumberProperty(property, propsItemNode, disabled);
		}

		propsItemsContainer.appendChild(propsItemNode);
	};
	
	
	
	BX.Sale.OrderAjaxComponentExt.insertStringProperty = function(property, propsItemNode, disabled)
	{
		var prop, inputs, values, i, propContainer;

		if (disabled)
		{
			prop = this.propsHiddenBlockNode.querySelector('div[data-property-id-row="' + property.getId() + '"]');
			if (prop)
			{
				values = [];
				inputs = prop.querySelectorAll('input[type=text]');
				if (inputs.length == 0)
					inputs = prop.querySelectorAll('textarea');

				if (inputs.length)
				{
					for (i = 0; i < inputs.length; i++)
					{
						if (inputs[i].value.length)
							values.push(inputs[i].value);
					}
				}

				propsItemNode.innerHTML += this.valuesToString(values);
			}
		}
		else
		{
			propContainer = BX.create('DIV', {props: {className: 'soa-property-container field'}});
			property.appendTo(propContainer);
			propsItemNode.appendChild(propContainer);
			this.alterProperty(property.getSettings(), propContainer);
			this.bindValidation(property.getId(), propContainer);
		}
	};
	
	
	BX.Sale.OrderAjaxComponentExt.alterProperty = function(settings, propContainer)
	{
		var divs = BX.findChildren(propContainer, {tagName: 'DIV'}),
			i, textNode, inputs, del, add,
			fileInputs, accepts, fileTitles;

		if (divs && divs.length)
		{
			for (i = 0; i < divs.length; i++)
			{
				divs[i].style.margin = '5px 0';
			}
		}

		textNode = propContainer.querySelector('input[type=text]');
		if (!textNode)
			textNode = propContainer.querySelector('textarea');

		if (textNode)
		{
			textNode.id = 'soa-property-' + settings.ID;
			if (settings.IS_ADDRESS == 'Y')
				textNode.setAttribute('autocomplete', 'address');
			if (settings.IS_EMAIL == 'Y')
				textNode.setAttribute('autocomplete', 'email');
			if (settings.IS_PAYER == 'Y')
				textNode.setAttribute('autocomplete', 'name');
			if (settings.IS_PHONE == 'Y')
				textNode.setAttribute('autocomplete', 'tel');
			if (settings.REQUIRED == 'Y')
				textNode.setAttribute('required', 'required');


			if (settings.PATTERN && settings.PATTERN.length)
			{
				textNode.removeAttribute('pattern');
			}
			
			textNode.setAttribute('title', settings.NAME);
		}

		inputs = propContainer.querySelectorAll('input[type=text]');
		for (i = 0; i < inputs.length; i++)
		{
			if (settings.REQUIRED == 'Y') {
				inputs[i].placeholder = settings.NAME + " *";
				BX.addClass(inputs[i], 'form-control bx-soa-customer-input bx-ios-fix   input required');
			} else {
				inputs[i].placeholder = settings.NAME;
				BX.addClass(inputs[i], 'form-control bx-soa-customer-input bx-ios-fix   input');
			}
		}
		
		
/*
		if (settings.IS_EMAIL == 'Y') {
			textNode.setAttribute('type', 'email');
		}
		if (settings.IS_PHONE == 'Y') {
			textNode.setAttribute('type', 'tel');
		}
	*/		
			
			
			
		inputs = propContainer.querySelectorAll('select');
		for (i = 0; i < inputs.length; i++)
			BX.addClass(inputs[i], 'form-control');

		inputs = propContainer.querySelectorAll('textarea');
		for (i = 0; i < inputs.length; i++)
		{
			inputs[i].placeholder = settings.NAME.toUpperCase();
			BX.addClass(inputs[i], 'form-control bx-ios-fix');
		}

		del = propContainer.querySelectorAll('label');
		for (i = 0; i < del.length; i++)
			BX.remove(del[i]);

		if (settings.TYPE == 'FILE')
		{
			if (settings.ACCEPT && settings.ACCEPT.length)
			{
				fileInputs = propContainer.querySelectorAll('input[type=file]');
				accepts = this.getFileAccepts(settings.ACCEPT);
				for (i = 0; i < fileInputs.length; i++)
					fileInputs[i].setAttribute('accept', accepts);
			}

			fileTitles = propContainer.querySelectorAll('a');
			for (i = 0; i < fileTitles.length; i++)
			{
				BX.bind(fileTitles[i], 'click', function(e){
					var target = e.target || e.srcElement,
						fileInput = target && target.nextSibling && target.nextSibling.nextSibling;

					if (fileInput)
						BX.fireEvent(fileInput, 'change');
				});
			}
		}

		add = propContainer.querySelectorAll('input[type=button]');
		for (i = 0; i < add.length; i++)
		{
			BX.addClass(add[i], 'btn btn-default btn-sm');

			if (settings.MULTIPLE == 'Y' && i == add.length - 1)
				continue;

			if (settings.TYPE == 'FILE')
			{
				BX.prepend(add[i], add[i].parentNode);
				add[i].style.marginRight = '10px';
			}
		}

		if (add.length)
		{
			add = add[add.length - 1];
			BX.bind(add, 'click', BX.delegate(function(e){
				var target = e.target || e.srcElement,
					targetContainer = BX.findParent(target, {tagName: 'div', className: 'soa-property-container'}),
					del = targetContainer.querySelector('label'),
					add = targetContainer.querySelectorAll('input[type=button]'),
					textInputs = targetContainer.querySelectorAll('input[type=text]'),
					textAreas = targetContainer.querySelectorAll('textarea'),
					divs = BX.findChildren(targetContainer, {tagName: 'DIV'});

				var i, fileTitles, fileInputs, accepts;

				if (divs && divs.length)
				{
					for (i = 0; i < divs.length; i++)
					{
						divs[i].style.margin = '5px 0';
					}
				}

				this.bindValidation(settings.ID, targetContainer);

				if (add.length && add[add.length - 2])
				{
					BX.prepend(add[add.length - 2], add[add.length - 2].parentNode);
					add[add.length - 2].style.marginRight = '10px';
					BX.addClass(add[add.length - 2], 'btn btn-default btn-sm');
				}

				del && BX.remove(del);
				if (textInputs.length)
				{
					textInputs[textInputs.length - 1].placeholder = settings.DESCRIPTION;
					BX.addClass(textInputs[textInputs.length - 1], 'form-control bx-soa-customer-input bx-ios-fix');
					if (settings.TYPE == 'DATE')
						this.alterDateProperty(settings, textInputs[textInputs.length - 1]);

					if (settings.PATTERN && settings.PATTERN.length)
						textInputs[textInputs.length - 1].removeAttribute('pattern');
				}

				if (textAreas.length)
				{
					textAreas[textAreas.length - 1].placeholder = settings.DESCRIPTION;
					BX.addClass(textAreas[textAreas.length - 1], 'form-control bx-ios-fix');
				}

				if (settings.TYPE == 'FILE')
				{
					if (settings.ACCEPT && settings.ACCEPT.length)
					{
						fileInputs = propContainer.querySelectorAll('input[type=file]');
						accepts = this.getFileAccepts(settings.ACCEPT);
						for (i = 0; i < fileInputs.length; i++)
							fileInputs[i].setAttribute('accept', accepts);
					}

					fileTitles = targetContainer.querySelectorAll('a');
					BX.bind(fileTitles[fileTitles.length - 1], 'click', function(e){
						var target = e.target || e.srcElement,
							fileInput = target && target.nextSibling && target.nextSibling.nextSibling;

						if (fileInput)
							setTimeout(function(){BX.fireEvent(fileInput, 'change');}, 10);
					});
				}
			}, this));
		}
	};
	
	BX.Sale.OrderAjaxComponentExt.editActivePaySystemBlock = function(activeNodeMode)
	{
		var node = activeNodeMode ? this.paySystemBlockNode : this.paySystemHiddenBlockNode,
			paySystemContent, paySystemNode;

		if (this.initialized.paySystem)
		{
			BX.remove(BX.lastChild(node));
			node.appendChild(BX.firstChild(this.paySystemHiddenBlockNode));
		}
		else
		{
			paySystemContent = node.querySelector('.bx-soa-section-content');
			if (!paySystemContent)
			{
				paySystemContent = this.getNewContainer();
				node.appendChild(paySystemContent);
			}
			else
				BX.cleanNode(paySystemContent);

			this.getErrorContainer(paySystemContent);
			paySystemNode = BX.create('DIV');
			this.editPaySystemItems(paySystemNode);
			paySystemContent.appendChild(paySystemNode);
			this.editPaySystemInfo(paySystemNode);
			

			if (this.params.SHOW_COUPONS_PAY_SYSTEM == 'Y')
				this.editCoupons(paySystemContent);
			
			this.editPaySystemInfoInner(paySystemContent);
			

			this.getBlockFooter(paySystemContent);
		}
	};
	
	BX.Sale.OrderAjaxComponentExt.editPaySystemItems = function(paySystemNode)
	{
		if (!this.result.PAY_SYSTEM || this.result.PAY_SYSTEM.length <= 0)
			return;

		var paySystemItemsContainer = BX.create('DIV', {props: {className: 'line methods'}}),
			paySystemItemNode, i;

		for (i = 0; i < this.paySystemPagination.currentPage.length; i++)
		{
			paySystemItemNode = this.createPaySystemItem(this.paySystemPagination.currentPage[i]);
			paySystemItemsContainer.appendChild(paySystemItemNode);
		}

		if (this.paySystemPagination.show)
			this.showPagination('paySystem', paySystemItemsContainer);

		paySystemNode.appendChild(paySystemItemsContainer);
	};
	
	BX.Sale.OrderAjaxComponentExt.editPaySystemInfo = function(paySystemNode)
	{
		if (!this.result.PAY_SYSTEM || (this.result.PAY_SYSTEM.length == 0 && this.result.PAY_FROM_ACCOUNT != 'Y'))
			return;

		var paySystemInfoContainer = BX.create('DIV', {
				props: {
					className: 'line credit_info bx-soa-pp-desc-container'
				}
			}),
			extPs, currentPaySystem,
			logotype, logoNode, subTitle, label, title, price;

		BX.cleanNode(paySystemInfoContainer);

		currentPaySystem = this.getSelectedPaySystem();
		if (currentPaySystem)
		{
			extPs = '';
			if (currentPaySystem.ID == 4) {			// для платежной системы "Долями"
				extPs = BX.create('DIV', {
								props: {className: 'row'},
								html: '<div>	\
										<div class="price">xx xxx <span>руб.</span></div>	\
										<div class="date">Сегодня</div>		\
									</div> \
									<div> \
										<div class="price">xx xxx <span>руб.</span></div>	\
										<div class="date">xx июля</div>	\
									</div>	\
									<div>	\
										<div class="price">xx xxx <span>руб.</span></div>	\
										<div class="date">xx августа</div>	\
									</div>	\
									<div>	\
										<div class="price">xx xxx <span>руб.</span></div>	\
										<div class="date">xx сентября</div>	\
									</div>	'});
				paySystemInfoContainer.appendChild(
					BX.create('DIV', {
						props: {className: 'bx-soa-pp-company--CUSTOM'},
						children: [extPs]
					})
				);
				paySystemNode.appendChild(paySystemInfoContainer);
			}
			
		}
	};
	
	
	BX.Sale.OrderAjaxComponentExt.editPaySystemInfoInner = function(paySystemNode)
	{
		if (!this.result.PAY_SYSTEM || (this.result.PAY_SYSTEM.length == 0 && this.result.PAY_FROM_ACCOUNT != 'Y'))
			return;

		var paySystemInfoContainer = BX.create('DIV', {
				props: {
					className: 'line credit_info bx-soa-pp-desc-container'
				}
			}),
			innerPs, extPs, delimiter,
			logotype, logoNode, subTitle, label, title, price;

		BX.cleanNode(paySystemInfoContainer);

		if (this.result.PAY_FROM_ACCOUNT == 'Y')
			innerPs = this.getInnerPaySystem(paySystemInfoContainer);


	//	if (innerPs && extPs)
	//		delimiter = BX.create('HR', {props: {className: 'bxe-light'}});

		paySystemInfoContainer.appendChild(
			BX.create('DIV', {
				props: {className: 'bx-soa-pp-company'},
				children: [innerPs]
			})
		);
		paySystemNode.appendChild(paySystemInfoContainer);
		
		
		
		
			
	};
	
	
	BX.Sale.OrderAjaxComponentExt.createPaySystemItem = function(item)
	{
		var checked = item.CHECKED == 'Y',
			logotype, logoNode,
			paySystemId = parseInt(item.ID),
			title, label, itemNode;

		/*
		logoNode = BX.create('DIV', {props: {className: 'bx-soa-pp-company-image'}});
		logotype = this.getImageSources(item, 'PSA_LOGOTIP');
		if (logotype && logotype.src_2x)
		{
			logoNode.setAttribute('style',
				'background-image: url("' + logotype.src_1x + '");' +
				'background-image: -webkit-image-set(url("' + logotype.src_1x + '") 1x, url("' + logotype.src_2x + '") 2x)'
			);
		}
		else
		{
			logotype = logotype && logotype.src_1x || this.defaultPaySystemLogo;
			logoNode.setAttribute('style', 'background-image: url("' + logotype + '");');
		}*/
		
		let content_html = item.NAME;
		if (item.DESCRIPTION && (item.DESCRIPTION != '<br>')) {
			content_html += '<div class="tooltip">  \
								<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_tooltip"></use></svg>  \
								<div class="text">' + item.DESCRIPTION + '</div> \
							</div>';
		}
		
		label = BX.create('LABEL', {
			props: {className: 'bx-soa-pp-company-graf-container--CUSTOM radio'},
			children: [
				BX.create('INPUT', {
					props: {
						id: 'ID_PAY_SYSTEM_ID_' + paySystemId,
						name: 'PAY_SYSTEM_ID',
						type: 'radio',
						className: 'bx-soa-pp-company-checkbox',
						value: paySystemId,
						checked: checked
					}
				}),
				BX.create('DIV', {props: {className: 'check'}}),
				BX.create('DIV', {html: content_html})
				//logoNode
			]
		});
/*
		if (this.params.SHOW_PAY_SYSTEM_LIST_NAMES == 'Y')
		{
			title = BX.create('DIV', {props: {className: 'bx-soa-pp-company-smalltitle'}, text: item.NAME});
		}
*/
		itemNode = BX.create('DIV', {
			props: {className: 'bx-soa-pp-company field'},
			children: [label],
			events: {
				click: BX.proxy(this.selectPaySystem, this)
			}
		});

		if (checked)
			BX.addClass(itemNode, 'bx-selected');

		return itemNode;
	};
	
	
	
	BX.Sale.OrderAjaxComponentExt.getSelectedPaySystem = function()
	{
		var paySystemCheckbox = this.paySystemBlockNode.querySelector('input[type=radio][name=PAY_SYSTEM_ID]:checked'),
			currentPaySystem = null, paySystemId, i;

		if (!paySystemCheckbox)
			paySystemCheckbox = this.paySystemHiddenBlockNode.querySelector('input[type=radio][name=PAY_SYSTEM_ID]:checked');

		if (!paySystemCheckbox)
			paySystemCheckbox = this.paySystemHiddenBlockNode.querySelector('input[type=hidden][name=PAY_SYSTEM_ID]');

		if (paySystemCheckbox)
		{
			paySystemId = paySystemCheckbox.value;

			for (i = 0; i < this.result.PAY_SYSTEM.length; i++)
			{
				if (this.result.PAY_SYSTEM[i].ID == paySystemId)
				{
					currentPaySystem = this.result.PAY_SYSTEM[i];
					break;
				}
			}
		}

		return currentPaySystem;
	};
	
	/*
	BX.Sale.OrderAjaxComponentExt.isSelectedInnerPayment = function()
	{
		var innerPaySystemCheckbox = this.paySystemBlockNode.querySelector('input[type=checkbox][name=PAY_CURRENT_ACCOUNT]');

		if (!innerPaySystemCheckbox)
			innerPaySystemCheckbox = this.paySystemHiddenBlockNode.querySelector('input[type=checkbox][name=PAY_CURRENT_ACCOUNT]');

		return innerPaySystemCheckbox && innerPaySystemCheckbox.checked;
	};
	*/
	BX.Sale.OrderAjaxComponentExt.selectPaySystem = function(event)
	{
		if (!this.orderBlockNode || !event)
			return;

		var target = event.target || event.srcElement,
			innerPaySystemSection = this.paySystemBlockNode.querySelector('div.bx-soa-pp-inner-ps'),
			innerPaySystemCheckbox = this.paySystemBlockNode.querySelector('input[type=checkbox][name=PAY_CURRENT_ACCOUNT]'),
			fullPayFromInnerPaySystem = this.result.TOTAL && parseFloat(this.result.TOTAL.ORDER_TOTAL_LEFT_TO_PAY) === 0;

		var innerPsAction = BX.hasClass(target, 'bx-soa-pp-inner-ps') ? target : BX.findParent(target, {className: 'bx-soa-pp-inner-ps'}),
			actionSection = BX.hasClass(target, 'bx-soa-pp-company') ? target : BX.findParent(target, {className: 'bx-soa-pp-company'}),
			actionInput, selectedSection;

		if (innerPsAction)
		{
			if (target.nodeName == 'INPUT')
				innerPaySystemCheckbox.checked = !innerPaySystemCheckbox.checked;

			if (innerPaySystemCheckbox.checked)
			{
				BX.removeClass(innerPaySystemSection, 'bx-selected');
				innerPaySystemCheckbox.checked = false;
			}
			else
			{
				BX.addClass(innerPaySystemSection, 'bx-selected');
				innerPaySystemCheckbox.checked = true;
			}
		}
		else if (actionSection)
		{
			if (BX.hasClass(actionSection, 'bx-selected'))
				return BX.PreventDefault(event);

			if (innerPaySystemCheckbox && innerPaySystemCheckbox.checked && fullPayFromInnerPaySystem)
			{
				BX.addClass(actionSection, 'bx-selected');
				actionInput = actionSection.querySelector('input[type=checkbox]');
				actionInput.checked = true;
				BX.removeClass(innerPaySystemSection, 'bx-selected');
				innerPaySystemCheckbox.checked = false;
			}
			else
			{
				selectedSection = this.paySystemBlockNode.querySelector('.bx-soa-pp-company.bx-selected');
				BX.addClass(actionSection, 'bx-selected');
				actionInput = actionSection.querySelector('input[type=radio]');
				actionInput.checked = true;

				if (selectedSection)
				{
					BX.removeClass(selectedSection, 'bx-selected');
					selectedSection.querySelector('input[type=radio]').checked = false;
				}
			}
		}

		this.sendRequest();
	};
	
	
	BX.Sale.OrderAjaxComponentExt.editPropsComment = function(propsNode)
	{
		var propsCommentContainer, input, div;

		propsCommentContainer = BX.create('DIV', {props: {className: 'line'}});

		input = BX.create('TEXTAREA', {
			props: {
				id: 'orderDescription',
				cols: '4',
				className: 'form-control bx-soa-customer-textarea bx-ios-fix',
				name: 'ORDER_DESCRIPTION',
				placeholder: "Комментарий"
			},
			text: this.result.ORDER_DESCRIPTION ? this.result.ORDER_DESCRIPTION : ''
		});
		div = BX.create('DIV', {
			props: {className: 'form-group bx-soa-customer-field  field'},
			children: [input]
		});

		propsCommentContainer.appendChild(div);
		propsNode.appendChild(propsCommentContainer);
	};
	

	/*
	<div class="promocode">
		<div class="field">
			<input type="text" name="" value="" class="input" placeholder="ПРОМОКОД">
		</div>

		<button type="button" class="btn">ПРИМЕНИТЬ</button>
	</div>
	*/
	
	BX.Sale.OrderAjaxComponentExt.editCoupons = function(basketItemsNode)
	{
		var couponsList = this.getCouponsList(true);

		basketItemsNode.appendChild(
			BX.create('DIV', {
				props: {className: 'bx-soa-coupon  promocode'},
				children: [
					BX.create('DIV', {
						props: {className: 'field'},
						children: [
						/*	BX.create('INPUT', {
								props: {
									className: 'form-control bx-ios-fix--CUSTOM   input',
									type: 'text',
									placeholder: 'ПРОМОКОД'
								},
								events: {
									change: BX.delegate(function(event){
										var newCoupon = BX.getEventTarget(event);
										if (newCoupon && newCoupon.value)
										{
											this.sendRequest('enterCoupon', newCoupon.value);
											newCoupon.value = '';
										}
									}, this)
								}
							})
							*/
							
							BX.create('INPUT', {
								props: {
									id: 'coupon_input',
									className: 'form-control--CUSTOM bx-ios-fix--CUSTOM   input',
									type: 'text',
									placeholder: 'ПРОМОКОД'
								}
								
							})
						]
					}),
					BX.create('BUTTON', {
						text: 'ПРИМЕНИТЬ',
						props: {
							className: 'btn',
							type: 'button'
						},
						events: {
							click: BX.delegate(function(event){
								var newCoupon = BX('coupon_input');
								if (newCoupon && newCoupon.value)
								{
									this.sendRequest('enterCoupon', newCoupon.value);
									newCoupon.value = '';
								}
							}, this)
						} 
					}),
					BX.create('SPAN', {props: {className: 'bx-soa-coupon-item'}, children: couponsList})
				]
			})
		);
	};



		
	BX.Sale.OrderAjaxComponentExt.editTotalBlock = function()
	{
		if (!this.totalInfoBlockNode || !this.result.TOTAL)
			return;

		var total = this.result.TOTAL, price_div,
			priceHtml, params = {},
			discText, valFormatted, i,
			curDelivery, deliveryError, deliveryValue,
			showOrderButton = this.params.SHOW_TOTAL_ORDER_BUTTON === 'Y';

		BX.cleanNode(this.totalInfoBlockNode);

		if (parseFloat(total.ORDER_PRICE) === 0)
		{
			priceHtml = this.params.MESS_PRICE_FREE;
			params.free = true;
		}
		else
		{
			priceHtml = total.ORDER_PRICE_FORMATED;
		}

		if (this.options.showPriceWithoutDiscount)
		{
			priceHtml += '<br><span class="bx-price-old">' + total.PRICE_WITHOUT_DISCOUNT + '</span>';
		}
		
		
		this.totalInfoBlockNode.appendChild( BX.create('DIV', {props: {className: 'title'}, text: 'ИТОГО'}) );
		
		price_div = BX.create('DIV', {props: {className: 'prices'}});
		
		

		price_div.appendChild(this.createTotalUnit(BX.message('SOA_SUM_SUMMARY'), priceHtml, params));   // Товары на сумму
		
		
		
		
		

		if (this.options.showOrderWeight)
		{
			price_div.appendChild(this.createTotalUnit(BX.message('SOA_SUM_WEIGHT_SUM'), total.ORDER_WEIGHT_FORMATED));   // общий вес
		}

		if (this.options.showTaxList)
		{
			for (i = 0; i < total.TAX_LIST.length; i++)
			{
				valFormatted = total.TAX_LIST[i].VALUE_MONEY_FORMATED || '';
				price_div.appendChild(
					this.createTotalUnit(
						total.TAX_LIST[i].NAME + (!!total.TAX_LIST[i].VALUE_FORMATED ? ' ' + total.TAX_LIST[i].VALUE_FORMATED : '') + ':',
						valFormatted
					)
				);
			}
		}

		params = {};
		curDelivery = this.getSelectedDelivery();
		deliveryError = curDelivery && curDelivery.CALCULATE_ERRORS && curDelivery.CALCULATE_ERRORS.length;

		if (deliveryError)
		{
			deliveryValue = BX.message('SOA_NOT_CALCULATED');
			params.error = deliveryError;
		}
		else
		{
			if (parseFloat(total.DELIVERY_PRICE) === 0)
			{
				deliveryValue = this.params.MESS_PRICE_FREE;
				params.free = true;
			}
			else
			{
				deliveryValue = total.DELIVERY_PRICE_FORMATED;
			}

			if (
				curDelivery && typeof curDelivery.DELIVERY_DISCOUNT_PRICE !== 'undefined'
				&& parseFloat(curDelivery.PRICE) > parseFloat(curDelivery.DELIVERY_DISCOUNT_PRICE)
			)
			{
				deliveryValue += '<br><span class="bx-price-old">' + curDelivery.PRICE_FORMATED + '</span>';
			}
		}

		if (this.result.DELIVERY.length)
		{
			price_div.appendChild(this.createTotalUnit(BX.message('SOA_SUM_DELIVERY'), deliveryValue, params));
		}

		if (this.options.showDiscountPrice)
		{
			discText = this.params.MESS_ECONOMY;
			if (total.DISCOUNT_PERCENT_FORMATED && parseFloat(total.DISCOUNT_PERCENT_FORMATED) > 0)
				discText += total.DISCOUNT_PERCENT_FORMATED;

			price_div.appendChild(this.createTotalUnit(discText, total.DISCOUNT_PRICE_FORMATED));
		}

		if (this.options.showPayedFromInnerBudget)
		{
			price_div.appendChild(this.createTotalUnit(BX.message('SOA_SUM_IT'), total.ORDER_TOTAL_PRICE_FORMATED));
			price_div.appendChild(this.createTotalUnit(BX.message('SOA_SUM_PAYED'), total.PAYED_FROM_ACCOUNT_FORMATED));
		}
		
		
		this.totalInfoBlockNode.appendChild(price_div);

		if (this.options.showPayedFromInnerBudget)
		{
			this.totalInfoBlockNode.appendChild(this.createTotalUnit(BX.message('SOA_SUM_LEFT_TO_PAY'), total.ORDER_TOTAL_LEFT_TO_PAY_FORMATED, {total: true}));
		}
		else
		{
			this.totalInfoBlockNode.appendChild(this.createTotalUnit(BX.message('SOA_SUM_IT'), total.ORDER_TOTAL_PRICE_FORMATED, {total: true}));
		}

		
		if (parseFloat(total.PAY_SYSTEM_PRICE) >= 0 && this.result.DELIVERY.length)
		{
			this.totalInfoBlockNode.appendChild(this.createTotalUnit(BX.message('SOA_PAYSYSTEM_PRICE'), '~' + total.PAY_SYSTEM_PRICE_FORMATTED));
		}

		if (!this.result.SHOW_AUTH)
		{
			this.totalInfoBlockNode.appendChild(
				BX.create('DIV', {
					props: {className: 'bx-soa-cart-total-button-container' + (!showOrderButton ? ' visible-xs' : '')},
					children: [
						BX.create('A', {
							props: {
								href: 'javascript:void(0)',
								className: 'btn btn-default btn-lg btn-order-save'
							},
							html: this.params.MESS_ORDER,
							events: {
								click: BX.proxy(this.clickOrderSaveAction, this)
							}
						})

					]
				})
			);
		}

		//this.editMobileTotalBlock();
		
		$(function(){
			$('#bx-soa-total > .bx-soa-cart-total').prependTo('.checkout_total');
			
			var delivery_select = document.querySelectorAll('#bx-soa-order select')[0];
			NiceSelect.bind(delivery_select, {
				placeholder: delivery_select.options[delivery_select.selectedIndex].text
			})

			
	
	
		})
	};
	
	
	BX.Sale.OrderAjaxComponentExt.createTotalUnit = function(name, value, params)
	{
		var totalValue, className = '';

		name = name || '';
		value = value || '';
		params = params || {};

		if (params.error)
		{
			totalValue = [BX.create('A', {
				props: {className: 'bx-soa-price-not-calc'},
				html: value,
				events: {
					click: BX.delegate(function(){
						this.animateScrollTo(this.deliveryBlockNode);
					}, this)
				}
			})];
		}
		else if (params.free)
		{
			totalValue = [BX.create('SPAN', {
				props: {className: 'bx-soa-price-free'},
				html: value
			})];
		}
		else if (params.total) {
			totalValue = [
				BX.create('DIV', {
					html: value
				}),
				BX.create('DIV', {
					html: '<div class="points">  \
							<svg class="icon"><use xlink:href="/local/images/sprite.svg#ic_points"></use></svg>  \
							<span>xxxx.xx на счет</span>  \
						</div>'
				})
			];
		}
		else
		{
			totalValue = [value];
		}

		if (params.total)
		{
			className += ' total_price';//' bx-soa-cart-total-line-total';
		}

		if (params.highlighted)
		{
			className += ' bx-soa-cart-total-line-highlighted';
		}
		
		
		
		
		return BX.create('DIV', {
			props: {className: className},
			children: [
				BX.create('DIV', {props: {className: 'label'}, text: name}),
				BX.create('DIV', {
					props: {
						className: 'price' //+ (!!params.total && this.options.totalPriceChanged ? ' bx-soa-changeCostSign' : '')
					},
					children: totalValue
				})
			]
		});
	};
		
		
		
		
		
		
		
	
	// убираем кнопки в подвале блоков
	BX.Sale.OrderAjaxComponentExt.getBlockFooter = function(node)
	{
		var sections = this.orderBlockNode.querySelectorAll('.bx-soa-section.bx-active'),
			firstSection = sections[0],
			lastSection = sections[sections.length - 1],
			currentSection = BX.findParent(node, {className: "bx-soa-section"}),
			isLastNode = false,
			buttons = [];

		if (currentSection && currentSection.id.indexOf(firstSection.id) == '-1')
		{
			buttons.push(
				BX.create('A', {
					props: {
						href: 'javascript:void(0)',
						className: 'pull-left btn btn-default btn-md'
					},
					html: this.params.MESS_BACK,
					events: {
						click: BX.proxy(this.clickPrevAction, this)
					}
				})
			);
		}

		if (currentSection && currentSection.id.indexOf(lastSection.id) != '-1')
			isLastNode = true;

		if (!isLastNode)
		{
			buttons.push(
				BX.create('A', {
					props: {href: 'javascript:void(0)', className: 'pull-right btn btn-default btn-md'},
					html: this.params.MESS_FURTHER,
					events: {click: BX.proxy(this.clickNextAction, this)}
				})
			);
		}
/*
		node.appendChild(
			BX.create('DIV', {
				props: {className: 'row bx-soa-more'},
				children: [
					BX.create('DIV', {
						props: {className: 'bx-soa-more-btn col-xs-12'},
						children: buttons
					})
				]
			})
		); */
	};
	
	
	BX.Sale.OrderAjaxComponentExt.totalBlockScrollCheck = function()
	{
		// убираем скролл тотал блока при прокрутке
	};
	
	BX.Sale.OrderAjaxComponentExt.setCart = function()
	{

		if (this.isValidForm())
		{
			this.allowOrderSave();
				
			if (this.params.USER_CONSENT === 'Y' && BX.UserConsent)
			{
				BX.onCustomEvent('bx-soa-order-save', []);
			}
			else
			{
				this.doSaveAction();
			}
		}
	};
})();