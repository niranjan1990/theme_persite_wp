/* JS File */

// Start Ready
$(document).ready(function() {  

	// Icon Click Focus
	$('div.icon').click(function(){
		$('input#searchkey').focus();
	});

	// Live Search
	// On Search Submit and Get Results
	function search() {
		var query_value = $('input#searchkey').val();
		var url_value = $('input#urlvalue').val();
		var brand_url = $('input#brandurl').val();
		$('b#search-string').text(query_value);
		if(query_value !== ''){
			$.ajax({
				type: "POST",
				url: "/search.php",
				data: { query: query_value, url: url_value, brand_url: brand_url },
				cache: false,
				success: function(html){
					$("ul#results").html(html);
					$("ul#results").find('li:first').addClass('active');
				}
			});
		}return false;    
	}

	$("input#searchkey").on("keyup", function(e) {
		if(e.which == 37 || e.which == 38 || e.which == 39 || e.which == 40){
			return;
		}
		// Set Timeout
		clearTimeout($.data(this, 'timer'));

		// Set Search String
		var search_string = $(this).val();

		// Do Search
		if (search_string == '') {
			$("ul#results").fadeOut();
			$('h4#results-text').fadeOut();
		}else{
			$("ul#results").fadeIn();
			$('h4#results-text').fadeIn();
			//$(this).data('timer', setTimeout(search, 100));
			search();
		};
	});
	
	$(document).keydown(function(e) {
    		switch(e.which) {
				// Added by Siva
				case 27:
					//alert('ESC');
					 $('#searchkey').trigger('blur'); 
					$('#results').hide();
					break;
				case 13:
					 var activeli = $("ul#results").find('li.active');
					 $("ul#results").find('li').removeClass('active');
					 console.log('next: ', activeli.next());
					 if(activeli.next().length > 0)
					 {				
						 //activeli.next().addClass('active');
						 var item = activeli.find('h3').text();
						 var href = activeli.find('a').attr('href');
						 console.log('item: ', item);
						 setSearchedProduct(item);
						 //alert(href);
						 window.location=href;
					}
					else
					{
						$("ul#results").find('li:first').addClass('active');
					}
					break;
				// Added by siva
				
        		case 37: // left

        		break;

		        case 38: // up
				var activeli = $("ul#results").find('li.active');
                                $("ul#results").find('li').removeClass('active');
                                 console.log('next: ', activeli.prev());
                                 if(activeli.prev().length > 0){
                                 activeli.prev().addClass('active');
                                 var item = activeli.prev().find('h3').text();
                                 console.log('item: ', item);
                                 setSearchedProduct(item);
                                //alert('key down');
                                }else{
                                        $("ul#results").find('li:last').addClass('active');
                                }
        		break;

        		case 39: // right
        		break;

		        case 40: // down
				 var activeli = $("ul#results").find('li.active');
				 $("ul#results").find('li').removeClass('active');
				 console.log('next: ', activeli.next());
				 if(activeli.next().length > 0){				
				 activeli.next().addClass('active');
				 var item = activeli.next().find('h3').text();
				 console.log('item: ', item);
				 setSearchedProduct(item);
				//alert('key down');
				}else{
					$("ul#results").find('li:first').addClass('active');
				}
		        break;

		        default: return; // exit this handler for other keys
    }
    e.preventDefault(); // prevent the default action (scroll / move caret)
});
	function setSearchedProduct(item){
		$('#searchkey').val(item);
	}
});
