//----------------------------------
function getTooltipValue( aValue ) {
//----------------------------------
	var sValue = aValue[0];
	if(sValue.length == 1) sValue = '0000';
	else if(sValue.length == 3) sValue = '0' + sValue;
	return sValue.substr(0,2) + ':' + sValue.substr(2);
}