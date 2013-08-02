<?PHP


const API_VERSION = "v2";
const API_ENDPOINT = "api.mailgun.net";
const API_USER = "api";
const SDK_VERSION = "0.1";
const SDK_USER_AGENT = "mailgun-sdk-php";
const DEFAULT_TIME_ZONE = "UTC";

//Common Exception Messages

const EXCEPTION_INVALID_CREDENTIALS = "Your credentials are incorrect.";
const EXCEPTION_GENERIC_HTTP_ERROR = "An HTTP Error has occurred! Check your network connection and try again.";
const EXCEPTION_MISSING_REQUIRED_MIME_PARAMETERS = "The parameters passed to the API were invalid. This might be a bug! Notify support@mailgun.com.";
const EXCEPTION_MISSING_ENDPOINT = "The endpoint you've tried to access does not exist. This might be a bug! Notify support@mailgun.com.";

?>
