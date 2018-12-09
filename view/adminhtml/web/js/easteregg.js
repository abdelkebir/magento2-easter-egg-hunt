require(
    [
        'jquery',
        'mage/translate',
        'domReady!'
    ],
    function ($) {
    	if($('#easteregg_general_images').length){
    		$('#easteregg_general_images').parent().first().addClass('eastereggImagesForm');

    		var images = $('#easteregg_general_images').val().split(",");
    		console.log(images);
	    	var html='<div class="eggscntr">';
	    	for(var i=0 ; i<images.length ; i++){
	    		if(images[i]!=""){
	    			html+='<div class="cntr" data-id="'+i+'"><div class="rmv">x</div><img src="/pub/media/eastereggs/'+images[i]+'"><div class="code">[egg id="'+i+'"]</div></div>';
	    		}
			}
			html+='<div class="cntr plus"><p>+</p><input type="file" id="attachment" name="attachment"/></div>';
			html+='</div>';
	    	$(html).insertAfter('#easteregg_general_images');

	    	$('.eggscntr .cntr .rmv').click(function(){
	    		var eggId = $(this).parent().first().data('id');

	    		images = $('#easteregg_general_images').val().split(",");
	    		var newImages=[];
	    		for(var i=0 ; i<images.length ; i++){
	    			if(i!=eggId){
	    				newImages.push(images[i]);
	    			}
				}
				$('#easteregg_general_images').val(newImages.toString());
				$(this).parent().first().remove();
				/*
				var images = $('#easteregg_general_images').val().split(",");
				$('.eggscntr').remove();
		    	var html='<div class="eggscntr">';
		    	for(var i=0 ; i<images.length ; i++){
		    		html+='<div class="cntr" data-id="'+i+'"><div class="rmv">x</div><img src="/pub/media/eastereggs/'+images[i]+'"><div class="code">[egg id="'+i+'"]</div></div>';
				}
				html+='<div class="cntr plus"><p>+</p><input type="file" id="attachment" name="attachment"/></div>';
				html+='</div>';
				$(html).insertAfter('#easteregg_general_images');
				*/
	    	});




	    	$("#attachment").change(function(){
                var formData = new FormData();
				formData.append('file', $('#attachment')[0].files[0]);
                jQuery.ajax({
                    url: "/eastereggs/easteregg/addegg/",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    showLoader: true,
                    success: function (response) {
                        if(response.error){
                        	console.log(response.error);
                        	console.log(response.message);
                        }else{
                        	console.log('error: '+response.error);
                        	console.log(response.message);
                        	console.log(response.data.html);
                        	var html = response.data.html;
                        	html = html.replace("{id_egg}", ($(".eggscntr .cntr").length - 1));
                        	$(html).insertBefore( ".eggscntr .cntr.plus" );
                        	var newValue = $('#easteregg_general_images').val() + ',' + response.data.filename;
                        	newValue = newValue.replace(/^,/, '');
                        	$('#easteregg_general_images').val(newValue);
                        }
                    },
                    error: function (response) {
                        alert(response.message);
                        $('#frm_attachment')[0].reset();
                    }
                });
            });
    	}
    }
);