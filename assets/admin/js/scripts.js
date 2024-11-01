jQuery(document).ready(function($) {

	var delay = (function(){
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
	})();


	$(document).on('keyup','#block-keyword',function(){
		_this = this;
		keyword = $(this).val();

		url = window.location.href
		//console.log();
		var url = new URL(url);


		delay(function(){
			$(_this).parent().children('.loading').addClass('button updating-message');

			url.searchParams.append('keyword', keyword);
			url.searchParams.delete('paged');
			window.location.href = url.href;

		}, 1000 );


	})






	$(document).on('click','.block-save',function(){
		_this = this;
		post_id = $(this).attr('data-id');

			$(_this).addClass('updating-message');

			$.ajax({
				type: 'POST',
				context: _this,
				url:wpblockhub_ajax.wpblockhub_ajaxurl,
				data: {
					"action" 		: "wpblockhub_ajax_fetch_block_hub_by_id",
					"wpblockhub_ajax_nonce"	: wpblockhub_ajax.ajax_nonce,
					"post_id" 		: post_id,
				},
				success: function( response ) {
					var data = JSON.parse( response );
					post_data = data['post_data'];
					download_count = data['download_count'];

					$(this).addClass('saved');
					$(this).text('Saved');

					$(_this).removeClass('updating-message');
				}
			});

	})


	$(document).on('mouseover','.block-thumb',function(){



		imgHeight = $(this).children('img').height();
		wrapHeight = $(this).height();

		if (imgHeight > wrapHeight) {
			var animationOffset = wrapHeight - imgHeight;
			var speed = 2000;
			$(this).children('img').animate({
				"marginTop": animationOffset + "px"
			}, speed);
		}


	})


	$(document).on('mouseout','.block-thumb',function(){



		imgHeight = $(this).children('img').height();
		wrapHeight = $(this).height();

		//console.log(wrapHeight);
		//console.log(imgHeight);

		if (imgHeight > wrapHeight) {

			animationOffset = 0;
				var speed = 1000;
				$(this).children('img').animate({
					"marginTop": animationOffset + "px"
				}, speed);

		}


	})


	$(document).on('click','.block-publish',function(){


		//_this = this;
		post_id = $(this).attr('data-post-id');
		data_update = $(this).attr('data-update');

		$(this).addClass('updating-message');


		//console.log(post_id);

		$.ajax({
			type: 'POST',
			context: this,
			url:wpblockhub_ajax.wpblockhub_ajaxurl,
			data: {
				"action" 		: "wpblockhub_ajax_submit_to_wpblockhub",
				"wpblockhub_ajax_nonce"	: wpblockhub_ajax.ajax_nonce,
				"post_id" 		: post_id,
				"data_update" 		: data_update,
			},
			success: function( response ) {
				var data = JSON.parse( response );
				//status = data['status'];
				block_hub_exist = data['block_hub_exist'];
				message = data['message'];

				if(block_hub_exist =='yes'){
					$(this).attr('data-update','yes');
					$(this).text('Update');
				}

				$(this).parent().children('.description').html(message);
				$(this).removeClass('updating-message');
			}
		});

	})





	$(document).on('click','.wpblockhub-import-container #wpblockhub-import-btn',function(){



		if($('.item-list-wrap').hasClass('active')){
			$('.item-list-wrap').removeClass('active');
		}else{

			$('.item-list-wrap').addClass('active');
		}

		wpblockhub_posts = wpblockhub_ajax.wpblockhub_data.data.posts;
		categories = wpblockhub_ajax.wpblockhub_data.data.categories;

		html = '';
		html += '<select class="categories">';
		html += '<option value="">Select category';
		html += '</option>';

		for(cate in categories){

			html += '<option value="'+cate+'">'+categories[cate]['name'];
			html += '</option>';
		}



		html += '</select>';
		html += '<input type="search" value="" placeholder="Start type here..." class="keyword">';
		html += '<ul>';

		for (item in wpblockhub_posts) {
			itemData = wpblockhub_posts[item];
			plugins_required = wpblockhub_posts[item]['plugins_required'];
			//console.log(typeof plugins_required);
			post_id = itemData.post_id;
			thumb = itemData.thumb;
			title = itemData.title;
			//console.log(title);

			if(thumb){
				html += '<li class="item">';
				html += '<span class="item-import button" postid="'+post_id+'">Import</span>';
				html += '<img width="300px" src="'+thumb+'" />';
				html += '<div class="plugins-required">';

				if( typeof plugins_required == 'string'){

					html += '<div class="">No 3rd party plugins required.</div>';
				}else{
					html += '<div class="">Plugins required:</div>';

					for (pluginData in plugins_required) {

						plugin_zip_url = plugins_required[pluginData]['plugin_zip_url'];
						plugin_name = plugins_required[pluginData]['name'];

						html += '<div class="plugins-item">';
						html += '<a href="'+plugin_zip_url+'">'+plugin_name+'</a>';
						html += '</div>';
					}
				}

				html += '</div>';
				html += '<hr>';
				html += '</li>';
			}
		}

		html += '</ul>';
		html += '<div paged="2" class="load-more button">Load more</div>';

		$('.item-list-wrap').html(html);

	})


	$(document).on('change','.item-list-wrap .categories',function(){

		var categories = $('.item-list-wrap .categories').val();
		var keyword = $('.item-list-wrap .keyword').val();
		$('.item-list-wrap .load-more').text('Load more');


		$('.item-list-wrap ul').html('<li class="button loading updating-message">Loading</li>');


		$.ajax({
			type: 'POST',
			context: this,
			url:wpblockhub_ajax.wpblockhub_ajaxurl,
			data: {
				"action" 		: "wpblockhub_ajax_fetch_blockdata",
				"wpblockhub_ajax_nonce"	: wpblockhub_ajax.ajax_nonce,
				"categories" 		: categories,
				"keyword" 		: keyword,
			},
			success: function( response ) {
				var remoteData = JSON.parse( response );

				wpblockhub_posts = remoteData.data.posts;

				html = '';

				for (item in wpblockhub_posts) {
					itemData = wpblockhub_posts[item];
					plugins_required = wpblockhub_posts[item]['plugins_required'];
					//console.log(plugins_required);
					post_id = itemData.post_id;
					thumb = itemData.thumb;
					title = itemData.title;
					//console.log(title);

					if(thumb){
						html += '<li class="item">';
						html += '<span class="item-import button" postid="'+post_id+'">Import</span>';
						html += '<img width="300px" src="'+thumb+'" />';
						html += '<div class="plugins-required">';

						if(typeof plugins_required == 'string'){

							html += '<div class="">No 3rd party plugins required.</div>';
						}else{
							html += '<div class="">Plugins required:</div>';

							for (pluginData in plugins_required) {

								plugin_zip_url = plugins_required[pluginData]['plugin_zip_url'];
								plugin_name = plugins_required[pluginData]['name'];

								html += '<div class="plugins-item">';
								html += '<a href="'+plugin_zip_url+'">'+plugin_name+'</a>';
								html += '</div>';
							}
						}

						html += '</div>';
						html += '<hr>';
						html += '</li>';
					}
				}

				$('.item-list-wrap ul').html(html);
				$('.item-list-wrap .load-more').attr('paged', 2);


			}
		});

	})


	$(document).on('keyup','.item-list-wrap .keyword',function(){

		var categories = $('.item-list-wrap .categories').val();
		var keyword = $('.item-list-wrap .keyword').val();

		$('.item-list-wrap .load-more').text('Load more');


		delay(function(){

			$('.item-list-wrap ul').html('<li class="button loading updating-message">Loading</li>');

			$.ajax({
				type: 'POST',
				context: this,
				url:wpblockhub_ajax.wpblockhub_ajaxurl,
				data: {
					"action" 		: "wpblockhub_ajax_fetch_blockdata",
					"wpblockhub_ajax_nonce"	: wpblockhub_ajax.ajax_nonce,
					"categories" 		: categories,
					"keyword" 		: keyword,
				},
				success: function( response ) {
					var remoteData = JSON.parse( response );

					wpblockhub_posts = remoteData.data.posts;

					html = '';

					for (item in wpblockhub_posts) {
						itemData = wpblockhub_posts[item];
						plugins_required = wpblockhub_posts[item]['plugins_required'];
						post_id = itemData.post_id;
						thumb = itemData.thumb;
						title = itemData.title;

						if(thumb){
							html += '<li class="item">';
							html += '<span class="item-import button" postid="'+post_id+'">Import</span>';
							html += '<img width="300px" src="'+thumb+'" />';
							html += '<div class="plugins-required">';

							if( typeof plugins_required == 'string'){

								html += '<div class="">No 3rd party plugins required.</div>';
							}else{
								html += '<div class="">Plugins required:</div>';

								for (pluginData in plugins_required) {

									plugin_zip_url = plugins_required[pluginData]['plugin_zip_url'];
									plugin_name = plugins_required[pluginData]['name'];

									html += '<div class="plugins-item">';
									html += '<a href="'+plugin_zip_url+'">'+plugin_name+'</a>';
									html += '</div>';
								}
							}

							html += '</div>';
							html += '<hr>';
							html += '</li>';
						}
					}


					$('.item-list-wrap ul').html(html);
					$('.item-list-wrap .load-more').attr('paged', 2);


				}
			});

		}, 1000 );



	})



	$(document).on('click','.item-list-wrap .load-more',function(){

		var categories = $('.item-list-wrap .categories').val();
		var keyword = $('.item-list-wrap .keyword').val();
		var paged = parseInt($(this).attr('paged'));

		$(this).addClass('updating-message');


		$.ajax({
			type: 'POST',
			context: this,
			url:wpblockhub_ajax.wpblockhub_ajaxurl,
			data: {
				"action" 		: "wpblockhub_ajax_fetch_blockdata",
				"wpblockhub_ajax_nonce"	: wpblockhub_ajax.ajax_nonce,
				"categories" 		: categories,
				"keyword" 		: keyword,
				"paged" 		: paged,
			},
			success: function( response ) {
				var remoteData = JSON.parse( response );
				//post_content = data['post_content'];


				wpblockhub_posts = remoteData.data.posts;

				html = '';

				for (item in wpblockhub_posts) {
					itemData = wpblockhub_posts[item];


					plugins_required = wpblockhub_posts[item]['plugins_required'];
					//console.log(plugins_required);
					post_id = itemData.post_id;
					thumb = itemData.thumb;
					title = itemData.title;
					//console.log(title);

					if(thumb){
						html += '<li class="item">';
						html += '<span class="item-import button" postid="'+post_id+'">Import</span>';
						html += '<img width="300px" src="'+thumb+'" />';
						html += '<div class="plugins-required">';

						if( typeof plugins_required == 'string'){

							html += '<div class="">No 3rd party plugins required.</div>';
						}else{
							html += '<div class="">Plugins required:</div>';

							for (pluginData in plugins_required) {

								plugin_zip_url = plugins_required[pluginData]['plugin_zip_url'];
								plugin_name = plugins_required[pluginData]['name'];

								html += '<div class="plugins-item">';
								html += '<a href="'+plugin_zip_url+'">'+plugin_name+'</a>';
								html += '</div>';
							}
						}

						html += '</div>';
						html += '<hr>';
						html += '</li>';
					}
				}


				$('.item-list-wrap ul').append(html);

				if(typeof  wpblockhub_posts != 'undefined'){

					$(this).attr('paged', paged+1);

				}else{
					$(this).text('No more post');
				}

				$(this).removeClass('updating-message');


			}
		});

	})


	$(document).on('click','.item-list-wrap .item .item-import',function(){

		//alert('Hello');
		post_id = $(this).attr('postid');
		$(this).addClass('updating-message');

		$.ajax({
			type: 'POST',
			context: this,
			url:wpblockhub_ajax.wpblockhub_ajaxurl,
			data: {
				"action" 		: "wpblockhub_ajax_fetch_block_hub_by_id",
				"wpblockhub_ajax_nonce"	: wpblockhub_ajax.ajax_nonce,
				"post_id" 		: post_id,
			},
			success: function( response ) {
				var data = JSON.parse( response );
				post_content = data['post_content'];

				//console.log(data);

				if(post_content){
					wp_data = wp.data;
					wp_dispatch = wp_data.dispatch;
					wp_editor = wp_dispatch("core/editor");
					wp_insertBlocks = wp_editor.insertBlocks;
					wp_insertBlocks(wp.blocks.parse(post_content));
				}

				$(this).removeClass('updating-message');

				delay(function(){
					$('.item-list-wrap').removeClass('active');


				}, 1000 );

			}
		});


	})


});




