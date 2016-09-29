<?php
header("Content-Type: text/html;charset=utf-8");

/**
* 功能：连接数据库
* @author gaoqing
* 2015年7月17日
*/

/**
 * 得到总记录数
 * @author gaoqing
 * 2015年7月22日
 * @param string $DSN 连接 MYSQL 的 DSN 信息
 * @param string $user 连接 MYSQL 的用户名
 * @param string $pass 连接 MYSQL 的密码
 * @return int 总记录数
 */
function getTotalNums($DSN, $user, $pass){
    //得到 PDO 对象
    $PDO = new PDO($DSN, $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    
    $sql = "SELECT COUNT(1) FROM tb_auth_rule";
    $PDOStatement = $PDO->prepare($sql);
    
    $PDOStatement->execute();
    
    $result = $PDOStatement->fetch(PDO::FETCH_NUM);
    
    return empty($result)? 0 : $result[0];
}

/**
 * 得到指定分页中的内容
 * @author gaoqing
 * 2015年7月17日
 * @param string $DSN 连接 MYSQL 的 DSN 信息
 * @param string $user 连接 MYSQL 的用户名
 * @param string $pass 连接 MYSQL 的密码
 * @param int $start 分页开始位置数
 * @param int $rows 每页显示的行数
 * @return array 得到分页内容的数据集
 */
function getPagesList($DSN, $user, $pass, $start, $rows){
    
$result_arr = array();

try {
    //实例化 PDO 
    $options = array();
    $PDO = new PDO($DSN, $user, $pass, $options);
    
    //设置显示错误信息
    $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $PDO->exec("SET NAMES 'utf8' ");
    
    //得到 PDOStatement 对象
    $sql = "SELECT * FROM tb_auth_rule LIMIT ?, ? ";
    $PDOStatement = $PDO->prepare($sql);
    
    $PDOStatement->bindParam(1, $start, PDO::PARAM_INT);
    $PDOStatement->bindParam(2, $rows, PDO::PARAM_INT);
    
    //执行查询操作
    $PDOStatement->execute();
    $result_arr = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $pe) {
   echo  $pe->getMessage();
}
return $result_arr;
}

?>