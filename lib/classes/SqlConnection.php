<?php

class SqlConnection
{
    static function getConnection (ErrorHandler $errorHandler = null, $database = null)
    {
        mysqli_report(MYSQLI_REPORT_STRICT);

        $host = Config::GetValue('host', 'database');
        $port = 3306;
        $socket = "";
        $user = Config::GetValue('username', 'database');
        $password = Config::GetValue('password', 'database');
        $databaseName = $database == null ? Config::GetValue('dbname', 'database') : $database;

        try
        {
            $conn = new mysqli($host, $user, $password, $databaseName, $port, $socket);
            if ($conn->connect_errno)
            {
                if ($errorHandler)
                {
                    $errorHandler->addError(new SimpleError($conn->connect_error, ErrorSeverityEnum::Error));
                }

                return null;
            }

            return $conn;
        }
        catch(Exception $ex)
        {
            print "<pre>";
            print $ex->getMessage();
            print $ex->getTraceAsString();
            print "</pre>";
        }

        return null;
    }
}