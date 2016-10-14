/**
 * @author Administrator
 */
jQuery.fn.spinner=function()
{
	return this.each(function(){
		
		jQuery(this).parent().css("clear","both");
	    jQuery(this).css({'width':(jQuery(this).width()-16)+"px",'float':'left'});
		jQuery(this).attr('readonly','readonly');
		
		jQuery(this).attr("minvalue",jQuery(this).attr("minvalue")?this.attr("minvalue"):1);
		jQuery(this).attr("maxvalue",jQuery(this).attr("maxvalue")?this.attr("maxvalue"):"");
		jQuery(this).attr("increment",jQuery(this).attr("increment")?this.attr("increment"):1);
		
		htmlitem="<div id='buttons_"+this.id+"' class='spinner_handler'></div>";
		jQuery(this).after(htmlitem);
		this.btn_container=jQuery(this).next();
		this.btn_container.css({'float':'left','height':jQuery(this).height(),'width':'10px'});
		
		htmlitem="<button id='plus_"+this.id+"' class='spinner_handler-plus' type='button'></button>"
		jQuery(this.btn_container).append(htmlitem);
		this.btn_plus=jQuery(this.btn_container).children()[0];
		jQuery(this.btn_plus).css({
							'height': jQuery(this).height() / 2 + "px",
							'line-height': jQuery(this).height() / 2 + "px",
							'width': '16px',
							'font-size':'4px',
							'padding':'0',
							'margin':'0',
							'border':'none',
							'vertical-align':'top'
						});
						
		htmlitem="<button id='minus_"+this.id+"' class='spinner_handler-minus' type='button'></button>"
		jQuery(this.btn_container).append(htmlitem);
		this.btn_minus=this.btn_container.children()[1];
		jQuery(this.btn_minus).css({
							'height': jQuery(this).height() / 2 + "px",
							'line-height': jQuery(this).height() / 2 + "px",
							'width': '16px',
							'font-size':'4px',
							'padding':'0',
							'margin':'0',
							'border':'none'
						});
		
		jQuery(this.btn_plus).bind("click",function()
			{
				input=jQuery(this).parent().prev("input")[0]
				minvalue=jQuery(input).attr("minvalue");	
				maxvalue=jQuery(input).attr("maxvalue");
				increment=jQuery(input).attr("increment");
				
				if ((maxvalue != "" && jQuery(input).val() < maxvalue) || maxvalue == "") {
					jQuery(input).val(parseInt(jQuery(input).val()) + parseInt(increment));
				}
				if(input.onchange)
					input.onchange();					
			}
		);
		
		jQuery(this.btn_minus).bind("click",function()
			{
				input=jQuery(this).parent().prev("input")[0]
				minvalue=jQuery(input).attr("minvalue");	
				maxvalue=jQuery(input).attr("maxvalue");
				increment=jQuery(input).attr("increment");
				
				if ((minvalue != "" && jQuery(input).val() > minvalue) || minvalue == "") {
					jQuery(input).val(parseInt(jQuery(input).val()) - parseInt(increment));
				}
				if(input.onchange)
					input.onchange();					
			}
		);		
	});
};