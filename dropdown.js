

// main function to handle the mouse events //
function ddMenu(id,d){
  ;
  var c = document.getElementById(id + '-ddcontent');
  //alert(c);
  if(c!=null)
  {
  if(d == 1){
   
      c.style.display = 'block';
     
      c.style.height = c.offsetHeight;
  }else{
  c.style.display = 'none';

  }
}
}



// cancel the collapse if a user rolls over the dropdown //
function cancelHide(id){
var p=document.getElementById(id + '-ddcontent');
if(p!=null){
 p.style.display = 'block';
}
}