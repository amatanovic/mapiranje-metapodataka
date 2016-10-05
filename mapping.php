<div class="mapping" id="mapping">
<form method="post" action="upload.php" enctype="multipart/form-data" target="log">
<div class="row">
<div class="large-5 large-push-4 columns">
    <div class="file-input-wrapper">
        <button id="odaberite" class="button expand">Odaberite datoteke</button>
        <input name="upload[]" type="file" multiple="multiple" accept=".xml" id="upload" title=""/>
    </div>
</div>
</div>
<div class="row">
<div class="large-4 large-push-4 columns">
<input type="submit" value="Mapiraj" class="button expand" name="mapping" id="mapiraj" />
</div>
</div>					
</form>
<div class="row">
<div class="large-12 columns">
<iframe class="log" id="log" name="log" src="upload.php">
</iframe>
</div>
</div>

</div>