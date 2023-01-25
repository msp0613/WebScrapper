<?php
    require_once('simple_html_dom.php');//dołączamy biblioeke php do tego projektu...
    //biblioteka sluzy do przeszukiwania kodu html pod katem wyszukiwanych wartosci

    require_once('ScrapperItem.php');//dołaczamy klasa na podstwie ktorej tworzymy obiekty poszczegolnych produktow 

    require_once('euroScrapper.php');//pliki dołączamy za pomoca ktorego pobieramy dane z euro agd
    require_once('mediaExpertScrapper.php');//...z media expert

    if(count($_GET) > 0){ //sprawdzamy czy ktos uzyl wyszukiwarki
        $mediaExpertURL = generateURLForMediaExpertScrapping($_GET); // jezeli tak generujemy adres url media expert do pobrania produktow z tego sklepu,jako parametr przekazujemy wszystkie wartosci z wyszukiwarki

        if($mediaExpertURL !== false){
            $mediaExpertProducts = getMediaExpertHTMLContent($mediaExpertURL); //jesli adrs media epert jest rozny od false
            //bedzie rowny false tylko wtedy gdy uzytkownik nie wybierze sklepu z wyszukiwarki 
        }
        else{
            $mediaExpertProducts = [];
            //nie bedzie zadnych produktow nie bedzie niczego
        }

        $euroURL = generateURLForEuroScrapping($_GET); //ta sama historia 

        if($euroURL !== false){
            $euroProducts = getEuroHTMLContent($euroURL);
        }
        else{
            $euroProducts = [];
        }

    }
    else{
        $euroProducts = getEuroHTMLContent(); //pobieramy domyslne produkty z euro
        $mediaExpertProducts = getMediaExpertHTMLContent(); //pobieramy z media
        // tylko wtedy gdy nic nie wybral nie wybral zadnego sklepu
    }
    


    $products = array_merge($euroProducts, $mediaExpertProducts);   //laczymy w jedna tablice wszystkie produkty z tablic z euro i media
    shuffle($products); // miwszzamy tablice zeby wyswietamy sie w lososowej kolejnosci
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laptopy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Wyszukiwanie laptopów</a>
    </div>
  </div>
</nav>
<div class="container mt-5">
    <form>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="mb-2">Sklep</label>
                    <select multiple class="form-control" name="shop[]">
                        <option value="euro" <?php if(isset($_GET['shop']) && in_array('euro', $_GET['shop'])): ?> selected <?php endif; ?>>Euro RTV AGD</option>
                        <option value="media" <?php if(isset($_GET['shop']) && in_array('media', $_GET['shop'])): ?> selected <?php endif; ?>>Media Expert</option>
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="mb-2">Producent</label>
                    <select class="form-control" name="producent">
                        <option disabled selected>Wybierz</option>
                        <option <?php if(isset($_GET['producent']) && $_GET['producent'] === 'hp'): ?> selected <?php endif; ?> value="hp">HP</option>
                        <option <?php if(isset($_GET['producent']) && $_GET['producent'] === 'lenovo'): ?> selected <?php endif; ?> value="lenovo">Lenovo</option>
                        <option <?php if(isset($_GET['producent']) && $_GET['producent'] === 'acer'): ?> selected <?php endif; ?> value="acer">Acer</option>
                        <option <?php if(isset($_GET['producent']) && $_GET['producent'] === 'dell'): ?> selected <?php endif; ?> value="dell">Dell</option>
                        <option <?php if(isset($_GET['producent']) && $_GET['producent'] === 'Apple'): ?> selected <?php endif; ?> value="Apple">Apple</option>
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="mb-2">Przekątna ekranu</label>
                    <select class="form-control" name="screen">
                        <option disabled selected>Wybierz</option>
                        <option <?php if(isset($_GET['screen']) && $_GET['screen'] === '10'): ?> selected <?php endif; ?> value="10">10 cali</option>
                        <option <?php if(isset($_GET['screen']) && $_GET['screen'] === '11'): ?> selected <?php endif; ?> value="11">11 cali</option>
                        <option <?php if(isset($_GET['screen']) && $_GET['screen'] === '12'): ?> selected <?php endif; ?> value="12">12 cali</option>
                        <option <?php if(isset($_GET['screen']) && $_GET['screen'] === '13'): ?> selected <?php endif; ?> value="13">13 cali</option>
                        <option <?php if(isset($_GET['screen']) && $_GET['screen'] === '14'): ?> selected <?php endif; ?> value="14">14 cali</option>
                        <option <?php if(isset($_GET['screen']) && $_GET['screen'] === '15'): ?> selected <?php endif; ?> value="15">15 cali</option>
                        <option <?php if(isset($_GET['screen']) && $_GET['screen'] === '16'): ?> selected <?php endif; ?> value="16">16 cali</option>
                        <option <?php if(isset($_GET['screen']) && $_GET['screen'] === '17'): ?> selected <?php endif; ?> value="17">17 cali</option>
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="mb-2">Pamięć RAM</label>
                    <select class="form-control" name="ram">
                        <option disabled selected>Wybierz</option>
                        <option <?php if(isset($_GET['ram']) && $_GET['ram'] === '2'): ?> selected <?php endif; ?> value="2">2 GB</option>
                        <option <?php if(isset($_GET['ram']) && $_GET['ram'] === '4'): ?> selected <?php endif; ?> value="4">4 GB</option>
                        <option <?php if(isset($_GET['ram']) && $_GET['ram'] === '8'): ?> selected <?php endif; ?> value="8">8 GB</option>
                        <option <?php if(isset($_GET['ram']) && $_GET['ram'] === '16'): ?> selected <?php endif; ?> value="16">16 GB</option>
                        <option <?php if(isset($_GET['ram']) && $_GET['ram'] === '32'): ?> selected <?php endif; ?> value="32">32 GB</option>
                    </select>
                </div>
            </div>
            <div class="col-3 mt-3">
                <div class="form-group">
                    <label class="mb-2">Pojemność dysku</label>
                    <select class="form-control" name="disc">
                        <option disabled selected>Wybierz</option>
                        <option <?php if(isset($_GET['disc']) && $_GET['disc'] === '2'): ?> selected <?php endif; ?> value="2">2 TB</option>
                        <option <?php if(isset($_GET['disc']) && $_GET['disc'] === '1'): ?> selected <?php endif; ?> value="1">1 TB</option>
                        <option <?php if(isset($_GET['disc']) && $_GET['disc'] === '512'): ?> selected <?php endif; ?> value="512">512 GB</option>
                        <option <?php if(isset($_GET['disc']) && $_GET['disc'] === '256'): ?> selected <?php endif; ?> value="256">256 GB</option>
                        <option <?php if(isset($_GET['disc']) && $_GET['disc'] === '128'): ?> selected <?php endif; ?> value="128">128 GB</option>
                        <option <?php if(isset($_GET['disc']) && $_GET['disc'] === '64'): ?> selected <?php endif; ?> value="64">64 GB</option>

                    </select>
                </div>
            </div>
            <div class="col-3 mt-3">
                <div class="form-group">
                    <label class="mb-2">Rodzaj matrycy</label>
                    <select class="form-control" name="matrix">
                        <option disabled selected>Wybierz</option>
                        <option <?php if(isset($_GET['matrix']) && $_GET['matrix'] === 'blyszczaca'): ?> selected <?php endif; ?> value="blyszczaca">Błyszcząca</option>
                        <option <?php if(isset($_GET['matrix']) && $_GET['matrix'] === 'matowa'): ?> selected <?php endif; ?> value="matowa">Matowa</option>

                    </select>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-2">
                <button class="btn btn-primary mt-3 mb-5" type="submit">Szukaj</button>
            </div>
        </div>
    </form>
    <div class="row">
        <?php foreach($products as $product): ?>
            <div class="col-4 mb-3">
                <div class="card">
                    <img src="<?php echo $product->image; ?>" class="card-img-top" style="height: 200px; object-fit:cover;">
                    <div class="card-body">
                        <img style="width: 100px; margin: 0 auto; display: block; margin-bottom: 20px;" src="<?php echo $product->shop; ?>" />
                        <h5 class="card-title"><?php echo $product->title; ?></h5>
                        <p class="card-text">
                            <ul>
                                <?php foreach($product->description as $item): ?>
                                    <li><?php echo $item; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </p>
                        <b style="text-align: center; font-size: 24px; display: block; margin-bottom: 20px;"><?php echo $product->price; ?></b>
                        <div style="text-align: center">
                            <a href="<?php echo $product->shop_url; ?>" class="btn btn-primary">Przejdź do sklepu</a>
                        </div>
                    </div>
                </div>  
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>