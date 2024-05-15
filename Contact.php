<?php

$PageTitle="TE-Links || Contact";

function customPageHeader(){?>
  <link rel="stylesheet" href="./assets/css/contact.css">
<?php }

include_once('header.php');
?>
        <main>
            <section class="py-5">
                <div class="container" data-aos="fade-up"
                data-aos-duration="1000">
                    <div class="row justify-content-center text-center mb-3">
                        <div class="col-lg-8 col-xxl-7">
                            <span class="text-muted">Let's Collaborate</span>
                            <h2 class="display-5 fw-bold mb-3">Contact Us</h2>
                            <p class="lead">Lorem ipsum dolor, sit amet elit Quamnitm veniam dicta, quos nemo minima nulla ducimus officii nulla ducimus officiis! Lorem ipsum dolor, sit amet elit Quamnitm.</p>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-lg-6">
                            <h5 class="fw-semibold mb-3">Send us a message</h5>
                            <iframe src="https://docs.google.com/forms/d/e/1FAIpQLScw9Nr6qpaInnGoU4uzeIneLESunZte6RYLyKgAbzb1SIk-Bw/viewform?embedded=true" width="100%" height="100%" frameborder="0" marginheight="0" marginwidth="0">Loading…</iframe>
                        </div>
                        <div class="col-lg-5 mt-5 mt-lg-0">
                            <div class="mb-4">
                                <h5>Address</h5>
                                <p>NED University, Karachi, Pakistan</p>
                            </div>
                            <div class="mb-4">
                                <h5>Phone</h5>
                                <p>+1 202-750-5577</p>
                            </div>
                            <div class="mb-4">
                                <h5>Email</h5>
                                <p>telinks21@gmail.com</p>
                            </div>
                            <div class="mb-4">
                                <h5>Socials</h5><a class="me-2" href="https://www.instagram.com/te.links"><i class='fab fa-instagram' style='font-size:24px;color:#1c1c1c'></i></a>
                                <a class="me-2" href="https://www.facebook.com/te.links1"><i class='fab fa-facebook' style='font-size:24px;color:#1c1c1c'></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <?php
        function customPageFooter(){?>
            <script src="./assets/js/contact.js"></script>
            <?php
        }
        include_once('footer.php');
    ?>
