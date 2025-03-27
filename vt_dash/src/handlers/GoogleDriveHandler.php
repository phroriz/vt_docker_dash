<?php

namespace src\handlers;

use Google\Client;
use Google\Service\Drive;
use src\Config;

class GoogleDriveHandler
{
    private Drive $driveService;

    public function __construct()
    {
        $this->initializeDriveService();
    }

    /**
     * 🔹 Inicializa o serviço do Google Drive com autenticação OAuth 2.0
     */
    private function initializeDriveService()
    {
        $client = new Client();
        $client->setApplicationName("vt-admin-users");
        $client->setScopes([Drive::DRIVE_METADATA_READONLY]);

        // Verifica se o arquivo de credenciais existe antes de configurar
        $credentialsPath = Config::GOOGLE_KEY;
        if (!file_exists($credentialsPath)) {
            throw new \Exception("Arquivo de credenciais não encontrado: $credentialsPath");
        }

        $client->setAuthConfig($credentialsPath);
        $client->setAccessType('offline');

        $this->driveService = new Drive($client);
    }

    /**
     * 🔍 Busca múltiplas pastas no Google Drive de uma vez só.
     *
     * @param array $folderNames Lista de nomes das pastas a serem buscadas.
     * @return array Retorna um mapa com os detalhes das pastas encontradas.
     */
    public function getFolders(array $folderNames): array
    {
        if (empty($folderNames)) {
            return [];
        }

        // 🔹 Montar a query para buscar todas as pastas de uma vez
        $queryParts = array_map(fn($name) => "name contains '$name'", $folderNames);
        $query = "mimeType='application/vnd.google-apps.folder' and (" . implode(" or ", $queryParts) . ")";

        try {
            $results = $this->driveService->files->listFiles([
                'q' => $query,
                'fields' => 'files(id, name, mimeType, modifiedTime)'
            ]);

            $folders = [];
            foreach ($results->getFiles() as $folder) {
                $fileDetails = $this->getFolderFileDetails($folder->getId());
                $folders[$folder->getName()] = [
                    'id' => $folder->getId(),
                    'name' => $folder->getName(),
                    'mimeType' => $folder->getMimeType(),
                    'lastUpdated' => $folder->getModifiedTime(),
                    'fileCount' => $fileDetails['fileCount'],
                    'totalSizeGB' => $fileDetails['totalSizeGB'],
                ];
            }

            return $folders;
        } catch (\Exception $e) {
            error_log("Erro ao buscar pastas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * 📂 Obtém detalhes dos arquivos dentro de uma pasta (quantidade, tamanho e última atualização)
     *
     * @param string $folderId ID da pasta no Google Drive.
     * @return array Retorna a contagem de arquivos, tamanho total em GB e última modificação.
     */
    private function getFolderFileDetails(string $folderId): array
    {
        $fileCount = 0;
        $totalSizeBytes = 0;
        $latestUpdate = null;

        try {
            $query = "'$folderId' in parents and trashed = false";
            $results = $this->driveService->files->listFiles([
                'q' => $query,
                'fields' => 'files(size, modifiedTime)'
            ]);

            foreach ($results->getFiles() as $file) {
                $fileCount++;
                $totalSizeBytes += $file->getSize() ?? 0;

                if (!$latestUpdate || $file->getModifiedTime() > $latestUpdate) {
                    $latestUpdate = $file->getModifiedTime();
                }
            }
        } catch (\Exception $e) {
            error_log("Erro ao buscar arquivos da pasta: " . $e->getMessage());
        }

        return [
            'fileCount' => $fileCount,
            'totalSizeGB' => round($totalSizeBytes / (1024 ** 3), 2), // Converte bytes para GB
            'latestUpdate' => $latestUpdate
        ];
    }
}
