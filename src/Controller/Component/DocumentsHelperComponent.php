<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class DocumentsHelperComponent extends Component
{

    private $sessionDocuments = [];
    private $Documents;

    public function initialize(array $config) {
        parent::initialize($config);
        $Documents = $this->loadModel('Documents');
    }

    private function loadModel($model) {
        $this->$model = TableRegistry::get($model);
    }

    //FIXME allow ['png', 'jpg'] for other files (not reimbursement)

    public function startNewSession() {
        $this->sessionDocuments = [];
    }

    public function deleteSessionFiles() {
        foreach ($this->sessionDocuments as $document) {
            $folder = WWW_ROOT; //FIXME
            $name = $document->filename;
            $extension = $document->extension;
            $this->log('here. unlinking ' . $folder . $name, 'debug'); //FIXME remove
            \unlink($folder . $name . '.' . $extension);
        }
    }

    /**
    * Create a new document entity
    * @param array $file includes 'name' and 'tmp_name' keys
    * @param array $extensionsAllowed (dots excluded)
    * @param string $newName (optional)
    * @param string $folderName (optional)
    * @param bool $isDirectPath
    * 
    * @return mixed new document if successful, string error message otherwise
    */
    public function newDocumentEntity(array $file, array $extensionsAllowed, string $newName = null, string $folderName = "", bool $isDirectPath = false) {
        //FIXME include folder id too
        $originalName = $file['name'];
        $tmpName = $file['tmp_name'];
        if (!$this->hasExtension($originalName))
            return $this->extensionErrorMessage($extensionsAllowed);
        $extension = substr($originalName, strrpos($originalName, '.') + 1);
        if (!\in_array($extension, $extensionsAllowed, true))
            return $this->extensionErrorMessage($extensionsAllowed);
        
        $newName = is_null($newName) ? $originalName : $newName;
        if ($this->hasExtension($newName))
            $newName = substr($newName, 0, strrpos($newName, '.'));

        if (!$this->Documents->findByFilename($newName)->isEmpty())
            return "File with name " . $newName . " already exists. ";

        //$path = "upload/".Security::hash($myname).".".$extension;

        $destinationFolder = $isDirectPath ? $folderName : WWW_ROOT . $folderName;
        $destination = $destinationFolder . $newName . '.' . $extension;
        if (!move_uploaded_file($tmpName, $destination))
            return "File couldn't be saved. ";

        $data = [
            'filename' => $newName,
            'extension' => $extension,
        ];
        $document = $this->Documents->newEntity($data);
        $this->sessionDocuments[] = $document;
        return $document;
        //FIXME handle WWW_REIMBURSEMENTS
        //FIXME handle folders...
    }

    private function extensionErrorMessage($extensions) {
        $extensionsString = '';
        if (\count($extensions) > 0) {
            $extensionsString = $extensions[0];
            foreach ($extensions as $k => $extension) {
                if ($k == 0)
                    continue;
                $extensionsString .= ', ' . $extension;
            }
        }
        return "The uploaded file must have one of the following extensions: " . $extensionsString . ". ";
    }
    
    private function hasExtension($filename) {
        $dotIndex = strrpos($filename, '.');
        return $dotIndex !== false && $dotIndex < strlen($filename) - 1;
    }


    /*
    public function initialize(){
        parent::initialize();
        $this->Auth->allow(['index', 'upload', 'download', 'delete']);
    }

    public function index(){
        $files = $this->Files->find('all');
        $this->set('files', $files);
    }

    public function delete($id){
        $file = $this->Files->get($id);
        if(unlink(WWW_ROOT.$file->path)){
            $this->Files->delete($file);
            return $this->redirect(['action'=>'index']);
        }
    }

    public function download($id){
        $file = $this->Files->get($id);
        $path = WWW_ROOT.$file->path;
        $this->response->body(function() use($path){
            return file_get_contents($path);
        });
        return $this->response->withDownload($file->name);
    }

    */
}