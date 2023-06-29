<?php
namespace Victor\TemplatePlotter;
use ZipArchive;


class Compacta
{
    private ZipArchive $zip;

    public function __construct(private string $filename)
    {
        // Crie um arquivo zip
        $this->zip = new ZipArchive();
        $this->filename = $filename . '.zip';
        if ($this->zip->open($this->filename, ZipArchive::CREATE) !== TRUE) {
            exit("Não foi possível criar o arquivo zip\n");
        }
    }

    public function adicionaArquivo($file): void
    {
        $this->zip->addFile($file);
    }

    public function fechaZip(): void
    {
        $this->zip->close();
    }

    public function enviaZip(): void
    {
        // Envie o arquivo zip para o navegador
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . $this->filename);
        header('Content-Length: ' . filesize($this->filename));
        readfile($this->filename);

    }
}
?>