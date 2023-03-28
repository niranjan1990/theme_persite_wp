var imageCREdit;(
	function(zaved){		
		imageCREdit={
		
			insertCR:function(h, d, url, img, imgname){
			
				var vt=zaved("#img_view_btn-"+h),
				t=zaved("#media-head-"+h),
				m=zaved(".imgTitleTr-"+h),
				v=zaved('#imgTitle-'+h),
				p=zaved('#imgedit-response-'+h),
				n=zaved('.media-button-action-'+h),
				g=vt.siblings("img"),
				td=zaved("#imgedit-panel-"+h),
				i=zaved("#image-editor-"+h);
				g.css("visibility","visible");
				
				
				f={ action:"image-editor", _ajax_nonce:d, postid:h,"do":"open", path:img, imgname:imgname};
				zaved("#image-editor-"+h).load( url, f, function(){
					i.fadeIn("fast");
					t.fadeOut("fast",function(){
						
						t.attr("disabled","disabled");
						t.css("display","none");					
						p.removeClass("toggelClassName");
						p.attr('style','border:none');
						//imageCREdit.setTD();
					})
					imageEdit.open(h,d);
					g.css("visibility","hidden");
					n.removeClass("toggelClassName");
					//n.attr('style','border-bottom:none');
					//td.attr('style','border-bottom:none');
				})			
			},
			
			setDisabled:function(h, d){
				
				t=zaved("#media-head-"+h),
				m=zaved("#img_view_btn_id"),
				p=zaved('#imgedit-response-'+h),				
				i=zaved("#image-editor-"+h);
					
					m.fadeIn("fast");
					m.fadeOut("fast",function(){
						t.removeAttr("disabled","disabled");
						t.css("display", "");
						i.removeAttr("disabled","");
						i.css("display", "");
						p.addClass("toggelClassName");
					})
			},
			
			setTD:function(){
					
					var tableID = 'imgedit-panel-*';
					var table = document.getElementById(tableID); 
					var rowCount = table.rows.length;
					var row = table.insertRow(rowCount);
					alert("rowCount "+rowCount);
				
			},
			
			insertIMG : function(src, name, width, height) {				
				
				var align = 'center';
				var caption = name;
				
				html = '<img alt="'+name+'" src="'+src+'"title='+name+' width="'+width+'" height="'+height+'" />';
				
				if ( src)
				
					html = '<a href="'+src+'">'+html+'</a>';					
					html = '[caption id="" align="'+align+'" width="'+width+'" caption="'+caption+'"]'+html+'[/caption]';
					var win = window.dialogArguments || opener || parent || top;
					win.send_to_editor(html);				
					return false;
			}
		}
		
})(jQuery);