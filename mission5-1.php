<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission5-1</title>
    </head>
    
    <body>
        <?php
            //データベースへの接続
            $dsn = "mysql:dbname='データベース名';host=localhost";
            $user = "'ユーザー名'";
            $password = "'パスワード'";
            $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
            //テーブル作成
            $sql = "CREATE TABLE IF NOT EXISTS mission5_1"
            ." ("
            ."id INT AUTO_INCREMENT PRIMARY KEY,"
            ."name char(32),"
            ."comment TEXT,"
            ."pass TEXT"
            .");";
            $stmt = $pdo->query($sql);
            
            
            
            //書き込みボタンが押されたら
            if(isset($_POST["submit"])){
                
                //編集の場合
                if(!empty($_POST["check"])){
                    
                    //データ取得
                    $id = $_POST["check"]; //id
                    $name = $_POST["name"]; //名前
                    $comment = $_POST["comment"]; //コメント
                    $pass = $_POST["password"];  //パスワード
                    
                    //編集
                    $sql = 'UPDATE mission5_1 SET name=:name,comment=:comment,pass=:pass WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    
                    $stmt->execute(); //実行
                    
                    //データ取得して表示
                    $sql = 'SELECT * FROM mission5_1';
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                    foreach ($results as $row){
                        echo $row['id'].',';
                        echo $row['name'].',';
                        echo $row['comment'].',';
                        echo $row['pass'].'<br>';
                    echo "<hr>";
                    }
                
                
                //新規書き込みの場合
                }else{
                    
                    //入力
                    $sql = $pdo -> prepare("INSERT INTO mission5_1 (name, comment, pass) VALUES (:name, :comment, :pass)");
                    
                    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                    
                    $name = $_POST["name"]; //名前
                    $comment = $_POST["comment"]; //コメント
                    $pass = $_POST["password"]; //パスワード
                    
                    
                    //名前とコメントとパスワードが空でなければ
                    if(!empty($name) && !empty($comment) && !empty($pass)){
                        
                        $sql -> execute(); //実行
                    }
                
                    //データ取得して表示
                    $sql = 'SELECT * FROM mission5_1';
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                    foreach ($results as $row){
                        echo $row['id'].',';
                        echo $row['name'].',';
                        echo $row['comment'].',';
                        echo $row['pass'].'<br>';
                    echo "<hr>";
                    }
                }
            }
            
            //削除ボタンが押されたら
            if(isset($_POST["subdel"])){
                
                //データ取得
                $id = intval($_POST["delete"]);
                $sql = 'SELECT * FROM mission5_1 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                
                $stmt->execute(); //データ取得実行
                $results = $stmt->fetchAll();
                
                
                
                foreach($results as $row){
                    
                    //パスワードが合致したら
                    if($row['pass'] == $_POST["delpass"]){
                        
                        //削除
                        $id = intval($_POST["delete"]);
                        $sql = 'delete from mission5_1 where id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        
                        $stmt->execute(); //削除実行
                    }
                
                }
                
                //データ取得して表示
                $sql = 'SELECT * FROM mission5_1';
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                    foreach ($results as $row){
                        echo $row['id'].',';
                        echo $row['name'].',';
                        echo $row['comment'].',';
                        echo $row['pass'].'<br>';
                    echo "<hr>";
                    }
            }
            
            //編集ボタンが押されたら
            if(isset($_POST["subed"])){
                
                //データ取得
                $id = intval($_POST["edit"]);
                $sql = 'SELECT * FROM mission5_1 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                
                $stmt->execute(); //データ取得実行
                $results = $stmt->fetchAll();
                
                $ednum = "";
                $edname = "";
                $edcom = "";
                $edpass = "";
                
                
                foreach($results as $row){
                    
                    //パスワードが合致したら
                    if($row['pass'] == $_POST["edpass"]){
                        
                        //idが一致する要素を取得
                        if($row['id'] == $_POST["edit"]){
                            
                            $ednum = $row['id'];
                            $edname = $row['name'];
                            $edcom = $row['comment'];
                            $edpass = $row['pass']; //入力フォームに表示する用
                        
                            break;
                        
                        }
                    }
                }
                
                //データ取得して表示
                $sql = 'SELECT * FROM mission5_1';
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                    foreach ($results as $row){
                        echo $row['id'].',';
                        echo $row['name'].',';
                        echo $row['comment'].',';
                        echo $row['pass'].'<br>';
                    echo "<hr>";
                    }
            }
        ?>
        
        <form action = "#" method = "post">
            
            <!--入力フォーム-->
            <input type = "text" name = "name" value = "<?php if(isset($edname)){echo $edname;} ?>"><br>
            <input type = "text" name = "comment" value = "<?php if(isset($edcom)){echo $edcom;} ?>"><br>
            <input type = "text" name = "password" value = "<?php if(isset($edpass)){echo $edpass;} ?>"><br>
            <input type = "hidden" name = "check" value = "<?php if(isset($ednum)){echo $ednum;} ?>">
            <input type = "submit" name = "submit"><br>
            
            <!--削除フォーム-->
            <input type = "number" name = "delete"><br>
            <input type = "text" name = "delpass"><br>
            <input type = "submit" name = "subdel"><br>
            
            <!--編集フォーム-->
            <input type = "number" name = "edit"><br>
            <input type = "text" name = "edpass"><br>
            <input type = "submit" name = "subed"><br>
            
        </form>
    </body>
</html>