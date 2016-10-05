<div class="examples" id="examples">
<?php if(!isset($_GET["id"])) { ?>
<div class="row">
<div class="large-12 columns subtitle">
<h4>Primjer mapiranja</h4>
<p>Dublin Core <i class="fa fa-exchange"></i> <span class="mods">MODS</span> *</p>
</div>
</div>
<div class="modsHover">
Prilikom mapiranja iz MODS-a u DC, u elementu <span class="italic">name</span> će biti mapirana vrijednost <span class="italic">displayForm</span> ukoliko postoji.
</div>
<div class="row">
<div class="large-6 columns">
<pre>
	<code class="xml">
&lt;?xml version=&quot;1.0&quot; encoding=&quot;utf-8&quot; ?&gt;
&lt;simpledc xmlns:dc=&quot;http://purl.org/dc/elements/1.1/&quot; xmlns:xsi=&quot;http://www.w3.org/2001/XMLSchema-instance&quot; xsi:noNamespaceSchemaLocation=&quot;http://dublincore.org/schemas/xmls/qdc/2008/02/11/simpledc.xsd&quot;&gt;
&lt;dc:title&gt;Abecevica&lt;/dc:title&gt;
&lt;dc:creator&gt;Juraj Mulih&lt;/dc:creator&gt;
&lt;dc:publisher&gt;s.l.; s.n.&lt;/dc:publisher&gt;
&lt;dc:date&gt;1737&lt;/dc:date&gt;
&lt;dc:language&gt;hrv&lt;/dc:language&gt;	 
&lt;dc:type&gt;Katekizam&lt;/dc:type&gt; 
&lt;dc:format&gt;text/html&lt;/dc:format&gt;	
&lt;dc:description&gt;Podaci o autoru i godini tiskanja rekonstruirani uz pomoć sadržajne analize teksta i usporedbe drugim sadržajno sličnim tekstovima toga razdoblja.&lt;/dc:description&gt;
&lt;dc:description&gt;Pretpostavlja se da je autor Juraj Mulih&lt;/dc:description&gt;
&lt;dc:description&gt;Digitalizirano i obrađeno 03.01.2010. u Nacionalna knjižnica Széchényi &lt;/dc:description&gt;
&lt;dc:description&gt;Posjednik izvornika: Nacionalna knjižnica Széchényi &lt;/dc:description&gt;
&lt;dc:coverage&gt;Mursa, Osijek&lt;/dc:coverage&gt;
&lt;dc:subject&gt;Bratovština muke i smrti Isusove, Kalvini, Limb, Luterani, Pravoslavci, Sakramenti, tablica množenja, Turci, Židovi&lt;/dc:subject&gt;
&lt;dc:subject&gt;jabuka, križ, kruh, vino&lt;/dc:subject&gt;
&lt;dc:subject&gt;Adam, Eva, Ivan Krstitelj, sv., Mihovil, sv., arkanđeo, Pavao Apostol, sv., Petar Apostol, sv., Poncije Pilat&lt;/dc:subject&gt;
&lt;dc:subject&gt;abeceda, brojevi, izgovor, jezik, slogovi &lt;/dc:subject&gt;	
&lt;dc:identifier&gt;http://web.ffos.hr/EDICIJA/digitalnaZbirka/pregledKnjige.php?kljuc=K2S1&lt;/dc:identifier&gt;	
&lt;/simpledc&gt;
</code>
  </pre>
</div>
<div class="large-6 columns">
<pre>
	<code class="xml">
&lt;?xml version=&quot;1.0&quot; encoding=&quot;UTF-8&quot;?&gt;
&lt;mods xmlns=&quot;http://www.loc.gov/mods/v3&quot; xmlns:xsi=&quot;http://www.w3.org/2001/XMLSchema-instance&quot; xmlns:xlink=&quot;http://www.w3.org/1999/xlink&quot; xsi:schemaLocation=&quot;http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-5.xsd&quot; version=&quot;3.5&quot;&gt;
  &lt;titleInfo&gt;
    &lt;title&gt;Abecevica&lt;/title&gt;
  &lt;/titleInfo&gt;
  &lt;name type=&quot;personal&quot; authority=&quot;lcnaf&quot;&gt;
    &lt;namePart&gt;Mulih, Juraj&lt;/namePart&gt;
    &lt;namePart type=&quot;date&quot;&gt;1694-1754&lt;/namePart&gt;
    &lt;displayForm&gt;Mulih, Juraj, 1694-1754&lt;/displayForm&gt;
    &lt;role&gt;
      &lt;roleTerm type=&quot;text&quot; authority=&quot;marcrelator&quot;&gt;Creator&lt;/roleTerm&gt;
    &lt;/role&gt;
  &lt;/name&gt;
  &lt;originInfo&gt;
    &lt;publisher&gt;s.l.; s.n.&lt;/publisher&gt;
    &lt;dateOther&gt;1737&lt;/dateOther&gt;
  &lt;/originInfo&gt;
  &lt;language&gt;
    &lt;languageTerm authority=&quot;iso639-2b&quot; type=&quot;code&quot;&gt;hrv&lt;/languageTerm&gt;
  &lt;/language&gt;
  &lt;genre&gt;Katekizam&lt;/genre&gt;
  &lt;physicalDescription&gt;
    &lt;internetMediaType&gt;text/html&lt;/internetMediaType&gt;
  &lt;/physicalDescription&gt;
  &lt;note&gt;Podaci o autoru i godini tiskanja rekonstruirani uz pomoć sadržajne analize teksta i usporedbe s drugim sadržajno sličnim tekstovima toga razdoblja.&lt;/note&gt;
  &lt;note&gt;Pretpostavlja se da je autor Juraj Mulih&lt;/note&gt;
  &lt;note&gt;Digitalizirano i obrađeno 03.01.2010. u Nacionalna knjižnica Széchényi &lt;/note&gt;
  &lt;note&gt;Posjednik izvornika: Nacionalna knjižnica Széchényi &lt;/note&gt;
  &lt;subject&gt;
    &lt;geographic&gt;Mursa, Osijek&lt;/geographic&gt;
  &lt;/subject&gt;
  &lt;subject&gt;
    &lt;topic&gt;Bratovština muke i smrti Isusove, Kalvini, Limb, Luterani, Pravoslavci, Sakramenti, tablica množenja, Turci, Židovi&lt;/topic&gt;
  &lt;/subject&gt;
  &lt;subject&gt;
    &lt;topic&gt;jabuka, križ, kruh, vino&lt;/topic&gt;
  &lt;/subject&gt;
  &lt;subject&gt;
    &lt;topic&gt;Adam, Eva, Ivan Krstitelj, sv., Mihovil, sv., arkanđeo, Pavao Apostol, sv., Petar Apostol, sv., Poncije Pilat&lt;/topic&gt;
  &lt;/subject&gt;
  &lt;subject&gt;
    &lt;topic&gt;abeceda, brojevi, izgovor, jezik, slogovi &lt;/topic&gt;
  &lt;/subject&gt;
  &lt;location&gt;
    &lt;url&gt;http://web.ffos.hr/EDICIJA/digitalnaZbirka/pregledKnjige.php?kljuc=K2S1&lt;/url&gt;
  &lt;/location&gt;
&lt;/mods&gt;
</code>
  </pre>
</div>
</div>
<?php } 
else 
{ ?>
<div class="row">
<div class="large-6 columns">
<pre>
  <code class="xml">
<?php 
$putanja = "uploaded/" . $_GET["id"] . "/" . scandir("uploaded/". $_GET["id"], 1)[1];
$dokument = file_get_contents($putanja);
$izmjena = array('<', '>', '"');
$izmijeniti = array('&lt;', '&gt;', '&quot;');
$noviDokument = str_replace($izmjena, $izmijeniti, $dokument);
echo $noviDokument;
 ?>
</code>
  </pre>
</div>
<div class="large-6 columns">
<pre>
  <code class="xml">
<?php 
$putanja = "uploaded/" . $_GET["id"] . "/output/" . scandir("uploaded/". $_GET["id"] . "/output/", 1)[0];
$dokument = file_get_contents($putanja);
$izmjena = array('<', '>', '"');
$izmijeniti = array('&lt;', '&gt;', '&quot;');
$noviDokument = str_replace($izmjena, $izmijeniti, $dokument);
echo $noviDokument;
 ?>

</code>
  </pre>
</div>
</div>
 <?php } ?>
</div>