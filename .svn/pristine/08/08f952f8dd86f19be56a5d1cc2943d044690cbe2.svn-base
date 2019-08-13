<?php

class MakeTask extends Task {

    protected static $actions = ['index', 'add', 'edit', 'delete'];

    public function tableAction(array $items) {
        if(empty($items)) {
            die('Please input table name(s).'.PHP_EOL);
        }
        foreach($items as $item) {
            $sql = <<<SQL
CREATE TABLE `{$item}`(
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主ID',
  `belong_to_id` INT(11) UNSIGNED NOT NULL COMMENT '所属ID',
  `user_id` INT(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `title` VARCHAR (255) NOT NULL COMMENT '标题/名称',
  `created_at` INT(11) UNSIGNED NOT NULL COMMENT '创建时间',
  `updated_at` INT(11) UNSIGNED NOT NULL COMMENT '更新时间',
  `status` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态',
  PRIMARY KEY (`id`)
);
SQL;
            try {
                DB::execute($sql);
                $r = 'Table '.$item.' created.';
                $this->info($r);
            } catch(PDOException $e) {
                switch($e->getCode()) {
                    case '42S01':
                        $r = 'Table `'.$item.'` has been existed.';
                        break;
                    default:
                        $r = $e->getMessage();
                        break;
                }
                $this->notice($r);
            }
        }
    }

    public function controllerAction(array $items) {
        if(empty($items)) {
            die('Please input controller name(s).'.PHP_EOL);
        }
        foreach($items as $item) {
            $this->generateController($item);
            $this->generateViews($item);
        }
    }

    protected function generateController($item) {
        $name = camel_case($item).'Controller';
        $path = $this->config->controller_path.$name.'.php';
        $tab = '    ';
        $contents = [
            '<?php',
            '',
            'class '.$name.' extends BaseController {',
            '',
        ];
        foreach(self::$actions as $i) {
            $contents = array_merge($contents, [
                $tab.'/**',
                $tab.' * '.camel_case($i).' action',
                $tab.' */',
                "{$tab}public function {$i}Action() {",
                "{$tab}}",
                '',
            ]);
        }
        array_push($contents, '}');
        if(!file_exists($path)) {
            file_put_contents($path, implode("\n", $contents));
            echo 'Generated controller: ', $path, PHP_EOL;
        } else {
            echo 'Controller '.$name.' has been existed.', PHP_EOL;
        }

    }

    protected function generateViews($item) {
        $dir = $this->config->view_path.$item.'/';
        if(!file_exists($dir)) {
            @mkdir($dir, 0755);
        }
        if(file_exists($dir)) {
            foreach(self::$actions as $i) {
                $contents = [
                    '<?php',
                    '// action '. $i .' view',
                ];
                $path = $dir.$i.'.phtml';
                if(!file_exists($path)) {
                    file_put_contents($path, implode("\n", $contents));
                    echo 'Generated view: ', $path, PHP_EOL;
                } else {
                    echo 'View '.$item.'.'.$i.' has been existed.', PHP_EOL;
                }
            }
        } else {
            echo 'View Directory make failed.', PHP_EOL;
        }

    }

    public function modelAction(array $tables) {
        if(empty($tables)) {
            die('Empty tables.'.PHP_EOL);
        }
        foreach($tables as $t) {
            try {
                $sql = "SHOW COLUMNS FROM `{$t}`;";
                $rs = DB::fetchAll($sql);
                $primary_keys = [];
                $none_primary_keys = [];
                $types = [];
                $not_null = [];
                $null = [];
                $defaults = [];
                $empty_str = [];
                foreach($rs as $r) {
                    $field = $r['Field'];
                    if($r['Key'] === 'PRI') {
                        $primary_keys[] = $field;
                    } else {
                        $none_primary_keys[] = $field;
                    }
                    if($r['Null'] == 'NO') {
                        $not_null[] = $field;
                    } else {
                        $null[] = $field;
                    }
                    if(!is_null($r['Default'])) {
                        $defaults[$field] = $r['Default'];
                    }
                    $types[$field] = $this->getColumnType($r['Type']);
                }
                $this->generateModel($t, compact('types', 'not_null', 'null', 'defaults', 'empty_str', 'none_primary_keys', 'primary_keys'));
            } catch(PDOException $e) {
                if($e->getCode() === '42S02') {
                    echo 'Table `'.$t.'` does not exist.', PHP_EOL;
                } else {
                    echo $e->getMessage(), PHP_EOL;
                }
            }
        }
    }

    /**
     * Generate model file
     * @param $table
     * @param $primaryKeys
     * @param $columns
     */
    protected function generateModel($table, $columns) {
        $modelName = camel_case($table);
        $path = $this->config->model_path.$modelName.'.php';
        $tab = '    ';
        $metas = $this->generateModelMeta($columns);
        $contents = [
            '<?php',
            '',
            'use Phalcon\Mvc\Model\MetaData;',
            'use Phalcon\Db\Column;',
            '',
            'class '.$modelName.' extends Model {',
            '',
            "{$tab}public function getSource() {",
            "{$tab}{$tab}return '{$table}';",
            "{$tab}}",
            '',
            "{$tab}public function metaData() {",
        ];
        $contents = array_merge($contents, $metas, ["{$tab}}", '', '}']);
        if(!file_exists($path)) {
            file_put_contents($path, implode("\n", $contents));
        } else {
            echo 'Model '.$modelName.' has been existed, current META DATA: ', PHP_EOL;
            echo implode(PHP_EOL, $metas), PHP_EOL;
        }
    }

    /**
     * Generate model column meta data
     * @param $primaryKeys
     * @param $columns
     * @return array
     */
    protected function generateModelMeta($columns) {
        $tab = '    ';
        $contents = [
            "{$tab}{$tab}return [",
            //MODELS_ATTRIBUTES
            "{$tab}{$tab}{$tab}MetaData::MODELS_ATTRIBUTES => [",
            "{$tab}{$tab}{$tab}{$tab}'".implode("', '", array_keys($columns['types']))."',",
            "{$tab}{$tab}{$tab}],",
            //MODELS_PRIMARY_KEY
            "{$tab}{$tab}{$tab}MetaData::MODELS_PRIMARY_KEY => ['".implode("', '", $columns['primary_keys'])."',],",
            //MODELS_NON_PRIMARY_KEY
            "{$tab}{$tab}{$tab}MetaData::MODELS_NON_PRIMARY_KEY => ['".implode("', '", $columns['none_primary_keys'])."',],",
            //MODELS_NOT_NULL
            "{$tab}{$tab}{$tab}MetaData::MODELS_NOT_NULL => ['".implode("', '", $columns['not_null'])."',],",
            //MODELS_DATA_TYPES
            "{$tab}{$tab}{$tab}MetaData::MODELS_DATA_TYPES => [",
            implode(",\n", array_map(function($v, $k) use ($tab) {
                return "{$tab}{$tab}{$tab}{$tab}'".$k."' => Column::TYPE_".$v;
            }, array_values($columns['types']), array_keys($columns['types']))).',',
            "{$tab}{$tab}{$tab}],",
            //MODELS_DATA_TYPES_NUMERIC
            "{$tab}{$tab}{$tab}MetaData::MODELS_DATA_TYPES_NUMERIC => [",
            "{$tab}{$tab}{$tab}{$tab}'".implode("', '", array_filter(
                array_map(function($v, $k) use ($tab) {
                    if(in_array($v, ['INTEGER', 'DECIMAL', 'FLOAT', 'BOOLEAN'])) {
                        return $k;
                    } else {
                        return '';
                    }
                }, array_values($columns['types']), array_keys($columns['types']))
            , function($v) {return $v !== '';}))."',",
            "{$tab}{$tab}{$tab}],",
            //types bind
            "{$tab}{$tab}{$tab}MetaData::MODELS_DATA_TYPES_BIND => [",
            implode(",\n", array_map(function($v, $k) use ($tab) {
                return "{$tab}{$tab}{$tab}{$tab}'".$k."' => Column::BIND_PARAM_".($v == 'INTEGER'? 'INT' : 'STR');
            }, array_values($columns['types']), array_keys($columns['types']))).',',
            "{$tab}{$tab}{$tab}],",
            //default value
            "{$tab}{$tab}{$tab}MetaData::MODELS_DEFAULT_VALUES => [",
            implode(",\n", array_map(function($v, $k) use ($tab) {
                return "{$tab}{$tab}{$tab}{$tab}'".$k."' => '".$v."'";
            }, array_values($columns['defaults']), array_keys($columns['defaults']))),
            "{$tab}{$tab}{$tab}],",
            // Fields that allow empty strings
            // TODO: 不知道做啥的, 暂时不输出
            "{$tab}{$tab}{$tab}MetaData::MODELS_EMPTY_STRING_VALUES => [",
            "{$tab}{$tab}{$tab}{$tab}".implode(", ", $columns['empty_str']),
            "{$tab}{$tab}{$tab}],",
            // Fields that must be ignored from INSERT SQL statements
            "{$tab}{$tab}{$tab}MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],",
            // Fields that must be ignored from UPDATE SQL statements
            "{$tab}{$tab}{$tab}MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],",
            //id
            "{$tab}{$tab}{$tab}MetaData::MODELS_IDENTITY_COLUMN => 'id',",
            "{$tab}{$tab}];",
        ];
        return $contents;
    }

    /**
     * Generate model column type
     * @param $type
     * @return string
     */
    protected function getColumnType($type) {
        $type = strtoupper(preg_replace('#\(\d+\)#i', '', $type));
        $t = 'INTEGER';
        switch($type) {
            case 'TINYINT':
            case 'SMALLINT':
            case 'INT':
            case 'INTEGER':
            case 'BIGINT':
                $t = 'INTEGER';
                break;
            case 'DATE':
                $t = 'DATE';
                break;
            case 'VARCHAR':
                //ENUM and SET are compatibility VARCHAR type
            case 'ENUM':
            case 'SET':
                $t = 'VARCHAR';
                break;
            case 'DECIMAL':
            case 'DEC':
            case 'NUMERIC':
                $t = 'DECIMAL';
                break;
            case 'DATETIME':
            case 'TIMESTAMP':
                $t = 'DATETIME';
                break;
            case 'CHAR':
                //YEAR and TIME are compatibility CHAR type
            case 'YEAR':
            case 'TIME':
                $t = 'CHAR';
                break;
            case 'TINYBLOB':
            case 'TINYTEXT':
            case 'BLOB':
            case 'TEXT':
            case 'MEDIUMBLOB':
            case 'MEDIUMTEXT':
            case 'LONGBLOB':
            case 'LONGTEXT':
                $t = 'TEXT';
                break;
            case 'FLOAT':
            case 'DOUBLE':
            case 'REAL':
                $t = 'FLOAT';
                break;
            case 'BIT':
            case 'BOOL':
                $t = 'BOOLEAN';
                break;
        }
        return $t;
    }
}