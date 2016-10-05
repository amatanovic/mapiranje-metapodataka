<div class="fixed">
<nav class="top-bar" data-topbar>
  <?php if(!isset($_GET["id"])) { ?>
  <ul class="title-area">
    <li class="name">
      <h1><a href="#header">Početak</a></h1>
    </li>
    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
  </ul>
  <section class="top-bar-section">
    <ul>
      <li><a href="#about">O aplikaciji</a></li>
      <li><a href="#examples">Primjeri</a></li>
      <li><a href="#mapping">Mapiranje</a></li>
    </ul>
  </section>
  <?php } else {
   ?>
    <ul class="title-area">
    <li class="name">
      <h1><a id="zatvoriNav">Zatvori</a></h1>
    </li>
  </ul>
  <?php } ?>
</nav>

</div>