<?php

namespace App\Helper;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DomPrint
{
    const PAPER_A4 = "A4";
    const PAPER_A5 = "A5";
    const ORIENTATION_PORTRAIT = "portrait";
    const ORIENTATION_LAND = "landscape";

    private $templating;
    private $parameter;
    private $size = [
        "A4" => [
            "portrait" => ["width" => 100, "height" => 50],
            "landscape" => ["width" => 650, "height" => 50],
        ],
        "A5" => [
            "portrait" => ["width" => 100, "height" => 90], //90
            "landscape" => ["width" => 650, "height" => 50],
        ],
    ];

    public function __construct(\Twig\Environment $templating, ParameterBagInterface $parameterBagInterface)
    {
        $this->templating = $templating;
        $this->parameter = $parameterBagInterface;
    }

    function print($twig, $data = null, $name = "print", $orientation = null, $paper = "A4", $footer = true)
    {
        $orientation = $orientation ? $orientation : "portrait";
        $orientation = strtolower($orientation);
        $paper = strtoupper($paper);

        $data["footer"] = $footer;
        $size = $this->size[$paper][$orientation];
        $data["size"] = $size;
        $data["logo"]= base64_encode(file_get_contents($this->parameter->get('kernel.project_dir')."/public/assets/assets/logo/logo.png"));
        $html = $this->templating->render($twig, $data);
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial'); //Courier
        $pdfOptions->set('isRemoteEnabled', true);
        $pdfOptions->set('fontDir', $this->parameter->get('kernel.project_dir') . "/fonts");
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        ob_get_clean();
        $dompdf->stream($name . ".pdf", [
            "Attachment" => false,
        ]);
        
    }
}
