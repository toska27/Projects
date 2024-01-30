<?php
    function ispisiTekst($imeFajla = "null"){
        if ($imeFajla !== "null") {
            $niz = file($imeFajla);
            $linija1 = array_rand($niz);
            if($linija1 % 2 == 0){
                $linija2 = ($linija1 + 1);
                $tekst = $niz[$linija1];
                $autor = $niz[$linija2];
                echo "<h2>$tekst</h2>";
                echo "<h4>$autor</h4>";
            } else{
                $linija2 = ($linija1 - 1);
                $tekst = $niz[$linija2];
                $autor = $niz[$linija1];
                echo "<h2>$tekst</h2>";
                echo "<h4>$autor</h4>";
            }
        } else {
            $nizTekstova = ["posao.txt", "zdravlje.txt", "ljubav.txt", "motivacija.txt"];
            $indeks = array_rand($nizTekstova);
            $jedanFajl = $nizTekstova[$indeks];
            $niz = file($jedanFajl);
            $linija1 = array_rand($niz);
            if($linija1 % 2 == 0){
                $linija2 = ($linija1 + 1);
                $tekst = $niz[$linija1];
                $autor = $niz[$linija2];
                echo "<h2>$tekst</h2>";
                echo "<h4>$autor</h4>";
            } else{
                $linija2 = ($linija1 - 1);
                $tekst = $niz[$linija2];
                $autor = $niz[$linija1];
                echo "<h2>$tekst</h2>";
                echo "<h4>$autor</h4>";
            }
        }
    };
?>