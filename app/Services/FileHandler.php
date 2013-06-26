<?php

namespace Services;

/**
 * File Handler.
 * Used by controller to upload file.
 * Upload folder is so organized:
 * /upload
 *    /type
 *        /year
 *            /date
 * 
 * @author Gabriele D'Arrigo - @acirdesign
 */
class FileHandler {

    protected $file;
    protected $rootFolder;
    protected $typeFolder;
    protected $yearFolder;
    protected $lastFolder;
    protected $filePath;
    protected $maxFilesInFolder;

    /**
     * Set root folder, root type's subfolder (one for each type of uploaded file, like tattoo, sketch etc),
     * root type's subfolder, subdivided by years, 
     * path where files will be uploaded,
     * and maximum number of uploadable files for each folders. 
     * 
     * @param type $root
     * @param type $maxFilesInFolder
     */
    public function __construct($typeFolder, $maxFilesInFolder = 500) {
        $this->rootFolder = __DIR__ . '/../..' . UPLOAD_PATH . $typeFolder;
        $this->typeFolder = $typeFolder;
        $this->yearFolder = date('Y', time());

        // Calculate which is last folder (picked up by chronological order).
        $this->lastFolder = $this->checkLastFolder($this->rootFolder . '/' . $this->yearFolder);

        // Calculate which will be file system's uploaded file's folder.
        $this->filePath = $this->rootFolder . '/' . $this->yearFolder . '/' . $this->lastFolder;
        $this->maxFilesInFolder = $maxFilesInFolder;
    }

    /**
     * Check if a file is an image.
     * 
     * @param type $subject
     * @return boolean
     */
    protected function isImage($subject) {
        $pattern = '/[^\s]\.(jpg|png|gif|bmp)$/';

        if (preg_match($pattern, $subject)) {
            return true;
        } else {
            return false;
        };
    }

    /**
     * Return an istance of DirectoryIterator's class.
     * 
     * @param type $path
     * @return \DirectoryIterator
     */
    protected function getIterator($path) {
        return new \DirectoryIterator($path);
    }

    /**
     * Check which is the last sub folder in upload root's folder.
     * 
     * @param type $path
     * @return type
     */
    protected function checkLastFolder($path) {
        $listOfFolders = array();

        // Cycle through all subfolders in a given path (passed in as parameter).
        foreach ($this->getIterator($path) as $info) {
            if ($info->isDot() || !$info->isDir()) {
                continue;
            }
            array_push($listOfFolders, $info->getBasename());
        }
        // If no subfolder was found then create one with today's date.
        if (empty($listOfFolders)) {
            $lastFolder = date('Y-m-d', time());

            mkdir($path . '/' . $lastFolder, 0777);

            return $lastFolder;
        }
        // If found, order all folders by chronological date and return
        // the most recent.
        $orderedFolder = \Services\DateCompare::compare($listOfFolders);

        $lastFolder = array_shift($orderedFolder);

        return $lastFolder;
    }

    /**
     * Return a file name of type: name_id.extension
     * 
     * @param type $name
     * @param type $id
     * @return type
     */
    protected function getFileName($name, $id) {
        $fileNameExploded = explode('.', $name);

        return $fileNameExploded[0] . '_'
                . $id . '.'
                . $fileNameExploded[1];
    }

    /**
     * Iterate all over subfolder's image.
     * Return path of uploaded image.
     */
    protected function iterateImages() {
        $currentFilesNumber = 0;

        // Cycle through all file to check number of images present in folder.
        foreach ($this->getIterator($this->filePath) as $info) {
            if ($info->isDot() || $info->isDir()) {
                continue;
            }

            if ($info->isFile() && $this->isImage($info)) {
                $currentFilesNumber += 1;
            }
        }

        // If the limit for maximum number of images was not reached
        // than upload file to the folder.
        if ($currentFilesNumber < $this->maxFilesInFolder) {
            $filaName = $this->getFileName($this->file->getClientOriginalName(), $currentFilesNumber);

            $this->file->move($this->filePath, $filaName);

            return UPLOAD_PATH . $this->typeFolder . '/'
                    . $this->yearFolder . '/'
                    . $this->lastFolder . '/' . $filaName;
        } else {

            // If not create a new folder to upload the file.
            // The new folder's name is a date (corrispondent to lastFolder date), 
            // incremented by one day.            
            $this->lastFolder = date('Y-m-d', strtotime($this->lastFolder) + 86400);

            $newPath = $this->rootFolder . '/'
                    . $this->yearFolder . '/'
                    . $this->lastFolder;

            mkdir($newPath, 0777);
            
            // Set id file's name to 0.
            $filaName = $this->getFileName($this->file->getClientOriginalName(), 0);

            $this->file->move($newPath, $filaName);

            return UPLOAD_PATH . $this->typeFolder . '/'
                    . $this->yearFolder . '/'
                    . $this->lastFolder . '/' . $filaName;
        }
    }

    /**
     * Public method. 
     * Return the path of uploaded file.
     * 
     * @param type $file
     * @return type
     */
    public function handle($file) {
        $this->file = $file;

        $result = $this->iterateImages();

        return $result;
    }

}