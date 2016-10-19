<?php
function libxml_display_error($error)
{
    $return = "Linija <span style='font-weight:bold'>$error->line</span>: ";
    $return .= "<span style='font-style:italic'>$error->message</span>";

    return $return;
}

function libxml_display_errors(&$poruke)
{
    $errors = libxml_get_errors();
    foreach ($errors as $error) {
        $poruke[] .= libxml_display_error($error);

    }

    libxml_clear_errors();
}

$poruke = array();
$downloadPoruka = array();
$dcNamespaceUri = "http://purl.org/dc/elements/1.1/";
$modsNamespaceUri = "http://www.loc.gov/mods/v3";
session_start();
$_SESSION["progress"] = 0;
if (isset($_POST['mapping'])) {
    $_SESSION["progress"] = 0;
    session_write_close();
    session_start();
    $folderName = date('YmdHis', time()) . rand(0, 1000);
    if (count($_FILES['upload']['name']) > 5) {
        $poruke[] = "<i class='fa fa-close'></i> Prešli ste maksimalan dozvoljeni broj datoteka.";
    } else if (count($_FILES['upload']['name']) == 1 && $_FILES['upload']['name'][0] == "") {
        $poruke[] = "<i class='fa fa-close'></i> Nemate priloženih datoteka.";
    } else {
        $progress = count($_FILES['upload']['name']) * 3;
        $progressLoop = 0;
        mkdir("./uploaded/" . $folderName . "/output", 0777, true);
        for ($index = 0; $index < count($_FILES['upload']['name']); $index++) {
            $tmpFilePath = $_FILES['upload']['tmp_name'][$index];
            if (pathinfo($_FILES['upload']['name'][$index], PATHINFO_EXTENSION) == 'xml') {
                $newFilePath = "./uploaded/" . $folderName . "/" . $_FILES['upload']['name'][$index];
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $progressLoop++;
                    $_SESSION["progress"] = $progressLoop / $progress * 100;
                    session_write_close();
                    session_start();
                    $dokument = new DOMDocument('1.0', 'UTF-8');
                    libxml_use_internal_errors(true);
                    if ($dokument->load($newFilePath)) {
                        $saveName = pathinfo($newFilePath, PATHINFO_FILENAME);
                        $dcPrefix = $dokument->lookupPrefix($dcNamespaceUri);
                        $modsPrefix = $dokument->lookupPrefix($modsNamespaceUri);
                        if ($dcPrefix != "" || $dokument->documentElement->getAttribute("xmlns") == $dcNamespaceUri) {
                            if ($dokument->schemaValidate('http://dublincore.org/schemas/xmls/qdc/2008/02/11/simpledc.xsd')) {
                                if ($dcPrefix != "") {
                                    $dcPrefix = $dcPrefix . ":";
                                }
                                include "dc_mods.php";
                            } else
                                $poruke[] = "<i class='fa fa-close'></i> Datoteka " . $_FILES['upload']['name'][$index] . " nije valjana prema DC shemi i ima sljedeće pogreške:";
                                libxml_display_errors($poruke);
                        } else if ($modsPrefix != "" || $dokument->documentElement->getAttribute("xmlns") == $modsNamespaceUri) {
                            if ($dokument->schemaValidate('http://www.loc.gov/standards/mods/v3/mods-3-6.xsd')) {
                                if ($modsPrefix != "") {
                                    $modsPrefix = $modsPrefix . ":";
                                }
                                include "mods_dc.php";
                            } else
                                $poruke[] = "<i class='fa fa-close'></i> Datoteka " . $_FILES['upload']['name'][$index] . " nije valjana prema MODS shemi i ima sljedeće pogreške:";
                                libxml_display_errors($poruke);
                        } else
                                $poruke[] = "<i class='fa fa-close'></i> Datoteku " . $_FILES['upload']['name'][$index] . " nije moguće mapirati " .
                                    "jer prema imenskom prostoru nije pronađeno da se radi o DC ili MODS shemi metapodataka.";

                    } else
                        $poruke[] = "<i class='fa fa-close'></i> Datoteka " . $_FILES['upload']['name'][$index] . " nije validan XML i ima sljedeće pogreške:";
                        libxml_display_errors($poruke);
                }

            } else
                $poruke[] = "<i class='fa fa-close'></i> Datoteka " . $_FILES['upload']['name'][$index] . " nije XML.";
        }
    }
    $mapiraneDatoteke = scandir("uploaded/" . $folderName . "/output", 1);
    $brojMapiranihDatoteka = count($mapiraneDatoteke);
    if ($brojMapiranihDatoteka == 3) {
        $downloadPoruka[] = "<p><input type='hidden' value='" . $folderName . "' id='pregledFolder' /><input type='hidden' value='" . pathinfo($mapiraneDatoteke[0], PATHINFO_FILENAME) . "' id='pregledDatoteka' /><a class='poveznica' id='pregled'>Pregledajte rezultat mapiranja</a></p>";
        $downloadPoruka[] = "<p><input type='hidden' value='" . $folderName . "' name='folder' id='folder' /><a class='download poveznica' id='" . $mapiraneDatoteke[0] . "'>Preuzmite mapiranu datoteku</a></p>";
    } else if ($brojMapiranihDatoteka > 3) {
        $downloadPoruka[] = "<input type='hidden' value='" . $folderName . "' name='folder' id='folder' /><a class='download poveznica' id='zip'>Preuzmite mapirane datoteke</a>";
    }
    if (!empty($downloadPoruka)) {
        $_SESSION["progress"] = 100;
    }
}

?>
<html class="no-js" lang="en">
<head>
    <link rel="stylesheet" href="css/foundation.min.css"/>
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css"/>
    <script src="js/vendor/modernizr.js"></script>
</head>
<body>
<div class="progress
<?php
if ($_SESSION['progress'] == 100) {
    echo 'success';
} else if (!empty($poruke) && $_SESSION['progress'] < 100) {
    echo "alert";
}
?>
">
    <span class="meter"></span>
</div>
<div class="row tablica">
    <div class="large-12 columns">
        <?php if (!empty($poruke)): ?>
        <table class="sirina">
            <thead>
            <th> Status mapiranja</th>
            </thead>
            <tbody>
            <?php foreach ($poruke as $p): ?>
                <tr>
                    <td><?php echo $p; ?></td>
                </tr>
            <?php endforeach;
            endif;
            ?>
            </tbody>
        </table>
    </div>
</div>
<?php if (!empty($downloadPoruka)): ?>
    <div class="row forma">
        <div class="large-12 columns">
            <?php foreach ($downloadPoruka as $poruka):
                echo $poruka;
            endforeach;
            ?>
        </div>
    </div>
<?php endif; ?>
<?php
if (empty($poruke)): ?>

    <div class="row spinner">
        <div class="large-12 columns">
            <p>
                <i class="fa fa-spinner fa-spin"></i>
            </p>
            <p>
                Molimo pričekajte...
            </p>
        </div>
    </div>

<?php endif; ?>
<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
<script>
    $(document).foundation();
    $(document).ready(function () {
        $(".download").click(function () {
            $(".progress").css("display", "none");
            $(".tablica").css("display", "none");
            $(".forma").css("visibility", "hidden");
            var folder = $("#folder").val();
            $.ajax({
                type: "POST",
                url: "download.php",
                data: "folder=" + folder + "&vrsta=" + $(".download").attr("id"),
                success: function (msg) {
                    window.parent.location.href = msg;
                    parent.closeIFrame();
                }
            });
        });
        $("#pregled").click(function () {
            parent.window.open("pregled.php?id=" + $("#pregledFolder").val() + "&name=" + $("#pregledDatoteka").val(), "_blank");

        });

    });


    function napuniProgress(data) {
        $(".meter").css("width", "" + data + "%");
    }

</script>
</body>
</html>