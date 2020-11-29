<?php
//DEBBUG ONLY, Remove after
// ini_set('display_errors', 1);


// TODO: Take care of form submission

//4*. It returns proper info in JSON    [checked]
//a. What is AJAX??? I dont need to refresh the page every time that I put a feedback
//b. What is JSON???
//c. How build JSON in PHP???

header('Access-Control-Allow-Origin:*');
header('Content-Type: application/json; charset=UFT-8');

$results = [];
$visitor_name = '';
$visitor_email = '';
$visitor_message = '';
$visitor_topic = '';

//1. Check the submission out - Validate the data
// $_POST['firstname']

if (isset($_POST{'firstname'})) {
    $visitor_name = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
}

if (isset($_POST{'lastname'})) {
    $visitor_name .= ' '.filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
}

if (isset($_POST{'email'})) {
    $visitor_email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
}

if (isset($_POST['message'])) {
    $visitor_message = filter_var(htmlspecialchars($_POST['message']), FILTER_SANITIZE_STRING);
}

if (isset($_POST{'Topics'})) {
    $visitor_topic = $_POST['Topics'];
}



/////
// if ($visitor_name == "" || $visitor_email == "") {
//     $results['message'] = sprintf('We are sorry but the email did not go through.');
// }
/////

$results['name'] = $visitor_name;
$results['message'] = $visitor_message;
$results['Topics'] = $visitor_topic;


//2. Prepare the email (How we want the email looks like)
$email_subject = 'Inquiry from Portfolio Site';
//$email_topic = sprintf('This email is for a %s', $visitor_topic);
$email_recipient = '';

if ($visitor_topic == 'Freelance') {
    $email_recipient = 'FreelanceSolicitation@gmail.com';
} else {
    $email_recipient = 'CompanyContracts@gmail.com';
}
//$email_recipient = 'andresgallod.i@gmail.com'; //Your email, or AKA, "To" email
$email_message = sprintf('Name: %s, Email: %s, Topic: %s, Message: %s', $visitor_name, $visitor_email, $visitor_topic, $visitor_message);

// Make sure you run the code in PHP 7.4+
//Otherwise you would need to make $email_headers as string http://www.php.net/manual/en/function.mail.php
$email_headers = array(
    //Best practice, but it may need you to have a mail set up in noreply@yourdomain.ca
    // 'From'=>'noreply@yourdomain.ca',
    // 'Reply-to'=>$visitor_email,

    //You can still use it, if above it too much work
    'From'=>$visitor_email
);

//3. Send out the email
$email_result = mail($email_recipient, $email_subject, $email_message, $email_headers);

if ($email_result) {
    $results['message'] = sprintf('Thank you for contacting us, %s. You will get a reply within 24 hours.', $visitor_name);
}

if (empty($_POST['name'] || $_POST['email'])) {
    $results['message'] = sprintf('There are empty fields that are required');

    die('There are empty fields');
} else {
    $results['message'] = sprintf('We are sorry %s, but the email did not send, please try again.', $visitor_name);
}

// if (empty($_POST['email'])) {
//     $results['message'] = sprintf('This field is required');

//     die('There are empty fields');
// }

//
// if ($email_result) {
//     $results['message'] = sprintf('Thank you for contacting us, %s. You will get a reply within 48 hours!', $visitor_name);
// }

// if (empty($visitor_name || $visitor_email)) {
//     $results['message'] = sprintf('Field requierd');
// //die('Please fill all required fields!');
// } else {
//     $results['message'] = sprintf('We are sorry %s, but the email did not send, please try again.', $visitor_name);
// }
// //

echo json_encode($results);
