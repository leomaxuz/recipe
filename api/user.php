<?php
//Sign up
$app->post('/signup', function ($request, $response) {
   
   try{
       $con = $this->db;
       $sql = "INSERT INTO `users`(`username`, `email`,`password`) VALUES (:username,:email,:password)";
       $pre	= $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $values = array(
       ':username' => $request->getParam('username'),
       ':email' => $request->getParam('email'),
		//Using hash for password encryption
       'password' => password_hash($request->getParam('password'),PASSWORD_DEFAULT)
       );
       $result = $pre->execute($values);
       return $response->withJson(array('status' => 'User Created'),200);
       
   }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }
   
});

//Log in
$app->get('/login', function ($request, $response) {
   try{
       $username = $request->getParam('username');
	   $password = $request->getParam('password');
       $con = $this->db;
       $sql = "SELECT * FROM users WHERE username = :username";
       $pre  = $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $values = array(':username' => $username);
       $pre->execute($values);
       $result = $pre->fetch();
	   $hash = $result['password'];

		if (password_verify($password, $hash)) {
			$_SESSION['auth_id'] = $result['id'];
			return $response->withJson(array('status' => 'Signed in'),200);	
		}else{
			return $response->withJson(array('status' => 'Invalid password.'),422);
       }
      
   }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }
   
});

//Sign out
$app->get('/signout', function ($request, $response) {
   try{
       $_SESSION['auth_id'] = "";
		return $response->withJson(array('status' => 'Sign out'),422);
   }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }
   
});

//View user
$app->get('/user/{id}', function ($request,$response) {
   try{
       $id	= $request->getAttribute('id');
       $con = $this->db;
       $sql = "SELECT * FROM users WHERE id = :id";
       $pre	= $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $values = array(
       ':id' => $id);
       $pre->execute($values);
       $result = $pre->fetch();
       if($result){
           return $response->withJson(array('status' => 'true','result'=> $result),200);
       }else{
           return $response->withJson(array('status' => 'User Not Found'),422);
       }
      
   }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }
   
});

//View users
$app->get('/users', function ($request,$response) {
   try{
       $con = $this->db;
       $sql = "SELECT * FROM users";
       $result = null;
       foreach ($con->query($sql) as $row) {
           $result[] = $row;
       }
       if($result){
           return $response->withJson(array('status' => 'true','result'=>$result),200);
       }else{
           return $response->withJson(array('status' => 'Users Not Found'),422);
       }
              
   }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }
   
});

//Update user
$app->put('/user', function ($request,$response) {
   if($_SESSION['auth_id']) {
	   try{
		   $id	= $_SESSION['auth_id'];
		   $con = $this->db;
		   $sql = "UPDATE users SET username=:username,email=:email,password=:password WHERE id = :id";
		   $pre	= $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   $values = array(
		   ':username' => $request->getParam('username'),
		   ':email' => $request->getParam('email'),
		   ':password' => password_hash($request->getParam('password'),PASSWORD_DEFAULT),
		   ':id' => $id
		   );
		   $result =  $pre->execute($values);
		   if($result){
			   return $response->withJson(array('status' => 'User Updated'),200);
		   }else{
			   return $response->withJson(array('status' => 'User Not Found'),422);
		   }
				  
	   }
	   catch(\Exception $ex){
		   return $response->withJson(array('error' => $ex->getMessage()),422);
		}
   } else {
       return $response->withJson(array('error' => 'Please log in to add a user'),422);
   }
   
});

//Delete user
$app->delete('/user', function ($request,$response) {
   if($_SESSION['auth_id']) {
	   try{
		   $id	= $_SESSION['auth_id'];
		   $con = $this->db;
		   $sql = "DELETE FROM users WHERE id = :id";
		   $pre	= $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   $values = array(
		   ':id' => $id);
		   $result = $pre->execute($values);
		   if($result){
			   return $response->withJson(array('status' => 'User Deleted'),200);
		   }else{
			   return $response->withJson(array('status' => 'User Not Found'),422);
		   }
		  
	   }
	   catch(\Exception $ex){
		   return $response->withJson(array('error' => $ex->getMessage()),422);
	   }
   } else {
       return $response->withJson(array('error' => 'Please log in to delete a user'),422);
   }
   
});