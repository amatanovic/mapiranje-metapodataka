<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();
      $(document).ready(function() {
  hljs.initHighlighting();
     $(function () {
        $('a[href*=#]:not([href=#])').click(function () {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
                    || location.hostname == this.hostname) {

                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: target.offset().top
                    }, 700);
                    return false;
                }
            }
        });
    });
    $(".mods").mouseover(function(){
        $(".modsHover").css("display", "block");
    });
   $(".mods").mouseout(function(){
        $(".modsHover").css("display", "none");
    });
   $("#mapiraj").click(function() {
           $("#log").css("visibility", "visible");
           getProgress();
           $("#forma").submit();

   });

   $("#upload").click(function() {
        $('#log').attr('src', $('#log').attr('src'));
        $("#log").css("visibility", "hidden");
   });

          $('#upload').bind('change', function() {
              var uploaded = this.files.length;
              if (uploaded === 0) {
                  $("#odaberite").html("Odaberite datoteke");
              }
              else {
                  $("#odaberite").html("Odabrano datoteka: " + this.files.length);
              }

          });

   $("#zatvoriNav").click(function() {
      window.close();
   });
});
 function closeIFrame(){
     $('#log').css("visibility", "hidden");
     $("#upload").val("");
     $("#odaberite").html("Odaberite datoteke");
 }

function getProgress() {
    $.ajax({
        url: "progress.php",
        success: function(data) {
            document.getElementById("log").contentWindow.napuniProgress(data);
            if(data<100){
                getProgress();
            }
        }
    });
}

</script>    
</body>
</html>