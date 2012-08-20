<?
/* =======================================================================
  
 ifsnow's multi cookie class

 See cookie folder infrequently. We live in cookie flood.
 This function is class to use several cookie in single cookie file.

 Desire to aid to your learning.
 ifsnow is korean phper. Is sorry to be unskillful to English. *^^*;; 
 
 Maker : ifsnow ( Mail : lsm@ebeecomm.com, ICQ : 71930801 )

========================================================================= */

/////////////////////////////////// Reference //////////////////////////////
//
//  1. BOOL MultiCookie($CID,$Debug,$Expire,$Domain,$Secure)
//     class Initialization function. $CID is cookie name to use and $Debug is
//     parameter for debug check. Remainder parameter are same with parameter 
//     of setcookie function.
//     Ex ) $MultiCookie = new MultiCookie("Login");
//
//  2. BOOL Create()
//     Function that create cookie. Parameter of "key=>value" form.
//     Is stored with "1=>a:2=>b:3=>c:4=>d" in file.
//     Ex ) MultiCookie->Create("id=>ifsnow","passwd=>12345","level=>99");
//
//  3. Destroy($Set)
//     Erase cookie and initialize interior variables. If $Set is TRUE,
//     erase cookie.
//     Ex ) $MultiCookie->Destroy();
//
//  4. Parsing()
//     Because reading cookie, analyze and store in array.
//     Ex ) $MultiCookie->Parsing();
//
//  5. String Read($Key)
//     Return the value of an array.
//     Ex ) $id = $MultiCookie->Read("id");
//
//  6. BOOL AddNew()
//     Add new value to existent cookie. 
//     Ex ) $MultiCookie->AddNew("email=>lsm@ebeecomm.com","icq=>71930801");
//
//  7. BOOL Modify()
//     Modify value of existing cookie.
//     Ex ) $MultiCookie->Modify("passwd=>34567","level=>100");
//
//  8. BOOL Delete()
//     Erase value of existing cookie.
//     Ex ) $MultiCookie->Delete("email","icq");
//
//  9. BOOL IsExist($Key)
//     Return TRUE if a $Key exists in an array;
//     Ex ) $MultiCookie->IsExist("id")==TRUE ? "O":"X";
//
// 10. Info()
//     Display information of cookie.
//     Ex ) $MultiCookie->Info();
//
/////////////////////////////////////////////////////////////////////////////

class MultiCookie
{
	var $CID, $Expire, $Path, $Domain, $Secure, $Debug;
	var $CookieArray, $KeyArray, $CookiesCount, $ParsingCheck;

	function MultiCookie($CID, $Debug=FALSE, $Expire=0, $Path="/", $Domain="", $Secure=0)
	{
		$this->Debug = $Debug;
		$this->CID = $CID;
		$this->Expire = $Expire;
		$this->Path = $Path;
		$this->Domain = $Domain;
		$this->Secure = $Secure;
		$this->ParsingCheck=FALSE;
		$this->KeyArray = array();
	}

	function Create()
	{
		if (func_num_args()==0) {
			return $this->DebugView("[Error] Input cookie value to create more than one.");
		}
		else {
			$Temp = func_get_args();
			SetCookie($this->CID,join(":",$Temp),$this->Expire,$this->Path,$this->Domain,$this->Secure);
			return TRUE;
		}	
	}

	function Destroy($Set=TRUE)
	{
		empty($CookieArray);
		empty($KeyArray);
		$this->ParsingCheck=FALSE;
		$this->CookiesCount=0;
		if ($Set)
			SetCookie($this->CID,"",0,$this->Path,$this->Domain,$this->Secure);
	}

	function Parsing()
	{
		global $HTTP_COOKIE_VARS;
		
		if (($SavedCookies = $HTTP_COOKIE_VARS[$this->CID])) {
			$TempCookies = explode(":",$SavedCookies);
			$this->CookiesCount = count($TempCookies);
		
			if ($this->CookiesCount>0) {
			for ($i = 0; $i < $this->CookiesCount; $i++) {
					$Temp = split("=>",$TempCookies[$i],2);
					$this->CookieArray[$Temp[0]]=$Temp[1];
					array_push($this->KeyArray,$Temp[0]);
				}
				$this->ParsingCheck=TRUE;
				return TRUE;
			}
			else return FALSE;
		}
	}

	function Read($Key)
	{
		if ($this->ParsingCheck==FALSE) $this->Parsing();
		return $this->CookieArray[$Key];
	}

	function AddNew()
	{
		if (($argNum=func_num_args())==0) {
			return $this->DebugView("[Error] Input cookie value to add more than one.");
		}
		else {
			$TempArgs = func_get_args();
			$ExistCheck = FALSE;
			$TempString = "";

			for ($i = 0 ; $i < $argNum ; $i++)
			{
				$Temp = split("=>",$TempArgs[$i]);
				if (!$this->IsExist($Temp[0]) && is_array($Temp)) {
					$TempString.=$TempArgs[$i].":";
					$ExistCheck=TRUE;
				}
			}

			if ($ExistCheck) {
				if ($this->CookiesCount>0)
					$TempString = $this->Extract().":".eregi_replace(":$","",$TempString);
				else
					$TempString = eregi_replace(":$","",$TempString);

				SetCookie($this->CID,$TempString,$this->Expire,$this->Path,$this->Domain,$this->Secure);
				$this->Destroy(FALSE);
				return TRUE;
			}
			else return FALSE;
		}
	}

	function Modify()
	{
		if ($this->ParsingCheck==FALSE) $this->Parsing();
		
		if (($argNum=func_num_args())==0) {
			return $this->DebugView("[Error] Input cookie value to modify more than one.");
		}
		else {
			$TempArgs = func_get_args();
			$ExistCheck = FALSE;
			for ($i = 0 ; $i < $argNum ; $i++)
			{
				$Temp = split("=>",$TempArgs[$i]);
				if ($this->IsExist($Temp[0]) && is_array($Temp)) {
					$this->CookieArray[$Temp[0]]=$Temp[1];
					$ExistCheck=TRUE;
				}
			}
			if ($ExistCheck) {
				SetCookie($this->CID,$this->Extract(),$this->Expire,$this->Path,$this->Domain,$this->Secure);
				$this->Destroy(FALSE);
				return TRUE;
			}
			else return FALSE;
		}
	}

	function Delete()
	{
		if ($this->ParsingCheck==FALSE) $this->Parsing();

		if (($argNum=func_num_args())==0) {
			return $this->DebugView("[Error] Input cookie value to delete more than one.");
		}
		else {
			$TempArgs = func_get_args();
			$ExistCheck = FALSE;
			for ($i = 0 ; $i < $argNum ; $i++)
			{
				if ($this->IsExist($TempArgs[$i])) {
					$this->CookieArray[$TempArgs[$i]]="";
					$ExistCheck=TRUE;
				}
			}
			if ($ExistCheck) {
				SetCookie($this->CID,$this->Extract(),$this->Expire,$this->Path,$this->Domain,$this->Secure);
				$this->Destroy(FALSE);
				return TRUE;
			}
			else return TRUE;
		}
	}

	function IsExist($Key)
	{
		if ($this->ParsingCheck==FALSE) $this->Parsing();
		return in_array($Key,$this->KeyArray);
	}

	function Extract()
	{
		$Temp="";
		foreach ($this->CookieArray as $key=>$value) {
			if ($value)
				$Temp.="$key=>$value:";
		}
		return eregi_replace(":$","",$Temp);
	}

	function Info()
	{
		if ($this->ParsingCheck==FALSE) $this->Parsing();
		if ($this->CookiesCount>0) {
			echo "cookie name : $this->CID<br>"
			."cookie expire : ".($this->Expire==0 ? "No":$this->Expire."seconds")."<br>"
			."cookie path : ".($this->Path ? $this->Path:"No")."<br>"
			."cookie domain : ".($this->Domain ? $this->Domain:"No")."<br>"
			."cookie secure : ".($this->Secure==1 ? "Use":"Not Use")."<br>"
			."============= $this->CookiesCount cookies exists =============<br>";

			foreach ($this->CookieArray as $key=>$value) {
				echo "key : $key => value : $value<br>";
			}
		}
		else {
			echo "There is no cookie that is stored in $this->CID name<br>";
		}
	}

	function DebugView($Msg)
	{
		if ($this->Debug) {
			echo $Msg."<br>";
			return FALSE;
		}
	}
}
?>
	

