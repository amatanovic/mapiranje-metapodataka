<?php
$novi_dokument = new DOMDocument('1.0', 'UTF-8');
$rootElement = $novi_dokument->createElementNS('http://www.loc.gov/mods/v3', 'mods');
$novi_dokument->appendChild($rootElement);
$rootElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
$rootElement->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'schemaLocation', 'http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-5.xsd');
$rootElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xlink', 'http://www.w3.org/1999/xlink');
$rootElement->setAttribute('version', '3.5');

$elementi = $dokument->documentElement->childNodes;
$progressLoop++;
$_SESSION["progress"] = $progressLoop / $progress * 100;
session_write_close();
session_start();
foreach ($elementi as $element) {
    if ($element->nodeName == $dcPrefix . "title") {
        $titleInfo = $novi_dokument->createElement("titleInfo");
        $rootElement->appendChild($titleInfo);
        $title = $novi_dokument->createElement("title", $element->nodeValue);
        $titleInfo->appendChild($title);

    }
    if ($element->nodeName == $dcPrefix . "creator" || $element->nodeName == $dcPrefix . "contributor") {
        $lcnafAPI = new DOMDocument();
        $lcnafAPI->load('http://id.loc.gov/search/?q=' . $element->nodeValue . '&q=cs:http://id.loc.gov/authorities/names&start=1&count=1&format=atom');
        $lcnafEntries = $lcnafAPI->documentElement->childNodes;
        foreach ($lcnafEntries as $lcnafEntry) {
            if ($lcnafEntry->nodeName == "entry") {
                $lcnafEntryChildren = $lcnafEntry->childNodes;
                foreach ($lcnafEntryChildren as $lcnafEntryChild) {
                    if ($lcnafEntryChild->nodeName == "title") {
                        $lcnafDisplayForm = $lcnafEntryChild->nodeValue;
                    }
                }
            }
        }
        $creator_contributor = $novi_dokument->createElement("name");
        $rootElement->appendChild($creator_contributor);
        if (!empty($lcnafDisplayForm)) {
            $nameDate = explode(", ", $lcnafDisplayForm);
            $creator_contributor->setAttribute('type', 'personal');
            $creator_contributor->setAttribute('authority', 'lcnaf');
            $namePart = $novi_dokument->createElement("namePart", $nameDate[0] . ", " . $nameDate[1]);
            $creator_contributor->appendChild($namePart);
            $namePartDate = $novi_dokument->createElement("namePart", $nameDate[2]);
            $creator_contributor->appendChild($namePartDate);
            $namePartDate->setAttribute('type', 'date');
            $displayForm = $novi_dokument->createElement("displayForm", $lcnafDisplayForm);
            $creator_contributor->appendChild($displayForm);
        } else if (empty($lcnafDisplayForm)) {
            $namePart = $novi_dokument->createElement("namePart", $element->nodeValue);
            $creator_contributor->appendChild($namePart);
        }
        $role = $novi_dokument->createElement("role");
        $creator_contributor->appendChild($role);
        if ($element->nodeName == $dcPrefix . "creator") {
            $roleTerm = $novi_dokument->createElement("roleTerm", 'Creator');
        } else if ($element->nodeName == $dcPrefix . "contributor") {
            $roleTerm = $novi_dokument->createElement("roleTerm", 'Contributor');
        }
        $roleTerm->setAttribute('type', 'text');
        $role->appendChild($roleTerm);
        $roleTerm->setAttribute('authority', 'marcrelator');
        $role->appendChild($roleTerm);
    }
    if ($element->nodeName == $dcPrefix . "type") {
        if (strtoupper($element->nodeValue) == "COLLECTION") {
            $genre = $novi_dokument->createElement("genre", $element->nodeValue);
            $genre->setAttribute('authority', 'dct');
            $rootElement->appendChild($genre);
        } else if (strtoupper($element->nodeValue) == "DATASET") {
            $typeOfResource = $novi_dokument->createElement("typeOfResource", "software, multimedia");
            $genre = $novi_dokument->createElement("genre", "database");
            $genre->setAttribute('authority', 'dct');
            $rootElement->appendChild($typeOfResource);
            $rootElement->appendChild($genre);
        } else if (strtoupper($element->nodeValue) == "IMAGE") {
            $typeOfResource = $novi_dokument->createElement("typeOfResource", "still image");
            $genre = $novi_dokument->createElement("genre", $element->nodeValue);
            $genre->setAttribute('authority', 'dct');
            $rootElement->appendChild($typeOfResource);
            $rootElement->appendChild($genre);
        } else if (strtoupper($element->nodeValue) == "INTERACTIVERESOURCE") {
            $typeOfResource = $novi_dokument->createElement("typeOfResource", "software, multimedia");
            $genre = $novi_dokument->createElement("genre", "interactive resource");
            $genre->setAttribute('authority', 'dct');
            $rootElement->appendChild($typeOfResource);
            $rootElement->appendChild($genre);
        } else if (strtoupper($element->nodeValue) == "MOVINGIMAGE") {
            $typeOfResource = $novi_dokument->createElement("typeOfResource", "moving image");
            $genre = $novi_dokument->createElement("genre", "moving image");
            $genre->setAttribute('authority', 'dct');
            $rootElement->appendChild($typeOfResource);
            $rootElement->appendChild($genre);
        } else if (strtoupper($element->nodeValue) == "PHYSICALOBJECT") {
            $typeOfResource = $novi_dokument->createElement("typeOfResource", "three dimensional object");
            $genre = $novi_dokument->createElement("genre", "physical object");
            $genre->setAttribute('authority', 'dct');
            $rootElement->appendChild($typeOfResource);
            $rootElement->appendChild($genre);
        } else if (strtoupper($element->nodeValue) == "SERVICE") {
            $typeOfResource = $novi_dokument->createElement("typeOfResource", "software, multimedia");
            $genre = $novi_dokument->createElement("genre", "online system or service");
            $genre->setAttribute('authority', 'dct');
            $rootElement->appendChild($typeOfResource);
            $rootElement->appendChild($genre);
        } else if (strtoupper($element->nodeValue) == "SOFTWARE") {
            $typeOfResource = $novi_dokument->createElement("typeOfResource", "software, multimedia");
            $genre = $novi_dokument->createElement("genre", "software");
            $genre->setAttribute('authority', 'dct');
            $rootElement->appendChild($typeOfResource);
            $rootElement->appendChild($genre);
        } else if (strtoupper($element->nodeValue) == "SOUND") {
            $typeOfResource = $novi_dokument->createElement("typeOfResource", "sound recording");
            $genre = $novi_dokument->createElement("genre", "sound");
            $genre->setAttribute('authority', 'dct');
            $rootElement->appendChild($typeOfResource);
            $rootElement->appendChild($genre);
        } else if (strtoupper($element->nodeValue) == "STILLIMAGE") {
            $typeOfResource = $novi_dokument->createElement("typeOfResource", "still image");
            $genre = $novi_dokument->createElement("genre", "still image");
            $genre->setAttribute('authority', 'dct');
            $rootElement->appendChild($typeOfResource);
            $rootElement->appendChild($genre);
        } else if (strtoupper($element->nodeValue) == "TEXT") {
            $typeOfResource = $novi_dokument->createElement("typeOfResource", "text");
            $genre = $novi_dokument->createElement("genre", "text");
            $genre->setAttribute('authority', 'dct');
            $rootElement->appendChild($typeOfResource);
            $rootElement->appendChild($genre);
        } else {
            $genre = $novi_dokument->createElement("genre", $element->nodeValue);
            $rootElement->appendChild($genre);
        }
    }

    if ($element->nodeName == $dcPrefix . "format") {
        $nodeElement = $novi_dokument->getElementsByTagName('physicalDescription');
        if ($nodeElement->length == 0) {
            $physicalDescription = $novi_dokument->createElement("physicalDescription");
            $rootElement->appendChild($physicalDescription);
        }
        $vrijednost = $element->nodeValue;
        $uvjet = "/";
        $rezultat = strpos($vrijednost, $uvjet);
        if ($rezultat !== false) {
            $internetMediaType = $novi_dokument->createElement("internetMediaType", $element->nodeValue);
            $physicalDescription->appendChild($internetMediaType);
        }
        for ($i = 1; $i < 10; $i++) {
            if (substr($vrijednost, 0, 1) == $i) {
                $extent = $novi_dokument->createElement("extent", $element->nodeValue);
                $physicalDescription->appendChild($extent);
            }
        }
        $search_internetMediaType = $novi_dokument->getElementsByTagName('internetMediaType');
        $search_extent = $novi_dokument->getElementsByTagName('extent');
        if ($search_internetMediaType->length == 0 && $search_extent->length == 0) {
            $form = $novi_dokument->createElement("form", $element->nodeValue);
            $physicalDescription->appendChild($form);
        }
    }
    if ($element->nodeName == $dcPrefix . "subject") {
        $subject = $novi_dokument->createElement("subject");
        $rootElement->appendChild($subject);
        $topic = $novi_dokument->createElement("topic", $element->nodeValue);
        $subject->appendChild($topic);
    }
    if ($element->nodeName == $dcPrefix . "description") {
        $note = $novi_dokument->createElement("note", $element->nodeValue);
        $rootElement->appendChild($note);
    }
    if ($element->nodeName == $dcPrefix . "publisher" || $element->nodeName == $dcPrefix . "date") {
        $search_originInfo = $novi_dokument->getElementsByTagName('originInfo');
        if ($search_originInfo->length == 0) {
            $originInfo = $novi_dokument->createElement("originInfo");
            $rootElement->appendChild($originInfo);
        }
        if ($element->nodeName == $dcPrefix . "publisher") {
            $publisher = $novi_dokument->createElement("publisher", $element->nodeValue);
            $originInfo->appendChild($publisher);
        } else if ($element->nodeName == $dcPrefix . "date") {
            $dateOther = $novi_dokument->createElement("dateOther", $element->nodeValue);
            $originInfo->appendChild($dateOther);
        }
    }

    if ($element->nodeName == $dcPrefix . "language") {
        $search_language = $novi_dokument->getElementsByTagName('language');
        if ($search_language->length == 0) {
            $language = $novi_dokument->createElement("language");
            $rootElement->appendChild($language);
        }
        $json = file_get_contents('iso639-2.json'); // URL: http://id.loc.gov/vocabulary/iso639-2.madsrdf.json
        $data = json_decode($json, true);
        for ($i = 0; $i < count($data[0]["http://www.loc.gov/mads/rdf/v1#hasTopMemberOfMADSScheme"]); $i++) {
            if ($data[0]["http://www.loc.gov/mads/rdf/v1#hasTopMemberOfMADSScheme"][$i]["@id"] == "http://id.loc.gov/vocabulary/iso639-2/" . $element->nodeValue) {
                $languageTerm = $novi_dokument->createElement("languageTerm", $element->nodeValue);
                $languageTerm->setAttribute('authority', 'iso639-2b');
                $languageTerm->setAttribute('type', 'code');
                $language->appendChild($languageTerm);
                goto nastavak;
            }

        }
        $languageTerm1 = $novi_dokument->createElement("languageTerm", $element->nodeValue);
        $languageTerm1->setAttribute('type', 'text');
        $language->appendChild($languageTerm1);

    }
    nastavak:

    if ($element->nodeName == $dcPrefix . "source" || $element->nodeName == $dcPrefix . "relation") {
        $relatedItem = $novi_dokument->createElement("relatedItem");
        $rootElement->appendChild($relatedItem);
        if (substr($element->nodeValue, 0, 7) == "http://") {
            $location = $novi_dokument->createElement("location");
            $relatedItem->appendChild($location);
            $url = $novi_dokument->createElement("url", $element->nodeValue);
            $location->appendChild($url);
        } else if (substr($element->nodeValue, 0, 7) !== "http://") {
            $titleInfo_relatedItem = $novi_dokument->createElement("titleInfo");
            $relatedItem->appendChild($titleInfo_relatedItem);
            $title_relatedItem = $novi_dokument->createElement("title", $element->nodeValue);
            $titleInfo_relatedItem->appendChild($title_relatedItem);
        }
        if ($element->nodeName == $dcPrefix . "source") {
            $relatedItem->setAttribute('type', 'original');
        }
    }
    if ($element->nodeName == $dcPrefix . "rights") {
        $accessCondition = $novi_dokument->createElement("accessCondition", $element->nodeValue);
        $rootElement->appendChild($accessCondition);
    }
    if ($element->nodeName == $dcPrefix . "coverage") {
        $subject_coverage = $novi_dokument->createElement("subject");
        $rootElement->appendChild($subject_coverage);
        $cartographics = $novi_dokument->createElement("cartographics");
        $vrijednost = $element->nodeValue;
        $uvjet = ":";
        $rezultat = strpos($vrijednost, $uvjet);
        if (substr($element->nodeValue, 0, 1) == "1" && $rezultat !== false) {
            $vrijednost1 = substr($vrijednost, strpos($vrijednost, ":") + 1);
            for ($i = 0; $i < 10; $i++) {
                $rezultat1 = strpos($vrijednost1, "$i");
                if ($rezultat1 !== false) {
                    $subject_coverage->appendChild($cartographics);
                    $scale = $novi_dokument->createElement("scale", $vrijednost);
                    $cartographics->appendChild($scale);
                    goto nastavak1;
                }
            }

        } else if (strpos($vrijednost, "°") !== false || strpos($vrijednost, " N ") !== false || strpos($vrijednost, " S ") !== false || strpos($vrijednost, " E ") !== false || strpos($vrijednost, " W ") !== false || strpos($vrijednost, "geo:lat") !== false || strpos($vrijednost, "geo:lon") !== false) {
            $subject_coverage->appendChild($cartographics);
            $coordinates = $novi_dokument->createElement("coordinates", $vrijednost);
            $cartographics->appendChild($coordinates);
            goto nastavak1;
        }
        $geographic = $novi_dokument->createElement("geographic", $vrijednost);
        $subject_coverage->appendChild($geographic);

        nastavak1:
    }

    if ($element->nodeName == $dcPrefix . "identifier") {
        $iso3166Checked = 0;
        $iso3166Check = file_get_contents('iso3166.json'); // URL: http://data.okfn.org/data/core/country-list/r/data.json

        $iso3166CheckDecoded = json_decode($iso3166Check, true);
        for ($i = 0; $i < count($iso3166CheckDecoded); $i++) {
            if (strpos($element->nodeValue, $iso3166CheckDecoded[$i]["Code"]) !== false) {
                $iso3166Checked = 1;
            }
        }
        $identifier = $novi_dokument->createElement("identifier", $element->nodeValue);
        if (substr($element->nodeValue, 0, 7) == "http://") {
            $location = $novi_dokument->createElement("location");
            $rootElement->appendChild($location);
            $url = $novi_dokument->createElement("url", $element->nodeValue);
            $location->appendChild($url);
        } else if (substr($element->nodeValue, 0, 7) == "http://" && strpos($element->nodeValue, "hdl") == false && strpos($element->nodeValue, "purl") == false) {
            $rootElement->appendChild($identifier);
            $identifier->setAttribute('type', 'uri');
        } else if (substr($element->nodeValue, 0, 7) == "urn:hdl" || substr($element->nodeValue, 0, 3) == "hdl" || substr($element->nodeValue, 0, 11) == "http://hdl.") {
            $rootElement->appendChild($identifier);
            $identifier->setAttribute('type', 'hdl');
        } else if (substr($element->nodeValue, 0, 3) == "doi") {
            $rootElement->appendChild($identifier);
            $identifier->setAttribute('type', 'doi');
        } else if (substr($element->nodeValue, 0, 3) == "ark") {
            $rootElement->appendChild($identifier);
            $identifier->setAttribute('type', 'ark');
        } else if (substr($element->nodeValue, 0, 3) == "tag") {
            $rootElement->appendChild($identifier);
            $identifier->setAttribute('type', 'tag');
        } else if (strpos($element->nodeValue, "purl") !== false) {
            $rootElement->appendChild($identifier);
            $identifier->setAttribute('type', 'purl');
        } else if (strtoupper(substr($element->nodeValue, 0, 4)) == "ISBN") {
            $rootElement->appendChild($identifier);
            $identifier->setAttribute('type', 'isbn');
        } //ISBN-10
        else if (strlen($element->nodeValue) == 13 && strpos($element->nodeValue, '-') !== false && substr($element->nodeValue, 0, 1) == "0" || substr($element->nodeValue, 0, 1) == "1" || strlen($element->nodeValue) == 10 && substr($element->nodeValue, 0, 1) == "0" || substr($element->nodeValue, 0, 1) == "1") {
            $rootElement->appendChild($identifier);
            $identifier->setAttribute('type', 'isbn');
        } //ISBN-13
        else if (strlen($element->nodeValue) == 17 && strpos($element->nodeValue, '-') !== false && substr($element->nodeValue, 0, 3) == "978" || substr($element->nodeValue, 0, 3) == "979" || strlen($element->nodeValue) == 13 && substr($element->nodeValue, 0, 3) == "978" || substr($element->nodeValue, 0, 3) == "979") {
            $rootElement->appendChild($identifier);
            $identifier->setAttribute('type', 'isbn');
        } else if (strtoupper(substr($element->nodeValue, 0, 4)) == "ISRC" || strlen($element->nodeValue) == 12 && $iso3166Checked == 1 || strlen($element->nodeValue) == 15 && strpos($element->nodeValue, '-') !== false || strpos($element->nodeValue, '/') !== false && $iso3166Checked == 1) {
            $rootElement->appendChild($identifier);
            $identifier->setAttribute('type', 'isrc');
        } else if (strtoupper(substr($element->nodeValue, 0, 4)) == "ISSN" || strlen($element->nodeValue) == 9 && strpos($element->nodeValue, '-') !== false || strlen($element->nodeValue) == 8) {
            $rootElement->appendChild($identifier);
            $identifier->setAttribute('type', 'issn');
        } else if (strtoupper(substr($element->nodeValue, 0, 4)) == "ISTC") {
            $rootElement->appendChild($identifier);
            $identifier->setAttribute('type', 'istc');
        } else if (strtoupper(substr($element->nodeValue, 0, 4)) == "SICI" || substr($element->nodeValue, 0, 1) == "0" || substr($element->nodeValue, 0, 1) == "1" && strpos($element->nodeValue, ';') !== false && strpos($element->nodeValue, '(') !== false && strpos($element->nodeValue, ')') !== false && strpos($element->nodeValue, '<') !== false && strpos($element->nodeValue, '>') !== false) {
            $rootElement->appendChild($identifier);
            $identifier->setAttribute('type', 'sici');
        } else {
            $rootElement->appendChild($identifier);
            $identifier->setAttribute('type', 'local');
        }


    }
}
$progressLoop++;
$_SESSION["progress"] = $progressLoop / $progress * 100;
session_write_close();
session_start();
$novi_dokument->preserveWhiteSpace = false;
$novi_dokument->formatOutput = true;
$novi_dokument->save('uploaded/' . $folderName . '/output/' . $saveName . '_output.xml');
$poruke[] = "<i class='fa fa-check'></i> Datoteka " . $_FILES['upload']['name'][$index] . " je uspješno mapirana iz DC-a u MODS.";