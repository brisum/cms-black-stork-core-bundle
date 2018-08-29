<?php

namespace Brisum\Stork\Bundle\CoreBundle\Entity;

use Brisum\Stork\Bundle\CoreBundle\Entity\SeoDataEntity;
use Brisum\Stork\Bundle\CoreBundle\Entity\SeoDataTranslation;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Translatable;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;
use Sonata\TranslationBundle\Traits\Gedmo\PersonalTranslatableTrait;

/**
 * PageSeoData
 *
 * @ORM\Table(name="page_seo_data")
 * @ORM\Entity(repositoryClass="Brisum\Stork\Bundle\CoreBundle\Repository\PageSeoDataRepository")
 * @Gedmo\TranslationEntity(class="Brisum\Stork\Bundle\CoreBundle\Entity\PageSeoDataTranslation")
 * @Gedmo\Loggable(logEntryClass="Brisum\Stork\Bundle\CoreBundle\Entity\PageSeoDataLog")
 */
class PageSeoData extends SeoDataEntity implements TranslatableInterface
{
    use PersonalTranslatableTrait;

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

