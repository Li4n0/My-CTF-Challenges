<?php

class Model
{
    private $db = null;
    private $tbname = null;

    public function __construct()
    {
        $this->db = Mysql::getInstance();
        $this->tbname = strtolower(get_called_class());
    }

    static private function prepareWhere($args)
    {
        $tmpSql = '';
        $i = 0;
        if (!empty($args)) {
            $tmpSql .= " WHERE";
        }
        foreach ($args as $column => $value) {
            $value = addslashes($value);
            $tmpSql .= " `$column`='$value'";
            if ($i !== count($args) - 1) {
                $tmpSql .= ' AND';
            }
            $i++;
        }
        return $tmpSql;
    }

    static private function prepareInsert($baseSql, $args)
    {
        $i = 0;
        if (!empty($args)) {
            foreach ($args as $column => $value) {
                $value = addslashes($value);
                if ($value !== null) {
                    if ($i !== count($args) - 1) {
                        $baseSql = sprintf($baseSql, "`$column`,%s", "'$value',%s");
                    } else {
                        $baseSql = sprintf($baseSql, "`$column`", "'$value'");
                    }
                }
                $i++;
            }
        }

        return $baseSql;
    }

    static private function prepareUpdate($baseSql, $args)
    {
        $i = 0;
        if (!empty($args)) {
            foreach ($args as $column => $value) {
                $value = addslashes($value);
                if ($value !== null) {
                    if ($i !== count($args) - 1) {
                        $baseSql = sprintf($baseSql, "`$column`='$value',%s");
                    } else {
                        $baseSql = sprintf($baseSql, "`$column`='$value'");
                    }
                }
                $i++;
            }
        }

        return $baseSql;
    }

    public static function findOne(array $args = array())
    {
        $db = Mysql::getInstance();
        $tbname = strtolower(get_called_class());
        $baseSql = "SELECT * FROM `$tbname`";
        $baseSql .= self::prepareWhere($args);
        $sql = $baseSql . " LIMIT 1";
        $result = $db->query($sql);
        if ($result->num_rows) {
            return new $tbname(...array_values($result->fetch_assoc()));
        }
    }

    public function save()
    {
        $args = get_object_vars($this);
        $args = array_slice($args, 0, -2);
        if ($args['id'] !== null) {
            $baseSql = "UPDATE `$this->tbname` SET %s WHERE id=" . $args['id'];
            $sql = self::prepareUpdate($baseSql, array_slice($args, 1));
            $this->db->query($sql);
            if (!$this->db->conn->error) {
                return $this;
            } else {
                return null;
            }

        } else {

            $baseSql = "INSERT INTO `$this->tbname`(%s) VALUE(%s)";
            $sql = self::prepareInsert($baseSql, $args);
            $this->db->query($sql);
            if (!$this->db->conn->error) {
                $objectID = $this->db->query("SELECT LAST_INSERT_ID()")->fetch_row()[0];
                return self::findOne(array("id" => $objectID));
            } else {
                return null;
            }
        }

    }
}