<?php
namespace T3Dev\Trainingcaces\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;

/***
 *
 * This file is part of the "Training Caces" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Dmitry Vasilev <dmitry@t3dev.ru>
 *
 ***/
/**
 * Category
 */
class Category extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * name
     *
     * @var string
     * @Extbase\Validate("NotEmpty")
     */
    protected $name = '';

    /**
     * name
     *
     * @var string
     */
    protected $shortName = '';

    /**
     * section
     *
     * @var string
     */
    protected $section = '';

    /**
     * type
     *
     * @var \T3Dev\Trainingcaces\Domain\Model\Type
     */
    protected $type = null;

    /**
     * description
     *
     * @var string
     */
    protected $description = '';

    /**
     * unique name
     *
     * @var string
     */
    protected $uniqueName = '';


    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getShortName(): string
    {
        return $this->shortName;
    }

    /**
     * @param string $shortName
     */
    public function setShortName(string $shortName): void
    {
        $this->shortName = $shortName;
    }

    /**
     * Returns the type
     *
     * @return \T3Dev\Trainingcaces\Domain\Model\Type $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type
     *
     * @param \T3Dev\Trainingcaces\Domain\Model\Type $type
     * @return void
     */
    public function setType(\T3Dev\Trainingcaces\Domain\Model\Type $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getSection(): string
    {
        return $this->section;
    }

    /**
     * @param string $section
     */
    public function setSection(string $section): void
    {
        $this->section = $section;
    }
    /**
     * @return string
     */
    public function getUniqueName(): string
    {
        return $this->uniqueName;
    }

    /**
     * @param string $uniqueName
     */
    public function setUniqueName(string $uniqueName): void
    {
        $this->uniqueName = $uniqueName;
    }
}
