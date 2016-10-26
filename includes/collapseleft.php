        
 <a id="trig" class="btn btn-sm btn-primary btnon"><i class="fa fa-arrow-left" aria-hidden="true"></i> Collapse Menu</a>
<script>
//left menu collapse
$('#trig').on('click', function () {
	console.log("collapsing");
	if($('#trig').is('.btnon')){
		
	  	 $('.main').attr( "class", "col-sm-12 col-md-12 main" );
    $('.sidebar').hide();
	 $('#trig').attr( "class", "btn btn-sm btn-primary btnoff" );
	 $('#trig').html( 'Show Menu <i class="fa fa-arrow-right" aria-hidden="true"></i> ' );
	 
	  }
	  
	 else if($('#trig').is('.btnoff')){
		  
	  	  $('.main').attr( "class", "col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main" );
    $('.sidebar').show();
	 $('#trig').attr( "class", "btn btn-sm btn-primary btnon" );
	  $('#trig').html( '<i class="fa fa-arrow-left" aria-hidden="true"></i> Collapse Menu ' );
	  }
   
});

</script>
