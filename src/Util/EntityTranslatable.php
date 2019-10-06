<?php

namespace BlackStork\Core\Util;

use Sonata\TranslationBundle\Traits\Gedmo\PersonalTranslatableTrait;

trait EntityTranslatable
{
    use PersonalTranslatableTrait;

    public function getTranslations()
    {
        return $this->translations;
    }

    public function setTranslations(\Doctrine\Common\Collections\ArrayCollection $translations)
    {
        $this->translations = $translations;
        return $this;
    }

    public function addTranslation($translation)
    {
        $translation->setObject($this);
        $this->translations[] = $translation;
        return $this;
    }

    public function removeTranslation($translation)
    {
        $this->translations->removeElement($translation);
    }
}
