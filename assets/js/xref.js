// JavaScript Document

function cbSelect(e, boxes, xrefId) {
	var current = document.id(e.target);
	if (e.shiftKey && typeOf(this.last) !== 'null') {
		var checked = current.getProperty('checked') ? 'checked' : '';
		var range = [boxes.index(current), boxes.index(this.last)].sort(function(a, b) {
			return a-b;
		});
		for (var i=range[0]; i < range[1]; i++) {
			boxes[i].setProperty('checked', checked);
		}
        jQuery('#xrefTable' + xrefId +  ' input:checkbox').filter(':checked').each(function(){
			var selectedId = parseInt(jQuery(this).attr('id').substring(2));
			eval('a' + xrefId + 'Selected').push(selectedId);
		});
		var sSelected = eval('a' + xrefId + 'Selected');
		var sSelected = sort_unique( sSelected );
        jQuery('#x'+xrefId).val( sSelected.toString() );
	}
	this.last = current;
}
function updateActiveSelected( id, xrefId ) {
	var index = jQuery.inArray(id, eval('a' + xrefId + 'Selected'));
	if ( index === -1 ) {
		eval('a' + xrefId + 'Selected').push( id );
		//$('tr#'+id).addClass('row_selected');
        jQuery('#cb'+id).attr('checked',true);
	} else {
		eval('a' + xrefId + 'Selected').splice( index, 1 );
		//$('tr#'+id).removeClass('row_selected');
        jQuery('#cb'+id).attr('checked',false);
	}
	var sSelected = eval('a' + xrefId + 'Selected');
	var sSelected = sort_unique( sSelected );
    jQuery('#x'+xrefId).val( sSelected.toString() );
}
function sort_unique(arr) {
    arr = arr.sort(function (a, b) { return a*1 - b*1; });
    var ret = [arr[0]];
    for (var i = 1; i < arr.length; i++) { // start loop at 1 as element 0 can never be a duplicate
        if (arr[i-1] !== arr[i]) {
            ret.push(arr[i]);
        }
    }
    return ret;
}


jQuery(document).ready(function(){
    jQuery(".xrefGroup .btn").click(function(){
		if(jQuery(this).hasClass("active")){
            jQuery(this).removeClass("active");
			active = 1;
		} else {
            jQuery(this).addClass("active");
			active = 0;
		}
        jQuery.ajax({
			url: 'index.php?option=com_engine&task=xref.toggle',
			type: 'POST',
			data: {
				active: active,
				from: jQuery(this).parent().attr('from'),
				to: jQuery(this).parent().attr('from'),
				id: jQuery(this).parent().attr('parent_id'),
				value: jQuery(this).attr('btn_id'),
			},
			success: function(data) {
				
			}
		});
	});
});