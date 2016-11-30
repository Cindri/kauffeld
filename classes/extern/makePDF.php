<?php

function replace($str)
{
	$str = str_replace("[leer]", "", $str);
	$str = str_replace("[br]", "\n", $str);
	$str = str_replace("&amp;", "&", $str);
	$str = str_replace('\"', '"', $str);
	return $str;
}

function createPDFOutput($pdfData)
{
	$p = PDF_new();
	if (PDF_begin_document($p, "", "") == 0)
	{
		die("Error: " . PDF_get_errmsg($p));
	}

	PDF_set_info($p, "Author", "Metzgerei Kauffeld"); // Allgemeine Dateiinformationen setzen
	PDF_set_info($p, "Title", $pdfData['header']);
	PDF_set_info($p, "Creator", "PDFlib Version 8, Code: David Peter");
	PDF_set_info($p, "Subject", "Speisekartenversand");
	PDF_set_parameter($p, "FontOutline", "t=calibril.ttf");
	PDF_set_parameter($p, "FontOutline", "x=calibri.ttf");
	PDF_set_parameter($p, "FontOutline", "y=calibrib.ttf");
	PDF_set_parameter($p, "FontOutline", "z=calibrii.ttf");
	PDF_set_parameter($p, "charref", "true");
	PDF_set_parameter($p, "textformat", "bytes");
	PDF_begin_page_ext($p, 595, 842, "");
	PDF_load_font($p, "x", "utf8", "");
	PDF_load_font($p, "y", "utf8", "");
	PDF_load_font($p, "z", "utf8", "");

	$image = PDF_load_image($p, "auto", $pdfData['backgroundImage'], "") OR die(PDF_get_errmsg($p)); // Bild als Hintergrund verwenden!
	PDF_fit_image($p, $image, 3, 0, "boxsize {587 838} position {0 0} fitmethod meet");

	$lly = 700;
	$leftIndent = 64;

	// Preis
	$rightColumn = 440;

	// Für Karten mit Startdatum: Andere Headline!
	if (!empty($pdfData['startDate'])) {
		$startDate = new DateTime($pdfData['startDate']);
		$endDate = new DateTime($pdfData['endDate']);
        $pageSubhead = "vom ".$startDate->format("d.m.Y")." bis ".$endDate->format("d.m.Y");
	}

	$opts = "fontname={y} fontsize=".$pdfData['fontSizeBigHead']." encoding=utf8 alignment=center leading=120%";

	$textflow = PDF_add_textflow($p, 0, $pdfData['header'], $opts);
	PDF_fit_textflow($p, $textflow, 50, $lly-10, 545, $lly+20, "fitmethod=clip firstlinedist=leading");
	$lly -= 34;

	if (!empty($pageSubhead)) {
		$opts = "fontname={x} fontsize=".$pdfData['fontSizeSubhead']." encoding=utf8 alignment=center leading=100%";
		$textflow = PDF_add_textflow($p, 0, $pageSubhead, $opts);
		PDF_fit_textflow($p, $textflow, 50, $lly-10, 545, $lly+20, "fitmethod=clip firstlinedist=leading");
		$lly = $lly - 21;
	}

	// Adresse
	$opts = "fontname={t} fontsize=".$pdfData['fontSizeText']." encoding=utf8 alignment=center leading=100%";
	$textflow = PDF_add_textflow($p, 0, $pdfData['adresse'], $opts);
	PDF_fit_textflow($p, $textflow, 50, $lly-10, 545, $lly+20, "fitmethod=clip firstlinedist=leading");
	$lly -= 17;

	// Öffnungszeiten
	$opts = "fontname={y} fontsize=".$pdfData['fontSizeText']." encoding=utf8 alignment=center leading=100%";
	$textflow = PDF_add_textflow($p, 0, $pdfData['mittagstisch'], $opts);
	PDF_fit_textflow($p, $textflow, 50, $lly-10, 545, $lly+20, "fitmethod=clip firstlinedist=leading");
	$lly -= 17;



	// Vorher einige Unterscheidungen, mal ist ein Start- und Enddatum gegeben, mal gibts keine "unit". Ansonsten wie gehabt.
	$headlineAlt = "";
	foreach ($pdfData['entries'] as $value)
	{
        if (empty($value['title'])) {
            continue;
        }
		if ($value['headline'] != $headlineAlt && $value['headline'] != "Angebot")
		{
            $lly -= 4;
			$opts = "fontname={y} fontsize=".$pdfData['fontSizeEntryHead']." encoding=utf8 alignment=left leading=120%";
			$textflow = PDF_add_textflow($p, 0, $value['headline'], $opts); // Tag
			PDF_fit_textflow($p, $textflow, $leftIndent, $lly-12, 585, $lly+12, "fitmethod=clip firstlinedist=leading");
			$headlineAlt = $value['headline'];
            $lly -= $pdfData['entryHeadMargin'];
		}

		$price = empty($value['unit']) ? $value['price'] . " €" : $value['price'] . " €" . (empty($value['unit']) ? "" : " / " . $value['unit']);

		$optsName = "fontname={x} fontsize=".$pdfData['fontSizeText']." fontstyle=bold encoding=utf8 fillcolor=red";
		$optsDescr = "fontname={z} fontsize=".$pdfData['fontSizeText']." encoding=utf8";
		$optsPrice = "fontname={z} fontsize=".$pdfData['fontSizeText']." encoding=utf8 alignment=right";

        $width_name = PDF_info_textline($p, replace($value['title']) . " " . replace($price), "width", $optsName);
        $width_beschr = PDF_info_textline($p, replace($value['description']), "width", $optsDescr);
        $width_ges = round($width_name) + round($width_beschr) + 10;
        $xspace = 595 - $width_ges;
        $xstart = round(($xspace / 2));

        $textflow = PDF_add_textflow($p, 0, replace($value['title']), $optsName); // Name
        PDF_fit_textflow($p, $textflow, $leftIndent, $lly - 8, 585, $lly + 8, "fitmethod=clip firstlinedist=leading");

		$textflow = PDF_add_textflow($p, 0, replace($price), $optsPrice); // Preis
		PDF_fit_textflow($p, $textflow, $rightColumn, $lly - 8, 530, $lly + 8, "fitmethod=clip firstlinedist=leading");

        if (!empty($value['description'])) {
			$lly = $lly - $pdfData['lineHeight'];
            $textflow = PDF_add_textflow($p, 0, replace($value['description']), $optsDescr); // Beschreibung
            PDF_fit_textflow($p, $textflow, $leftIndent, $lly - 8, 585, $lly + 8, "fitmethod=clip firstlinedist=leading");

        }

        $lly -= $pdfData['entryMargin'];
	}

	// Ende Content

	// Start Filialen-Texte, wenn vorhanden!
	if (!empty($pdfData['footerAdressen'])) {

		$lly -= 32;

		$opts = "fontname={y} fontsize=".$pdfData['fontSizeEntryHead']." encoding=utf8 alignment=center fillcolor=red leading=120%";
		$textflow = PDF_add_textflow($p, 0, "Hauptgeschäft Baden-Baden Oos", $opts);
		PDF_fit_textflow($p, $textflow, 50, $lly-10, 545, $lly+20, "fitmethod=clip firstlinedist=leading");
		$lly -= 22;
		$opts = "fontname={x} fontsize=".$pdfData['fontSizeText']." encoding=utf8 alignment=center leading=120%";
		$textflow = PDF_add_textflow($p, 0, $pdfData['footerAdressen']['hauptgeschaeft'], $opts);
		PDF_fit_textflow($p, $textflow, 50, $lly-10, 545, $lly+20, "fitmethod=clip firstlinedist=leading");
		$lly -= 16;
		$opts = "fontname={x} fontsize=".$pdfData['fontSizeText']." encoding=utf8 alignment=center leading=120%";
		$textflow = PDF_add_textflow($p, 0, $pdfData['footerZeiten']['hauptgeschaeft'], $opts);
		PDF_fit_textflow($p, $textflow, 50, $lly-10, 545, $lly+20, "fitmethod=clip firstlinedist=leading");

		$lly -= 30;

		$opts = "fontname={y} fontsize=".$pdfData['fontSizeEntryHead']." encoding=utf8 alignment=center fillcolor=red leading=120%";
		$textflow = PDF_add_textflow($p, 0, "Filiale Baden-Baden Weststadt", $opts);
		PDF_fit_textflow($p, $textflow, 50, $lly-10, 545, $lly+20, "fitmethod=clip firstlinedist=leading");
		$lly -= 22;
		$opts = "fontname={x} fontsize=".$pdfData['fontSizeText']." encoding=utf8 alignment=center leading=120%";
		$textflow = PDF_add_textflow($p, 0, $pdfData['footerAdressen']['rheinstrasse'], $opts);
		PDF_fit_textflow($p, $textflow, 50, $lly-10, 545, $lly+20, "fitmethod=clip firstlinedist=leading");
		$lly -= 16;
		$opts = "fontname={x} fontsize=".$pdfData['fontSizeText']." encoding=utf8 alignment=center leading=120%";
		$textflow = PDF_add_textflow($p, 0, $pdfData['footerZeiten']['rheinstrasse'], $opts);
		PDF_fit_textflow($p, $textflow, 50, $lly-10, 545, $lly+20, "fitmethod=clip firstlinedist=leading");

		$lly -= 30;

		$opts = "fontname={y} fontsize=".$pdfData['fontSizeEntryHead']." encoding=utf8 alignment=center fillcolor=red leading=120%";
		$textflow = PDF_add_textflow($p, 0, "Filiale Karlsruhe-Daxlanden", $opts);
		PDF_fit_textflow($p, $textflow, 50, $lly-10, 545, $lly+20, "fitmethod=clip firstlinedist=leading");
		$lly -= 22;
		$opts = "fontname={x} fontsize=".$pdfData['fontSizeText']." encoding=utf8 alignment=center leading=120%";
		$textflow = PDF_add_textflow($p, 0, $pdfData['footerAdressen']['daxlanden'], $opts);
		PDF_fit_textflow($p, $textflow, 50, $lly-10, 545, $lly+20, "fitmethod=clip firstlinedist=leading");
		$lly -= 16;
		$opts = "fontname={x} fontsize=".$pdfData['fontSizeText']." encoding=utf8 alignment=center leading=120%";
		$textflow = PDF_add_textflow($p, 0, $pdfData['footerZeiten']['daxlanden'], $opts);
		PDF_fit_textflow($p, $textflow, 50, $lly-10, 545, $lly+20, "fitmethod=clip firstlinedist=leading");

	}

	// Footer, Hinweise zur Abmeldung
	$opts = "fontname={x} fontsize=10 encoding=utf8 alignment=center leading=120%";
	$textflow = PDF_add_textflow($p, 0, $pdfData['footerText'], $opts);
	PDF_fit_textflow($p, $textflow, 50, 5, 545, 35, "fitmethod=clip firstlinedist=leading");

	PDF_end_page_ext($p, "");
	PDF_end_document($p, "");

	$output_pdf = PDF_get_buffer($p);
	$fileUrl = $pdfData['folder']."/".date("d_m_Y", time() + (60*60*24))."_".$pdfData['kartenID'].".pdf";
	if (!$file = fopen($fileUrl, "w+")) {
		die("Fehler beim Dateiöffnen");
	}
	$size = fwrite($file, $output_pdf);
	fclose($file);

	$returnarray = array("url" => $fileUrl, "size" => $size, "error" => pdf_get_errmsg($p));

	PDF_delete($p);
    return ($returnarray);
}