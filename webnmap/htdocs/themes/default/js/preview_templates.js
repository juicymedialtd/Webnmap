/*
Simple Image Trail script- By JavaScriptKit.com
Visit http://www.javascriptkit.com for this script and more
This notice must stay intact
*/

var offsetfrommouse=[15,15]; //image x,y offsets from cursor position in pixels. Enter 0,0 for no offset
var displayduration=0; //duration in seconds image should remain visible. 0 for always.
var currentimageheight = 410;	// maximum image size.

var currentimagewidth = 490;	// maximum image size.
var timer;

function gettrailobj(){
if (document.getElementById)
return document.getElementById("preview_div").style
else if (document.all)
return document.all.trailimagid.style
}

function gettrailobjnostyle(){
if (document.getElementById)
return document.getElementById("preview_div")
else if (document.all)
return document.all.trailimagid
}


function truebody(){
return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}


function hidetrail(){	
	gettrailobj().display= "none";
	document.onmousemove=""
	gettrailobj().left="-500px"
	clearTimeout(timer);
}

function showtrail(imagename,title,showthumb){
	i = imagename
	t = title
	s = showthumb
	timer = setTimeout("show('"+i+"',t,s);",200);
}
function show(imagename,title,showthumb){
        var docwidth=document.all? truebody().scrollLeft+truebody().clientWidth : pageXOffset+window.innerWidth - offsetfrommouse[0]
	var docheight=document.all? Math.min(truebody().scrollHeight, truebody().clientHeight) : Math.min(window.innerHeight)

if((navigator.userAgent.indexOf("Firefox")!=-1 || (navigator.userAgent.indexOf("Opera")==-1 && navigator.appVersion.indexOf("MSIE")!=-1)) && (docwidth>650 && docheight>500)){

	document.onmousemove=followmouse; 
	
	newHTML = '<div class="border_preview"><div id="loader_container"><div id="loader"><div align="center">Loading template preview...</div><div id="loader_bg"><div id="progress"> </div></div></div></div>';
	newHTML = newHTML + '<h2 class="title_h2">' + title + '</h2>'
	if (showthumb > 0){
    newHTML = newHTML + '<div align="center" style="padding: 8px 10px 17px 10px;"><img onload="javascript:remove_loading();" src="' + imagename + '" border="0"></div>';
}

	newHTML = newHTML + '</div>';
	if(navigator.userAgent.indexOf("Firefox")==-1){	newHTML = newHTML+'<iframe src="about:blank" scrolling="no" frameborder="0" width="390" height="390"></iframe>';}

	gettrailobjnostyle().innerHTML = newHTML;

	gettrailobj().display="block";

}

function followmouse(e){

	var xcoord=offsetfrommouse[0]
	var ycoord=offsetfrommouse[1]

	var docwidth=document.all? truebody().scrollLeft+truebody().clientWidth : pageXOffset+window.innerWidth - offsetfrommouse[0]
	var docheight=document.all? Math.min(truebody().scrollHeight, truebody().clientHeight) : Math.min(window.innerHeight)

	if (typeof e != "undefined"){
		if (docwidth - e.pageX < currentimagewidth){

		if(navigator.userAgent.indexOf("Firefox")!=-1)	{xcoord = e.pageX - xcoord - currentimagewidth + 2*offsetfrommouse[0]} else{ xcoord = e.pageX - xcoord - currentimagewidth + 6*offsetfrommouse[0] ;} // Move to the left side of the cursor
		} else {
			xcoord += e.pageX;
		}
		if (docheight - e.pageY < (currentimageheight + 110)){
			ycoord += e.pageY - Math.max(0,(110 + currentimageheight + e.pageY - docheight - truebody().scrollTop));
		} else {
			ycoord += e.pageY;
		}

	} else if (typeof window.event != "undefined"){
		if (docwidth - event.clientX < currentimagewidth){
			xcoord = event.clientX + truebody().scrollLeft - xcoord - currentimagewidth + 2*offsetfrommouse[0]; // Move to the left side of the cursor
		} else {
			xcoord += truebody().scrollLeft+event.clientX
		}
		if (docheight - event.clientY < (currentimageheight + 110)){
			ycoord += event.clientY + truebody().scrollTop - Math.max(0,(110 + currentimageheight + event.clientY - docheight));
		} else {
			ycoord += truebody().scrollTop + event.clientY;
		}
	}

	var docwidth=document.all? truebody().scrollLeft+truebody().clientWidth : pageXOffset+window.innerWidth-offsetfrommouse[0]
	var docheight=document.all? Math.max(truebody().scrollHeight, truebody().clientHeight) : Math.max(document.body.offsetHeight, window.innerHeight)
		if(ycoord < 0) { ycoord = ycoord*-1; }
	gettrailobj().left=xcoord+"px"
	gettrailobj().top=ycoord+"px"
}
}
