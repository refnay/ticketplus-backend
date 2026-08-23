<?php

namespace App\Shared\Infrastructure\Pdf\Dompdf;

use App\Shared\Application\Pdf\PdfDocument;
use App\Shared\Application\Pdf\PdfRenderer;
use Dompdf\Dompdf;
use Dompdf\Options;

final class DompdfPdfRenderer implements PdfRenderer
{
    public function render(string $html, PdfDocument $document): string
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->setPaper($document->paper(), $document->orientation());
        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf->output();
    }
}