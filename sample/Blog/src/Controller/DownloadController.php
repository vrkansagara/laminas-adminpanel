<?php
namespace Blog\Controller;

use http\Exception\InvalidArgumentException;
use Laminas\Mvc\Controller\AbstractActionController;

class DownloadController extends AbstractActionController
{
    public function indexAction()
    {
        // Get the file name from GET variable
        $fileName = (string)$this->params()->fromQuery('name', '');
        trim($fileName);
        if (strlen($fileName) == 0) {
            throw new \Exception('File not found !');
            // Set 404 Not Found status code
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Take some precautions to Make file name secure
        $fileName = str_replace("/", "", $fileName);  // Remove slashes
        $fileName = str_replace("\\", "", $fileName); // Remove back-slashes

        // Try to open file
        $path = './data/download/' . $fileName;
        if (! is_readable($path)) {
            // Set 404 Not Found status code
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Get file size in bytes
        $fileSize = filesize($path);

        // Write HTTP headers
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine("Content-type: application/octet-stream");
        $headers->addHeaderLine("Content-Disposition: attachment; filename=\"" . $fileName . "\"");
        $headers->addHeaderLine("Content-length: $fileSize");
        $headers->addHeaderLine("Cache-control: private"); //use this to open files directly

        // Write file content
        $fileContent = file_get_contents($path);
        if ($fileContent != false) {
            $response->setContent($fileContent);
        } else {
            // Set 500 Server Error status code
            $this->getResponse()->setStatusCode(500);
            return;
        }

        // Return Response to avoid default view rendering
        return $this->getResponse();
    }
}
