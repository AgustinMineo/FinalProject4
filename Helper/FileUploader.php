<?php
namespace Helper;
use \Exception as Exception;
class FileUploader
{
    private $baseUploadDir;

    public function __construct($baseUploadDir)
    {
        $this->baseUploadDir = rtrim($baseUploadDir, '/') . '/';
    }

    public function uploadFiles($files, $subFolder = '', $formatNameCallback = null)
{
    $uploadedFiles = [];

    $uploadDir = $this->baseUploadDir . trim($subFolder, '/') . '/'; 

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true); 
    }

    if (!is_array($files['name'])) {
        $files = [
            'name' => [$files['name']],
            'type' => [$files['type']],
            'tmp_name' => [$files['tmp_name']],
            'error' => [$files['error']],
            'size' => [$files['size']]
        ];
    }

    foreach ($files['name'] as $key => $name) {
        if ($files['error'][$key] === UPLOAD_ERR_OK) {
            $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $newFileName = $name;

            if (is_callable($formatNameCallback)) {
                $newFileName = $formatNameCallback($files, $key);
            }

            if (substr($newFileName, -1) === '.') {
                $newFileName = rtrim($newFileName, '.');
            }

            $targetFile = $uploadDir . $newFileName;

            if (!$this->isValidFileType($name)) {
                throw new Exception("Tipo de archivo no permitido: " . $name);
            }

            if ($files['size'][$key] > 10 * 1024 * 1024) { 
                throw new Exception("El archivo es demasiado grande: " . $name);
            }

            if (!file_exists($files['tmp_name'][$key])) {
                throw new Exception("Archivo temporal no encontrado: " . $files['tmp_name'][$key]);
            }

            if (file_exists($targetFile)) {
                unlink($targetFile); 
            }

            if (!move_uploaded_file($files['tmp_name'][$key], $targetFile)) {
                $error = error_get_last()['message'];
                throw new Exception("Error al subir el archivo '{$name}': {$error} (Destino: {$targetFile})");
            } else {
                $uploadedFiles[] = $targetFile;
            }
        } else {
            throw new Exception("Error en el archivo '{$name}': " . $files['error'][$key]);
        }
    }

    return $uploadedFiles; 
}


    private function isValidFileType($filename)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'mp4', 'avi', 'mov','webp'];

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($extension, $allowedExtensions);
    }
}
