<?php

require_once 'vendor/autoload.php';

use XmlProcessor\TripsXml;
use XmlProcessor\ProgramsXml;
use XmlProcessor\DescriptorsXml;

$descriptorsXml = new DescriptorsXml('data/descriptors.xml');
$tripsXml = new TripsXml('data/trips.xml', $descriptorsXml);  
$programsXml = new ProgramsXml('data/programs.xml', $descriptorsXml, $tripsXml);

if (isset($_GET['id'])) {
    $programId = $_GET['id'];
    $currentProgram = $programsXml->getProgramById($programId); 
} else {
    // Handle the case when ID parameter is not set or invalid
    // For example, you can redirect to an error page or display a message
    header("Location: index.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dive Hard Tours Tesztfeladat</title>
        <!-- Include Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>

    <body>
        <header>
        <div class="container">
    <div class="row">
        <div class="col">
            <h1 class="display-4"><?php echo $currentProgram['name']; ?></h1>
        </div>
        <div class="col text-right">
            <a href="index.php" class="btn btn-primary mt-3">Vissza a főoldalra</a>
        </div>
    </div>
</div>

            <hr>
        </header>
        <main class="container">
            <div class="row">
                <div class="col col-12 col-md-4">
                    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">                
                        <div class="carousel-inner">
                            <?php foreach($currentProgram['images'] as $k => $image) : ?>
                                <div class="carousel-item <?php if($k == 0) {echo "active";} ?>">
                                    <img class="d-block w-100" src="<?php echo $image; ?>" alt="">
                                </div>    
                            <?php endforeach; ?>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>                
                </div>
                <div class="col col-12 col-md-8">
                    <p><?php echo $currentProgram['locations']; ?></p>
                    <h2 class="display-6">Leírás</h2>
                    <?php echo $currentProgram['description']; ?>

                    <p class="mb-1">Szobák:</p>
                        <?php foreach ($currentProgram['available_rooms'] as $room) : ?>
                            <span class="badge badge-info"><?php echo $room['name'] ?> (<?php echo $room['num_people'] ?> fő)</span>        
                        <?php endforeach; ?>
                    <p class="mb-1">Jellemzők:</p>
                    <?php foreach ($currentProgram['tags'] as $tag) : ?>
                        <span class="badge badge-secondary"><?php echo $tag ?></span>
                    <?php endforeach; ?>  
                    
                </div>
            </div>   
            <div class="row">
                <div class="col"><p class="mb-1">Aktuális ajánlatok:</p></div>
            </div>         
            <div class="row">
                <?php foreach ($currentProgram['trips'] as $trip) : ?>
                    <div class="col-12 col-md-4 col-lg-3">
                    <div class="card mb-2">
                        <div class="card-body">
                            <?php echo $trip['df']; ?> - <?php echo $trip['dt']; ?> <br>
                            <small><?php echo $trip['na'] ?? 0; ?> felnőtt | <?php echo $trip['nc'] ?? 0; ?> gyerek | <?php echo $trip['nb'] ?? 0; ?> csecsemő</small> <br>
                        
                        <?php echo number_format($trip['prices'][0]['value'],0,'.',' ') . ' ' . $trip['prices'][0]['currency']; ?>
                        <?php echo $tripsXml->displayDiscount($trip['d']); ?> <br>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>





        <!-- Include Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>

</html>
