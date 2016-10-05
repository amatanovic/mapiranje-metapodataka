<?php
if ($_POST['vrsta'] == "zip") {
  $zip_file_name = "uploaded/" . $_POST['folder'] . "/output.zip";
    class FlxZipArchive extends ZipArchive {
   public function addDir($location, $name) {
        $this->addEmptyDir($name);

        $this->addDirDo($location, $name);
     } 

    private function addDirDo($location, $name) {
        $name .= '/';
        $location .= '/';
        $dir = opendir ($location);
        while ($file = readdir($dir))
        {
            if ($file == '.' || $file == '..') continue;
            $do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
            $this->$do($location . $file, $name . $file);
        }
    }
}
$za = new FlxZipArchive;
$res = $za->open($zip_file_name, ZipArchive::CREATE);
if($res === TRUE) 
{
    $za->addDir("uploaded/" . $_POST['folder'] . "/output", basename("/"));
    $za->close();
}
    echo $zip_file_name;
}

else {
$file = "uploaded/" . $_POST['folder'] . "/output/" . $_POST['vrsta'];
echo $file;
}