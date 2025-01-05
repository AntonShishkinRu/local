// вывод сообщений
var block = "";
function view_msg(header_msg, text_msg, block_width, block_color, timeout){
	if (block)
		document.body.removeChild(block);		
		
	block = document.createElement("div");
	block.className = "popup_window_custom";
	var inner_html = "";
	if (header_msg)
		inner_html = "<p>" + header_msg + "</p>";
	if (text_msg)
		inner_html = inner_html + "<p>" + text_msg + "</p>";
	block.innerHTML = inner_html;
	document.body.appendChild(block);
	
	$(".popup_window_custom").width(block_width);
	$(".popup_window_custom").css('borderColor', block_color);
	
	var scroll_func2 = function(){
		if ($(document).scrollTop() >= 1)
			$('.popup_window_custom').css({marginTop: -$('.popup_window_custom').height()/2 + $(document).scrollTop() + 'px'});
		else 
			$('.popup_window_custom').css({marginTop: -$('.popup_window_custom').height()/2 + 'px'});
	};
	
	scroll_func2();
	$(window).scroll(scroll_func2);	
	var win_width = $(window).width();
	var block_width = $(".popup_window_custom").outerWidth();
	if (block_width < win_width) {
		var need_width = (win_width - block_width)/2;
		$(".popup_window_custom").css('left', need_width + 'px');
	} else
		$(".popup_window_custom").css('left', '0px');

	$('.popup_window_custom').animate({opacity:1}, 300);
	setTimeout(function(){
		document.body.removeChild(block);
		block = "";
	},timeout);
}



function add2cart(obj) 
{	
	let p_id = +$(obj).attr('prodid');
	
	if (!p_id) {
		if ($(obj).closest('.data').find('.sizes').length) {
			p_id = +$(obj).closest('.data').find('.sizes input[name="product_size"]:checked').val();
			if (!p_id) view_msg("Пожалуйста, выберите размер.", "", 300, '#565656', 2000);
		}
	}
	
	if (p_id) {
		var thisEl = $(obj);
		if (thisEl.hasClass('active')) {
			var data = "p_id=" + p_id;
			$.ajax({
				type: "POST",
				url: "/local/del_from_cart.php",
				data: data,
				success: function(html){
					$.ajax({
						type: "POST",
						//url: "",
						data: data,
						success: function(html2){
							$('header .icons .favorite').html( $(html2).find('header .icons .favorite').html() );
							$('header .icons .cart').html( $(html2).find('header .icons .cart').html() );
							
							
						}
					})
					
		
					thisEl.removeClass('active');

					$('#add_to_cart_success .info .price').html(prod_obj.find('.price').html());
					
					if (prod_obj.hasClass('product_info')) {
						$('#add_to_cart_success .thumb').html('<div class="img">' + prod_obj.find('.images .big img:first').parents().html() + '</div>');
						$('#add_to_cart_success .info .name').text($('h1:first').text());
						$('#add_to_cart_success .info .weight').text(prod_obj.find('.vol_size:first').text());
					} else {
						$('#add_to_cart_success .thumb').html(prod_obj.find('.thumb').html());
						$('#add_to_cart_success .info .name').text(prod_obj.find('.name a').text());
						$('#add_to_cart_success .info .weight').text(prod_obj.find('.weight').text());
					}
				
				}
			})
		} else {
			var data = "p_id=" + p_id + "&quantity=1&delay=N";
			$.ajax({
				type: "POST",
				url: "/local/add_to_cart.php",
				data: data,
				success: function(html){
					$.ajax({
						type: "POST",
						//url: "",
						data: data,
						success: function(html2){
							$('header .icons .favorite').html( $(html2).find('header .icons .favorite').html() );
							$('header .icons .cart').html( $(html2).find('header .icons .cart').html() );
							
							thisEl.addClass('active');
							
							view_msg("Товар добавлен в корзину", "", 300, '#565656', 2000);
							//if (thisEl.hasClass('buy_btn_el')) thisEl.text('ПЕРЕЙТИ В КОРЗИНУ');
							//thisEl.on('click', function(){location.href='/personal/cart/';});
						}
					});
					
					$('.cart_added_success').hide();
					$('.cart_added_success').html( $(html).find('.cart_added_success_source').html() );
					$('.cart_added_success').fadeIn(200)
				}
			})
		}
	}
};


function add2fav(p_id, obj) {	
	if (p_id) {
		var data = "p_id=" + p_id + "&quantity=1&delay=Y";
		if (!$(obj).hasClass('active')) {
			$.ajax({
				type: "POST",
				url: "/local/add_to_cart.php",
				data: data,
				success: function(html){

					$.ajax({
						type: "POST",
						//url: "",
						data: data,
						success: function(html2){
							$('header .icons .favorite').html( $(html2).find('header .icons .favorite').html() );
							$('header .icons .cart').html( $(html2).find('header .icons .cart').html() );
						}
					})

					$.each($('.favorite_link_prod'), function(){
						if ($(this).attr('prod') == $(obj).attr('prod')){
							$(this).addClass('active');
							//if ($(this).hasClass('favorite_link_prod_item')) $(this).find('span').text('В ИЗБРАННОМ');
						}
					});
				}
			})
		} else {
			$.ajax({
				type: "POST",
				url: "/local/del_from_cart.php",
				data: data,
				success: function(html){
					$.ajax({
						type: "POST",
						//url: "",
						data: data,
						success: function(html2){
							$('header .icons .favorite').html( $(html2).find('header .icons .favorite').html() );
							$('header .icons .cart').html( $(html2).find('header .icons .cart').html() );
						}
					})

					
					$.each($('.favorite_link_prod'), function(){
						if ($(this).attr('prod') == $(obj).attr('prod')){
							$(this).removeClass('active');
							//if ($(this).hasClass('favorite_link_prod_item')) $(this).find('span').text('В ИЗБРАННОЕ')
						}
					});
					
					if (window.location.pathname == '/wishlist/') {
						$(obj).closest('.product').fadeOut(300);
						setTimeout(
							function(){
								$(obj).closest('.product').detach();
								if(!$('#wishlist_block .product').length) location.reload();
							}, 500
						)
					}
				}
			})
		}
	}
};


function checkUserFields(obj){
	
	var valid_flag = 1;
	var form_obj = $(obj).closest('form');
	form_obj.find('.error_text').detach();
	form_obj.find('.error_custom, .error_custom_checkbox').detach();
	$.each(form_obj.find('input, textarea'), function(){
		if ($(this).attr('name') != "NEW_PASSWORD" && $(this).attr('name') != "NEW_PASSWORD_CONFIRM") {
			if ($(this).attr('name') == "agree") { 
				if ($(this).is(":checked"));
				else {
					$(this).addClass('error');
					$(this).parent().after('<label class="error_custom error_custom_checkbox">Согласие обязательно</label>');
					valid_flag = 0;	
				}
			} else if ($(this).attr('required')) {
				if(!(/[\S]+/.test($(this).val()))){
					$(this).addClass('error');
					$(this).after('<label class="error_text">Не заполнено поле "' + $(this).attr('title') + '"</label>');
					valid_flag = 0;
				} else {
					if ($(this).attr('name') == "NAME") {
						if (!(/^[А-Яа-яA-Za-z\s]+$/.test($(this).val()))){
							$(this).addClass('error');
							$(this).after('<label class="error_text">Поле "' + $(this).attr('title') + '" заполнено неверно!</label>');
							valid_flag = 0;
						}
					} else if ($(this).attr('name') == "EMAIL") {
						if(!(/^[-a-zA-Z0-9!#$%&'*+/=?^_`{|}~]+(\.[-a-zA-Z0-9!#$%&'*+/=?^_`{|}~]+)*@([a-zA-Z0-9]([-a-zA-Z0-9]{0,61}[a-zA-Z0-9])?\.)*(aero|arpa|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel|club|[a-zA-Z][a-zA-Z])$/.test($(this).val()))){
							$(this).addClass('error');
							$(this).after('<label class="error_text">Поле "E-mail" заполнено неверно!</label>');
							valid_flag = 0;
						}
					} else if ($(this).attr('name') == "PERSONAL_PHONE") {
						if(!(/^[\+\-\(\)\s0-9]{12,20}$/.test($(this).val()))){
							$(this).addClass('error');
							$(this).after('<label class="error_text">Поле "Телефон" заполнено неверно!</label>');
							valid_flag = 0;
						}
					}
				} 
			}
		}
	})
	

	
	if (valid_flag) { 
		$('.personal_form input[name="PERSONAL_PHONE"]').val( $('.personal_form input[name="PERSONAL_PHONE_REC"]').val().replace(/[^+\d]/g, '') );
		

		//form_obj.submit();
		return true;
	} else {
		//$('label.error').detach();
	/*	setTimeout(function(){
			setTimeout(function(){$('input').removeClass('error');}, 300);
			form_obj.find('.error_text').detach();
		}, 3000);*/
		return false; 
	}
}

function checkPasswordFields(){
	var valid_flag = 1;
	
	var form_obj = $('.change_password_form');
	form_obj.find('label.error_custom').detach();
	
	$.each($('.change_password_form input[type="password"]'), function(){
		if(!(/[\S]+/.test($(this).val()))){
			$(this).addClass('error');
			$(this).after('<label class="error_custom">Не заполнено поле "' + $(this).attr('title') + '"</label>');
			valid_flag = 0;
		} else {
			if ($(this).attr('name') == "NEW_PASSWORD") {
				if (!(/[\S]{6,100}/.test($(this).val()))){
					$(this).addClass('error');
					$(this).after('<label class="error_custom">Поле "Пароль" заполнено неверно (минимум 6 символов или цифр)!</label>');
					valid_flag = 0;
				}
			}  else if ($(this).attr('name') == "NEW_PASSWORD_CONFIRM") {
				if ($(this).val() != $('input[name="NEW_PASSWORD"]').val()) {
					$(this).addClass('error');
					$(this).after('<label class="error_custom">Поля "Пароль" и "Подтверждение пароля" не совпадают!</label>');
					valid_flag = 0;	
				}
			}
		}
	})

	if (valid_flag) {
		/*view_msg("Пароль изменен!", "", 300, '#565656', 2000);
		setTimeout(function(){
			return true;
		}, 300); */
		$('.personal_form input[type="text"], .personal_form input[type="email"]').attr('disabled', 'disabled');
		return true;
		
	} else {
		/*setTimeout(function(){
			$('label.error_custom').fadeOut(300);
			setTimeout(function(){$('.error_custom').detach(); $('.error').removeClass('error');}, 300);
		}, 3000); */
		return false; 
	}
}



function getNextPage(url) {
	if (url) {
		$.ajax({
			type: "GET",
			url: url,
			success: function(html){
				$('.products > .row').append($(html).find('.products > .row').html());
				
				/*
				setTimeout(() => {
					observer = lozad('.products > .row .lozad, .products > .list .lozad', {
						rootMargin: '200px 0px',
						threshold: 0,
						loaded: (el) => el.classList.add('loaded')
					})

					observer.observe(),
					
					
						$('.products .list .product .big_slider').owlCarousel({
							items: 1,
							margin: 20,
							loop: false,
							smartSpeed: 500,
							dots: false,
							onTranslate: event => {
								let parent = $(event.target).closest('.images')

								parent.find('.thumbs .slide button').removeClass('active')
								parent.find('.thumbs .slide:eq(' + event.item.index + ') button').addClass('active')
							},
							responsive: {
								0: {
									nav: true
								},
								768: {
									nav: false
								}
							}
						})

						$('.products .list .product .thumbs_slider').bxSlider({
							mode: 'vertical',
							infiniteLoop: false,
							speed: 500,
							slideMargin: 17,
							minSlides: 3,
							maxSlides: 3,
							moveSlides: 1,
							pager: false
						})

						$('.products .list .product .thumbs .slide button').click(function (e) {
							e.preventDefault()

							let parent = $(this).closest('.images')

							parent.find('.big_slider').trigger('to.owl.carousel', $(this).data('slide-index'))
						})
				}, 200)  */
				
				$('.products > .pagination').html('');
				$('.products > .pagination').html($(html).find('.products > .pagination').html());
			}
		});
	}
	return false;
}

$(function(){
	$('#top_banner_main .close_btn').on('click', function(){
		localStorage.setItem("top_banner_main", $('#top_banner_main').attr('cache_length'));
	});
	
	$('.more_footer_cat').on('click', function(e){
		e.preventDefault();
		
		$(this).closest('.items').find('a').slideDown(300);
		$(this).detach();
	});
	
	
	
	$('select[name="count"]').on('change', function(){
		var need_url = document.location.search;
		var need_val = $(this).val();
		if (need_val) {
			if(need_url){
				if(need_url.match(/count=[0-9]*/))
					need_url = need_url.replace(/count=[0-9]*/, "count=" + need_val);
				else
					need_url = need_url + "&count=" + need_val;
			} else
				need_url = "?count=" + need_val;	
		} else {
			if(need_url){
				if(need_url.match(/count=[0-9]*/))
					need_url = need_url.replace(/count=[0-9]*/, "");
			}
		}
		document.location.search = need_url;
	})
	
	$('select[name="sort"]').on('change', function(){
		var need_url = document.location.search;
		var need_val = $(this).val();
		if (need_val) {
			if(need_url){
				if(need_url.match(/sort=[A-aZ-z]*/))
					need_url = need_url.replace(/sort=[A-aZ-z]*/, "sort=" + need_val);
				else
					need_url = need_url + "&sort=" + need_val;
			} else
				need_url = "?sort=" + need_val;	
		} else {
			if(need_url){
				if(need_url.match(/sort=[A-aZ-z]*/))
					need_url = need_url.replace(/sort=[A-aZ-z]*/, "");
			}
		}
		document.location.search = need_url;
	})
	
	
	
	$('#register_modal .submit_btn').on('click', function(){
		let valid_flag = 1;
		
		let form_obj = $(this).closest('form');
		form_obj.find('label.error_custom').detach();
		form_obj.find(".my_ok_text").text('');
		form_obj.find(".error_text_form").text('');
		$.each(form_obj.find('input, textarea'), function(){
			if ($(this).attr('name') == "agree") { 
				if ($(this).is(":checked"));
				else {
					$(this).addClass('error');
					$(this).parent().after('<label class="error_custom error_custom_checkbox">Согласие обязательно</label>');
					valid_flag = 0;	
				}
			} else if ($(this).attr('required')) {
				if(!(/[\S]+/.test($(this).val()))){
					$(this).addClass('error');
					$(this).after('<label class="error_custom">Не заполнено поле "' + $(this).attr('title') + '"</label>');
					valid_flag = 0;
				} else {
					if ($(this).attr('name') == "user[NAME]") {
						if (!(/^[А-Яа-яA-Za-z\s]+$/.test($(this).val()))){
							$(this).addClass('error');
							$(this).after('<label class="error_custom">Поле "' + $(this).attr('title') + '" заполнено неверно!</label>');
							valid_flag = 0;
						}
					} else if ($(this).attr('name') == "user[EMAIL]") {
						if(!(/^[-a-zA-Z0-9!#$%&'*+/=?^_`{|}~]+(\.[-a-zA-Z0-9!#$%&'*+/=?^_`{|}~]+)*@([a-zA-Z0-9]([-a-zA-Z0-9]{0,61}[a-zA-Z0-9])?\.)*(aero|arpa|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel|club|[a-zA-Z][a-zA-Z])$/.test($(this).val()))){
							$(this).addClass('error');
							$(this).after('<label class="error_custom">Поле "E-mail" заполнено неверно!</label>');
							valid_flag = 0;
						}
					} else if ($(this).attr('name') == "user[PERSONAL_PHONE]") {
						if(!(/^[\+\-\(\)\s0-9]{12,20}$/.test($(this).val()))){
							$(this).addClass('error');
							$(this).after('<label class="error_custom">Поле "Телефон" заполнено неверно!</label>');
							valid_flag = 0;
						}
					} else if ($(this).attr('name') == "user[PASSWORD]") {
						if (!(/[\S]{6,100}/.test($(this).val()))){
							$(this).addClass('error');
							$(this).after('<label class="error_custom" style="position:initial">Поле "Пароль" заполнено неверно (минимум 6 символов или цифр)!</label>');
							valid_flag = 0;
						}
					}  else if ($(this).attr('name') == "user[CONFIRM_PASSWORD]") {
						if ($(this).val() != form_obj.find('input[name="user[PASSWORD]"]').val()) {
							$(this).addClass('error');
							$(this).after('<label class="error_custom">Пароли не совпадают!</label>');
							valid_flag = 0;	
						}
					}  else if ($(this).attr('name') == "aggreement") {
						if ($(this).is(":checked"));
						else {
							$(this).parent().after('<label class="error_custom">Согласие обязательно</label>');
							valid_flag = 0;	
						}
					}
				}
			}
		})

		if (valid_flag) {
			var sForm = form_obj.serialize();
			$.ajax({
				url: '/local/auth.php',
				type: 'post',
				dataType: 'json',
				data: sForm,
				success: function (ob) {
					if (ob.status == 'success') {
						view_msg("Поздравляем! Вы успешно зарегистрированы.", "", 300, '#565656', 2000);
						$('.is-close-btn').click();
					} else {
						form_obj.find(".error_text_form").html(ob.error_text);
						setTimeout(function(){
							form_obj.find(".error_text_form").html('');
						}, 3000);
					}
				}
			});
			
		} else {
			/*setTimeout(function(){
				$('label.error_custom').fadeOut(300);
				setTimeout(function(){$('.error_custom').detach(); $('.error').removeClass('error');}, 300);
			}, 3000);*/
		}
		
		return false;
	});
	
	
	$("#login_modal button[type='submit']").on('click',function(e){
		e.preventDefault();

		let valid_flag = 1;
		let form_obj = $(this).closest('form');
		form_obj.find('label.error_custom').detach();
		$("#login_modal .error_text").html('');
	
		$.each($('#login_modal input'), function(){
			if ($(this).attr('required')) {
				if(!(/[\S]+/.test($(this).val()))){
					$(this).after('<label class="error_custom">Не заполнено поле "' + $(this).attr('title') + '"</label>');
					valid_flag = 0;
				} else {
					if ($(this).attr('name') == "NAME") {
						if(!(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test($(this).val()))){
							$(this).addClass('error');
							$(this).after('<label class="error_custom">Поле "E-mail" заполнено неверно!</label>');
							valid_flag = 0;
						}
					} else if ($(this).attr('name') == "PASSWORD") {
						if (!(/[\S]{6,100}/.test($(this).val()))){
							$(this).addClass('error');
							$(this).after('<label class="error_custom">Поле "Пароль" заполнено неверно (минимум 6 символов или цифр)!</label>');
							valid_flag = 0;
						}
					}  
				}
			}
		})

		if (valid_flag) {
			$.ajax({
				url: '/local/auth.php',
				type: 'post',
				dataType: 'json',
				data: $("#login_modal form").serialize(),
				success: function (ob) {
					if (ob.status == 'success') {
						 $.ajax({
							url: '/local/auth.php',
							type: 'post',
							dataType: 'json',
							data: $("#login_modal form").serialize(),
							success: function (ob) {
								location.reload();
							}
						});
						
						
					} else {
						$("#login_modal .error_text").html(ob.error_text);
					}
				}
			});
		} else {
			setTimeout(function(){
				$('label.error').fadeOut(300);
				setTimeout(function(){$('.error').removeClass('error');}, 300);
			}, 3000);
			return false; 
		}
	});
	
	
	
	$("#forgot_form button[type='submit']").on('click',function(e){
		e.preventDefault();
		
		var valid_flag = 1;
		var form_obj = $('#forgot_form');
		form_obj.find('label.error_custom').detach();
		
		$.each($('#forgot_form input'), function(){
			if ($(this).attr('required')) {
				if(!(/[\S]+/.test($(this).val()))){
					$(this).after('<label class="error_custom">Не заполнено поле "' + $(this).attr('title') + '"</label>');
					valid_flag = 0;
				} else {
					if ($(this).attr('name') == "EMAIL") {
						if(!(/^[-A-Za-z0-9!#$%&'*+/=?^_`{|}~]+(\.[-A-Za-z0-9!#$%&'*+/=?^_`{|}~]+)*@([A-Za-z0-9]([-A-Za-z0-9]{0,61}[A-Za-z0-9])?\.)*(aero|arpa|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel|club|[A-Za-z][A-Za-z])$/.test($(this).val()))){
							$(this).addClass('error');
							$(this).after('<label class="error_custom">Поле "E-mail" заполнено неверно!</label>');
							valid_flag = 0;
						}
					} 
				}
			}
		})

		if (valid_flag) {
			var sForm = $("#forgot_form").serialize();
			$.ajax({
				url: '/local/auth.php',
				type: 'post',
				dataType: 'json',
				data: sForm,
				success: function (ob) {
					if (ob.status == 'success') {
						view_msg('Перейдите в указанный почтовый ящик для получения дальнейших инструкций по восстановлению пароля.', "", 300, '#565656', 2000);
						$('.is-close-btn').click();
					} else {
						$("#forgot_form .error_text").html(ob.error_text);
						setTimeout(function(){
							$("#forgot_form .error_text").html('');
						}, 4000);
					}
				}
			});
		} else {
			/*setTimeout(function(){
				$('label.error_custom').fadeOut(300);
				setTimeout(function(){$('.error_custom').detach(); $('.error').removeClass('error');}, 300);
			}, 3000);*/
			return false; 
		}
	})
	
	$('input').on('input', function(){ 
		$(this).parent().parent().find('.error_custom, .error_text').detach();
		$(this).removeClass('error');
	})
	
	$('input[type=checkbox]').on('click', function(){
		$(this).parent().parent().find('.error_custom, .error_text').detach();
		$(this).removeClass('error');
	})
	
	
	var hide_file = true;
	$('#org_form .submit_btn').on('click', function(){
		if (hide_file && !$('#file_lk input[name="UF_FILE"]').val())
			$('#file_lk input[name="UF_FILE"]').attr('disabled', 'disabled');
	});
	
	$('#file_lk input[name="UF_FILE"]').on('change', function(){
		setTimeout(function(){
			let file_val = $('#file_lk input[name="UF_FILE"]').val();
			if (file_val) {
				$('#file_lk span').text(file_val);
			} else {
				$('#file_lk span').text("ПРИКРЕПИТЬ ФАЙЛ С РЕКВИЗИТАМИ");
			}
		}, 10);
	});
	
	$('#org_form .remove_btn').on('click', function(){
		$(this).closest('.selected').fadeOut(300);
		hide_file = false;
	})
	
	// отправка формы "Намекнуть"
	$('#send_gift .submit_btn').on('click', function(){
		let valid_flag = 1;
		
		let form_obj = $(this).closest('form');
		form_obj.find('label.error_custom').detach();
		$.each(form_obj.find('input, textarea'), function(){
			if ($(this).attr('name') == "agree") { 
				if ($(this).is(":checked"));
				else {
					$(this).addClass('error');
					$(this).parent().after('<label class="error_custom error_custom_checkbox">Согласие обязательно</label>');
					valid_flag = 0;	
				}
			} else if ($(this).attr('required')) {
				if(!(/[\S]+/.test($(this).val()))){
					$(this).addClass('error');
					$(this).after('<label class="error_custom">Не заполнено поле "' + $(this).attr('title') + '"</label>');
					valid_flag = 0;
				} else {
					if ($(this).attr('name') == "name" || $(this).attr('name') == "hint_name") {
						if (!(/^[А-Яа-яA-Za-z\s]+$/.test($(this).val()))){
							$(this).addClass('error');
							$(this).after('<label class="error_custom">Поле "' + $(this).attr('title') + '" заполнено неверно!</label>');
							valid_flag = 0;
						}
					} else if ($(this).attr('name') == "email") {
						if(!(/^[-a-zA-Z0-9!#$%&'*+/=?^_`{|}~]+(\.[-a-zA-Z0-9!#$%&'*+/=?^_`{|}~]+)*@([a-zA-Z0-9]([-a-zA-Z0-9]{0,61}[a-zA-Z0-9])?\.)*(aero|arpa|asia|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel|club|[a-zA-Z][a-zA-Z])$/.test($(this).val()))){
							$(this).addClass('error');
							$(this).after('<label class="error_custom">Поле "E-mail" заполнено неверно!</label>');
							valid_flag = 0;
						}
					} 
				}
			}
		})

		if (valid_flag) {
			var sForm = form_obj.serialize();
			$.ajax({
				url: '/local/send_gift.php',
				type: 'post',
				dataType: 'json',
				data: sForm,
				success: function (ob) {
					if (ob.status == 'success') {
						view_msg("Ваще сообщение успешно отправлено!", "", 300, '#565656', 2000);
						$('.is-close-btn').click();
					} else {
						view_msg("К сожалению, сообщение отправить не удалось.", "", 300, '#565656', 2000);
					}
				}
			});
		}
		
		return false;
	});
	
	if (delay_cart_items) {
		$.each($('.favorite_link_prod'), function(){
			if ($.inArray(parseInt($(this).attr('prod')), delay_cart_items) != -1) {
				$(this).addClass('active');
			}
		});
	}
});