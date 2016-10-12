<?php
if ($modsNS == "http://www.loc.gov/mods/v3") {
    $prefix = "mods:";
}
else if ($modsNS !== "http://www.loc.gov/mods/v3") {
    $prefix = false;
}
$novi_dokument = new DOMDocument('1.0', 'UTF-8');
$rootElement = $novi_dokument->createElement('simpledc');
$novi_dokument->appendChild($rootElement);
$rootElement->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:dc', 'http://purl.org/dc/elements/1.1/');
$rootElement->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
$rootElement->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance' ,'noNamespaceSchemaLocation', 'http://dublincore.org/schemas/xmls/qdc/2008/02/11/simpledc.xsd');

$elementi = $dokument->documentElement->childNodes;
$progressLoop++;
$_SESSION["progress"]= $progressLoop / $progress * 100;
session_write_close();
session_start();
if ($elementi->item(1)->nodeName == $prefix . "mods") {
	$elementi = $elementi->item(1)->childNodes;
}
$progressLoop++;
$_SESSION["progress"]= $progressLoop / $progress * 100;
foreach ($elementi as $element) {
    if ($element->nodeName == $prefix . "titleInfo") {
    	$title = $novi_dokument->createElement("dc:title", $element->childNodes->item(1)->nodeValue);
    	$rootElement->appendChild($title);
    }
    if ($element->nodeName == $prefix . "name") {
        $creator = false;
    	$childElements = $element->childNodes;
    	$values = array();
    	for ($i = 0; $i < $childElements->length; $i++) {
    		if ($childElements->item($i)->nodeName !== "#text" && $childElements->item($i)->nodeName !== $prefix . "role" && $childElements->item($i)->nodeName !== $prefix . "displayForm") {
    		$values[] = $childElements->item($i)->nodeValue;
            $value = implode(", ", $values);
    		}
            else if ($childElements->item($i)->nodeName !== "#text" && $childElements->item($i)->nodeName !== $prefix . "role" && $childElements->item($i)->nodeName == $prefix . "displayForm") {
            $value = $childElements->item($i)->nodeValue;
            }
    	}
    	foreach ($childElements as $childElement) {
    			if ($childElement->nodeName == $prefix . "role") {
    				    if ($childElement->childNodes->item(1)->nodeValue == "Creator" || $childElement->childNodes->item(1)->nodeValue == "creator" || $childElement->childNodes->item(1)->nodeValue == "cre" || $childElement->childNodes->item(1)->nodeValue == "Cre" || $childElement->childNodes->item(1)->nodeValue == "Author" || $childElement->childNodes->item(1)->nodeValue == "author" || $childElement->childNodes->item(1)->nodeValue == "aut" || $childElement->childNodes->item(1)->nodeValue == "Aut") {
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
     if ($element->nodeName == $prefix . "subject") {
     	$childElements = $element->childNodes;
    	$values = array();
    	for ($i = 0; $i < $childElements->length; $i++) {
    		if ($childElements->item($i)->nodeName !== "#text" && $childElements->item($i)->nodeName !== $prefix . "name" && $childElements->item($i)->nodeName !== $prefix . "geographic" || $childElements->item($i)->nodeName == $prefix . "temoporal" || $childElements->item($i)->nodeName == $prefix . "hierarchicalGeographic" || $childElements->item($i)->nodeName == $prefix . "cartographics") {
    		$values[] = $childElements->item($i)->nodeValue;
    		}
    		else if ($childElements->item($i)->nodeName !== "#text" && $childElements->item($i)->nodeName == $prefix . "name") {
    			for ($j = 0; $j < $childElements->item($i)->childNodes->length; $j++) {
    				if ($childElements->item($i)->childNodes->item($j)->nodeName !== "#text") {
    					$values[] = $childElements->item($i)->childNodes->item($j)->nodeValue;
    				}
    			}
    		}
    		if ($childElements->item($i)->nodeName == $prefix . "geographic" || $childElements->item($i)->nodeName == $prefix . "temoporal" || $childElements->item($i)->nodeName == $prefix . "hierarchicalGeographic" || $childElements->item($i)->nodeName == $prefix . "cartographics") {
    		$coverage = $novi_dokument->createElement("dc:coverage", $childElements->item($i)->nodeValue);
    		$rootElement->appendChild($coverage);
    		goto classification;
    		}
    	}
    	$value = implode("; ", $values);
    	$subject = $novi_dokument->createElement("dc:subject", $value);
    	$rootElement->appendChild($subject);
    }
    classification:
    if ($element->nodeName == $prefix . "classification") {
    	$subject = $novi_dokument->createElement("dc:subject", $element->nodeValue);
    	$rootElement->appendChild($subject);
    }
    if ($element->nodeName == $prefix . "abstract" || $element->nodeName == $prefix . "note" || $element->nodeName == $prefix . "tableOfContents") {
    	$description = $novi_dokument->createElement("dc:description", $element->nodeValue);
    	$rootElement->appendChild($description);
    }  
    if ($element->nodeName == $prefix . "originInfo") {
     	$childElements = $element->childNodes;
    	foreach ($childElements as $childElement) {
    		if ($childElement->nodeName == $prefix . "publisher") {
    			$publisher = $novi_dokument->createElement("dc:publisher", $childElement->nodeValue);
    			$rootElement->appendChild($publisher);
    		}
    		else if ($childElement->nodeName == $prefix . "dateIssued" || $childElement->nodeName == $prefix . "dateCreated" || $childElement->nodeName == $prefix . "dateCaptured" || $childElement->nodeName == $prefix . "dateOther") {
    			$date = $novi_dokument->createElement("dc:date", $childElement->nodeValue);
    			$rootElement->appendChild($date);
    		}

    	}
 
    }
    if ($element->nodeName == $prefix . "genre" && $element->getAttribute('authority') == "dct") {
    $type = $novi_dokument->createElement("dc:type", $element->nodeValue);
    $rootElement->appendChild($type);
    }
    else if ($element->nodeName == $prefix . "genre" && $element->getAttribute('authority') !== "dct") {
    if ($element->previousSibling->previousSibling->nodeName == $prefix . "typeOfResource") {
    	$type = $novi_dokument->createElement("dc:type", $element->previousSibling->previousSibling->nodeValue);
    	$rootElement->appendChild($type);
    }
    $type = $novi_dokument->createElement("dc:type", $element->nodeValue);
    $rootElement->appendChild($type);    
    }
    if ($element->nodeName == $prefix . "physicalDescription") {
    $childElements = $element->childNodes;
    for ($i = 0; $i < $childElements->length; $i++) {
    		if ($childElements->item($i)->nodeName !== "#text" && $childElements->item($i)->nodeName == $prefix . "internetMediaType" || $childElements->item($i)->nodeName == $prefix . "extent" || $childElements->item($i)->nodeName == $prefix . "form") {
    		$format = $novi_dokument->createElement("dc:format", $childElements->item($i)->nodeValue);
   			$rootElement->appendChild($format);
    		}
    }
  }
  if ($element->nodeName == $prefix . "location") {
    $childElements = $element->childNodes;
    for ($i = 0; $i < $childElements->length; $i++) {
    		if ($childElements->item($i)->nodeName !== "#text") {
    		$identifier = $novi_dokument->createElement("dc:identifier", $childElements->item($i)->nodeValue);
   			$rootElement->appendChild($identifier);
    		}
    }
  } 
  if ($element->nodeName == $prefix . "identifier") {
    	$identifier = $novi_dokument->createElement("dc:identifier", $element->nodeValue);
   		$rootElement->appendChild($identifier);
    } 
  if ($element->nodeName == $prefix . "language") {
    $childElements = $element->childNodes;
    for ($i = 0; $i < $childElements->length; $i++) {
    		if ($childElements->item($i)->nodeName !== "#text") {
    		$language = $novi_dokument->createElement("dc:language", $childElements->item($i)->nodeValue);
   			$rootElement->appendChild($language);
    		}
    }
  } 
  if ($element->nodeName == $prefix . "relatedItem") {
     	$childElements = $element->childNodes;
        $value = array();
    	for ($i = 0; $i < $childElements->length; $i++) {
    		if ($childElements->item($i)->nodeName !== "#text" && $childElements->item($i)->nodeName == $prefix . "identifier") {
    		$relation = $novi_dokument->createElement("dc:relation", $childElements->item($i)->nodeValue);
    		$rootElement->appendChild($relation);
    		}
    		if ($childElements->item($i)->nodeName !== "#text" && $childElements->item($i)->nodeName == $prefix . "titleInfo") {
    			for ($j = 0; $j < $childElements->item($i)->childNodes->length; $j++) {
    				if ($childElements->item($i)->childNodes->item($j)->nodeName !== "#text") {
    					$relation = $novi_dokument->createElement("dc:relation", $childElements->item($i)->childNodes->item($j)->nodeValue);
    					$rootElement->appendChild($relation);
    				}
    			}
    		}
            if ($childElements->item($i)->nodeName !== "#text" && $childElements->item($i)->nodeName == $prefix . "location") {
                for ($k = 0; $k < $childElements->item($i)->childNodes->length; $k++) {
                    if ($childElements->item($i)->childNodes->item($k)->nodeName !== "#text") {
                        $relation = $novi_dokument->createElement("dc:relation", $childElements->item($i)->childNodes->item($k)->nodeValue);
                        $rootElement->appendChild($relation);
                    }
                }
            }
    	}
    } 
   if ($element->nodeName == $prefix . "accessCondition") {
    	$rights = $novi_dokument->createElement("dc:rights", $element->nodeValue);
   		$rootElement->appendChild($rights);
    } 
    if ($element->nodeName == $prefix . "recordInfo") {
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
$_SESSION["progress"]= $progressLoop / $progress * 100;
session_write_close();
session_start();
$novi_dokument->preserveWhiteSpace = false;
$novi_dokument->formatOutput = true;
$novi_dokument->save('uploaded/' . $folderName . '/output/' . $saveName .'_output.xml');
$poruke[] = "<i class='fa fa-check'></i> Datoteka " . $_FILES['upload']['name'][$index] . " je uspješno mapirana iz MODS-a u DC.";