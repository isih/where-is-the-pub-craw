<!DOCTYPE html>
<html lang="en">
<head>
    <script type="text/javascript" src="index.js"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="icon" href="pub-trans.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- jQuery -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <?php
    include 'common_header.php';
    ?>
    <script src="//www.jsdelivr.com/package/npm/date-fns@2.28.0/format"></script>
    <script src="//www.jsdelivr.com/packag/npm/date-fns@2.28.0/parseISO"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Serif:opsz,wght@8..144,500&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans&display=swap" rel="stylesheet">

    <style type="text/css" >
        .slider_container .container{
            padding-left: 15px;
            padding-right: 15px;
        }
        .card_slider{
            padding: 50px 0;
        }
        .desk-nav{
            display: flex;
            justify-content: space-around;
        }
        a{
            text-decoration: none;
            font-size: 18px;
            color: black;
            font-weight: bold;
        }
        a:hover{
            color: #ef8028;
        }
        #book-btn:hover{
            background-color: black;
            color: #ef8028;
        }

        ion-button  {
            --background:#ef8028 ;
            --color:black ;
        }

        @media (min-width:1281px){
            .text-m{
                color: white;
                font-size: 50px;
                font-weight: bold;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
            .child .child-image{
            width: 400px;
            height: 300px;
            }
            #nav-btnn{
                display: none;
            }
        }
        @media (min-width:1025px) and (max-width:1280px){
            .text-m{
                color: white;
                font-size: 50px;
                font-weight: bold;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
            .child .child-image{
            width: 300px;
            height: 200px;
            }
            #nav-btnn{
                display: none;
            }
        }
        @media (min-width:768px) and (max-width: 1024px){
            .child .child-image{
            width: 300px;
            height: 200px;
            }
            .text-m{
                color: white;
                font-size: 50px;
                font-weight: bold;
                position: absolute;
                top: 50%;
                left: 150px;
                transform: translate(-50%, -50%);
            }
            #nav-btnn{
                display: none;
            }
        }
       
        @media (min-width: 320px) and (max-width: 480px){
            .child .child-image{
            width: 300px;
            height: 200px;
            }
            .desk-nav{
                display: none;
            }
            #hOne{
                font-size: 17px;
            }
            #top-para{
                font-size: 13px;
            }
            #des-tit, #des-para, #book-btn{
                margin-left: 7%;
            }
            #pub-des{
                display: none;
            }

        
            .cover{
                padding: 0px 30px;
                position: relative;
                width: auto;
            }
            .cities-divv{
                display: flex;
                width: auto;
                height: auto;
                overflow: auto;
                position: relative;
                scroll-behavior: smooth;
                justify-content: space-between;
            }

            .cities-divv::-webkit-scrollbar{
                width: 0;
            }
            .text-m{
                color: white;
                font-size: 50px;
                font-weight: bold;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            #book-t{
                display: flex;
                justify-content: center;
                font-size: 38px;
                margin-top: 130px;
                margin-left: 2%;
                margin-right: 2%;
            }
        }
        @media (min-width: 481px) and (max-width: 599px){
            .desk-nav{
                display: none;
            }
            #pub-des{
                display: none;
            }
            .text-m{
                color: white;
                font-size: 40px;
                font-weight: bold;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
            .child .child-image{
            width: 300px;
            height: 200px;
            }

        }
        @media (min-width: 600px) and (max-width: 767px){
            .text-m{
                color: white;
                font-size: 50px;
                font-weight: bold;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
            .desk-nav{
                display: none;
            }
            .child .child-image{
            width: 300px;
            height: 200px;
            }
        }
    </style>

</head>

<body>
<ion-app style="overflow:auto">
    <ion-menu side="end" id="menuContainer" menu-id="first" content-id="main">
          <ion-content>
            <ion-list>
              <ion-item href="index.php">Home</ion-item>
              <ion-item href="#about-t">About</ion-item>
              <ion-item href="#city-head">Cities</ion-item>
              <ion-item href="https://south.tours/magazine/" target="_blank">Blog</ion-item>
              <ion-item href="#faq-t">FAQ</ion-item>
              <ion-item href="#contact-h">Contact</ion-item>
            </ion-list>
          </ion-content>
    </ion-menu>
    <ion-header>
        <ion-toolbar id="toolbar" color="white">
            <ion-button style='float:right;' onclick="openFirst()" id="nav-btnn">
              <ion-icon  name="menu-outline"></ion-icon>
            </ion-button>

            <ion-buttons slot="start">
                <a id="main-logo" href="index.php">
                    <img src="imgs/logo.webp" alt="logo" />
                </a>
            </ion-buttons>
            <ion-text class="desk-nav">
                <a href="index.php"><p>Home</p></a>
                <a href="#about-t"><p>About</p></a>
                <a href="#city-head"><p>Cities</p></a>
                <a href="https://south.tours/magazine/" target="_blank"><p>Blog</p></a>
                <a href="#faq-t"><p>FAQ</p></a>
                <a href="#contact-h"><p>Contact</p></a>
            </ion-text>
        </ion-toolbar>
    </ion-header>

    <ionic-content id="bgVid" width="100%">
        <div id="vid-div">
          <div id="text-div">
            <h1 id="hOne">Real Time Pub Crawl Location</h1>
            <p id="top-para">Find where the Pub Crawl is going in real time. You will be able to join the party even if you are late!</p>
            <button id="check" onclick="parent.open('https://fareharbor.com/embeds/book/southtours/items/148437/calendar/2022/08/?flow=no')">Check Availability</button>
          </div>
          <video muted autoplay loop src="cover-video.mp4"></video>
        </div>
      </div>
    </ionic-content>
    <ion-text color="secondary" id="des-text">
      <img src="pub-trans.png" alt="logo" width="150px" height="150px" id="pub-des" />
      <div>
      <h2 id="des-tit">What "Where is the Pub Crawl?" does:</h2>
      <p id="des-para">Welcome to "Where is The Pub Crawl". This app will help you find the realtime location of your favourite Pub Crawl, Bar Hop or Nightlife tour, You just need to navigate down the webpage and in the "Cities" section click on your city to find your pub crawl and you will be able join the Pub Crawl even if you were late and were not able to meet at the starting point. Moreover if you haven't booked your Pub Crawl yet then you can book it easily By clicking on "Book Now" Button.</p>
      <a href="https://fareharbor.com/embeds/book/southtours/items/148437/calendar/2022/08/?flow=no" target="_blank">
                <input id="book-btn" type="button" value="Book Now">
                </a>
    </div>
    </ion-text>
    <h2 id="city-head">Cities</h2>
    <section class="slider_container">
    <div class="container">
        <div class="swiper card_slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="img_box">
                        <div class="child"><image class="child-image" src="malaga.jpg" alt="Image" ><div class="text-m" >Malaga</div></div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="img_box">
                        <div class="child"><image class="child-image" src="granada.jpg" alt="Image" ><div class="text-m">Granada</div></div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="img_box">
                        <div class="child"><image class="child-image" src="madrid.png" alt="Image" ><div class="text-m" >Madrid</div></div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="img_box">
                        <div class="child"><image class="child-image" src="valencia.jpeg" alt="Image" ><div class="text-m">Valencia</div></div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="img_box">
                        <div class="child"><image class="child-image" src="barcelona.jpg" alt="Image" ><div class="text-m"  height="200px">Barcelona</div></div>
                    </div>
                </div>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    </section>
    <ionic-content>
      <div id="book-t">Use This To</div>
      <div id="FJB">
          <p><a id="find">Find</a></p>
          <p><a id="join">Join</a></p>
          <p><a id="book">Book</a></p>
      </div>
      <div id="exp-fjb">
          <p id="paraO"><p id="find-m">Find</p><br/>The app provides exact real time information about where the nearest
              pub crawls to you are and the time they start. At any time, no matter where you are. You will have the most up-to-date information on the best parties happening near you.</p>
          <p id="paraT"><p id="join-m">Join</p><br/>Although many pub crawls begin at a certain time, you should feel free to join the crawl at any stage. Perhaps you’re running a bit late, live slightly outside
              the centre or spontaneously decided to go out. Our app helps you find what’s going on
              right now
              so that you can join in the fun! It will provide you details about how to find the group,
              including a sign/t-shirt that the guide will be wearing. Feel free to join at a later stage if you don’t feel like completing every single pub.</p>
          <p id="paraTh"><p id="book-m">Book</p><br/>If you wish, you can book a
              pub crawl through our app. The app will provide all the details
              you need on the various
              pub crawls
              that are available to book. Therefore, you can have piece
              of mind in ensuring that your tickets are booked before heading out.
              </p>        
      </div>
      <ion-text>
        <div>
            <p id="about-t">About Us</p></br>
            <p id="about-p">South Tours is a booking service where every traveler can enrich his or her trip by discovering and taking part unique experiences anywhere in Spain. Our team is formed of field experts who are passionate and keen to show the glorious beauty and distinctive culture of Spain. Every experience is hand picked after a careful process carried out by our professionals to ensure that only the most relevant and worthwhile ones are included. Our website is backed up by high technology together with a dedicated team that strives to keep the process bug-free and deliver the best service for our customers right from the first click.</p>
        </div>
      </ion-text>
      <ion-text>
        <p id="faq-t">FAQ</p>
      </ion-text>
      <button id="fq" onclick="firstQfun()">1. What is WITP?</button>
      <div id="fa">
            <p>A. WITP stands for ‘Where is the Pub Crawl?’ WITP is a new service that consists of tracking in real time the best pub crawls in your city. It provides information on the easiest, fastest and most comfortable way of getting to the event so that you can enjoy the best parties taking place right now. Depending on which city you’re in, we provide different routes according to which part of town you’re in and what time of year it is. Therefore, you can find the best way to socialise and enjoy the city’s nightlife, whenever and wherever you want. WITP is currently available in Malaga and Granada and will soon be available throughout Spain.</p>
        </div>
        <button id="sq" onclick="secondQfun()">2. What do I need to know about a pub crawl?</button>
      <div id="sa">
            <p>A. Put simply, a pub crawl (also known as a bar crawl) is the act of visiting multiple pubs or bars in a single session, and perhaps a club at the end. It is an experience that will allow you to come together with locals as well as other people who are new to a city from all corners of the world. You will spend the evening drinking, dancing and partying until the early hours of the morning. Moreover, a pub crawl will help you save money by paying a single fee rather than an entry fee to each bar. It also helps you get to know which bars are worth visiting quicker than spending a single night out in each one.</p>
        </div>
        <button id="tq" onclick="thirdQfun()">3. How do I recognize a WITP party guide?</button>
        <div id="ta">
            <p>A. Information on how to recognize your WITP party guide will be provided in real time.</p>
        </div>
        <button id="foq" onclick="fourthQfun()">4. When will WITP be available in other cities?</button>
        <div id="foa">
            <p>A. Currently, you can find WITP in Malaga and Granada, but we are in the process of preparing new openings. Soon, you will be able to use our services in other Spanish cities. We also plan on establishing operations in other countries. Watch this space. Pub crawl Koln, pub crawl Hvar, pub crawl Porto and pub crawl Valencia are all in our plans!</p>
        </div>
        <button id="fifq" onclick="fifthQfun()">5. How can I keep up with news from WITP?</button>
        <div id="fifa">
            <p>A. You can connect to our social media to keep up to date with our journey. The links to our socials are below. You can also subscribe to our newsletter, in which we provide information and updates about our services. Give us a follow or subscribe to make sure you don’t miss out!</p>
        </div>
        <button id="sixq" onclick="sixthQfun()">6. This looks like a fantastic project! How can I join the WITP team?</button>
        <div id="sixa">
            <p>A. To find out about our current openings, consult our career section. Here, you will find information about careers with us, as well as internship positions. If you are someone who is passionate about the world of startups and sustainable mobility and you share our way of thinking, we’d love to have you as part of our ambitious team!</p>
        </div>
    </ionic-content>
    <ionic-content>
      <form action="info.php" method="post">
          <div id="contact-h">Contact Us!</div>
          <input type="text" id="Name" name="Name" placeholder="Enter Your Name"></br>
          <input type="email" id="Email" name="Email" placeholder="Enter Your Email"></br>
          <textarea type="text" id="contact-p" name="Message" placeholder="Enter Your Message here..."></textarea></br>
          <div id="sub-div"><button type="submit" name="submit" id="submit-btn">Submit</button></div>
      </form>
    </ionic-content>

</ion-app>
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script>
      var swiper = new Swiper(".card_slider", {
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
                
            },
            480: {
                slidesPerView: 2,
                spaceBetween: 10,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 50,
    
            },
            1025: {
                slidesPerView: 3,
                spaceBetween: 80,
            },
        },
        keyboard: true,
      });
    </script>
</body>
<script>
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
  function firstQfun(){
      var firstQ = document.getElementById("fq");
      var firstA = document.getElementById("fa");
      if(firstA.style.display === "block"){
          firstA.style.display = "none";
      } else{
          firstA.style.display = "block";
      }
  }

  function secondQfun(){
      var firstQ = document.getElementById("sq");
      var secondA = document.getElementById("sa");
      if(secondA.style.display === "block"){
          secondA.style.display = "none";
      } else{
          secondA.style.display = "block";
      }
  }
  function thirdQfun(){
    var firstQ = document.getElementById("fq");
    var thirdA = document.getElementById("ta");
    if(thirdA.style.display === "block"){
        thirdA.style.display = "none";
    } else{
        thirdA.style.display = "block";
    }
}

function fourthQfun(){
    var firstQ = document.getElementById("fq");
    var fourthA = document.getElementById("foa");
    if(fourthA.style.display === "block"){
        fourthA.style.display = "none";
    } else{
        fourthA.style.display = "block";
    }
}

function fifthQfun(){
    var firstQ = document.getElementById("fq");
    var fifthA = document.getElementById("fifa");
    if(fifthA.style.display === "block"){
        fifthA.style.display = "none";
    } else{
        fifthA.style.display = "block";
    }
}

function sixthQfun(){
    var firstQ = document.getElementById("fq");
    var sixA = document.getElementById("sixa");
    if(sixA.style.display === "block"){
        sixA.style.display = "none";
    } else{
        sixA.style.display = "block";
    }
}


  window.menuController =  document.getElementById("menuContainer");
//document.getElementsByClassName("button-native").style.backgroundColor = "#ef8028";
document.getElementById("city-head").style.marginTop = "5em";
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
document.getElementById("des-tit").style.marginLeft = "15%";
document.getElementById("des-para").style.marginLeft = "15%";
document.getElementById("book-btn").style.marginLeft = "15%";
// document.getElementById("des-tit").style.position = "absolute";
document.getElementById("des-tit").style.color = "black";
document.getElementById("text-div").style.color = "black";
document.getElementById("top-para").style.color = "white";
//document.getElementById("top-para").style.fontSize = "14px";
document.getElementById("hOne").style.color = "white";
//document.getElementById("hOne").style.fontSize = "18px";
document.getElementById("text-div").style.position = "absolute";
document.getElementById("text-div").style.marginBottom = "30%";
document.getElementById("text-div").style.zIndex = "1";
document.getElementById("pub-des").style.position = "relative";
document.getElementById("pub-des").style.marginLeft = "100px";
document.getElementById("about-t").style.display = "flex";
document.getElementById("about-t").style.justifyContent = "center";
document.getElementById("about-t").style.marginTop = "5em";
document.getElementById("about-t").style.fontSize = "38px";
document.getElementById("about-t").style.marginBottom = "0";
document.getElementById("about-p").style.display = "flex";
document.getElementById("about-p").style.justifyContent = "center";
document.getElementById("about-p").style.marginLeft = "10%";
document.getElementById("about-p").style.marginRight = "10%";
document.getElementById("about-p").style.marginTop = "0";
document.getElementById("fa").style.display = "none";
document.getElementById("fq").style.cursor = "pointer";
document.getElementById("fq").style.marginBottom = "0.5em";
document.getElementById("fq").style.display = "block";
document.getElementById("fq").style.width = "100%";
document.getElementById("fq").style.textAlign = "left";
document.getElementById("fq").style.fontSize = "1.5em";
document.getElementById("sa").style.display = "none";
document.getElementById("sq").style.marginBottom = "0.5em";
document.getElementById("sq").style.display = "block";
document.getElementById("sq").style.width = "100%";
document.getElementById("sq").style.textAlign = "left";
document.getElementById("sq").style.fontSize = "1.5em";
document.getElementById("ta").style.display = "none";
document.getElementById("tq").style.marginBottom = "0.5em";
document.getElementById("tq").style.display = "block";
document.getElementById("tq").style.width = "100%";
document.getElementById("tq").style.textAlign = "left";
document.getElementById("tq").style.fontSize = "1.5em";
document.getElementById("foa").style.display = "none";
document.getElementById("foq").style.marginBottom = "0.5em";
document.getElementById("foq").style.display = "block";
document.getElementById("foq").style.width = "100%";
document.getElementById("foq").style.textAlign = "left";
document.getElementById("foq").style.fontSize = "1.5em";
document.getElementById("fifa").style.display = "none";
document.getElementById("fifq").style.marginBottom = "0.5em";
document.getElementById("fifq").style.display = "block";
document.getElementById("fifq").style.width = "100%";
document.getElementById("fifq").style.textAlign = "left";
document.getElementById("fifq").style.fontSize = "1.5em";
document.getElementById("sixa").style.display = "none";
document.getElementById("sixq").style.marginBottom = "0.5em";
document.getElementById("sixq").style.display = "block";
document.getElementById("sixq").style.width = "100%";
document.getElementById("sixq").style.textAlign = "left";
document.getElementById("sixq").style.fontSize = "1.5em";
document.getElementById("faq-t").style.fontSize = "38px";
document.getElementById("faq-t").style.display = "flex";
document.getElementById("faq-t").style.justifyContent = "center";
document.getElementById("faq-t").style.marginTop = "15%";
document.getElementById("contact-h").style.display = "flex";
document.getElementById("contact-h").style.justifyContent = "center";
document.getElementById("contact-h").style.fontSize = "38px";
document.getElementById("contact-h").style.marginTop = "15%";
document.getElementById("contact-h").style.marginBottom = "20px";
document.getElementById("Name").style.marginLeft = "25%";
document.getElementById("Name").style.width = "50%";
document.getElementById("Name").style.height = "2em";
document.getElementById("Name").style.fontSize = "1em";
document.getElementById("Email").style.marginLeft = "25%";
document.getElementById("Email").style.width = "50%";
document.getElementById("Email").style.height = "2em";
document.getElementById("Email").style.fontSize = "1em";
document.getElementById("Email").style.marginTop = "1em";
document.getElementById("contact-p").style.marginLeft = "25%";
document.getElementById("contact-p").style.width = "50%";
document.getElementById("contact-p").style.marginTop = "1em";
//document.getElementById("submit-btn").style.marginLeft = "50%";
document.getElementById("submit-btn").style.marginTop = "1em";
document.getElementById("submit-btn").style.width = "100px";
document.getElementById("submit-btn").style.height = "3em";
document.getElementById("submit-btn").style.backgroundColor = "#ef8028";
document.getElementById("submit-btn").style.borderStyle = "none";
document.getElementById("sub-div").style.display = "flex";
document.getElementById("sub-div").style.justifyContent = "center";



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
  document.getElementById("find-m").style.display = "flex";
  document.getElementById("find-m").style.justifyContent = "center";
  document.getElementById("find-m").style.marginTop = "2em";
  document.getElementById("find-m").style.marginBottom = "0";
  document.getElementById("join-m").style.fontSize = "xx-large";
  document.getElementById("join-m").style.display = "flex";
  document.getElementById("join-m").style.justifyContent = "center";
  document.getElementById("join-m").style.marginTop = "2em";
  document.getElementById("join-m").style.marginBottom = "0";
  document.getElementById("book-m").style.fontSize = "xx-large";
  document.getElementById("book-m").style.display = "flex";
  document.getElementById("book-m").style.justifyContent = "center";
  document.getElementById("book-m").style.marginTop = "2em";
  document.getElementById("book-m").style.marginBottom = "0";
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
</script>
</html>