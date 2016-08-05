<?php

namespace TypiCMS\Modules\Files\Models;

use TypiCMS\Modules\Core\Custom\Models\BaseTranslation;

class FileTranslation extends BaseTranslation
{
    /**
     * get the parent model.
     */
    public function owner()
    {
        return $this->belongsTo('TypiCMS\Modules\Files\Custom\Models\File', 'file_id')->withoutGlobalScopes();
    }
}
