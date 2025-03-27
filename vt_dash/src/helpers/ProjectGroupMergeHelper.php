<?php
namespace src\helpers;

use src\handlers\GoogleDriveHandler;

class ProjectGroupMergeHelper
{
    private array $projects;
    private array $groups;
    private GoogleDriveHandler $driveHandler;

    public function __construct(array $projects, array $groups)
    {
        $this->projects = $projects;
        $this->groups = $groups;
        $this->driveHandler = new GoogleDriveHandler(); // 🔥 Inicializa o Google Drive Handler
    }

    /**
     * 🔄 Merge entre Projetos do Jira, Grupos do Google e Pastas do Google Drive.
     */
    public function mergeProjectsWithGroups(): array
    {
        $mergedProjects = [];

        // 🔍 Criar um array de **nomes de projetos** para buscar no Google Drive de uma vez
        $projectNames = array_map(fn($p) => $p['title'] ?? '', $this->projects);
        $driveFolders = $this->driveHandler->getFolders($projectNames); // 🚀 Busca otimizada

        // Criar um **mapa de pastas** (chave = nome do projeto, valor = dados da pasta)
        $folderMap = [];
        foreach ($driveFolders as $folder) {
            $folderMap[strtolower($folder['name'])] = $folder;
        }

        // 🔄 Itera sobre os projetos e vincula grupos e pastas do Drive
        foreach ($this->projects as $project) {
            $projectNameLower = strtolower($project['title'] ?? '');

            // 🔍 Buscar grupo do Google correspondente
            $matchedGroup = null;
            foreach ($this->groups as $group) {
                if (strtolower($group['name'] ?? '') === $projectNameLower) {
                    $matchedGroup = $group;
                    break;
                }
            }

            // 📂 Associar pasta do Google Drive usando o mapa criado
            $matchedFolder = $folderMap[$projectNameLower] ?? null;

            // 🔄 Adiciona os dados do projeto + grupo + pasta correspondente
            $mergedProjects[] = [
                'id' => $project['id'] ?? '',
                'key' => $project['key'] ?? '',
                'title' => $project['title'] ?? 'Projeto Desconhecido',
                'description' => $project['description'] ?? 'Sem descrição disponível.',
                'date' => $project['date'] ?? '',
                'progress' => $project['progress'] ?? 0,
                'badge' => $project['badge'] ?? '',
                'badgeColor' => $project['badgeColor'] ?? '',
                'avatarProject' => $project['avatarProject'] ?? [],

                // 🔗 Dados do Grupo Google vinculado
                'groupName' => $matchedGroup['name'] ?? null,
                'groupEmail' => $matchedGroup['email'] ?? null,

                // 📂 Dados da Pasta no Google Drive
                'folderId' => $matchedFolder['id'] ?? null,
                'folderName' => $matchedFolder['name'] ?? null,
                'fileCount' => $matchedFolder['fileCount'] ?? 0,
                'totalSizeGB' => $matchedFolder['totalSizeGB'] ?? 0.0,
                'lastUpdated' => $matchedFolder['lastUpdated'] ?? null,
            ];
        }

        return $mergedProjects;
    }
}
