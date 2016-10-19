<?php
$novi_dokument = new DOMDocument('1.0', 'UTF-8');
$rootElement = $novi_dokument->createElement('simpledc');
$novi_dokument->appendChild($rootElement);
$rootElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:dc', 'http://purl.org/dc/elements/1.1/');
$rootElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
$rootElement->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance', 'noNamespaceSchemaLocation', 'http://dublincore.org/schemas/xmls/qdc/2008/02/11/simpledc.xsd');

$elementi = $dokument->documentElement->childNodes;
$progressLoop++;
$_SESSION["progress"] = $progressLoop / $progress * 100;
session_write_close();
session_start();
if ($elementi->item(1)->nodeName == $modsPrefix . "mods") {
    $elementi = $elementi->item(1)->childNodes;
}

foreach ($elementi as $element) {
    if ($element->nodeName == $modsPrefix . "titleInfo") {
        $title = $novi_dokument->createElement("dc:title", $element->childNodes->item(1)->nodeValue);
        $rootElement->appendChild($title);
    }
    if ($element->nodeName == $modsPrefix . "name") {
        $creator = false;
        $childElements = $element->childNodes;
        $values = array();
        for ($i = 0; $i < $childElements->length; $i++) {
            if ($childElements->item($i)->nodeName !== "#text" && $childElements->item($i)->nodeName !== $modsPrefix . "role" && $childElements->item($i)->nodeName !== $modsPrefix . "displayForm") {
                $values[] = $childElements->item($i)->nodeValue;
                $value = implode(", ", $values);
            } else if ($childElements->item($i)->nodeName !== "#text" && $childElements->item($i)->nodeName !== $modsPrefix . "role" && $childElements->item($i)->nodeName == $modsPrefix . "displayForm") {
                $value = $childElements->item($i)->nodeValue;
            }
        }
        foreach ($childElements as $childElement) {
            if ($childElement->nodeName == $modsPrefix . "role") {
                if (strtoupper($childElement->childNodes->item(1)->nodeValue) == "CREATOR" || strtoupper($childElement->childNodes->item(1)->nodeValue) == "CRE" || strtoupper($childElement->childNodes->item(1)->nodeValue) == "AUTHOR" || strtoupper($childElement->childNodes->item(1)->nodeValue) == "AUT") {
                    $creator = $novi_dokument->createElement("dc:creator", $value);
                    $rootElement->appendChild($creator);
                }
            }
        }
        if ($creator == false) {
            $contributor = $novi_dokument->createElement("dc:contributor", $value);
            $rootElement->appendChild($contributor);
        }

    }
    if ($element->nodeName == $modsPrefix . "subject") {
        $childElements = $element->childNodes;
        $values = array();
        $value = false;
        $mapirajSubject = false;
        $mapirajCoverage = false;
        for ($i = 0; $i < $childElements->length; $i++) {
            if ($childElements->item($i)->nodeName !== "#text" && $childElements->item($i)->nodeName != $modsPrefix . "name" && $childElements->item($i)->nodeName == $modsPrefix . "topic" || $childElements->item($i)->nodeName == $modsPrefix . "occupation") {
                $values[] = $childElements->item($i)->nodeValue;
                $value = implode(", ", $values);
                $mapirajSubject = true;
            } else if ($childElements->item($i)->nodeName !== "#text" && $childElements->item($i)->nodeName == $modsPrefix . "name") {
                for ($j = 0; $j < $childElements->item($i)->childNodes->length; $j++) {
                    if ($childElements->item($i)->childNodes->item($j)->nodeName !== "#text") {
                        $values[] = $childElements->item($i)->childNodes->item($j)->nodeValue;
                        $value = implode(", ", $values);
                        $mapirajSubject = true;
                    }
                }
            }
        }
        if ($mapirajSubject == true) {
            $subject = $novi_dokument->createElement("dc:subject", $value);
            $rootElement->appendChild($subject);
            $value = false;
        }

        for ($i = 0; $i < $childElements->length; $i++) {
            if ($childElements->item($i)->nodeName !== "#text" && $childElements->item($i)->nodeName == $modsPrefix . "geographic" || $childElements->item($i)->nodeName == $modsPrefix . "temporal" || $childElements->item($i)->nodeName == $modsPrefix . "hierarchicalGeographic" || $childElements->item($i)->nodeName == $modsPrefix . "cartographics") {
                $values[] = $childElements->item($i)->nodeValue;
                $value = implode(", ", $values);
                $mapirajCoverage = true;
            }
        }
        if ($mapirajCoverage == true) {
            $coverage = $novi_dokument->createElement("dc:coverage", $value);
            $rootElement->appendChild($coverage);
            $value = false;
        }
    }

    if ($element->nodeName == $modsPrefix . "classification") {
        $subject = $novi_dokument->createElement("dc:subject", $element->nodeValue);
        $rootElement->appendChild($subject);
    }
    if ($element->nodeName == $modsPrefix . "abstract" || $element->nodeName == $modsPrefix . "note" || $element->nodeName == $modsPrefix . "tableOfContents") {
        $description = $novi_dokument->createElement("dc:description", $element->nodeValue);
        $rootElement->appendChild($description);
    }
    if ($element->nodeName == $modsPrefix . "originInfo") {
        $childElements = $element->childNodes;
        foreach ($childElements as $childElement) {
            if ($childElement->nodeName == $modsPrefix . "publisher") {
                $publisher = $novi_dokument->createElement("dc:publisher", $childElement->nodeValue);
                $rootElement->appendChild($publisher);
            } else if ($childElement->nodeName == $modsPrefix . "dateIssued" || $childElement->nodeName == $modsPrefix . "dateCreated" || $childElement->nodeName == $modsPrefix . "dateCaptured" || $childElement->nodeName == $modsPrefix . "dateOther") {
                $date = $novi_dokument->createElement("dc:date", $childElement->nodeValue);
                $rootElement->appendChild($date);
            }

        }

    }
    if ($element->nodeName == $modsPrefix . "genre" && $element->getAttribute('authority') == "dct") {
        $type = $novi_dokument->createElement("dc:type", $element->nodeValue);
        $rootElement->appendChild($type);
    } else if ($element->nodeName == $modsPrefix . "genre" && $element->getAttribute('authority') !== "dct") {
        if ($element->previousSibling->previousSibling->nodeName == $modsPrefix . "typeOfResource") {
            $type = $novi_dokument->createElement("dc:type", $element->previousSibling->previousSibling->nodeValue);
            $rootElement->appendChild($type);
        }
        $type = $novi_dokument->createElement("dc:type", $element->nodeValue);
        $rootElement->appendChild($type);
    }
    if ($element->nodeName == $modsPrefix . "physicalDescription") {
        $childElements = $element->childNodes;
        for ($i = 0; $i < $childElements->length; $i++) {
            if ($childElements->item($i)->nodeName !== "#text" && $childElements->item($i)->nodeName == $modsPrefix . "internetMediaType" || $childElements->item($i)->nodeName == $modsPrefix . "extent" || $childElements->item($i)->nodeName == $modsPrefix . "form") {
                $format = $novi_dokument->createElement("dc:format", $childElements->item($i)->nodeValue);
                $rootElement->appendChild($format);
            }
        }
    }
    if ($element->nodeName == $modsPrefix . "location") {
        $childElements = $element->childNodes;
        for ($i = 0; $i < $childElements->length; $i++) {
            if ($childElements->item($i)->nodeName !== "#text") {
                $identifier = $novi_dokument->createElement("dc:identifier", $childElements->item($i)->nodeValue);
                $rootElement->appendChild($identifier);
            }
        }
    }
    if ($element->nodeName == $modsPrefix . "identifier") {
        $identifier = $novi_dokument->createElement("dc:identifier", $element->nodeValue);
        $rootElement->appendChild($identifier);
    }
    if ($element->nodeName == $modsPrefix . "language") {
        $childElements = $element->childNodes;
        for ($i = 0; $i < $childElements->length; $i++) {
            if ($childElements->item($i)->nodeName !== "#text") {
                $language = $novi_dokument->createElement("dc:language", $childElements->item($i)->nodeValue);
                $rootElement->appendChild($language);
            }
        }
    }
    if ($element->nodeName == $modsPrefix . "relatedItem") {
        $childElements = $element->childNodes;
        $value = array();
        for ($i = 0; $i < $childElements->length; $i++) {
            if ($childElements->item($i)->nodeName !== "#text" && $childElements->item($i)->nodeName == $modsPrefix . "identifier") {
                $relation = $novi_dokument->createElement("dc:relation", $childElements->item($i)->nodeValue);
                $rootElement->appendChild($relation);
            }
            if ($childElements->item($i)->nodeName !== "#text" && $childElements->item($i)->nodeName == $modsPrefix . "titleInfo") {
                for ($j = 0; $j < $childElements->item($i)->childNodes->length; $j++) {
                    if ($childElements->item($i)->childNodes->item($j)->nodeName !== "#text") {
                        $relation = $novi_dokument->createElement("dc:relation", $childElements->item($i)->childNodes->item($j)->nodeValue);
                        $rootElement->appendChild($relation);
                    }
                }
            }
            if ($childElements->item($i)->nodeName !== "#text" && $childElements->item($i)->nodeName == $modsPrefix . "location") {
                for ($k = 0; $k < $childElements->item($i)->childNodes->length; $k++) {
                    if ($childElements->item($i)->childNodes->item($k)->nodeName !== "#text") {
                        $relation = $novi_dokument->createElement("dc:relation", $childElements->item($i)->childNodes->item($k)->nodeValue);
                        $rootElement->appendChild($relation);
                    }
                }
            }
        }
    }
    if ($element->nodeName == $modsPrefix . "accessCondition") {
        $rights = $novi_dokument->createElement("dc:rights", $element->nodeValue);
        $rootElement->appendChild($rights);
    }
    if ($element->nodeName == $modsPrefix . "recordInfo") {
        $childElements = $element->childNodes;
        $values = array();
        for ($i = 0; $i < $childElements->length; $i++) {
            if ($childElements->item($i)->nodeName !== "#text") {
                $values[] = $childElements->item($i)->nodeValue;
            }
        }
        $value = implode("; ", $values);
        $rights = $novi_dokument->createElement("dc:rights", $value);
        $rootElement->appendChild($rights);
    }
}
$progressLoop++;
$_SESSION["progress"] = $progressLoop / $progress * 100;
session_write_close();
session_start();
$novi_dokument->preserveWhiteSpace = false;
$novi_dokument->formatOutput = true;
$novi_dokument->save('uploaded/' . $folderName . '/output/' . $saveName . '_output.xml');
$poruke[] = "<i class='fa fa-check'></i> Datoteka " . $_FILES['upload']['name'][$index] . " je uspješno mapirana iz MODS-a u DC.";