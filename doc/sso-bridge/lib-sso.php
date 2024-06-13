<?php /** @noinspection PhpMissingFieldTypeInspection */

if( !\filter_var( \ini_get('allow_url_fopen'), \FILTER_VALIDATE_BOOLEAN ) ) {
    die("Sorry, this server has a misconfiguration which blocks SSO from working... Please contact the operator with errorCode 1:allow_url_fopen disabled");
}

$config = parse_ini_file("config.ini.php");
define("API_KEY",$config["API_KEY"]);

const SSO_PORTAL = "https://apps.pm2etml.ch/auth/";
const SESSION_SSO_KEY = "sso_bridge_correlation_id";

//
switch (session_status())
{
    case PHP_SESSION_DISABLED   :
        die("Session must be enabled for SSO bridge");

    case PHP_SESSION_NONE:
        session_start();
        break;
}

/*
 * Start SSO Login
 *
 * @param $cid CorrelationId (ideally generated by #generateCorrelationId
 * @param $apiKey A token for API access (must be asked to maintainer)
 * @param $customRedirectParameters Add parameters that will be given back to callback call (callback.php?param1=1&param2=2 ...)
 */
function InitiateSSOLogin(string $cid,array $customRedirectParameters=[],string $customCallbackURI = NULL)
{
    //Auto build redirect URI if necessary
    $redirectUri=$customCallbackURI??
        "http" . ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 's' : '') . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/callback.php";

    $params="";
    foreach ($customRedirectParameters as $name =>$value)
    {
        $params.=($params==""?"?":"&").$name."=".$value;
    }
    $redirectUri .= $params;

    $SSO_URL= SSO_PORTAL . "redirect?". "correlationId=" . $cid."&redirectUri=".urlencode($redirectUri);

    //Redirect to SSO Login
    header("Location: $SSO_URL");
}

/*
 * Try to get a well-formed correlationId from API... If not, fallback to own generation
 */
function GenerateCorrelationId(string $apiKey,bool $storeInSession=true)
{
    $ssoCorrelationId=@json_decode(@file_get_contents(SSO_PORTAL."bridge/cid?token=".$apiKey),true)["correlationId"];
    if($ssoCorrelationId=="")
    {
        try {
            $randomBytes = random_bytes(32);
        } catch (Exception $e) {
            die("Cannot generate valid correlationId");
        }
        $ssoCorrelationId = bin2hex($randomBytes);
    }

    if($storeInSession)
    {
        $_SESSION[SESSION_SSO_KEY]=$ssoCorrelationId;
    }

    return $ssoCorrelationId;
}

/*
 * @return a #LoginInfo instance
 */
function RetrieveSSOLoginInfos(string $token, string $correlationId) : SSOLoginInfo
{
    $ssoResult = file_get_contents(SSO_PORTAL."bridge/check?token=".$token."&correlationId=".$correlationId);
    $loginInfos=json_decode($ssoResult,true);

    $result = new SSOLoginInfo();
    if(!array_key_exists("error",$loginInfos))
    {
        $result->username=$loginInfos["username"];
        $result->email=$loginInfos["email"];
    }
    else{
        $result->error=$loginInfos["error"];
    }

    return $result;
}

class SSOLoginInfo
{
    public $email;
    public $username;
    public $error="";

    function IsSuccess(): bool
    {
        return $this->error=="";
    }
}
