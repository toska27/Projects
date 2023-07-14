<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projekat citati</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div id="carouselExample" class="carousel slide">
            <div class="carousel-inner"> 
                <?php
                    $slike = ["images/1.jpg", "images/2.jpg", "images/3.jpg", "images/4.jpg", "images/5.jpg", "images/6.jpg", "images/7.jpg", "images/8.jpg", "images/9.jpg", "images/10.jpg", "images/11.jpg", "images/12.jpg"];
                    shuffle($slike);
                    $rand_slike = array_slice($slike, 0, 3);
                    foreach ($rand_slike as $k => $v){
                        if($k == 0){
                            echo "<div class='carousel-item active'>";
                        } else{
                            echo "<div class='carousel-item'>";
                        }
                        echo "<img src='".$v."' class='d-block w-100'>";
                        echo "</div>";
                    }
                ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </header>
    
    <nav class="navbar">
        <ul class="row">
            <li class="col-3">
                
                <a href="index.php?file=posao.txt">Posao <i class="fa-solid fa-person-digging"></i></a>
            </li>
            <li class="col-3">
                <a href="index.php?file=zdravlje.txt">Zdravlje <i class="fa-solid fa-notes-medical"></i></a>
            </li>
            <li class="col-3">
                <a href="index.php?file=ljubav.txt">Ljubav <i class="fa-solid fa-heart"></i></a>
            </li>
            <li class="col-3">
                <a href="index.php?file=motivacija.txt">Motivacija <i class="fa-solid fa-person-arrow-up-from-line"></i></a>
            </li>
        </ul>  
    </nav>
    
    <section class="citat">
        <?php
            require_once("test.php");
            if (empty($_GET)){
                ispisiTekst();
            } else {
                $link = $_GET["file"];
                if ($link == "posao.txt") {
                    ispisiTekst("posao.txt");
                }
                elseif ($link == "ljubav.txt") {
                    ispisiTekst("ljubav.txt");
                }
                elseif ($link == "motivacija.txt") {
                    ispisiTekst("motivacija.txt");
                }
                elseif ($link == "zdravlje.txt") {
                    ispisiTekst("zdravlje.txt");
                }
            }     
        ?>
    </section>
    
    <footer>
        <?php
            echo "<p>".date("r")."</p>";
        ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>