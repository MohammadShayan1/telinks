
</main>
        </div>
    </div>
    <footer class="app-footer">
        <div class="container-fluid text-center py-3">
             <!--/* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */-->
        <small class="copyright">
                    © 2024 <a class="d-block d-lg-inline mx-1" href="https://MohammadShayan.com"> MohammadShayan.com</a>
                    All rights reserved.</small>
           
        </div>
    </footer><!--//app-footer-->
    
</div><!--//app-wrapper-->    					

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    let warningTime = 840000; // 14 minutes (in milliseconds)
    let logoutTime = 900000; // 15 minutes (in milliseconds)

    // Warn user at 14 minutes
    setTimeout(function() {
        alert("You will be logged out in 1 minute due to inactivity.");
    }, warningTime);

    // Log out user at 15 minutes
    setTimeout(function() {
        window.location.href = "admin_logout.php";
    }, logoutTime);
</script>
</body>

</html>