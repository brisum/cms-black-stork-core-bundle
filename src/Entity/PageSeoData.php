<?php

namespace BlackStork\Core\Entity;

use BlackStork\Core\Util\EntityTranslatable;
use BlackStork\Core\Entity\SeoDataEntity;
use BlackStork\Core\Entity\SeoDataTranslation;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;

/**
 * PageSeoData
 *
 * @ORM\Table(name="page_seo_data")
 * @ORM\Entity(repositoryClass="BlackStork\Core\Repository\PageSeoDataRepository")
 * @Gedmo\TranslationEntity(class="BlackStork\Core\Entity\PageSeoDataTranslation")
 * @Gedmo\Loggable(logEntryClass="BlackStork\Core\Entity\PageSeoDataLog")
 */
class PageSeoData extends SeoDataEntity implements TranslatableInterface
{
    use EntityTranslatable;

    /**
     * @ORM\OneToMany(
     *   targetEntity="PageSeoDataTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }
}

