<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/db.php');

// Ensure only buyer has access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}

$buyer_id = $_SESSION['user_id'];?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Hero Section</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/buyer_dashboard.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:200,400,800,900&display=swap');

$poppins: 'Poppins', sans-serif;
$body-color: blue;

@import url("https://fonts.googleapis.com/css2?family=Spartan:wght@100;200;300;400;500;600;700;800;900&display=swap");
body {
    margin: 0;
    padding: 0;
    background: #333;
    font-family: $poppins;
    display: flex;
    justify-content: center;
  }
  @import url("https://fonts.googleapis.com/css2?family=Spartan:wght@100;200;300;400;500;600;700;800;900&display=swap");

html {
  scroll-behavior: smooth;
}

*{
  margin: 0;
  padding: 0; 
  box-sizing: border-box;
  font-family: "Spartan", san-serif;
}

  
  .hero {
    background: #133A53 url('../assets/images/hero.jpg') no-repeat center center;
    background-size: cover;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: left;
    height: 100vh;
    width: 100vw;
  
    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
  
      .content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        height: 100vh;
        width: 70vw;
        margin: auto;
        transform-origin: left;
        animation: reveal 1s ease-in-out forwards;
        position: relative;
        z-index: 4;
  
        h1 {
          font-size: 90px;
          line-height: 0.9;
          margin-bottom: 0;
          color: white;
        }
  
        p {
    font-size: 28px;
    color: #E53935;
    font-weight: bold;
    background-color:white;
    Width:max-content;
  }
  
      }
  
      &::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #ff6700;
        z-index: 3;
        animation: reveal 0.5s reverse forwards;
        animation-delay: 0.5s;
        transform-origin: left;
      }
  
      &::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #133A53;
        z-index: 2;
        animation: reveal 0.7s reverse forwards;
        animation-delay: 0.7s;
        transform-origin: left;
      }
    }
  }
  
  @keyframes reveal {
    0% {
      transform: scaleX(0);
    }
  
    100% {
      transform: scaleX(1);
    }
  }
    

h1{
  font-size: 50px;
  line-height: 64px;
  color: #222;
}

h2{
  font-size: 46px;
  line-height: 54px;
  color: #222;
}

h4{
  font-size: 20px;
  color: #222;
}

h6{
  font-size: 12px;
  font-weight: 700;
}

p{
  font-size: 16px;
  color: #465b52;
  margin: 15px 0 20px 0
}

.section-p1{
  padding: 40px 80px;
  
}


.section-m1{
  padding: 40px 0;
}

body{
 width: 100%;
 font-family: inherit; 
}



#header{
  position: fixed;
  width: 100%;
  overflow: hidden;
  z-index: 100;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 80px;
  background-color: #E3E6F3;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06)
}

#navbar{
  display: flex;
  align-items: center;
  justify-content: center;

}

.quantity{
  background-color: red;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #fff;
  position: absolute;
  top: -5px;
  left: 80%;
  padding: 3px 5px;
  font-size: 7px
  
}

#mobile{
display: none;
  align-items: center
}

#close{
  display: none
}

#navbar li{
  list-style: none; 
  padding: 0 20px;
  position: relative;
}

#navbar li a{
  text-decoration: none;
  font-size: 16px;
  font-weight: 600;
  color: #1a1a1a;
  transition: 0.3s ease
}

#navbar li a:hover,
#navbar li a.active{
  color: #088178;
}

#navbar li a:hover::after,
#navbar li a.active::after{
  content: " ";
  width: 30%;
  height: 2px;
  background: 2px;
  background-color: #088178;
  position: absolute;
  bottom: -4px;
  left: 20px;
}

#hero{
  background-image: url("https://i.postimg.cc/cCwBHzDV/hero4.png");
  height: 90vh;
  width: 100%;
  background-size: cover;
  background-position: top 25% right 0;
  padding: 0 80px;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: center;
  line-height: 1;
 
}

#hero h1{
  color: #088178;
  
}
#hero h2{
  
}

#hero h4{
  padding-bottom: 15px;
 
}

#hero button{
  background-image: url(https://i.postimg.cc/528H2mmS/button.png);
  background-color: transparent;
  color: #088178;
  border: 0;
  padding: 14px 80px 14px 65px;
}

#feature .fe-box{
  width: 180px;
  text-align: center;
  padding: 25px 15px;
  box-shadow: 20px 20px 34px rgba(0, 0, 0, 0.03);
  border: 1px solid #cce7d0;
  border-radius: 4px;
  margin: 15px 0;
}

#feature .fe-box:hover {
  box-shadow: 10px 10px 54px rgba(70, 62, 221, 0.1);
}

#feature .fe-box h6{
  display: in-block;
  padding: 9px 8px 6px 8px;
  line-height: 1;
  border-radius: 4px;
  color: #088178;
  background-color: #fddde4;
}

#feature .fe-box img{
  width: 100%;
  margin-bottom: 10px;
    
}
  
#feature{
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  
}

#feature .fe-box:nth-child(2) h6{
  background-color: #cdebbc;
}


#feature .fe-box:nth-child(3) h6{
  background-color: #d1e8f2;
}

#feature .fe-box:nth-child(4) h6{
  background-color: #cdd4f8;
}

#feature .fe-box:nth-child(5) h6{
  background-color: #f6dbf6;
}

#feature .fe-box:nth-child(6) h6{
  background-color: #fff2e5;
}

#product1{
  text-align: center;
}

#product1 .pro-container{
display: flex;
justify-content: space-between;
padding-top: 20px;
flex-wrap: wrap
}

#product1 .pro{
  width: 23%;
  min-width: 250px;
  padding: 10px 12px;
  border: 1px solid #cce7d0;
  border-radius: 25px;
  cursor: pointer;
  box-shadow: 20px 20px 30px rgba(0, 0, 0, 0.02);
  margin: 15px 0;
  transition: 0.2s ease;
  position: relative;
}

#product .pro:hover{
   box-shadow: 20px 20px 30px rgba(0, 0, 0, 0.06);
}

#product1 .pro img{
  width: 100%;
  border-radius: 20px;
}

#product1 .pro .des{
  text-align: start;
  padding: 10px 0;
}

#product1 .pro .des span{
  color: #606063;
  font-size: 12px;
}

#product1 .pro .des h5{
  padding-top: 7px;
  color: #1a1a1a;
  font-size: 14px;
}

#product1 .pro .des i{
  font-size: 12px;
  color: rgb(243, 181, 25)
}

#product1 .pro .des h4{
  font-size: 15px;
  padding-top: 7px;
  font-weight: 700;
  color: #088178;
}

#product1 .pro .cart{
  width: 40px;
  height: 40px;
  line-height: 40px;
  border-radius: 50px;
  background-color: #e8f6ea;
  font-weight: 500;
  color: #088178;
  border: 1px solid #cce7d0;
  position: absolute;
  bottom: 20px;
  right: 10px;
  
}

#banner{
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  background-image: url(https://i.postimg.cc/SsC7D5WD/b2.jpg);
  width: 100%;
  height: 40vh;
  background-position: center;
  background-size: cover;
  
}

#banner h4{
  color: #fff;
  font-size: 16px;
}

#banner h2{
  color: #fff;
  font-size: 30px;
  padding: 10px 0;
}

#banner h2 span{
  color: #ef3636;
  
}

button.normal{
  color: #000;
  padding: 15px 30px;
  font-weight: 400;
  font-size: 14px;
  border-radius: 4px;
  background-color: #fff;
  border: none;
  outline: none;
  transition: 0.2s;
  cursor: pointer
    
}

#banner button:hover{
  background-color: #088178;
  color: #fff;
}

#sm-banner .banner-box{
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
  text-align: center;
  background-image: url(https://i.postimg.cc/vZ6YLxDG/b17.jpg);
  min-width: 580px;
  height: 40vh;
  background-position: center;
  background-size: cover;
  padding: 30px
}

#sm-banner .banner-box2{
  background-image: url(https://i.postimg.cc/gJ7FHxHv/b10.jpg)
}

#sm-banner{
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
}

#sm-banner h4{
  color: #fff;
  font-size: 20px;
  font-weight: 300;
}

#sm-banner h2{
  color: #fff;
  font-size: 32px;
  font-weight: 800;
}

#sm-banner span{
  color: #0e0e0e;
  font-size: 16px;
  font-weight: 500;
  padding-bottom: 16px;
}

button.white{
  color: #000;
  padding: 15px 30px;
  font-weight: 400;
  font-size: 14px;
  border-radius: 4px;
  background-color: transparent;
  border: 1px solid #fff;
  outline: none;
  transition: 0.2s;
  cursor: pointer
}

#sm-banner .banner-box:hover button{
  background-color: #088178;
  color: #fff;
  border: 1px solid #088178;
    
}

#banner3{
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  padding: 0 80px;
}

#banner3 .banner-box{
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
  text-align: center;
  background-image: url(https://i.postimg.cc/BQQHKtwh/b7.jpg);
  min-width: 30%;
  height: 30vh;
  background-position: center;
  background-size: cover;
  padding: 30px;
  margin-bottom: 20px
}

#banner3 h2{
  color: #fff;
  font-weight: 900;
  font-size: 22px;
}

#banner3 h3{
  color: #ec544e;
  font-weight: 800;
  font-size: 15px;
}

#banner3 .banner-img2{
  background-image: url(https://i.postimg.cc/SxP6qqdg/b4.jpg)
}

#banner3 .banner-img3{
   background-image: url(https://i.postimg.cc/m2th49nG/b18.jpg)
}

#newsletter  {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  background-image: url(https://i.postimg.cc/R0Bs4qqt/b14.png);
    background-repeat: no-repeat;
    background-position: 20% 30%;
  background-color: #041e42;
}

#newsletter h4{
  color: #fff;
  font-weight: 700;
  font-size: 22px;
}

#newsletter p{
  color: #818ea0;
  font-weight: 600;
  font-size: 14px;
}

#newsletter p span{
  color: #ffbd27;
  }

#newsletter input{
  height: 3.125rem;
  width: 100%;
  font-size: 14px;
  padding: 0 1.25em;
  border: 1px solid transparent;
  border-radius: 4px;
  outline: none;
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}

#newsletter button{
  background-color: #088178;
  color: #fff;
  white-space: nowrap;
  border-left-right-radius: 0;
  border-left-right-radius: 0; 
}

#newsletter .form{
  display: flex;
  width: 40%;
  
}

footer {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  position: relative;
}


footer .col{
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  margin-bottom: 20px;
  margin-left: 50px

}

footer .sec{
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;

  
}

footer .logo{
  margin-bottom: 20px;
}

footer h4{
  font-size: 14px;
  padding-bottom: 20px
}

footer p{
  font-size: 13px;
  margin: 0 0 8px 0;
}


footer a{
  font-size: 13px;
  text-decoration: none;
  color: #222;
  margin-bottom: 10px;
}

footer .follow{
  margin-top: 20px
}

footer .follow i{
  color: #465b52;
  padding-right: 5px;
  cursor: pointer;
  
}

footer .follow i:hover, footer a:hover {
  color: #088178;
 
}

footer .install .row img{
  border: 1px solid #088178; 
  border-radius: 6px;
    
}

footer .install img{
  margin: 10px 0 15px 0
}


footer .copyright{
  width: 100%;
  text-align: center
   
}



/*----------------------Media Query ----------*/


@media (max-width: 920px) {
  
  .section-p1 {
    padding: 40px 40px  
  }
  
  #navbar{
  display: flex;
  flex-direction: column;   
  align-items: flex-start;
  justify-content: flex-start;
  position: fixed;
  top: 0;
  right: -300px;
  height: 100vh;
  width: 300px;
  background-color: #E3E6F3;
  box-shadow: 0 40px 60px rgba(0, 0, 0, 0.1);
  padding: 80px 0 0 10px;
    transition: 0.3s
    }
  
  #navbar.active{
  right: 0;
}

  #navbar li{
    margin-bottom: 25px
  }
  
  #mobile{
display: flex;
  align-items: center
}
  #mobile i{
    font-size: 32px;
    color: #1a1a1a;
    padding-left: 20px
  }
  body #lg-bag{
    display: none
  }
  
  #close{
  display: initial;
  position: absolute;
  top: -280px;
  left: 20px;
  color: #222;
  font-size: 32px;  
}
  
  #lg-bag{
    display: none
  }
  
  .quantity{
    top: 15px;
  left: 83%;
  }
   
  #hero{
  height: 70vh;
  padding: 0 80px;
  background-position: top 30% right 30%
 }

  #feature {
  justify-content: center;
    
}
  
  #feature .fe-box {
    margin: 15px 15px
  }
  
  #product1 .pro-container{
    justify-content: center
  }
  
  #product1 .pro{
    margin: 15px;
  }
  
  #banner{
    height: 25vh
  }
  
  #sm-banner .banner-box{
    min-width: 100%;
    height: 30vh;
  }
  
  #banner3{
    padding: 0 40px
  }
  
  #banner3 .banner-box{
    width: 28%
  }
  
  #newsletter .form {
    width: 70%
  }
  
}

@media (max-width: 477px) {
  .section-p1{
    padding: 20px
  }
  
  #header{
    padding: 10px 30px;
  }
  
  .quantity{
    top: 7px;
  left: 80%;
  }
  
  #hero{
    padding: 0 20px;
    background-position: 55%;
  }
  
  h2 {
    font-size: 30px
  }
  
  h1{
    font-size: 28px
  }
  
  p{
    line-height: 24px;
    font-size: 10px;
  }
  
  #hero button{
    margin-right: 10px
  }
  
  #feature{
    justify-content: space-between;
  }
  
  #feature .fe-box{
    width: 155px;
    margin: 0 0 15px 0;
  }
  
  #product1 .pro{
    width: 100%
  }
  
  #banner{
    height: 40vh;
  }
  
  #sm-banner .banner-box{
    height: 40vh;
}
  
  #sm-banner .banner-box2 {
    margin-top: 20px;
  }
  
  #banner3{
    padding: 0 20px;
  }
  
  #banner3 .banner-box{
    width: 100%;
  }
  
  #newsletter .form{
    width:  100%
  }
  
  #newsletter{
    padding: 40px 20px;
  }
  
  footer .copyright{
    text-align: start;
  }
}
    </style>
</head>
<body>
    <?php
    // Add PHP logic here if needed
    ?>

<section class="hero">
  <div class="overlay">
      <div class="content">
        <h1>Welcome<br>Back</h1>
        <p>Find Joy in Every Purchase!!</p>
      </div>
  </div>
</section>
<section id="feature" class="section-p1">
  <div class="fe-box">
    <img src="https://i.postimg.cc/PrN2Y6Cv/f1.png" alt="">
    <h6>Free Shipping</h6>
  </div>
  
  <div class="fe-box">
    <img src="https://i.postimg.cc/qvycxW4q/f2.png" alt="">
    <h6>Online Order</h6>
  </div>
  
  <div class="fe-box">
    <img src="https://i.postimg.cc/1Rdphyz4/f3.png" alt="">
    <h6>Save Money</h6>
  </div>
  
  <div class="fe-box">
    <img src="https://i.postimg.cc/GpYc2JFZ/f4.png" alt="">
    <h6>Promotions</h6>
  </div>
  
  <div class="fe-box">
    <img src="https://i.postimg.cc/4yFCwmv6/f5.png" alt="">
    <h6>Happy Sell</h6>
  </div>
  
  <div class="fe-box">
    <img src="https://i.postimg.cc/gJN1knTC/f6.png" alt="">
    <h6>F24/7 Support</h6>
  </div>
  
</section>

<section id="product1" class="section-p1">
  <h2>Featured Products</h2>
  <p>Summer Collection New Modern Design</p>
  <div class="pro-container">
    <div class="pro" onclick="window.location.href='https://codepen.io/Motun/full/OJBwbrQ'">
      <img src="https://i.postimg.cc/kg9YYbTn/f1.jpg" alt="">
      <div class="des">
        <span>adidas</span>
        <h5>Carton Astronault Tshirts</h5>
        <div class="star">
          <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
        </div>
        <h4>$78</h4>
      </div>
      <a href=""><i class="fal fa-shopping-cart cart"></i></a>
    </div>
    
    
    <div class="pro">
      <img src="https://i.postimg.cc/2yhT2kvb/f2.jpg" alt="">
      <div class="des">
        <span>adidas</span>
        <h5>Carton Leave Tshirts</h5>
        <div class="star">
          <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
        </div>
        <h4>$78</h4>
      </div>
      <a href=""><i class="fal fa-shopping-cart cart"></i></a>
    </div>
    
    
    <div class="pro">
      <img src="https://i.postimg.cc/VL9DtNm2/f3.jpg" alt="">
      <div class="des">
        <span>adidas</span>
        <h5>Rose Multicolor Tshirts</h5>
        <div class="star">
          <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
        </div>
        <h4>$78</h4>
      </div>
      <a href=""><i class="fal fa-shopping-cart cart"></i></a>
    </div>
    
    
    <div class="pro">
      <img src="https://i.postimg.cc/vZ3hPS1z/f4.jpg" alt="">
      <div class="des">
        <span>adidas</span>
        <h5>Pink Flower Tshirts</h5>
        <div class="star">
          <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
        </div>
        <h4>$78</h4>
      </div>
      <a href=""><i class="fal fa-shopping-cart cart"></i></a>
    </div>
    
    
    
    <div class="pro">
      <img src="https://i.postimg.cc/q7FLrhx6/f5.jpg" alt="">
      <div class="des">
        <span>adidas</span>
        <h5>Purple Flowering Tshirts</h5>
        <div class="star">
          <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
        </div>
        <h4>$78</h4>
      </div>
      <a href=""><i class="fal fa-shopping-cart cart"></i></a>
    </div>
    
    
    
    <div class="pro">
      <img src="https://i.postimg.cc/L86BZByZ/f7.jpg" alt="">
      <div class="des">
        <span>adidas</span>
        <h5>Short Knicker </h5>
        <div class="star">
          <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
        </div>
        <h4>$78</h4>
      </div>
      <a href=""><i class="fal fa-shopping-cart cart"></i></a>
    </div>
    
    
    
    <div class="pro">
      <img src="https://i.postimg.cc/zDxJ2f0H/f6.jpg" alt="">
      <div class="des">
        <span>adidas</span>
        <h5>2 in 1 Double Routed</h5>
        <div class="star">
          <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
        </div>
        <h4>$78</h4>
      </div>
      <a href=""><i class="fal fa-shopping-cart cart"></i></a>
    </div>
    
    
    
    <div class="pro">
      <img src="https://i.postimg.cc/x8qcBrpP/n6.jpg" alt="">
      <div class="des">
        <span>adidas</span>
        <h5>Ash Short</h5>
        <div class="star">
          <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
        </div>
        <h4>$78</h4>
      </div>
      <a href=""><i class="fal fa-shopping-cart cart"></i></a>
    </div>
    
  </div>
</section>

<section id="banner" class="section-m1">
  <h4> Repair Service</h4>
  <h2>Up to <span>70% off </span> - All Tshirts and Accessories</h2>
  <button class="btn normal">Explore more</button>
  </section>

<section id="product1" class="section-p1">
  <h2>New Arrivals</h2>
  <p>Summer Collection New Modern Design</p>
  <div class="pro-container">
    <div class="pro">
      <img src="https://i.postimg.cc/hG1hqqK6/n1.jpg" alt="">
      <div class="des">
        <span>adidas</span>
        <h5>Carton Astronault Tshirts</h5>
        <div class="star">
          <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
        </div>
        <h4>$78</h4>
      </div>
      <a href=""><i class="fal fa-shopping-cart cart"></i></a>
    </div>
    
    
    <div class="pro">
      <img src="https://i.postimg.cc/BZkSkvxt/n2.jpg" alt="">
      <div class="des">
        <span>adidas</span>
        <h5>Carton Leave Tshirts</h5>
        <div class="star">
          <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
        </div>
        <h4>$78</h4>
      </div>
      <a href=""><i class="fal fa-shopping-cart cart"></i></a>
    </div>
    
    
    <div class="pro">
      <img src="https://i.postimg.cc/KYjcC3sk/n3.jpg" alt="">
      <div class="des">
        <span>adidas</span>
        <h5>Rose Multicolor Tshirts</h5>
        <div class="star">
          <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
        </div>
        <h4>$78</h4>
      </div>
      <a href=""><i class="fal fa-shopping-cart cart"></i></a>
    </div>
    
    
    <div class="pro">
      <img src="https://i.postimg.cc/vHvQBtJx/n4.jpg" alt="">
      <div class="des">
        <span>adidas</span>
        <h5>Pink Flower Tshirts</h5>
        <div class="star">
          <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
        </div>
        <h4>$78</h4>
      </div>
      <a href=""><i class="fal fa-shopping-cart cart"></i></a>
    </div>
    
    
    
    <div class="pro">
      <img src="https://i.postimg.cc/908J8S4q/n5.jpg" alt="">
      <div class="des">
        <span>adidas</span>
        <h5>Purple Flowering Tshirts</h5>
        <div class="star">
          <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
        </div>
        <h4>$78</h4>
      </div>
      <a href=""><i class="fal fa-shopping-cart cart"></i></a>
    </div>
    
    
    
    <div class="pro">
      <img src="https://i.postimg.cc/X7r8NsGQ/n7.jpg" alt="">
      <div class="des">
        <span>adidas</span>
        <h5>Short Knicker </h5>
        <div class="star">
          <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
        </div>
        <h4>$78</h4>
      </div>
      <a href=""><i class="fal fa-shopping-cart cart"></i></a>
    </div>
    
    
    
    <div class="pro">
      <img src="https://i.postimg.cc/JhrH0hYM/n8.jpg" alt="">
      <div class="des">
        <span>adidas</span>
        <h5>2 in 1 Double Routed</h5>
        <div class="star">
          <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
        </div>
        <h4>$78</h4>
      </div>
      <a href=""><i class="fal fa-shopping-cart cart"></i></a>
    </div>
    
    
    
    <div class="pro">
      <img src="https://i.postimg.cc/2Sq4mytJ/f8.jpg" alt="">
      <div class="des">
        <span>adidas</span>
        <h5>Ash Short</h5>
        <div class="star">
          <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
           <i class="fas fa-star"></i>
        </div>
        <h4>$78</h4>
      </div>
      <a href=""><i class="fal fa-shopping-cart cart"></i></a>
    </div>
    
  </div>
</section>

<section id="sm-banner" class="section-p1"> 
  <div class="banner-box">
    <h4>crazy deals</h4>
    <h2>buy 1 get 1 free</h2>
    <span>The best classic dress is on sales at cara</span>
    <button class="btn white">Learn More</button> 
  
  </div>
  
  <div class="banner-box banner-box2">
    <h4>spring/summer</h4>
    <h2>upcoming season</h2>
    <span>The best classic dress is on sales at cara</span>
    <button class="btn white">Collection</button> 
  
  </div>
  
</section>

<section id="banner3" class="section-p1">
  <div class="banner-box">
    
    <h2>SEASONAL SALES</h2>
    <h3>Winter Collection -50% OFF</h3>
 
  </div>
  
  <div class="banner-box banner-img2">
    
    <h2>SEASONAL SALES</h2>
    <h3>Winter Collection -50% OFF</h3>
 
  </div>
  
  <div class="banner-box banner-img3">
    
    <h2>SEASONAL SALES</h2>
    <h3>Winter Collection -50% OFF</h3>
 
  </div>
  
</section>

<section id="newsletter" class="section-p1">
  <div class="newstext">
    <h4>Sign Up for Newsletters</h4>
    <p>Get Email updates about our latest shop and <span> special offers.</span> </p>
  </div>
    <div class="form">
       <button class="btn normal">Sign Up</button>
    </div>
   
  </div>
  
</section>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>



<?php require_once('../includes/footer.php'); ?>