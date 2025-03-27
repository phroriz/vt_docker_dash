<?php

namespace core;

use \core\Database;
use \ClanCats\Hydrahon\Builder;
use \ClanCats\Hydrahon\Query\Sql\FetchableInterface;

class Model
{
    protected static $_h;
    protected static $dbName = 'DB1'; // Define o banco de dados padrão
    protected static $tableName;      // Nome da tabela para consultas dinâmicas

    public function __construct()
    {
        self::_checkH();
    }

    /**
     * Inicializa o Hydrahon com a conexão do banco apropriado.
     */
    public static function _checkH()
    {
        if (self::$_h === null) {
            $connection = Database::getConnection(self::$dbName);
            self::$_h = new Builder('mysql', function ($query, $queryString, $queryParameters) use ($connection) {
                $statement = $connection->prepare($queryString);
                $statement->execute($queryParameters);

                // Retorna a última ID inserida caso seja um comando INSERT
                if ($query instanceof \ClanCats\Hydrahon\Query\Sql\Insert) {
                    return $connection->lastInsertId();
                }

                // Para consultas SELECT, retorna os resultados
                if ($query instanceof FetchableInterface) {
                    return $statement->fetchAll(\PDO::FETCH_ASSOC);
                }

                return $statement->rowCount(); // Para comandos UPDATE/DELETE, retorna o número de linhas afetadas
            });
        }

        self::$_h = self::$_h->table(self::getTableName());
    }


    /**
     * Define dinamicamente o banco de dados.
     */
    public static function setDatabase($dbName)
    {
        if (!in_array($dbName, ['DB1', 'DB2'])) {
            throw new \Exception("Banco de dados inválido: $dbName");
        }

        self::$dbName = $dbName;
        self::$_h = null; // Reinicializa a conexão para usar o novo banco
    }

    /**
     * Define dinamicamente o nome da tabela.
     */
    public static function setTable($table)
    {
        self::$tableName = $table;
        self::$_h = null; // Reinicializa a conexão para usar a nova tabela
    }

    /**
     * Valida o comando SQL contra ações detalhadas permitidas.
     *
     * @param string $sql O comando SQL fornecido.
     * @param array $allowedActions As permissões detalhadas permitidas.
     * @throws \Exception Se o comando não for permitido.
     */




    /**
     * Executa uma consulta SQL e retorna os resultados.
     */
    public static function executeSQL($sql, $tableName = null, &$error = null, array $allowedActions = [])
    {
        // Obtém a conexão com o banco de dados atual
        $connection = Database::getConnection(self::$dbName);

        try {
            //  Passo 1: Verifica comandos especiais (INFO)
            if (stripos(trim($sql), 'INFO') === 0) {
                return self::getSQLInfo($allowedActions);
            }

            //  Passo 2: Valida e sanitiza o comando SQL
            $processedSQL = self::preprocessSQL($sql, $tableName, $allowedActions);

            // Atualiza as variáveis com os valores processados
            $sql = $processedSQL['sql'];                    // SQL validado e processado
            $tableName = $processedSQL['tableName'];        // Nome da tabela processado (se aplicável)
            $bindings = $processedSQL['bindings'] ?? [];    // Parâmetros para consulta preparada

            //  Passo 3: Executa o comando SQL no banco de dados
            $stmt = $connection->prepare($sql);
            $stmt->execute($bindings); // Usa bindings para maior segurança

            //  Passo 4: Gera mensagem amigável com base no tipo de comando SQL
            $message = self::generateSQLMessage($sql, $stmt, $tableName);

            //  Passo 5: Retorna os resultados da execução
            return [
                'results' => $stmt->fetchAll(\PDO::FETCH_ASSOC),       // Registros retornados pela consulta
                'columns' => $tableName ? self::getColumns($tableName) : [], // Colunas da tabela (se aplicável)
                'message' => $message                                  // Mensagem amigável de sucesso
            ];
        } catch (\Exception $e) {
            //  Passo 6: Tratamento de erros e logs
            $error = $e->getMessage();                                // Captura o erro
            self::logSQL($sql, $tableName, $error);                   // Registra o erro no log

            return [
                'results' => [],                                       // Nenhum resultado em caso de erro
                'columns' => [],                                       // Nenhuma coluna retornada
                'message' => "Erro: {$error}"                          // Mensagem de erro amigável
            ];
        }
    }

    private static function preprocessSQL(string $sql, ?string $tableName, array $allowedActions): array
    {
        $processedSQL = [
            'sql' => $sql,
            'tableName' => $tableName,
            'bindings' => []
        ];



        //  Sanitiza nomes de tabelas e colunas
        if ($tableName) {
            $processedSQL['tableName'] = self::sanitizeTableName($tableName);
        }

        //  Processa SQL específico
        if (preg_match('/^INSERT\s+\{(.+)\}$/i', trim($sql), $matches)) {
            // Comando: INSERT {coluna1=valor1, coluna2=valor2}
            $dataString = $matches[1]; // Exemplo: "nome='Jose', idade=30"
            $dataPairs = explode(',', $dataString);
            $data = [];

            foreach ($dataPairs as $pair) {
                [$column, $value] = explode('=', $pair, 2);
                $column = self::sanitizeColumnName(trim($column));
                $value = trim($value, " '\"");
                $data[$column] = $value;
            }

            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_map(fn($col) => ':' . trim($col, '`'), array_keys($data)));

            $processedSQL['sql'] = "INSERT INTO `$tableName` ($columns) VALUES ($placeholders)";
            $processedSQL['bindings'] = $data;
        }

        if (preg_match('/^UPDATE\s+\{(.+)\}\s+WHERE\s+(.+)$/i', trim($sql), $matches)) {
            // Comando: UPDATE {coluna1=valor1} WHERE condição
            $dataString = $matches[1];
            $condition = $matches[2];
            $dataPairs = explode(',', $dataString);
            $data = [];

            foreach ($dataPairs as $pair) {
                [$column, $value] = explode('=', $pair, 2);
                $column = self::sanitizeColumnName(trim($column));
                $value = trim($value, " '\"");
                $data[$column] = $value;
            }

            $setClauses = implode(', ', array_map(fn($col) => "`$col` = :$col", array_keys($data)));

            $processedSQL['sql'] = "UPDATE `$tableName` SET $setClauses WHERE $condition";
            $processedSQL['bindings'] = $data;
        }

        return $processedSQL;
    }
    /**
     * Sanitiza o nome da coluna para evitar problemas de segurança ou sintaxe.
     *
     * - Remove aspas simples ou duplas.
     * - Substitui espaços por underscores (_).
     * - Verifica se o nome contém apenas caracteres válidos.
     * - Envolve o nome com backticks (`) para evitar conflitos com palavras reservadas.
     *
     * @param string $columnName O nome da coluna fornecido pelo usuário.
     * @return string O nome da coluna sanitizado.
     * @throws \Exception Se o nome da coluna for inválido.
     */
    private static function sanitizeColumnName(string $columnName): string
    {
        // Remove aspas simples e duplas
        $columnName = str_replace(["'", '"'], '', $columnName);

        // Substitui espaços por underscores (_)
        $columnName = str_replace(' ', '_', $columnName);

        // Verifica se o nome contém apenas caracteres válidos (letras, números, underscores)
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $columnName)) {
            throw new \Exception("O nome da coluna contém caracteres inválidos: `$columnName`.");
        }

        // Verifica se o nome está vazio após a sanitização
        if (trim($columnName) === '') {
            throw new \Exception("O nome da coluna não pode ser vazio.");
        }

        // Sempre envolve o nome da coluna em backticks (`) para evitar conflitos
        return "`" . $columnName . "`";
    }



    /**
     * Sanitiza o nome da coluna.
     *
     * - Remove aspas simples.
     * - Envolve nomes com espaços em backticks.
     *
     * @param string $columnName O nome da coluna fornecido pelo usuário.
     * @return string O nome da coluna sanitizado.
     */



    /**
     * Sanitiza o nome da tabela.
     *
     * - Remove aspas simples ou duplas.
     * - Substitui espaços por underscores (_).
     * - Envolve nomes com backticks (`), preservando caracteres especiais.
     *
     * @param string $tableName O nome da tabela fornecido pelo usuário.
     * @return string O nome da tabela sanitizado.
     * @throws \Exception Se o nome da tabela for inválido.
     */
    private static function sanitizeTableName(string $tableName): string
    {
        // Remove aspas simples e duplas
        $tableName = str_replace(["'", '"'], "", $tableName);

        // Substitui espaços por underscores (_)
        $tableName = str_replace(" ", "_", $tableName);

        // Verifica se o nome está vazio ou apenas espaços após a sanitização
        if (trim($tableName) === '') {
            throw new \Exception("O nome da tabela não pode ser vazio.");
        }

        // Sempre envolve o nome em backticks (`) para evitar problemas de sintaxe
        $tableName = "`" . str_replace("`", "", $tableName) . "`";

        return $tableName;
    }

    /**
     * Retorna informações sobre os comandos SQL permitidos e suas sintaxes.
     *
     * @param array $allowedActions Ações SQL permitidas para o usuário.
     * @return array Informações detalhadas sobre os comandos SQL disponíveis.
     */
    /**
     * Retorna informações sobre os comandos SQL permitidos e suas sintaxes.
     *
     * @param array $allowedActions Ações SQL permitidas para o usuário.
     * @return array Informações detalhadas sobre os comandos SQL disponíveis.
     */
    public static function getSQLInfo(array $allowedActions)
    {
        $info = [
            'SELECT' => [
                'description' => 'Consulta registros da tabela.',
                'syntax' => 'SELECT [colunas] FROM {TABELA} WHERE [condições];',
                'examples' => [
                    'SELECT * FROM {TABELA};',
                    'SELECT id, nome FROM {TABELA} WHERE status = "ativo";',
                    'where id = 1;'
                ]
            ],
            'ADD' => [
                'description' => 'Adiciona uma nova coluna na tabela.',
                'syntax' => 'ADD [nome_da_coluna] [tipo];',
                'examples' => [
                    'ADD nova_coluna VARCHAR(255);',
                    'ADD idade INT;'
                ]
            ],
            'DROP' => [
                'description' => 'Remove uma coluna da tabela.',
                'syntax' => 'DROP [nome_da_coluna];',
                'examples' => [
                    'DROP nova_coluna;',
                    'DROP idade;'
                ]
            ]
        ];

        $filteredInfo = [];
        foreach ($allowedActions as $action) {
            if (isset($info[$action])) {
                $filteredInfo[$action] = $info[$action];
            }
        }

        return [
            'allowedActions' => $allowedActions,
            'info' => $filteredInfo
        ];
    }

    private static function getColumnDefinition($tableName, $columnName)
    {
        $connection = Database::getConnection(self::$dbName);

        // Obtém as informações sobre as colunas da tabela
        $stmt = $connection->prepare("SHOW COLUMNS FROM `$tableName` LIKE ?");
        $stmt->execute([$columnName]);
        $columnInfo = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$columnInfo) {
            throw new \Exception("Coluna `$columnName` não existe na tabela `$tableName`.");
        }

        // Extrai a definição da coluna
        $definition = $columnInfo['Type']; // Tipo de dado (ex: VARCHAR(255), INT, etc.)

        // Adiciona a propriedade `NOT NULL` se aplicável
        if ($columnInfo['Null'] === 'NO') {
            $definition .= " NOT NULL";
        }

        // Adiciona o valor padrão (DEFAULT)
        if (!is_null($columnInfo['Default'])) {
            $default = $columnInfo['Default'];
            if (strtoupper($default) === 'CURRENT_TIMESTAMP') {
                $definition .= " DEFAULT CURRENT_TIMESTAMP";
            } else {
                $definition .= " DEFAULT '$default'";
            }
        }

        // Adiciona o comportamento `ON UPDATE` se aplicável
        if (strpos($columnInfo['Extra'], 'on update CURRENT_TIMESTAMP') !== false) {
            $definition .= " ON UPDATE CURRENT_TIMESTAMP";
        }

        return $definition;
    }


    /**
     * Registra logs de consultas SQL.
     */
    private static function logSQL($sql, $tableName, $error = null)
    {
        $logFile = __DIR__ . '/sql_log.txt';
        $date = date('Y-m-d H:i:s');
        $logEntry = "[$date] Table: $tableName | SQL: $sql | Error: " . ($error ?? 'Nenhum') . "\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }

    /**
     * Obtém todas as colunas de uma tabela.
     */
    public static function getColumns(string $tableName, array $columnLabels = []): array
    {
        // Verifica se o nome da tabela está vazio
        if (empty($tableName)) {
            throw new \Exception("O nome da tabela não pode ser vazio.");
        }

        $connection = Database::getConnection(self::$dbName);

        try {
            // Obtém as informações das colunas da tabela usando o comando `DESCRIBE`
            $stmt = $connection->prepare("DESCRIBE $tableName");
            $stmt->execute();
            $columns = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $result = [];
            foreach ($columns as $column) {
                $columnName = $column['Field']; // Nome da coluna
                $label = $columnLabels[$columnName] ?? $columnName; // Usa label customizado ou o nome padrão
                $result[$label] = $columnName;
            }

            return $result; // Retorna um array associativo com label => nome_da_coluna
        } catch (\Exception $e) {
            throw new \Exception("Erro ao obter colunas da tabelaa `$tableName`: " . $e->getMessage());
        }
    }


    /**
     * Gera mensagens amigáveis com base no comando SQL executado.
     *
     * @param string $sql O comando SQL executado.
     * @param \PDOStatement $stmt O objeto PDOStatement da execução.
     * @param string|null $tableName (Opcional) O nome da tabela, caso aplicável.
     * @return string Mensagem amigável.
     */
    private static function generateSQLMessage(string $sql, \PDOStatement $stmt, ?string $tableName = null): string
    {
        // Define uma mensagem padrão
        $message = "Comando SQL executado com sucesso.";

        // Analisa o comando SQL para determinar o tipo de operação
        if (stripos($sql, 'CREATE TABLE') === 0) {
            $message = "Tabela criada com sucesso.";
        } elseif (stripos($sql, 'DROP TABLE') === 0) {
            $message = "Tabela excluída com sucesso.";
        } elseif (stripos($sql, 'RENAME TABLE') === 0) {
            $message = "Tabela renomeada com sucesso.";
        } elseif (stripos($sql, 'ADD') === 0) {
            $message = "Coluna adicionada com sucesso na tabela `$tableName`.";
        } elseif (stripos($sql, 'DROP') === 0) {
            $message = "Coluna removida com sucesso da tabela `$tableName`.";
        } elseif (stripos($sql, 'MODIFY') === 0) {
            $message = "Coluna modificada com sucesso na tabela `$tableName`.";
        } elseif (stripos($sql, 'SELECT') === 0) {
            $rowCount = $stmt->rowCount();
            $message = "Consulta realizada com sucesso. {$rowCount} registro(s) encontrado(s).";
        } elseif (stripos($sql, 'INSERT') === 0) {
            $rowCount = $stmt->rowCount();
            $message = "{$rowCount} registro(s) inserido(s) com sucesso.";
        } elseif (stripos($sql, 'UPDATE') === 0) {
            $rowCount = $stmt->rowCount();
            $message = "{$rowCount} registro(s) atualizado(s) com sucesso.";
        } elseif (stripos($sql, 'DELETE') === 0) {
            $rowCount = $stmt->rowCount();
            $message = "{$rowCount} registro(s) excluído(s) com sucesso.";
        }

        return $message;
    }


    /**
     * Obtém o nome da tabela.
     */
    public static function getTableName()
    {
        if (self::$tableName) {
            return self::$tableName; // Nome da tabela definido dinamicamente
        }

        // Caso não tenha nome dinâmico, utiliza o nome do modelo
        $className = explode('\\', get_called_class());
        $className = end($className);
        return strtolower($className) . 's';
    }

    public static function select($fields = [])
    {
        self::_checkH();
        return self::$_h->select($fields);
    }

    public static function insert($fields = [])
    {
        self::_checkH();
        return self::$_h->insert($fields);
    }



    public static function update($fields = [])
    {
        self::_checkH();
        return self::$_h->update($fields);
    }

    public static function delete()
    {
        self::_checkH();
        return self::$_h->delete();
    }
}
