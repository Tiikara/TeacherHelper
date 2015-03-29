<?php


class CSqlResult
{

    public function CSqlResult($sql_result)
    {
        $this->sql_result = $sql_result;
        $this->count_rows = mysqli_num_rows($sql_result);
    }

    /**
     * @return int
     */
    public function getCountRows()
    {
        return $this->count_rows;
    }

    /**
     * @return string[][]
     */
    public function getArrayRows()
    {
        if($this->array_data == null)
        {
            $this->array_data = array();

            for ($c=0; $c<$this->count_rows; $c++)
            {
                $this->array_data[] = mysqli_fetch_array($this->sql_result);
            }
        }

        return $this->array_data;
    }

    /**
     * @return string[]
     */
    public function getRow()
    {
        return mysqli_fetch_array($this->sql_result);
    }

    private $array_data = null;
    private $count_rows;

    private $sql_result;
}

class CSqlDatabase
{
    public function connect()
    {
        $this->mysqli = mysqli_connect($this->hostname,$this->username,$this->password) or die("Cant connect to MySQL database.");

        mysqli_select_db($this->mysqli, $this->dbName) or die(mysql_error());

        mysqli_query($this->mysqli ,"set names 'utf8'");
    }

    /**
     *
     * Возвращает CSqlResult, если запрос SELECT, иначе null.
     * Возвращает CSqlDatabase::sqlerror, если запрос завершился с ошибкой.
     *
     * @param string
     * @return CSqlResult
     */
    public function executeQuery($query)
    {
        $result = mysqli_query($this->mysqli ,$query);

        if($result == null)
            return self::sqlerror;

        if(is_bool($result))
            return null;
        else
            return new CSqlResult($result);
    }

    public function getInsertId()
    {
        return mysqli_insert_id($this->mysqli);
    }

    const sqlerror = -1;

    private $mysqli;

    private $hostname = 'localhost';
    private $username = 'root';
    private $password = '';

    private $dbName = 'teacher';
}