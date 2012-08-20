var _aResults = new Array();
var _aFilters = new Array();

var Flights = new Section('Flights');
var Hotels = new Section('Hotels');
var Cars = new Section('Cars');

var deselected = false;
var selected = true;

// ----------------------------------------------------------------
function Section( sName ) {
// ----------------------------------------------------------------
	this.sName = sName;
	this.aResults = new Array();
	this.aFilters = new Array();

	this.AddFilter = function( sProperty, sFilterType ) {
		if(!this.aFilters[sProperty]) this.aFilters[sProperty] = new Filter(sFilterType, sProperty);
	}

	this.ApplyFilters = function( sFilter ) {

		var aFiltered = this.aResults;
		for(var sProperty in  this.aFilters) {
			var oFilter = this.aFilters[sProperty];
			if(aFiltered.length && oFilter.sProperty && oFilter.sFilterType) {
				aFiltered = aFiltered.Filter(oFilter, sProperty, sFilter);
			}
		}

		aXML = new Array();
		for(var i=0; i < this.aResults.length; i++) {
			var oObj = this.aResults[i];
			if(oObj.bVisible != oObj.bNewVisible) {
				var sEvent = oObj.bNewVisible ? 'filter-in' : 'filter-out';
				bpc.task(['b:action', 'trigger', 'b:event', sEvent, 'b:target', "id('item-" + this.sName + "-" + oObj.iIndex + "')", 'b:async', 'true']);
				oObj.bVisible = oObj.bNewVisible;
			}
		}
	}
}

// ----------------------------------------------------------------
function Item( iIndex ) {
// ----------------------------------------------------------------
	this.iIndex		= iIndex;
	this.bVisible	= null;
	this.bNewVisible	= null;

	this.toString	= function() {
		return '\n--------\nindex:\t' + this.iIndex + '\nbVisible:\t' + this.bVisible + '\nbNewVisible:\t' + this.bNewVisible;
	}
}


// ----------------------------------------------------------------
function Filter( sFilterType, sProperty ) {
// ----------------------------------------------------------------
	this.sFilterType	= sFilterType;
	this.sProperty		= sProperty;
	this.aMatches		= new Array();
	this.iLower			= -Infinity;
	this.iUpper			= Infinity;
	this.sChoice		= '';

	this.SetUpper		= function( iUpper ) { this.iUpper = iUpper; }

	this.SetLower		= function( iLower ) { this.iLower = iLower; }

	this.AddMatch		= function( Match ) {
		if(this.aMatches.indexOf(Match) == -1) this.aMatches.push(Match);
	}

	this.RemoveMatch	= function( Match ) {
		var iIndex = this.aMatches.indexOf(Match);
		if(iIndex != -1) this.aMatches.splice(iIndex, 1);
	}

	this.RemoveMatch	= function( Match ) {
		var iIndex = this.aMatches.indexOf(Match);
		if(iIndex != -1) this.aMatches.splice(iIndex, 1);
	}

	this.SetChoice		= function( Choice ) {
		this.sChoice = Choice;
	}

	this.toString	= function( ) {
		return '\nFiltering: ' + this.sProperty + ' = ' + this.iUpper;
	}
}



// ----------------------------------------------------------------
Array.prototype.Filter = function( oFilter, sProp, sFilter ) {
// ----------------------------------------------------------------
	var aFiltered = new Array();

	var sFilterType = oFilter.sFilterType;

	switch(sFilterType) {

		case 'NUMBER' :
			var iUpper = oFilter.iUpper;
			var iLower = oFilter.iLower;
			for(var i=0; i<this.length; i++) {
				var oObj = this[i];
				if(oObj[sProp] >= iLower && oObj[sProp] <= iUpper) {
					aFiltered.push(oObj);
					oObj.bNewVisible = true;
				}
				else {
					oObj.bNewVisible = false;
				}
			}
			break;

		case 'MATCH' :
			for(var i=0; i<this.length; i++) {
				var oObj = this[i];
				if(oFilter.aMatches.indexOf(oObj[sProp]) != -1) {
					aFiltered.push(oObj);
					oObj.bNewVisible = true;
				}
				else {
					oObj.bNewVisible = false;
				}
			}
			break;

		case 'CHOICE' :
			var sChoice = oFilter.sChoice;
			for(var i=0; i<this.length; i++) {
				var oObj = this[i];
				if(sChoice == '__ALL__' || oObj[sProp] == sChoice) {
					aFiltered.push(oObj);
					oObj.bNewVisible = true;
				}
				else {
					oObj.bNewVisible = false;
				}
			}
			break;

		default:
			break;
	}
	return aFiltered;
}



// ----------------------------------------------------------------
Array.prototype.indexOf = function( Match ) {
// ----------------------------------------------------------------
	for(var i=0; i<this.length; i++) {
		var oCurrent = this[i];
		if(Match == oCurrent) return i;
	}
	return -1;
}



if(!Array.prototype.push) {
	// ----------------------------------------------------------------
	Array.prototype.push = function( Item ) {
	// ----------------------------------------------------------------
		this[this.length] = Item;
	}
}



if(!Array.prototype.splice) {
	// ----------------------------------------------------------------
	Array.prototype.splice = function(iStart, iLength) {
	// ----------------------------------------------------------------
		/*
			This is a partial implementation of Array.splice() for IE 5.0 & 5.5
			The real splice() can accept extra arguments that are inserted in stead
			of the spliced items.
		*/

		var aTemp = new Array();
		var aReturn = new Array();
		if(!iLength || iLength > this.length - iStart) iLength = this.length - iStart;

		// Copy the values to the new (to be returned) array
		for(var i=iStart+iLength-1; i>=iStart; i--) {
			aReturn[i-iStart] = this[i];
		}

		// Re-position the values from the array
		for(i=iStart; i<this.length; i++) {
			this[i] = this[i+iLength];
		}

		// prune the end of the array
		this.length = this.length - iLength;

		return aReturn;
	}
}



var _bMyAlert = true;
// ----------------------------------------------------------------
function MyAlert( ) {
// ----------------------------------------------------------------
    if(_bMyAlert) {
        var sAlert = '';
        for(var i=0; i<arguments.length; i++) {
            sAlert += arguments[i] + '\n';
        }
        _bMyAlert = confirm(sAlert);
    }
}
window.alert = MyAlert;



// ----------------------------------------------------------------
function DBG( oObj ) {
// ----------------------------------------------------------------
	var s = '';
	for(var i in  oObj) {
		s += i + oObj[i] + '\n';
	}
	alert(s);
}