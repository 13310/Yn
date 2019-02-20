<?php
 class sql{
	private function err($error){
		exit("错误:".$error);
	}
	private $_pdo;
	private $_sql;

	 /***
	  * @param array $config 数组传入
	  * @param string $char
	  */
	public function connect($config=array(),$char='set names "utf8"'){
		$dbs="mysql:host=".$config['db_host'].";dbname=".$config['db_database'];
		// die($dbs);
		try{
			$this->_pdo = new PDO($dbs,$config['db_user'],$config['db_pass']);
			$this->_pdo->prepare($char)->execute();
		}catch (Exception $err){
			$this->err("数据库连接失败".$err->getMessage());
		}
	}

	 /***
	  * @param $sql sql语句
	  *
	  * @return mixed 返回数组,包含状态信息和数据
	  * @internal param string $char 字符编码
	  *
	  */
	private function runSql($sql){
		$result = $this->_pdo->prepare($sql);
		$this->_sql = $sql;
		try{
			$status = $result->execute();
		}catch (Exception $err){
			$this->err($sql."不能执行");
		}
		return array("status"=>$status,"data"=>$result);
	}
	public function _getSql(){
		return $this->_sql;
	}
	public function custom($sql){
		$result = $this->runSql($sql);
		if($result['status']){
			return $result['data']->fetchAll(PDO::FETCH_ASSOC);
		}else{
			return "无数据";
		}
	}

	 /***
	  * @param $table 传入表名
	  *
	  * @return mixed 返回所有数据的数组
	  */
	public function getAll($table){
		$sql = "select * from ".$table;
		$result = $this->runSql($sql);
		if($result['status']){
			return $result['data']->fetchAll(PDO::FETCH_ASSOC);
		}else{
			return "无数据";
		}
	}

	 /***
	  * @param $table
	  * @param $key
	  * @param string $where
	  *
	  * @return array|string
	  */
	public function getValue($table,$dbkey,$where){
		if(!empty($where)){
			$where = "where $where";
		}
		$sql = "SELECT $dbkey FROM $table $where";
		$result = $this->RunSql($sql);
		if($result['status']){
			$value = $result['data']->fetchAll(PDO::FETCH_ASSOC);
			$arr = array();
			foreach ($value as $key=>$val){
				array_push($arr,$val[$dbkey]);
			}
			if(sizeof($arr)==1){
				return $arr[0];
			}elseif(sizeof($arr)>1){
				return $arr;
			}
			return false;
		}else{
			return false;
		}
	}

	 /***
	  * @param mixed $tabname
	  * @param string $where 判断条件
	  * @param string $rowsIndex
	  * @param string $rowsConut 获取行数
	  *
	  * @return mixed 返回数组对象
	  * @internal param string $char 字符编码
	  *
	  */
	 public function select($tabname,$where='',$rowsIndex='',$rowsConut=''){
	 	if(is_array($tabname)){
	 		if(empty($tabname['table'])){
	 			return "缺失表";
		    }
		    $table = $tabname['table'];
		    $key=empty($tabname['key'])?"*":$tabname['key'];
		    $sql = "SELECT $key FROM $table";
		    if(!empty($tabname['where'])){
			    $sql =$sql." where ".$tabname['where'];
		    }
		    if(!empty($tabname['rowsIndex'])){
			    $rowsIndex = $tabname['rowsIndex'];
			    $rowsConut = !empty($tabname['rowsConut'])?$tabname['rowsConut']:'';
			    $sql= $sql." limit $rowsIndex,$rowsConut";
		    }
		    if(!empty($tabname['order'])){
		    	$order=$tabname['order'];
		    	$desc = empty($tabname['desc'])?"desc":$tabname['desc'];
			    $sql= $sql." order by $order $desc";
		    }
	    }else{
		    if(!empty($where)){
			    $where = "where $where";
		    }
		    if(!empty($rowsIndex)){
			    $sql = "select * from $tabname $where limit $rowsIndex,$rowsConut";
		    }else{
			    $sql = "select * from $tabname $where";
		    }
	    }
		 $result = $this->RunSql($sql);
		 if($result['status']){
			 return $result['data']->fetchAll(PDO::FETCH_ASSOC);
		 }else{
			 return "无数据";
		 }
	 }

	 /***
	  * @param $tabName 表名
	  * @param $insertValues 插入数据
	  * @param string $alone 单独表字段
	  *
	  * @return string 返回结果
	  */
	 public function insert($tabName,$insertValues,$alone=''){
		 if(!empty($alone)){
			 $alone="($alone)";
		 }
		 if(is_array($insertValues)){
		 	 $keys="(";
		 	 $vals="";
			 foreach ($insertValues as $key=>$val){
				$keys= $keys.$key.",";
				$vals = $vals.'"'.$val.'",';
			 }
			 $keys = rtrim($keys,",");
			 $vals = rtrim($vals,",");
			 $keys = $keys.")";
			 $sql = "insert into $tabName $keys values($vals)";
		 }else{
			 $sql = "insert into $tabName $alone values($insertValues)";
		 }
		 $result = $this->RunSql($sql);
		 if($result['status']){
		 	return true;
		 }else{
			 return false;
		 }
	 }

	 /***
	  * @param $tabname 表名
	  * @param $updatevalues 更新数据
	  * @param $where 更新条件
	  *
	  * @return string 返回成功或失败
	  */
	 public function update($tabname,$updatevalues,$where){
		 if(!empty($where)){
			 $where="where $where";
		 }
		 if(is_array($updatevalues)){
		 	$value="";
		 	foreach ($updatevalues as $key=>$val){
			    $value=$key.'="'.$val.'",';
		    }
			 $value = trim($value,",");
			 $sql = "update $tabname set $value $where";
		 }else{
			 $sql = "update $tabname set $updatevalues $where";
		 }
		 $this->_sql = $sql;
		 $conut = $this->_pdo->exec($sql);
		 if($conut===false){
			 return false;
		 }
		 return $conut;
	 }

	 /***
	  * @param $tabName 表名
	  * @param $where 条件
	  *
	  * @return mixed 受影响的条数
	  */
	 public function delete($tabName,$where){
		 if(!empty($where)){
			 $where="where $where";
		 }
		 $sql = "delete from $tabName $where";
		 $this->_sql = $sql;
		 $conut = $this->_pdo->exec($sql);
		 if($conut===false){
			 return false;
		 }
		 return $conut;
	 }

	 /***
	  * @param $tabname 表名
	  * @param string $where 条件
	  * @param int $startRowID 开始
	  * @param int $endRowID 结束
	  *
	  * @return mixed 返回未知
	  */
	 public function getLines($tabname,$where="",$startRowID=0,$endRowID=10){
		 if(!empty($where)){
			 $where="where $where";
		 }
		 $sql = "select * from $tabname $where limit $startRowID,$endRowID";
		 $rowsNum = $this->runSql($sql)['data']->rowCount();
		 return $rowsNum;
	 }
 }