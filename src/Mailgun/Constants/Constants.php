<?PHP

const API_USER = "api";
const SDK_VERSION = "1.11";
const SDK_USER_AGENT = "mailgun-sdk-php";
const RECIPIENT_COUNT_LIMIT = 1000;
const CAMPAIGN_ID_LIMIT = 3;
const TAG_LIMIT = 3;

//Common Exception Messages
const EXCEPTION_INVALID_CREDENTIALS = "Your credentials are incorrect.";
const EXCEPTION_GENERIC_HTTP_ERROR = "An HTTP Error has occurred! Check your network connection and try again.";
const EXCEPTION_MISSING_REQUIRED_PARAMETERS = "The parameters passed to the API were invalid. This might be a bug! Notify support@mailgun.com.";
const EXCEPTION_MISSING_ENDPOINT = "The endpoint you've tried to access does not exist. This might be a bug! Notify support@mailgun.com.";
const TOO_MANY_RECIPIENTS = "You've exceeded the maximum recipient count (1,000) on the to field with autosend disabled.";
const INVALID_PARAMETER_NON_ARRAY = "The parameter you've passed in position 2 must be an array.";
const INVALID_PARAMETER_ATTACHMENT = "Attachments must be passed with an \"@\" preceding the file path. Web resources not supported.";
const INVALID_PARAMETER_INLINE = "Inline images must be passed with an \"@\" preceding the file path. Web resources not supported.";
const TOO_MANY_PARAMETERS_CAMPAIGNS = "You've exceeded the maximum (3) campaigns for a single message.";
const TOO_MANY_PARAMETERS_TAGS = "You've exceeded the maximum (3) tags for a single message.";
const TOO_MANY_PARAMETERS_RECIPIENT = "You've exceeded the maximum recipient count (1,000) on the to field with autosend disabled.";


?>
