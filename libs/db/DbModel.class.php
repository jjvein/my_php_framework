<?PHP
namespace Twt\Libs\Db;

/**
 * 这是一个数据库的操作类...我们可以使用该工具类来进行数据库操作.
 */

class DbModel {

    /* table of database */
    protected $table  = "";
    /* PK */
    protected $primaryKey = "";
    /* all fields of table  */
    protected $fields = array();
    /* request params */
    protected $param  = array();

    //是否连表查询
    protected $unionQuery = FALSE;
    /**
     * query datas
     */ 

    public function __construct ( $tableName, $fields) {
    	$this->table = $tableName;
    	$this->fields = $fields;
    }

    public function query($param){
    	$this->param = $param;
        $buildData = $this->buildParam($this->param);
        if (empty($buildData)) {
            return FALSE;
        }
        $result = $this->executeSql('read', $buildData['sql'], $buildData['data']);
        return $result ?: array();
    }

    /**
     * compute the records count.
     *
     */ 
    public function totalCount($param){
        $param = $this->param;
        unset($param['extra']);
        $param['fields'] = array('COUNT(*) AS num');
    
        $buildData = $this->buildParam($param);
        if (empty($buildData)) {
            return FALSE;
        }
        $result = $this->executeSql('read', $buildData['sql'], $buildData['data']);
        if (empty($result)) {
            return 0;
        }
        return (int) $result[0]['num'];
    }

    /**
     * add data
     */ 
    public function insert($param){
        if (empty($param)) {
            return FALSE;
        }
        $this->param = $param;
        $sqlData = array();

        foreach( $this->param as $field => $value) {
            if( in_array( $field, $this->fields ) ) {
                $sqlData['_'.$field] = $value;
            }
            else {//数据库中不存在该字段,则过滤掉
                unset($this->param[$field]);
            }
        }
        $insertFields = array_keys($this->param);
        // 需要插入值的字段
        $fieldStr = implode(',', $insertFields);
        // 占位符
        $placeHolder = implode(',:_', $insertFields);
        $placeHolder = ':_'. $placeHolder;
        $sqlComm = "INSERT INTO {$this->table} ({$fieldStr}) VALUES ({$placeHolder})";
        $result = $this->executeSql('write', $sqlComm, $sqlData ); 
        return $result;
    }

    /**
     * 插入数据的时候判断是否已经存在
     * 数据重复的逻辑根据实际业务来判定
     *
     * 如果数据存在则返回主键，方便直接进行更新.
     * return pk:存在，0:不存在
     */ 
    public function exists($param) {
    	$this->param = $param;
        $buildData = $this->buildParam($this->param);
        if (empty($buildData)) {
            return FALSE;
        }
        $result = $this->executeSql('read', $buildData['sql'], $buildData['data']);
        return empty($result) ? 0 : 1;
    }

    /**
     * update data
     */ 
    public function update($param){
        if (empty($param)) {
            return FALSE;
        }

        $this->param = $param;

        $sqlComm = '';
        $sqlData = array();
        $updateBy = '';
        // 更新的字段
        if ( !empty($this->param['update'] )) {
            foreach ($this->param['update'] as $f => $_v) {
                $sqlComm .= " {$f}=:{$f},";
                $sqlData[$f] = $_v;
            }
        }
        if (empty($sqlComm)) {
            return FALSE;
        }

        $sqlComm = "UPDATE {$this->table} SET ". $sqlComm;
        // 更新的条件
        if (!empty($this->param['where'])) {
            // 过滤
            foreach( $this->param['where'] as $field => $value) {
                if(!in_array( $field, $this->fields)) {
                    unset($this->param['where'][$field]);
                }
                $updateBy .= empty($updateBy) ? " WHERE " : " AND ";
                if (!empty($value) || $value == 0) {
                    if ("BETWEEN" == strtoupper ($value['op'])) {// 查询某区间的值,[a , b]
                        $valArr = explode(',', $value['value']);
                        $updateBy .= " {$field} >= :_{$field}_start AND {$field} <= :_{$field}_end";
                        $sqlData["_{$field}_start"] = $valArr[0];
                        $sqlData["_{$field}_end"] = $valArr[1];
                        unset($this->param['where'][$field]);
                    }
                    else if ( "IN" == strtoupper( $value['op'] ) ){// 枚举
                        $valArr = implode(',', $value['value']);
                        $updateBy .= " {$field} IN ({$valArr}) ";
                    }
                    else if ( "=" == $value['op'] || ">" == $value['op'] || "<" == $value['op']
                        || ">=" == $value['op'] || "<=" == $value['op'] || "<>" == $value['op'] ){
                            $updateBy .= " {$field} {$value['op']} :_{$field}";
                            $sqlData["_{$field}"] = $value['value'];
                        }
                }
            }
        }
        if (empty($updateBy) || $updateBy == " WHERE ") {
            //说明条件为空，一定要防止，否则将全表更新
            return FALSE;
        }
        // delete last letter.
        $sqlComm = rtrim($sqlComm, ',');
        $sqlComm = $sqlComm . $updateBy;
        $affectNum = $this->executeSql('write', $sqlComm, $sqlData);
        
        return $affectNum;
    }

/*    private function buildWhereSubSql ($where) {
        !is_array($where)  && return FALSE;

        foreach ($where as $field => $value) {
            if (!in_array($field, $this->fields)) {
                //不在表字段中, 直接过滤掉.
                continue;
            }
            
            if (!empty($value) || 0 == $value) {
                $whereSql .= empty($whereSql) ? " WHERE " : " AND ";
                switch ( strtoupper($value['op']) ) {
                    case 'BETWEEN':
                         list ($begin, $end) = explode (',', $value, 2);
                         $whereSql .= "{$field} >= :_{$field}_begin AND {$field} <= :_{$field}_end";
                         $sqlData["_{$filed}_begin"] = $begin;
                         $sqlData["_{$filed}_end"] = $end;                  
                        break;
                    case 'IN':
                         $inStr = implode(',', $value);
                         $whereSql .=  "{$field} IN ({$inStr})";
                        break;
                    case '>=':
                    case '>':
                    case '=':
                    case '<':
                    case '<=':
                         $whereSql .= "{$field} >= :_{$field}";
                         $sqlData["_{$field}"] = $value; 
                        break;
                    default:
                        //将最后一个AND 删除掉..
                        $whereSql = rtrim($whereSql);
                        $whereSql = rtrim($whereSql, "AND");
                        break;
                       
                }
                unset($where[$field]);   
            }
        }
        $ret = array(
            'whereSql' => $whereSql,
            'sqlData'  => $sqlData,
            );

        return $ret;
    }*/

    /**
     * 一般进行软删除，即通过更新来实现，
     * 如果确实需要硬删除，则调用此方法
     */ 
    public function del($param){
        if (empty($param ['where'])) {
            return FALSE;
        }
        $where = $this->buildWhereSubSql ($param['where']);
        $sqlComm = "DELETE FROM {$this->table} " . $where['whereSql'];
        $sqlData = $where['sqlData'];
        $affectNum = $this->executeSql("write", $sqlComm, $sqlData);
        return $affectNum;
    }

    /**
     * execute sql with special DbHelper.
     *
     * @func : must be equal 'read' or 'write'
     */
    protected function executeSql($func, $sql, $data ){
        if ( $func != 'read' && $func != 'write') {
            throw new \Twt\Libs\VException('invalid param func, value must be read or write.['.$func .']' , 200000);
        }

        //这里还需要继续执行函数..
        $db = Database::singleton ();
        return call_user_func_array(array($db, $func), array($sql, $data));
    }

    /**
     * set request params.
     */ 
    public function setParam($param){
        $this->param = $param;
    }

    /**
     * build sql with params.
     *
     * @param : param
     */
    protected function buildParam($param){
        if (empty($param)) {
            return FALSE;
        }
        // default select * from table.
        $fields = empty( $param['fields'] ) ? '*' : implode(',', $param['fields']);
        if ($this->unionQuery) {
            $sqlComm = "SELECT {$fields} FROM %s WHERE 1 ";
        }
        else {
            $sqlComm = "SELECT {$fields} FROM {$this->table} WHERE 1 ";
        }
        $sqlData = array();

        $whereCondition = $param['where'];
        if( !empty ( $whereCondition ) ){
            foreach ( $whereCondition as $key => $value ){
                if ( "LIKE" == strtoupper( $value['op'] ) ){// like "%...%"
                    $sqlComm .= " AND {$key} like '%{$value['value']}%'";
                }
                else if( "LLIKE" == $value['op'] ){// like "%..."
                    $sqlComm .= " AND {$key} like '%{$value['value']}'";
                }
                else if ( "RLIKE" == $value['op'] ){// like "...%"
                    $sqlComm .= " AND {$key} like '{$value['value']}%'";
                }
                else if ( "IN" == strtoupper($value['op'])){// 枚举
                    if (!is_array($value['value'])) {
                        $value['value'] = array($value['value']);
                    }
                    if (is_numeric($value['value'][0])) {//num.
                        $valArr = implode(',', $value['value']);
                        $sqlComm .= " AND {$key} IN ({$valArr}) ";
                    }
                    else {//string
                        $valArr = implode("','", $value['value']);
                        $sqlComm .= " AND {$key} IN ('{$valArr}') ";
                    }
                }
                else if ( "BETWEEN" == strtoupper ( $value['op'] ) ){// 查询某区间的值,[a , b]
                    $valArr = explode(',', $value['value']);
                    if (!empty($valArr[0])) {
                        $sqlComm .= " AND {$key} >= :{$key}_start";
                        $sqlData[$key . '_start'] = $valArr[0];
                    }
                    if (!empty($valArr[1])) {
                        $sqlComm .= " AND {$key} <= :{$key}_end";
                        $sqlData[$key . '_end'] = $valArr[1];
                    }
                }
                else if ( "=" == $value['op'] || ">" == $value['op'] || "<" == $value['op'] 
                    || ">=" == $value['op'] || "<=" == $value['op'] || "<>" == $value['op']){ 
                        if($value['key']){
                            $sqlComm .= " AND {$key} {$value['op']} :{$value['key']}";
                            $sqlData[$value['key']] = $value['value'];
                        }else{
                            $sqlComm .= " AND {$key} {$value['op']} :{$key}";
                            $sqlData[$key] = $value['value'];
                        }
                    }
                else if ("is null" == $value['op'] || 'is not null' == $value['op']){
                    $sqlComm .= " AND {$key} {$value['op']}";
                }
                else if ("|" == $value ['op'] || '&' == $value ['op']) {
                    $sqlComm .= " AND {$key} {$value['op']} :{$key}";
                    $sqlData[$key] = $value['value'];
                }
                else {
                    throw new \Twt\Libs\VException("invalid op value. [op = ". $value['op'] . "]" , 100005);
                } 
            }
        }
        //Union Query.
        if ($this->unionQuery) {
            if(empty($this->table)) {
                return FALSE;
            }
            $tableList = explode(",", $this->table);
            $f = TRUE;
            $str = $sqlComm;
            $sqlComm = '';
            foreach ($tableList as $tb) {
                if (!$f) {
                    $sqlComm .= " UNION ";
                }
                $f = FALSE;
                $sqlComm .= sprintf($str, $tb);
            } 
        }
        $extraCondition = $param['extra'];
        if (!empty($extraCondition['groupby'])) {
            $sqlComm .= " GROUP BY " . $extraCondition['groupby'];
        }
        if (!empty($extraCondition['orderby'])) {
            if(!empty( $extraCondition['orderby']['field'] ) ){
                $sort = empty( $extraCondition['orderby']['sort']) ? 'DESC' : strtoupper($extraCondition['orderby']['sort']);
                $sqlComm .= " ORDER BY " . $extraCondition['orderby']['field'] . " " . $sort;
            }
        }
        if (isset($extraCondition['offset']) && isset($extraCondition['limit'])) {
            $sqlComm .= " LIMIT :_offset, :_limit ";
            $sqlData['_offset'] = (int) $extraCondition['offset'];
            $sqlData['_limit'] = (int) $extraCondition['limit'];
        }
        return array('sql' => $sqlComm, 'data' => $sqlData);
    }		
}

?>