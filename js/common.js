
function changelanguage()
{
var lan;
lan=document.getElementById('lang').value;

var splitted=lan.split("_");
Set_Cookie('language',splitted[0],0, "/") ;
Set_Cookie('io_lang_code',splitted[1],0, "/") ;
var langcook=Get_Cookie('language');
window.location.href = window.location.href;
}





function Set_Cookie( name, value, expires, path, domain, secure ) 
{	


	document.cookie = name + "=" +escape( value ) +

		( ( path ) ? ";path=" + path : "" ) + 

		( ( domain ) ? ";domain=" + domain : "" ) +

		( ( secure ) ? ";secure" : "" );

}


function Get_Cookie( name )
 {
	var start = document.cookie.indexOf( name + "=" );
	var len = start + name.length + 1;
	if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) )
	{
		return null;
	}

	if ( start == -1 ) return null;

	var end = document.cookie.indexOf( ";", len );

	if ( end == -1 ) end = document.cookie.length;

	return unescape( document.cookie.substring( len, end ) );

}



function Delete_Cookie( name, path, domain ) 
{
	if ( Get_Cookie( name ) ) document.cookie = name + "=" +

			( ( path ) ? ";path=" + path : "") +

			( ( domain ) ? ";domain=" + domain : "" ) +

			";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}
