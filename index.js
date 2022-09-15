window.menuController =  document.getElementById("menuContainer");
document.getElementById("bgVid").style.marginTop = "0px";
document.getElementById("bgVid").style.position = "relative";
document.getElementById("vid-div").style.height = "400px";
document.getElementById("vid-div").style.overflow = "hidden";
document.getElementById("vid-div").style.position = "relative";
document.getElementById("vid-div").style.zIndex = "0";
document.getElementById("des-text").style.display = "flex";
document.getElementById("des-para").style.color = "black";
// document.getElementById("des-para").style.marginTop = "60px";
document.getElementById("pub-des").style.marginLeft = "10%";
document.getElementById("des-text").style.marginRight = "14%";
document.getElementById("des-text").style.marginTop = "5%";
document.getElementById("des-text").style.marginLeft = "1%";
// document.getElementById("des-tit").style.marginLeft = "20%";
// document.getElementById("des-tit").style.position = "absolute";
document.getElementById("des-tit").style.color = "black";
document.getElementById("text-div").style.color = "black";
document.getElementById("top-para").style.color = "white";
document.getElementById("hOne").style.color = "white";
document.getElementById("text-div").style.position = "absolute";
document.getElementById("text-div").style.marginBottom = "30%";
document.getElementById("text-div").style.zIndex = "1";
document.getElementById("pub-des").style.position = "relative";
//Styling for find, join and book section.
//if($(window).width() > 319 & $(window).width() < 481){
  document.getElementById("book-t").style.display = "flex";
  document.getElementById("book-t").style.justifyContent = "center";
  document.getElementById("book-t").style.fontSize = "38px";
  document.getElementById("book-t").style.marginTop = "130px";
  document.getElementById("book-t").style.marginLeft = "2%";
  document.getElementById("book-t").style.marginRight = "2%";
  document.getElementById("FJB").style.display = "none";
  document.getElementById("find-m").style.fontSize = "xx-large";
  document.getElementById("join-m").style.fontSize = "xx-large";
  document.getElementById("book-m").style.fontSize = "xx-large";
  document.getElementById("exp-fjb").style.marginLeft = "0.7em";
  document.getElementById("exp-fjb").style.marginRight = "0.5em";
/*} else if($(window).width() > 481){
    document.getElementById("book-t").style.display = "flex";
    document.getElementById("book-t").style.justifyContent = "center";
    document.getElementById("book-t").style.fontSize = "38px";
    document.getElementById("book-t").style.marginTop = "130px";
    document.getElementById("book-t").style.marginLeft = "2%";
    document.getElementById("book-t").style.marginRight = "2%";
    document.getElementById("FJB").style.marginTop = "50px";
    document.getElementById("FJB").style.display = "flex";
    document.getElementById("FJB").style.justifyContent = "center";
    document.getElementById("FJB").style.justifyContent = "space-around";
    document.getElementById("find").style.textDecoration = "none";
    document.getElementById("find").style.fontSize = "xx-large";
    document.getElementById("find").style.color = "black";
    document.getElementById("join").style.textDecoration = "none";
    document.getElementById("join").style.fontSize = "xx-large";
    document.getElementById("join").style.color = "black";
    document.getElementById("book").style.textDecoration = "none";
    document.getElementById("book").style.fontSize = "xx-large";
    document.getElementById("book").style.color = "black";
    document.getElementById("find-m").style.display = "none";
    document.getElementById("join-m").style.display = "none";
    document.getElementById("book-m").style.display = "none";
    document.getElementById("exp-fjb").style.display = "flex";
    document.getElementById("exp-fjb").style.justifyContent = "center";
    document.getElementById("paraO").style.paddingRight = "10px";
    document.getElementById("paraO").style.marginLeft = "20px";
    document.getElementById("paraT").style.paddingRight = "10px";
    document.getElementById("paraT").style.marginLeft = "80px";
    document.getElementById("paraTh").style.marginRight = "30px";
}*/


function openFirst() {
menuController.open('end');
}

function openEnd() {
menuController.open('end');
}

function openCustom() {
menuController.open('custom');
}
function scrollleft(){
var Left = document.querySelector(".cities-divv");
Left.scrollBy(-450, 0);
}
function scrollRight(){
  var Right = document.querySelector(".cities-divv");
  Right.scrollBy(450, 0);
}
function goToCity(){
window.scroll(0, 400);
}