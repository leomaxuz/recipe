<?php
//Create recipe
$app->post('/recipe', function ($request, $response) {
   if($_SESSION['auth_id']) {
       try{
           $con = $this->db;
           $user_id = $_SESSION['auth_id']; //THE USER ID
           $sql = "INSERT INTO recipe(`user_id`, `name`, `description`, `image_link`) VALUES (:user_id,:name,:description,:image_link)";
           $pre  = $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
           $values = array(
           ':user_id' => $user_id,
           ':name' => $request->getParam('name'),
           ':description' => $request->getParam('description'),
           ':image_link' => $request->getParam('image_link'),
           );
           $result = $pre->execute($values);
           return $response->withJson(array('status' => 'Recipe Created'),200);
           
       }
       catch(\Exception $ex){
           return $response->withJson(array('error' => $ex->getMessage()),422);
       }
   } else {
       return $response->withJson(array('error' => 'Please log in to add a recipe'),422);
   }
   
});

//View recipe
$app->get('/recipe/{id}', function ($request,$response) {
   try{
       $id	= $request->getAttribute('id');
       $con = $this->db;
       $sql = "SELECT * FROM recipe WHERE id = :id";
       $pre	= $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
       $values = array(
       ':id' => $id);
       $pre->execute($values);
       $result = $pre->fetch();
       if($result){
           return $response->withJson(array('status' => 'true','result'=> $result),200);
       }else{
           return $response->withJson(array('status' => 'Recipe Not Found'),422);
       }
      
   }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }
   
});

//View recipes
$app->get('/recipes', function ($request,$response) {
   try{
       $con = $this->db;
       $sql = "SELECT * FROM recipe";
       $result = null;
       foreach ($con->query($sql) as $row) {
           $result[] = $row;
       }
       if($result){
           return $response->withJson(array('status' => 'true','result'=>$result),200);
       }else{
           return $response->withJson(array('status' => 'Recipes Not Found'),422);
       }
              
   }
   catch(\Exception $ex){
       return $response->withJson(array('error' => $ex->getMessage()),422);
   }
   
});

//Update recipe
$app->put('/recipe/{id}', function ($request,$response) {
   if($_SESSION['auth_id']) {
	   try{
		   $id	= $request->getAttribute('id');
		   $user_id = $_SESSION['auth_id']; //THE USER ID
		   $con = $this->db;
		   $sql = "UPDATE recipe SET name=:name,description=:description,image_link=:image_link WHERE id = :id, user_id=:user_id";
		   $pre	= $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   $values = array(
		   ':name' => $request->getParam('name'),
		   ':description' => $request->getParam('description'),
		   ':image_link' => $request->getParam('image_link'),
		   ':id' => $id,
		   ':user_id' => $user_id
		   );
		   $result =  $pre->execute($values);
		   if($result){
			   return $response->withJson(array('status' => 'Recipe Updated'),200);
		   }else{
			   return $response->withJson(array('status' => 'Recipe Not Found'),422);
		   }
				  
	   }
	   catch(\Exception $ex){
		   return $response->withJson(array('error' => $ex->getMessage()),422);
	   }
   } else {
       return $response->withJson(array('error' => 'Please log in to edit a recipe'),422);
   }
   
});

//Delete recipe
$app->delete('/recipe/{id}', function ($request,$response) {
   if($_SESSION['auth_id']) {
	   try{
		   $id	= $request->getAttribute('id');
		   $user_id = $_SESSION['auth_id'];
		   $con = $this->db;
		   $sql = "DELETE FROM recipe WHERE id = :id, user_id=:user_id";
		   $pre	= $con->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		   $values = array(
		   ':id' => $id,
		   ':user_id' => $user_id
		   );
		   $result = $pre->execute($values);
		   if($result){
			   return $response->withJson(array('status' => 'Recipe Deleted'),200);
		   }else{
			   return $response->withJson(array('status' => 'Recipe Not Found'),422);
		   }
		  
	   }
	   catch(\Exception $ex){
		   return $response->withJson(array('error' => $ex->getMessage()),422);
	   }
   } else {
       return $response->withJson(array('error' => 'Please log in to delete a recipe'),422);
   }
   
});