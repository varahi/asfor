<?php
namespace T3Dev\Trainingcaces\Utility;

use Exception;
use T3Dev\Trainingcaces\Domain\Model\FileReference;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Resource\DuplicationBehavior;
use TYPO3\CMS\Core\Resource\Exception\InsufficientFolderAccessPermissionsException;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\InaccessibleFolder;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use T3Dev\Trainingcaces\Domain\Model\FrontendUser;

class FileUtility
{

    /**
     * Contains the settings of the current extension
     *
     * @var array
     */
    protected $settings;

    /**
     * Initialize methods and form data
     *
     * @return
     */
    public function __construct()
    {
        $this->settings = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_trainingcaces.']['settings.'];
    }

    public function checkUploadDirectory($confTargetFolder)
    {
        $pathSite = Environment::getPublicPath();
        //$confTargetFolder = $this->settings['upload.']['userdir'];
        $targetFolder = $pathSite . $confTargetFolder .'/';

        if (!file_exists($targetFolder)) {
            GeneralUtility::mkdir_deep($targetFolder);
        }

        return $targetFolder;
    }

    /**
     * @return Folder|InaccessibleFolder
     * @throws Exception
     * @throws InsufficientFolderAccessPermissionsException
     */
    public function getImageStorageFolder($confTargetFolder, $storage)
    {
        $pathSite = Environment::getPublicPath();
        //$confTargetFolder = $this->settings['upload.']['userdir'];
        $userdirPath = $pathSite . $confTargetFolder .'/';

        if (!is_dir($userdirPath)) {
            mkdir($userdirPath, 0755);
        }

        //list($storageUid, $folderIdentifier) = GeneralUtility::trimExplode(':', $this->settings['upload.']['storage']);
        list($storageUid, $folderIdentifier) = GeneralUtility::trimExplode(':', $storage);

        $resourceFactory = ResourceFactory::getInstance();
        $storage = $resourceFactory->getStorageObject($storageUid);

        try {
            $folder = $storage->getFolder($folderIdentifier);
        } catch (Exception $exception) {
            return null;
        }

        return $folder;
    }

    /**
     * @param array $tmpName
     * @param integer $storagePid
     * @param string $tablenames
     * @param string $storagePath
     * @return FileReference|null
     * @throws Exception
     * @throws InsufficientFolderAccessPermissionsException
     */
    public function createFileReference($tmpName, $storagePid, $tablenames, $storagePath)
    {
        $currentDate = date("Ymd");

        $fileRef = null;
        $confTargetFolder = $this->settings['upload.']['userdir'];
        $storageFolder = $this->getImageStorageFolder($confTargetFolder, $storagePath);

        if ($storageFolder) {
            $conflictMode = DuplicationBehavior::RENAME;
            $fileNameCompleteName = 'user_' . $currentDate . $tmpName;
            $fileObj = $storageFolder->addFile($tmpName, $fileNameCompleteName, $conflictMode);
            if ($fileObj) {
                /** @var FileReference $fileRef */
                $fileRef = GeneralUtility::makeInstance(FileReference::class);
                $fileRef->setOriginalResource($fileObj, $storagePid);
                $fileRef->setTablenames($tablenames);
            }
        }

        return $fileRef;
    }

    /**
     * @param array $tmpName
     * @param integer $storagePid
     * @param string $tablenames
     * @return FileReference|null
     * @throws Exception
     * @throws InsufficientFolderAccessPermissionsException
     */
    public function createPdfFileReference($tmpName, $storagePid, $tablenames)
    {
        $currentDate = date("Ymd");

        $fileRef = null;
        $confTargetFolder = $this->settings['upload.']['pdfdir'];
        $storage = $this->settings['upload.']['pdfStorage'];
        $storageFolder = $this->getImageStorageFolder($confTargetFolder, $storage);

        if ($storageFolder) {
            $conflictMode = DuplicationBehavior::RENAME;
            $fileNameCompleteName = 'pdf_' . $currentDate . $tmpName;
            $fileObj = $storageFolder->addFile($tmpName, $fileNameCompleteName, $conflictMode);
            if ($fileObj) {
                /** @var FileReference $fileRef */
                $fileRef = GeneralUtility::makeInstance(FileReference::class);
                $fileRef->setOriginalResource($fileObj, $storagePid);
                $fileRef->setTablenames($tablenames);
            }
        }

        return $fileRef;
    }

    /**
     * @param string $fieldname
     * @return string
     */
    public function uploadFile($data, $user)
    {

        // Check if directory non exist
        $confTargetFolder = $this->settings['upload.']['userdir'];
        //$targetFolder = $this->checkUploadDirectory($confTargetFolder);
        $this->checkUploadDirectory($confTargetFolder);

        $storagePid = $this->settings['user.']['storagePid'];

        // Obtain the original content (usually binary data)
        $bin = base64_decode($data->image);

        // Gather information about the image using the GD library
        $size = getImageSizeFromString($bin);

        // Check the MIME type to be sure that the binary data is an image
        if (empty($size['mime']) || strpos($size['mime'], 'image/') !== 0) {
            die('Base64 value is not a valid image');
        }

        // Mime types are represented as image/gif, image/png, image/jpeg, and so on
        // Therefore, to extract the image extension, we subtract everything after the
        $ext = substr($size['mime'], 6);

        // Make sure that you save only the desired file extensions
        if (!in_array($ext, ['png', 'gif', 'jpeg', 'jpg'])) {
            die(LocalizationUtility::translate('tx_trainingcaces.unsupported_image', 'trainingcaces'));
        }

        //$imgFile = $targetFolder. "$data->login" . '.' .$ext;
        $tmpFile = "$data->name" . '.' .$ext;

        // Save binary data as raw data (that is, it will not remove metadata or invalid contents)
        // In this case, the PHP backdoor will be stored on the server
        file_put_contents($tmpFile, $bin);

        $storagePath = $this->getStoragePath($user);
        $userDirectory = $this->getUserDirectory($user);
        $this->checkUploadDirectory($userDirectory);
        $fileRef = $this->createFileReference($tmpFile, $storagePid, 'fe_users', $storagePath);
        $user->setPhoto($fileRef);
    }

    /**
     * function eventDirectory
     *
     * @param FrontendUser $user
     * @return string
     */
    public function getStoragePath(FrontendUser $user)
    {
        if ($user->getCrdate() instanceof \DateTime) {
            $directoryTstamp = $user->getCrdate()->getTimestamp();
        } else {
            $directoryTstamp = $user->getCrdate();
        }

        $directoryTime = date('mdy', $directoryTstamp);
        $directory = 'user_' . $user->getUid() .'/'. 'exam_date_' . $directoryTime ;

        $storage = $this->settings['upload.']['storage'];
        $storagePath = $storage .'/'. $directory;
        return $storagePath;
    }

    /**
     * function eventDirectory
     *
     * @param FrontendUser $user
     * @return string
     */
    public function getUserDirectory(FrontendUser $user)
    {
        if ($user->getCrdate() instanceof \DateTime) {
            $directoryTstamp = $user->getCrdate()->getTimestamp();
        } else {
            $directoryTstamp = $user->getCrdate();
        }

        $directoryTime = date('mdy', $directoryTstamp);
        $directory = 'user_' . $user->getUid() .'/'. 'exam_date_' . $directoryTime ;

        $storage = $this->settings['upload.']['userdir'];
        $storagePath =  $storage .'/'. $directory;
        return $storagePath;
    }

    /**
     * @param string $fieldname
     * @return string
     */
    public function uploadPdfFile($data, $exam, $propertyMethod, $theory, $practice)
    {

        // Check if directory non exist
        $confTargetFolder = $this->settings['upload.']['pdfdir'];
        $this->checkUploadDirectory($confTargetFolder);

        $storagePid = $this->settings['exam.']['storagePid'];

        // Obtain the original content (usually binary data)
        if ($theory == 1) {
            $bin = base64_decode($data->theory_pdf, true);
        }

        if ($practice == 1) {
            $bin = base64_decode($data->practice_pdf, true);
        }

        # Perform a basic validation to make sure that the result is a valid PDF file
        # Be aware! The magic number (file signature) is not 100% reliable solution to validate PDF files
        # Moreover, if you get Base64 from an untrusted source, you must sanitize the PDF contents
        //if (strpos($bin, '%PDF') !== 0) {
        //    throw new Exception('Missing the PDF file signature');
        //}

        // Gather information about the image using the GD library
        //$size = getImageSizeFromString($bin);

        // Check the MIME type to be sure that the binary data is an image
        //if (empty($size['mime']) || strpos($size['mime'], 'application/') !== 0) {
        //    die('Base64 value is not a valid file');
        //}

        // Mime types are represented as image/gif, image/png, image/jpeg, and so on
        // Therefore, to extract the image extension, we subtract everything after the
        //$ext = substr($size['mime'], 6);

        // Make sure that you save only the desired file extensions
        //if (!in_array($ext, ['pdf'])) {
        //    die(LocalizationUtility::translate('tx_trainingcaces.unsupported_file', 'trainingcaces'));
        //}

        //$tmpFile = "$data->user" . '.' .$ext;
        $ext = 'pdf';
        $tmpFile = "$data->user" . '.' .$ext;

        // Save binary data as raw data (that is, it will not remove metadata or invalid contents)
        // In this case, the PHP backdoor will be stored on the server
        file_put_contents($tmpFile, $bin);

        $fileRef = $this->createPdfFileReference($tmpFile, $storagePid, 'tx_trainingcaces_domain_model_exam');
        $exam->$propertyMethod($fileRef);
    }


    /**
     * Writes information into a csv-file
     *
     * @param \string $fileName
     * @param \string $record
     * @return void
     */
    private function writeCSVFileEntry($fileName, $record = '', $mode)
    {
        $documentRoot = Environment::getPublicPath();
        $fullFileName = $documentRoot . $fileName;

        if ($record) {
            $handle = fopen($fullFileName, $mode);
            fwrite($handle, utf8_decode($record));
            fclose($handle);
        }
    }

    private function getUserFullName($exam, $userType)
    {
        if ($exam->$userType()) {
            if ($exam->$userType()->getFirstName() && $exam->$userType()->getFirstName()) {
                $user = $exam->$userType()->getFirstName() . ' ' . $exam->$userType()->getLastName();
            } elseif ($exam->$userType()->getFirstName()) {
                $user = $exam->$userType()->getFirstName();
            } elseif ($exam->$userType()->getLastName()) {
                $user = $exam->$userType()->getLastName();
            } elseif ($exam->$userType()->getUsername()) {
                $user = $exam->$userType()->getUsername();
            } else {
                $user = '';
            }
        } else {
            $user = '';
        }
        return $user;
    }

    public function saveExamsCSVFile($exams, $filePathName, $headline)
    {
        $this->writeCSVFileEntry($filePathName, $headline."\r\n", 'w+');
        $tableColumn = GeneralUtility::trimExplode(';', $headline, true);
        if ($exams) {
            foreach ($exams as $exam) {
                if ($exam->getSessionDate()) {
                    $sessionDate = $exam->getSessionDate()->format('d/m/Y');
                } else {
                    $sessionDate = '';
                }
                if ($exam->getEnterpriceClient()) {
                    $enterpriceClient = $exam->getEnterpriceClient()->getName();
                } else {
                    $enterpriceClient = '';
                }
                if ($exam->getPlace()) {
                    $place = $exam->getPlace()->getName();
                } else {
                    $place = '';
                }

                $candidate = $this->getUserFullName($exam, 'getCandidate');
                $theoryTrainer = $this->getUserFullName($exam, 'getTheoryTrainer');
                $practiceTrainer = $this->getUserFullName($exam, 'getPracticeTrainer');

                if ($exam->getCandidate()->getDateOfBirth()) {
                    $dateOfBirth = $exam->getCandidate()->getDateOfBirth()->format('d/m/Y');
                } else {
                    $dateOfBirth = '';
                }

                if ($exam->getTheoryTestDate()) {
                    $theoryTestDate = $exam->getTheoryTestDate()->format('d/m/Y');
                } else {
                    $theoryTestDate = '';
                }

                if ($exam->getPracticeTestDate()) {
                    $practiceTestDate = $exam->getPracticeTestDate()->format('d/m/Y');
                } else {
                    $practiceTestDate = '';
                }

                if ($exam->getType()) {
                    $type = $exam->getType()->getName();
                } else {
                    $type = '';
                }

                if ($exam->getCategory()) {
                    $category = $exam->getCategory()->getName();
                } else {
                    $category = '';
                }

                $record = '';
                foreach ($tableColumn as $column) {
                    switch ($column) {
                        case 'Date de session':
                            $record .= $sessionDate.';';
                            break;
                        case 'Entreprise cliente':
                            $record .= $enterpriceClient.';';
                            break;
                        case 'Lieu du test':
                            $record .= $place.';';
                            break;
                        case 'Candidat':
                            $record .= $candidate.';';
                            break;
                        case 'Date de naissance':
                            $record .= $dateOfBirth.';';
                            break;
                        case 'Formateur test théorique':
                            $record .= $theoryTrainer.';';
                            break;
                        case 'Date test théorique':
                            $record .= $theoryTestDate.';';
                            break;
                        case 'Résultat test théorique':
                            $record .= $exam->getTheoryResult().';';
                            break;
                        case 'Formateur test pratique':
                            $record .= $practiceTrainer.';';
                            break;
                        case 'Date test pratique':
                            $record .= $practiceTestDate.';';
                            break;
                        case 'Résultat test pratique':
                            $record .= $exam->getPracticeResult().';';
                            break;
                        case 'Type de CACES':
                            $record .= $type.';';
                            break;
                        case 'Catégorie CACES':
                            $record .= $category.';';
                            break;
                        default:
                            $record .= ';';
                    }
                }
                $this->writeCSVFileEntry($filePathName, $record."\r\n", 'a+');
            }
        }
    }
}
