<?php

namespace TypiCMS\Modules\Files\Models;

use TypiCMS\Modules\Core\Shells\Models\BaseTranslation;

class FileTranslation extends BaseTranslation
{
    /**
     * get the parent model.
     */
    public function owner()
    {
        return $this->belongsTo('TypiCMS\Modules\Files\Shells\Models\File', 'file_id')->withoutGlobalScopes();
    }
}
