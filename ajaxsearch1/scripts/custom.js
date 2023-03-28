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
		var query_value = $('input#searchkey1').val();
		var url_value = $('input#urlvalue1').val();
		$('b#search-string1').text(query_value);
		if(query_value !== ''){
			$.ajax({
				type: "POST",
				url: "/search1.php",
				data: { query: query_value, url: url_value },
				cache: false,
				success: function(html){
					$("ul#results1").html(html);
				}
			});
		}return false;    
	}

	$("input#searchkey1").on("keyup", function(e) {
		// Set Timeout
		clearTimeout($.data(this, 'timer'));

		// Set Search String
		var search_string = $(this).val();

		// Do Search
		if (search_string == '') {
			$("ul#results1").fadeOut();
			$('h4#results-text1').fadeOut();
		}else{
			$("ul#results1").fadeIn();
			$('h4#results-text1').fadeIn();
			$(this).data('timer', setTimeout(search, 100));
		};
	});

});