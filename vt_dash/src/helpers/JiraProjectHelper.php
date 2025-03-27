<?php

namespace src\helpers;

use src\handlers\JiraApiHandler;

class JiraProjectHelper
{
    private JiraApiHandler $jiraApi;

    public function __construct(JiraApiHandler $jiraApi)
    {
        $this->jiraApi = $jiraApi;
    }

    /**
     * Obtém todos os projetos do Jira (SEM buscar usuários)
     */
    public function getProjects(): array
    {
        $response = $this->jiraApi->request("project/search");

        if (isset($response['error']) || empty($response['data']['values'])) {
            return [];
        }

        return array_map(fn($project) => $this->formatProjectData($project), $response['data']['values']);
    }

    /**
     * Obtém um projeto específico pelo ID (COM usuários)
     */
    public function getProjectById(string $projectId): ?array
    {
        $response = $this->jiraApi->request("project/$projectId");

        if (isset($response['error']) || empty($response['data'])) {
            return null;
        }

        $project = $this->formatProjectData($response['data']);
       
        $groupName = $project['title'];

        // Buscar usuários do grupo que tem o mesmo nome do projeto
        $project['users'] = $this->getUsersFromGroup($groupName);
      
        return $project;
    }

    /**
     * Obtém as últimas atividades (issues atualizadas recentemente) de um projeto
     */
    public function getRecentActivities(string $projectKey, int $limit = 10): array
    {
        // Consulta JQL para buscar as últimas atividades do projeto (ordenado por atualização)
        $jql = "project = $projectKey ORDER BY updated DESC";

        $response = $this->jiraApi->request("search", "POST", [
            "jql" => $jql,
            "maxResults" => $limit,
            "fields" => ["summary", "updated", "creator"]
        ]);

        if (isset($response['error']) || empty($response['data']['issues'])) {
            return [];
        }

        // Formata os dados das atividades
        return array_map(function ($issue) {
            return [
                "summary" => $issue['fields']['summary'] ?? 'Sem resumo',
                "updated" => $issue['fields']['updated'] ?? '',
                "creator" => $issue['fields']['creator']['displayName'] ?? 'Desconhecido',
            ];
        }, $response['data']['issues']);
    }

    /**
     * Formata os dados do projeto (SEM usuários)
     */
    private function formatProjectData(array $project): array
    {
        return [
            "id" => $project['id'] ?? '',
            "key" => $project['key'] ?? '',
            "title" => $project['name'] ?? 'Unnamed Project',
            "description" => $project['description'] ?? 'No description available.',
            "date" => date("Y-m-d"),
            "progress" => 0,
            "badge" => $project['projectCategory']['name'] ?? 'Ativo',
            "badgeColor" => "blue",
            "avatarProject" => [
                "src" => $project['avatarUrls']['48x48'] ?? "./assets/images/default-avatar.jpg",
                "alt" => $project['name'] ?? "Project Avatar"
            ],
        ];
    }

    /**
     * Obtém os usuários de um grupo no Jira
     */
    private function getUsersFromGroup(string $groupName): array
    {
        $groupResponse = $this->jiraApi->request("group/member?groupname=" . urlencode($groupName));

        if (isset($groupResponse['error']) || empty($groupResponse['data']['values'])) {
            return [];
        }

        return array_map(fn($user) => [
            "displayName" => $user['displayName'] ?? 'Unknown User',
            "emailAddress" => $user['emailAddress'] ?? 'Email não disponível',
            "avatar" => $user['avatarUrls']['48x48'] ?? "./assets/images/default-avatar.jpg"
        ], $groupResponse['data']['values']);
    }
}
