<?php
class db
{
    // database connection information
    // (modify as needed for every project)
    protected static $host = 'localhost';
    protected static $username = 'root';
    protected static $password = 'rootroot';
    protected static $database = 'react_worklog';
    protected static $pdo = null;
    /**
     * return the pdo object for the connection
     *
     * if the connection has been made, it will just
     * return the object, if not, it will first connect
     * and then return it
     *
     * @return pdo connection
     */
    public static function pdo()
    {
        if(static::$pdo === null) // if we have not yet tried to connect
        {
            // connect to the database
            try 
            {
                // store the connection (PDO) into static::$pdo
                static::$pdo = new PDO(
                    // 'mysql:dbname=database_name;host=locahost;charset=utf8'
                    'mysql:dbname='.static::$database.';host='.static::$host.';charset=utf8', 
                    static::$username,
                    static::$password
                );
                // set error reporting
                static::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } 
            catch (PDOException $e) 
            {
                // if something went wrong, just print out the error message            
                echo 'Connection failed: ' . $e->getMessage();
            }
        }
        return static::$pdo;
    }
    /**
     * runs a SQL query and returns the statement
     *
     * @param $sql - SQL string
     * @param $substitutions - array of values to substitute for ?
     * @return PDOStatement object
     */
    public static function query($sql, $substitutions = [])
    {
        // get PDO connection object
        $pdo = static::pdo();
        // prepare a statement out of SQL
        $statement = $pdo->prepare($sql);
        // we run the query and keep the outcome (true or false)
        // we supply the substitutions for ?s
        $outcome = $statement->execute(is_array($substitutions) ? $substitutions : [$substitutions]);
        // if there was an error
        if($outcome === false)
        {
            // print the error and exit
            static::exitWithError();
        }
        // return the statement (pointing to the result)
        return $statement;
    }

    public static function runQuery($query, $substitutions = [])
    {
        $stmt = static::query($query, $substitutions);

        $stmt->setFetchMode(PDO::FETCH_OBJ);

        return $stmt;
    }

    public static function fetchAll($query, $substitutions = [])
    {
        $stmt = static::runQuery($query, $substitutions);

        return $stmt->fetchAll();
    }

    public static function fetch($query, $substitutions = [])
    {
        $stmt = static::runQuery($query, $substitutions);
        
        return $stmt->fetch();
    }

    public static function getLastInsertId()
    {
        return static::pdo()->lastInsertId();
    }

    /**
     * an ugly (but better than nothing) way of
     * outputting errors
     */
    protected function exitWithError()
    {
        // print a <h1>
        echo '<h1>MySQL error:</h1>';
    
        // dump information about the error
        var_dump(static::pdo()->errorInfo());
    
        // end execution
        exit();
    }
    public static function setDatabase($database)
    {
        static::$database = $database;
    }
}