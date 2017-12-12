var tableFieldset= new Class.create();
Array.max = function( array ){
    return Math.max.apply( Math, array );
};

function escapeRegExp(string) {
    return string.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
}

function replaceAll(string, find, replace) {
	  return string.replace(new RegExp(escapeRegExp(find), 'g'), replace);
	}

tableFieldset.prototype = {
	    initialize : function(tableId,containerId ,templateId){
	        this.containerId    = containerId;
	        this.tableId  = tableId;
	        this.templateId = templateId;
	    },
	    deleteRow : function (event) {
	    	 var tr = Event.findElement(event, 'tr');
	    	 tr.remove();
	         //console.log(tr.id);
	    	},
	    addRow : function() {
	    	var condition = '#' + this.containerId + ' tr';
	    	var numrows = $$(condition).length;
	    	var templateRow = this.getTemplate();
	    	var mx = this.getMaxRowId();
	    	$(mx).insert({after:templateRow});
	    } 	,
	    getTemplate : function() {
	    	var ids = new Array();
	    	var condition = '#' + this.containerId + ' tr';
	    	$$(condition).each( function(element) {
	    		console.log(element.readAttribute('id'));
	    		ids.push(element.readAttribute('id'));
	    	}
	    			) ;
	    	
	    	console.log(ids.max());
	    	var row_id = ids.max();
	    	var next_id = parseInt(row_id) + 1;
	    	var templateRow = '<tr ' + ' id =' + next_id + '>'+  $(  this.templateId ).innerHTML + '</tr>';
	    	
	    	var find = "[" + row_id + "]";
	    	var re = new RegExp(find, 'g');

	    	//var res = templateRow.replace(re, "[" + next_id + "]"); 
	    	var replace = "[" + next_id + "]";
	    	var res= replaceAll(templateRow, find, replace) ;

	    	return res;
	    },
	    getMaxRowId :function() {var ids = new Array();
    	var condition = '#' + this.containerId + ' tr';
    	$$(condition).each( function(element) {
    		console.log(element.readAttribute('id'));
    		ids.push(element.readAttribute('id'));
    	}
    			) ;
    	
    	console.log(ids.max());
    	var row_id = ids.max();
	    	return row_id;
	    }
	    }
