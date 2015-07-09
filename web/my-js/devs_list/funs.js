/*
	dev[i]	.gid
			.name
			.valid
*/
function data_info_xml_parser( responseTxt ) {
			
	var xml = $(responseTxt);
	if ( xml.length<=0 )
		return false;
	
	var ds = xml.find('d');
	$.each( ds, function(i,value) {
		var v = $(value);
		dev[i] = new Object();
		dev[i].gid = v.attr('id');
		dev[i].name = v.children('n').text();
		dev[i].valid = parseInt(v.children('v').text());
	} );

	return true;
}

 function get_devs_info() {
	$.post( 'my-php/devs_list/get_devs.php', function( data ) {
		data_info_xml_parser( data );	
		$('#num').text('设备总数量:'+dev.length+'台');
		add_dev();
	} );	
 }

 function add_dev() {
	var table = $('#list');
	$.each(dev,function(i,v) {
		var tr = $('<tr></tr>');
		tr.attr('id', i);
		if(dev[i].valid==0)
			tr.css('background','rgb(253,51,40)');
		table.append(tr);
		
		var th1 = $("<th class='name'></th>");
		th1.text( dev[i].name );
		th1.attr('id', i);
		tr.append( th1 );
		
		var th2 = $("<th class='gid'></th>");
		th2.text( dev[i].gid );
		th2.attr('id', i);
		tr.append( th2 );
	} );
 }