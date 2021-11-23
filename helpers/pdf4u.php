<?php
jimport('tcpdf.config.lang.nld');
jimport('tcpdf.tcpdf');
jimport('joomla.filesystem.folder');

class PDF4U
{
    public $font = 'helvetica';
    public $size = 9;
    private $pdf;

    public function __construct($template, $data = array())
    {
        $this->pdf = new TCPPDF_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);

        $this->pdf->font = $this->font;
        $this->pdf->size = $this->size;
        $this->pdf->template = $template;
        $this->pdf->data = $data;

        $this->pdf->SetMargins(PDF_MARGIN_LEFT, 40, PDF_MARGIN_RIGHT);
        $this->pdf->SetFont($this->font, '', $this->size, '', true);
        $this->pdf->AddPage();
        $this->pdf->setPageMark();
        $this->pdf->writeHTMLCell(0, 0, 15, 5, JLayoutHelper::render('pdf.' . $template . '.pdf', array('data' => $data), 0, 1));
    }

    public function download($folder, $name)
    {
        $this->output($folder, $name, 'D');
        die;
    }

    public function show()
    {
        $this->pdf->IncludeJS('print();');
        $this->output(null, null, 'I');
        die;
    }

    public function save($folder, $name)
    {
        return $this->output($folder, $name, 'F');
    }

    private function output($folder, $name, $type)
    {
        $name = $name . '.pdf';
        $output = '';

        if(in_array($type, array('D', 'F'))){
            $output = '/files/' . $folder . '/';
        }

        if (!JFolder::exists(JPATH_ROOT . $output)){
            JFolder::create(JPATH_ROOT . $output, 0755);
        }

        if($type == 'D') {
            $this->pdf->Output($name, $type);
        } else {
            $this->pdf->Output(JPATH_ROOT . '/images/' . $name, $type);
        }

        JFile::move(JPATH_SITE . '/images/' . $name, JPATH_SITE . $output . $name);

        return JPATH_ROOT . $output . $name;
    }
}

class TCPPDF_PDF extends TCPDF
{
    public $font;
    public $size;
    public $template;
    public $data;

    public function Header()
    {
        $this->SetFont($this->font, 'B', $this->size);

        if($this->data['header']){
            $this->writeHTMLCell(0, 0, 15, 15, JLayoutHelper::render('pdf.' . $this->template . '.pdf_header', array('data' => $this->data), 0, 1));
        }

        $this->SetAutoPageBreak(false, 0);

        $this->SetAutoPageBreak(true, 20);

        $this->setPageMark();
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont($this->font, 'I', $this->size);

        if($this->data['footer']){
            $this->writeHTMLCell(0, 0, 15, 275, JLayoutHelper::render('pdf.' . $this->template . '.pdf_footer', array('data' => $this->data), 0, 1));
        }

        $this->setPageMark();
    }
}