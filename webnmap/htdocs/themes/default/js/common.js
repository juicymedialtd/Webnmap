var hr = escape(window.location.href);
var popups = new Array();

function MM_preloadImages() { //v3.0
	var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
	var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
	if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
	var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.0
	var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
	d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
	if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
	if(!x && document.getElementById) x=document.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
	var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
	if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function Popup(url,width,height,target,status){
	if(!target) target = '_blank';
	if(!width)  width  = '430';
	if(!height) height = '250';
	if(!status) status = 'no';

	popups[popups.lenght] = window.open(
		url,
		target,
		"width="+width+", height="+height+", scrollbars=yes, status="+status+", resizable=yes"
	);
}
function Replay(id,url){
document.getElementById(id).src=url;
}


function submit_tell_friend(form, type, templ){
	real_action = '/tell_friend.php'
	target="_tell_friend"
	if (type != 3){
		email = form.friendsemail.value
		arr = email.match("^[0-9a-zA-Z]([0-9a-zA-Z\._\-]*)@(([0-9a-zA-Z\-]+\.)+)([0-9a-zA-Z\-]+)$")
	
		if (!arr){
			alert("Please enter valid email")
			return
		}
	}
	
	if (type == 2){
		window.open(real_action + "?type=2&friend_email=" + escape(email), target, "width=580, height=400, location=0, menubar=0, status=0, resizable=1");
	}else if (type == 3){
		window.open(real_action + "?type=3&templ=" + escape(templ), target, "width=580, height=400, location=0, menubar=0, status=0, resizable=1");
	}else{
		window.open(real_action + "?friend_email=" + escape(email), target, "width=580, height=400, location=0, menubar=0, status=0, resizable=1");
	}
	
}

function wopen2(url){
	window.open(url, 'ww', 'width=600, height=450, location=no,resizable=yes,scrollbars=yes');
}

function wopen(url){
	window.open(url, 'ww', 'width=550, height=450, location=no,resizable=yes,scrollbars=yes');
}
function wopen3(url) {
    window.open(url, 'ww', 'width=575, height=385, location=no,resizable=yes,scrollbars=no');
}

/*function submit_wish(){
	var real_action = 'wish.php';
	var target="Thank_You";
	var wish = document.forms['wish_form'].wish.value;
	var url = document.forms['wish_form'].url.value;
	if( wish.length < 1  ){
		alert("Enter subject you can not find, please !");
	}else if( url.length < 1){
		alert("Enter url of a sample site, please !");
	}else{
		window.open( real_action+"?wish="+escape(wish)+"&url="+escape(url)+"&wish_type=<?=(isset($wish_type) ? $wish_type : "1")?>", target, "location=0, menubar=0, status=0, resizable=1" );
	}
}*/



