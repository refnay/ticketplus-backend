<?php

namespace App\Shared\Infrastructure\Pdf\Render;

use App\Shared\Domain\Pdf\PdfRenderer;
use Dompdf\Dompdf;
use Dompdf\Options;

final class DompdfPdfRenderer implements PdfRenderer
{
    public function render(string $html): string
    {
        $options = new Options();

        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}
