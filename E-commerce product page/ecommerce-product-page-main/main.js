
/* Change photo */

function changePhoto(id){
    let smallPhoto = document.getElementById(id).getAttribute('src');
    
    if(smallPhoto.indexOf(1)>0){
        document.getElementById('bigPhoto').setAttribute('src', 'images/image-product-1.jpg');
    } else if(smallPhoto.indexOf(2)>0){
        document.getElementById('bigPhoto').setAttribute('src', 'images/image-product-2.jpg');
    } else if(smallPhoto.indexOf(3)>0){
        document.getElementById('bigPhoto').setAttribute('src', 'images/image-product-3.jpg');
    } else if(smallPhoto.indexOf(4)>0){
        document.getElementById('bigPhoto').setAttribute('src', 'images/image-product-4.jpg');
    } 
    
}

/* Buttons of counter */

function counterPlus(){
    let numberCounter = document.getElementById('numberCounter');  
    let counter = parseInt(numberCounter.textContent);
    
    counter += 1;
    numberCounter.textContent = counter;
}

function counterMinus(){
    let numberCounter = document.getElementById('numberCounter');  
    let counter = parseInt(numberCounter.textContent);
    
    if(counter<=0){
        numberCounter.textContent = 0;
    } else{
        counter -= 1;
        numberCounter.textContent = counter;
    }
}

function cartBtn(){
    let numberCounter = document.getElementById('numberCounter');
    let cartNum = document.getElementById('cartNum');

    cartNum.textContent = numberCounter.textContent;

    numberCounter.textContent = 0;
}


/* Size of screen*/


window.addEventListener("resize", () => {
    const width = window.innerWidth;
   
    if(width <= 699){
        document.getElementById('photo').innerHTML = carousel();
        document.getElementById('menu').innerHTML = hamburgerMenu();
    } else{
        document.getElementById('photo').innerHTML = `<img src="images/image-product-1.jpg" alt="image1" id="bigPhoto">
        <div class="smallImage row">
          <img src="images/image-product-1-thumbnail.jpg" alt="image-t-1" class="col-2-4 col-t-2-4" id="smallPhoto1" onclick="changePhoto('smallPhoto1')">
          <img src="images/image-product-2-thumbnail.jpg" alt="image-t-2" class="col-2-4 col-t-2-4" id="smallPhoto2" onclick="changePhoto('smallPhoto2')">
          <img src="images/image-product-3-thumbnail.jpg" alt="image-t-3" class="col-2-4 col-t-2-4" id="smallPhoto3" onclick="changePhoto('smallPhoto3')">
          <img src="images/image-product-4-thumbnail.jpg" alt="image-t-4" class="col-2-4 col-t-2-4" id="smallPhoto4" onclick="changePhoto('smallPhoto4')">
        </div>`;
        document.getElementById('menu').innerHTML = `<div class="row navigator">
        <a href="#" class="col-2 col-t-2">Collections</a>
        <a href="#" class="col-2 col-t-2">Men</a>
        <a href="#" class="col-2 col-t-2">Women</a>
        <a href="#" class="col-2 col-t-2">About</a>
        <a href="#" class="col-2 col-t-2">Contact</a>
      </div>`;
    }
});


/* Carousel */

function carousel(){
    let carousel = `
    <section class="carousel" aria-label="Gallery">
    <ol class="carousel__viewport">
    <li id="carousel__slide1"
        tabindex="0"
        class="carousel__slide">
        <div class="carousel__snapper">
        <a href="#carousel__slide4"
           class="carousel__prev"></a>
        <a href="#carousel__slide2"
           class="carousel__next"></a>
      </div>
    </li>
    <li id="carousel__slide2"
        tabindex="0"
        class="carousel__slide">
      <div class="carousel__snapper"></div>
      <a href="#carousel__slide1"
         class="carousel__prev"></a>
      <a href="#carousel__slide3"
         class="carousel__next"></a>
    </li>
    <li id="carousel__slide3"
        tabindex="0"
        class="carousel__slide">
      <div class="carousel__snapper"></div>
      <a href="#carousel__slide2"
         class="carousel__prev"></a>
      <a href="#carousel__slide4"
         class="carousel__next"></a>
    </li>
    <li id="carousel__slide4"
        tabindex="0"
        class="carousel__slide">
      <div class="carousel__snapper"></div>
      <a href="#carousel__slide3"
         class="carousel__prev"></a>
      <a href="#carousel__slide1"
         class="carousel__next"></a>
    </li>
  </ol>
  </section>
  `;

  return carousel;
}


/* Hamburger */

function hamburgerMenu(){
    let hamburger = `<div class="container nav-container">
    <input class="checkbox" type="checkbox" name="" id="" />
    <div class="hamburger-lines">
      <span class="line line1"></span>
      <span class="line line2"></span>
      <span class="line line3"></span>
    </div>  
  <div class="menu-items">
    <li><a href="#">Collections</a></li>
    <li><a href="#">Men</a></li>
    <li><a href="#">Women</a></li>
    <li><a href="#">About</a></li>
    <li><a href="#">Contact</a></li>
  </div>
</div>`;
    return hamburger;
}






/* Modal */

function modal(){
    let numberCounter = document.getElementById('cartNum').textContent;
    let nowPrice = document.getElementById('nowPrice').textContent;
    let price = nowPrice.substr(1);
    let fullPrice = parseInt(numberCounter) * parseInt(price);
    let roundedFullPrice = fullPrice.toFixed(2);
    let modal = `<div id="openModal" class="modalbg">
    <div class="dialog">
        <a href="#close" title="Close" class="close">X</a>
        <h2>Cart</h2>
        <hr>
        <img src="images/image-product-1-thumbnail.jpg" class="imgModal">
        <p class="describeOfItem">Fall Limited Edition Sneakers</p>
        <p class="priceOfItem">$125.00 x ${numberCounter} <strong>$${roundedFullPrice}</strong> </p>
    </div>
  </div>`;

  return modal;
}

function modalNonPrice(){
    let modal = `<div id="openModal" class="modalbg">
    <div class="dialog">
        <a href="#close" title="Close" class="close">X</a>
        <h2>Cart</h2>
        <hr>
        <p class="emptyCart">Your cart is empty</p>
    </div>
  </div>`;

  return modal;
}

let cart = document.getElementById('cart');
let modalDiv = document.getElementById('modal');

cart.addEventListener('click', function(){
    let counter = document.getElementById('cartNum').textContent;
    if(parseInt(counter)>0){
        modalDiv.innerHTML = modal();
    } else{
        modalDiv.innerHTML = modalNonPrice();
    }
});
