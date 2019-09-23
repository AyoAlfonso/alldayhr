$(document).ready(function(){
     document.getElementById("navigation-linkhr").style.marginLeft = "10.5%";
     document.getElementById("navigation-linkhr").style.width = "20%";
});

$("#horizontal-menu").mouseleave(()=>{
  document.getElementById("navigation-linkhr").style.marginLeft = "10.5%";
  document.getElementById("navigation-linkhr").style.width = "20%";
});

$("li.general").hover(()=>{
  document.getElementById("navigation-linkhr").style.marginLeft = "1.5%";
  document.getElementById("navigation-linkhr").style.width = "7%";
});

$("li.subscription").hover(()=> {
  document.getElementById("navigation-linkhr").style.marginLeft = "10.5%";
  document.getElementById("navigation-linkhr").style.width = "20%";
});

$("li.billing").hover(()=> {
  document.getElementById("navigation-linkhr").style.marginLeft = "33%"
  document.getElementById("navigation-linkhr").style.width = "6%";

});
$("li.security").hover(()=>{ 
  document.getElementById("navigation-linkhr").style.marginLeft = "43%"
  document.getElementById("navigation-linkhr").style.width = "7%";
});

$("li.connectedapps").hover(()=> {
  document.getElementById("navigation-linkhr").style.marginLeft = "55%";
  document.getElementById("navigation-linkhr").style.width = "15%";
});

$("li.deleteacc").hover(()=> {
   document.getElementById("navigation-linkhr").style.marginLeft = "74%";
   document.getElementById("navigation-linkhr").style.width = "17%";
  });