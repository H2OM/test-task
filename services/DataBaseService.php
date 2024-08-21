<?php
    namespace services;

    use PDO;
    use DTO\IphoneDTO;
    use PDOException;

    /**
     * Сервис для записи в базу данных
     */
    class DataBaseService {
        // Для наглядности настройки для соедением с БД сделал в этом сервисе

        private PDO|null $pdo;

        public function __construct() {
            try {

                $this->pdo = new PDO(
                    dsn: 'mysql:host=localhost;dbname=test;charset=utf8',
                    username: 'root',
                    password: '',
                    options: [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_EMULATE_PREPARES => FALSE,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="TRADITIONAL"'
                    ]);

            } catch ( PDOException $e ) {
                //Действия в случае неудачной попытки соединеня с БД
                throw new \PDOException("Ошибка с соединением БД", 500);
            }
        }
        function __destruct()
        {
            $this->pdo = null;
        }

        /**
         *
         * @param string $url
         * @param string $tableName
         * @param IphoneDTO $DTO вместе с IphoneDTO, можно перечислять другие DTO
         * @return void
         */
        public function setIntoBaseByURL(string $url, string $tableName, IphoneDTO $DTO): void
        {

            $curl = curl_init($url);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'timeout: 90',
                'Content-Type: application/json'
            ]);

            $response = json_decode(curl_exec($curl), true);

            if(json_last_error() !== JSON_ERROR_NONE) {
                //Действия в случае неудачной новертации JSON
                echo 'Api error: ' . json_last_error_msg();
            }

            $insertValues = [];

            foreach (array_shift($response) as $key => $value) {
                if($DTO->setSelf($value)) {
                    $insertValues = array_merge($insertValues, array_values($DTO->getAllVars()));
                }
            }
            $insertFields = implode(',', $DTO->getAllFields());

            $prepareValues = '(' . implode(', ', array_fill(0, count($DTO->getAllFields()), '?')) . ')';

            $prepareValues = implode(', ', array_fill(0, count($insertValues) / count($DTO->getAllFields()), $prepareValues));

            $request = 'INSERT INTO ' . $tableName . ' (' . $insertFields . ')' . ' VALUES ' . $prepareValues;

            try {
                $state = $this->pdo->prepare($request);

                for($i = 1; $i <= count($insertValues); $i++) {
                    $state->bindParam($i, $insertValues[$i-1], PDO::PARAM_STR);
                }

                $state->execute();

            } catch (PDOException $e) {
                //Действия в случае неудачной попытки записи в БД
                throw new \PDOException('Ошибка записи данных ' . $e->getMessage(), 500);
            }
        }
    }
