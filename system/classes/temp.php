<?php
    class DB{
    ## select MIN functions => min('column');
    public function min($column, $name = null){
        $this->select = $this->isSelect('MIN', $column, $name);
        return $this;
    }
    ## select MAX functions => max('column');
    public function max($column, $name = null){
        $this->select = $this->isSelect('MAX', $column, $name);
        return $this;
    }
    ## select COUNT functions => count('column');
    public function count($column, $name = null){
        $this->select = $this->isSelect('COUNT', $column, $name);
        return $this;
    }
    ## select AVG functions => avg('column');
    public function avg($column, $name = null){
        $this->select = $this->isSelect('AVG', $column, $name);
        return $this;
    }
    ## select SUM functions => sum('column');
    public function sum($column, $name = null){
        $this->select = $this->isSelect('SUM', $column, $name);
        return $this;
    }

    public function join($table, $key = null, $op = '', $foreign = null, $sql = ''){
        $setForeign = !is_null($foreign) ? $foreign : $op;
        $setOp = !is_null($foreign) ? $op : '=';
        $key = strpos(self::$table, 'AS') ? $key : $this->isTable($key);
        $setForeign = strpos($table, 'AS') ? $setForeign : $this->isTable($setForeign);
        $this->join .= "{$sql} JOIN {$this->isTable($table)} ON {$key} {$setOp} {$setForeign} ";
        return $this;
    }

    public function innerJoin($table, $key, $op, $foreign = null){
        $this->join($table, $key, $op, $foreign, 'INNER');
        return $this;
    }

    public function leftJoin($table, $key, $op, $foreign = null){
        $this->join($table, $key, $op, $foreign, 'LEFT');
        return $this;
    }

    public function rightJoin($table, $key, $op, $foreign = null){
        $this->join($table, $key, $op, $foreign, 'RIGHT');
        return $this;
    }
    
    public function leftOuterJoin($table, $key, $op, $foreign = null){
        $this->join($table, $key, $op, $foreign, 'LEFT OUTER');
        return $this;
    }
    
    public function rightOuterJoin($table, $key, $op, $foreign = null){
        $this->join($table, $key, $op, $foreign, 'RIGHT OUTER');
        return $this;
    }
    
    public function fullOuterJoin($table, $key, $op, $foreign = null){
        $this->join($table, $key, $op, $foreign, 'FULL OUTER');
        return $this;
    }


    Public function orWhere($column, $op = null, $value = null){
        $this->state = 'OR';
        $this->where($column, $op, $value);
        return $this;
    }

    public function notWhere($column, $op = null, $value = null){
        $this->setWhere($column, $op, $value, 'NOT');
        return $this;
    }

    public function orNotWhere($column, $op = null, $value = null){
        $this->state = 'OR';
        $this->notWhere($column, $op, $value);
        return $this;
    }

    public function whereNull($column){
        $this->where .= $this->isState("{$column} IS NULL");
        return $this;
    }

    public function orWhereNull($column){
        $this->state = 'OR';
        $this->whereNull($column);
        return $this;
    }

    public function whereNotNull($column){
        $this->where .= $this->isState("{$column} IS NOT NULL");
        return $this;
    }

    public function orWhereNotNull($column){
        $this->state = 'OR';
        $this->whereNotNull($column);
        return $this;
    }

    public function whereIn($column, array $columns){
        $array = '';
        foreach ($columns as $key => $value) {
            $array .= $this->isText($value) . ',';
        }
        $array = substr($array, 0, -1);
        $_where = "{$column} IN ({$array})";
        $this->where .= $this->isState($_where);
        return $this;
    }

    public function orWhereIn($column, array $value){
        $this->state = 'OR';
        $this->whereIn($column, $value);
        return $this;
    }

    public function whereNotIn($column, array $value){
        $value = implode(', ', $value);
        $_where = "{$column} NOT IN ({$value})";
        $this->where .= $this->isState($_where);
        return $this;
    }

    public function orWhereNotIn($column, array $value){
        $this->state = 'OR';
        $this->whereNotIn($column, $value);
        return $this;
    }

    public function between($column, $val1, $val2 = null){
        $param = is_array($val1) ? $this->isText($val1[0]) .' AND '.  $this->isText($val1[1]) : $this->isText($val1) .' AND '.  $this->isText($val2);
        $_between = "{$column} {$this->not} BETWEEN {$param}";
        $this->not = '';
        $this->where .= $this->isState($_between);
        return $this;
    }

    public function orBetween($column, $val1, $val2 = null){
        $this->state = 'OR';
        $this->between($column, $val1, $val2);
        return $this;
    }

    public function notBetween($column, $val1, $val2 = null){
        $this->not = 'NOT';
        $this->between($column, $val1, $val2);
        return $this;
    }

    public function orNotBetween($column, $val1, $val2 = null){
        $this->state = 'OR';
        $this->not = 'NOT';
        $this->between($column, $val1, $val2);
        return $this;
    }

    public function like($column, $search){
        $this->where .= $this->isState("{$column} {$this->not} LIKE {$search}");
        $this->not = '';
        return $this;
    }

    public function orLike($column, $search){
        $this->state = 'OR';
        $this->like($column, $search);
        return $this;
    }

    public function notLike($column, $search){
        $this->not = 'NOT';
        $this->like($column, $search);
        return $this;
    }

    public function orNotLike($column, $search){
        $this->state = 'OR';
        $this->not = 'NOT';
        $this->like($column, $search);
        return $this;
    }

    public function groupBy($values){
        $this->query .= " GROUP BY {$this->isIm($values)}";
        return $this;
    }

    public function having($column, $op = null, $value = null){
        $_having = ' HAVING ';
        if (is_array($op)) {
            $q = explode('?', $column);
            foreach ($op as $key => $val) {
                $_having .= $q[$key] . $val;
            }
        } elseif (empty($value)) {
            $_having .= "{$column} > {$op}";
        } else {
            $_having .= "{$column} {$op} {$value}";
        }
        $this->query .= $_having;
        return $this;
    }

    public function orderBy($column, $sort = 'ASC'){
        $this->query .= " ORDER BY {$column} {$sort}";
        return $this;
    }

    public function limit($column1, $column2 = null){
        $this->query .= " LIMIT {$column1} " . (!is_null($column2) ? ", {$column2}" : '');
        return $this;
    }

    public function offset($column){
        $this->query .= " OFFSET {$column}";
        return $this;
    }

    public function pagination($perPage, $page = 1){
        $this->limit($perPage);
        $page = (($page > 0 ? $page : 1) - 1) * $perPage;
        $this->offset($page);
        return $this;
    }

    public function get($select = null){
        $this->select .= !empty($select) ? ', ' . $this->isIm($select) : '';
        $sql = sprintf("SELECT %s FROM %s %s", $this->select, self::$table, $this->setExtract());
        $result = self::$conn->prepare($sql);
        $result->execute();
        return $result->fetchAll();
    }

    public function first($select = null){
        $this->select .= !empty($select) ? ', ' . $this->isIm($select) : '';
        $sql = sprintf("SELECT %s FROM %s %s LIMIT 1", $this->select, self::$table, $this->setExtract());
        $result = self::$conn->prepare($sql);
        $result->execute();
        return $result->fetchAll();
    }

    public function insert(Array $columns){
        $columns = implode(',', array_keys($columns));
        $values = '';
        foreach ($columns as $key => $val) {
            $values .= (is_int($val) ? $val : "'{$val}'") . ",";
        }
        $sql = sprintf("INSERT INTO %s (%s) VALUES(%s)", self::$table, $columns, substr($values, 0, -1));
        return $this->runQuery($sql);
    }

    public function update(Array $columns){
        $_columns = '';
        foreach ($columns as $key => $value) {
            $_columns .= "{$key} = " . $this->istext($value) . ", ";
        }
        $_columns = substr($_columns, 0, -2);
        $sql = sprintf("UPDATE %s SET %s WHERE %s", self::$table, $_columns, $this->where);
        return $this->runQuery($sql);
    }

    public function delete(){
        $sql = sprintf("DELETE FROM %s WHERE %s", self::$table, $this->where);
        return $this->runQuery($sql);
    }

    private function setWhere($column, $op, $value, $sql = ''){
        $_where = '';
        if (is_array($column)) {
            $op = is_null($op) ? 'AND' : $op;
            foreach ($column as $keys => $value) {
                if (is_array($value)) {
                    $_where .= $this->setWhere(
                        $value[0],
                        isset($value[1]) ? (is_int($value[1]) ? $value[1] : (in_array($value[1], $this->op) ? $value[1] : "'{$value[1]}'")) : '',
                        isset($value[2]) ? "'{$value[2]}'" : ''
                    ) . $op;
                } else {
                    die('Lihat dokumentasi');
                }
            }
            $_where = substr($_where, 0, -strlen($op));
        } else {
            if (empty($op) && empty($value)) {
                $_where = " {$sql} id = {$column} ";
            } elseif (empty($value)) {
                $_where = " {$sql} {$column} = " . $this->isText($op);
            } else {
                $_where = " {$sql} {$column} {$op} " . $this->isText($value);
            }
        }
        $this->where .= $this->isState($_where);
    }


    private function setExtract(){
        $sql = '';
        $sql .= $this->join;
        $sql .= !empty($this->where) ? 'WHERE ' . $this->where : '';
        $sql .= $this->query;
        $this->reset();
        return $sql;
    }
    
    private function runQuery($sql){
        $query = self::$conn->prepare($sql);
        if ($query->execute()) return true;
        else return false;
    }

    private function isState($sql){
        $sql = empty($this->where) ? $sql : " {$this->state} " . $sql;
        $this->state = 'AND';
        return $sql;
    }

    private function isText($val){
        return is_int($val) ? $val : "'{$val}'";
    }

    private function isIm($column){
        return is_array($column) ? implode(', ', $column) : $column;
    }

    private function isTable($column){
        if (is_array($column)) {
            $string = '';
            foreach ($column as $key => $value) {
                $string .= "{$value}, ";
            }
            return substr($string, 0, -2);
        }
        return $column;
    }
    private function isSelect($sql, $column, $name){
        return "{$sql}({$column})" . (!is_null($name) ? " AS {$name}" : '');
    }




}