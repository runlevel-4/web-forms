<?php
// Form input variables
$empFirstName = $_POST['empFirstName'];
$empLastName = $_POST['empLastName'];
$requesterEmail = $_POST['requesterEmail'];
$superFirstName = $_POST['superFirstName'];
$superLastName = $_POST['superLastName'];
$phone = $_POST['phone'];
$dept = $_POST['dept'];
$location = $_POST['location'];
$compName = $_POST['compName'];
$checkOptions = $_POST['checkOptions'];
$reasonSelect = $_POST['reasonSelect'];
$purchaseReason = $_POST['purchaseReason'];
$replaceReason = $_POST['replaceReason'];

// Converts Director's name into an email address
$strNameToEmail = $superFirstName . "." . $superLastName . "@organization.org";

// Combines the director first & last name and the employee first name and last name
// to be inserted into the email body
$strDirectorName = $superFirstName . " " . $superLastName;
$strEmpName = $empFirstName . " " . $empLastName;

// Create email headers
$to = "me@organization.org, support@organization.org, $strNameToEmail";
$email_subject = "Hardware/Software Request"; // Subject
$headers = "From: $requesterEmail \r\n"; // From: header
$headers .= "Reply-To: $requesterEmail \r\n"; // Reply-To: header

//Form validation (check to make sure the fields are filled out)
if (empty($empFirstName) || empty($empLastName) || empty($requesterEmail) || empty($superFirstName) || empty($superLastName) || empty($phone) || empty($dept) || empty($location)) {
	echo "Please ensure that all of the information is filled out.\r\n";
	echo "Redirecting...";
	header('Refresh: 4; URL=http://server-name/forms/swhwrequest/pcreq.html');
	return false;
}

$selectedOptions  = 'None';
if (isset($checkOptions) && is_array($checkOptions) && count($checkOptions) > 0){
    $selectedOptions = implode(', ' , $checkOptions);
}
else {
	echo "You did not select any hardware or software.";
	return false;
	echo "\n\nRedirecting...";
	header('Refresh: 4; URL=http://server-name/forms/swhwrequest/pcreq.html');
}

if (isset($reasonSelect) && $reasonSelect == "Blank") {
	echo "Please select an option.";
	return false;
	echo "\n\nRedirecting...";
	header('Refresh: 4; URL=http://server-name/forms/swhwrequest/pcreq.html');
}
if (isset($reasonSelect) && $reasonSelect == "Purchasing") {
	if (empty($purchaseReason)) {
		echo "Please fill out the reason for the purchase.";
		return false;
		echo "\n\nRedirecting...";
		header('Refresh: 4; URL=http://server-name/forms/swhwrequest/pcreq.html');
	}
}
if (isset($reasonSelect) && $reasonSelect == "Replacement") {
	if (empty($replaceReason)) {
		echo "Please fill out the reason for the replacement.";
		return false;
		echo "\n\nRedirecting...";
		header('Refresh: 4; URL=http://server-name/forms/swhwrequest/pcreq.html');
	}
}

// Email body content
$email_body = "Requester: $strEmpName\n\nRequester Email: $requesterEmail\n\nDirector Name: $strDirectorName\n\nPhone: $phone\n\nDepartment: $dept\n\nLocation: $location\n\nComputer Name: $compName\n\nHardware/Software: " . $selectedOptions . "\n\nReason For Request: $reasonSelect\n\nDescription: ";
$email_body .= $purchaseReason;
$email_body .= $replaceReason;

// Send email with headers and content
mail($to,$email_subject,$email_body,$headers);

//Protects against email injections
function IsInjected($str) {
	$injections = array('(\n+)',
		'(\r+)',
            	'(\t+)',
            	'(%0A+)',
            	'(%0D+)',
            	'(%08+)',
            	'(%09+)'
            	);
                
	$inject = join('|', $injections);
    	$inject = "/$inject/i";
     
    	if(preg_match($inject,$str))
    	{
		return true;
    	} else {
		return false;
	  }
}
 
if(IsInjected($requesterEmail)) {
	echo "Mail Injection Occured";
    	exit;
} else {
	echo "Thank you...Your request is being processed by the IT department.";
  }
?>
