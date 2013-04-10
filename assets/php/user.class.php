<?php
require_once ($_SERVER['DOCUMENT_ROOT']."/assets/php/config.inc.php");
require_once ($_SERVER['DOCUMENT_ROOT']."/assets/php/database.class.php");

class User {
	 
	//encryption
    var $encrypt = false;       //set to true to use md5 encryption for the password
	 
	//Constructor
	function __construct() {
		$this->db = new Database(DB_SERVER_CASE, DB_USER_CASE, DB_PASS_CASE, DB_DATABASE_CASE);
		$this->db->connect(true);
		mysql_query("set names utf8");
	}
	
	//Destructor
	function __destruct() {
		$this->db->close();
	}
	
	/**************************************************************************************************************************************************/
	/* Function: 		login($email, $password)
	/* Return:			null
	/* Description:		
	/* Revision:		-
	/*					
	/* 
	/**************************************************************************************************************************************************/
    function login($email, $password){
        //check if encryption is used
        if($this->encrypt == true)	$password = md5($password);
		
		//connect to DB
		$sql_user = "SELECT * FROM ".USER_TABLE_USER." WHERE Email = '".$email."' AND Password = '".$password."' ".$conditions;
		$row_user = $this->db->query($sql_user);
		$result = $this->db->fetch_array($row_user);
		
		if(isset($result['UserID'])) {
			if($result['UserID'] !="" && $result['Password'] !="") {
				//register sessions
                $_SESSION['loggedin'] = $row[$this->pass_column];
                //userlevel session is optional. Use it if you have different user levels
                $_SESSION['userlevel'] = $row[$this->user_level];
                return true;
			} else {
				session_destroy();
				return false;
			}
				
		}
		else {
			return false;
		}
    }
	
	/**************************************************************************************************************************************************/
	/* Function: 		logout()
	/* Return:			null
	/* Description:		
	/* Revision:		-
	/*					
	/* 
	/**************************************************************************************************************************************************/
	function logout(){
		$_SESSION = array(); //destroy all of the session variables
        session_destroy();
    }
	
	/**************************************************************************************************************************************************/
	/* Function: 		createPassword()
	/* Return:			null
	/* Description:		create random password with 8 alphanumerical characters
	/* Revision:		-
	/*					
	/* 
	/**************************************************************************************************************************************************/
    function createPassword() {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
        srand((double)microtime()*1000000);
        $i = 0;
        $pass = '' ;
        while ($i <= 7) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }
	
	/**************************************************************************************************************************************************/
	/* Function: 		resetPassword()
	/* Return:			null
	/* Description:		create random password with 8 alphanumerical characters
	/* Revision:		-
	/*					
	/* 
	/**************************************************************************************************************************************************/
    function resetPassword($email){
        //generate new password
        $newpassword = $this->createPassword();

        //check if encryption is used
        if($this->encrypt == true){
            $newpassword_db = md5($newpassword);
        }else{
            $newpassword_db = $newpassword;
        }
 
        //update database with new password
        $qry = "UPDATE ".$this->user_table." SET ".$this->pass_column."='".$newpassword_db."' WHERE ".$this->user_column."='".stripslashes($username)."'";
        $result = mysql_query($qry) or die(mysql_error());
 
        $to = stripslashes($username);
        //some injection protection
        $illegals=array("%0A","%0D","%0a","%0d","bcc:","Content-Type","BCC:","Bcc:","Cc:","CC:","TO:","To:","cc:","to:");
        $to = str_replace($illegals, "", $to);
        $getemail = explode("@",$to);
 
        //send only if there is one email
        if(sizeof($getemail) > 2){
            return false;
        }else{
            //send email
            $from = $_SERVER['SERVER_NAME'];
            $subject = "Password Reset: ".$_SERVER['SERVER_NAME'];
            $msg = "
 
Your new password is: ".$newpassword."
 
";
 
            //now we need to set mail headers
            $headers = "MIME-Version: 1.0 rn" ;
            $headers .= "Content-Type: text/html; \r\n" ;
            $headers .= "From: $from  \r\n" ;
 
            //now we are ready to send mail
            $sent = mail($to, $subject, $msg, $headers);
            if($sent){
                return true;
            }else{
                return false;
            }
        }
    }
	
	/**************************************************************************************************************************************************/
	/* Function: 		createNewUser()
	/* Return:			null
	/* Description:		
	/* Revision:		-
	/*					
	/* 
	/**************************************************************************************************************************************************/
	function createNewUser($email, $password, $description) {
		$sql_case = "INSERT INTO ".USER_TABLE_USER." (Email, Password, Description, IsEnabled, AccountStatus) VALUES('".$email."', '".$password."', '".$description."', '0', '".$this->createPassword()."')";
		$row_case = $this->db->query($sql_case);
		
		$sql_user = "SELECT UserID FROM ".USER_TABLE_USER." WHERE Email = '".$email."' LIMIT 1 ";
		$row_user = $this->db->query($sql_user);
		$result = $this->db->fetch_array($row_user);
		
		//$sql_case = "INSERT INTO ".USER_TABLE_DETAIL." (UserID, FirstName, LastName, DOB) VALUES('".$email."', '".$password."', '".$description."', '0', '".$this->createPassword()."')";
		//$row_case = $this->db->query($sql_case);
	
	}
}
?>