<?php

namespace App\Http\Controllers;

use App\Http\Requests\Download\ShowDownloadRequest;
use App\Http\Traits\DownloadTraite;
use App\Models\Download;
use App\Repositories\DownloadRepository;
use App\Services\HeaderCheckTokenAttractionServices;
use App\Services\Permissions\PermissionsSessionServices;
use Illuminate\Http\Request;

class DownloadController extends Controller {

    use DownloadTraite;

    protected $access_attraction;
    protected $service;


    public function __construct(Request $req) {
        // проверяет токен аттракциона в header и ищет его в таблице аттракцион
        if(!$this->access_attraction = (new HeaderCheckTokenAttractionServices())->checkToken($req)){
            // в другом случае авторизует по bearer token
            $this->middleware('auth:api');
        }

        // доступы
        $this->service = new PermissionsSessionServices();
    }


    /**
     * Загрузить файл
     * @param ShowDownloadRequest $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \App\Exceptions\UserException
     */
    public function show(ShowDownloadRequest $request) {

        $index = $request->validated();

    // загрузка файла новой версии приложения
    return $this->loadVersionFile($index);
    }


    /**
     * показать версию файла
     *
     * @param ShowDownloadRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkVersion(ShowDownloadRequest $request) {

        $index = $request->validated();

        // доступы аттракцион, root для admin или admin
        $this->service->attractionRootOrAdmin($this->access_attraction, $index);

        $download = (new DownloadRepository)->show($index['id']);

        return response()->json([
            'code' => 777,
            'status' => 'success',
            'message' => $download->version,
        ], 201);
    }










}
