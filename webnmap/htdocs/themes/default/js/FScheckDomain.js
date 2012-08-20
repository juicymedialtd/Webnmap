/*
PRODUCT: 	JavaScript Utilities for Email/Domain Validation
VERSION:	1.01, April 2004
CONTACT:	Future Shock Ltd, www.Future-Shock.net, post@future-shock.net

This file may be used freely as long as none of the comments are removed.

NOTES:
The optional CheckTLD argument on both functions will verify that the address ends in a two-letter country or well-known TLD.
This can be disabled by sending a value of false.
Both routines use the TITLE attribute of the form element in validity warning messages.
*/

var knownDomsPat=/^(com|net|org|edu|int|mil|gov|arpa|biz|aero|name|coop|info|pro|museum|co|uk)$/;

function FSfncValidateDomain(FormField,NoWWW,CheckTLD) {
	// NoWWW and CheckTLD are optional, both accept values of true, false, and null.
	// NoWWW is used to check that a domain name does not begin with 'www.', eg. for WHOIS lokkups.
	DomainName=FormField.value.toLowerCase();
	if (CheckTLD==null) {CheckTLD=true}
	var specialChars="/\\(\\)><@,;:\\\\\\\"\\.\\[\\]";
	var validChars="\[^\\s" + specialChars + "\]";
	var atom=validChars + '+';
	var atomPat=new RegExp("^" + atom + "$");
	var domArr=DomainName.split(".");
	var len=domArr.length;
	if (len==1) {alert(FormField.title + " invalid"); FormField.focus(); return false}
	for (i=0;i<len;i++) {if (domArr[i].search(atomPat)==-1) {alert(FormField.title + " invalid"); FormField.focus(); return false}}
	if ((CheckTLD) && (domArr[domArr.length-1].length!=2) && (domArr[domArr.length-1].search(knownDomsPat)==-1)) {alert(FormField.title + " must end in a well-known domain or two letter country."); FormField.focus(); return false}
	if ((NoWWW) && (DomainName.substring(0,4).toLowerCase()=="www.")) {alert(FormField.title + " invalid: starts with www."); FormField.focus(); return false}
	return true;
	}

function FSfncValidateEmailAddress (FormField,CheckTLD) {
	// CheckTLD is optional, it accepts values of true, false, and null.
	emailStr = FormField.value.toLowerCase()
	if (CheckTLD==null) {CheckTLD=true}
	var emailPat=/^(.+)@(.+)$/;
	var specialChars="\\(\\)><@,;:\\\\\\\"\\.\\[\\]";
	var validChars="\[^\\s" + specialChars + "\]";
	var quotedUser="(\"[^\"]*\")";
	var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/;
	var atom=validChars + '+';
	var word="(" + atom + "|" + quotedUser + ")";
	var userPat=new RegExp("^" + word + "(\\." + word + ")*$");
	var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$");
	var matchArray=emailStr.match(emailPat);
	if (matchArray==null) {alert(FormField.title + " seems incorrect (check @ and .'s)"); FormField.focus(); FormField.focus(); return false}
	var user=matchArray[1];
	var domain=matchArray[2];
	for (i=0; i<user.length; i++) {if (user.charCodeAt(i)>127) {alert(FormField.title + " username contains invalid characters"); return false}}
	for (i=0; i<domain.length; i++) {if (domain.charCodeAt(i)>127) {alert(FormField.title + " domain name contains invalid characters"); FormField.focus(); return false}}
	if (user.match(userPat)==null) {alert(FormField.title + " username is invalid."); FormField.focus(); return false}
	var IPArray=domain.match(ipDomainPat);
	if (IPArray!=null) {for (var i=1;i<=4;i++) {if (IPArray[i]>255) {alert(FormField.title + " IP address is invalid"); FormField.focus(); return false}}; return true}
	var atomPat=new RegExp("^" + atom + "$");
	var domArr=domain.split(".");
	var len=domArr.length;
	for (i=0;i<len;i++) {if (domArr[i].search(atomPat)==-1) {alert(FormField.title + " domain name is invalid."); FormField.focus(); return false}}
	if ((CheckTLD) && (domArr[domArr.length-1].length!=2) && (domArr[domArr.length-1].search(knownDomsPat)==-1)) {alert(FormField.title + " must end in a well-known domain or two letter country."); FormField.focus(); return false}
	if (len<2) {alert(FormField.title + " is missing a hostname"); FormField.focus(); return false}
	return true;
	}
