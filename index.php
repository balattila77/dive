<?php

require_once 'vendor/autoload.php';

use XmlProcessor\DescriptorsXml;
use XmlProcessor\ProgramsXml;

$descriptorsXml = new DescriptorsXml('data/descriptors.xml');
$programsXml = new ProgramsXml('data/programs.xml', $descriptorsXml);
$programs = $programsXml->getPrograms();


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
                <h1 class="display-4">Elérhető ajánlatok</h1>
                <hr>
            </div>
        </header>
        <main class="container">
            <div class="row">
            <?php foreach ($programs as $program) : ?>
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card mb-4" style="width: 100%;">
            <div style="height: 190px; overflow: hidden;">
                <img src="<?php echo $program['image'] ?>" class="card-img-top" alt="..." style="object-fit: cover; width: 100%;">
            </div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $program['name'] ?></h5>
                <p class="card-text"><?php echo $program['short_description'] ?> ... </p>
                <p class="mb-1">Helyszín:</p>
                <p><?php echo $program['location'] ?></p>
                <p class="mb-1">Szobák:</p>
                <?php foreach ($program['available_rooms'] as $room) : ?>
                    <span class="badge badge-info"><?php echo $room['name'] ?> (<?php echo $room['num_people'] ?> fő)</span>        
                <?php endforeach; ?>
                <p class="mb-1">Jellemzők:</p>
                <?php foreach ($program['tags'] as $tag) : ?>
                    <span class="badge badge-secondary"><?php echo $tag ?></span>
                <?php endforeach; ?>
                <br />
                <a href="program.php?id=<?php echo $program['id']; ?>" class="btn btn-primary mt-2">Részletek</a>
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
