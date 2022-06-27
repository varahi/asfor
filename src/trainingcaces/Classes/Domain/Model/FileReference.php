<?php

namespace T3Dev\Trainingcaces\Domain\Model;

class FileReference extends \TYPO3\CMS\Extbase\Domain\Model\FileReference
{

    /**
     * Wir benötigen dieses Feld, damit Extbase Persistence das Objekt ordentlich persistiert.
     *
     * @var integer
     */
    protected $uidLocal;

    /**
     * @var string
     */
    protected $tableLocal = 'sys_file';

    /**
     * @var string
     */
    protected $tablenames = 'fe_users';


    /**
     * @param \TYPO3\CMS\Core\Resource\ResourceInterface $originalResource
     */
    public function setOriginalResource(\TYPO3\CMS\Core\Resource\ResourceInterface $originalResource)
    {
        $this->originalResource = $originalResource;
        $this->uidLocal         = (int)$originalResource->getUid();
    }

    /**
     * @return \TYPO3\CMS\Core\Resource\FileReference
     */
    public function getOriginalResource()
    {
        if ($this->originalResource === null) {
            $this->originalResource = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance()->getFileReferenceObject(
                $this->getUid()
            );
        }

        return $this->originalResource;
    }

    /**
     * @return int
     */
    public function getUidLocal()
    {
        return $this->uidLocal;
    }

    /**
     * @param int $uidLocal
     */
    public function setUidLocal($uidLocal)
    {
        $this->uidLocal = $uidLocal;
    }

    /**
     * @return string
     */
    public function getTableLocal()
    {
        return $this->tableLocal;
    }

    /**
     * @param string $tableLocal
     */
    public function setTableLocal($tableLocal)
    {
        $this->tableLocal = $tableLocal;
    }

    /**
     * @return string
     */
    public function getTablenames()
    {
        return $this->tablenames;
    }

    /**
     * @param string $tablenames
     */
    public function setTablenames($tablenames)
    {
        $this->tablenames = $tablenames;
    }

    /**
     * setFile
     *
     * @param \TYPO3\CMS\Core\Resource\File $falFile
     * @return void
     */
    public function setFile(\TYPO3\CMS\Core\Resource\File $falFile)
    {
        $this->originalFileIdentifier = (int)$falFile->getUid();
    }
}
