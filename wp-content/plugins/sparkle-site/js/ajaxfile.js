	jQuery(function (zaved) {
		
		var CSS = [];
		
		AjaxFile = {
			
			load: function () {
				
				CSS.length = 0;				
				//check if checkbox is checked
				if ( zaved("#fontfamily").is(':checked') ) {
				
					zaved('.thishastodisable').attr('disabled', true);
					zaved('.thishastodisable').attr('style', 'color:#BBBBBB; border-color:#666666;' );
				}
				if(zaved('#enable3d').is(':checked'))
				{
					zaved('#3dtop').attr('enabled', true);
					zaved('#3dbottom').attr('enabled', true);
				}
				else
				{
					zaved('#3dtop').attr('disabled', true);
					zaved('#3dbottom').attr('disabled', true);
				}
				if( zaved('#viewofchildmenu :option').size() == 0 ) {
				
					zaved("#viewofchildmenu").html( "<option value=''>Please Select Parent Menu</option>" );
					zaved('#viewofchildmenu').attr ('disabled', 'disabled');
				}
				else {
					
					zaved('#viewofchildmenu').removeAttr ('disabled');
				}
				
				if ( zaved('#seleditparentmenu :option').size() == 0 ) {
					
					zaved("#seleditparentmenu").html( "<option value=''>Please Select Parent Menu</option>" );
					zaved('#seleditparentmenu').attr ('disabled', 'disabled');
				}
				else {
					
					zaved('#seleditparentmenu').removeAttr ('disabled');
				}
				
				if ( zaved('#parent_menu_id :option').size() == 0 ) {
					
					zaved("#parent_menu_id").html( "<option value=''>Please Select Parent Menu</option>" );
					zaved('#parent_menu_id').attr ('disabled', 'disabled');
					zaved('#position').attr ('disabled', 'disabled');				
				}
				else {
				
					zaved('#parent_menu_id').removeAttr ('disabled');
					zaved('#position').removeAttr ('disabled');
				}
				
				if ( zaved('#selchildmenu :option').size() == 0 ) { //selchildmenu
					
					zaved('#selchildmenu').attr ('disabled', 'disabled');
				}
				else {
				
					zaved('#selchildmenu').removeAttr ('disabled');
				}				
			},
			
			DisplayParentPageUrl: function( page ) {
				
				alert("DisplayParentPageUrl");
			},
			
			FormCancelChild: function() {
			
				location.href = "?page=site-review-sparkle-site.php&tab=menus&action=editmenusproperties";			
			},
			
			FormCancelParent: function() {
			
				location.href = "?page=site-review-sparkle-site.php&tab=menus&action=editmenusproperties";			
			},
			
			HomeValidation: function() {
			
				zaved("#txthome").val('Home');
			},
			
			GetPageTemplate: function() {
			
				var t = zaved("#parent_drop_page_temp").val();
				
				if( t == 'new' ) {
				
					zaved("#txtTemplateFile").removeAttr('disabled');
					zaved("#txtTemplateFile").val('');
					var menuname = zaved("#txtmenubox").val();
					var mname = menuname.replace(/ /g,'-');
					var fname = ( menuname != '' ) ? "page-"+mname.toLowerCase()+".php" : '';
					
					zaved("#txtTemplateFile").val( fname );
					zaved("#hdnTemplateFile").val( fname );
				}
				else {
				
					zaved("#hdnTemplateFile").val( t );
					zaved("#txtTemplateFile").val( t );
					zaved("#txtTemplateFile").attr( "disabled", "disabled");
				}				
			},
			
			GroupColorCode: function() {
				
				var textComponent = document.getElementById('CodeMainMenuGradient');
				var BckgroundColor = zaved('#BckgroundColor').val();
				var selectedText;
				  
				// IE version
				if (document.selection != undefined) {
			  
					textComponent.focus();
					var sel = document.selection.createRange();
					selectedText = sel.text;
				}
				// Mozilla version
				else if (textComponent.selectionStart != undefined ) {
			  
					var startPos = textComponent.selectionStart;
					var endPos = textComponent.selectionEnd;
					selectedText = textComponent.value.substring(startPos, endPos);
				}
				  
				if( selectedText == '' ) {
				  
				  alert("Please select the background color code");
				}
				else {
					
					zaved('#BckgroundColor').val( "#"+selectedText);
				}
			},
			
			BestGolfClubsGradient: function() {
			
				var idholder = '';
				idholder += "<tr><td colspan='3'>&nbsp;</td></tr>";
				idholder += "<tr><td align='center' colspan='3'>- <b>Best Golf Clubs Gradient</b> -</td></tr>";
				idholder += "<tr><td colspan='3'><textarea id='BestGolfClubsGradient' style='resize:none' cols='42' rows='8'></textarea></td></tr>";
				idholder += "<tr><td colspan='3'>&nbsp;</td></tr>";
				idholder += "<tr><td align='center'><input type='button' Onclick='close_modal_window()' value='Cancel'  /></td><td align='center'><input type='button' OnClick='AjaxFile.OpenUltimateCSSGradientGenerator()' value='Ultimate CSS Gradient Generator'  /></td><td align='center'><input type='button' onClick='AjaxFile.ChangeBestGolfClubsGradient();' value='Save'  /></td></tr>";
				zaved("#tblDialogBoxGradient").html( idholder );			
			},			
			
			ChangeBestGolfClubsGradient: function() {
			
				var BestGolfClubsGradient = zaved("#BestGolfClubsGradient").val();
				if( BestGolfClubsGradient == '' ) {
				
					alert("Please enter the Gradient Code.");
				}
				else {						
					
					var self = this;
					var FoundBestGolfColor = ( BestGolfClubsGradient != undefined ) ? self.GetColorCode(BestGolfClubsGradient) : "";					
					zaved('.best-golf-clubs-sample').attr( 'style', BestGolfClubsGradient );
					CSS['BestGolfClubsGradient'] = BestGolfClubsGradient;
					close_modal_window();
				}
			},
			
			MainMenuGradient: function() {
			
				var idholder = '';
				idholder += "<tr><td colspan='3'>&nbsp;</td></tr>";
				idholder += "<tr><td align='center' colspan='3'>- <b>Main Menu Gradient</b> -</td></tr>";
				idholder += "<tr><td colspan='3'><textarea id='CodeMainMenuGradient' style='resize:none' cols='42' rows='8'></textarea></td></tr>";
				idholder += "<tr><td colspan='3'>&nbsp;</td></tr>";
				idholder += "<tr><td align='center'><input type='button' Onclick='close_modal_window()' value='Cancel'  /></td><td align='center'><input type='button' OnClick='AjaxFile.OpenUltimateCSSGradientGenerator()' value='Ultimate CSS Gradient Generator'  /></td><td align='center'><input type='button' onClick='AjaxFile.ChangeMainMenuGradient();' value='Save'  /></td></tr>";
				zaved("#tblDialogBoxGradient").html( idholder );
			},
			
			OpenUltimateCSSGradientGenerator: function() {
			
				url = "http://www.colorzilla.com/gradient-editor/";
				window.open( url, '_blank' );
			},
			
			ChangeMainMenuGradient: function() {
				
				var CodeMainMenuGradient = zaved("#CodeMainMenuGradient").val();
				var extra1 = '';
				
				if( CodeMainMenuGradient == '' ) {
				
					alert("Please enter the Gradient Code.");
				}
				else {
				
					var self = this;
					var FoundColor = ( CodeMainMenuGradient != undefined ) ? self.GetColorCode(CodeMainMenuGradient) : "";				
					
					var extra1 = 'border-radius:5px 5px 5px 5px;';
					extra1 = extra1 + CodeMainMenuGradient;
					//Change Main Menu Background Gradient Code
					zaved('.sample-site-navigation').attr( 'style', extra1 );
					CSS['MainMenuGradient'] = extra1;
					
					if ( zaved("#fontfamily").is(':checked') ) {
						
						//Change Article Header Background Gradient Code
						var extra2 = 'border-top-left-radius:5px; border-top-right-radius:5px;';
						extra2 = extra1 + CodeMainMenuGradient;
						zaved('.text-header-sample').attr( 'style', extra2 );
						CSS['ArticleHeaderGradient'] = extra2;
						
						//Change Article Title Text Color
						zaved('.headertxtcolor a').css('color', '#'+FoundColor );
						CSS['ArticleTitleTextColor'] = "#"+FoundColor;
						
						//Change Save Bottom Divider Color
						var extra3 = 'background:none; background-color:#'+ FoundColor + '; margin:0px; height:8px;';						
						zaved('.bottomdivider').attr('style', extra3 );
						CSS['SaveBottomDividerColor'] = extra3;
						
						//Change Circle Arrow Image Color
						var extra4 = "display: inline-block; height: 15px; padding-right: 20px; color: #"+FoundColor+";";
						CSS['CircleArrowImage']	  = "#"+FoundColor;
						zaved('a.arrow-right').attr( 'style', extra4 );
					}
					
					close_modal_window();
				}
			},
			
			ChangeSubMenuGradientHeader: function() {
				
				var CodeSubMenuGradient = zaved("#idSubMenuGradient").val();
				
				if( CodeSubMenuGradient == '' ) {
				
					alert("Please enter the Gradient Code.");
				}
				else {				
					
					var self = this;
					var FoundSubColor = ( CodeSubMenuGradient != undefined ) ? self.GetColorCode(CodeSubMenuGradient) : "";
					zaved('.header_sub_menu_sample').attr('style', CodeSubMenuGradient );
					CSS['SubMenuGradient'] = CodeSubMenuGradient;
					//Add White Triangle along with color
					CSS['WhiteTriangle'] = "#"+FoundSubColor;
					
					if ( zaved("#fontfamily").is(':checked') ) {
						
						//Change Featured Golf Deals Background Color
						zaved('.featured-golf-deals-sample').attr('style', 'background-color: #'+FoundSubColor + ';' );
						CSS['FeaturedGolfDealsBackgroundColor'] = "#"+FoundSubColor;
						
						//Change Best Golf Clubs Gradient
						zaved('.best-golf-clubs-sample').attr( 'style', CodeSubMenuGradient );
						CSS['BestGolfClubsGradient'] = CodeSubMenuGradient;					
					}
					
					close_modal_window();
				}
			},
			
			CircleArrowImage: function() {
				
				var self = this;
				var dialogModal = $('#cp-dialog-modal').dialog({
			
					autoOpen:	false,	minWidth:	500, modal:	true, title : 'Circle Arrow Image',
					buttons:	{'Cancel': function() {
				
						$(this).dialog('close'); }, 'Save': function() { self.SaveCircleArrowImage();  }
					}
				});				
				$('.cp-onclick').colorpicker({ showOn:'click', inlineFrame:false, color:"#ffffff" });
				dialogModal.dialog('open');		
			},
			
			SaveCircleArrowImage: function() {
			
				var color = '';
				var extra1 = '';
				var self = this;				
				color = zaved(".ui-colorpicker-hex-input").val();
				extra1 = "display: inline-block; height: 15px; padding-right: 20px; color: #"+color+";";
				zaved('a.arrow-right').attr( 'style', extra1 );
				CSS['CircleArrowImage'] = "#"+color;
				self.CloseDialogModalWindow();				
			},
			
			CloseDialogModalWindow: function() {
				
				dialogModal = $('#cp-dialog-modal').dialog({});
				dialogModal.dialog('close');
			},
			
			/*---------------------------START SECONDARY ELEMENT SECTION---------------------------*/
			
			ArticleSectionHeaderGradient: function() {
			
				var idholder = '';
				idholder += "<tr><td colspan='3'>&nbsp;</td></tr>";
				idholder += "<tr><td align='center' colspan='3'>- <b>Article Section Header Gradient</b> -</td></tr>";
				idholder += "<tr><td colspan='3'><textarea id='ArticleSectionHeaderGradient' style='resize:none' cols='42' rows='8'></textarea></td></tr>";
				idholder += "<tr><td colspan='3'>&nbsp;</td></tr>";
				idholder += "<tr><td align='center'><input type='button' Onclick='close_modal_window()' value='Cancel'  /></td><td align='center'><input type='button' OnClick='AjaxFile.OpenUltimateCSSGradientGenerator()' value='Ultimate CSS Gradient Generator'  /></td><td align='center'><input type='button' onClick='AjaxFile.ChangeArticleSectionHeaderGradient();' value='Save'  /></td></tr>";
				zaved("#tblDialogBoxGradient").html( idholder );
			},
			
			ChangeArticleSectionHeaderGradient: function() {
				
				var ArticleSectionHeaderGradient = zaved("#ArticleSectionHeaderGradient").val();
				var extra1 = '';
				
				if( ArticleSectionHeaderGradient == '' ) {
				
					alert("Please enter the Gradient Code.");
				}
				else {
					
					extra1 = 'border-top-left-radius:5px; border-top-right-radius:5px;';
					extra1 = extra1 + ArticleSectionHeaderGradient;
					zaved('.text-header-sample').attr( 'style', extra1 );
					CSS['ArticleHeaderGradient'] = ArticleSectionHeaderGradient;
					close_modal_window();
				}
			},
			
			ArticleHeaderTextColor: function() {
			
				var self = this;
				var thiscolor = $(".text-header h3").css("color");
				var dialogModal = $('#cp-dialog-modal').dialog({
			
					autoOpen:	false,	minWidth:	500, modal:	true, title : 'Article Header Text Color',
					buttons:	{'Cancel': function() {
				
						$(this).dialog('close'); }, 'Save': function() { self.SaveArticleHeaderTextColor();  }
					}
				});
				
				$('.cp-onclick').colorpicker({ showOn:'click', inlineFrame:false });
				console.log(thiscolor);
				$('.cp-onclick').colorpicker("setColor", thiscolor);
				dialogModal.dialog('open');
			},
			
			SaveArticleHeaderTextColor: function() {
				
				var color = '';
				var self = this;
				color = zaved(".ui-colorpicker-hex-input").val();
				zaved('.text-header h3').css('color', '#'+color );
				CSS['ArticleHeaderTextColor'] = "#"+color;
				self.CloseDialogModalWindow();
			},
			
			ArticleTitleTextColor: function() {
				
				var self = this;
				var thiscolor = $(".title.headertxtcolor a").css("color");
				var dialogModal = $('#cp-dialog-modal').dialog({
			
					autoOpen:	false,	minWidth:	500, modal:	true, title : 'Article Title Text Color',
					buttons:	{'Cancel': function() {
				
						$(this).dialog('close'); }, 'Save': function() { self.SaveArticleTitleTextColor();  }
					}
				});
				
				$('.cp-onclick').colorpicker({ showOn:'click', inlineFrame:false });
				console.log(thiscolor);
				$('.cp-onclick').colorpicker("setColor", thiscolor);
				dialogModal.dialog('open');
			},
			
			SaveArticleTitleTextColor: function() {
			
				var color = '';
				var self = this;
				
				color = zaved(".ui-colorpicker-hex-input").val();
				zaved('.headertxtcolor a').css('color', '#'+color );
				CSS['ArticleTitleTextColor'] = "#"+color;
				self.CloseDialogModalWindow();			
			},
			
			ArticleHeaderBottomDividerColor: function() {
				
				var self = this;
				var thiscolor = $("#footer-separator").css("color");
				var dialogModal = $('#cp-dialog-modal').dialog({
			
					autoOpen:	false,	minWidth:	500, modal:	true, title : 'Article Header Bottom Divider Color',
					buttons:	{'Cancel': function() {
				
						$(this).dialog('close'); }, 'Save': function() { self.SaveArticleHeaderBottomDividerColor();  }
					}
				});
				
				$('.cp-onclick').colorpicker({ showOn:'click', inlineFrame:false });
				console.log(thiscolor);
				$('.cp-onclick').colorpicker("setColor", thiscolor);
				dialogModal.dialog('open');
			},
			
			SaveArticleHeaderBottomDividerColor: function() {
				
				var color = '';
				var self = this;
				var extra2 = '';
				color = zaved(".ui-colorpicker-hex-input").val();
				extra2 = 'background-color:#'+ color + '; ' + 'margin:0px;';
				zaved('.bottomdivider').attr('style', extra2 );
				CSS['SaveBottomDividerColor'] = "#"+color;
				self.CloseDialogModalWindow();
			},
			
			/*-----------------END SECONDARY ELEMENT SECTION--------------------------*/
			
			MainActiveMenuTextColor: function(curcolor) {
				var self = this;
			    var thiscolor = $("#menu-menu_header li.current-menu-item a").css("color");
				var dialogModal = $('#cp-dialog-modal').dialog({
			
					autoOpen:	false,	minWidth:	500, modal:	true, title : 'Main Active Menu Text Color',
					buttons:	{'Cancel': function() {
				
						$(this).dialog('close'); }, 'Save': function() { self.SaveMainActiveMenuTextColor();  }
					}
				});				
				$('.cp-onclick').colorpicker({ showOn:'click', inlineFrame:false}, "setColor", thiscolor);
				console.log(thiscolor);
				$('.cp-onclick').colorpicker("setColor", thiscolor);
				dialogModal.dialog('open');
			},

			SaveMainActiveMenuTextColor: function() {
				
				var color = '';
				var self = this;
				color = zaved(".ui-colorpicker-hex-input").val();
				zaved('ul.samle_main_menu li.active a').css('color', '#'+color );
				CSS['MainActiveMenuColor'] = "#"+color;
				
				if ( zaved("#fontfamily").is(':checked') ) {
					
					zaved('.text-header h3').css('color', '#'+color );
					CSS['ArticleHeaderTextColor'] = "#"+color;
				}
				
				self.CloseDialogModalWindow();
			},
									
			MainInactiveMenuColor: function(curcolor) {
				var self = this;
				var thiscolor = $("#menu-menu_header.nav-menu li a").css("color");
				var dialogModal = $('#cp-dialog-modal').dialog({
				

					autoOpen:	false,	minWidth:	500, modal:	true, title : 'Main Inactive Menu Text Color',
					buttons:	{'Cancel': function() {
				
						$(this).dialog('close'); }, 'Save': function() { self.SaveMainInactiveMenuColor();  }
					}
				});
				
				$('.cp-onclick').colorpicker({ showOn:'click', inlineFrame:false }, "setColor", thiscolor);
				console.log(thiscolor);
				$('.cp-onclick').colorpicker("setColor", thiscolor);
				dialogModal.dialog('open');

			},
			
			SaveMainInactiveMenuColor: function() {
				
				var color = '';
				var self = this;
				color = zaved(".ui-colorpicker-hex-input").val();
				zaved('ul.samle_main_menu li.inactive a').css('color', '#'+color );
				CSS['MainInactiveMenuColor'] = "#"+color;
				self.CloseDialogModalWindow();
			},
			
			SubMenuGradient: function() {
			
				var idholder = '';
				idholder += "<tr><td colspan='3'>&nbsp;</td></tr>";
				idholder += "<tr><td align='center' colspan='3'>- <b>Sub Menu Gradient</b> -</td></tr>";
				idholder += "<tr><td colspan='3'><textarea id='idSubMenuGradient' style='resize:none' cols='42' rows='8'></textarea></td></tr>";
				idholder += "<tr><td colspan='3'>&nbsp;</td></tr>";
				idholder += "<tr><td align='center'><input type='button' Onclick='close_modal_window()' value='Cancel'  /></td><td align='center'><input type='button' OnClick='AjaxFile.OpenUltimateCSSGradientGenerator()' value='Ultimate CSS Gradient Generator'  /></td><td align='center'><input type='button' OnClick='AjaxFile.ChangeSubMenuGradientHeader()' value='Save'  /></td></tr>";
				zaved("#tblDialogBoxGradient").html( idholder );
			},
			
			SubActiveMenuColor: function() {
				var self = this;
				var thiscolor = $("ul.sub-menu-sample li.active a").css("color");
				var dialogModal = $('#cp-dialog-modal').dialog({
					autoOpen:	false,	minWidth:	500, modal:	true, title : 'Sub Active Menu Text Color',
					buttons:	{'Cancel': function() {				
						$(this).dialog('close'); }, 'Save': function() { self.SaveSubActiveMenuColor();  }
					}
				});
				$('.cp-onclick').colorpicker({ showOn:'click', inlineFrame:false });
				console.log(thiscolor);
				$('.cp-onclick').colorpicker("setColor", thiscolor);
				dialogModal.dialog('open');
			},
			
			SaveSubActiveMenuColor: function() {
			
				var color = '';
				var self = this;
				color = zaved(".ui-colorpicker-hex-input").val();
				zaved('ul.sub-menu-sample li.active a').css('color', '#'+color );
				CSS['SubActiveMenuColor'] = "#"+color;
				self.CloseDialogModalWindow();
			},
			
			SubInactiveMenuColor: function() {
				
				var self = this;
				var thiscolor = $("ul.sub-menu-sample li.inactive a").css("color");

				$('.cp-onclick').colorpicker("setColor", thiscolor);
				var dialogModal = $('#cp-dialog-modal').dialog({
					autoOpen:	false,	minWidth:	500, modal:	true, title : 'Sub Inactive Menu Text Color',
					buttons:	{'Cancel': function() {
						$(this).dialog('close'); }, 'Save': function() { self.SaveSubInactiveMenuColor();  }
					}
				});
				$('.cp-onclick').colorpicker({ showOn:'click', inlineFrame:false });
				console.log(thiscolor);
				$('.cp-onclick').colorpicker("setColor", thiscolor);
				dialogModal.dialog('open');
			},
			
			SaveSubInactiveMenuColor: function() {
				
				var color = '';
				var self = this;
				color = zaved(".ui-colorpicker-hex-input").val();
				zaved('ul.sub-menu-sample li.inactive a').css('color', '#'+color );
				CSS['SubInactiveMenuColor'] = "#"+color;
				self.CloseDialogModalWindow();
			},
			
			SubActiveMenuBckrund: function() {
				var self = this;
				var thiscolor = $("ul.sub-menu-sample li.active a").css("background-color");
				var dialogModal = $('#cp-dialog-modal').dialog({
					autoOpen:	false,	minWidth:	500, modal:	true, title : 'Sub Active Menu Background Color',
					buttons:	{'Cancel': function() {
					
						$(this).dialog('close'); }, 'Save': function() { self.SaveSubActiveMenuBckrund();  }
					}
				});
				$('.cp-onclick').colorpicker({ showOn:'click', inlineFrame:false });
				console.log(thiscolor);
				$('.cp-onclick').colorpicker("setColor", thiscolor);
				dialogModal.dialog('open');
			},
			FooterColor: function() {
				var self = this;
				var thiscolor = $(".FooterColor").css("color");
				var dialogModal = $('#cp-dialog-modal').dialog({
					autoOpen:	false,	minWidth:	500, modal:	true, title : 'Footer Color',
					buttons:	{'Cancel': function() {
					
						$(this).dialog('close'); }, 'Save': function() { self.SaveFooterColor();  }
					}
				});
				$('.cp-onclick').colorpicker({ showOn:'click', inlineFrame:false });
				console.log(thiscolor);
				$('.cp-onclick').colorpicker("setColor", thiscolor);
				dialogModal.dialog('open');
			},
			TopLoginRegister: function() {
				var self = this;
				var thiscolor = $(".TopLoginRegister").css("color");
				var dialogModal = $('#cp-dialog-modal').dialog({
					autoOpen:	false,	minWidth:	500, modal:	true, title : 'Top Login/Register Color',
					buttons:	{'Cancel': function() {
					
						$(this).dialog('close'); }, 'Save': function() { self.SaveTopLoginRegister();  }
					}
				});
				$('.cp-onclick').colorpicker({ showOn:'click', inlineFrame:false });
				console.log(thiscolor);
				$('.cp-onclick').colorpicker("setColor", thiscolor);
				dialogModal.dialog('open');
			},
			SubActive3DTop: function() {
				var self = this;
				var thiscolor = $("ul.sub-menu-sample li.active a").css("background-color");
				var dialogModal = $('#cp-dialog-modal').dialog({
					autoOpen:	false,	minWidth:	500, modal:	true, title : 'Sub Active Menu 3D Top Color',
					buttons:	{'Cancel': function() {
					
						$(this).dialog('close'); }, 'Save': function() { self.SaveSubActive3DTop();  }
					}
				});
				$('.cp-onclick').colorpicker({ showOn:'click', inlineFrame:false });
				console.log(thiscolor);
				$('.cp-onclick').colorpicker("setColor", thiscolor);
				dialogModal.dialog('open');
			},
			Enable3D: function() {
				var self = this;
				self.SaveEnable3D(); 
			},
			SubActive3DBottom: function() {
				var self = this;
				var thiscolor = $("ul.sub-menu-sample li.active a").css("background-color");
				var dialogModal = $('#cp-dialog-modal').dialog({
					autoOpen:	false,	minWidth:	500, modal:	true, title : 'Sub Active Menu 3D Top Color',
					buttons:	{'Cancel': function() {
					
						$(this).dialog('close'); }, 'Save': function() { self.SaveSubActive3DBottom();  }
					}
				});
				$('.cp-onclick').colorpicker({ showOn:'click', inlineFrame:false });
				console.log(thiscolor);
				$('.cp-onclick').colorpicker("setColor", thiscolor);
				dialogModal.dialog('open');
			},
			
			FeaturedGolfDealsBackgroundColor: function() {
				
				var self = this;
				var dialogModal = $('#cp-dialog-modal').dialog({
					autoOpen:	false,	minWidth:	500, modal:	true, title : 'Featured Golf Deals Background Color',
					buttons:	{'Cancel': function() {
					
						$(this).dialog('close'); }, 'Save': function() { self.SaveFeaturedGolfDealsBackgroundColor();  }
					}
				});
				$('.cp-onclick').colorpicker({ showOn:'click', inlineFrame:false });
				dialogModal.dialog('open');			
			},
			
			SaveFeaturedGolfDealsBackgroundColor: function() {
				
				var color = '';
				var self = this;
				color = zaved(".ui-colorpicker-hex-input").val();
				zaved('.featured-golf-deals-sample').css('background-color', '#'+color );
				CSS['FeaturedGolfDealsBackgroundColor'] = "#"+color;
				self.CloseDialogModalWindow();
			},
			
			SaveSubActiveMenuBckrund: function() {
				
				var color = '';
				var self = this;
				color = zaved(".ui-colorpicker-hex-input").val();
				zaved('ul.sub-menu-sample li.active a').css('background-color', '#'+color );
				CSS['SubActiveMenuBackground'] = "#"+color;
				self.CloseDialogModalWindow();
			},
			SaveFooterColor: function() {
				
				var color = '';
				var self = this;
				color = zaved(".ui-colorpicker-hex-input").val();
				zaved('.FooterColor').css('color', '#'+color );
				CSS['FooterColor'] = "#"+color;
				self.CloseDialogModalWindow();
			},
			SaveTopLoginRegister: function() {

				var color = '';
				var self = this;
				color = zaved(".ui-colorpicker-hex-input").val();
				zaved('.TopLoginRegister').css('color', '#'+color );
				CSS['TopLoginRegister'] = "#"+color;
				self.CloseDialogModalWindow();
			},
			SaveSubActive3DTop: function() {
				
				var color = '';
				var self = this;
				color = zaved(".ui-colorpicker-hex-input").val();
				zaved('ul.sub-menu-sample li.active a').css('border-top', '1px solid #'+color );
				CSS['SubActive3DTop'] = "1px solid #"+color;
				self.CloseDialogModalWindow();
			},
			SaveEnable3D: function() {
				
				
				var self = this;
	            if($('#enable3d').is(":checked"))
				{
					alert("3DEnable is checked.");
					CSS['Enable3D'] = "checked";
					$('#3dtop').attr('enabled', true);
					$('#3dbottom').attr('enabled', true);
					$('#3dtop').removeAttr('disabled');
					$('#3dbottom').removeAttr('disabled');
				}
				else if($('#enable3d').is(":not(:checked)"))
				{
					alert("3DEnable is unchecked.");
					CSS['Enable3D'] = "unchecked";
					$('#3dtop').attr('disabled', true);
					$('#3dbottom').attr('disabled', true);
					$('#3dtop').removeAttr('enabled');
					$('#3dbottom').removeAttr('enabled');
				}
			},
			SaveSubActive3DBottom: function() {
				
				var color = '';
				var self = this;
				color = zaved(".ui-colorpicker-hex-input").val();
				zaved('ul.sub-menu-sample li.active a').css('border-bottom', '1px solid #'+color );
				CSS['SubActive3DBottom'] = "1px solid #"+color;
				self.CloseDialogModalWindow();
			},
			
			GetColorCode: function( gradient ) {
				
					var self = this;
					var reg = /background: #[0-9A-Fa-f]{6}/g;					
					color = gradient.match( reg );
					if(color == null){
					alert('PLEASE ONLY HEX COLOR FORMAT AND NOT TRANSPARENT');
					self.CloseDialogModalWindow();
					$("#site-navigation").load(location.href + " #site-navigation");
					}else{
					color = color.toString();
					color = color.replace("background: #","");					
					return color;
					}
					
			},
			
			GetFontFamilyToSecondElem: function() {
				
				var self = this;
				
				var MainActiveMenuColor		= '';
				var ArticleHeaderGradient	= '';
				var CodeSubMenuGradient		= '';
				var ArticleHeaderTextColor	= '';
				
				//This is for main active text color.
				MainActiveMenuColor		= zaved('ul.samle_main_menu li.active a').attr('style');
				ArticleHeaderGradient	= zaved('.sample-site-navigation').attr('style');
				CodeSubMenuGradient		= zaved(".header_sub_menu_sample").attr('style');
				
				ArticleHeaderTextColor = ( MainActiveMenuColor != undefined ) ? MainActiveMenuColor.split(':') : '#fff';
				FoundMainColor	= ( ArticleHeaderGradient != undefined ) ? self.GetColorCode(ArticleHeaderGradient) : '';
				FoundSubColor	= ( CodeSubMenuGradient != undefined ) ? self.GetColorCode(CodeSubMenuGradient) : '';				
				
				//ArticleHeaderGradient = ArticleHeaderGradient.replace('width:250px; border-radius:5px 5px 5px 5px;', 'border-top-left-radius:5px; border-top-right-radius:5px;');
				
				//check if checkbox is checked
				if ( zaved("#fontfamily").is(':checked') ) {
					
					zaved('.thishastodisable').attr('disabled', true);
					zaved('.thishastodisable').attr('style', 'color:#BBBBBB; border-color:#666666;' );
					
					zaved('.text-header-sample h3').attr('style', MainActiveMenuColor );
					CSS['ArticleHeaderTextColor'] = ( MainActiveMenuColor != undefined ) ?  ArticleHeaderTextColor[1].slice(0, -1) : '';
					
					zaved('.text-header-sample').attr( 'style', ArticleHeaderGradient );
					CSS['ArticleHeaderGradient'] = ( ArticleHeaderGradient != undefined ) ?  ArticleHeaderGradient: '';
					
					//to Article Title Text Color
					zaved('.headertxtcolor a').css('color', '#'+FoundMainColor );
					CSS['ArticleTitleTextColor'] = "#"+FoundMainColor;
					
					//to bottom divder
					var extra2 = 'background-color:#'+ FoundMainColor + '; ' + 'margin:0px;';
					zaved('.bottomdivider').attr('style', extra2 );
					CSS['SaveBottomDividerColor'] = "#"+FoundMainColor;
					
					//Circle image
					var extra1 = "display: inline-block; height: 15px; padding-right: 20px; color: #"+FoundMainColor+";";
					zaved('a.arrow-right').attr( 'style', extra1 );
					CSS['CircleArrowImage'] = "#"+FoundMainColor;
					
					//Featured Golf Deals Background
					zaved('.featured-golf-deals-sample').css('background-color', '#'+FoundSubColor );
					CSS['FeaturedGolfDealsBackgroundColor'] = "#"+FoundSubColor;
				}
				else {
				
					zaved('.thishastodisable').removeAttr('disabled');
					zaved('.thishastodisable').removeAttr('style');
				}
			},
			
			CreateCSS: function() {
				var self = this;
								
				self.SaveEnable3D();
				var MainActiveMenuColor = ''; 	var MainInactiveMenuColor = '';	var SubActiveMenuColor = '';
				var SubInactiveMenuColor = '';	var SubActiveMenuBackground = '';	var MainMenuGradient = '';
				var ArticleHeaderGradient = '';	var ArticleHeaderTextColor = '';	var ArticleTitleTextColor = '';
				var SaveBottomDividerColor = ''; var FeaturedGolfDealsBackgroundColor = ''; var CircleArrowImage = '';
				var BestGolfClubsGradient = ''; var WhiteTriangle = '';
				
				MainActiveMenuColor = ( CSS['MainActiveMenuColor'] != undefined ) ? CSS['MainActiveMenuColor'] : '';
				MainInactiveMenuColor = ( CSS['MainInactiveMenuColor'] != undefined ) ? CSS['MainInactiveMenuColor'] : '';
				SubActiveMenuColor = ( CSS['SubActiveMenuColor'] != undefined ) ? CSS['SubActiveMenuColor'] : '';
				SubInactiveMenuColor = ( CSS['SubInactiveMenuColor'] != undefined ) ? CSS['SubInactiveMenuColor'] : '';
				SubActiveMenuBackground = ( CSS['SubActiveMenuBackground'] != undefined ) ? CSS['SubActiveMenuBackground'] : '';
				TopLoginRegister = ( CSS['TopLoginRegister'] != undefined ) ? CSS['TopLoginRegister'] : '';
				FooterColor = ( CSS['FooterColor'] != undefined ) ? CSS['FooterColor'] : '';
				SubActive3DTop = ( CSS['SubActive3DTop'] != undefined ) ? CSS['SubActive3DTop'] : '';
				SubActive3DBottom = ( CSS['SubActive3DBottom'] != undefined ) ? CSS['SubActive3DBottom'] : '';
				Enable3D = ( CSS['Enable3D'] != undefined ) ? CSS['Enable3D'] : '';
				MainMenuGradient = ( CSS['MainMenuGradient'] != undefined ) ? CSS['MainMenuGradient'] : '';
				SubMenuGradient = ( CSS['SubMenuGradient'] != undefined ) ? CSS['SubMenuGradient'] : '';
				ArticleHeaderGradient = ( CSS['ArticleHeaderGradient'] != undefined ) ? CSS['ArticleHeaderGradient'] : '';
				ArticleHeaderTextColor = ( CSS['ArticleHeaderTextColor'] != undefined ) ? CSS['ArticleHeaderTextColor'] : '';
				ArticleTitleTextColor = ( CSS['ArticleTitleTextColor'] != undefined ) ? CSS['ArticleTitleTextColor'] : '';
				SaveBottomDividerColor = ( CSS['SaveBottomDividerColor'] != undefined ) ? CSS['SaveBottomDividerColor'] : '';
				FeaturedGolfDealsBackgroundColor = ( CSS['FeaturedGolfDealsBackgroundColor'] != undefined ) ? CSS['FeaturedGolfDealsBackgroundColor'] : '';
				CircleArrowImage = ( CSS['CircleArrowImage'] != undefined ) ? CSS['CircleArrowImage'] : '';
				BestGolfClubsGradient = ( CSS['BestGolfClubsGradient'] != undefined ) ? CSS['BestGolfClubsGradient'] : '';
				WhiteTriangle = ( CSS['WhiteTriangle'] != undefined ) ? CSS['WhiteTriangle'] : '';
					
				
				var CssCode = MainActiveMenuColor + MainInactiveMenuColor + SubActiveMenuColor + 
				SubInactiveMenuColor + SubActiveMenuBackground + TopLoginRegister + FooterColor + SubActive3DTop + SubActive3DBottom + Enable3D + MainMenuGradient + SubMenuGradient + 
				ArticleHeaderGradient + ArticleHeaderTextColor + ArticleTitleTextColor + SaveBottomDividerColor
				+ FeaturedGolfDealsBackgroundColor + CircleArrowImage + BestGolfClubsGradient + WhiteTriangle;
				
				if( CssCode == "" ) {
				
					alert("Please select theme color or gradient css.");
				}
				else {
				
					zaved.ajax({ url: ajaxurl, data: { 'action' : 'CreateCSS' ,
						
						'MainActiveMenuColor'	: MainActiveMenuColor,
						'MainInactiveMenuColor' : MainInactiveMenuColor,
						'SubActiveMenuColor'	: SubActiveMenuColor,
						'SubInactiveMenuColor' 	: SubInactiveMenuColor,
						'SubActiveMenuBackground'	: SubActiveMenuBackground,
						'FooterColor'	: FooterColor,
						'TopLoginRegister'	: TopLoginRegister,
						'SubActive3DTop'	: SubActive3DTop,
						'SubActive3DBottom'	: SubActive3DBottom,
						'Enable3D'	: Enable3D,
						'MainMenuGradient'			: MainMenuGradient,
						'SubMenuGradient'			: SubMenuGradient,
						'ArticleHeaderGradient'		: ArticleHeaderGradient,
						'ArticleHeaderTextColor'	: ArticleHeaderTextColor,
						'ArticleTitleTextColor'		: ArticleTitleTextColor,
						'SaveBottomDividerColor'	: SaveBottomDividerColor,
						'FeaturedGolfDealsBackgroundColor' : FeaturedGolfDealsBackgroundColor,
						'CircleArrowImage'			: CircleArrowImage,
						'BestGolfClubsGradient'		: BestGolfClubsGradient,
						'WhiteTriangle'				: WhiteTriangle
					},
					success:function(data) {
						
						alert( "Theme changes has been apply to file.");
						CssCode.length = 0;
					}});
				}
			},
			
			DisplayPrntMenuInfo: function() {
				
				var menuname = zaved("#txtmenubox").val();
				
				if( menuname != "") {
				
					zaved("#txtWPPageName").val( menuname );
					page = menuname.replace(/ /g,'-');
					var root = location.protocol + '//' + location.host + "/" + page + ".html";					
					zaved("#txtpageurl").val( root.toLowerCase() );
				}
				else {
					
					zaved("#txtWPPageName").val( "" );
					zaved("#txtpageurl").val( "" );
				}
			},
			
			DisplayChildMenuInfo: function() {
				
				
				var menuname = zaved("#txtmenubox").val().toLowerCase();
				
				if( menuname != "" ) {
				
					var home_id = zaved("#home_id").val();
					var childname = zaved("#selchildbox").val();
					var parentTxt = zaved("#seleditparentmenu option:selected").text();					
					zaved("#txtWPPageName").val( menuname );
					page = menuname.replace(/ /g, '-');
					
					if( parentTxt != '' ) {
					
						parentTxt = parentTxt.trim();
						parentTxt = parentTxt.replace(/ /g, '-');
						var root = location.protocol + '//' + location.host + "/" + parentTxt + "/" + page + ".html";
					}
					else {
						
						var childurl = zaved("#childurl").val();
						root = childurl.split('//');
						root = root[1];
						root = root.split('/');
						root = location.protocol + "//" +root[0] + "/" + root[1] + "/" + page + ".html";
					}
					
					var homeid = ( window.location.search.split('mid=')[1] ) ? window.location.search.split('mid=')[1] : zaved("#seleditparentmenu option:selected").val() ;
					
					if( homeid == home_id ) {
					
						var root = location.protocol + '//' + location.host + "/" + page + ".html";
					}
					else {
					
						root;
					}
					
					zaved("#txtpageurl").val( root.toLowerCase() );
					zaved("#childurl").val(root.toLowerCase());
				}
				else {
				
					zaved("#txtWPPageName").val( "" );
					zaved("#txtpageurl").val( "" );
				}
			},
			
			GetPageURL: function( pageid ) {
			
				var menuname = zaved("#txtmenubox").val();
				if( menuname != '' ) {
					
					var parentTxt = zaved("#seleditparentmenu option:selected").text();
					var menuname = zaved("#txtmenubox").val();
					parentTxt = parentTxt.replace(/ /g,'-');
					page = menuname.replace(/ /g,'-');
					var root = location.protocol + '//' + location.host + "/" + parentTxt + "/" + page + ".html";
					zaved("#txtpageurl").val( root.toLowerCase() );
				}
			},
			
			ExternalCheckBox: function () {
			
				if( document.getElementById("chkboxurl").checked ) {
				
					zaved("#txtexternalurl").removeAttr( "disabled");
					zaved("#parent_drop_page_temp").attr( "disabled", "disabled");
				}
				else {
				
					zaved("#txtexternalurl").attr( "disabled", "disabled");
					zaved("#parent_drop_page_temp").removeAttr( "disabled");
				}			
			},
			
			AddAnotherChildReset: function() {
				
							
			},
			
			AddAnotherParentReset: function() {
			
				var txtmenubox 	= zaved("#txtmenubox").val();
				var template	= zaved("#parent_drop_page_temp").val();
				var exurl		= zaved("#txtexternalurl").val();
				
				if( txtmenubox == '' ) {
				
					alert("Please enter the menu name");
				}
				else if( zaved('#chkboxurl').is(':checked') && exurl == '' ) {					
				
					alert("Please enter the external url");
				}
				else if( !zaved('#chkboxurl').is(':checked') && template == '' ) {
				
					alert("Please select the template");
				}
				else {
				
					location.href = "?page=site-review-sparkle-site.php";
				}			
			},
			
			AddAnotherParentReset2: function() {
				
				var txtmenubox 	= zaved("#txtmenubox").val();
				var template	= zaved("#parent_drop_page_temp").val();
				var exurl		= zaved("#txtexternalurl").val();
				var flag		= "";
				
				if( txtmenubox == '' ) {
				
					alert("Please enter the menu name");
				}
				else if( zaved('#chkboxurl').is(':checked') && exurl == '' ) {					
				
					alert("Please enter the external url");
				}
				else if( !zaved('#chkboxurl').is(':checked') && template == '' ) {
				
					alert("Please select the template");
				}
				else {
					
					flag = ( zaved('#chkboxurl').is(':checked') ) ? 'check' : 'uncheck' ;
					
					zaved.ajax({ url: ajaxurl, data: { 'action':'AddAnotherParentReset' , flag:flag, txtmenubox:txtmenubox, exurl:exurl, template:template },
						success:function(data) {
							zaved("#txtmenubox").val('');
							zaved('#position option[value="1"]').attr('selected','selected');
							zaved('#parent_menu_id option:last').attr('selected','selected');
							zaved("#parent_drop_page_temp").val('');
							zaved("#txtTemplateFile").val('');
							zaved("#txtWPPageName").val('');
							zaved("#txtpageurl").val('');
						}
					});
				}
			},
			
			AddAnotherReset: function() {
			
				zaved("#txtmenubox").val('');
				zaved("#parent_drop_page_temp").val('');
				zaved("#txtTemplateFile").val('');
				zaved("#txtWPPageName").val('');
				zaved("#txtpageurl").val('');
			},
			
			EditParentMenu: function() {
			
				var mid  = zaved("#seleditparentmenu").val();
				var mtext  = zaved("#seleditparentmenu option:selected").text();
				
				if( mid == '' ) {
				
					alert("Please Select Parent Menu");
				}
				else if( mtext.trim() == 'Home' ) {
				
					alert( mtext+ ' page can not modify drirectly but it is own child.');
				}
				else {
				
					location.href = "?page=site-review-sparkle-site.php&action=editparentmenu&id="+mid;					
				}
			},
			
			DeleteParentMenu: function( menu ) {
				
				var mtext  = zaved("#seleditparentmenu option:selected").text();
				var postid = zaved("#seleditparentmenu").val();
				var pid1 = postid.split('|');
				
				if( mtext.trim() == 'Home' ) {
					
					alert( mtext+ ' page can not remove drirectly but it is own child.');
				}
				else {
				
					if( confirm("If parent menu ("+mtext+") has child menus which will also be deleted if you proceed." ) ) {
					
						location.href = "?page=site-review-sparkle-site.php&tab=menus&action=editmenusproperties&delaction=deleteparentmenu&postid="+pid1[1];
					}
				}
			},
			
			DeleteChildMenu: function() {
				
				var parent_menu_id = zaved("#seleditparentmenu").val();
				var child_post_id = zaved("#selchildmenu").val();
				var mtext  = zaved("#selchildmenu option:selected").text();
				
				var child_post_id = child_post_id.split('|');
				var parent_id = parent_menu_id.split('|');		
				
				if( confirm("Are you sure you want to delete child menu - " + mtext + "?" ) ) {
					
					location.href = "?page=site-review-sparkle-site.php&tab=menus&action=editmenusproperties&delaction=deletechildmenu&childpostid="+child_post_id[1]+"&childid="+child_post_id[0]+"&p_postid="+parent_id[0]+"&c_postid="+parent_id[1];
				}
			},
			
			EditChildMenu: function() {
			
				var mid  = zaved("#seleditparentmenu").val();
				var cid  = zaved("#selchildmenu").val();
				
				var mid1 = mid.split('|');
				var cid1 = cid.split('|');
				
				if( cid1 == '' ) {
					
					alert('Please Select Child Menu');
				}
				else {
					
					location.href = "?page=site-review-sparkle-site.php&action=editchildmenu&cid="+cid1[0]+"&mid="+mid1[0];
				}			
			},
			
			ShowChildMenus: function( pid, a, def ) {
				
				var pid = zaved("#seleditparentmenu").val();
				//var _default = zaved("#selchildmenu").val();
				
				//alert('EDIT2 ' + pid + '- ' + a + ' - ' + _default )
				
				zaved.ajax({ url: ajaxurl, data: { 'action':'ShowChildMenu', 'pid':pid, a:a  },
					success:function(data) {
						
						zaved("#selchildmenu").html( data );
						zaved('#selchildmenu').removeAttr ('disabled');
						zaved('#childoption').removeAttr ('disabled');
					}
				});
			},
			
			SubmitParentForm: function() {		
				
				var menuname = zaved("#txtmenubox").val();
				var template = zaved("#parent_drop_page_temp").val();
				var templateFile = zaved("#txtTemplateFile").val();
				
				if( menuname == '' ) {
				
					alert( "Menu name is empty" );
				}
				else if( template == '' ) {
								
					alert( "Please select the page template" );
				}
				else if( template == 'new' ) {
					
					if( templateFile == '' ) {
					
						alert( "Please enter the template Php file" );
					}
				}
				else {
				
					location.href = "options-general.php?page=site-review-sparkle-site.php&action=addparentmenu";
				}
			},
			
			TestOnload: function() {
			
				alert('TestOnload');
			}
		
		} // end AjaxFile		
		
		//AjaxFile.ShowChildMenus( '', 'edit', '' );
		AjaxFile.load();
		
	}); //end JQuery
	
	function Set_Model_Popup_Window_Properties( id, obj ) {
		
		if( id != '' && obj != '' || typeof obj == 'object'  ) {
		
			dialog = obj;		
		}
		else if( id == '#Search_Manage_Clips_Dialog' ) {
			
			dialog = $(id);			
		}		
		
		//Get the screen height and width
		var maskHeight	= jQuery(document).height();
		var maskWidth	= jQuery(window).width();	
		
		//Set heigth and width to mask to fill up the whole screen
		jQuery('#mask').css({'width':maskWidth,'height':maskHeight, 'position':'fixed'});
		
		//Get the window height and width
		var winH = jQuery(window).height();
		var winW = jQuery(window).width();
		
		if( typeof obj == 'object' ) {
			
			//Set the popup window to center
			dialog.css('top',  winH/2-dialog.height()/2);
			dialog.css('left', winW/2-dialog.width()/2);
		}
	}
	
	function Set_Window_Effect( dialog ) {
				
		//transition effect		
		jQuery('#mask').fadeIn(1000);
		jQuery('#mask').fadeTo("slow", 0.8);
		//transition effect
		dialog.fadeIn(2000);
	}
	
	function close_modal_window() {
	
		jQuery('#mask').hide();
		jQuery('.this_window').hide();	
	}
	
	jQuery(document).ready(function() {
		
		var dialog = '';
		//select all the a tag with name equal to modal
		jQuery('a[name=modal], input[name=modal], select[name=modal]').click(function(e) {
			e.preventDefault(); //Cancel the link behavior
			var id = jQuery(this).attr('href'); //Get the A tag			
			dialog = jQuery(id);
			Set_Model_Popup_Window_Properties( id, dialog );
			Set_Window_Effect( dialog );			
		});	
		//if close button is clicked
		jQuery('.this_window .close').click(function (e) {
			//Cancel the link behavior
			e.preventDefault();
			jQuery('#mask').hide();
			jQuery('.this_window').hide();
		});
		
		jQuery(window).resize( function () {
			//Get the screen height and width
			Set_Model_Popup_Window_Properties( '', dialog );
		});
		
		jQuery(window).keyup(function(e) {
		
			if (e.keyCode == 27) { // escape key maps to keycode `27`
			
				close_modal_window();
			}
		});
	});

