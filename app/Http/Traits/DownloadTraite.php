<?php

namespace App\Http\Traits;

use App\Exceptions\UserException;
use App\Repositories\DownloadRepository;

trait DownloadTraite {


    public function loadVersionFile($index){

        // доступы аттракцион, root для admin или admin
       $this->service->attractionRootOrAdmin($this->access_attraction, $index);

        $download = (new DownloadRepository)->show($index['id']);

        if ($download->mime == 'application/zip'){
            return response()->download(storage_path($download->path));
        }

    throw new UserException(config('game.custom_value.download.1'), 3);
    }


}
