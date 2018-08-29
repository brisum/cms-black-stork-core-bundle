<?php

namespace Brisum\Stork\Bundle\CoreBundle\Entity;

use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="seo_template_log")
 * @ORM\Entity(repositoryClass="Gedmo\Loggable\Entity\Repository\LogEntryRepository")
 *
 */
class SeoTemplateLog extends AbstractLogEntry
{
}