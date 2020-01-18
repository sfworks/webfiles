    <div class="footer">
        ©2019 Powered by SF Works BV. All Rights reserved. &nbsp; | &nbsp; <a href="tos.php">Terms of Service and Privacy Policy</a>
    </div>

    <!-- loading modal -->
    <div id="loadingModal" class="modal fade" tabindex="-1"  data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-body">
                  <div class="text-center">
                    <i class="fas fa-circle-notch fa-spin"></i><br><br>
                    <p id="loadingText" class="loading-text"></p>
                  </div>
              </div>
          </div>
        </div>
    </div><!-- ./ loading modal -->

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/vendor/lightbox/js/lightbox.min.js"></script>
    <script src="assets/vendor/owl/owl.carousel.min.js"></script>

    <script>
      $(document).ready(function(){
        $('.owl-carousel').owlCarousel({
          loop:false,
          margin:20,
          autoWidth:true,
          items:4
        });
      });

      function hideLoadingModal() {
        $('#loadingModal').modal('hide');
      }

      //---------------------------------
      // MARK - CHECK FOR BROWSER NAME
      //---------------------------------
      if(navigator.userAgent.indexOf("Chrome") != -1 ){
        console.log('Chrome');
      } else if(navigator.userAgent.indexOf("Safari") != -1) {
        console.log('Safari');
      } else if(navigator.userAgent.indexOf("Firefox") != -1 ) {
         console.log('Firefox');
      // Show the download-broswer.php page
      } else { window.location.href = "download-browser.php"; }


      //---------------------------------
      // MARK - CALCULATE THE SCREEN SIZE
      //---------------------------------
      var viewportwidth;
      var viewportheight;
      if (typeof window.innerWidth != "undefined") {
          viewportwidth = window.innerWidth,
          viewportheight = window.innerHeight
        } else if (typeof document.documentElement != "undefined" && typeof document.documentElement.clientWidth != "undefined" && document.documentElement.clientWidth != 0) {
          viewportwidth = document.documentElement.clientWidth,
          viewportheight = document.documentElement.clientHeight
        } else {
          viewportwidth = document.getElementsByTagName("body")[0].clientWidth,
          viewportheight = document.getElementsByTagName("body")[0].clientHeight
        }
        // console.log("SCREEN SIZE: " + viewportwidth + "x" + viewportheight + "px");


        // Show/Hide bottom navbar based on screen size (mobile/desktop)
        var bottomNav = document.getElementById("bottom-navbar");
        if(viewportwidth < 767){ bottomNav.style.display = "block";
        } else { bottomNav.style.display = "none";  }

        // Show/Hide rigtht menu button based on screen size (mobile/desktop)
        var btnRightMenu = document.getElementById("btn-right-menu");
        if(viewportwidth < 767){ btnRightMenu.style.display = "none";
        } else { btnRightMenu.style.display = "block";  }

        // Show/Hide rigtht menu button based on screen size (mobile/desktop)
        var navbarBrand = document.getElementById("navbar-brand");
        if(viewportwidth < 767){ navbarBrand.style.display = "none";
        } else { navbarBrand.style.display = "block";  }
    </script>
