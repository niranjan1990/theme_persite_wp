	
	function $(id) {
		
		return document.getElementById(id);
	}
	
	function showLetter(v) {
	
		var letters = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];		
		for( var i=0; i < letters.length; i++ ) {
				
			if(document.getElementById('let-'+letters[i]) != null || document.getElementById('let-'+	[i]) != undefined) {
							
				document.getElementById( letters[i] ).style.display = "none";
				document.getElementById('let-'+letters[i]).removeAttribute("class");
			}
		}
			
		document.getElementById(v).style.display = "";
		document.getElementById('let-'+v).setAttribute("class", "sel");
	}
	
	function showLetter1(v1) {
	
		var letters1 = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];	
		for( var i=0; i < letters1.length; i++ ) {
				
			if(document.getElementById('let1-'+letters1[i]+'xyz') != null || document.getElementById('let1-'+letters1[i]+'xyz') != undefined) {
							
				document.getElementById( letters1[i]+'xyz' ).style.display = "none";
				document.getElementById('let1-'+letters1[i]+'xyz').removeAttribute("class");
			}
		}
			
		document.getElementById(v1).style.display = "";
		document.getElementById('let1-'+v1+'xyz').setAttribute("class", "sel");
	}	

	window.onload = function() {
		
		if($('star-rating')) {
			var stars = $('star-rating');
			var lnk = stars.getElementsByTagName('a');
			for(var i=0;i<lnk.length;i++) {
				lnk[i].onmouseout = function() {
					$('rate-text').innerHTML = '&nbsp;';
				}
				lnk[i].onmouseover = function() {
					var txt = this.getAttribute('title');
					$('rate-text').innerHTML = txt;
				}
			}
		}
	}
	
	function rateProduct(id,star) {
		
		doAjaxRequest('/wp-content/themes/site/qikrate.php?id='+id+'&rating='+star);
	}
	
	function doAjaxRequest(url) {

		var request = getHTTPObject();
		if(request) {
			request.open( "GET", url, true );
			request.onreadystatechange = function() {
				if(request.readyState == 4) {
					var r = request.responseText;
					$('rate-text').style.display="none";
					$('rate-result').style.display="";
				
					if(r == 0) {
						$('rate-result').innerHTML="You have QIK rated this previously";
						
					} else {
						
						var v = r.split('|');
						$('rate-result').innerHTML="QIK rating added.";
						$('overall-count').innerHTML="<u>"+v[0]+"</u>";
						$('overall-average').innerHTML=v[1];
						$('quickrate-count').innerHTML=v[2];
						$('quickrate-average').innerHTML=v[3];
					}
				}
			};
			request.send(null);
		} else {
			
			alert("Sorry, your browser doesn't support Ajax.");
		}
	}
 
	function getHTTPObject() {
		
		if(typeof XMLHttpRequest == "undefined")
			XMLHttpRequest = function() {
				try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); }
					catch (e) {}
				try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); }
					catch (e) {}
				try { return new ActiveXObject("Msxml2.XMLHTTP"); }
					catch (e) {}
				return false;
			}
		return new XMLHttpRequest();
	}
	