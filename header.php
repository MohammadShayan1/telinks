<!doctype html>
<html lang="en">
<head>
<title><?= isset($PageTitle) ? $PageTitle : "TE LINKS"?></title>
    <link rel="shortcut icon" href="./assets/imgs/telinkslogo.ico" type="image/x-icon">
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:400,900">
    <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
        <!-- Additional tags here -->
        <?php if (function_exists('customPageHeader')){
      customPageHeader();
    }?>
    <link rel="stylesheet" href="./assets/css/header.css">
</head>

<body>
    <header>
        <section class="bg-dark">
            <a href="./index.php" class="logo">
                <img class="sticky-top" style="position: fixed; top: 5%; height: 75px;" src="./assets/imgs/Original.png"
                    alt="TE LINKS Logo">
            </a>
            <input class="menu-icon" type="checkbox" id="menu-icon" name="menu-icon" />
            <label for="menu-icon"></label>
            <nav class="nav">
                <ul class="pt-5">
                    <li><a href="./index.php">Home</a></li>
                    <li><a href="./member.php">Member</a></li>
                    <li><a href="./Gallery.php">Gallery</a></li>
                    <li><a href="./Contact.php">Contact</a></li>
                </ul>
            </nav>
        </section>
    </header>